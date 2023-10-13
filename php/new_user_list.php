<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');


# // http://localhost:8099/dms/php/new_user_list.php?id_teamleader=3

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}


function teamLeader($id_teamleader){

    $dbh = new Db_inc();
    $query = "SELECT `tb_utenti`.`id` AS `id`,
        `tb_utenti`.`nome` AS `nome`,
        `tb_utenti`.`cognome` AS `cognome`,
        `tb_utenti`.`foto` AS `foto`
      FROM `tb_utenti` WHERE 
      `tb_utenti`.`id` = :id_teamleader;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":id_teamleader" => $id_teamleader));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    $array = array();
    $k = 0;
    foreach ($res as $item) {

        $array[$k]['id'] = intval($item['id']);
        $array[$k]['teamleader'] = true;
        $array[$k]['nome'] = $item['nome'];
        $array[$k]['cognome'] = $item['cognome'];
        $array[$k]['foto'] = $item['foto'];
        $array[$k]['fixed'] = false;
        $array[$k]['order'] = $k + 1;

        $k ++;

    }

    return $array;

}

function userList($id_teamleader)
{

    /**
     * user_list: [
    {id:4, teamleader: false, nome:"Arianna", cognome:"Lanzarini", foto:"avatars\/arianna-lanzarini.jpg", fixed: false, order: 2},
    {id:5, teamleader: false, nome:"Emanuele", cognome :"Carano", foto:"avatars\/emanuele.jpg", fixed: false, order: 3},
    {id:8, teamleader: false, nome:"Davide", cognome:"Marangoni", foto:"avatars\/davide.jpg", fixed: false, order: 4},
    {id:9, teamleader: false, nome:"Raffaele", cognome:"Salvi", foto:"avatars\/raffaele.jpg", fixed: false, order: 5},
    {id:11, teamleader: false, nome:"Giovanni",cognome:"Parisi",foto:"avatars\/giovanni.jpg", fixed: false, order: 6},
    {id:12, teamleader: false, nome:"Rosanna", cognome:"Caporusso", foto:"avatars\/rosanna.jpg", fixed: false, order: 7},
    {id:13, teamleader: false, nome:"Lorenzo", cognome:"Cappellini", foto:"avatars\/lorenzo.jpg", fixed: false, order: 8},
    {id:14, teamleader: false, nome:"Stefania",cognome:"Fontanini", foto:"avatars\/stefania.jpg", fixed: false, order: 9},
    {id:15, teamleader: false, nome:"Maria Teresa", cognome: "Salvi", foto:"avatars\/teresa.jpg", fixed: false, order: 10},
    {id:16, teamleader: false, nome:"Federico", cognome :"Presazzi", foto:"avatars\/fede.jpg", fixed: false, order: 11},
    {id:18, teamleader: false, nome:"Arianna", cognome:"Losi", foto:"avatars\/arianna-losi.jpg", fixed: false, order: 12}],
    team_user_list: [{id:3, teamleader: true, nome:"Monica", cognome: "Ortenzi", foto: "avatars\/monica.jpg", fixed: false, order: 1},],
     */

    $dbh = new Db_inc();
    $query = "SELECT `tb_utenti`.`id` AS `id`,
        `tb_utenti`.`nome` AS `nome`,
        `tb_utenti`.`cognome` AS `cognome`,
        `tb_utenti`.`foto` AS `foto`
      FROM `tb_utenti` WHERE 
      `tb_utenti`.`attivo` = 1 AND
      `tb_utenti`.`group` != 1 AND
      `tb_utenti`.`group` != 2 AND 
                       `tb_utenti`.`id` != :id_teamleader;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":id_teamleader" => $id_teamleader));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    $array = array();
    $k = 0;
    foreach ($res as $item) {

        $array[$k]['id'] = intval($item['id']);
        $array[$k]['teamleader'] = false;
        $array[$k]['nome'] = $item['nome'];
        $array[$k]['cognome'] = $item['cognome'];
        $array[$k]['foto'] = $item['foto'];
        $array[$k]['fixed'] = false;
        $array[$k]['order'] = $k + 1;

        $k ++;

    }

    return $array;
}

$id_teamleader = isset($_REQUEST['id_teamleader']) ? $_REQUEST['id_teamleader'] : "";

if ($id_teamleader == ""){

    $messaggio = "NESSUN UTENTE TEAMLEADER. Inserire un id_teamleader!";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "lista_utenti" => []));
    exit;

}


$lista = userList($id_teamleader);
$lista_teamleader = teamLeader($id_teamleader);

$messaggio = "Lista Completata!";

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $messaggio, "lista_utenti" => $lista, "lista_teamleader" => $lista_teamleader), JSON_PARTIAL_OUTPUT_ON_ERROR);
exit;



?>
