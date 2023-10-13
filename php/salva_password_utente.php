<?php
require_once('db_pdo/database.php');
require_once('db_pdo/utente.php');
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

    HTTPStatus(405);
    HTTPContentType('json');
    $response_data['messaggio'] = "Metodo non autorizzato!";

    echo json_encode($response_data);
    exit;

}


$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");


if ($json_data == ""){

    $messaggio = "NESSUN DATO ARRIVATO!";
    $id_utente = "-1";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));
    exit;

}


if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;


    //Modifica Utente

    $utente = new Utente($array_json['id']);

    $utente->password = md5($array_json['password']);

    try {

        $utente->update();
        $messaggio = "Password Modificata";
        $id_utente = $array_json['id'];


    } catch (Exception $e){

        $messaggio = $e->getMessage();

    }



    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}


exit;
