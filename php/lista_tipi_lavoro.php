<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/lista_tipi_lavoro.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}


function worktypeList($keyword){

    $dbh = new Db_inc();
    $query = "SELECT DISTINCT `tipo_lavoro` FROM `tb_commesse` WHERE `tipo_lavoro` LIKE :tipo_lavoro;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":tipo_lavoro" => "%".$keyword ."%"));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}

$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("lista_tipi_lavoro" => worktypeList($keyword)), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;