<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Nas.php');
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

$id_nas = isset($_REQUEST['id_nas'])?$_REQUEST['id_nas']:'';

if ($id_nas == ''){


    $messaggio = "NESSUN NAS DA CANCELLARE: manca l'ID!";
    $id_nas = "0";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_nas" => $id_nas));

    exit;

}

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {


    $nas = new Nas($id_nas);
    $nas->delete();

    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "Nas Cancellato", "id_nas" => "0"));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
    
}







