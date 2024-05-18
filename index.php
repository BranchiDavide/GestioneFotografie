<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

if (!isset($_SESSION["photos-seen"])) {
    /**
     * Array per la memorizzazione delle fotografie visualizzate
     * la visualizzazione dell'utente viene conteggiata unoicamente una
     * volta per sessione
     */
    $_SESSION["photos-seen"] = array();
}

require'vendor/autoload.php';

//Caricamento della variabili d'ambiente dal file .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// carico il file di configurazione
require 'application/config/config.php';

// carico le classi dell'applicazione
require 'application/libs/application.php';

// faccio partire l'applicazione
$app = new Application();
