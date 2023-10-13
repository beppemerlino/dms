<?php
session_start();



if (!(isset($_SESSION["ID"])) || $_SESSION["ID"] == 0) {

    header("location: ./login");

} else {

    $nome = $_SESSION["NOME"];
    $foto = $_SESSION["FOTO"];
    $email = $_SESSION["EMAIL"];
    $id_utente = $_SESSION["ID"];
}


include('layouts/app.template.php');
