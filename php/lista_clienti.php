<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# // http://localhost:8099/dms/php/lista_clienti.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

function clientList($keyword){

    $dbh = new Db_inc();
    $query = "SELECT DISTINCT `cliente` FROM `tb_commesse` WHERE `cliente` LIKE :cliente;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":cliente" => "%".$keyword ."%"));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}

$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("lista_clienti" => clientList($keyword)), JSON_PARTIAL_OUTPUT_ON_ERROR);


} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}






