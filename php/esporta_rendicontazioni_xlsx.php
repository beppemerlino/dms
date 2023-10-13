<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');
require 'vendor/autoload.php';


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

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");
$array = json_decode($json_data, TRUE);

function data_ita($data_usa)
{
    if (is_null($data_usa) || $data_usa == "" || $data_usa == "0000-00-00") {

        $_dataita = "";

    } else {

        $array = explode("-", $data_usa);

        if ($array[2]!="" && $array[1]!="" && $array[0]!="") $_dataita = $array[2]."/".$array[1]."/".$array[0]; else $_dataita="";
    }

    return $_dataita;
}

function rendicontazioniList($date1, $date2, $anno = '')
{

    $claus = 'ORDER BY `tb_rendicontazioni`.`data` DESC';
    if ($anno != '') $claus = "AND `tb_commesse`.`anno` = '". $anno ."' ORDER BY `tb_rendicontazioni`.`data` DESC";
    $dbh = new Db_inc();
    $query = sprintf("SELECT 
        `tb_rendicontazioni`.`id` AS `id`,
        `tb_commesse`.`id` AS `id_commessa`, 
        `tb_commesse`.`codice` AS `codice`, 
        `tb_commesse`.`anno` AS `anno`, 
        `tb_commesse`.`cliente` AS `cliente`, 
        `tb_commesse`.`localizzazione` AS `localizzazione`, 
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`, 
        `tb_commesse`.`chiusa` AS `chiusa`,
        `tb_rendicontazioni`.`data` AS `data`,
        `tb_rendicontazioni`.`num_ore` AS `num_ore`,
        `tb_utenti`.`id` AS `id_utente`,
        CONCAT(`tb_utenti`.`nome`, ' ', `tb_utenti`.`cognome`) AS `tecnico`,
        `tb_rendicontazioni`.`start_date` AS `start_date`,
        `tb_rendicontazioni`.`end_date` AS `end_date`,
        `tb_rendicontazioni`.`note` AS `note`
        FROM (`tb_commesse` INNER JOIN (`tb_rendicontazioni` INNER JOIN `tb_utenti` ON `tb_rendicontazioni`.`id_utente` = `tb_utenti`.`id`) ON `tb_commesse`.`id` = `tb_rendicontazioni`.`id_commessa`) 
            WHERE `tb_rendicontazioni`.`data` >= :start_date AND `tb_rendicontazioni`.`data` <= :end_date %s ", $claus);


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":start_date" => $date1, ":end_date" => $date2));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}



$anno = $array['anno'];
$date1 = $array['start_date'];
$date2 = $array['end_date'];

/*
print_r($array).chr(13).chr(10);
echo $anno.chr(13).chr(10);
echo $date1.chr(13).chr(10);
echo $date2.chr(13).chr(10);
exit;
*/

$lista_rendicontazioni = rendicontazioniList($date1, $date2, $anno);


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$ss= new Spreadsheet();

$ss->setActiveSheetIndex(0);
$ss->getActiveSheet()->setTitle('REPORT');



$ss->setActiveSheetIndex(0)
    ->setCellValue("A1", 'DATA')
    ->setCellValue("B1", 'CODICE COMMESSA')
    ->setCellValue("C1", 'ANNO')
    ->setCellValue("D1", 'TECNICO')
    ->setCellValue("E1", 'CLIENTE')
    ->setCellValue("F1", 'LOCALIZZAZIONE')
    ->setCellValue("G1", 'TIPO LAVORO')
    ->setCellValue("H1", 'NUMERO ORE')
    ->setCellValue("I1", 'DATA ORA INIZIALE')
    ->setCellValue("J1", 'DATA ORA FINALE')
    ->setCellValue("K1", 'COMMESSA CHIUSA')
    ->setCellValue("L1", 'NOTE');

$row = 2;

foreach ($lista_rendicontazioni as $item){
    $ss->setActiveSheetIndex(0)
        ->setCellValue("A" . $row, data_ita($item["data"]))
        ->setCellValue("B" . $row, $item["codice"])
        ->setCellValue("C" . $row, $item["anno"])
        ->setCellValue("D" . $row, $item["tecnico"])
        ->setCellValue("E" . $row, $item["cliente"])
        ->setCellValue("F" . $row, $item["localizzazione"])
        ->setCellValue("G" . $row, $item["tipo_lavoro"])
        ->setCellValue("H" . $row, $item["num_ore"])
        ->setCellValue("I" . $row, $item["start_date"])
        ->setCellValue("J" . $row, $item["end_date"])
        ->setCellValue("K" . $row, ($item["chiusa"] == "0") ? "NO" : "SI")
        ->setCellValue("L" . $row, $item["note"]);

    $row ++;
}


$writer = IOFactory::createWriter($ss, 'Xlsx');

HTTPStatus(200);
HTTPContentType('xlsx');
$writer->save('php://output');
exit;