<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Device.php');
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
        $id_device = "-1";
        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id" => $id_device));
        exit;

    }


    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id_device'] === 0){
        // Nuovo Device
        $device = new Device();

        $device->nome                     = $array_json['nome'];
        $device->vendor                   = $array_json['vendor'];
        $device->model                    = $array_json['model'];
        $device->foto                     = $array_json['foto'];
        $device->id_workstation           = $array_json['id_workstation'];
        $device->serial_number            = $array_json['serial_number'];
        $device->rif_cespite              = $array_json['rif_cespite'];
        $device->part_number              = $array_json['part_number'];
        $device->description              = $array_json['description'];


        try {

            $device->insert();
            $messaggio = "Device Inserito";
            $id_device = $device->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica Device

        $device = new Device($array_json['id_device']);

        $device->nome                     = $array_json['nome'];
        $device->vendor                   = $array_json['vendor'];
        $device->model                    = $array_json['model'];
        $device->foto                     = $array_json['foto'];
        $device->id_workstation           = $array_json['id_workstation'];
        $device->serial_number            = $array_json['serial_number'];
        $device->rif_cespite              = $array_json['rif_cespite'];
        $device->part_number              = $array_json['part_number'];
        $device->description              = $array_json['description'];

        try {

            $device->update();
            $messaggio = "Device Modificato";
            $id_device = $device->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

    }

    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_device));
    exit;

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}


