<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Rendicontazione.php');
require_once('db_pdo/Commessa.php');
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

$array_json = (array) json_decode(file_get_contents("php://input"));

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    if ($array_json['id_rendicontazione'] != 0) {

        // Rendicontazione esistente!
        $rendicontazione = new Rendicontazione($array_json['id_rendicontazione']);

        try {

            $rendicontazione->delete();
            $id_rendicontazione = $rendicontazione->id;
            $messaggio = "Rendicontazione cancellata!";

        } catch (Exception $e) {

            $messaggio = $e->getMessage();
            $id_rendicontazione = "0";

        }

    } else {


        $id_rendicontazione = "0";
        $messaggio = "Rendicontazione inesistente!";

    }


    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_rendicontazione" => $id_rendicontazione));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;
    
}


