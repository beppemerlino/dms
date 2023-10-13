<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/lista_utenti.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

} else {
  // code...
  if ($_SESSION['ID'] == 1) $super_admin = true; else $super_admin = false;
  if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS') $gruppo_admin = true; else $gruppo_admin = false;

}


function SimpleList()
{

    $dbh = new Db_inc();
    $query = "SELECT `tb_utenti`.`id` AS `id`,
        `tb_utenti`.`username` AS `username`,
        `tb_utenti`.`nome` AS `nome`,
        `tb_utenti`.`cognome` AS `cognome`,
        `tb_utenti`.`sesso` AS `sesso`,
        `tb_utenti`.`titolo` AS `titolo`,
        `tb_utenti`.`mansione` AS `mansione`,
        `tb_utenti`.`email` AS `email`,
        `tb_utenti`.`email2` AS `email2`,
        `tb_utenti`.`group` AS `group`,
        `tb_utenti`.`telefono` AS `telefono`,
        `tb_utenti`.`note` AS `note`,
        `tb_utenti`.`attivo` AS `attivo`,
        `tb_utenti`.`tipo_lavoratore` AS `tipo_lavoratore`,
        `tb_lavoratori`.`tipo_lavoratore` AS `lavoratore`,
        IF((SELECT `id_utente` FROM `tb_rendicontazioni` WHERE `id_utente` = `tb_utenti`.`id` LIMIT 0, 1) IS NOT NULL, 1, 0) AS `rendicontato`,
        `tb_utenti`.`foto` AS `foto`
    FROM (`tb_utenti` INNER JOIN `tb_lavoratori` ON `tb_utenti`.`tipo_lavoratore` = `tb_lavoratori`.`id`) WHERE 1;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}



if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $lista = SimpleList();


    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("lista_utenti" => $lista, "super_admin" => $super_admin, "gruppo_admin" => $gruppo_admin), JSON_PARTIAL_OUTPUT_ON_ERROR);

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}
