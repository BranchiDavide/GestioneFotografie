DROP DATABASE IF EXISTS gestione_fotografie_test;
CREATE DATABASE gestione_fotografie_test;
USE gestione_fotografie_test;

CREATE TABLE ruolo(
  nome VARCHAR(255) PRIMARY KEY
);

CREATE TABLE utente(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  cognome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  data_nascita DATE NOT NULL,
  password CHAR(64) NOT NULL,
  ruolo_nome VARCHAR(255) NOT NULL,
  FOREIGN KEY (ruolo_nome) REFERENCES ruolo(nome)
);

CREATE TABLE tempUtente(
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  cognome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  data_nascita DATE NOT NULL,
  password CHAR(64) NOT NULL,
  ruolo_nome VARCHAR(255) NOT NULL,
  FOREIGN KEY (ruolo_nome) REFERENCES ruolo(nome)
);

CREATE TABLE fotografia(
  id INT AUTO_INCREMENT PRIMARY KEY,
  path VARCHAR(255) NOT NULL,
  data_ora DATETIME NOT NULL,
  luogo VARCHAR(255) NOT NULL,
  soggetto VARCHAR(255),
  tipologia ENUM('b/n', 'colori') NOT NULL,
  visualizzazioni INT NOT NULL DEFAULT 0,
  utente_id INT NOT NULL,
  FOREIGN KEY (utente_id) REFERENCES utente(id)
);

CREATE TABLE commenta(
  id INT AUTO_INCREMENT PRIMARY KEY,
  fotografia_id INT NOT NULL,
  utente_id INT NOT NULL,
  contenuto TEXT NOT NULL,
  FOREIGN KEY (fotografia_id) REFERENCES fotografia(id),
  FOREIGN KEY (utente_id) REFERENCES utente(id)
);

CREATE TABLE valuta(
  fotografia_id INT NOT NULL,
  utente_id INT NOT NULL,
  stelle INT NOT NULL DEFAULT 0,
  PRIMARY KEY(fotografia_id, utente_id),
  FOREIGN KEY (fotografia_id) REFERENCES fotografia(id),
  FOREIGN KEY (utente_id) REFERENCES utente(id)
);

INSERT INTO ruolo VALUES ('amministratore'), ('fotografo'), ('utente');

INSERT INTO utente (nome, cognome, email, data_nascita, password, ruolo_nome)
VALUES
    ('Mario', 'Rossi', 'mario.rossi@example.com', '1990-05-15', SHA2('password1', 256), 'utente'),
    ('Luigi', 'Verdi', 'luigi.verdi@example.com', '1985-10-20', SHA2('password2', 256), 'fotografo');

INSERT INTO tempUtente (nome, cognome, email, data_nascita, password, ruolo_nome)
VALUES
    ('Giovanni', 'Bianchi', 'giovanni.bianchi@example.com', '1988-03-25', SHA2('password3', 256), 'utente'),
    ('Anna', 'Neri', 'anna.neri@example.com', '1995-12-10', SHA2('password4', 256), 'utente');

INSERT INTO fotografia (path, data_ora, luogo, soggetto, tipologia, utente_id)
VALUES
    ('public/datastore/uuid1.jpg', NOW(), 'Lucerna', 'Paesaggio urbano', 'colori', 2),
    ('public/datastore/uuid2.jpg', NOW(), 'Zurigo', 'Architettura', 'b/n', 2),
    ('public/datastore/uuid3.jpg', NOW(), 'Lugano', 'Ritratto', 'colori', 2);

INSERT INTO commenta (fotografia_id, utente_id, contenuto)
VALUES
    (1, 1, 'Bellissima foto!'),
    (1, 2, 'Mi piace molto il contrasto dei colori.'),
    (2, 1, 'Interessante prospettiva.'),
    (3, 2, 'Complimenti per il ritratto.');

INSERT INTO valuta (fotografia_id, utente_id, stelle)
VALUES
    (1, 1, 5),
    (1, 2, 4),
    (2, 1, 4),
    (3, 2, 5);
