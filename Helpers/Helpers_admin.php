<?php

declare(strict_types=1);

/* Funciones no utlizadas ==========================================================================*/
//function clear_telf() { limpiar el prefijo pais de un numero (arg)
//        $int_len = int_len($intMobil);
//      if ($int_len > 11) {
//        dep(substr('$intMobil', -$int_len, 3));
//        $intMobil = substr('$intMobil', -$int_len, 3) == 549 ? substr('$intMobil', 3) :
//            (substr('$intMobil', -$int_len, 2) == 54 ? substr('$intMobil', 2) : $intMobil);
//        dep($intMobil);
//      }
//
//}

/*
  function detectar_dispositivo_1() {
  $tablet_browser = 0;
  $mobile_browser = 0;
  $body_class = 'desktop';
  if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
  $tablet_browser++;
  $body_class = 'tablet';
  }
  if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
  $mobile_browser++;
  $body_class = 'mobile';
  }

  if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
  $mobile_browser++;
  $body_class = 'mobile';
  }
  $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
  $mobile_agents = array(
  'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
  'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
  'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
  'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-', 'newt', 'noki', 'palm', 'pana',
  'pant', 'phil', 'play', 'port', 'prox');

  if (in_array($mobile_ua, $mobile_agents)) {
  $mobile_browser++;
  }

  if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
  $mobile_browser++;
  //Check for tablets on opera mini alternative headers
  $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
  if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
  $tablet_browser++;
  }
  }
  if ($tablet_browser > 0) {// Si es tablet has lo que necesites
  return 'tablet';
  } else if ($mobile_browser > 0) {// Si es dispositivo mobil has lo que necesites
  return 'mobile';
  } else {// Si es ordenador de escritorio has lo que necesites
  return 'desktop';
  }
  }
 */
/*
  function detectDeviceType() {//chatgpt
  $userAgent = $_SERVER['HTTP_USER_AGENT'];

  $deviceTypes = array(
  'tablet' => '/(ipad|android(?!.*mobile)|tablet|kindle)|(windows(?!.*phone)(?=.*touch)|touchpad)/i',
  'mobile' => '/(iphone|ipod|android.*(mobile|mini)|windows.*phone)/i'
  );

  foreach ($deviceTypes as $deviceType => $regex) {
  if (preg_match($regex, $userAgent)) {
  return $deviceType;
  }
  }

  return 'desktop';
  }
 */
/*
  function dispositivoTipo() {
  include_once 'Librerias/vendor/autoload.php';
  $detect = new \Detection\MobileDetect();

  $dispositivo = 'PC';
  $detect->isMobile() ? $dispositivo = 'movil' : '';
  $detect->isiphone() ? $dispositivo = 'movil' : '';
  $detect->isIphone() ? $dispositivo = 'movil' : '';
  $detect->istablet() ? $dispositivo = 'Tablet' : '';
  $detect->isTablet() ? $dispositivo = 'Tablet' : '';

  return $dispositivo;
  }
 */






/*
function strClean($string) {//Elimina exceso de espacios entre palabras
  //$string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
  $string = trim($string); //Elimina espacios en blanco al inicio y al final
  $string = stripslashes($string); // Elimina las \ invertidas
  $string = str_ireplace('<script>', '', $string);
  $string = str_ireplace('</script>', '', $string);
  $string = str_ireplace('<script src>', '', $string);
  $string = str_ireplace('<script type=>', '', $string);
  $string = str_ireplace('SELECT * FROM', '', $string);
  $string = str_ireplace('DELETE FROM', '', $string);
  $string = str_ireplace('INSERT INTO', '', $string);
  $string = str_ireplace('SELECT COUNT(*) FROM', '', $string);
  $string = str_ireplace('DROP TABLE', '', $string);
  $string = str_ireplace('OR "1"="1"', '', $string);
  $string = str_ireplace("OR '1'='1", '', $string);
  $string = str_ireplace('OR ´1´=´1´', '', $string);
  $string = str_ireplace('is NULL; --', '', $string);
  $string = str_ireplace('is NULL; --', '', $string);
  $string = str_ireplace("LIKE '", '', $string);
  $string = str_ireplace('LIKE "', '', $string);
  $string = str_ireplace('LIKE ´', '', $string);
  $string = str_ireplace("OR 'a'='a'", '', $string);
  $string = str_ireplace('OR "a"="a"', '', $string);
  $string = str_ireplace('OR ´a´=´a', '', $string);
  $string = str_ireplace('OR ´a´=´a', '', $string);
  $string = str_ireplace('--', '', $string);
  $string = str_ireplace('  ', ' ', $string);
  $string = str_ireplace('^', '', $string);
  $string = str_ireplace('[', '', $string);
  $string = str_ireplace(']', '', $string);
  $string = str_ireplace('===', '', $string);
  $string = str_ireplace('==', '', $string);
  return $string;
}
*/

/*function clear_cadena(string $cadena) { // reemplaza en una cadena caracteres especiales por caracteres comunes
//Reemplazamos la A y a
  $cadena = str_replace(array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'), array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'), $cadena);
//Reemplazamos la E y e
  $cadena = str_replace(array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'), array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'), $cadena);
//Reemplazamos la I y i
  $cadena = str_replace(array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'), array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'), $cadena);
//Reemplazamos la O y o
  $cadena = str_replace(array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'), array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'), $cadena);
//Reemplazamos la U y u
  $cadena = str_replace(array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'), array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'), $cadena);
//Reemplazamos la N, n, C y c
  $cadena = str_replace(array('Ñ', 'ñ', 'Ç', 'ç', ',', '.', ';', ':'), array('N', 'n', 'C', 'c', '', '', '', ''), $cadena);
//Reemplazamos (parentesis) 
  $cadena = str_replace(array('(', ')', '.', ','), array('', '', '', ''), $cadena);
  $cadena = str_replace(array('/', '  '), array('-', ' '), $cadena);
  return $cadena;
}*/
/*function clear_cadena(string $cadena): string {
  // reemplaza en una cadena caracteres especiales por caracteres comunes
  $replacements = [
    'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a', 'ª' => 'a', 'É' => 'E',
    'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
    'Í' => 'I', 'Ì' => 'I', 'Ï' => 'I', 'Î' => 'I', 'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
    'Ó' => 'O', 'Ò' => 'O', 'Ö' => 'O', 'Ô' => 'O', 'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
    'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U', 'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
    'Ñ' => 'N', 'ñ' => 'n', 'Ç' => 'C', 'ç' => 'c'
  ];

  $cadena = strtr($cadena, $replacements);
  $cadena = str_replace(['(', ')', '.', ',', '/'], ['', '', '', '', '-'], $cadena);
  $cadena = preg_replace('/\s+/', ' ', $cadena);

  return $cadena;
}*/

/*function passGenerator($length = 10) {
  $pass = '';
  $longitudPass = $length;
  $cadena = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvxyz1234567890';
  $longitudCadena = strlen($cadena);
  for ($i = 1; $i <= $longitudPass; $i++) {
    $pos = rand(0, $longitudCadena - 1);
    $pass .= substr($cadena, $pos, 1);
  }
  return $pass;
}

function token() {
  $r1 = bin2hex(random_bytes(10));
  $r2 = bin2hex(random_bytes(10));
  $r3 = bin2hex(random_bytes(10));
  $r4 = bin2hex(random_bytes(10));
  return $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
}

function url_ws($texto) {
  return urlencode(str_ireplace('@url_pag', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], $texto));
}

function redondear_decenas($numero) {
  $ceros = $numero > 999 ? 100 : ($numero > 99 ? 10 : 1);
  return ceil($numero / $ceros) * $ceros;
}

function formatMoney($cantidad) {
  return SMONEY . '' . number_format((float) $cantidad, 0, SPD, SPM);
}*/

/*function getTokenPayPal() {
//https://developer.paypal.com/docs/api/get-an-access-token-curl/
  $payLogyn = curl_init(URL_API_PAYPAL . '/v1/oauth2/token'); //url de consulta api
  curl_setopt($payLogyn, CURLOPT_SSL_VERIFYPEER, false); //verifica el certificado ssl 
  curl_setopt($payLogyn, CURLOPT_RETURNTRANSFER, true); // indica que retornara informacion 
  curl_setopt($payLogyn, CURLOPT_USERPWD, CLIENTE_PAYPAL . ':' . SECRET); // enviamos los datos del login el numero de cliente y el secret 
  curl_setopt($payLogyn, CURLOPT_POSTFIELDS, 'grant_type=client_credentials'); //enviamos el body por metodo post
  $result = curl_exec($payLogyn); //ejecuta la configuracion de la variable payLogin
  $err = curl_error($payLogyn); //consultamos la devolucion de error 
  curl_close($payLogyn); //cerramos el curl
  if ($err) { //si hay mensaje de error retornamos el mensaje, de lo contrario retornamos el token
    $request = 'getTokenCURL Error #: ' . $err;
  } else {
    $request = json_decode($result)->access_token;
  }
  return $request;
}
function curlConectionGet(string $ruta, string $contentType = null, string $token = null) {
  $content_type = $contentType != null ? $contentType : 'application/x-www-form-urlencoded'; // si $contentType es distinto de null pordefecto sera application/x-www-form-urlencoded
  if ($token != null) {
    $arrHeader = array('Content-Type: ' . $content_type, 'Authorization: Bearer ' . $token);
  } else {
    $arrHeader = array('Content-Type: ' . $content_type);
  }

  $ch = curl_init(); // iniciamos el curl pero no le indicamos la url 
  curl_setopt($ch, CURLOPT_URL, $ruta); //insertamos la url
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //verifica el certificado ssl 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // indica que retornara informacion 
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader); // indicamos los Header 
  $result = curl_exec($ch); //ejecuta la configuracion de la variable payLogin
  $err = curl_error($ch); //consultamos la devolucion de error 
  curl_close($ch); //cerramos el curl
  //si hay un error retornamos el mensaje, de lo contrario retornamos el array de datos 
  return $err ? $request = 'CURL Error #: ' . $err : json_decode($result);
}

// ======================================================================================================================== 

function curlConectionPost(string $ruta, string $contentType = null, string $token = null) {
  $content_type = $contentType != null ? $contentType : 'application/x-www-form-urlencoded'; // si $contentType es distinto de null pordefecto sera application/x-www-form-urlencoded
  if ($token != null) {
    $arrHeader = array('Content-Type: ' . $content_type, 'Authorization: Bearer ' . $token);
  } else {
    $arrHeader = array('Content-Type: ' . $content_type);
  }
  $ch = curl_init(); // iniciamos el curl pero no le indicamos la url 
  curl_setopt($ch, CURLOPT_URL, $ruta); //insertamos la url
  curl_setopt($ch, CURLOPT_POST, true); //indicamos que la peticion sera tipo post
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //verifica el certificado ssl 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // indica que retornara informacion 
  curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader); // indicamos los Header 
  $result = curl_exec($ch); //ejecuta la configuracion de la variable payLogin
  $err = curl_error($ch); //consultamos la devolucion de error 
  curl_close($ch); //cerramos el curl
  //si hay un error retornamos el mensaje, de lo contrario retornamos el array de datos 
  return $err ? $request = 'CURL Error #: ' . $err : json_decode($result);
}
  
  */

/*use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function sendEmail($data, $bodyMail) {
//Declaracion de variables requiere 2 elementos un array de datos y el nombre ldel archivo a levantar en Views/Template/Email/ 
  $empresa = $data['empresa'];
  $emailDestino = $data['email'];
  $asunto = $data['asunto'];
  $nomb_user = $data['nombreUsuario'];
  $remitente = $empresa['email']; // no-reply@dominio.com
// //ENVIO DE CORREO
  if ($empresa['smtp_status']) { //si la variable es 1 usamos libreria PHPMailer con smtp. de lo contrarios usamos funcion mail() de PHP 
    include_once '';
    ('Librerias/vendor/autoload.php'); //include_once __DIR__ . '/.././Librerias/PHPMailer/src/Exception.php';//include_once __DIR__ . '/.././Librerias/PHPMailer/src/PHPMailer.php'; //include_once __DIR__ . '/.././Librerias/PHPMailer/src/SMTP.php';
    $mail = new PHPMailer(true); // instanciacion y pasando valor 'true' habilatas las exceptions
    try {
//CONFIGURACION DEL SERVIDOR
//$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output. Habilitar la salida de depuración detallada
      $mail->isSMTP(); // Send using SMTP. Enviar usando SMTP
      $mail->Host = $empresa['host_mail']; // Set the SMTP server to send through. Configure el servidor de envio SMTP 
      $mail->SMTPAuth = true; // Enable SMTP authentication. Habilitar la autenticación SMTP
      $mail->Username = $empresa['serv_mail']; // SMTP username. Nombre de usuario SMTP
      $mail->Password = $empresa['pass_mail']; // SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged. Habilite el cifrado TLS; Se recomienda `PHPMailer :: ENCRYPTION_SMTPS`
      $mail->Port = 587; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS`. Autenticación por SMTP en puertos 25 sin encriptar, 587 TLS, 465 SSL
//Recipients
      $mail->setFrom($empresa['serv_mail'], $empresa['nombre_comercial']); // seleciona la cuenta de email smtp y el nombre comercial
      $mail->addAddress($emailDestino, $nomb_user); // Add a recipient. seleccuina el mail de destino y el nombre del destinatario
//$mail->addAddress('ellen@example.com');// Name is optional
// $mail->addReplyTo('info@example.com', 'Information');
// $mail->addCC('cc@example.com'); // con copia al mail
// $mail->addBCC('bcc@example.com'); // con copia oculta al mail
// ADJUNTOS 
// $mail->addAttachment('/var/tmp/file.tar.gz');// Add attachments. Agregar archivos adjuntos
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');// Optional name
// Content
      $mail->isHTML(true); // Set email format to HTML. Establecer el formato de correo electrónico en HTML
      $mail->Subject = $data['asunto']; // asunto
      $mail->Body = $bodyMail; // cuerpo del mail en HTML
//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; //Este es el cuerpo en texto sin formato para clientes de correo electrónico que no son HTML.

      $send = $mail->send();
      return $send;
    } catch (Exception $e) {
      return 'Mailer Error:' . $mail->ErrorInfo;
    }
  } else {
    try {
//Generamos el html de mail 
// ob_start(); //crea un búfer de salida. 
// include_once('Views/Template/Email/' . $template . '.php');
// $mensaje = ob_get_clean(); // cierra la ejecucion del bufer. (lo que se ejecute entre ob_start() y ob_get_clean() se puede guardar en una variable )
      $parametros = 'MIME-Version: 1.0\r\n';
      $parametros .= 'Content-type: text/html; charset=UTF-8\r\n';
      $parametros .= "From: {$empresa['nombre_comercial']} <{$remitente}>\r\n";
//$parametros .= 'Cc: myboss@example.com' . '\r\n'; //opcional
      $send = mail($emailDestino, $asunto, $bodyMail, $parametros);
      return $send;
    } catch (Exception $ex) {
      return 'Mailer Error:' . $ex->ErrorInfo;
    }
  }
}*/

/*function array_sort_by(&$arrIni, $col, $order = SORT_ASC) {
  //ordena un array asociativo por medio de una de sus elementos 
  $arrAux = array();
  foreach ($arrIni as $key => $row) {
    $arrAux[$key] = is_object($row) ? $arrAux[$key] = $row->$col : $row[$col];
    $arrAux[$key] = is_numeric($arrAux[$key]) ? $arrAux[$key] : strtolower($arrAux[$key]);
  }
  array_multisort($arrAux, $order, $arrIni);
}
}*/

/*function path() {
  $arrUrl = explode('/', !empty($_GET['url']) ? $_GET['url'] : 'home/home');
  $metodo = (!empty($arrUrl[1]) && $arrUrl[1] != '') ? $arrUrl[1] : $arrUrl[0];
  $url = (!empty($arrUrl[2]) && $arrUrl[2] != '') ? $arrUrl[2] : null;
  return array($metodo, $url);
}*/
/*function int_len($n) {
  $numero = $n;
  $digito = 0;
  //se sumara un digito y el valor de numero se dividira entre 10 
  //hasta que el valor de numero sea igual a 0,
  // entoces retornara la cantidad de digitos
  do {
    $numero = floor($numero / 10);
    $digito = $digito + 1;
  } while ($numero > 0);
  return $digito;
}*/
//
//function ofertaArtiva($data_articulo) { // toma los datos de un producto y devuelve si una oferta esta activa o no
//  $fecha_actual = strtotime(date('d-m-Y H:i:00', time()));
//  $fecha_ini = $data_articulo['oferta_f_fin'] != null ? strtotime($data_articulo['oferta_f_ini'] . ' 00:00:00') : '';
//  $fecha_fin = $data_articulo['oferta_f_fin'] != null ? strtotime($data_articulo['oferta_f_fin'] . ' 23:59:59') : strtotime('+1 hour', $fecha_actual);
//
//  return ( $data_articulo['oferta'] > 0 &&
//      $fecha_ini <= $fecha_actual &&
//      $fecha_fin >= $fecha_actual) ? 1 : 0;
//}

/*function cantCarrito() {
  $cantCarrito = 0;
  if (isset($_SESSION['arrCarrito']) and count($_SESSION['arrCarrito']) > 0) {
    foreach ($_SESSION['arrCarrito'] as $producto) {
      $cantCarrito += $producto['cantidad']; // sumamos las cantidades por item 
    }
  }
  return $cantCarrito;
}*/

//function html_producto_carrito($pro) {
//  $html_oferta = $pro['oferta'] != 0 ?
//      "<span class='header-cart-item-info p-l-15 p-r-10'>{$pro['cantidad']} X " . formatMoney($pro['oferta']) . '</span>' : '';
//  $html_precio = $pro['oferta'] !== 0 ?
//      "<span class='header-cart-item-info del_precio_oferta stext-107 cl12'>" . formatMoney($pro['precio']) . '</span>' :
//      "<span class='header-cart-item-info p-l-15 p-r-10'>{$pro['cantidad']} X " . formatMoney($pro['precio']) . '</span>';
//
//  return "<!-- Item carrito -->
//          <li class='header-cart-item flex-w flex-t m-b-15'>
//            <div loading='lazy' class='header-cart-item-img prod-pic-rel' style='background: url({$pro['img']})' idpr='{$pro['idproducto']}' op = '1' onclick='fntdelItem(this)' >
//              <img loading='lazy' class='prod-scale-img' src='{$pro['img']}' alt='{$pro['nombre']}'>
//            </div>
//            <div class='header-cart-item-txt p-t-6'>
//              <a href='" . base_url() . "tienda/producto/{$pro['idproducto']}/{$pro['ruta']}' 
//                class='header-cart-item-name m-b-18 hov-cl1 trans-04 text-recort'>{$pro['nombre']} </a>
//                <div class='row'>  
//                {$html_oferta}
//                {$html_precio}
//                </div>
//            </div>
//          </li>";
//}

/*function is_number($str) {
  $str = str_replace(',', '.', $str);
  if (!is_numeric($str))
    return false;
  $str = (int) $str;
  if (!is_integer($str) AND !is_float($str))
    return false;
  return true;
}*/
  /*


   */

/*function convertImageToWebP($imgArchivo, $quality = 75) {//retorna una imagen (nombre) webp con calidad 100 si no se especifica la calidad de la misma
  $nombre = pathinfo($imgArchivo, PATHINFO_FILENAME);
  $extension = pathinfo($imgArchivo, PATHINFO_EXTENSION);
  switch ($extension) {
    case 'jpg' :
    case 'jpeg':
      $image = imagecreatefromjpeg($imgArchivo);
      break;
    case 'png': $image = imagecreatefrompng($imgArchivo);
      break;
    case 'gif': $image = imagecreatefromgif($imgArchivo);
      break;
    case 'webp': $image = imagecreatefromwebp($imgArchivo);
      break;
  }
  $destination = './uploads/' . FILE_SISTEM_CLIENTE . $nombre . '.webp'; //ruta y nombre de destino
  imagewebp($image, $destination, $quality); //primer parametro la imagen a convertir, segundo parametro la direcion de destino , tercer parametro calidad de imagen 0 menor calidad/menor tamaño vs 100 mayor calidad/mayor tamaño 
  return $nombre . '.webp';
}*/
/*function convertImageToJPG($imgArchivo, $nombfinal, $quality = 75) {//retorna una imagen webp con calidad 100 si no se especifica la calidad de la misma
  $extension = pathinfo($imgArchivo, PATHINFO_EXTENSION); //$nombre = pathinfo($imgArchivo, PATHINFO_FILENAME);
  $destination = './uploads/' . FILE_SISTEM_CLIENTE . $nombfinal; //ruta y nombre de destino
  if (file_exists($imgArchivo)) {

    switch ($extension) {
      case 'jpg' : case 'jpeg': $image = imagecreatefromjpeg($imgArchivo);
        break;
      case 'png': $image = imagecreatefrompng($imgArchivo);
        break;
      case 'gif': $image = imagecreatefromgif($imgArchivo);
        break;
      case 'webp': $image = imagecreatefromwebp($imgArchivo);
        break;
    }
    imagejpeg($image, $destination, $quality); //primer parametro la imagen a convertir, segundo parametro la direcion de destino , tercer parametro calidad de imagen 0 menor calidad/menor tamaño vs 100 mayor calidad/mayor tamaño 
  }
  return $destination;
}*/

/*
  recive una imagen y devuelve el alto y el ancho de la imagen segun su orientacion
  se establece el alto o ancho de una imagen determinado la altura maxima y calculando el acho
  ejemplo 1: recibe una imagen de $directorio_foto vertical de con las siguientes medidas:
  alto:800px ancho:600
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando asi alto:1200px ancho:900px

  ejemplo 1: recibe una imagen de $directorio_foto vertical de con las siguientes medidas:
  alto:800px ancho:600px
  $medida_alto_alto es igual a 1200
  entonces el valor mas alto sera igual a $medida_alto_alto quedando algo asi: alto:1200px ancho:900px

  ejemplo 2: recibe una imagen de $directorio_foto vertical de con las siguientes medidas:
  alto:1560px ancho:1170px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:1200px ancho:900px

  ejemplo 3: recibe una imagen de $directorio_foto horizontal de con las siguientes medidas:
  alto:600px ancho:800px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:900px ancho:1200px

  ejemplo 4: recibe una imagen de $directorio_foto horizontal de con las siguientes medidas:
  alto:1170px ancho:1560px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:900px ancho:1200px

  ejemplo 5: recibe una imagen de $directorio_foto cuadrada de con las siguientes medidas:
  alto:800x ancho:800px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:1200px ancho:1200px

  ejemplo 5: recibe una imagen de $directorio_foto cuadrada de con las siguientes medidas:
  alto:800x ancho:800px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:1200px ancho:1200px
  ejemplo 6: recibe una imagen de $directorio_foto cuadrada de con las siguientes medidas:
  alto:1600x ancho:1600px
  $medida_alto_alto es igual a 1200
  entonces el valor mayor sera igual a $medida_alto_alto quedando algo asi: alto:1200px ancho:1200px
function img_alto_ancho2($directorio_foto, $ancho_def, $alto_def) {

  if (file_exists($directorio_foto)) {
    list($ancho, $alto) = getimagesize($directorio_foto);
    $proporcion = $ancho / $alto;
    if ($ancho > $ancho_def) {
      $ancho = $ancho_def;
      $alto = $ancho / $proporcion;
    }
    if ($alto > $alto_def) {
      $alto = $alto_def;
      $ancho = $alto * $proporcion;
    }
  } else {
    $ancho = $ancho_def;
    $alto = $alto_def;
  }
  return array($ancho, $alto);
}
 */

//function img_alto_ancho($directorio_foto, $medida_alto_alto) {
//
//  if (file_exists($directorio_foto)) {
//    $imagen = getimagesize($directorio_foto);    //Sacamos la información
//    $ancho = $imagen[0];
//    $alto = $imagen[1];
//    $orientacion = $ancho <= $alto ? 'v' : 'o';
//    if ($orientacion == 'v') {
//      $porciento = obtenerPorcentajeDiferencia($medida_alto_alto, $ancho);
//      $ancho = $medida_alto_alto;
//      $alto = $alto / 100 * $porciento;
//    } else if ($orientacion == 'o') {
//      $porciento = obtenerPorcentajeDiferencia($medida_alto_alto, $alto);
//      $ancho = $medida_alto_alto;
//      $alto = $alto / 100 * $porciento;
//    }
//  } else {
//    $ancho = null;
//    $alto = null;
//  }
//  return array($ancho, $alto);
//}


/*
function estadoFoto($nombre_foto, $foto_actual, $foto_remove) {
//recibe 3 parametros referente la fofografia y para determinar si es nueva, actualizada, borrada o sin cambios
  if ($nombre_foto == '' && $foto_actual != 'portada_categoria.png' && $foto_remove != 1) {
    $estado_foto = 'sin_mov';
  }
  if ($nombre_foto == '' && $foto_actual == 'portada_categoria.png' ||
      $nombre_foto == '' && $foto_actual == '') {
    $estado_foto = 'sin_mov_def';
  }
  if ($nombre_foto != '' && $nombre_foto != $foto_actual) {
    $estado_foto = 'actualizada';
  }
  if (($foto_actual == '' || $foto_actual == 'portada_categoria.png') && $nombre_foto != '') {
    $estado_foto = 'nueva';
  }
  if ($nombre_foto == '' &&
      $foto_actual != 'portada_categoria.png' &&
      $foto_remove == 1) {
    $estado_foto = 'eliminada';
  }
  return $estado_foto;
}
*/


/*function dispositivoOS() {
  include_once('Librerias/vendor/autoload.php');
  $detect = new \Detection\MobileDetect();
  $OS = 'Otro';

  //  strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), array('MAC')) !== false ? $OS = 'Mac' : '';
  $detect->isAndroidOS() ? $OS = 'AndroidOS' : '';
  $detect->isBlackBerryOS() ? $OS = 'BlackBerryOS' : '';
  $detect->isPalmOS() ? $OS = 'PalmOS' : '';
  $detect->isSymbianOS() ? $OS = 'SymbianOS()' : '';
  $detect->isWindowsMobileOS() ? $OS = 'WindowsMobileOS()' : '';
  $detect->isWindowsPhoneOS() ? $OS = 'WindowsPhoneOS' : '';
  $detect->isiOS() ? $OS = 'iOS' : '';
  $detect->isIOS() ? $OS = 'iOS' : '';
  $detect->isiPadOS() ? $OS = 'iPadOS' : '';
  preg_match('/iPad/i', $_SERVER['HTTP_USER_AGENT']) ? $OS = 'iPadOS' : '';
  $detect->isSailfishOS() ? $OS = 'SailfishOS' : '';
  $detect->isMeeGoOS() ? $OS = 'MeeGoOS' : '';
  $detect->isMaemoOS() ? $OS = 'MaemoOS' : '';
  $detect->isJavaOS() ? $OS = 'JavaOS' : '';
  $detect->iswebOS() ? $OS = 'webOS' : '';
  $detect->isbadaOS() ? $OS = 'BadaOS' : '';
  preg_match('/Linux/i', $_SERVER['HTTP_USER_AGENT']) ? $OS = 'linux' : '';
  preg_match('/Windows/i', $_SERVER['HTTP_USER_AGENT']) ? $OS = 'Windows' : '';
  preg_match('/Macintosh/i', ($_SERVER['HTTP_USER_AGENT'])) ? $OS = 'Mac' : '';
  preg_match('/Mac/i', ($_SERVER['HTTP_USER_AGENT'])) ? $OS = 'Mac' : '';
  preg_match('/OS X/i', ($_SERVER['HTTP_USER_AGENT'])) ? $OS = 'Mac' : '';
  preg_match('/iOS X/i', $_SERVER['HTTP_USER_AGENT']) ? $OS = 'OSX' : '';
  preg_match('/iOS X/i', $_SERVER['HTTP_USER_AGENT']) ? $OS = 'OSX' : '';
  return $OS;
}*/


//function diferencia_entre_fechas($fecha1, $fecha2) {//recibe 2 fecha y devuelve la diferencia en un string.
//  dep($fecha1);
//  dep($fecha2);
//  $fecha2 = new DateTime($fecha2);
//  $intervalo = $fecha1->diff($fecha2);
//  $mes = $intervalo->m < 1 ? '' : ($intervalo->m >= 1 ? $intervalo->m . ' meses ' : $intervalo->m . ' mes ');
//  $dia = $intervalo->d < 1 ? '' : ($intervalo->d >= 1 ? $intervalo->d . ' dias ' : $intervalo->d . ' dia ');
//  $hora = ($intervalo->h < 1 && $intervalo->m > 0) ? '' : ($intervalo->h >= 1 ? $intervalo->h . ' horas ' : $intervalo->h . ' hora ');
//  $minuto = ($intervalo->i < 1 && $intervalo->d > 0) ? '' : ($intervalo->i >= 1 ? $intervalo->i . ' minutos ' : $intervalo->i . ' minuto ');
//  return $tiempo = $mes . $dia . $hora . $minuto;
//}








/*----------------------------------------------------------------------------------------------*/
/*
  <div id="divLoading" style="
       position: fixed;
       top: 0;
       left: 0;
       width: 100%;
       height: 100%;
       background-color: rgba(255, 255, 255, 0.99);
       z-index: 9999;
       display: none;
       justify-content: center;
       align-items: center;
       opacity: 0;
       transition: opacity 0.5s ease-in-out;"> 
    <div>
      <img src="<?= DIR_MEDIA; ?>images/loading.svg" alt="Loading" style="  width: 50px;  height: 50px;">
    </div>
  </div>
 */