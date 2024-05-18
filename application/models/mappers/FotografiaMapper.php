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

    public function getFotografieOfUtente($utente_id){
        $stm = $this->db->prepare("SELECT * FROM fotografia WHERE utente_id=:utente_id");
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
        $result = $stm->fetchAll();
        $fotografie = array();
        foreach($result as $fotografia){
            $fotografie[] = new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]);
        }
        return $fotografie;
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
        $utenteMapper = new UtenteMapper();
        if(is_array($fotografia)){
            foreach($fotografia as $f){
                $utente_id = $f->getUtenteId();
                $utente = $utenteMapper->getById($utente_id);
                $f->setUtenteId($utente->getNome() . " " . $utente->getCognome());
            }
        }else{
            $utente_id = $fotografia->getUtenteId();
            $utente = $utenteMapper->getById($utente_id);
            $fotografia->setUtenteId($utente->getNome() . " " . $utente->getCognome());
        }
        return $fotografia;
    }

    public function incrementViews($id){
        $stm = $this->db->prepare("UPDATE fotografia SET visualizzazioni=visualizzazioni+1 WHERE id=:id");
        $stm->bindParam(":id", $id);
        $stm->execute();
    }

    public function getClassifica($changeIdUtenteToNome = false){
        $stm = $this->db->prepare("
            SELECT id, path, data_ora, luogo, soggetto, tipologia, visualizzazioni, fotografia.utente_id, AVG(stelle) AS 'score'
            FROM valuta INNER JOIN fotografia ON fotografia.id=fotografia_id
            GROUP BY(id)
            ORDER BY score DESC
        ");
        $stm->execute();
        $result = $stm->fetchAll();
        $data = array();
        if($result){
            $utenteMapper = new UtenteMapper();
            foreach($result as $fotografia){
                $utente_id = $fotografia["utente_id"];
                $utente = $utenteMapper->getById($utente_id);
                if($changeIdUtenteToNome){
                    $fotografia["utente_id"] = $utente->getNome() . " " . $utente->getCognome();
                }
                $data[] = array(
                    new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]),
                    $fotografia["score"]
                );
            }
            return $data;
        }else{
            return null;
        }
    }

    public function getClassificaByVisualizzazioni(){
        $stm = $this->db->prepare("SELECT * FROM fotografia ORDER BY visualizzazioni DESC");
        $stm->execute();
        $result = $stm->fetchAll();
        $fotografie = array();
        foreach($result as $fotografia){
            $fotografie[] = new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]);
        }
        return $fotografie;
    }

    public function getClassifica3Best(){
        $stm = $this->db->prepare("
            SELECT id, path, data_ora, luogo, soggetto, tipologia, visualizzazioni, fotografia.utente_id, AVG(stelle) AS 'score'
            FROM valuta INNER JOIN fotografia ON fotografia.id=fotografia_id
            GROUP BY(id)
            ORDER BY score DESC
            LIMIT 3
        ");
        $stm->execute();
        $result = $stm->fetchAll();
        $data = array();
        if($result){
            foreach($result as $fotografia){
                $data[] = array(
                    new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]),
                    $fotografia["score"]
                );
            }
            return $data;
        }else{
            return null;
        }
    }

    public function search($filters, $value){
        $selectSql = "SELECT fotografia.*, utente.nome, utente.cognome FROM fotografia INNER JOIN utente ON fotografia.utente_id=utente.id WHERE ";
        $whereSql = array();
        for($i = 0; $i < count($filters); $i++){
            if($filters[$i] == "nome_fotografo"){
                $filters[$i] = "utente.nome";
            }
            if($filters[$i] == "cognome_fotografo"){
                $filters[$i] = "utente.cognome";
            }
            if($i == count($filters) - 1){ //Ultimo elemento, non server OR
                $whereSql[] = $filters[$i] . " LIKE :value";
            }else{
                $whereSql[] = $filters[$i] . " LIKE :value OR";
            }
        }
        $fullQuery = $selectSql . implode(" ", $whereSql);

        $stm = $this->db->prepare($fullQuery);
        $value = '%' . $value . '%';
        $stm->bindParam(":value", $value);
        $stm->execute();
        $result = $stm->fetchAll();
        $fotografie = array();
        foreach($result as $fotografia){
            $fotografie[] = new Fotografia($fotografia["id"], $fotografia["path"], $fotografia["data_ora"], $fotografia["luogo"], $fotografia["soggetto"], $fotografia["tipologia"], $fotografia["visualizzazioni"], $fotografia["utente_id"]);
        }
        return $fotografie;
    }

    public function convertFotografieArrayToJson($array){
        $data = array();
        foreach($array as $item){
            $data[] = array(
                "id" => $item->getId(),
                "path" => $item->getPath(),
                "data_ora" => $item->getDataOra(),
                "luogo" => $item->getLuogo(),
                "soggetto" => $item->getSoggetto(),
                "tipologia" => $item->getTipologia(),
                "visualizzazioni" => $item->getVisualizzazioni(),
                "utente_id" => $item->getUtenteId()
            );
        }
        return $data;
    }

    public function getNumberOfUserPhotos($utente_id){
        $stm = $this->db->prepare("SELECT COUNT(*) AS 'count' FROM fotografia WHERE utente_id=:utente_id");
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
        $result = $stm->fetchAll();
        if($result){
            return $result[0]["count"];
        }else{
            return null;
        }
    }

    public function getMostRatedPhotosChartJsFormat($utente_id){
        $stm = $this->db->prepare("
            SELECT id, luogo,
            CONCAT(YEAR(data_ora),'-',LPAD(MONTH(data_ora), 2, '0'),'-',LPAD(DAY(data_ora), 2, '0')) AS 'data_ora',
            fotografia.utente_id, AVG(stelle) AS 'score'
            FROM fotografia
            INNER JOIN valuta ON fotografia_id=fotografia.id
            WHERE fotografia.utente_id=:utente_id
            GROUP BY(id)
            ORDER BY score DESC
        ");
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
        $result = $stm->fetchAll();
        $data = array();
        $xValues = array();
        $yValues = array();
        $ids = array();
        foreach($result as $fotografia){
            $xValues[] = $fotografia["luogo"] . ", " . $fotografia["data_ora"];
            $yValues[] = $fotografia["score"];
            $ids[] = $fotografia["id"];
        }
        $data["xValues"] = $xValues;
        $data["yValues"] = $yValues;
        $data["ids"] = $ids;
        return $data;
    }

    public function getMostViewedPhotosChartJsFormat($utente_id){
        $stm = $this->db->prepare("
        SELECT id, luogo, visualizzazioni,
        CONCAT(YEAR(data_ora),'-',LPAD(MONTH(data_ora), 2, '0'),'-',LPAD(DAY(data_ora), 2, '0')) AS 'data_ora'
        FROM fotografia
        WHERE utente_id=:utente_id
        ORDER BY visualizzazioni DESC");
        $stm->bindParam(":utente_id", $utente_id);
        $stm->execute();
        $result = $stm->fetchAll();
        $data = array();
        $xValues = array();
        $yValues = array();
        $ids = array();
        foreach($result as $fotografia){
            $xValues[] = $fotografia["luogo"] . ", " . $fotografia["data_ora"];
            $yValues[] = $fotografia["visualizzazioni"];
            $ids[] = $fotografia["id"];
        }
        $data["xValues"] = $xValues;
        $data["yValues"] = $yValues;
        $data["ids"] = $ids;
        return $data;
    }
}