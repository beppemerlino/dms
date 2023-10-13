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

/**
 * Ritorna la lista dei pc
 * @return array
 */
function listaComputer() : array {

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
    $query = "SELECT `id` AS `value`, `nome` AS `text` FROM `tb_pcs` WHERE 1 ORDER BY `id` DESC";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute();
    $lista = $sth->fetchAll(PDO::FETCH_ASSOC);

    $array_return = array();
    $k = 0;

    foreach ($lista as $item) {

        $k = array_push($array_return, array('value' => $item['value'], 'text' => $item['text']));

    }

    return $array_return;

}


$lista_computer = listaComputer();

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("lista" => $lista_computer), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;