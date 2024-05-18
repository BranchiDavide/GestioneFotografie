<?php

use PHPUnit\Framework\TestCase;

class CommentoMapperTest extends TestCase
{
    private $commentoMapper;
    private $db;

    public static function setUpBeforeClass(): void
    {
        // Caricare variabili per connessione DB da tests/.env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
        $dotenv->load();
    }

    protected function setUp(): void
    {
        $this->commentoMapper = new CommentoMapper();
        $this->db = Database::getConnection();
        $this->db->query("START TRANSACTION");
    }

    protected function tearDown(): void
    {
        $this->db->query("ROLLBACK");
    }

    public function test_01_getAllOfPhoto()
    {
        $fotografia_id = 1;
        $actual = $this->commentoMapper->getAllOfPhoto($fotografia_id);
        $this->assertGreaterThan(0, count($actual));
        foreach($actual as $commento){
            $this->assertInstanceOf(Commento::class, $commento);
        }
    }

    public function test_02_changeIdUtenteToName()
    {
        $commenti = $this->commentoMapper->getAllOfPhoto(1);
        $result = $this->commentoMapper->changeIdUtenteToName($commenti);
        foreach ($result as $commento) {
            $this->assertInstanceOf(Commento::class, $commento);
            $this->assertIsString($commento->getUtenteId());
        }
    }

    public function test_03_getById_Exists()
    {
        $id = 1;
        $expected = new Commento(1, 1, 1, 'Bellissima foto!');
        $actual = $this->commentoMapper->getById($id);
        $this->assertEquals($expected, $actual);
    }

    public function test_04_getById_NotExists()
    {
        $id = 999;
        $actual = $this->commentoMapper->getById($id);
        $this->assertNull($actual);
    }

    public function test_05_insert()
    {
        $fotografia_id = 1;
        $utente_id = 2;
        $contenuto = 'New comment';
        $this->commentoMapper->insert($fotografia_id, $utente_id, $contenuto);

        $actual = $this->commentoMapper->getAllOfPhoto($fotografia_id);
        $found = false;
        foreach($actual as $commento){
            if($commento->getContenuto() == $contenuto){
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function test_06_delete()
    {
        $id = 1;
        $this->commentoMapper->delete($id);

        $actual = $this->commentoMapper->getById($id);
        $this->assertNull($actual);
    }

    public function test_07_update()
    {
        $id = 1;
        $newContent = 'Updated comment';
        $this->commentoMapper->update($id, $newContent);

        $expected = new Commento($id, 1, 1, $newContent);
        $actual = $this->commentoMapper->getById($id);

        $this->assertEquals($expected, $actual);
    }
}