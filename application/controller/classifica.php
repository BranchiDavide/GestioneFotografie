<?php

class classifica
{
    public function index(){
        $fotografiaMapper = new FotografiaMapper();
        $fotografie = $fotografiaMapper->getClassifica(true);
        $fotografieWithoutNome = $fotografiaMapper->getClassifica(false);
        $fotografieVisualizzazioni = $fotografiaMapper->getClassificaByVisualizzazioni();
        $fotografieVisualizzazioniWithoutNome = $fotografiaMapper->getClassificaByVisualizzazioni();
        $fotografieVisualizzazioni = $fotografiaMapper->changeIdUtenteToName($fotografieVisualizzazioni);
        Twig::render("classifica/classifica.twig", ["fotografie" => $fotografie, "fotografieWithoutNome" => $fotografieWithoutNome, "fotografieVisualizzazioni" => $fotografieVisualizzazioni, "fotografieVisualizzazioniWithoutNome" => $fotografieVisualizzazioniWithoutNome, "highlight" => false]);
    }

    public function highlight($utente_id = null){
        if($utente_id != null){
            $fotografiaMapper = new FotografiaMapper();
            $fotografie = $fotografiaMapper->getClassifica(true);
            $fotografieWithoutNome = $fotografiaMapper->getClassifica(false);
            $fotografieVisualizzazioni = $fotografiaMapper->getClassificaByVisualizzazioni();
            $fotografieVisualizzazioniWithoutNome = $fotografiaMapper->getClassificaByVisualizzazioni();
            $fotografieVisualizzazioni = $fotografiaMapper->changeIdUtenteToName($fotografieVisualizzazioni);
            Twig::render("classifica/classifica.twig", ["fotografie" => $fotografie, "fotografieWithoutNome" => $fotografieWithoutNome, "fotografieVisualizzazioni" => $fotografieVisualizzazioni, "fotografieVisualizzazioniWithoutNome" => $fotografieVisualizzazioniWithoutNome, "highlight" => true, "highlight_id" => $utente_id]);
        }else{
            $this->index();
        }
    }
}