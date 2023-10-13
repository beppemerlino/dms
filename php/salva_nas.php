<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Nas.php');
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
    $id_nas = "-1";
    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_nas));
    exit;

}




if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id_nas'] === 0){
        // Nuovo Nas
        $nas = new Nas();

        $nas->nome                     = $array_json['nome'];
        $nas->vendor                   = $array_json['vendor'];
        $nas->model                    = $array_json['model'];
        $nas->cpu_1                    = $array_json['cpu_1'];
        $nas->cpu_2                    = $array_json['cpu_2'];
        $nas->operative_system         = $array_json['operative_system'];
        $nas->foto                     = $array_json['foto'];
        $nas->id_workstation           = $array_json['id_workstation'];
        $nas->serial_number            = $array_json['serial_number'];
        $nas->rif_cespite              = $array_json['rif_cespite'];
        $nas->part_number              = $array_json['part_number'];
        $nas->form_factory             = $array_json['form_factory'];
        $nas->ram_size                 = $array_json['ram_size'];
        $nas->num_hd                   = $array_json['num_hd'];
        $nas->type_hd                  = $array_json['type_hd'];
        $nas->descr_raid               = $array_json['descr_raid'];
        $nas->ip_address_1             = $array_json['ip_address_1'];
        $nas->ip_address_2             = $array_json['ip_address_2'];
        $nas->bluetooth                = $array_json['bluetooth'];
        $nas->ethernet_1               = $array_json['ethernet_1'];
        $nas->ethernet_2               = $array_json['ethernet_2'];
        $nas->mac_ethernet_1           = $array_json['mac_ethernet_1'];
        $nas->mac_ethernet_2           = $array_json['mac_ethernet_2'];
        $nas->hdmi_port                = $array_json['hdmi_port'];
        $nas->dvi_port                 = $array_json['dvi_port'];
        $nas->display_port             = $array_json['display_port'];
        $nas->mdisplay_port            = $array_json['mdisplay_port'];
        $nas->thunderbolt_port         = $array_json['thunderbolt_port'];
        $nas->wifi_card                = $array_json['wifi_card'];
        $nas->audio_card               = $array_json['audio_card'];
        $nas->num_usb                  = $array_json['num_usb'];
        $nas->num_usb_3                = $array_json['num_usb_3'];
        $nas->power_supply             = $array_json['power_supply'];
        $nas->power_cell               = $array_json['power_cell'];

        try {

            $nas->insert();
            $messaggio = "Nas Inserito";
            $id_nas = $nas->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica nas

        $nas = new Nas($array_json['id_nas']);

        $nas->nome                     = $array_json['nome'];
        $nas->vendor                   = $array_json['vendor'];
        $nas->model                    = $array_json['model'];
        $nas->cpu_1                    = $array_json['cpu_1'];
        $nas->cpu_2                    = $array_json['cpu_2'];
        $nas->operative_system         = $array_json['operative_system'];
        $nas->foto                     = $array_json['foto'];
        $nas->id_workstation           = $array_json['id_workstation'];
        $nas->serial_number            = $array_json['serial_number'];
        $nas->rif_cespite              = $array_json['rif_cespite'];
        $nas->part_number              = $array_json['part_number'];
        $nas->form_factory             = $array_json['form_factory'];
        $nas->ram_size                 = $array_json['ram_size'];
        $nas->num_hd                   = $array_json['num_hd'];
        $nas->type_hd                  = $array_json['type_hd'];
        $nas->descr_raid               = $array_json['descr_raid'];
        $nas->ip_address_1             = $array_json['ip_address_1'];
        $nas->ip_address_2             = $array_json['ip_address_2'];
        $nas->bluetooth                = $array_json['bluetooth'];
        $nas->ethernet_1               = $array_json['ethernet_1'];
        $nas->ethernet_2               = $array_json['ethernet_2'];
        $nas->mac_ethernet_1           = $array_json['mac_ethernet_1'];
        $nas->mac_ethernet_2           = $array_json['mac_ethernet_2'];
        $nas->hdmi_port                = $array_json['hdmi_port'];
        $nas->dvi_port                 = $array_json['dvi_port'];
        $nas->display_port             = $array_json['display_port'];
        $nas->mdisplay_port            = $array_json['mdisplay_port'];
        $nas->thunderbolt_port         = $array_json['thunderbolt_port'];
        $nas->wifi_card                = $array_json['wifi_card'];
        $nas->audio_card               = $array_json['audio_card'];
        $nas->num_usb                  = $array_json['num_usb'];
        $nas->num_usb_3                = $array_json['num_usb_3'];
        $nas->power_supply             = $array_json['power_supply'];
        $nas->power_cell               = $array_json['power_cell'];

        try {

            $nas->update();
            $messaggio = "Nas Modificato";
            $id_nas = $nas->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

    }



    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_nas));


} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;