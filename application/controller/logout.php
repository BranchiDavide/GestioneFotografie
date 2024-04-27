<?php
require_once 'application/controller/login.php';
class logout
{
    public function index(){
        session_destroy();
        header("Location: " . URL . "home");
    }
}