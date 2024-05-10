<?php

class classifica
{
    public function index(){
        $fotografiaMapper = new FotografiaMapper();
        $fotografie = $fotografiaMapper->getClassifica();
        $fotografieVisualizzazioni = $fotografiaMapper->getClassificaByVisualizzazioni();
        $fotografieVisualizzazioni = $fotografiaMapper->changeIdUtenteToName($fotografieVisualizzazioni);
        Twig::render("classifica/classifica.twig", ["fotografie" => $fotografie, "fotografieVisualizzazioni" => $fotografieVisualizzazioni]);
    }
}