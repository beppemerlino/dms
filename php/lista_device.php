<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# http://localhost/dms/php/lista_device.php
# https://www.inc-intranet.it/php/lista_device.php

$dominio = '';
#$dominio = 'https://www.inc-intranet.it';

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

/**
 * Ritorna la lista dei pc
 * @return array
 */
function listaDevice() : array {

    $dbh = new Db_inc();

    global $dominio;

    $query = "SELECT 
        `tb_devices`.`id` AS `id_device`, 
        `tb_devices`.`nome`, 
        `tb_devices`.`vendor`, 
        `tb_devices`.`model`, 
        `tb_devices`.`foto`, 
        `tb_devices`.`id_workstation`, 
        `tb_workstations`.`nome` AS `nome_workstation`,
        `tb_devices`.`serial_number`, 
        `tb_devices`.`rif_cespite`, 
        `tb_devices`.`part_number`, 
        `tb_devices`.`description` 
        FROM (`tb_devices` INNER JOIN `tb_workstations` ON `tb_devices`.`id_workstation` = `tb_workstations`.`id`) ORDER BY `tb_devices`.`id` DESC;";


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_ASSOC);

}


$lista_device = listaDevice();


HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("lista_device" => $lista_device), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;