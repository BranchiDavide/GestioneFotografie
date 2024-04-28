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
}