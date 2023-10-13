<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Utente.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/lista_commesse.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

function siglaTeam($id_team){

    $dbh = new Db_inc();
    $query = "SELECT `id_teamleader` FROM `tb_teams` WHERE `id` = :id";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array("id" => $id_team));

    list($id_teamleader) = $sth->fetch();

    $teamleader = new Utente($id_teamleader);
    $siglaTeamleader = strtoupper(substr($teamleader->nome, 0, 1).substr($teamleader->cognome, 0, 1));


    return $siglaTeamleader;
}

function commesseList($anno = '')
{

    $claus = 'ORDER BY `codice` DESC';
    if ($anno != '') $claus = "WHERE `anno` = '". $anno ."' ORDER BY `codice` DESC";
    $dbh = new Db_inc();
    $query = sprintf("SELECT 
        `tb_commesse`.`id` AS `id`, 
        `tb_commesse`.`codice` AS `codice`, 
        `tb_commesse`.`anno` AS `anno`, 
        `tb_commesse`.`cliente` AS `cliente`, 
        `tb_commesse`.`localizzazione` AS `localizzazione`, 
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`, 
        `tb_commesse`.`chiusa` AS `chiusa`,
        IF((SELECT `id_commessa` FROM `tb_rendicontazioni` WHERE `id_commessa` = `tb_commesse`.`id` LIMIT 0, 1) IS NOT NULL, 1, 0) AS `rendicontata`
        FROM `tb_commesse` %s ", $claus);


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}

function teamsList($id_commessa){

    $dbh = new Db_inc();
    $query = "SELECT 
`tb_commesse_teams`.`id_team` AS `id_team`, 
`tb_teams`.`nome_team` AS `nome_team`   
FROM (`tb_commesse_teams` INNER JOIN `tb_teams` ON `tb_commesse_teams`.`id_team` = `tb_teams`.`id`) 
WHERE `tb_commesse_teams`.`id_commessa` = :id_commessa;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":id_commessa" => $id_commessa));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    $array = [];
    $k = 0;

    foreach ($res as $item) {
        $array[$k]['id_team'] = $item['id_team'];
        $array[$k]['nome_team'] = $item['nome_team'];
        $array[$k]['sigla'] = siglaTeam($item['id_team']);

        $k ++;
    }

    return $array;

}

$anno = isset($_REQUEST['anno']) ? $_REQUEST['anno'] : '';

$lista_commesse = commesseList($anno);

$arr_listacommesse = array();
$k = 0;

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    foreach ($lista_commesse as $item) {

        $arr_listacommesse[$k]['id'] = $item['id'];
        $arr_listacommesse[$k]['codice'] = $item['codice'];
        $arr_listacommesse[$k]['anno'] = $item['anno'];
        $arr_listacommesse[$k]['cliente'] = $item['cliente'];
        $arr_listacommesse[$k]['localizzazione'] = $item['localizzazione'];
        $arr_listacommesse[$k]['tipo_lavoro'] = $item['tipo_lavoro'];
        $arr_listacommesse[$k]['chiusa'] = $item['chiusa'];
        $arr_listacommesse[$k]['rendicontata'] = $item['rendicontata'];
        $arr_listacommesse[$k]['teams'] = teamsList($item['id']);

        $k++;

    }

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("lista_commesse" => $arr_listacommesse), JSON_PARTIAL_OUTPUT_ON_ERROR);


} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;