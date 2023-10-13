<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

/**
 * Funzione per ottenere l'elenco delle commesse del mio team
 * @param string $id_utente
 * @return array
 */
function listaCommesseTeams(string $id_utente){

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT `tb_commesse_teams`.`id_commessa` AS `id_commessa`,
        `tb_commesse`.`codice` AS `codice`,
        `tb_commesse`.`anno` AS `anno`,
        `tb_commesse`.`cliente` AS `cliente`,
        `tb_commesse`.`localizzazione` AS `localizzazione`,
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`,
        `tb_commesse`.`chiusa` AS `chiusa`
        FROM ((`tb_membri_team` 
            INNER JOIN (`tb_teams` 
                INNER JOIN (`tb_commesse_teams` 
                    INNER JOIN `tb_commesse` ON `tb_commesse_teams`.`id_commessa` = `tb_commesse`.`id`) ON `tb_teams`.`id` = `tb_commesse_teams`.`id_team`) ON `tb_membri_team`.`id_team` = `tb_teams`.`id`) 
            INNER JOIN `tb_utenti` ON `tb_membri_team`.`id_utente` = `tb_utenti`.`id`) 
        WHERE `tb_utenti`.`id` = :id_utente AND `tb_commesse`.`chiusa` = 0;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id_utente' => $id_utente));

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $list;
}

/**
 * Funzione che restituisce la schedulazione delle commesse dell'utente in un arco di tempo
 * @param string $id_utente
 * @param string $start_date
 * @param string $end_date
 * @return array|false
 */
function schedulerData(string $id_utente, string $start_date, string $end_date){

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT 
        `tb_rendicontazioni`.`data` AS `data`, 
        `tb_rendicontazioni`.`id_commessa` AS `id_commessa`, 
        `tb_commesse`.`codice` AS `codice`,
        `tb_commesse`.`cliente` AS `cliente`,
        `tb_rendicontazioni`.`start_date` AS `start_date`, 
        `tb_rendicontazioni`.`end_date` AS `end_date`, 
        `tb_rendicontazioni`.`note` AS `note`
        FROM (`tb_rendicontazioni` INNER JOIN `tb_commesse` ON `tb_rendicontazioni`.`id_commessa` = `tb_commesse`.`id`)
        WHERE `tb_rendicontazioni`.`id_utente` = :id_utente
        AND `tb_rendicontazioni`.`start_date` >= :start_date AND `tb_rendicontazioni`.`end_date` <= :end_date;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id_utente' => $id_utente, ':start_date' => $start_date, ':end_date' => $end_date));

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $list;

}

function convertDateUTC(string $datetime){

    return substr($datetime, 0, 10)."T".substr($datetime, 11).".000Z";

}




# http://localhost:8099/dms/php/dati_commesse.php

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

$start_date = isset($_REQUEST['startDate']) ? $_REQUEST['startDate'] : '2022-06-01 00:00:00';
$end_date = isset($_REQUEST['endDate']) ? $_REQUEST['endDate'] : '2022-06-30 23:59:59';

$array_color = array();

function getColor($num) {
    $hash = md5('color' . $num);
    return array(substr($hash, 0, 2), substr($hash, 2, 2), substr($hash, 4, 2));
}

$nums = range(0, 199);
$k = 0;

foreach ($nums as $num) {

    list($r,$g,$b) = getColor($num);
    $array_color[$k] = "#".$r.$g.$b;
    $k ++;

}

$lista_commesse_team =  listaCommesseTeams($_SESSION['ID']);

$scheduler_commesse = schedulerData($_SESSION['ID'], $start_date, $end_date);

$scheduler_data = [];

foreach ($scheduler_commesse as $item) {

    $z = array_push($scheduler_data,
        array('id' => intval($item['id_commessa']),
            'text' => 'Commessa ' . $item['codice'],
            'cliente' => $item['cliente'],
            'startDate' => convertDateUTC($item['start_date']),
            'endDate' => convertDateUTC($item['end_date']),
            'note' => $item['note']
        ));

}

$commesse_push = [];

$x = 0;

$d = array_push($commesse_push, array('id' => 83, 'text' => 'Commessa X', 'cliente' => 'VARIE-COORDINAMENTO', 'year' => '2022', 'color' => $array_color[$x]));

$x ++;

foreach ($lista_commesse_team as $item) {


    $h = array_push($commesse_push, array('id' => intval($item['id_commessa']), 'text' => 'Commessa ' . $item['codice'], 'cliente' => $item['cliente'], 'year' => $item['anno'], 'color' => $array_color[$x]));

    $x ++;

}

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("commesseData" => $commesse_push, "schedulerData" => $scheduler_data));
exit;

