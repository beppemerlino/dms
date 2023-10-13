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

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");

if ($json_data == ""){

    $messaggio = "NESSUN DATO ARRIVATO!";
    $id_utente = "-1";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));
    exit;

}

/*
$json ='[{
        "id": 0,
        "username":"Mark",
        "password":"Jeson1",
        "nome":"Mark",
        "cognome":"Billow",
        "sesso":1,
        "titolo":"Mr.",
        "mansione":"CTO",
        "email":"mark.billow@fseitalia.com",
        "email2":"mark.billow@fseitalia.com",
        "group":1,
        "telefono":"3315286882",
        "note":"Test01",
        "attivo":1,
        "foto":"./assets/avatars/anonimus.jpg",
        "lang":"en"
    }]';

*/



if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id'] === 0){
        // Nuovo Utente
        $utente = new Utente();

        $utente->username = $array_json['username'];
        $utente->password = md5($array_json['password']);
        $utente->nome = $array_json['nome'];
        $utente->cognome = $array_json['cognome'];
        $utente->sesso = $array_json['sesso'];
        $utente->titolo = $array_json['titolo'];
        $utente->mansione = $array_json['mansione'];
        $utente->email = $array_json['email'];
        $utente->email2 = $array_json['email2'];
        $utente->group = $array_json['group'];
        $utente->telefono = $array_json['telefono'];
        $utente->note = $array_json['note'];
        $utente->attivo = $array_json['attivo'];
        $utente->tipo_lavoratore = $array_json['tipo_lavoratore'];
        $utente->foto = str_replace("./assets/", "", $array_json['foto']);
        $utente->lang = $array_json['lang'];

        try {

            $utente->insert();
            $messaggio = "Utente Inserito";
            $id_utente = $utente->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica Utente

        $utente = new Utente($array_json['id']);

        $utente->username = $array_json['username'];
        $utente->nome = $array_json['nome'];
        $utente->cognome = $array_json['cognome'];
        $utente->sesso = $array_json['sesso'];
        $utente->titolo = $array_json['titolo'];
        $utente->mansione = $array_json['mansione'];
        $utente->email = $array_json['email'];
        $utente->email2 = $array_json['email2'];
        $utente->group = $array_json['group'];
        $utente->telefono = $array_json['telefono'];
        $utente->note = $array_json['note'];
        $utente->attivo = $array_json['attivo'];
        $utente->tipo_lavoratore = $array_json['tipo_lavoratore'];
        $utente->foto = str_replace("./assets/", "", $array_json['foto']);
        $utente->lang = $array_json['lang'];

        try {

            $utente->update();
            $messaggio = "Utente Modificato";
            $id_utente = $array_json['id'];


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

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