<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Software.php');
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
    $id_software = "-1";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_software));
    exit;

}




if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = array();
    $response_data = array();

    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id_software'] === 0){
        // Nuovo Nas
        $software = new Software();

        $software->vendor        = $array_json['vendor'];
        $software->model         = $array_json['model'];
        $software->foto          = $array_json['foto'];
        $software->id_pc         = $array_json['id_pc'];
        $software->serial_number = $array_json['serial_number'];
        $software->rif_cespite   = $array_json['rif_cespite'];
        $software->part_number   = $array_json['part_number'];
        $software->description   = $array_json['description'];
        $software->expired_date  = $array_json['expired_date'];

        try {

            $software->insert();
            $messaggio = "Software Inserito";
            $id_software = $software->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica nas

        $software = new Software($array_json['id_software']);

        $software->vendor        = $array_json['vendor'];
        $software->model         = $array_json['model'];
        $software->foto          = $array_json['foto'];
        $software->id_pc         = $array_json['id_pc'];
        $software->serial_number = $array_json['serial_number'];
        $software->rif_cespite   = $array_json['rif_cespite'];
        $software->part_number   = $array_json['part_number'];
        $software->description   = $array_json['description'];
        $software->expired_date  = $array_json['expired_date'];

        try {

            $software->update();
            $messaggio = "Software Modificato";
            $id_software = $software->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

    }

    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_software));

}
else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;
