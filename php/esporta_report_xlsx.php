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



function reportList($date1, $date2, $anno = '')
{

    $claus = 'GROUP BY `tb_commesse`.`id` ORDER BY `tb_commesse`.`id` DESC';
    if ($anno != '') $claus = "AND `tb_commesse`.`anno` = '". $anno ."' GROUP BY `tb_commesse`.`id` ORDER BY `tb_commesse`.`id` DESC";
    $dbh = new Db_inc();
    $query = sprintf("SELECT 
        `tb_commesse`.`id` AS `id_commessa`, 
        `tb_commesse`.`codice` AS `codice`, 
        `tb_commesse`.`anno` AS `anno`, 
        `tb_commesse`.`cliente` AS `cliente`, 
        `tb_commesse`.`localizzazione` AS `localizzazione`, 
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`, 
        `tb_commesse`.`chiusa` AS `chiusa`,
        SUM(`tb_rendicontazioni`.`num_ore`) AS `tot_ore`
        FROM (`tb_commesse` INNER JOIN `tb_rendicontazioni` ON `tb_commesse`.`id` = `tb_rendicontazioni`.`id_commessa`) 
            WHERE `tb_rendicontazioni`.`data` >= :start_date AND `tb_rendicontazioni`.`data` <= :end_date %s ", $claus);


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":start_date" => $date1, ":end_date" => $date2));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}



$anno = $array['anno'];
$date1 = $array['start_date'];
$date2 = $array['end_date'];


$lista_rendicontazioni = reportList($date1, $date2, $anno);


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$ss= new Spreadsheet();

$ss->setActiveSheetIndex(0);
$ss->getActiveSheet()->setTitle('REPORT');



$ss->setActiveSheetIndex(0)
    ->setCellValue("A1", 'CODICE COMMESSA')
    ->setCellValue("B1", 'ANNO')
    ->setCellValue("C1", 'CLIENTE')
    ->setCellValue("D1", 'LOCALIZZAZIONE')
    ->setCellValue("E1", 'TIPO LAVORO')
    ->setCellValue("F1", 'TOTALE ORE')
    ->setCellValue("G1", 'COMMESSA CHIUSA');

$row = 2;

foreach ($lista_rendicontazioni as $item){
    $ss->setActiveSheetIndex(0)
        ->setCellValue("A" . $row, $item["codice"])
        ->setCellValue("B" . $row, $item["anno"])
        ->setCellValue("C" . $row, $item["cliente"])
        ->setCellValue("D" . $row, $item["localizzazione"])
        ->setCellValue("E" . $row, $item["tipo_lavoro"])
        ->setCellValue("F" . $row, $item["tot_ore"])
        ->setCellValue("G" . $row, ($item["chiusa"] == "0") ? "NO" : "SI");

    $row ++;
}


$writer = IOFactory::createWriter($ss, 'Xlsx');

HTTPStatus(200);
HTTPContentType('xlsx');
$writer->save('php://output');
exit;