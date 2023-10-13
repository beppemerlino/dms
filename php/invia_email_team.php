<?php
require_once('db_pdo/database.php');
require_once('common_function/code_header.php');

require 'vendor/autoload.php';
require_once('phpmailer/PHPMailer.php');

function componentiTeam($id_team){

    $dbh = new Db_inc();
    $query = "SELECT 
        `tb_utenti`.`id` AS `id_utente`, 
        `tb_membri_team`.`id_team` AS `id_team`, 
        CONCAT(`tb_utenti`.`nome`, ' ', `tb_utenti`.`cognome`)  AS `nome`, 
        `tb_utenti`.`email` AS `email`, 
        `tb_utenti`.`email2` AS `email2`
        FROM (`tb_membri_team` INNER JOIN `tb_utenti` ON `tb_membri_team`.`id_utente` = `tb_utenti`.`id`) WHERE `tb_membri_team`.`id_team` = :id_team";
    $sth = $dbh->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    $sth->execute(array(':id_team' => $id_team));

    $res = $sth->fetchAll(PDO::FETCH_ASSOC);

    return $res;

}

function inviaEmail(array $lista_dest, string $subject, string $body){

    //==================================================================================
    //Impostazione dei servizi e-mail per la INC Ambiente e Territorio Srl
    //==================================================================================

    $message_attach = '';

    $email = new PHPMailer();

    $email->IsSMTP();                                      // Set mailer to use SMTP
    $email->Host = 'smtps.aruba.it';  // Specify main and backup server
    $email->SMTPAuth = true;                               // Enable SMTP authentication
    $email->Username = 'posta@beppemerlino.com';                            // SMTP username
    $email->Password = 'Veronik72';                           // SMTP password
    $email->SMTPSecure = 'ssl';
    $email->Port = 465;// Enable encryption, 'ssl' also accepted

    $email->From = 'noreply@incaet.it';
    $email->FromName = 'Servizio di Invio Email di INC Ambiente e Territorio Srl';

    foreach ($lista_dest as $item) {

        $email->AddAddress($item["email"], $item["nome"]);

    }

    function converter($text) {

        $map = array(
            '¡' => '&iexcl;',	#INVERTED EXCLAMATION MARK
            '¢' => '&cent;'	,	#CENT SIGN
            '£' => '&pound;',	#POUND SIGN
            '¤' => '&curren;',	#CURRENCY SIGN
            '¥' => '&yen;'	,	#YEN SIGN
            '¦' => '&brvbar;',	#BROKEN BAR
            '§' => '&sect;'	,	#SECTION SIGN
            '¨' => '&uml;'	,	#DIAERESIS
            '©' => '&copy;'	,	#COPYRIGHT SIGN
            'ª' => '&ordf;'	,	#FEMININE ORDINAL INDICATOR
            '«' => '&laquo'	,	#LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
            '¬' => '&not;'	,	#NOT SIGN
            '®' => '&reg;'	,	#REGISTERED SIGN
            '¯' => '&macr;'	,	#MACRON
            '°' => '&deg;'	,	#DEGREE SIGN
            '±' => '&plusmn;',	#PLUS-MINUS SIGN
            '²' => '&sup2;'	,	#SUPERSCRIPT TWO
            '³' => '&sup3;'	,	#SUPERSCRIPT THREE
            '´' => '&acute;',	#ACUTE ACCENT
            'µ' => '&micro;',	#MICRO SIGN
            '¶' => '&para;'	,	#PILCROW SIGN
            '·' => '&middot;',	#MIDDLE DOT
            '¸' => '&cedil;',	#CEDILLA
            '¹' => '&sup1;'	,	#SUPERSCRIPT ONE
            'º' => '&ordm;'	,	#MASCULINE ORDINAL INDICATOR
            '»' => '&raquo;',	#RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
            '¼' => '&frac14;',	#VULGAR FRACTION ONE QUARTER
            '½' => '&frac12;',	#VULGAR FRACTION ONE HALF
            '¾' => '&frac34;',	#VULGAR FRACTION THREE QUARTERS
            '¿' => '&iquest;',	#INVERTED QUESTION MARK
            'À' => '&Agrave;',	#LATIN CAPITAL LETTER A WITH GRAVE
            'Á' => '&Aacute;',	#LATIN CAPITAL LETTER A WITH ACUTE
            'Â' => '&Acirc;',	#LATIN CAPITAL LETTER A WITH CIRCUMFLEX
            'Ã' => '&Atilde;',	#LATIN CAPITAL LETTER A WITH TILDE
            'Ä' => '&Auml;'	,	#LATIN CAPITAL LETTER A WITH DIAERESIS
            'Å' => '&Aring;',	#LATIN CAPITAL LETTER A WITH RING ABOVE
            'Æ' => '&AElig;',	#LATIN CAPITAL LETTER AE
            'Ç' => '&Ccedil;',	#LATIN CAPITAL LETTER C WITH CEDILLA
            'È' => '&Egrave;',	#LATIN CAPITAL LETTER E WITH GRAVE
            'É' => '&Eacute;',	#LATIN CAPITAL LETTER E WITH ACUTE
            'Ê' => '&Ecirc;',	#LATIN CAPITAL LETTER E WITH CIRCUMFLEX
            'Ë' => '&Euml;'	,	#LATIN CAPITAL LETTER E WITH DIAERESIS
            'Ì' => '&Igrave;',	#LATIN CAPITAL LETTER I WITH GRAVE
            'Í' => '&Iacute;',	#LATIN CAPITAL LETTER I WITH ACUTE
            'Î' => '&Icirc;',	#LATIN CAPITAL LETTER I WITH CIRCUMFLEX
            'Ï' => '&Iuml;'	,	#LATIN CAPITAL LETTER I WITH DIAERESIS
            'Ð' => '&ETH;'	,	#LATIN CAPITAL LETTER ETH
            'Ñ' => '&Ntilde;',	#LATIN CAPITAL LETTER N WITH TILDE
            'Ò' => '&Ograve;',	#LATIN CAPITAL LETTER O WITH GRAVE
            'Ó' => '&Oacute;',	#LATIN CAPITAL LETTER O WITH ACUTE
            'Ô' => '&Ocirc;',	#LATIN CAPITAL LETTER O WITH CIRCUMFLEX
            'Õ' => '&Otilde;',	#LATIN CAPITAL LETTER O WITH TILDE
            'Ö' => '&Ouml;'	,	#LATIN CAPITAL LETTER O WITH DIAERESIS
            '×' => '&times;',	#MULTIPLICATION SIGN
            'Ø' => '&Oslash;',	#LATIN CAPITAL LETTER O WITH STROKE
            'Ù' => '&Ugrave;',	#LATIN CAPITAL LETTER U WITH GRAVE
            'Ú' => '&Uacute;',	#LATIN CAPITAL LETTER U WITH ACUTE
            'Û' => '&Ucirc;',	#LATIN CAPITAL LETTER U WITH CIRCUMFLEX
            'Ü' => '&Uuml;'	,	#LATIN CAPITAL LETTER U WITH DIAERESIS
            'Ý' => '&Yacute;',	#LATIN CAPITAL LETTER Y WITH ACUTE
            'Þ' => '&THORN;',	#LATIN CAPITAL LETTER THORN
            'ß' => '&szlig;',	#LATIN SMALL LETTER SHARP S
            'à' => '&agrave;',	#LATIN SMALL LETTER A WITH GRAVE
            'á' => '&aacute;',	#LATIN SMALL LETTER A WITH ACUTE
            'â' => '&acirc;',	#LATIN SMALL LETTER A WITH CIRCUMFLEX
            'ã' => '&atilde;',	#LATIN SMALL LETTER A WITH TILDE
            'ä' => '&auml;'	,	#LATIN SMALL LETTER A WITH DIAERESIS
            'å' => '&aring;',	#LATIN SMALL LETTER A WITH RING ABOVE
            'æ' => '&aelig;',	#LATIN SMALL LETTER AE
            'ç' => '&ccedil;',	#LATIN SMALL LETTER C WITH CEDILLA
            'è' => '&egrave;',	#LATIN SMALL LETTER E WITH GRAVE
            'é' => '&eacute;',	#LATIN SMALL LETTER E WITH ACUTE
            'ê' => '&ecirc;',	#LATIN SMALL LETTER E WITH CIRCUMFLEX
            'ë' => '&euml;'	,	#LATIN SMALL LETTER E WITH DIAERESIS
            'ì' => '&igrave;',	#LATIN SMALL LETTER I WITH GRAVE
            'í' => '&iacute;',	#LATIN SMALL LETTER I WITH ACUTE
            'î' => '&icirc;',	#LATIN SMALL LETTER I WITH CIRCUMFLEX
            'ï' => '&iuml;'	,	#LATIN SMALL LETTER I WITH DIAERESIS
            'ð' => '&eth;'	,	#LATIN SMALL LETTER ETH
            'ñ' => '&ntilde;',	#LATIN SMALL LETTER N WITH TILDE
            'ò' => '&ograve;',	#LATIN SMALL LETTER O WITH GRAVE
            'ó' => '&oacute;',	#LATIN SMALL LETTER O WITH ACUTE
            'ô' => '&ocirc;',	#LATIN SMALL LETTER O WITH CIRCUMFLEX
            'õ' => '&otilde;',	#LATIN SMALL LETTER O WITH TILDE
            'ö' => '&ouml;'	,	#LATIN SMALL LETTER O WITH DIAERESIS
            '÷' => '&divide;',	#DIVISION SIGN
            'ø' => '&oslash;',	#LATIN SMALL LETTER O WITH STROKE
            'ù' => '&ugrave;',	#LATIN SMALL LETTER U WITH GRAVE
            'ú' => '&uacute;',	#LATIN SMALL LETTER U WITH ACUTE
            'û' => '&ucirc;',	#LATIN SMALL LETTER U WITH CIRCUMFLEX
            'ü' => '&uuml;'	,	#LATIN SMALL LETTER U WITH DIAERESIS
            'ý' => '&yacute;',	#LATIN SMALL LETTER Y WITH ACUTE
            'þ' => '&thorn;',	#LATIN SMALL LETTER THORN
            'ÿ' => '&yuml;'	,	#LATIN SMALL LETTER Y WITH DIAERESIS
        );
        return strtr($text, $map);
    }

    $email->WordWrap = 250;                                 // Set word wrap to 50 characters

    $email->IsHTML(true);


    $email->Subject = $subject;
    $email->Body = converter($body);



    try {

        $email->Send();
        $messaggio = "Email inviata!";
        /**
        $handler = fopen("/var/www/html/glm-bergner/php/logs/log_invio_email_brt_".date('Y-m-d').".txt", "a");
        fwrite($handler, chr(13).chr(10)."[".date('Y-m-d H:i:s')."]".chr(13).chr(10).$messaggio);
        fclose($handler);
         */

    } catch (Exception $e){

        $messaggio = "Email non inviata! Eccezione: " . $e->getMessage();
        /**
        $handler = fopen("/var/www/html/glm-bergner/php/logs/errorlog_invio_email_brt_".date('Y-m-d').".txt", "a");
        fwrite($handler, chr(13).chr(10)."[".date('Y-m-d H:i:s')."]".chr(13).chr(10).$messaggio);
        fclose($handler);
         */

    }


    return $messaggio;

    //==================================================================================
    //-------------------------------------- FINE --------------------------------------
    //==================================================================================

}

# http://localhost:8099/dms/php/invia_email_team.php

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

$json_data = (isset($_REQUEST['data'])? $_REQUEST['data'] : "");

if ($json_data == ""){

    $messaggio = "NESSUN DATO ARRIVATO!";
    $id_utente = "-1";
    HTTPStatus(206);
    HTTPContentType('json');
    echo json_encode(array("messaggio" => $messaggio, "id_utente" => $id_utente));
    exit;

}

use DBlackborough\Quill\Render as QuillRender;

$array_json = json_decode($json_data, TRUE);
$id_team = $array_json['id_team'];
$subject = $array_json['subject'];
$array_ops = $array_json['body'];


$quill_json = json_encode($array_ops);


$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->load($quill_json)->parse();

$body = $renderer->load($parser->deltas())->render();

$lista_destinatari = array();

$lista_membri = componentiTeam($id_team);

foreach ($lista_membri as $item) {

    if ($item['email2'] != "") array_push($lista_destinatari, array("email" => $item['email2'], "nome" => $item['nome']));

    array_push($lista_destinatari, array("email" => $item['email'], "nome" => $item['nome']));


}


$reponse_email = inviaEmail($lista_destinatari, $subject, $body);

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $reponse_email, "lista_destinatari" => $lista_destinatari));


exit;