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
        if(!$data && $data != 0){
            throw new Exception("Valori inseriti non validi");
        }
    }

    public static function validateDate($date){
        $regex = '/^((0[1-9]|[1-2][0-9]|3[0-1])[.\/](0[1-9]|1[0-2])[.\/]\d{4}|\d{4}-((0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])))$/';
        return preg_match($regex, $date);
    }

    public static function validateEmail($email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}