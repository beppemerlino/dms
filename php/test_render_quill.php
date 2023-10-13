<?php
require 'vendor/autoload.php';
require_once('phpmailer/PHPMailer.php');
require_once('common_function/code_header.php');

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


    $email->WordWrap = 250;                                 // Set word wrap to 50 characters

    $email->IsHTML(true);

    $email->Subject = $subject;
    $email->Body = $body;

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

# http://localhost:8099/dms/php/test_render_quill.php

use DBlackborough\Quill\Render as QuillRender;


$quill_json = '{"ops":[{"attributes":{"color":"#008a00"},"insert":"Ciao Arianna,"},{"insert":"\nSono veramente euforico.\n\nGuarda queste credenziali:\n"},{"attributes":{"underline":true},"insert":"Username"},{"insert":": "},{"attributes":{"italic":true},"insert":"Beppe"},{"attributes":{"list":"bullet"},"insert":"\n"},{"attributes":{"underline":true},"insert":"Password"},{"insert":": "},{"attributes":{"italic":true},"insert":"dd45"},{"attributes":{"list":"bullet"},"insert":"\n"},{"insert":"\n\n\n"},{"attributes":{"italic":true,"color":"#0047b2","bold":true},"insert":"La Direzione"},{"insert":"\n"},{"attributes":{"alt":"logo INC Ambiente e Territorio Srl"},"insert":{"image":"https://www.incaet.it/wp-content/uploads/2019/05/thumbnail_Logo_INC-150x150.png"}},{"insert":"\n\n"},{"attributes":{"color":"gray","bold":true},"insert":"inc "},{"attributes":{"color":"maroon","bold":true},"insert":"ambiente e territorio srl"},{"insert":"\n"},{"attributes":{"color":"gray"},"insert":"Corso Roma 118 - 26900 Lodi"},{"insert":"\n"},{"attributes":{"color":"gray"},"insert":"tel. +39 0371 421821"},{"insert":"\n"},{"attributes":{"bold":true,"color":"#1155cc","link":"http://www.incaet.it/"},"insert":"www.incaet.it"},{"insert":"\n"}]}';

$parser = new \DBlackborough\Quill\Parser\Html();
$renderer = new \DBlackborough\Quill\Renderer\Html();

$parser->load($quill_json)->parse();

$body = $renderer->load($parser->deltas())->render();



$lista_destinatari = array(array("email" => "beppe.merlino@gmail.com", "nome" => "Beppe Merlino"));

$subject = "Email di TEST";

$reponse_email = inviaEmail($lista_destinatari, $subject, $body);

HTTPStatus(200);
HTTPContentType('json');
echo json_encode(array("messaggio" => $reponse_email));
exit;