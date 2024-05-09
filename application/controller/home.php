<?php
class Home
{

    public function index()
    {
        $fotografiaMapper = new FotografiaMapper();
        $fotografie = $fotografiaMapper->getAll();
        $best3 = $fotografiaMapper->getClassifica3Best();
        Twig::render("home/home.twig", ["fotografie" => $fotografie, "best3" => $best3]);
    }
}
