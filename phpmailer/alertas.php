<?php

//require_once "Mail.php";
require "phpmailer/class.phpmailer.php";
//require "phpmailer/class.PHPMailerAutoload.php";
require "phpmailer/class.smtp.php";

try {
    $from = "Udatabox <udataboxprueba@gmail.com>";
$to = "Udatabox <udataboxprueba@gmail.com>";
$subject = "Hi!";
$body = "HORA  " . date('d-m-y h:m');

$host = "smtp.gmail.com";
$port = "587";
$username = "udataboxprueba@gmail.com";
$password = "udatabox29";
echo $username.' '.$password;
$mail = new PHPMailer();
$mail->isSMTP();
$mail->CharSet = "utf-8";
$mail->PluginDir = "mailer/";
$mail->Mailer = "smtp";
$mail->Host = $host;
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->Port = 587;  //587
$mail->SMTPDebug = 1;
$mail->SMTPAuth = true; // true        
$mail->Username = $username;
$mail->Password = $password;
//correo y nombre
$mail->From = "udataboxprueba@gmail.com";
$mail->FromName = "UDATABOX®";

$mail->AddAddress('yurisan29@gmail.com');
$mail->Subject = "Prueba tarea programada";
$mail->msgHTML("<em>Mensaje: <em> Hola, Yurisan. Esto es una prueba");
//Definimos AltBody por si el destinatario del correo no admite email con formato html
$mail->AltBody = "Prueba tarea programada";
if ($mail->send())
    echo "correo enviado";
else {
    echo "correo no enviado\n";
    //echo $mail->ErrorInfo;
}
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

/* $headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
  $smtp = PHPMailer::factory('smtp',
  array ('host' => $host,
  'port' => $port,
  'auth' => true,
  'username' => $username,
  'password' => $password));

  $mail = $smtp->send($to, $headers, $body);

  if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
  } else {
  echo("<p>Message successfully sent!</p>");
  } */
?>

<?php

/*
  require_once "Mail.php";

  /*
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->CharSet = "utf-8";
  $mail->PluginDir = "mailer/";
  $mail->Mailer = "smtp";
  $mail->Host = "mail.udatabox.com";
  $mail->Port = 587;  //587
  $mail->SMTPAuth = true; // true
  $mail->Username = "noresponder@udatabox.com";
  $mail->Password = "PhVZP24qh9Ww";
  //correo y nombre
  $mail->From = "noresponder@udatabox.com";
  $mail->FromName = "UDATABOX®";
  0 * */

//$from = "Yuri <udataboxprueba@gmail.com>";
/* $from = "<noresponder@udatabox.com>";

  $to = "Yurisan <udataboxprueba@gmail.com>";
  $subject = "Hi!";
  $body = "HORA  ".date('d-m-y h:m');

  //$host = "smtp.gmail.com";
  $host = "mail.udatabox.com";

  $port = "587";

  //$username = "udataboxprueba";
  $username = 'noresponder@udatabox.com';
  //$password = "udatabox29";
  $password = "PhVZP24qh9Ww";

  $headers = array ('From' => $from,
  'To' => $to,
  'Subject' => $subject);
  $smtp = Mail::factory('smtp',
  array ('host' => $host,
  'port' => $port,
  'auth' => true,
  'username' => $username,
  'password' => $password));

  $mail = $smtp->send($to, $headers, $body);

  if (PEAR::isError($mail)) {
  echo("<p>" . $mail->getMessage() . "</p>");
  } else {
  echo("<p>Message successfully sent!</p>");
  } */
?>
