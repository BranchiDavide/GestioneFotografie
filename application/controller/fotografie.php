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
            $score =  $fotografiaMapper->getScore($id);
            $valutazioni = $fotografiaMapper->getAllValutazioni($id);
            $valutazionePresente = null;
            if(Session::hasSessionType()){
                $valutazionePresente = $fotografiaMapper->getValutazioneByUserId($id, $_SESSION["utente-id"]);
            }
            $photosSeen = $_SESSION["photos-seen"];
            if(!in_array($id, $photosSeen)){
                $photosSeen[] = $id;
                $_SESSION["photos-seen"] = $photosSeen;
                $fotografiaMapper->incrementViews($id);
            }
            Twig::render("fotografie/dettagli.twig", ["fotografia" => $fotografia, "score" => $score, "valutazioni" => $valutazioni, "valutazionePresente" => $valutazionePresente]);
        }catch (Exception $e){
            Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
        }
    }

    public function valuta(){
        if(Session::hasSessionType()){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $fotografiaMapper = new FotografiaMapper();
                header("Content-Type: application/json; charset=UTF-8");
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                try{
                    $foto_id = Sanitizer::sanitize($data["foto_id"]);
                    $stelle = Sanitizer::sanitize($data["stelle"]);
                    $action = Sanitizer::sanitize($data["action"]);
                    if($action == "insert"){
                        if($stelle > 0 && $stelle <= 5){
                            $fotografiaMapper->insertValutazione($foto_id, $_SESSION["utente-id"], $stelle);
                            $response = array("status" => "SUCCESS");
                            echo json_encode($response);
                        }else{
                            $response = array("status" => "FAILED");
                            echo json_encode($response);
                        }
                    }else{
                        if($stelle == 0){
                            $fotografiaMapper->deleteValutazione($foto_id, $_SESSION["utente-id"]);
                            $response = array("status" => "SUCCESS");
                            echo json_encode($response);
                        }else{
                            if($stelle > 0 && $stelle <= 5){
                                $fotografiaMapper->updateValutazione($foto_id, $_SESSION["utente-id"], $stelle);
                                $response = array("status" => "SUCCESS");
                                echo json_encode($response);
                            }else{
                                $response = array("status" => "FAILED");
                                echo json_encode($response);
                            }
                        }
                    }
                }catch(Exception $ex){
                    $response = array("status" => "FAILED", "ex" => $ex->getMessage());
                    echo json_encode($response);
                }
            }
        }
    }
}