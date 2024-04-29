<?php
use Ramsey\Uuid\Uuid;

class upload
{
    public function index(){
        if(Session::hasSessionType()){
            if(Session::isFotografo()){
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    try{
                        $dataOra = Sanitizer::sanitize($_POST["data-ora"]);
                        $luogo = Sanitizer::sanitize($_POST["luogo"]);
                        $soggetto = null;
                        if($_POST["soggetto"]){
                            $soggetto = Sanitizer::sanitize($_POST["soggetto"]);
                        }
                        $tipologia = Sanitizer::sanitize($_POST["tipologia"]);
                        if($tipologia != "b/n" && $tipologia != "colori"){
                            Twig::render("upload/upload.twig", ["errorMessage" => "Tipologia non valida!"]);
                            return;
                        }
                        // Gestione dell'upload del file
                        $fileName = $_FILES["file"]["name"];
                        Sanitizer::isSetted($fileName);
                        $fileTmpName = $_FILES["file"]["tmp_name"];
                        if(!is_uploaded_file($fileTmpName)){
                            Twig::render("upload/upload.twig", ["errorMessage" => "Errore durante il caricamento dell'immagine"]);
                            return;
                        }
                        $fileRawExt = explode(".", $fileName);
                        $fileExt = strtolower(end($fileRawExt));
                        $allowedExt = array("jpg", "jpeg", "png");
                        if(!in_array($fileExt, $allowedExt)){
                            Twig::render("upload/upload.twig", ["errorMessage" => "Formato dell'immagine .{$fileExt} non valido!"]);
                            return;
                        }
                        $allowedMime = array("image/jpeg", "image/png");
                        $mimeType = mime_content_type($fileTmpName);
                        if(!in_array($mimeType, $allowedMime)){
                            Twig::render("upload/upload.twig", ["errorMessage" => "Formato dell'immagine {$mimeType} non valido!"]);
                            return;
                        }
                        $uuid = Uuid::uuid4()->toString();
                        $filePath = "public/datastore/" . $uuid . "." . $fileExt;
                        move_uploaded_file($fileTmpName, $filePath);

                        $fotografiaMapper = new FotografiaMapper();
                        $fotografiaMapper->insert($filePath, $dataOra, $luogo, $soggetto, $tipologia, 0, $_SESSION["utente-id"]);

                        Twig::render("_templates/successPage.twig", ["successMsg" => "Fotografia caricata con successo!"]);
                    }catch (Exception $exp){
                        Twig::render("upload/upload.twig", ["errorMessage" => "Non sono stati compilati tutti i campi!"]);
                    }
                }else{
                    Twig::render('upload/upload.twig');
                }
            }else{
                Twig::render("_templates/errorPage.twig", ["errorMsg" => "Non sei autorizzato ad accedere a questa pagina!"]);
            }
        }else{
            header("Location: " . URL . "login");
        }
    }
}