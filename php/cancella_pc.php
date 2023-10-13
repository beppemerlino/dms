<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Pc.php');
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


function controllaId($id_pc){

    $dbh = new Db_inc();
    $query = "SELECT `id` FROM `tb_softwares` WHERE `id_pc` = :id_pc";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(":id_pc" => $id_pc));

    list($id) = $sth->fetch();

    if(!$id) return false;

    return true;
}

$id_computer = isset($_REQUEST['id_computer'])?$_REQUEST['id_computer']:'';

if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    if ($id_computer == ''){
    
        $messaggio = "NESSUN COMPUTER DA CANCELLARE: manca l'ID!";
        $id_computer = "0";
    
        HTTPStatus(206);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => $messaggio, "id_computer" => $id_computer));
    
        exit;
    
    }
    
    $controllo = controllaId($id_computer);
    
    
    if(!$controllo){
    
        $computer = new Pc($id_computer);
        $computer->delete();
    
        HTTPStatus(200);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => "PC Cancellato", "id_computer" => "0"));
        exit;
    
    } else {
    
        HTTPStatus(200);
        HTTPContentType('json');
        echo json_encode(array("messaggio" => "Non è possibile cancellare il PC!", "id_computer" => "0"));
        exit;
    
    }

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}







