<?php
use Ramsey\Uuid\Uuid;

class upload
{
    public function index(){
        if(Session::hasSessionType()){
            if(Session::isFotografo()){
                if($_SERVER["REQUEST_METHOD"] == "POST"){
                    try{
                        $fileName = $_FILES["file"]["name"];
                        Sanitizer::isSetted($fileName);
                        $fileTmpName = $_FILES["file"]["tmp_name"];
                        if(!is_uploaded_file($fileTmpName)){
                            Twig::render("upload/upload.twig", ["errorMessage" => "Errore durante il caricamento dell'immagine"]);
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
                        // Save image path to db not implemented yet
                        Twig::render("_templates/successPage.twig", ["successMsg" => "Fotografia caricata con successo!"]);
                    }catch (Exception $exp){
                        Twig::render("upload/upload.twig", ["errorMessage" => "Non è stata caricata alcuna immagine!"]);
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