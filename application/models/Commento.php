<?php

class Commento
{
    private $id;
    private $fotografia_id;
    private $utente_id;
    private $contenuto;

    /**
     * @param $id
     * @param $fotografia_id
     * @param $utente_id
     * @param $contenuto
     */
    public function __construct($id, $fotografia_id, $utente_id, $contenuto)
    {
        $this->id = $id;
        $this->fotografia_id = $fotografia_id;
        $this->utente_id = $utente_id;
        $this->contenuto = $contenuto;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getFotografiaId()
    {
        return $this->fotografia_id;
    }

    /**
     * @param mixed $fotografia_id
     */
    public function setFotografiaId($fotografia_id)
    {
        $this->fotografia_id = $fotografia_id;
    }

    /**
     * @return mixed
     */
    public function getUtenteId()
    {
        return $this->utente_id;
    }

    /**
     * @param mixed $utente_id
     */
    public function setUtenteId($utente_id)
    {
        $this->utente_id = $utente_id;
    }

    /**
     * @return mixed
     */
    public function getContenuto()
    {
        return $this->contenuto;
    }

    /**
     * @param mixed $contenuto
     */
    public function setContenuto($contenuto)
    {
        $this->contenuto = $contenuto;
    }
}