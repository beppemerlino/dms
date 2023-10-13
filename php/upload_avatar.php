<?php
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

//path name of file for storage
$ora = date("Y-m-d H:i:s");

$filesize = $_FILES['file']['size'];


$nome_file = isset($_REQUEST['nome_file'])?$_REQUEST['nome_file'] : '';
$storage = isset($_REQUEST['storage'])?$_REQUEST['storage'] : '../assets/avatars';

$uploadfile = "$storage/".$nome_file;

//if the file is moved successfully
if ( move_uploaded_file( $_FILES['file']['tmp_name'] , $uploadfile ) ) {

    $messaggio = "File Inserito";

} else {

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("message" => "FILE NON INSERITO!"));
    exit;

}

HTTPStatus(201);
HTTPContentType('json');
echo json_encode(array("message" => $messaggio));
exit;