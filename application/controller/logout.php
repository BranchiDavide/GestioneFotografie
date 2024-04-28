<?php
class logout
{
    public function index(){
        session_destroy();
        header("Location: " . URL . "home");
    }
}