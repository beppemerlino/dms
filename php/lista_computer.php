<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# http://localhost/dms/php/lista_computer.php
# https://www.inc-intranet.it/php/lista_computer.php

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
function listaComputer() : array {

    $dbh = new Db_inc();

    global $dominio;

    $query = "SELECT 
        `tb_pcs`.`id` AS `id_computer`,
        `tb_pcs`.`nome`, 
        `tb_pcs`.`vendor`, 
        `tb_pcs`.`model`, 
        `tb_pcs`.`cpu_1`, 
        `tb_pcs`.`cpu_2`, 
        `tb_pcs`.`operative_system`, 
        `tb_pcs`.`keyboard`, 
        `tb_pcs`.`mouse`, 
        `tb_pcs`.`foto` AS `foto`, 
        `tb_pcs`.`id_workstation`, 
        `tb_workstations`.`nome` AS `nome_workstation`,
        `tb_pcs`.`serial_number`, 
        `tb_pcs`.`rif_cespite`, 
        `tb_pcs`.`part_number`, 
        `tb_pcs`.`form_factory`, 
        `tb_pcs`.`ram_size`, 
        `tb_pcs`.`primary_disk_size`, 
        `tb_pcs`.`secondary_disk_size`, 
        `tb_pcs`.`dvd_rom`, 
        `tb_pcs`.`video_card`, 
        `tb_pcs`.`bluetooth`, 
        `tb_pcs`.`ethernet_1`, 
        `tb_pcs`.`ethernet_2`, 
        `tb_pcs`.`ip_address_1`, 
        `tb_pcs`.`ip_address_2`, 
        `tb_pcs`.`mac_ethernet_1`, 
        `tb_pcs`.`mac_ethernet_2`, 
        `tb_pcs`.`hdmi_port`, 
        `tb_pcs`.`dvi_port`, 
        `tb_pcs`.`display_port`, 
        `tb_pcs`.`mdisplay_port`, 
        `tb_pcs`.`thunderbolt_port`, 
        `tb_pcs`.`wifi_card`, 
        `tb_pcs`.`audio_card`, 
        `tb_pcs`.`num_usb`, 
        `tb_pcs`.`num_usb_3`, 
        `tb_pcs`.`power_supply`, 
        `tb_pcs`.`power_cell` 
    FROM (`tb_pcs` INNER JOIN `tb_workstations` ON `tb_pcs`.`id_workstation` = `tb_workstations`.`id`) ORDER BY `tb_pcs`.`id` DESC;";

    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_ASSOC);

}


$lista_computer = listaComputer();

HTTPStatus(200);
HTTPContentType('json');
    echo json_encode(array("lista_pc" => $lista_computer), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;