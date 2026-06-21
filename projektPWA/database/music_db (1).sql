CREATE DATABASE IF NOT EXISTS music_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE music_db;

DROP TABLE IF EXISTS pjesme;
DROP TABLE IF EXISTS korisnici;

CREATE TABLE korisnici (
  id INT AUTO_INCREMENT PRIMARY KEY,
  korisnicko_ime VARCHAR(50) UNIQUE NOT NULL,
  lozinka VARCHAR(255) NOT NULL,
  uloga ENUM('admin','korisnik') DEFAULT 'korisnik',
  kreirano TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pjesme (
  id INT AUTO_INCREMENT PRIMARY KEY,
  naslov VARCHAR(255) NOT NULL,
  izvodjac VARCHAR(255) NOT NULL,
  album VARCHAR(255),
  zanr VARCHAR(100),
  godina INT,
  trajanje INT,
  ocjena DECIMAL(3,1),
  cover VARCHAR(500),
  opis TEXT,
  kreirano TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO korisnici (korisnicko_ime, lozinka, uloga) VALUES
('admin', MD5('1234'), 'admin'),
('nika', MD5('1234'), 'korisnik');

INSERT INTO pjesme (naslov, izvodjac, album, zanr, godina, trajanje, ocjena, cover, opis) VALUES
('Mirrors','Justin Timberlake','The 20/20 Experience','Pop',2013,484,9.7,'images/mirrors.jpg','Emotivna pop pjesma o ljubavi, povezanosti i osjećaju da u drugoj osobi vidiš odraz sebe.'),
('You''re Beautiful','James Blunt','Back to Bedlam','Pop Rock',2005,213,9.2,'images/youre-beautiful.jpg','Prepoznatljiva balada o kratkom susretu, čežnji i osjećaju koji ostane i nakon što trenutak prođe.'),
('Somebody to Love','Queen','A Day at the Races','Rock',1976,296,9.8,'images/somebody-to-love.jpg','Moćna rock pjesma s gospel utjecajem, snažnim vokalima i velikom emocijom u potrazi za ljubavlju.'),
('Viva La Vida','Coldplay','Viva la Vida or Death and All His Friends','Alternative Rock',2008,242,9.5,'images/viva-la-vida.jpg','Atmosferična pjesma s orkestralnim zvukom, velikim refrenom i temom pada s moći.'),
('Angels','Robbie Williams','Life thru a Lens','Pop Rock',1997,265,9.4,'images/angels.jpg','Velika pop-rock balada o zaštiti, ljubavi i osjećaju sigurnosti koji dolazi od posebne osobe.'),
('Careless Whisper','George Michael','Make It Big','Pop',1984,300,9.6,'images/careless-whisper.jpg','Elegantna i nostalgična pjesma s prepoznatljivim saksofonom, temom krivnje i izgubljene ljubavi.'),
('Don''t Stop Me Now','Queen','Jazz','Rock',1978,209,9.7,'images/dont-stop-me-now.jpg','Brza i euforična rock pjesma puna energije, samopouzdanja i osjećaja potpune slobode.'),
('Fix You','Coldplay','X&Y','Alternative Rock',2005,295,9.5,'images/fix-you.jpg','Emotivna pjesma o podršci, boli i pokušaju da se nekome bude svjetlo u teškom trenutku.');
