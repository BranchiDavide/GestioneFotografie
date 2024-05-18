<?php
use PHPUnit\Framework\TestCase;

class FotografiaMapperTest extends TestCase
{
    private $fotografiaMapper;
    private $db;

    public static function setUpBeforeClass(): void
    {
        // Caricare variabili per connessione DB da tests/.env
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
        $dotenv->load();
    }

    protected function setUp(): void
    {
        $this->fotografiaMapper = new FotografiaMapper();
        $this->db = Database::getConnection();
        $this->db->query("START TRANSACTION");
    }

    protected function tearDown(): void
    {
        $this->db->query("ROLLBACK");
    }

    public function test_01_GetAll()
    {
        $expectedFotografie = [
            new Fotografia(1, 'public/datastore/uuid1.jpg', '2024-05-12 16:22:30', 'Lucerna', 'Paesaggio urbano', 'colori', 0, 2),
            new Fotografia(2, 'public/datastore/uuid2.jpg', '2024-05-12 16:22:30', 'Zurigo', 'Architettura', 'b/n', 0, 2),
            new Fotografia(3, 'public/datastore/uuid3.jpg', '2024-05-12 16:22:30', 'Lugano', 'Ritratto', 'colori', 0, 2),
        ];
        $actualFotografie = $this->fotografiaMapper->getAll();
        $this->assertEquals($expectedFotografie, $actualFotografie);
    }

    public function test_02_GetById_Exists()
    {
        $expectedFotografia = new Fotografia(1, 'public/datastore/uuid1.jpg', '2024-05-12 16:22:30', 'Lucerna', 'Paesaggio urbano', 'colori', 0, 2);
        $actualFotografia = $this->fotografiaMapper->getById(1);
        $this->assertEquals($expectedFotografia, $actualFotografia);
    }

    public function test_03_GetById_NotExists()
    {
        $actualFotografia = $this->fotografiaMapper->getById(100);
        $this->assertNull($actualFotografia);
    }

    public function test_04_GetFotografieOfUtente()
    {
        $expectedFotografie = [
            new Fotografia(1, 'public/datastore/uuid1.jpg', '2024-05-12 16:22:30', 'Lucerna', 'Paesaggio urbano', 'colori', 0, 2),
            new Fotografia(2, 'public/datastore/uuid2.jpg', '2024-05-12 16:22:30', 'Zurigo', 'Architettura', 'b/n', 0, 2),
            new Fotografia(3, 'public/datastore/uuid3.jpg', '2024-05-12 16:22:30', 'Lugano', 'Ritratto', 'colori', 0, 2),
        ];
        $actualFotografie = $this->fotografiaMapper->getFotografieOfUtente(2);
        $this->assertEquals($expectedFotografie, $actualFotografie);
    }

    public function test_05_Insert()
    {
        $this->fotografiaMapper->insert('public/datastore/new_uuid.jpg', '2024-05-17 12:00:00', 'Ginevra', '','colori', 0, 1);
        $actualFotografia = $this->fotografiaMapper->getFotografieOfUtente(1);
        $found = false;
        foreach($actualFotografia as $fotografia){
            if($fotografia->getPath() == 'public/datastore/new_uuid.jpg'){
                $found = true;
            }
        }
        $this->assertTrue($found);
    }

    public function test_06_changeIdUtenteToName(){
        $fotografie = $this->fotografiaMapper->getAll();
        $result = $this->fotografiaMapper->changeIdUtenteToName($fotografie);
        foreach($result as $r){
            $this->assertInstanceOf(Fotografia::class, $r);
            $this->assertIsString($r->getUtenteId());
        }
    }

    public function test_07_incrementViews(){
        $views = $this->fotografiaMapper->getById(1)->getVisualizzazioni();
        $expected = $views + 1;
        $this->fotografiaMapper->incrementViews(1);
        $actual = $this->fotografiaMapper->getById(1)->getVisualizzazioni();
        $this->assertEquals($expected, $actual);
    }

    public function test_08_GetClassifica()
    {
        $classifica = $this->fotografiaMapper->getClassifica();
        $this->assertNotEmpty($classifica);
        $scoreValue = 100;
        foreach($classifica as $fotografia){
            $this->assertInstanceOf(Fotografia::class, $fotografia[0]);
            $score = $fotografia[1];
            $this->assertLessThanOrEqual($scoreValue, $score);
            $scoreValue = $score;
        }
    }

    public function test_09_GetClassificaByVisualizzazioni()
    {
        $this->fotografiaMapper->incrementViews(1);
        $this->fotografiaMapper->incrementViews(1);
        $this->fotografiaMapper->incrementViews(1);
        $this->fotografiaMapper->incrementViews(2);

        $classifica = $this->fotografiaMapper->getClassificaByVisualizzazioni();
        $this->assertNotEmpty($classifica);
        $viewsValue = 100;
        foreach($classifica as $fotografia){
            $this->assertInstanceOf(Fotografia::class, $fotografia);
            $views = $fotografia->getVisualizzazioni();
            $this->assertLessThanOrEqual($viewsValue, $views);
            $viewsValue = $views;
        }
    }

    public function test_10_GetClassifica3Best()
    {
        $classifica = $this->fotografiaMapper->getClassifica3Best();
        $this->assertNotEmpty($classifica);
        $scoreValue = 100;
        foreach($classifica as $fotografia){
            $this->assertInstanceOf(Fotografia::class, $fotografia[0]);
            $score = $fotografia[1];
            $this->assertLessThanOrEqual($scoreValue, $score);
            $scoreValue = $score;
        }
    }

    /*
     * Inizio test della ricerca con filtri
     */

    public function test_11_SearchByDataOra()
    {
        $filters = ['data_ora'];
        $value = '2024-05-12 16:22:30';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $this->assertStringContainsString($value, $fotografia->getDataOra());
        }
    }

    public function test_12_SearchByLuogo()
    {
        $filters = ['luogo'];
        $value = 'Lucerna';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $this->assertStringContainsString($value, $fotografia->getLuogo());
        }
    }

    public function test_13_SearchBySoggetto()
    {
        $filters = ['soggetto'];
        $value = 'Paesaggio urbano';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $this->assertStringContainsString($value, $fotografia->getSoggetto());
        }
    }

    public function test_14_SearchByTipologia()
    {
        $filters = ['tipologia'];
        $value = 'colori';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $this->assertStringContainsString($value, $fotografia->getTipologia());
        }
    }

    public function test_15_SearchByVisualizzazioni()
    {
        $filters = ['visualizzazioni'];
        $value = 0;

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $this->assertEquals($value, $fotografia->getVisualizzazioni());
        }
    }

    public function test_16_SearchByNomeFotografo()
    {
        $filters = ['nome_fotografo'];
        $value = 'Luigi';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $fotografia = $this->fotografiaMapper->changeIdUtenteToName($fotografia);
            $this->assertStringContainsString($value, $fotografia->getUtenteId());
        }
    }

    public function test_17_SearchByCognomeFotografo()
    {
        $filters = ['cognome_fotografo'];
        $value = 'Verdi';

        $result = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($result);
        foreach ($result as $fotografia) {
            $fotografia = $this->fotografiaMapper->changeIdUtenteToName($fotografia);
            $this->assertStringContainsString($value, $fotografia->getUtenteId());
        }
    }

    public function test_18_SearchCombined()
    {
        $value = "Lu";
        $filters = [
            'data_ora',
            'luogo',
            'soggetto',
            'tipologia',
            'visualizzazioni',
            'cognome_fotografo'
        ];

        $actual = $this->fotografiaMapper->search($filters, $value);

        $this->assertIsArray($actual);
        $expected = [
            new Fotografia(1, 'public/datastore/uuid1.jpg', '2024-05-12 16:22:30', 'Lucerna', 'Paesaggio urbano', 'colori', 0, 2),
            new Fotografia(3, 'public/datastore/uuid3.jpg', '2024-05-12 16:22:30', 'Lugano', 'Ritratto', 'colori', 0, 2)
        ];
        $this->assertEquals($expected, $actual);
    }

    /*
     * Fine test ricerca filtri
     */

    public function test_19_ConvertFotografieArrayToJson()
    {
        $fotografie = $this->fotografiaMapper->getAll();

        $result = $this->fotografiaMapper->convertFotografieArrayToJson($fotografie);

        $expected = [
            [
                'id' => 1,
                'path' => 'public/datastore/uuid1.jpg',
                'data_ora' => '2024-05-12 16:22:30',
                'luogo' => 'Lucerna',
                'soggetto' => 'Paesaggio urbano',
                'tipologia' => 'colori',
                'visualizzazioni' => 0,
                'utente_id' => 2
            ],
            [
                'id' => 2,
                'path' => 'public/datastore/uuid2.jpg',
                'data_ora' => '2024-05-12 16:22:30',
                'luogo' => 'Zurigo',
                'soggetto' => 'Architettura',
                'tipologia' => 'b/n',
                'visualizzazioni' => 0,
                'utente_id' => 2
            ],
            [
                'id' => 3,
                'path' => 'public/datastore/uuid3.jpg',
                'data_ora' => '2024-05-12 16:22:30',
                'luogo' => 'Lugano',
                'soggetto' => 'Ritratto',
                'tipologia' => 'colori',
                'visualizzazioni' => 0,
                'utente_id' => 2
            ]
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_20_GetNumberOfUserPhotos()
    {
        $utente_id = 2;
        $result = $this->fotografiaMapper->getNumberOfUserPhotos($utente_id);
        $this->assertEquals(3, $result);
    }

    public function test_21_GetMostRatedPhotosChartJsFormat()
    {
        $utente_id = 2;
        $result = $this->fotografiaMapper->getMostRatedPhotosChartJsFormat($utente_id);

        $expected = [
            'xValues' => ['Lugano, 2024-05-12', 'Lucerna, 2024-05-12', 'Zurigo, 2024-05-12'],
            'yValues' => [5.0000, 4.5000, 4.0000],
            'ids' => [3, 1, 2]
        ];

        $this->assertEquals($expected, $result);
    }

    public function test_22_GetMostViewedPhotosChartJsFormat()
    {
        $utente_id = 2;
        $result = $this->fotografiaMapper->getMostViewedPhotosChartJsFormat($utente_id);

        $expected = [
            'xValues' => ['Lucerna, 2024-05-12', 'Zurigo, 2024-05-12', 'Lugano, 2024-05-12'],
            'yValues' => [0, 0, 0],
            'ids' => [1, 2, 3]
        ];

        $this->assertEquals($expected, $result);
    }
}