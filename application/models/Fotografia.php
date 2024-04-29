<?php

class Fotografia
{
    private $id;
    private $path;
    private $data_ora;
    private $luogo;
    private $soggetto;
    private $tipologia;
    private $visualizzazioni;
    private $utente_id;

    /**
     * @param $id
     * @param $path
     * @param $data_ora
     * @param $luogo
     * @param $soggetto
     * @param $tipologia
     * @param $visualizzazioni
     * @param $utente_id
     */
    public function __construct($id, $path, $data_ora, $luogo, $soggetto, $tipologia, $visualizzazioni, $utente_id)
    {
        $this->id = $id;
        $this->path = $path;
        $this->data_ora = $data_ora;
        $this->luogo = $luogo;
        $this->soggetto = $soggetto;
        $this->tipologia = $tipologia;
        $this->visualizzazioni = $visualizzazioni;
        $this->utente_id = $utente_id;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getDataOra()
    {
        return $this->data_ora;
    }

    /**
     * @param mixed $data_ora
     */
    public function setDataOra($data_ora)
    {
        $this->data_ora = $data_ora;
    }

    /**
     * @return mixed
     */
    public function getLuogo()
    {
        return $this->luogo;
    }

    /**
     * @param mixed $luogo
     */
    public function setLuogo($luogo)
    {
        $this->luogo = $luogo;
    }

    /**
     * @return mixed
     */
    public function getSoggetto()
    {
        return $this->soggetto;
    }

    /**
     * @param mixed $soggetto
     */
    public function setSoggetto($soggetto)
    {
        $this->soggetto = $soggetto;
    }

    /**
     * @return mixed
     */
    public function getTipologia()
    {
        return $this->tipologia;
    }

    /**
     * @param mixed $tipologia
     */
    public function setTipologia($tipologia)
    {
        $this->tipologia = $tipologia;
    }

    /**
     * @return mixed
     */
    public function getVisualizzazioni()
    {
        return $this->visualizzazioni;
    }

    /**
     * @param mixed $visualizzazioni
     */
    public function setVisualizzazioni($visualizzazioni)
    {
        $this->visualizzazioni = $visualizzazioni;
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
}