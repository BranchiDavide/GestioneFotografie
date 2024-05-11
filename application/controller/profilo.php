<?php

class profilo
{
    public function index(){
        if(Session::hasSessionType()){
            $utenteMapper = new UtenteMapper();
            $utente = $utenteMapper->getById($_SESSION["utente-id"]);
            Twig::render("profilo/myprofile.twig", ["utente" => $utente]);
        }else{
            header("Location: " . URL . "login");
        }
    }

    public function fotografie(){
        if(Session::hasSessionType()){
            if(Session::isFotografo()){
                $fotografiaMapper = new FotografiaMapper();
                $fotografie = $fotografiaMapper->getFotografieOfUtente($_SESSION["utente-id"]);
                $mostRatedChartJs = $fotografiaMapper->getMostRatedPhotosChartJsFormat($_SESSION["utente-id"]);
                $mostViewedChartJs = $fotografiaMapper->getMostViewedPhotosChartJsFormat($_SESSION["utente-id"]);
                Twig::render("profilo/myphotos.twig", ["fotografie" => $fotografie, "mostRatedChartJs" => $mostRatedChartJs, "mostViewedChartJs" => $mostViewedChartJs]);
            }else{
                Twig::render("_templates/errorPage.twig", ["errorMsg" => "Non sei autorizzato ad accedere a questa pagina!"]);
            }
        }else{
            header("Location: " . URL . "login");
        }
    }
}