<?php
    require_once __DIR__ . '/../includes/functions.php';

    $format = $_GET['format'] ?? 'xml';
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    $data = $id ? dohvatiPjesmuPoId($id) : dohvatiSvePjesme();

    if ($format === 'json') {
        header('Content-Type: application/json; charset=UTF-8');

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    header('Content-Type: application/xml; charset=UTF-8');

    $dom = new DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = true;

    $root = $dom->createElement($id ? 'pjesma' : 'pjesme');
    $dom->appendChild($root);

    function dodajPjesmuXML($dom, $parent, $p)
    {
        if ($parent->nodeName === 'pjesme') {
            $el = $dom->createElement('pjesma');
            $el->setAttribute('id', $p['id']);
            $parent->appendChild($el);
        } else {
            $el = $parent;
            $el->setAttribute('id', $p['id']);
        }

        $polja = [
            'naslov',
            'izvodjac',
            'album',
            'zanr',
            'godina',
            'trajanje',
            'ocjena',
            'cover',
            'opis'
        ];

        foreach ($polja as $polje) {
            $child = $dom->createElement($polje);
            $child->appendChild(
                $dom->createTextNode((string) ($p[$polje] ?? ''))
            );

            $el->appendChild($child);
        }
    }

    if ($id) {
        if ($data) {
            dodajPjesmuXML($dom, $root, $data);
        }
    } else {
        foreach ($data as $p) {
            dodajPjesmuXML($dom, $root, $p);
        }
    }

    echo $dom->saveXML();
?>