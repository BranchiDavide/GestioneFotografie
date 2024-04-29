<?php
class Home
{

    public function index()
    {
        $fotografiaMapper = new FotografiaMapper();
        $fotografie = $fotografiaMapper->getAll();
        Twig::render("home/home.twig", ["fotografie" => $fotografie]);
    }
}
