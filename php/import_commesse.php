<?php
require_once 'db_pdo/database.php';
require_once 'db_pdo/Commessa.php';
require_once 'db_pdo/CommessaTeam.php';
require_once('common_function/code_header.php');
require_once 'vendor/autoload.php';

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

if($_SERVER['REQUEST_METHOD'] != "POST"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "Metodo non autorizzato!";

    HTTPStatus(405);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;
}

use PhpOffice\PhpSpreadsheet\IOFactory;

$storage = '../files_xlsx';
$j = 0;
$errors = 0;
$array_team = [];


if (count($_FILES) > 0) {
//path name of file for storage
    $ora = date("Y-m-d H:i:s");
    $nome_file = $_FILES['file_xlsx']['name'];
    $filesize = $_FILES['file_xlsx']['size'];
    $ext = strtolower(substr(strrchr($nome_file, "."), 1));

    if ($ext != 'xlsx' && $ext != 'xls') {

        HTTPStatus(200);
        HTTPContentType('json');
        echo json_encode(array('message' => "Errore di trasmissione: Il file inviato non rispetta le caratteristiche richieste! Mi hai inviato un file " . strtoupper($ext) . "!"), JSON_PARTIAL_OUTPUT_ON_ERROR);
        exit;

    }

    $uploadfile = "$storage/" . $nome_file;




    if (move_uploaded_file($_FILES['file_xlsx']['tmp_name'], $uploadfile)) {

        $spreadsheet = IOFactory::load($uploadfile);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        for($k = 2; $k <= count($sheetData); $k ++){

            $commessa = new Commessa();

            $array_team = (array) json_decode($sheetData[$k]["A"], true);
            //print_r($array_team);exit;

            $commessa->anno = $sheetData[$k]["B"];
            $commessa->codice = $sheetData[$k]["C"];
            $commessa->cliente = $sheetData[$k]["D"];
            $commessa->localizzazione = $sheetData[$k]["E"];
            $commessa->tipo_lavoro = $sheetData[$k]["F"];

            $commessa->chiusa = 0;

            try{

                $commessa->insert();
                $j ++;

                foreach ($array_team as $item) {

                    $commessa_team = new CommessaTeam();
                    $commessa_team->id_commessa = $commessa->id;
                    $commessa_team->id_team = $item['id_team'];
                    $commessa_team->insert();

                }



            } catch (Exception $e){

                $messaggio = "Errore di inserimenti DB: " . $e->getMessage();
                $handler = fopen("logs/log_Insert_Commessa_".date('Y-m-d').".log", "a");
                fwrite($handler, chr(13).chr(10)."[".date('Y-m-d H:i:s')."]".chr(13).chr(10).$messaggio);
                fclose($handler);

                $errors ++;

            }

        }

    }
}

$messaggio = "Inserite :". $j . " Commesse in totale. Errori: ".$errors;

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("message" => $messaggio));
exit;
