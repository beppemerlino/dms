<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/lista_rendicontazione.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

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



$anno = isset($_REQUEST['anno']) ? $_REQUEST['anno'] : '';
$date1 = isset($_REQUEST['date1']) ? $_REQUEST['date1'] : '';
$date2 = isset($_REQUEST['date2']) ? $_REQUEST['date2'] : '';




if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {
    $lista_report = reportList($date1, $date2, $anno);

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("lista_report" => $lista_report), JSON_PARTIAL_OUTPUT_ON_ERROR);

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;