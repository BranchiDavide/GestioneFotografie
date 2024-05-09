<?php

class Valutazione
{
    private $fotografia_id;
    private $utente_id;
    private $stelle;

    /**
     * @param $fotografia_id
     * @param $utente_id
     * @param $stelle
     */
    public function __construct($fotografia_id, $utente_id, $stelle)
    {
        $this->fotografia_id = $fotografia_id;
        $this->utente_id = $utente_id;
        $this->stelle = $stelle;
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
    public function getStelle()
    {
        return $this->stelle;
    }

    /**
     * @param mixed $stelle
     */
    public function setStelle($stelle)
    {
        $this->stelle = $stelle;
    }
}