<?php

class UtenteMapper
{
    private $db;
    public function __construct(){
        $this->db = \Database::getConnection();
    }

    public function getAllActive(){
        $stm  = $this->db->prepare("SELECT * FROM utente");
        $stm->execute();
        $result = $stm->fetchAll();
        $users = array();
        foreach($result as $user){
            $users[] = new Utente($user["id"], $user["nome"], $user["cognome"], $user["email"], $user["data_nascita"], $user["password"], $user["ruolo_nome"]);
        }
        return $users;
    }

    public function getAllRequests(){
        $stm  = $this->db->prepare("SELECT * FROM tempUtente");
        $stm->execute();
        $result = $stm->fetchAll();
        $users = array();
        foreach($result as $user){
            $users[] = new Utente($user["id"], $user["nome"], $user["cognome"], $user["email"], $user["data_nascita"], $user["password"], $user["ruolo_nome"]);
        }
        return $users;
    }

    public function getByEmail($email){
        $stmUtente = $this->db->prepare("SELECT * FROM utente WHERE email=:email LIMIT 1");
        $stmUtente->bindParam(":email", $email);
        $stmUtente->execute();
        $resultUtente = $stmUtente->fetchAll();
        if(!$resultUtente){
            $stmTempUtente = $this->db->prepare("SELECT * FROM tempUtente WHERE email=:email LIMIT 1");
            $stmTempUtente->bindParam(":email", $email);
            $stmTempUtente->execute();
            $resultTempUtente = $stmTempUtente->fetchAll();
            if(!$resultTempUtente){
                return null;
            }else{
                return new Utente($resultTempUtente[0]["id"], $resultTempUtente[0]["nome"], $resultTempUtente[0]["cognome"], $resultTempUtente[0]["email"], $resultTempUtente[0]["data_nascita"], $resultTempUtente[0]["password"], $resultTempUtente[0]["ruolo_nome"]);
            }
        }else{
            return new Utente($resultUtente[0]["id"], $resultUtente[0]["nome"], $resultUtente[0]["cognome"], $resultUtente[0]["email"], $resultUtente[0]["data_nascita"], $resultUtente[0]["password"], $resultUtente[0]["ruolo_nome"]);
        }
    }

    public function getByEmailOnlyActive($email){
        $stmUtente = $this->db->prepare("SELECT * FROM utente WHERE email=:email LIMIT 1");
        $stmUtente->bindParam(":email", $email);
        $stmUtente->execute();
        $resultUtente = $stmUtente->fetchAll();
        if($resultUtente){
            return new Utente($resultUtente[0]["id"], $resultUtente[0]["nome"], $resultUtente[0]["cognome"], $resultUtente[0]["email"], $resultUtente[0]["data_nascita"], $resultUtente[0]["password"], $resultUtente[0]["ruolo_nome"]);
        }else{
            return null;
        }
    }

    public function getById($id){
        $stm = $this->db->prepare("SELECT * FROM utente WHERE id=:id LIMIT 1");
        $stm->bindParam(":id", $id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            $utente = $result[0];
            return new Utente($utente["id"], $utente["nome"], $utente["cognome"], $utente["email"], $utente["data_nascita"], $utente["password"], $utente["ruolo_nome"]);
        }else{
            return null;
        }
    }

    public function insert($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome){
        $stm = $this->db->prepare("INSERT INTO utente(nome, cognome, email, data_nascita, password, ruolo_nome) VALUES (:nome, :cognome, :email, :data_nascita, :password, :ruolo_nome)");
        $stm->bindParam(":nome", $nome);
        $stm->bindParam(":cognome", $cognome);
        $stm->bindParam(":email", $email);
        $stm->bindParam(":data_nascita", $data_nascita);
        $stm->bindParam(":password", $password);
        $stm->bindParam(":ruolo_nome", $ruolo_nome);
        $stm->execute();
    }
    
    public function insertTemp($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome){
        $stm = $this->db->prepare("INSERT INTO tempUtente(nome, cognome, email, data_nascita, password, ruolo_nome) VALUES (:nome, :cognome, :email, :data_nascita, :password, :ruolo_nome)");
        $stm->bindParam(":nome", $nome);
        $stm->bindParam(":cognome", $cognome);
        $stm->bindParam(":email", $email);
        $stm->bindParam(":data_nascita", $data_nascita);
        $stm->bindParam(":password", $password);
        $stm->bindParam(":ruolo_nome", $ruolo_nome);
        $stm->execute();
    }

    public function approveRequest($id){
        $stm = $this->db->prepare("
                                    INSERT INTO utente(nome, cognome, email, data_nascita, password, ruolo_nome)
                                    SELECT nome, cognome, email, data_nascita, password, ruolo_nome FROM tempUtente WHERE id=:id
                                  ");
        $stm->bindParam(":id", $id);
        $stm->execute();
        $deleteStm = $this->db->prepare("DELETE FROM tempUtente WHERE id=:id");
        $deleteStm->bindParam(":id", $id);
        $deleteStm->execute();
    }

    public function denyRequest($id){
        $stm = $this->db->prepare("DELETE FROM tempUtente WHERE id=:id");
        $stm->bindParam(":id", $id);
        $stm->execute();
    }
}