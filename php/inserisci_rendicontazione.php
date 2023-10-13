<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Rendicontazione.php');
require_once('db_pdo/Commessa.php');
require_once('common_function/code_header.php');

# http://localhost:8099/dms/php/inserisci_commessa.php



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

/**
 * Funzione che controlla se l'utente non abbia già inserito una commessa in quella fascia oraria
 * @param string $start_date
 * @param string $end_date
 * @param string $id_utente
 * @return int
 */
function controllaRendicontazione(string $start_date, string $end_date, string $id_utente){

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT `tb_rendicontazioni`.`id` FROM `tb_rendicontazioni` WHERE `start_date`<= :start_date AND  `end_date` >= :end_date AND `id_utente` = :id_utente;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':start_date' => $start_date, ':end_date' => $end_date, ':id_utente' => $id_utente));

    list($id) = $sth->fetch();

    if($id) {

        return intval($id);

    }

    return 0;

}

$array_json = (array) json_decode(file_get_contents("php://input"));;

$start_dt = new DateTime($array_json['startDate'], new DateTimeZone('UTC'));
$start_dt->setTimezone(new DateTimeZone('Europe/Rome'));
$start_date = $start_dt->format('Y-m-d H:i:s');

$end_dt = new DateTime($array_json['endDate'], new DateTimeZone('UTC'));
$end_dt->setTimezone(new DateTimeZone('Europe/Rome'));
$end_date = $end_dt->format('Y-m-d H:i:s');

$diff = $end_dt->diff($start_dt);

$hours = $diff->h;
$hours = $hours + ($diff->days * 24);

if($array_json['id_rendicontazione'] == 0){

    $controlla_rendicontazione = controllaRendicontazione($start_date, $end_date, $_SESSION['ID']);

} else {

    $controlla_rendicontazione = $array_json['id_rendicontazione'];

}


if ($controlla_rendicontazione != 0){

    // Rendicontazione esistente!
    $rendicontazione = new Rendicontazione($controlla_rendicontazione);

    $rendicontazione->id_commessa = $array_json['id'];
    $rendicontazione->data = substr($array_json['startDate'], 0, 10);
    $rendicontazione->id_utente = $_SESSION['ID'];
    $rendicontazione->num_ore = $hours;
    $rendicontazione->start_date = $start_date;
    $rendicontazione->end_date = $end_date;
    $rendicontazione->note = $array_json['note'];

    try {

        $rendicontazione->update();
        $id_rendicontazione = $rendicontazione->id;
        $messaggio = "Rendicontazione modificata!";

    } catch (Exception $e){

        $messaggio = $e->getMessage();
        $id_rendicontazione = "0";

    }

} else {

    // Nuova Rendicontazione!
    $rendicontazione = new Rendicontazione();

    $rendicontazione->id_commessa = $array_json['id'];
    $rendicontazione->data = substr($array_json['startDate'], 0, 10);
    $rendicontazione->id_utente = $_SESSION['ID'];
    $rendicontazione->num_ore = $hours;
    $rendicontazione->start_date = $start_date;
    $rendicontazione->end_date = $end_date;
    $rendicontazione->note = $array_json['note'];

    try {

        $rendicontazione->insert();
        $id_rendicontazione = $rendicontazione->id;
        $messaggio = "Rendicontazione inserita!";

    } catch (Exception $e){

        $messaggio = $e->getMessage();
        $id_rendicontazione = "0";

    }

}


HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $messaggio, "id_rendicontazione" => $id_rendicontazione));
exit;


