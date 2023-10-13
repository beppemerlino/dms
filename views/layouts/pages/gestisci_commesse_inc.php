<!-- Main page content-->
<?php
require_once('php/db_pdo/database.php');
/**
 * Funzione per ottenere l'elenco delle commesse del mio team
 * @param string $id_utente
 * @return array
 */
function listaCommesseTeams(string $id_utente){

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT `tb_commesse_teams`.`id_commessa` AS `id_commessa`,
        `tb_commesse`.`codice` AS `codice`,
        `tb_commesse`.`anno` AS `anno`,
        `tb_commesse`.`cliente` AS `cliente`,
        `tb_commesse`.`localizzazione` AS `localizzazione`,
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`,
        `tb_commesse`.`chiusa` AS `chiusa`
        FROM ((`tb_membri_team` 
            INNER JOIN (`tb_teams` 
                INNER JOIN (`tb_commesse_teams` 
                    INNER JOIN `tb_commesse` ON `tb_commesse_teams`.`id_commessa` = `tb_commesse`.`id`) ON `tb_teams`.`id` = `tb_commesse_teams`.`id_team`) ON `tb_membri_team`.`id_team` = `tb_teams`.`id`) 
            INNER JOIN `tb_utenti` ON `tb_membri_team`.`id_utente` = `tb_utenti`.`id`) 
        WHERE `tb_utenti`.`id` = :id_utente AND `tb_commesse`.`chiusa` = 0 GROUP BY `tb_commesse`.`id` ORDER BY `tb_commesse`.`codice` DESC;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id_utente' => $id_utente));

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $list;
}

/**
 * Funzione che restituisce la schedulazione delle commesse dell'utente in un arco di tempo
 * @param string $id_utente
 * @param string $start_date
 * @param string $end_date
 * @return array|false
 */
function schedulerData(string $id_utente, string $start_date, string $end_date){

    $dbh = new Db_inc();
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $query = "SELECT 
        `tb_rendicontazioni`.`id` AS `id_rendicontazione`,
        `tb_rendicontazioni`.`data` AS `data`, 
        `tb_rendicontazioni`.`id_commessa` AS `id_commessa`, 
        `tb_commesse`.`codice` AS `codice`,
        `tb_commesse`.`cliente` AS `cliente`,
        `tb_commesse`.`tipo_lavoro` AS `tipo_lavoro`,
        `tb_rendicontazioni`.`start_date` AS `start_date`, 
        `tb_rendicontazioni`.`end_date` AS `end_date`, 
        `tb_rendicontazioni`.`note` AS `note`
        FROM (`tb_rendicontazioni` INNER JOIN `tb_commesse` ON `tb_rendicontazioni`.`id_commessa` = `tb_commesse`.`id`)
        WHERE `tb_rendicontazioni`.`id_utente` = :id_utente
        AND `tb_rendicontazioni`.`start_date` >= :start_date AND `tb_rendicontazioni`.`end_date` <= :end_date;";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':id_utente' => $id_utente, ':start_date' => $start_date, ':end_date' => $end_date));

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $list;

}

/**
 * Questa Funzione converte una data di zona in data assoluta
 * @param $date_time
 * @return false|string
 * @throws Exception
 */
function dateTimeConverter($date_time){

    $start_dt = new DateTime($date_time, new DateTimeZone('UTC'));
    $start_dt->setTimezone(new DateTimeZone('Europe/Rome'));
    $offset = $start_dt->getOffset();
    $timestamp_date = $start_dt->getTimestamp() - $offset;
    $datetime = date('Y-m-d H:i:s', $timestamp_date);
    return substr($datetime, 0, 10)."T".substr($datetime, 11).".000Z";

}

$array_color = array();

function getColor($num) {
    $hash = md5('color' . $num);
    return array(substr($hash, 0, 2), substr($hash, 2, 2), substr($hash, 4, 2));
}

$nums = range(0, 199);
$k = 0;

foreach ($nums as $num) {

    list($r,$g,$b) = getColor($num);
    $array_color[$k] = "#".$r.$g.$b;
    $k ++;

}
$lista_commesse_team =  listaCommesseTeams($_SESSION['ID']);

$scheduler_commesse = schedulerData($_SESSION['ID'], '2022-01-01 00:00:00', '2022-12-31 23:59:59');

$scheduler_data = [];

foreach ($scheduler_commesse as $item) {

    array_push($scheduler_data,
        array('id' => intval($item['id_commessa']),
            'id_rendicontazione' => intval($item['id_rendicontazione']),
            'text' => 'Commessa ' . $item['codice'],
            'cliente' => $item['cliente'],
            'tipo_lavoro' => $item['tipo_lavoro'],
            'startDate' => dateTimeConverter($item['start_date']),
            'endDate' => dateTimeConverter($item['end_date']),
            'note' => $item['note']
        ));

}

$commesse_push = [];

$x = 0;

foreach ($lista_commesse_team as $item) {


    array_push($commesse_push, array('id' => intval($item['id_commessa']), 'text' => 'Commessa ' . $item['codice'], 'cliente' => $item['cliente'], 'tipo_lavoro' => $item['tipo_lavoro'], 'year' => $item['anno'], 'color' => $array_color[$x]));

    $x ++;

}
?>
<div id="app"  class="container-fluid">
    <!-- Custom page header alternative example-->
    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
        <div class="mr-4 mb-3 mb-sm-0">

            <div class="small">
                <?php
                function giorno($d){

                    $d_ex = explode("-", $d);
                    $d_ts = mktime(0,0,0,$d_ex[1],$d_ex[2],$d_ex[0]);
                    $num_gg = (int)date("N",$d_ts);

                    $giorno=array('','lunedì','martedì','mercoledì','giovedì','venerdì','sabato','domenica');
                    return $giorno[$num_gg];
                }


                echo '<span class="font-weight-500 text-primary">'.giorno(date('Y-m-d')).'</span>';
                echo ' &middot; ' . date('d/m/Y') . ' &middot; ' . date('H:i');
                ?>
            </div>
        </div>

    </div>

    <div class="card card-header-actions mx-auto">
        <div class="card-header">
            <img src="assets/img/icons/hand-shake.svg" height="32">GESTIONE COMMESSE
        </div>
        <script>
            const schedulerData = [
                <?php foreach ($scheduler_data as $item) { ?>
                {id: <?php echo $item['id']; ?>,id_rendicontazione: <?php echo $item['id_rendicontazione']; ?>,text: "<?php echo $item['text']; ?>",cliente: "<?php echo $item['cliente']; ?>",startDate: new Date("<?php echo $item['startDate']; ?>"),endDate: new Date("<?php echo $item['endDate']; ?>"),note: "<?php echo $item['note']; ?>"},
                <?php } ?>
            ];

            const commesseData =[
                <?php foreach ($commesse_push as $item) { ?>
                {id:<?php echo $item['id']; ?>,text:"<?php echo $item['text']; ?>",cliente:"<?php echo $item['cliente']; ?>",tipo_lavoro:"<?php echo $item['tipo_lavoro']; ?>",year:"<?php echo $item['year']; ?>",color:"<?php echo $item['color']; ?>"},
                <?php } ?>
            ];
        </script>
        <div class="card-body " >
            <div id="widget"></div>
        </div>
    </div>


</div>