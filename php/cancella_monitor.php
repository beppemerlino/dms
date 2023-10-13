<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Monitor.php');
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


if($_SERVER['REQUEST_METHOD'] != "POST"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "Metodo non autorizzato!";

    HTTPStatus(405);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;
}


$id_monitor = isset($_REQUEST['id_monitor'])?$_REQUEST['id_monitor']:'';

if ($id_monitor == ''){


    $messaggio = "NESSUN MONITOR DA CANCELLARE: manca l'ID!";
    $id_monitor = "0";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_monitor" => $id_monitor));

    exit;

}

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {


    $monitor = new Monitor($id_monitor);
    $monitor->delete();

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "Monitor Cancellato", "id_monitor" => "0"));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
    
}









