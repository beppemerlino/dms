<?php
require_once('db_pdo/database.php');
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

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");

if ($json_data == ""){

    $messaggio = "NESSUN DATO ARRIVATO!";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio));
    exit;

}

$array_json = json_decode($json_data, TRUE);



if (!key_exists('id_computer', $array_json)){

    $messaggio = "MANCA ID_COMPUTER!";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio));
    exit;

}


/**
 * Ritorna la lista dei software legati ad un PC
 * @return array
 */
function listaSoftware($id_pc) : array {

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    $query = "SELECT `id`, `vendor`, `model`, `foto`, `id_pc`, `serial_number`, `rif_cespite`, `part_number`, `description`, `expired_date` FROM `tb_softwares` WHERE `id_pc` = :id_pc;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id_pc' => $id_pc));
    $lista = $sth->fetchAll(PDO::FETCH_ASSOC);



    return $lista;

}


$lista_computer = listaSoftware($array_json['id_computer']);

$messaggio = 'Ok';
HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $messaggio, "lista" => $lista_computer), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;