<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Utente.php');
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

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $id_utente = isset($_REQUEST['id_utente']) ? $_REQUEST['id_utente'] : '';

    if ($id_utente == '') {


        $messaggio = "NESSUN UTENTE DA CANCELLARE: manca l'ID!";
        $id_utente = "-1";

        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));

        exit;

    }

    if ($id_utente == '1') {


        $messaggio = "NON È POSSIBILE CANCELLARE IL \'SUPER ADMIN\'";
        $id_utente = "-1";

        HTTPStatus(200);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));

        exit;

    }


    $utente = new Utente($id_utente);

    $utente->delete();


    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "Utente Cancellato", "id_utente" => "0"));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
    
}