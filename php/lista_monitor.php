<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# http://localhost/dms/php/lista_monitor.php
# https://www.inc-intranet.it/php/lista_monitor.php

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
function listaMonitor() : array {

    $dbh = new Db_inc();

    global $dominio;

    $query = "SELECT
            `tb_monitors`.`id` AS `id_monitor`, 
            `tb_monitors`.`nome`, 
            `tb_monitors`.`vendor`, 
            `tb_monitors`.`model`, 
            `tb_monitors`.`foto`, 
            `tb_monitors`.`id_workstation`, 
            `tb_workstations`.`nome` AS `nome_workstation`
            `tb_monitors`.`serial_number`, 
            `tb_monitors`.`rif_cespite`, 
            `tb_monitors`.`part_number`, 
            `tb_monitors`.`resolution`, 
            `tb_monitors`.`inc_size`, 
            `tb_monitors`.`hdmi_port`, 
            `tb_monitors`.`dvi_port`, 
            `tb_monitors`.`display_port`, 
            `tb_monitors`.`mdisplay_port`, 
            `tb_monitors`.`thunderbolt_port`, 
            `tb_monitors`.`power_supply` 
        FROM (`tb_monitors` INNER JOIN `tb_workstations` ON `tb_monitors`.`id_workstation` = `tb_workstations`.`id`) ORDER BY `tb_monitors`.`id` DESC;";


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_ASSOC);

}


$lista_monitor = listaMonitor();


HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("lista_monitor" => $lista_monitor), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;