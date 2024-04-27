<?php

class register
{
    public function index(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            try{
                $nome = Sanitizer::sanitize($_POST["nome"]);
                $cognome = Sanitizer::sanitize($_POST["cognome"]);
                $email = Sanitizer::sanitize($_POST["email"]);
                $data_nascita = Sanitizer::sanitize($_POST["nascita"]);
                $ruolo_nome = Sanitizer::sanitize($_POST["ruolo"]);
                $password = $_POST["password"];
                $password_confirm = $_POST["password-confirm"];
                Sanitizer::isSetted($_POST["password"]);
                Sanitizer::isSetted($_POST["password-confirm"]);
                if($password != $password_confirm){
                    Twig::render("register/register.twig", ["errorMessage" => "Le password non coincidono!"]);
                    return;
                }
                $utenteMapper = new UtenteMapper();
                if($utenteMapper->getByEmail($email)){
                    Twig::render("register/register.twig", ["errorMessage" => "L'utente con email " . $email . " esiste già!"]);
                    return;
                }
                if(!Sanitizer::validateEmail($email)){
                    Twig::render("register/register.twig", ["errorMessage" => "Email non valida!"]);
                    return;
                }
                //Date check not implemented yet
                /*if(!Sanitizer::validateDate($data_nascita)){
                    Twig::render("register/register.twig", ["errorMessage" => "Data di nascita non valida!"]);
                }*/
                if($ruolo_nome != "utente" && $ruolo_nome != "fotografo"){
                    Twig::render("register/register.twig", ["errorMessage" => "Ruolo non valido!"]);
                    return;
                }
                $password = hash('sha256', $password);
                $utenteMapper->insertTemp($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);
                Twig::render("_templates/successPage.twig", ["successMsg" => "Utente creato con successo, attendere l'attivazione dell'account dall'amministratore per poter accedere"]);
            }catch(Exception $ex){
                Twig::render("register/register.twig", ["errorMessage" => "Non sono stati inseriti tutti i valori!"]);
            }
        }else{
            if(Session::hasSessionType()){
                header("Location: " . URL . "home");
            }else{
                Twig::render("register/register.twig", ["errorMessage" => null]);
            }
        }
    }
}