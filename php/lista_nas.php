<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# http://localhost/dms/php/lista_nas.php
# https://www.inc-intranet.it/php/lista_nas.php

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
function listaNas() : array {

    $dbh = new Db_inc();

    global $dominio;

    $query = "SELECT 
            `tb_nas`.`id` AS `id_nas`, 
            `tb_nas`.`nome`, 
            `tb_nas`.`vendor`, 
            `tb_nas`.`model`, 
            `tb_nas`.`cpu_1`, 
            `tb_nas`.`cpu_2`, 
            `tb_nas`.`operative_system`, 
            `tb_nas`.`foto`, 
            `tb_nas`.`id_workstation`, 
            `tb_workstations`.`nome` AS `nome_workstation`,
            `tb_nas`.`serial_number`, 
            `tb_nas`.`rif_cespite`, 
            `tb_nas`.`part_number`, 
            `tb_nas`.`form_factory`, 
            `tb_nas`.`ram_size`, 
            `tb_nas`.`num_hd`, 
            `tb_nas`.`type_hd`, 
            `tb_nas`.`descr_raid`, 
            `tb_nas`.`ip_address_1`, 
            `tb_nas`.`ip_address_2`, 
            `tb_nas`.`bluetooth`, 
            `tb_nas`.`ethernet_1`, 
            `tb_nas`.`ethernet_2`, 
            `tb_nas`.`mac_ethernet_1`, 
            `tb_nas`.`mac_ethernet_2`, 
            `tb_nas`.`hdmi_port`, 
            `tb_nas`.`dvi_port`, 
            `tb_nas`.`display_port`, 
            `tb_nas`.`mdisplay_port`, 
            `tb_nas`.`thunderbolt_port`, 
            `tb_nas`.`wifi_card`, 
            `tb_nas`.`audio_card`, 
            `tb_nas`.`num_usb`, 
            `tb_nas`.`num_usb_3`, 
            `tb_nas`.`power_supply`, 
            `tb_nas`.`power_cell`
            FROM (`tb_nas` INNER JOIN `tb_workstations` ON `tb_nas`.`id_workstation` = `tb_workstations`.`id`) ORDER BY `tb_nas`.`id` DESC;";

    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_ASSOC);

}


$lista_nas = listaNas();

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("lista_nas" => $lista_nas), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;