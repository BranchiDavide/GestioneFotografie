<?php
class login
{
    public function index(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            try{
                $email = Sanitizer::sanitize($_POST["email"]);
                $password = $_POST["password"];
                Sanitizer::isSetted($password);;
                $utenteMapper = new UtenteMapper();
                $user = $utenteMapper->getByEmailOnlyActive($email);
                if(!$user){
                    Twig::render("login/login.twig", ["errorMessage" => "Errore di accesso!"]);
                    return;
                }
                if($user->getPassword() != hash('sha256', $password)){
                    Twig::render("login/login.twig", ["errorMessage" => "Errore di accesso!"]);
                    return;
                }
                $_SESSION["ruolo"] = $user->getRuoloNome();
                header("Location: " . URL . "home");
            }catch(Exception $ex){
                Twig::render("login/login.twig", ["errorMessage" => "Non sono stati inseriti tutti i dati!"]);
            }
        }else{
            if(Session::hasSessionType()){
                header("Location: " . URL . "home");
            }else{
                Twig::render("login/login.twig", ["errorMessage" => null]);
            }
        }
    }
}