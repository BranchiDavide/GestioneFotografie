<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig {
    private static $twig;

    public static function init($templatePath) {
        $loader = new FilesystemLoader($templatePath);
        self::$twig = new Environment($loader);
    }

    public static function render($template, $data = []) {
        if (self::$twig === null) {
            throw new Exception('Twig environment not initialized. Call Twig::init() first.');
        }
        echo self::$twig->render($template, $data);
    }
}
