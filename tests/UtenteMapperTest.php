<?php

use PHPUnit\Framework\TestCase;

class UtenteMapperTest extends TestCase
{
    private $utenteMapper;
    private $db;

    public static function setUpBeforeClass(): void
    {
        // Caricare variabili per connessione DB da tests/.env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
        $dotenv->load();
    }

    protected function setUp(): void
    {
        $this->utenteMapper = new UtenteMapper();
        $this->db = Database::getConnection();
        $this->db->query("START TRANSACTION");
    }

    protected function tearDown(): void
    {
        $this->db->query("ROLLBACK");
    }

    public function test_01_getAllActive()
    {
        $expected = [
            new Utente(1, 'Mario', 'Rossi', 'mario.rossi@example.com', '1990-05-15', '0b14d501a594442a01c6859541bcb3e8164d183d32937b851835442f69d5c94e', 'utente'),
            new Utente(2, 'Luigi', 'Verdi', 'luigi.verdi@example.com', '1985-10-20', '6cf615d5bcaac778352a8f1f3360d23f02f34ec182e259897fd6ce485d7870d4', 'fotografo')
        ];
        $actual = $this->utenteMapper->getAllActive();
        $this->assertEquals($expected, $actual);
    }

    public function test_02_getAllRequests()
    {
        $expected = [
            new Utente(1, 'Giovanni', 'Bianchi', 'giovanni.bianchi@example.com', '1988-03-25', '5906ac361a137e2d286465cd6588ebb5ac3f5ae955001100bc41577c3d751764', 'utente'),
            new Utente(2, 'Anna', 'Neri', 'anna.neri@example.com', '1995-12-10', 'b97873a40f73abedd8d685a7cd5e5f85e4a9cfb83eac26886640a0813850122b', 'utente')
        ];
        $actual = $this->utenteMapper->getAllRequests();
        $this->assertEquals($expected, $actual);
    }

    public function test_03_getByEmail_ExistsInUtente()
    {
        $email = 'mario.rossi@example.com';
        $expected = new Utente(1, 'Mario', 'Rossi', $email, '1990-05-15', '0b14d501a594442a01c6859541bcb3e8164d183d32937b851835442f69d5c94e', 'utente');
        $actual = $this->utenteMapper->getByEmail($email);
        $this->assertEquals($expected, $actual);
    }

    public function test_04_getByEmail_ExistsInTempUtente()
    {
        $email = 'giovanni.bianchi@example.com';
        $expected = new Utente(1, 'Giovanni', 'Bianchi', $email, '1988-03-25', '5906ac361a137e2d286465cd6588ebb5ac3f5ae955001100bc41577c3d751764', 'utente');
        $actual = $this->utenteMapper->getByEmail($email);
        $this->assertEquals($expected, $actual);
    }

    public function test_05_getByEmail_NotExists()
    {
        $email = 'nonexistent@example.com';
        $actual = $this->utenteMapper->getByEmail($email);
        $this->assertNull($actual);
    }

    public function test_06_getByEmailOnlyActive()
    {
        $email = 'luigi.verdi@example.com';
        $expected = new Utente(2, 'Luigi', 'Verdi', $email, '1985-10-20', '6cf615d5bcaac778352a8f1f3360d23f02f34ec182e259897fd6ce485d7870d4', 'fotografo');
        $actual = $this->utenteMapper->getByEmailOnlyActive($email);
        $this->assertEquals($expected, $actual);
    }

    public function test_07_getByEmailOnlyActive_NotExists()
    {
        $email = 'nonexistent@example.com';
        $actual = $this->utenteMapper->getByEmailOnlyActive($email);
        $this->assertNull($actual);
    }

    public function test_08_getById_Exists()
    {
        $id = 1;
        $expected = new Utente($id, 'Mario', 'Rossi', 'mario.rossi@example.com', '1990-05-15', '0b14d501a594442a01c6859541bcb3e8164d183d32937b851835442f69d5c94e', 'utente');
        $actual = $this->utenteMapper->getById($id);
        $this->assertEquals($expected, $actual);
    }

    public function test_09_getById_NotExists()
    {
        $id = 999;
        $actual = $this->utenteMapper->getById($id);
        $this->assertNull($actual);
    }

    public function test_10_insert()
    {
        $nome = 'Franco';
        $cognome = 'Neri';
        $email = 'franco.neri@example.com';
        $data_nascita = '1995-12-11';
        $password = hash('sha256', 'test123');
        $ruolo_nome = 'utente';

        $this->utenteMapper->insert($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);

        $actual = $this->utenteMapper->getByEmailOnlyActive($email);
        $expected = new Utente($actual->getId(), $nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);

        $this->assertEquals($expected, $actual);
    }

    public function test_11_insertTemp()
    {
        $nome = 'Carlo';
        $cognome = 'Rossi';
        $email = 'carlo.rossi@example.com';
        $data_nascita = '1992-07-21';
        $password = hash('sha256', 'test123');
        $ruolo_nome = 'utente';

        $this->utenteMapper->insertTemp($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);

        $actual = $this->utenteMapper->getByEmail($email);
        $expected = new Utente($actual->getId(), $nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);

        $this->assertEquals($expected, $actual);
    }

    public function test_12_approveRequest()
    {
        $nome = 'Carlo';
        $cognome = 'Rossi';
        $email = 'carlo.rossi@example.com';
        $data_nascita = '1992-07-21';
        $password = hash('sha256', 'test123');
        $ruolo_nome = 'utente';

        $this->utenteMapper->insertTemp($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);
        $tempUser = $this->utenteMapper->getByEmail($email);
        $this->utenteMapper->approveRequest($tempUser->getId());
        $actual = $this->utenteMapper->getByEmailOnlyActive($email);
        $expected = new Utente($actual->getId(), $nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);

        $this->assertEquals($expected, $actual);
    }

    public function test_13_denyRequest()
    {
        $nome = 'Carlo';
        $cognome = 'Rossi';
        $email = 'carlo.rossi@example.com';
        $data_nascita = '1992-07-21';
        $password = hash('sha256', 'test123');
        $ruolo_nome = 'utente';

        $this->utenteMapper->insertTemp($nome, $cognome, $email, $data_nascita, $password, $ruolo_nome);
        $tempUser = $this->utenteMapper->getByEmail($email);
        $this->utenteMapper->denyRequest($tempUser->getId());
        $actual = $this->utenteMapper->getByEmail($email);
        $this->assertNull($actual);
    }
}