<?php

require_once('php/db_pdo/database.php');
require_once('php/db_pdo/Utente.php');
require_once('php/db_pdo/Gruppo.php');

ini_set('display_errors','On');


session_start();

$username = isset($_REQUEST["username"])? $_REQUEST["username"]: '';
$password = isset($_REQUEST["password"])? $_REQUEST["password"]: '';

$error = "";


if ($username != '' && $password != ''){

    $utente = new Utente();
    try {

        $id_utente = $utente->findByUsernamePassword($username, $password)->id;

        $utente_autorizzato = new Utente($id_utente);

        $gruppo = new Gruppo($utente_autorizzato->group);


        $_SESSION["ID"] = strval($utente_autorizzato->id);
        $_SESSION["TITOLO"] = $utente_autorizzato->titolo;
        $_SESSION["USERNAME"] = $utente_autorizzato->username;
        $_SESSION["NOME"] = $utente_autorizzato->nome;
        $_SESSION["COGNOME"] = $utente_autorizzato->cognome;
        $_SESSION["NOME.GRUPPO"] = $gruppo->nome;
        $_SESSION["SESSO"] = $utente_autorizzato->sesso;
        $_SESSION["EMAIL"] = $utente_autorizzato->email;
        $_SESSION["FOTO"] = $utente_autorizzato->foto;




    } catch (Exception $e){


        $error = $e->getMessage();

    }

}


if (isset($_SESSION["ID"]) && $_SESSION["ID"] > 0) {

    header("location: ./");

}

?>

<!DOCTYPE html>
<html lang="it">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SITO PER GESTIRE I DATI DI INC Ambiente e Territorio Srl">
    <meta name="author" content="Beppe Merlino">

    <title>DMS - DATA MANAGEMENT SYSTEM</title>
    <link rel="icon" href="https://www.incaet.it/wp-content/uploads/2019/05/cropped-thumbnail_Logo_INC-1-192x192.png" sizes="192x192" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Roboto');
        @import url('https://use.fontawesome.com/releases/v5.0.8/css/solid.css');

        body {
            font-family: 'Roboto', sans-serif;
            background-size: cover;
        }
        .main-section {
            margin: 0 auto;
            margin-top: 130px;
            padding: 0;
        }
        .modal-content {
            background-color: #9a1915;
            opacity: .9;
            padding: 0 18px;
            border-radius: 10px;
        }
        .user-img img {
            height: 140px;
            width: 150px;
        }
        .user-img {
            margin-top: -160px;
            margin-bottom: 45px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group input {
            height: 42px;
            border-radius: 5px;
            border: 0;
            font-size: 18px;
            letter-spacing: 2px;
            padding-left: 54px;
        }
        .form-group::before {
            font-family: 'Font Awesome\ 5 Free';
            content: "\f007";
            position: absolute;
            font-size: 22px;
            left: 28px;
            padding-top: 4px;
            color: #555e60;
        }
        .form-group:last-of-type::before {
            content: "\f023";
        }
        .form-input button {
            width: 40%;
            margin: 5px 0 25px;
        }
        .btn-success {
            background-color: #4C5C68;
            font-size: 19px;
            border-radius: 5px;
            padding: 7px 14px;
            border: 1px solid #4C5C38;
        }
        .btn-success:hover {
            background-color: #13445e;
            border: 1px solid #daf1ff;
        }
        .forgot {
            padding: 5px 0 25px;
        }
        .forgot a {
            color: #daf1ff;
        }
    </style>
</head>
<body>

<div class="modal-dialog text-center">
    <div class="col-sm-10 main-section">
        <div class="modal-header" style="align-content: center; ">
            <!-- LOGO -->
        </div>
        <div class="modal-content">
            <div class="col-12 user-img">
                <img src="assets/img/LOGO_INC.png">
            </div>
            <div class="col-12 form-input">
                <form action="login-page.php" method="post">
                    <p style="color: white">
                        <i>Inserisci il tuo Account INC</i>
                    </p>
                    <div class="form-group">
                        <input class="form-control" placeholder="Inserisci la Username" type="text" name="username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Inserisci la Password" name="password">
                    </div>
                    <button type="submit" class="btn btn-success">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php if($error) echo '<small id="emailHelp" class="form-text text-muted" >'.$error.'</small>'; ?>
</div>


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>

</body>
</html>