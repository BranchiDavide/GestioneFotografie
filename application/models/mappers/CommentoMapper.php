<?php

class CommentoMapper
{
    private $db;
    public function __construct(){
        $this->db = \Database::getConnection();
    }

    public function getAllOfPhoto($fotografia_id){
        $stm = $this->db->prepare("SELECT * FROM commenta WHERE fotografia_id=:fotografia_id ORDER BY id DESC");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->execute();
        $result = $stm->fetchAll();
        $commenti = array();
        foreach($result as $commento){
            $commenti[] = new Commento($commento["id"], $commento["fotografia_id"], $commento["utente_id"], $commento["contenuto"]);
        }
        return $commenti;
    }

    public function changeIdUtenteToName($commenti){
        $utenteMapper = new UtenteMapper();
        if(is_array($commenti)){
            foreach($commenti as $c){
                $utente_id = $c->getUtenteId();
                $utente = $utenteMapper->getById($utente_id);
                $c->setUtenteId($utente->getNome() . " " . $utente->getCognome());
            }
        }else{
            $utente_id = $commenti->getUtenteId();
            $utente = $utenteMapper->getById($utente_id);
            $commenti->setUtenteId($utente->getNome() . " " . $utente->getCognome());
        }
        return $commenti;
    }

    public function getById($id){
        $stm = $this->db->prepare("SELECT * FROM commenta WHERE id=:id LIMIT 1");
        $stm->bindParam(":id", $id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            return new Commento($result[0]["id"], $result[0]["fotografia_id"], $result[0]["utente_id"], $result[0]["contenuto"]);
        }else{
            return null;
        }
    }

    public function insert($fotografia_id, $utente_id, $contenuto){
        $stm = $this->db->prepare("INSERT INTO commenta(fotografia_id, utente_id, contenuto) VALUES(:fotografia_id, :utente_id, :contenuto)");
        $stm->bindParam(":fotografia_id", $fotografia_id);
        $stm->bindParam(":utente_id", $utente_id);
        $stm->bindParam(":contenuto", $contenuto);
        $stm->execute();
    }

    public function delete($id){
        $stm = $this->db->prepare("DELETE FROM commenta WHERE id=:id");
        $stm->bindParam(":id", $id);
        $stm->execute();
    }

    public function update($id, $contenuto){
        $stm = $this->db->prepare("UPDATE commenta SET contenuto=:contenuto WHERE id=:id");
        $stm->bindParam(":id", $id);
        $stm->bindParam(":contenuto", $contenuto);
        $stm->execute();
    }
}