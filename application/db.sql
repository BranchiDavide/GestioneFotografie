DROP DATABASE IF EXISTS gestione_fotografie;
CREATE DATABASE gestione_fotografie;
USE gestione_fotografie;

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
  luogo VARCHAR(255),
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