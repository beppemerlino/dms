<?php
require_once('db_pdo/database.php');
require_once('db_pdo/Team.php');
require_once('db_pdo/MembroTeam.php');
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

/*
$json_data = '"id_team": 2, "nome_team": "Team di Nicola Clemeno", "note": "",
"user_list": [
  {
    "id": 5,
    "teamleader": true,
    "nome": "Emanuele",
    "cognome": "Carano",
    "foto": "avatars/emanuele.jpg",
    "fixed": false,
    "order": 1
  },
  {
    "id": 11,
    "teamleader": false,
    "nome": "Giovanni",
    "cognome": "Parisi",
    "foto": "avatars/giovanni.jpg",
    "fixed": false,
    "order": 2
  },
  {
    "id": 12,
    "teamleader": false,
    "nome": "Rosanna",
    "cognome": "Caporusso",
    "foto": "avatars/rosanna.jpg",
    "fixed": false,
    "order": 3
  },
  {
    "id": 13,
    "teamleader": false,
    "nome": "Lorenzo",
    "cognome": "Cappellini",
    "foto": "avatars/lorenzo.jpg",
    "fixed": false,
    "order": 4
  }
]';
*/

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");



$array_json = json_decode($json_data, JSON_THROW_ON_ERROR);

if ($json_data == ""){

    $messaggio = "NESSUN DATO ARRIVATO!";
    $id_utente = "-1";

    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));
    exit;

}



if (strtoupper($_SESSION["NOME.GRUPPO"]) == 'ADMINS' || strtoupper($_SESSION["NOME.GRUPPO"]) == 'SUPERADMINS' ) {

    $lista_utenti = $array_json['user_list'];
    $id_team = $array_json['id_team'];
    $nome_team = $array_json['nome_team'];
    $note = $array_json['note'];

    function findTeamleader(array $lista_utenti){

        $id_teamleader = $lista_utenti[0]['id'];

        foreach ($lista_utenti as $item) {

            if ($item['teamleader']) $id_teamleader = $item['id'];

        }

        return $id_teamleader;

    }

    function cancellaListaUtentiTeam($id_team){

        $query = 'DELETE FROM `tb_membri_team` WHERE `id_team` = :id_team';
        $dbh = new Db_inc();
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id_team' => $id_team));

    }

    if($id_team == 0){
        //Nuovo team

        $id_teamleader = findTeamleader($lista_utenti);

        $team = new Team();
        $team->id_teamleader = $id_teamleader;
        $team->nome_team = $nome_team;
        $team->note = $note;

        $team->insert();

        foreach ($lista_utenti as $item) {

            $membro_team = new MembroTeam();
            $membro_team->id_team = $team->id;
            $membro_team->id_utente = $item['id'];
            if($id_teamleader == $item['id']) $membro_team->note = "Project Manager";
            $membro_team->insert();

        }

        $messaggio = "Liste Membri Inserita con Successo!";
        $id_team = $team->id;

    } else {
        //Modifica team

        $id_teamleader = findTeamleader($lista_utenti);

        $team = new Team($id_team);
        $team->id_teamleader = $id_teamleader;
        $team->nome_team = $nome_team;
        $team->note = $note;

        $team->update();

        cancellaListaUtentiTeam($id_team);

        foreach ($lista_utenti as $item) {

            $membro_team = new MembroTeam();
            $membro_team->id_team = $id_team;
            $membro_team->id_utente = $item['id'];
            if($id_teamleader == $item['id']) $membro_team->note = "Project Manager";
            $membro_team->insert();

        }

        $messaggio = "Liste Membri Modificata con Successo!";

    }

    HTTPStatus(201);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_team" => $id_team));

} else {

    $response_data['id'] = "-1";
    $response_data['messaggio'] = "UTENTE NON AUTORIZZATO! PRIVILEGI INSUFFICIENTI";

    HTTPStatus(401);
    HTTPContentType('json');
    echo json_encode($response_data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    exit;

}
exit;
