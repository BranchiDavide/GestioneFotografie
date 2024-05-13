<?php

use PHPUnit\Framework\TestCase;

class ValutazioneMapperTest extends TestCase
{
    private $valutazioneMapper;
    private $db;

    public static function setUpBeforeClass(): void
    {
        // Caricare variabili per connessione DB da tests/.env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
        $dotenv->load();
    }

    protected function setUp(): void
    {
        $this->valutazioneMapper = new ValutazioneMapper();
        $this->db = Database::getConnection();
        $this->db->query("START TRANSACTION");
    }

    protected function tearDown(): void
    {
        $this->db->query("ROLLBACK");
    }

    public function test_01_getScore_Exists()
    {
        $fotografia_id = 1;
        $expected = 4.5;
        $actual = $this->valutazioneMapper->getScore($fotografia_id);
        $this->assertEquals($expected, $actual);
    }

    public function test_02_getScore_NotExists()
    {
        $nonExistentFotografiaId = 100;
        $score = $this->valutazioneMapper->getScore($nonExistentFotografiaId);
        $this->assertNull($score);
    }

    public function test_03_getAll()
    {
        $fotografia_id = 1;
        $expectedLength = 2;
        $actual = $this->valutazioneMapper->getAll($fotografia_id);
        $this->assertIsArray($actual);
        $this->assertEquals($expectedLength, count($actual));
    }

    public function test_04_changeIdUtenteToName()
    {
        $valutazioni = $this->valutazioneMapper->getAll(1);
        $result = $this->valutazioneMapper->changeIdUtenteToName($valutazioni);
        foreach($result as $r){
            $this->assertInstanceOf(Valutazione::class, $r);
            $this->assertIsString($r->getUtenteId());
        }
    }

    public function test_05_insertValutazione()
    {
        $fotografia_id = 3;
        $utente_id = 1;
        $stelle = 1;
        $this->valutazioneMapper->insertValutazione($fotografia_id, $utente_id, $stelle);

        $expected = new Valutazione($fotografia_id,$utente_id,$stelle);
        $actual = $this->valutazioneMapper->getAll($fotografia_id);
        $this->assertContainsEquals($expected, $actual);
    }

    public function test_06_updateValutazione()
    {
        $fotografia_id = 1;
        $utente_id = 1;
        $stelle = 4;
        $this->valutazioneMapper->updateValutazione($fotografia_id, $utente_id, $stelle);
        $expected = new Valutazione($fotografia_id,$utente_id,$stelle);
        $actual = $this->valutazioneMapper->getAll($fotografia_id);
        $this->assertContainsEquals($expected, $actual);
    }

    public function test_07_deleteValutazione()
    {
        $fotografia_id = 1;
        $utente_id = 1;
        $stelle = 5;
        $this->valutazioneMapper->deleteValutazione($fotografia_id, $utente_id);
        $expected = new Valutazione($fotografia_id,$utente_id,$stelle);
        $actual = $this->valutazioneMapper->getAll($fotografia_id);
        $this->assertNotContainsEquals($expected, $actual);
    }

    public function test_08_getValutazioneByUserId_Exists()
    {
        $fotografia_id = 1;
        $utente_id = 1;
        $valutazione = $this->valutazioneMapper->getValutazioneByUserId($fotografia_id, $utente_id);
        $this->assertInstanceOf(Valutazione::class, $valutazione);
    }

    public function test_09_getValutazioneByUserId_NotExists()
    {
        $fotografia_id = 1;
        $nonExistentUtenteId = 100;
        $valutazione = $this->valutazioneMapper->getValutazioneByUserId($fotografia_id, $nonExistentUtenteId);
        $this->assertNull($valutazione);
    }
}