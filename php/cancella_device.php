<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Device.php');
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


$id_device = isset($_REQUEST['id_device'])?$_REQUEST['id_device']:'';

if ($id_device == ''){


    $messaggio = "NESSUN DEVICE DA CANCELLARE: manca l'ID!";
    $id_device = "0";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_device" => $id_device));

    exit;

}

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {


    $device = new Device($id_device);
    $device->delete();

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "Device Cancellato", "id_device" => "0"));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
    
}









