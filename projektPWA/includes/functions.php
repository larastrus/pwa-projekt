<?php
    require_once __DIR__ . '/db.php';

    function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

    function formatirajTrajanje($s){
        $s = (int)$s;
        return floor($s / 60) . ':' . str_pad($s % 60, 2, '0', STR_PAD_LEFT);
    }

    function isLoggedIn(){ return isset($_SESSION['user']); }

    function isAdmin()
    {
        return isset($_SESSION['user'])
            && is_array($_SESSION['user'])
            && ($_SESSION['user']['uloga'] ?? '') === 'admin';
    }

    function login($username, $password){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM korisnici WHERE korisnicko_ime=? AND lozinka=MD5(?) LIMIT 1");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    function registracija($username, $password){
        global $conn;
        $stmt = $conn->prepare("INSERT INTO korisnici (korisnicko_ime, lozinka, uloga) VALUES (?, MD5(?), 'korisnik')");
        $stmt->bind_param("ss", $username, $password);
        return $stmt->execute();
    }

    function dohvatiSvePjesme($pretraga='', $zanr='', $sort='ocjena'){
        global $conn;

        $sql = "SELECT * FROM pjesme WHERE 1";
        $types = "";
        $vals = [];

        if($pretraga != ''){
            $sql .= " AND (naslov LIKE ? OR izvodjac LIKE ? OR album LIKE ? OR zanr LIKE ?)";
            $q = "%$pretraga%";
            $vals = [$q, $q, $q, $q];
            $types = "ssss";
        }

        if($zanr != ''){
            $sql .= " AND zanr=?";
            $vals[] = $zanr;
            $types .= "s";
        }

        $sql .= $sort == 'godina' ? " ORDER BY godina DESC" : ($sort == 'naslov' ? " ORDER BY naslov ASC" : " ORDER BY ocjena DESC");

        $stmt = $conn->prepare($sql);
        if($vals) $stmt->bind_param($types, ...$vals);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    function dohvatiPjesmuPoId($id){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pjesme WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    function sviZanrovi(){
        global $conn;
        $r = $conn->query("SELECT DISTINCT zanr FROM pjesme WHERE zanr<>'' ORDER BY zanr");
        return array_column($r->fetch_all(MYSQLI_ASSOC), 'zanr');
    }

    function statistika(){
        global $conn;
        return $conn->query("SELECT COUNT(*) broj, COUNT(DISTINCT zanr) zanrovi, ROUND(AVG(ocjena),1) prosjek FROM pjesme")->fetch_assoc();
    }

    function topIzvodjac(){
        global $conn;
        return $conn->query("SELECT izvodjac, COUNT(*) broj FROM pjesme GROUP BY izvodjac ORDER BY broj DESC LIMIT 1")->fetch_assoc();
    }

    function dodajPjesmu($d){
        global $conn;
        $stmt = $conn->prepare("INSERT INTO pjesme (naslov,izvodjac,album,zanr,godina,trajanje,ocjena,cover,opis) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssiidss", $d['naslov'], $d['izvodjac'], $d['album'], $d['zanr'], $d['godina'], $d['trajanje'], $d['ocjena'], $d['cover'], $d['opis']);
        return $stmt->execute();
    }

    function azurirajPjesmu($id, $d){
        global $conn;
        $stmt = $conn->prepare("UPDATE pjesme SET naslov=?, izvodjac=?, album=?, zanr=?, godina=?, trajanje=?, ocjena=?, cover=?, opis=? WHERE id=?");
        $stmt->bind_param("ssssiidssi", $d['naslov'], $d['izvodjac'], $d['album'], $d['zanr'], $d['godina'], $d['trajanje'], $d['ocjena'], $d['cover'], $d['opis'], $id);
        return $stmt->execute();
    }

    function obrisiPjesmu($id){
        global $conn;
        $stmt = $conn->prepare("DELETE FROM pjesme WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    function itunesPretraga($upit, $limit=8){
        $url = "https://itunes.apple.com/search?term=" . urlencode($upit) . "&media=music&entity=song&limit=" . $limit;
        $json = @file_get_contents($url);
        if(!$json) return [];

        $data = json_decode($json, true);
        $rez = [];

        foreach($data['results'] ?? [] as $p){
            $rez[] = [
                'naslov' => $p['trackName'] ?? '',
                'izvodjac' => $p['artistName'] ?? '',
                'album' => $p['collectionName'] ?? '',
                'zanr' => $p['primaryGenreName'] ?? 'Nepoznato',
                'godina' => isset($p['releaseDate']) ? (int)substr($p['releaseDate'], 0, 4) : 0,
                'trajanje' => isset($p['trackTimeMillis']) ? round($p['trackTimeMillis'] / 1000) : 0,
                'ocjena' => 0,
                'cover' => isset($p['artworkUrl100']) ? str_replace('100x100bb', '600x600bb', $p['artworkUrl100']) : '',
                'opis' => 'Podaci su dohvaćeni putem iTunes Search API-ja.'
            ];
        }

        return $rez;
    }
?>