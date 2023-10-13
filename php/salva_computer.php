<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Pc.php');
require_once('common_function/code_header.php');

/**
 *
 * {
        "id_computer": 0,
        "vendor": "Apple",
        "model": "Macbook Pro M1",
        "cpu_1": "SILICON M1",
        "cpu_2": "",
        "operative_system": "MACOS 13.13",
        "keyboard": "Integrata",
        "mouse": "Trackpad",
        "foto": "./assets/pcs/RTV.jpg",
        "id_workstation": 1,
        "serial_number": "123456",
        "rif_cespite": "Cespite 1",
        "part_number": "GT01256",
        "form_factory": "Laptop",
        "ram_size": "16GB",
        "primary_disk_size": "1TB",
        "secondary_disk_size": "",
        "dvd_rom": "1",
        "video_card": "",
        "bluetooth": "1",
        "ethernet_1": "1",
        "ethernet_2": "1",
        "ip_address_1": "192.168.10.25",
        "ip_address_2": "192.168.10.26",
        "mac_ethernet_1": "D4:11:5F:7A",
        "mac_ethernet_2": "",
        "hdmi_port": "1",
        "dvi_port": "1",
        "display_port": "1",
        "mdisplay_port": "1",
        "thunderbolt_port": "1",
        "wifi_card": "1",
        "audio_card": "Realteck Integrata",
        "num_usb": "2",
        "num_usb_3": "2",
        "power_supply": "",
        "power_cell": "Batteria Integrata Apple"
 * }
 *
 */


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
    $id_computer = "-1";
    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_computer));
    exit;

}

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS') {

    $array_json = json_decode($json_data, TRUE);
    $array = $array_json;
    $k = 0;

    if ($array_json['id_computer'] === 0){
        // Nuovo Computer
        $computer = new Pc();


        $computer->nome                 = $array_json['nome'];
        $computer->vendor               = $array_json['vendor'];
        $computer->model                = $array_json['model'];
        $computer->cpu_1                = $array_json['cpu_1'];
        $computer->cpu_2                = $array_json['cpu_2'];
        $computer->operative_system     = $array_json['operative_system'];
        $computer->keyboard             = $array_json['keyboard'];
        $computer->mouse                = $array_json['mouse'];
        $computer->foto                 = $array_json['foto'];
        $computer->id_workstation       = $array_json['id_workstation'];
        $computer->serial_number        = $array_json['serial_number'];
        $computer->rif_cespite          = $array_json['rif_cespite'];
        $computer->part_number          = $array_json['part_number'];
        $computer->form_factory         = $array_json['form_factory'];
        $computer->ram_size             = $array_json['ram_size'];
        $computer->primary_disk_size    = $array_json['primary_disk_size'];
        $computer->secondary_disk_size  = $array_json['secondary_disk_size'];
        $computer->dvd_rom              = $array_json['dvd_rom'];
        $computer->video_card           = $array_json['video_card'];
        $computer->bluetooth            = $array_json['bluetooth'];
        $computer->ethernet_1           = $array_json['ethernet_1'];
        $computer->ethernet_2           = $array_json['ethernet_2'];
        $computer->ip_address_1         = $array_json['ip_address_1'];
        $computer->ip_address_2         = $array_json['ip_address_2'];
        $computer->mac_ethernet_1       = $array_json['mac_ethernet_1'];
        $computer->mac_ethernet_2       = $array_json['mac_ethernet_2'];
        $computer->hdmi_port            = $array_json['hdmi_port'];
        $computer->dvi_port             = $array_json['dvi_port'];
        $computer->display_port         = $array_json['display_port'];
        $computer->mdisplay_port        = $array_json['mdisplay_port'];
        $computer->thunderbolt_port     = $array_json['thunderbolt_port'];
        $computer->wifi_card            = $array_json['wifi_card'];
        $computer->audio_card           = $array_json['audio_card'];
        $computer->num_usb              = $array_json['num_usb'];
        $computer->num_usb_3            = $array_json['num_usb_3'];
        $computer->power_supply         = $array_json['power_supply'];
        $computer->power_cell           = $array_json['power_cell'];

        try {

            $computer->insert();
            $messaggio = "Pc Inserito";
            $id_computer = $computer->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }


    } else {
        //Modifica Computer

        $computer = new Pc($array_json['id_computer']);

        $computer->nome                 = $array_json['nome'];
        $computer->vendor               = $array_json['vendor'];
        $computer->model                = $array_json['model'];
        $computer->cpu_1                = $array_json['cpu_1'];
        $computer->cpu_2                = $array_json['cpu_2'];
        $computer->operative_system     = $array_json['operative_system'];
        $computer->keyboard             = $array_json['keyboard'];
        $computer->mouse                = $array_json['mouse'];
        $computer->foto                 = $array_json['foto'];
        $computer->id_workstation       = $array_json['id_workstation'];
        $computer->serial_number        = $array_json['serial_number'];
        $computer->rif_cespite          = $array_json['rif_cespite'];
        $computer->part_number          = $array_json['part_number'];
        $computer->form_factory         = $array_json['form_factory'];
        $computer->ram_size             = $array_json['ram_size'];
        $computer->primary_disk_size    = $array_json['primary_disk_size'];
        $computer->secondary_disk_size  = $array_json['secondary_disk_size'];
        $computer->dvd_rom              = $array_json['dvd_rom'];
        $computer->video_card           = $array_json['video_card'];
        $computer->bluetooth            = $array_json['bluetooth'];
        $computer->ethernet_1           = $array_json['ethernet_1'];
        $computer->ethernet_2           = $array_json['ethernet_2'];
        $computer->ip_address_1         = $array_json['ip_address_1'];
        $computer->ip_address_2         = $array_json['ip_address_2'];
        $computer->mac_ethernet_1       = $array_json['mac_ethernet_1'];
        $computer->mac_ethernet_2       = $array_json['mac_ethernet_2'];
        $computer->hdmi_port            = $array_json['hdmi_port'];
        $computer->dvi_port             = $array_json['dvi_port'];
        $computer->display_port         = $array_json['display_port'];
        $computer->mdisplay_port        = $array_json['mdisplay_port'];
        $computer->thunderbolt_port     = $array_json['thunderbolt_port'];
        $computer->wifi_card            = $array_json['wifi_card'];
        $computer->audio_card           = $array_json['audio_card'];
        $computer->num_usb              = $array_json['num_usb'];
        $computer->num_usb_3            = $array_json['num_usb_3'];
        $computer->power_supply         = $array_json['power_supply'];
        $computer->power_cell           = $array_json['power_cell'];

        try {

            $computer->update();
            $messaggio = "Pc Modificato";
            $id_computer = $computer->id;


        } catch (Exception $e){

            $messaggio = $e->getMessage();

        }

    }

    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id" => $id_computer));

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}

exit;