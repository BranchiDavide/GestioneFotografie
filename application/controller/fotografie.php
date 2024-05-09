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
            $valutazioneMapper = new ValutazioneMapper();
            $commentoMapper = new CommentoMapper();
            $fotografia = $fotografiaMapper->getById($id);
            if(!$fotografia){
                Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
                return;
            }
            $fotografia = $fotografiaMapper->changeIdUtenteToName($fotografia);
            $score =  $valutazioneMapper->getScore($id);
            $valutazioni = $valutazioneMapper->getAll($id);
            $valutazioni = $valutazioneMapper->changeIdUtenteToName($valutazioni);
            $valutazionePresente = null;
            if(Session::hasSessionType()){
                $valutazionePresente = $valutazioneMapper->getValutazioneByUserId($id, $_SESSION["utente-id"]);
            }
            $photosSeen = $_SESSION["photos-seen"];
            if(!in_array($id, $photosSeen)){
                $photosSeen[] = $id;
                $_SESSION["photos-seen"] = $photosSeen;
                $fotografiaMapper->incrementViews($id);
            }
            $commenti = $commentoMapper->getAllOfPhoto($id);
            $commentiWithNome = $commentoMapper->changeIdUtenteToName($commentoMapper->getAllOfPhoto($id));
            Twig::render("fotografie/dettagli.twig", ["fotografia" => $fotografia, "score" => $score, "valutazioni" => $valutazioni, "valutazionePresente" => $valutazionePresente, "commenti" => $commenti, "commentiWithNome" => $commentiWithNome]);
            unset($_SESSION['showSuccessMsg']);
        }catch (Exception $e){
            Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
        }
    }

    public function valuta(){
        if(Session::hasSessionType()){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $fotografiaMapper = new FotografiaMapper();
                $valutazioneMapper = new ValutazioneMapper();
                header("Content-Type: application/json; charset=UTF-8");
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                try{
                    $foto_id = Sanitizer::sanitize($data["foto_id"]);
                    $stelle = Sanitizer::sanitize($data["stelle"]);
                    $action = Sanitizer::sanitize($data["action"]);
                    if(!$fotografiaMapper->getById($foto_id)){ //La foto non esiste
                        $response = array("status" => "FAILED");
                        echo json_encode($response);
                        return;
                    }
                    if($action == "insert"){
                        if($stelle > 0 && $stelle <= 5){
                            $valutazioneMapper->insertValutazione($foto_id, $_SESSION["utente-id"], $stelle);
                            $response = array("status" => "SUCCESS");
                            echo json_encode($response);
                        }else{
                            $response = array("status" => "FAILED");
                            echo json_encode($response);
                        }
                    }else{
                        if($stelle == 0){
                            $valutazioneMapper->deleteValutazione($foto_id, $_SESSION["utente-id"]);
                            $response = array("status" => "SUCCESS");
                            echo json_encode($response);
                        }else{
                            if($stelle > 0 && $stelle <= 5){
                                $valutazioneMapper->updateValutazione($foto_id, $_SESSION["utente-id"], $stelle);
                                $response = array("status" => "SUCCESS");
                                echo json_encode($response);
                            }else{
                                $response = array("status" => "FAILED");
                                echo json_encode($response);
                            }
                        }
                    }
                }catch(Exception $ex){
                    $response = array("status" => "FAILED");
                    echo json_encode($response);
                }
            }else{
                $response = array("status" => "FAILED");
                echo json_encode($response);
            }
        }else{
            $response = array("status" => "FAILED");
            echo json_encode($response);
        }
    }

    public function commenta($id = null){
        if(Session::hasSessionType()){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                try{
                    $id = Sanitizer::sanitize($id);
                    $contenuto = Sanitizer::sanitize($_POST["contenuto"]);
                    $fotografiaMapper = new FotografiaMapper();
                    $commentoMapper = new CommentoMapper();
                    $fotografia = $fotografiaMapper->getById($id);
                    if(!$fotografia){
                        Twig::render("_templates/errorPage.twig", ["errorMsg" => "Fotografia non trovata!"]);
                        return;
                    }
                    if(strlen($contenuto) <= 0 || strlen($contenuto) > 500){
                        Twig::render("_templates/errorPage.twig", ["errorMsg" => "Errore, caratteri commento non validi!"]);
                        return;
                    }
                    $commentoMapper->insert($id, $_SESSION["utente-id"], $contenuto);
                    $_SESSION["showSuccessMsg"] = "Commento aggiunto con successo!";
                    header("Location: " . URL . "fotografie/dettagli/" . $id . "#comments");
                }catch (Exception $e){
                    Twig::render("_templates/errorPage.twig", ["errorMsg" => "Errore nell'inserimento del commento!"]);
                }
            }
        }
    }

    public function eliminacommento(){
        if(Session::hasSessionType()){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $commentoMapper = new CommentoMapper();
                header("Content-Type: application/json; charset=UTF-8");
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                try{
                    $id = Sanitizer::sanitize($data["id"]);
                    $commento = $commentoMapper->getById($id);
                    if(!$commento){// Il commento non esiste
                        $response = array("status" => "FAILED");
                        echo json_encode($response);
                        return;
                    }
                    if($commento->getUtenteId()!= $_SESSION["utente-id"]){ //Un utente prova ad eliminare un commento che non è suo
                        $response = array("status" => "FAILED");
                        echo json_encode($response);
                        return;
                    }
                    $commentoMapper->delete($id);
                    $response = array("status" => "SUCCESS");
                    echo json_encode($response);
                }catch(Exception $ex){
                    $response = array("status" => "FAILED");
                    echo json_encode($response);
                }
            }else{
                $response = array("status" => "FAILED");
                echo json_encode($response);
            }
        }else{
            $response = array("status" => "FAILED");
            echo json_encode($response);
        }
    }

    public function modificacommento(){
        if(Session::hasSessionType()){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $commentoMapper = new CommentoMapper();
                header("Content-Type: application/json; charset=UTF-8");
                $json = file_get_contents('php://input');
                $data = json_decode($json, true);
                try{
                    $id = Sanitizer::sanitize($data["id"]);
                    $contenuto = Sanitizer::sanitize($data["contenuto"]);
                    $commento = $commentoMapper->getById($id);
                    if(!$commento){// Il commento non esiste
                        $response = array("status" => "FAILED");
                        echo json_encode($response);
                        return;
                    }
                    if($commento->getUtenteId() != $_SESSION["utente-id"]){ //Un utente prova a modificare un commento che non è suo
                        $response = array("status" => "FAILED");
                        echo json_encode($response);
                        return;
                    }
                    $commentoMapper->update($id, $contenuto);
                    $response = array("status" => "SUCCESS");
                    echo json_encode($response);
                }catch(Exception $ex){
                    $response = array("status" => "FAILED");
                    echo json_encode($response);
                }
            }else{
                $response = array("status" => "FAILED");
                echo json_encode($response);
            }
        }else{
            $response = array("status" => "FAILED");
            echo json_encode($response);
        }
    }
}