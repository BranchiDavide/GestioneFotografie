<?php

class Sanitizer
{
    public static function sanitize($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        Sanitizer::isSetted($data);
        return $data;
    }

    public static function isSetted($data){
        if(!$data){
            throw new Exception("Valori inseriti non validi");
        }
    }

    public static function validateDate($date){
        // Not implemented yet
    }

    public static function validateEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}