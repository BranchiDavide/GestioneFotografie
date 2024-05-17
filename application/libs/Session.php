<?php

class Session
{
    public static function hasSessionType(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['ruolo']);
    }

    public static function isAmministratore(){
        if(self::hasSessionType()){
            if($_SESSION['ruolo'] == "amministratore"){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public static function isFotografo(){
        if(self::hasSessionType()){
            if($_SESSION['ruolo'] == "fotografo"){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public static function isUtente(){
        if(self::hasSessionType()){
            if($_SESSION['ruolo'] == "utente"){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public static function genCSRFtoken(){
        $token = bin2hex(random_bytes(35));
        $_SESSION["CSRFToken"] = $token;
    }

    public static function validateCSRFToken($fromJson = false, $jsonCSRFToken = null){
        if($fromJson){
            if(!isset($_SESSION["CSRFToken"])){
                return false;
            }
            if(!isset($jsonCSRFToken)){
                return false;
            }
            if ($jsonCSRFToken != $_SESSION["CSRFToken"]) {
                return false;
            }
            return true;
        }else{
            if(!isset($_SESSION["CSRFToken"])){
                return false;
            }
            if(!isset($_POST["CSRFToken"])){
                return false;
            }
            if ($_POST["CSRFToken"] != $_SESSION["CSRFToken"]) {
                return false;
            }
            return true;
        }
    }

    public static function showCSRFTokenError($json = false){
        if($json){
            $response = array("status" => "FAILED");
            echo json_encode($response);
            return;
        }else{
            Twig::render("_templates/errorPage.twig", ["errorMsg" => "CSRF error!"]);
        }
    }
}