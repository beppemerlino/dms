<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/lista_teams.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}


function teamList()
{

    $dbh = new Db_inc();
    $query = "SELECT 
        `tb_teams`.`id` AS `id_team`,
        `tb_teams`.`nome_team` AS `nome_team`,
        `tb_utenti`.`id` AS `id_teamleader`,
        CONCAT(`tb_utenti`.`nome`, ' ', `tb_utenti`.`cognome`) AS `team_leader`,
        `tb_teams`.`note`
        FROM (`tb_teams` INNER JOIN `tb_utenti` ON `tb_teams`.`id_teamleader` = `tb_utenti`.`id`) WHERE 1;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}

function teamleaderList()
{

    $dbh = new Db_inc();
    $query = "SELECT 
        `tb_utenti`.`id` AS `value`, CONCAT(`tb_utenti`.`nome`, ' ', `tb_utenti`.`cognome`) AS `text`
        FROM `tb_utenti` WHERE `tb_utenti`.`group` = 3 AND `tb_utenti`.`attivo` = 1;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute();

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;
}


if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $lista_team = teamList();

    $teamleaders = teamleaderList();

    $option_list = array();
    $k = 0;

    foreach ($lista_team as $item) {

        $option_list[$k]['text'] = $item['nome_team'];
        $option_list[$k]['value'] = $item['id_team'];

        $k ++;

    }

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("lista_team" => $lista_team, "teamleaders" => $teamleaders, "options_teams" => $option_list), JSON_PARTIAL_OUTPUT_ON_ERROR);

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;