<?php

class fotografie
{
    public function index(){
        header("Location: " . URL . "home");
    }

    public function dettagli($id = null){
        try{
            $id = Sanitizer::sanitize($id);
        }catch (Exception $e){
            Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
        }
    }
}