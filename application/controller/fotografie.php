<?php

class fotografie
{
    public function index(){
        header("Location: " . URL . "home");
    }

    public function dettagli($id = null){
        try{
            $id = Sanitizer::sanitize($id);
            $fotografiaMapper = new FotografiaMapper();
            $fotografia = $fotografiaMapper->getById($id);
            if(!$fotografia){
                Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
                return;
            }
            $fotografia = $fotografiaMapper->changeIdUtenteToName($fotografia);
            Twig::render("fotografie/dettagli.twig", ["fotografia" => $fotografia]);
        }catch (Exception $e){
            Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
        }
    }
}