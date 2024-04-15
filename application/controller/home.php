<?php
class Home
{

    public function index()
    {
        if(Session::hasSessionType()){

        }else{
            header("Location: " . URL . "login");
        }
    }
}
