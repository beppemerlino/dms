<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# // http://localhost:8099/dms/php/num_commessa.php

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

function codiceList($anno)
{

    $dbh = new Db_inc();
    $query = "SELECT `codice` FROM `tb_commesse` WHERE `anno` LIKE :anno;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":anno" => $anno));

    $items = $sth->fetchAll(PDO::FETCH_ASSOC);

    $codici = [];
    $k = 0;

    foreach ($items as $item) {

        $codici[$k] = intval($item['codice']);
        $k ++;

    }

    return max($codici) + 1;

}

$anno = isset($_REQUEST['anno']) ? $_REQUEST['anno'] : date('Y');

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $next_codice = codiceList($anno);

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("next_codice" => $next_codice), JSON_PARTIAL_OUTPUT_ON_ERROR);

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}


exit;

