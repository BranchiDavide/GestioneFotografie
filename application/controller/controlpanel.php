<?php

class controlpanel
{
    public function index(){
        if(Session::hasSessionType()){
            if(Session::isAmministratore()){
                $utenteMapper = new UtenteMapper();
                $requests = $utenteMapper->getAllRequests();
                Twig::render("controlpanel/richieste_utenti.twig", ["requests" => $requests]);
            }else{
                Twig::render("_templates/errorPage.twig", ["errorMsg" => "Non sei autorizzato ad accedere a questa pagina!"]);
            }
        }else{
            header("Location: " . URL . "login");
        }
    }

    public function managerequest(){
        if(Session::hasSessionType()){
            if(Session::isAmministratore()){
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $utenteMapper = new UtenteMapper();
                    header("Content-Type: application/json; charset=UTF-8");
                    $json = file_get_contents('php://input');
                    $data = json_decode($json, true);
                    try{
                        $id = Sanitizer::sanitize($data["id"]);
                        $action = Sanitizer::sanitize($data["action"]);
                        if($action != "approve" && $action != "deny"){
                            $response = array("status" => "FAILED");
                            echo json_encode($response);
                            return;
                        }
                        if($action == "approve"){
                            $utenteMapper->approveRequest($id);
                        }else{
                            $utenteMapper->denyRequest($id);;
                        }
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
        }else{
            $response = array("status" => "FAILED");
            echo json_encode($response);
        }
    }
}