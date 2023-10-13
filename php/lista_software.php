<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# http://localhost/dms/php/lista_software.php
# https://www.inc-intranet.it/php/lista_software.php

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

// TODO Keywords | Filtri

/**
 * Ritorna la lista dei pc
 * @return array
 */
function listaSoftware() : array {

    $dbh = new Db_inc();

    global $dominio;

    $query = "SELECT 
            `tb_softwares`.`id` AS `id_software`, 
            `tb_softwares`.`vendor`, 
            `tb_softwares`.`model`, 
            `tb_softwares`.`foto`, 
            `tb_softwares`.`id_pc`, 
            `tb_pcs`.`nome` AS `nome_computer`,
            `tb_softwares`.`serial_number`, 
            `tb_softwares`.`rif_cespite`, 
            `tb_softwares`.`part_number`, 
            `tb_softwares`.`description`, 
            `tb_softwares`.`expired_date` 
            FROM (`tb_softwares` INNER JOIN `tb_pcs` ON `tb_softwares`.`id_pc` = `tb_pcs`.`id`) ORDER BY `tb_softwares`.`id` DESC;";

    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    return $sth->fetchAll(PDO::FETCH_ASSOC);

}


$lista_software = listaSoftware();


HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("code" => 1, "response" => "Success", "lista_software" => $lista_software), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;