<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

# http://localhost:8099/dms/php/lista_tipi_lavoratore.php

function listaLavoratori()
{
    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT `tb_lavoratori`.`id` AS `id`, `tb_lavoratori`.`tipo_lavoratore` AS `tipo_lavoratore`  FROM `tb_lavoratori` WHERE 1";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);
    $array = [];
    $k = 0;

    foreach ($list as $item) {

        $array[$k]['value'] = intval($item['id']);
        $array[$k]['text'] = $item['tipo_lavoratore'];

        $k ++;

    }

    return $array;

}


HTTPStatus(200);
HTTPContentType('json');
echo json_encode(listaLavoratori());
exit;


