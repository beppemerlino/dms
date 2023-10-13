<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Team.php');
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

$id_team = isset($_REQUEST['id_team'])?$_REQUEST['id_team']:'';

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    if ($id_team == '') {


        $messaggio = "NESSUN TEAM DA CANCELLARE: manca l'ID!";
        $id_team = "-1";

        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id_team" => $id_team));

        exit;

    }


    $team = new Team($id_team);

    $team->delete();


    HTTPStatus(200);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => "Team Cancellato", "id_team" => "0"));
    exit;

} else {
    
    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}