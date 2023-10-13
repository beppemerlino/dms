<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Commessa.php');
require_once('db_pdo/CommessaTeam.php');
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

function delete_commesse_teams($id_commessa){

    $dbh = new Db_inc();
    $query = "DELETE FROM `tb_commesse_teams` WHERE `id_commessa` = :id_commessa";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":id_commessa" => $id_commessa));


}

/*
$json ='{
        "id": 0,
        "codice":"906",
        "anno":"2022",
        "cliente":"STEA Consulting srl (geom. N. De Rosa)",
        "localizzazione":"Castelli Calepio (BG)",
        "tipo_lavoro":Progetto Antincendio, variante stabilimento Gapi spa in Castelli Calepio (BG), via Molinaretto - capannone 1.",
        "teams":["3", "4"],
        "chiusa": "0"
    }';

*/

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = json_decode($json_data, TRUE);

    $k = 0;

    //print_r($array_json);exit;

    if ($array_json['id'] == "0"){
        // Nuova Commessa
        $commessa = new Commessa();

        $commessa->codice = $array_json['codice'];
        $commessa->anno = $array_json['anno'];
        $commessa->cliente = $array_json['cliente'];
        $commessa->localizzazione = $array_json['localizzazione'];
        $commessa->tipo_lavoro = $array_json['tipo_lavoro'];
        $commessa->chiusa = $array_json['chiusa'];


        try {

            $commessa->insert();
            $messaggio = "Commessa Aggiunta con Successo!";
            $id_commessa = $commessa->id;


        } catch (Exception $e) {

            $messaggio = $e->getMessage();
            $id_commessa = $array_json['id'];

        }

        if ($id_commessa != "0"){
            //inserisco i teams

            for ($i = 0; $i < count($array_json['teams']); $i ++) {

                $commessa_team = new CommessaTeam();
                $commessa_team->id_team = $array_json['teams'][$i];
                $commessa_team->id_commessa = $id_commessa;

                $commessa_team->insert();

            }
        }


    } else {

        // Modifica Commessa
        $commessa = new Commessa($array_json['id']);

        $commessa->codice = $array_json['codice'];
        $commessa->anno = $array_json['anno'];
        $commessa->cliente = $array_json['cliente'];
        $commessa->localizzazione = $array_json['localizzazione'];
        $commessa->tipo_lavoro = $array_json['tipo_lavoro'];
        $commessa->chiusa = $array_json['chiusa'];


        try {

            $commessa->update();
            $messaggio = "Commessa Salvata con Successo!";
            $id_commessa = $commessa->id;


        } catch (Exception $exception){

            $messaggio = $exception->getMessage();
            $id_commessa = $array_json['id'];

        }

        delete_commesse_teams($id_commessa);

        if ($id_commessa != "0"){
            //inserisco i teams

            for ($i = 0; $i < count($array_json['teams']); $i ++) {

                $commessa_team = new CommessaTeam();
                $commessa_team->id_team = $array_json['teams'][$i];
                $commessa_team->id_commessa = $id_commessa;

                $commessa_team->insert();

            }
        }

    }

    HTTPStatus(201);
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

exit;