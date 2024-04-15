<?php

class Utente
{
    private $id;
    private $nome;
    private $cognome;
    private $email;
    private $data_nascita;
    private $password;
    private $ruolo_nome;

    /**
     * @param $nome
     * @param $cognome
     * @param $email
     * @param $data_nascita
     * @param $password
     * @param $ruolo_nome
     */
    public function __construct($id, $nome, $cognome, $email, $data_nascita, $password, $ruolo_nome)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->cognome = $cognome;
        $this->email = $email;
        $this->data_nascita = $data_nascita;
        $this->password = $password;
        $this->ruolo_nome = $ruolo_nome;
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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * @return mixed
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * @param mixed $cognome
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getDataNascita()
    {
        return $this->data_nascita;
    }

    /**
     * @param mixed $data_nascita
     */
    public function setDataNascita($data_nascita)
    {
        $this->data_nascita = $data_nascita;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getRuoloNome()
    {
        return $this->ruolo_nome;
    }

    /**
     * @param mixed $ruolo_nome
     */
    public function setRuoloNome($ruolo_nome)
    {
        $this->ruolo_nome = $ruolo_nome;
    }
}