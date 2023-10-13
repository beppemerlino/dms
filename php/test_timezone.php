<?php
require_once('common_function/code_header.php');

# http://localhost:8099/dms/php/test_timezone.php



$data = "2022-06-22 08:00:00";


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
    return date('Y-m-d H:i:s', $timestamp_date);

}

HTTPStatus(200);
HTTPContentType('text_utf8');
echo "DATA DI PARTENZA: " . $data . chr(13).chr(10). " DATA OTTENUTA: " . dateTimeConverter($data);

