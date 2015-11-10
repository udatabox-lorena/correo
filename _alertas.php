<?php

//require_once "Mail.php";
require "phpmailer/PHPMailerAutoload.php";
//require "phpmailer/class.PHPMailerAutoload.php";
//require "phpmailer/class.smtp.php";

try {
    $from = "Udatabox <udatabox@consultoresmendezcamperosca.com>";
$to = "Udatabox <udatabox@consultoresmendezcamperosca.com>";
$subject = "Hi!";
setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
date_default_timezone_set('America/Caracas');
echo strftime("%A %d de %B del %Y a las %I:%M:%S %p");
//echo Yii::$app->timeZone;
$body = strftime("%A %d de %B del %Y a las %I:%M:%S %p");

$host = "smtp.gmail.com";
$port = "587";
$username = "udatabox@consultoresmendezcamperosca.com";
$password = "Udatabox.29";
$mail = new PHPMailer();
$mail->isSMTP();
$mail->CharSet = "utf-8";
$mail->PluginDir = "mailer/";
$mail->Mailer = "smtp";
$mail->Host = $host;
$mail->Port = 587;  //587
//$mail->SMTPDebug = 2;
$mail->SMTPAuth = true; // true        
$mail->Username = $username;
$mail->Password = $password;
//correo y nombre
$mail->From = "udatabox@consultoresmendezcamperosca.com";
$mail->FromName = "UDATABOX®";

$mail->AddAddress('udatabox@consultoresmendezcamperosca.com');
$mail->Subject = "Prueba tarea programada";
$mail->msgHTML("<em>Mensaje: </em> Hola, Lorena. Esto es una prueba. La hora del envio es: " . $body);
//Definimos AltBody por si el destinatario del correo no admite email con formato html
$mail->AltBody = "Prueba tarea programada";
if ($mail->send())
    echo "correo enviado";
else {
    echo "correo no enviado\n";
    echo $mail->ErrorInfo;
}
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
?>
