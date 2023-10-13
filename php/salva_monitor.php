<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Monitor.php');
require_once('common_function/code_header.php');

session_start();


$array_json = array();
$response_data = array();


if (empty($_SESSION)) {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "Accesso non autorizzato alla pagina!";

    header('Content-Type: application/jsons');
    echo json_encode($response_data);

    exit;

}

if ($_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "Accesso non autorizzato alla pagina!";

    header('Content-Type: application/jsons');
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


if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    if ($json_data == ""){

        $messaggio = "NESSUN DATO ARRIVATO!";
        $id_monitor = "-1";
        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id" => $id_monitor));
        exit;

    }


    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id_monitor'] === 0){
        // Nuovo Monitor
        $monitor = new Monitor();

        $monitor->nome                      = $array_json['nome'];
        $monitor->vendor                    = $array_json['vendor'];
        $monitor->model                     = $array_json['model'];
        $monitor->foto                      = $array_json['foto'];
        $monitor->id_workstation            = $array_json['id_workstation'];
        $monitor->serial_number             = $array_json['serial_number'];
        $monitor->rif_cespite               = $array_json['rif_cespite'];
        $monitor->part_number               = $array_json['part_number'];
        $monitor->resolution                = $array_json['resolution'];
        $monitor->inc_size                  = $array_json['inc_size'];
        $monitor->hdmi_port                 = $array_json['hdmi_port'];
        $monitor->dvi_port                  = $array_json['dvi_port'];
        $monitor->display_port              = $array_json['display_port'];
        $monitor->mdisplay_port             = $array_json['mdisplay_port'];
        $monitor->thunderbolt_port          = $array_json['thunderbolt_port'];
        $monitor->power_supply              = $array_json['power_supply'];

        try {

            $monitor->insert();
            $messaggio = "Monitor Inserito";
            $id_monitor = $monitor->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica Monitor

        $monitor = new Monitor($array_json['id_monitor']);

        $monitor->nome                      = $array_json['nome'];
        $monitor->vendor                    = $array_json['vendor'];
        $monitor->model                     = $array_json['model'];
        $monitor->foto                      = $array_json['foto'];
        $monitor->id_workstation            = $array_json['id_workstation'];
        $monitor->serial_number             = $array_json['serial_number'];
        $monitor->rif_cespite               = $array_json['rif_cespite'];
        $monitor->part_number               = $array_json['part_number'];
        $monitor->resolution                = $array_json['resolution'];
        $monitor->inc_size                  = $array_json['inc_size'];
        $monitor->hdmi_port                 = $array_json['hdmi_port'];
        $monitor->dvi_port                  = $array_json['dvi_port'];
        $monitor->display_port              = $array_json['display_port'];
        $monitor->mdisplay_port             = $array_json['mdisplay_port'];
        $monitor->thunderbolt_port          = $array_json['thunderbolt_port'];
        $monitor->power_supply              = $array_json['power_supply'];

        try {

            $monitor->update();
            $messaggio = "Monitor Modificato";
            $id_monitor = $monitor->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

    }

    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_monitor));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}


