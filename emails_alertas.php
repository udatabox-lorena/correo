<?php

//require "phpmailer/PHPMailerAutoload.php";
require "phpmailer/class.phpmailer.php";
//require "phpmailer/class.PHPMailerAutoload.php";
require "phpmailer/class.smtp.php";
//----------------------------------------------------------------------------
// Conectando, seleccionando la base de datos
set_time_limit(0);
setlocale(LC_TIME, 'es_VE', 'es_VE.utf-8', 'es_VE.utf8');
date_default_timezone_set('America/Caracas');

function envia_correo($email, $titulo, $body) {
    setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
    date_default_timezone_set('America/Caracas');
    //$body = $mensaje;

    $host = "smtp.gmail.com";
    $port = "587";
    $username = "udataboxprueba@gmail.com";
    $password = "udatabox29";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->CharSet = "utf-8";
    $mail->PluginDir = "mailer/";
    $mail->Mailer = "smtp";
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;  //587
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->From = "udatabox@consultoresmendezcamperosca.com";
    $mail->FromName = "UDATABOX®";
    $mail->AddAddress($email);
    $mail->Subject = $titulo;
    $mail->msgHTML($body);
    //Definimos AltBody por si el destinatario del correo no admite email con formato html
    //$mail->AltBody = "Prueba tarea programada";
    if ($mail->send())
        echo "correo enviado";
    else {
        echo "correo no enviado\n";
        echo $mail->ErrorInfo;
    }
}

/* /function envia_correo($email, $titulo, $body) { //Nube
  setlocale(LC_TIME, 'es_VE'); # Localiza en español es_Venezuela
  date_default_timezone_set('America/Caracas');
  //$body = $mensaje;

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
  $mail->Username = $username;
  $mail->Password = $password;
  $mail->From = "noresponder@udatabox.com";
  $mail->FromName = "UDATABOX®";
  $mail->AddAddress($email);
  $mail->Subject = $titulo;
  $mail->msgHTML($body);
  //Definimos AltBody por si el destinatario del correo no admite email con formato html
  //$mail->AltBody = "Prueba tarea programada";
  if ($mail->send())
  echo "correo enviado";
  else {
  echo "correo no enviado\n";
  echo $mail->ErrorInfo;
  }
  } */

function calcular_dias($id) {
    $cantdias = 0;
    $diasferiados = array();

    $query = 'SELECT * FROM diaferiado';
    $result = mysql_query($query) or die('Consulta fallida diaferiado: ' . mysql_error());

    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
//echo "dia: " . $line['dia'] . " - mes: " . $line['mes'] . "<br>";
        array_push($diasferiados, [$line['dia'], $line['mes']]);
        $cantdias++;
    }

//echo "Cantidad de dias feriados: " . $cantdias . "<br>";

    $query_fase = 'SELECT e.id as t_id,  a.fecha_inicio , a.dia , a.tipodia '
            . 'FROM fases a inner join procedimiento b on a.procedimiento_id=b.id '
            . 'inner join cuaderno c on b.cuaderno_id=c.id '
            . 'inner join expediente d on d.id=c.expediente_id '
            . 'inner join tribunal e on e.id=d.tribunal_id '
            . 'WHERE a.id=' . $id;

    $result_fase = mysql_query($query_fase) or die('Consulta fallida fase: ' . mysql_error());
    $line = mysql_fetch_array($result_fase, MYSQL_ASSOC);

    /* echo "t_id: " . $line['t_id'] . " - fecha_inicio: " . $line['fecha_inicio'] . " - dia: " . $line['dia']
      . " - tipodia: " . $line['tipodia'] . "<br>"; */

    if (isset($line))
        $idtribunal = $line['t_id'];


    $cantdiadespacho = 0;
    $diasdespacho = array();

    $query_tablilla = 'select * '
            . 'from tablilla '
            . 'where tribunal_id=' . $idtribunal;

    $result_tablilla = mysql_query($query_tablilla) or die('Consulta fallida tablilla: ' . mysql_error());
    while ($tablilla = mysql_fetch_array($result_tablilla, MYSQL_ASSOC)) {

        /* echo "ID: " . $tablilla['id'] . " - archivo: " . $tablilla['archivo'] . " - extension: " 
          . $tablilla['extension'] . " - mes: " . $tablilla['mes'] . " - ano: " . $tablilla['ano'] . "<br>"; */
//Aqui pendiente revisar esta consulta

        $query_tablilla_dia = 'select * '
                . 'from tablilla_dia '
                . 'where tablilla_id=' . $tablilla['id'];
        $result_tablilla_dia = mysql_query($query_tablilla_dia) or die('Consulta fallida: ' . mysql_error());

        while ($tablilla_dia = mysql_fetch_array($result_tablilla_dia, MYSQL_ASSOC)) {
            /* echo "ID: " . $tablilla_dia['id'] . " - DESPACHO: " . $tablilla_dia['despacho'] . " - fecha: " 
              . $tablilla_dia['fecha'] . " - responsable: " . $tablilla_dia['responsable'] . "<br>"; */
            $fechadespacho = explode('-', $tablilla_dia['fecha']);

            array_push($diasdespacho, [$fechadespacho[2], $fechadespacho[1]]);
            $cantdiadespacho++;
        }
    }

    $fecha = $line['fecha_inicio'];

    for ($i = 0; $i < $line['dia']; $i++) {
        $fecha = date('Y-m-j', strtotime('+1 day', strtotime($fecha)));
        $fecha_explode = explode('-', $fecha);

        for ($j = 0; $j < $cantdias; $j++) {
            if ($diasferiados[$j][0] == $fecha_explode[2] && $diasferiados[$j][1] == $fecha_explode[1]) {
                $i--;
                break;
            }
        }

        for ($j = 0; $j < $cantdiadespacho; $j++) {
            if ($diasdespacho[$j][0] == $fecha_explode[2] && $diasdespacho[$j][1] == $fecha_explode[1]) {
                $i--;
                break;
            }
        }

        if ($line['tipodia'] == 1) { // 1: solo dias habiles
            if (date('N', strtotime($fecha)) > 5) {
                $i--;
                continue;
            } else {
                
            }
        }

        if ($line['tipodia'] == 2) { // 2:continuo
        }
    }

    return date("d-m-Y", strtotime($fecha));
}

function dias_restantes($fecha_final, $fecha_inicial) {
    /* $segundos = strtotime($fecha_final) - strtotime('now');
      $diferencia_dias = intval($segundos / 60 / 60 / 24); */
    //echo "Fecha inicial: " . $fecha_inicial . "<br>";
    //echo $fecha_final . "<br>";
    $datetime1 = new DateTime($fecha_inicial);
    $datetime2 = new DateTime($fecha_final);
    $interval = $datetime1->diff($datetime2);

    //echo $interval->format('%R%a dias %H - ');

    return $interval->format('%R%a');
    //return $diferencia_dias;
}

function dif_horas($fecha_final, $fecha_inicial) {
    
    
}

//----Revisar desde aqui----
$link = mysql_connect('localhost', 'root', '123456789') or die('No se pudo conectar: ' . mysql_error());
mysql_select_db('boxroot_empresa_juridico') or die('No se pudo seleccionar la base de datos');
/* $link = mysql_connect('localhost', 'boxroot_ju_test', 'Doctor2015A') or die('No se pudo conectar: ' . mysql_error());
  mysql_select_db('boxroot_test_interno') or die('No se pudo seleccionar la base de datos'); */

// Realizar una consulta MySQL
$query_alertas = "select * from alertas where estado=0";
$result_alertas = mysql_query($query_alertas) or die('Consulta fallida: ' . mysql_error());

while ($alertas = mysql_fetch_array($result_alertas, MYSQL_ASSOC)) {

    $query = 'SELECT * FROM fases where id=' . $alertas['fases_id'];
    $result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());

//echo "Estas son las fases <br>";
// Imprimir los resultados en HTML
    while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
        /* echo "Id: " . $line['id'] . " - Fecha_inicio: " . $line['fecha_inicio'] . " - dia: " . $line['dia']
          . " - tipodia: " . $line['tipodia'] . " - descipcion: " . $line['descripcion'] . "<br>"; */

        $query_conf = 'SELECT a.id, a.descripcion as d1, a.fecha_inicio, c.descripcion as d2, d.numero, e.nombre, f.abogado_id, f.ayudante_id, f.codigo_id,'
                . ' e.persona_natural_id, e.persona_juridica_id '
                . ' FROM fases a inner join procedimiento b on a.procedimiento_id=b.id '
                . 'inner join cuaderno c on c.id=b.cuaderno_id '
                . 'inner join expediente d on c.expediente_id=d.id '
                . 'inner join cuenta e on e.id=d.cuenta_id '
                . 'inner join configuracion f on f.cuenta_id=e.id '
                . 'WHERE a.id =' . $line['id'];

//echo "Consulta: " . $query_conf . "<br><br>";

        $result_conf = mysql_query($query_conf) or die('Consulta fallida: ' . mysql_error());

        while ($fila = mysql_fetch_array($result_conf, MYSQL_ASSOC)) {

            /* echo "Id: " . $fila['id'] . " - d1: " . $fila['d1'] . " - fecha_inicio: " . $fila['fecha_inicio']
              . " - d2: " . $fila['d2'] . " - numero: " . $fila['numero'] . " - nombre: " . $fila['nombre']
              . " - abogado_id: " . $fila['abogado_id'] . " - ayudante_id: " . $fila['ayudante_id']
              . " - codigo_id: " . $fila['codigo_id'] . "<br>"; */

            $band = false;

            $f_d = date('d-m-Y');
            //echo $f_d;
            if (dias_restantes(calcular_dias($fila['id']), $f_d) > 0) {
                $f = explode("-", $fila['fecha_inicio']);
                $fe = $f[2] . "-" . $f[1] . "-" . $f[0];

                if (!is_null($fila['persona_natural_id'])) {
                    $sql = "select * from persona_natural where id=" . $fila['persona_natural_id'];
                    $result_s = mysql_query($sql) or die('Consulta fallida: ' . mysql_error());
                    $natural = mysql_fetch_array($result_s, MYSQL_ASSOC);
                    $cliente = "<b>" . $natural['primer_nombre'] . " " . $natural['primer_apellido']
                            . "</b> titular de la CI <b>" . $natural['cedula'] . "</b>";
                } else if (!is_null($fila['persona_juridica_id'])) {
                    $sql = "select * from persona_juridica where id=" . $fila['persona_juridica_id'];
                    $result_s = mysql_query($sql) or die('Consulta fallida: ' . mysql_error());
                    $juridico = mysql_fetch_array($result_s, MYSQL_ASSOC);
                    $cliente = "<b>" . $juridico['razon_social'] . "</b> titular del RIF <b>" . $juridico['RIF'] . "</b>";
                }

                $titulo = "AVISO: Recuerda las fases Pendientes del Expediente " . $fila['numero'];
                $mensaje = "Recuerda que debes realizar la fase del expediente <b>" . $fila['numero']
                        . "</b> que pertenece a la cuenta <b>" . $fila['nombre'] . "</b>, del cliente " . $cliente
                        . ", la cual consiste en <b><em>" . $fila['d1'] . "</em></b>, inicio el dia " . $fe
                        . ", y finaliza el dia " . calcular_dias($fila['id'])
                        . ".</br></br> Te quedan aproximadamente " . dias_restantes(calcular_dias($fila['id']), $f_d) . " dias.";
                $band = true;
            }

            if (dias_restantes(calcular_dias($fila['id']), $f_d) == 0) {
                $titulo = "AVISO: Ha finalizado una fase del Expediente " . $fila['numero'];
                $mensaje = "Ha finalizado el lapso de tiempo para concluir la fase del expediente <b>"
                        . $fila['numero'] . "</b> que pertenece a la cuenta <b>" . $fila['nombre']
                        . "</b>, del cliente " . $cliente . "la cual consistia en <b><em>" . $fila['d1']
                        . "</em></b>, y finalizo el dia " . calcular_dias($fila['id']);
                $band = true;
            }

//BUSCAR ABOGADO
            if (!is_null($fila['abogado_id'])) {
                $query_abogado = 'select * from abogado where id=' . $fila['abogado_id'];
                $result_abogado = mysql_query($query_abogado) or die('Consulta fallida: ' . mysql_error());
                $abogado = mysql_fetch_array($result_abogado, MYSQL_ASSOC);

                $query_user = 'select * from users where id=' . $abogado['usuario_id'];
                $result_user = mysql_query($query_user) or die('Consulta fallida: ' . mysql_error());
                $user = mysql_fetch_array($result_user, MYSQL_ASSOC);

                if ($band) {
                    $ultimo_correo_guardar = strftime("%Y-%m-%d %H:00:00");
                    $ultimo_correo = $alertas['ultimo_correo'];

                    if ($alertas['frecuencia_tipo'] == 1) {
                        //Horas
                        //----Calculo de Horas    
                        $query_new = "select date_add( '" . $ultimo_correo . "', INTERVAL " . $alertas['frecuencia_cantidad'] . " hour) as fecha,now() as hoy";
                        //echo $query_new . "<br>";
                        $result_new = mysql_query($query_new) or die('Consulta fallida: ' . mysql_error());
                        $fecha_calculada = mysql_fetch_array($result_new, MYSQL_ASSOC);

                        echo "Ultimo Correo guardado: " . $ultimo_correo . "<br>";
                        echo "Fecha nueva de Ultimo Correo: " . $ultimo_correo_guardar . "<br>";
                        echo "Fecha de proximo correo calculada: " . $fecha_calculada['fecha'] . "<br>";
                        $dif = dias_restantes($ultimo_correo_guardar, $ultimo_correo);
                        echo "Diferencia de dias: " . $dif . "<br>";
                        $query_dif = "select timediff('" . $ultimo_correo_guardar . "', '" . $fecha_calculada['fecha'] . "') as hora";
                        $result_dif = mysql_query($query_dif) or die('Consulta fallida: ' . mysql_error());
                        $fecha_dif = mysql_fetch_array($result_dif, MYSQL_ASSOC);
                        echo "Diferencia horas: " . $fecha_dif['hora'] . "<br>";
                        
                        $f_1 = explode( ":", $fecha_dif['hora']);
                        
                        if($f_1[0] >= $alertas['frecuencia_cantidad'])
                            echo "Se ha retrasado o es la hora de enviar el mensaje <br>";
                        else
                            echo "Ya se envio el correo <br>"; 
                                
                    } else if ($alertas['frecuencia_tipo'] == 2) {
                        //Dias
                        
                    }
                    //envia_correo($user['email'], $titulo, $mensaje);
                }

                $envia = $user['email'] . " - " . $user['email2'];
            }
            //Buscar Codigos
            if (!is_null($fila['codigo_id'])) {
                $query_codigo = 'select * from codigo where id=' . $fila['codigo_id'];
                $result_codigo = mysql_query($query_codigo) or die('Consulta fallida: ' . mysql_error());
                $codigo = mysql_fetch_array($result_codigo, MYSQL_ASSOC);

                $query_user = 'select * from users where id=' . $codigo['usuario_id'];
                $result_user = mysql_query($query_user) or die('Consulta fallida: ' . mysql_error());
                $user = mysql_fetch_array($result_user, MYSQL_ASSOC);

                if ($band)
                    echo "";
                //envia_correo($user['email'], $titulo, $mensaje);
                //$envia = $user['email'] . " - " . $user['email2'];
            }

//Falta mandar email a admin
            $query_admin = "select * from users where tipo_usuario=1 or tipo_usuario=2";
            $result_admin = mysql_query($query_admin) or die('Consulta fallida: ' . mysql_error());
            $adminis = "";
            while ($admin = mysql_fetch_array($result_admin, MYSQL_ASSOC)) {
                $adminis .= $admin['email'] . " - " . $admin['email2'];
                if ($band)
                    echo "";
                //envia_correo($admin['email'], $titulo, $mensaje);
            }

            if ($band) {//Aqui va enviar el email
                echo "Enviar a: " . $envia . "<br>";
                echo "Administradores: " . $adminis . "<br>";
                echo "<b>" . $titulo . "</b><br>";
                echo $mensaje . "<br><hr>";
            }
        }
    }//----Fin de sql fases----
}
// Liberar resultados
mysql_free_result($result);

// Cerrar la conexión
mysql_close($link);
?>