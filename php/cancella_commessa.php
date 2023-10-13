<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Commessa.php');
require_once('db_pdo/Rendicontazione.php');
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

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");

if ($json_data == ""){

     = "NESSUN DATO ARRIVATO!";
    $id_utente = "-1";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));
    exit;

}

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = json_decode($json_data, TRUE);

    $id_commessa = $array_json['id'];

    $commessa = new Commessa($id_commessa);

    try {

        $commessa->delete();
        $messaggio = "Commessa cancellata";
        $id_commessa = "0";

    } catch (Exception $e) {

        $messaggio = $e->getMessage();

    }


    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_commessa" => $id_commessa));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;    
    
}
