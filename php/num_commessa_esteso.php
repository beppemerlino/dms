<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

# // http://localhost:8099/dms/php/num_commessa_esteso.php?codice=644

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

function codiceList($codice)
{

    $dbh = new Db_inc();
    $query = "SELECT `codice` FROM `tb_commesse` WHERE `codice` LIKE :codice;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":codice" => $codice . "%"));

    $items = $sth->fetchAll(PDO::FETCH_ASSOC);

    if(!$items) return null;

    $codici = [];
    $k = 0;

    foreach ($items as $item) {

        $codici[$k] = floatval($item['codice']);
        $k ++;

    }

    return max($codici) + 0.1;

}

$codice = isset($_REQUEST['codice']) ? $_REQUEST['codice'] : '';

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    if ($codice == '') {

        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => "Devi inserire un  codice commessa valido!", "next_codice" => "0"), JSON_PARTIAL_OUTPUT_ON_ERROR);
        exit;

    }

    $next_codice = codiceList($codice);

    if (!$next_codice) {

        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => "Devi inserire un  codice esistente!", "next_codice" => "0"), JSON_PARTIAL_OUTPUT_ON_ERROR);
        exit;

    }

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "OK", "next_codice" => $next_codice), JSON_PARTIAL_OUTPUT_ON_ERROR);


} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;