<?php
require_once('./db_pdo/database.php');
require_once('./db_pdo/Utente.php');
require_once('common_function/code_header.php');

# // http://localhost:8099/dms/php/load_team.php?id_team=1&id_teamleader=5

session_start();

if (empty($_SESSION) || $_SESSION['ID'] == "0"){

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO!";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data);
    exit;

}

function teamUserList(string $id_team, int $id_teamleader = 0){

    $dbh = new Db_inc();
    $query = "SELECT 
        `tb_membri_team`.`id_team` AS `id_team`, 
        `tb_teams`.`id_teamleader` AS `id_teamleader`,
        `tb_utenti`.`id` AS `id`, 
        `tb_utenti`.`nome` AS `nome`, 
        `tb_utenti`.`cognome` AS `cognome`, 
        `tb_utenti`.`foto` AS `foto`,
        IF(`tb_utenti`.`id` = `tb_teams`.`id_teamleader`, '1', '0') AS `teamleader`
        FROM ((`tb_membri_team` INNER JOIN `tb_teams` ON `tb_membri_team`.`id_team` = `tb_teams`.`id`) 
            INNER JOIN `tb_utenti` ON `tb_membri_team`.`id_utente`=`tb_utenti`.`id`) 
        WHERE `tb_membri_team`.`id_team` = :id_team AND `tb_utenti`.`attivo` = 1 ORDER BY `teamleader` DESC; ";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(":id_team" => $id_team));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    $array = array();
    $k = 0;
    foreach ($res as $item) {

        if($id_teamleader == 0){

            $array[$k]['id'] = intval($item['id']);
            if(intval($item['id']) == intval($item['id_teamleader'])) $array[$k]['teamleader'] = true; else $array[$k]['teamleader'] = false;
            $array[$k]['nome'] = $item['nome'];
            $array[$k]['cognome'] = $item['cognome'];
            $array[$k]['foto'] = $item['foto'];
            $array[$k]['fixed'] = false;
            $array[$k]['order'] = $k + 1;

        } else {

            if(intval($item['id']) == intval($item['id_teamleader'])){

                $utente = new Utente($id_teamleader);

                $array[$k]['id'] = $id_teamleader;
                $array[$k]['teamleader'] = true;
                $array[$k]['nome'] = $utente->nome;
                $array[$k]['cognome'] = $utente->cognome;
                $array[$k]['foto'] = $utente->foto;
                $array[$k]['fixed'] = false;
                $array[$k]['order'] = $k + 1;

            } else {

                $array[$k]['id'] = intval($item['id']);
                $array[$k]['teamleader'] = false;
                $array[$k]['nome'] = $item['nome'];
                $array[$k]['cognome'] = $item['cognome'];
                $array[$k]['foto'] = $item['foto'];
                $array[$k]['fixed'] = false;
                $array[$k]['order'] = $k + 1;

            }


        }

        $k ++;

    }

    return $array;

}

function userList(array $userlList)
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

    $clausule = '';

    foreach ($userlList as $item) {

            $clausule .= ' AND `tb_utenti`.`id` != '  . $item['id'];

    }

    $dbh = new Db_inc();
    $query = sprintf("SELECT `tb_utenti`.`id` AS `id`,
        `tb_utenti`.`nome` AS `nome`,
        `tb_utenti`.`cognome` AS `cognome`,
        `tb_utenti`.`foto` AS `foto`
      FROM `tb_utenti` WHERE 
      `tb_utenti`.`attivo` = 1 AND
      `tb_utenti`.`group` != 1 AND
      `tb_utenti`.`group` != 2 %s
      ORDER BY `tb_utenti`.`cognome` ASC
      ", $clausule);


    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute();

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

$id_team = isset($_REQUEST['id_team']) ? $_REQUEST['id_team'] : "";
$id_teamleader = isset($_REQUEST['id_teamleader']) ? intval($_REQUEST['id_teamleader']) : 0;

if ($id_team == ""){

    $messaggio = "NESSUN ID TEAM. Inserire un id_team!";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "lista_utenti_team" => [],  "lista_utenti" => []));
    exit;

}

$userlList = teamUserList($id_team, $id_teamleader);
$lista_utenti = userList($userlList);


$messaggio = "Liste Completate!";

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $messaggio, "lista_utenti_team" => $userlList,  "lista_utenti" => $lista_utenti));
exit;