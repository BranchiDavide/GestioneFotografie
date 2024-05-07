<?php

class FotografiaMapper
{
    private $db;
    public function __construct(){
        $this->db = \Database::getConnection();
    }

    public function getAll(){
        $stm = $this->db->prepare("SELECT * FROM fotografia");
        $stm->execute();
        $result = $stm->fetchAll();
        $fotografie = array();
        foreach($result as $fotografia){
            $fotografie[] = new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]);
        }
        return $fotografie;
    }

    public function getById($id){
        $stm = $this->db->prepare("SELECT * FROM fotografia WHERE id=:id LIMIT 1");
        $stm->bindParam(":id", $id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            $fotografia = $result[0];
            return new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]);
        }else{
            return null;
        }
    }

    public function insert($path, $data_ora, $luogo, $soggetto, $tipologia, $visualizzazioni, $utente_id){
        $stm = $this->db->prepare("INSERT INTO fotografia (path, data_ora, luogo, soggetto, tipologia, visualizzazioni, utente_id) VALUES(:path, :data_ora, :luogo, :soggetto, :tipologia, :visualizzazioni, :utente_id)");
        $stm->bindValue(':path', $path);
        $stm->bindValue(':data_ora', $data_ora);
        $stm->bindValue(':luogo', $luogo);
        $stm->bindValue(':soggetto', $soggetto);
        $stm->bindValue(':tipologia', $tipologia);
        $stm->bindValue(':visualizzazioni', $visualizzazioni);
        $stm->bindValue(':utente_id', $utente_id);
        $stm->execute();
    }

    public function changeIdUtenteToName($fotografia){
        $userMapper = new UtenteMapper();
        if(is_array($fotografia)){
            foreach($fotografia as $f){
                $utente_id = $f->getUtenteId();
                $utente = $userMapper->getById($utente_id);
                $f->setUtenteId($utente->getNome() . " " . $utente->getCognome());
            }
        }else{
            $utente_id = $fotografia->getUtenteId();
            $utente = $userMapper->getById($utente_id);
            $fotografia->setUtenteId($utente->getNome() . " " . $utente->getCognome());
        }
        return $fotografia;
    }
}