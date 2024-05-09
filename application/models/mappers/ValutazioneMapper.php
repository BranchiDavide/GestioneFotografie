<?php

class ValutazioneMapper
{
    private $db;
    public function __construct(){
        $this->db = \Database::getConnection();
    }

    public function getScore($fotografia_id){
        $stm = $this->db->prepare("SELECT AVG(stelle) AS 'score' FROM valuta WHERE fotografia_id=:fotografia_id");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            return $result[0]["score"];
        }else{
            return null;
        }
    }

    public function getAll($fotografia_id){
        $stm = $this->db->prepare("SELECT * FROM valuta WHERE fotografia_id=:fotografia_id");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->execute();
        $result = $stm->fetchAll();
        $valutazioni = array();
        foreach($result as $valutazione){
            $valutazioni[] = new Valutazione($valutazione["fotografia_id"], $valutazione["utente_id"], $valutazione["stelle"]);
        }
        return $valutazioni;
    }

    public function changeIdUtenteToName($valutazioni){
        $utenteMapper = new UtenteMapper();
        if(is_array($valutazioni)){
            foreach($valutazioni as $v){
                $utente_id = $v->getUtenteId();
                $utente = $utenteMapper->getById($utente_id);
                $v->setUtenteId($utente->getNome() . " " . $utente->getCognome());
            }
        }else{
            $utente_id = $valutazioni->getUtenteId();
            $utente = $utenteMapper->getById($utente_id);
            $valutazioni->setUtenteId($utente->getNome() . " " . $utente->getCognome());
        }
        return $valutazioni;
    }

    public function insertValutazione($fotografia_id, $utente_id, $stelle){
        $stm = $this->db->prepare("INSERT INTO valuta(fotografia_id, utente_id, stelle) VALUES(:fotografia_id, :utente_id, :stelle)");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->bindParam(":utente_id", $utente_id);
        $stm->bindParam(":stelle", $stelle);
        $stm->execute();
    }

    public function updateValutazione($fotografia_id, $utente_id, $stelle){
        $stm = $this->db->prepare("UPDATE valuta SET stelle=:stelle WHERE fotografia_id=:fotografia_id AND utente_id=:utente_id");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->bindParam(":utente_id", $utente_id);
        $stm->bindParam(":stelle", $stelle);
        $stm->execute();
    }

    public function deleteValutazione($fotografia_id, $utente_id){
        $stm = $this->db->prepare("DELETE FROM valuta WHERE fotografia_id=:fotografia_id AND utente_id=:utente_id");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
    }

    public function getValutazioneByUserId($fotografia_id, $utente_id){
        $stm = $this->db->prepare("SELECT * FROM valuta WHERE fotografia_id=:fotografia_id AND utente_id=:utente_id");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            return new Valutazione($result[0]["fotografia_id"], $result[0]["utente_id"], $result[0]["stelle"]);
        }else{
            return null;
        }
    }
}