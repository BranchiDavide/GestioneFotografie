<?php
abstract class Database
{
    private static $_connection = null;
    public static function getConnection(){
        if(self::$_connection == null){
            $hostname = $_SERVER["DB_HOST"];
            $db = $_SERVER["DB_SCHEMA"];
            $port = $_SERVER["DB_PORT"];
            $username = $_SERVER["DB_USER"];
            $password = $_SERVER["DB_PASS"];
            try{
                self::$_connection = new PDO("mysql:host = {$hostname}; dbname={$db}; port={$port}",
                    $username, $password);
            }catch(PDOException $ex){
                die($ex);
            }
        }
        return self::$_connection;
    }
}