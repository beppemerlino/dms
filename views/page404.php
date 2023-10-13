<?php
session_start();



if (!(isset($_SESSION["ID"])) || $_SESSION["ID"] == 0) {

    header("location: ./login");

} else {

    $nome = $_SESSION["NOME"];
    $foto = $_SESSION["FOTO"];
    $email = $_SESSION["EMAIL"];
}

$scheda = "";
$menupoint = "page404";

require_once('inc/navbar.php');
require_once('inc/sidebar.php');
require_once('inc/modal_logout.php');


include('layouts/app.template.php');
