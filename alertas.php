<?php

//require_once "Mail.php";
require "phpmailer/class.phpmailer.php";
//require "phpmailer/class.PHPMailerAutoload.php";
//require "phpmailer/class.smtp.php";
setlocale(LC_TIME, 'es_VE', 'es_VE.utf-8', 'es_VE.utf8');
date_default_timezone_set('America/Caracas');
try {
    $from = "Udatabox <udatabox@consultoresmendezcamperosca.com>";
$to = "Udatabox <udataboxprueba@gmail.com>";
$subject = "Hi!";
$body = "HORA  " .  strftime("%A %d de %B del %Y a las %I:%M:%S %p");

$host = "mail.udatabox.com";
$port = "587";
$username = "noresponder@udatabox.com";
$password = "PhVZP24qh9Ww";
$mail = new PHPMailer();
$mail->isSMTP();
$mail->CharSet = "utf-8";
$mail->PluginDir = "mailer/";
$mail->Mailer = "smtp";
$mail->Host = $host;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;  //587
$mail->SMTPAuth = true; // true        
$mail->Username = $username;
$mail->Password = $password;
//correo y nombre
$mail->From = "noresponder@udatabox.com";
$mail->FromName = "UDATABOX®";

$mail->AddAddress('udataboxprueba@gmail.com');
$mail->Subject = $body;
$mail->msgHTML("<em>Mensaje: <em> Hola, Yurisan. Esto es una prueba");
//Definimos AltBody por si el destinatario del correo no admite email con formato html
$mail->AltBody = "Prueba tarea programada";
if ($mail->send())
    echo "correo enviado ".strftime("%A %d de %B del %Y a las %I:%M:%S %p");
else {
    echo "correo no enviado\n";
    //echo $mail->ErrorInfo;
}
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}


?>
