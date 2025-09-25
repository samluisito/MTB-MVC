<?php

declare(strict_types=1);
/* archivo de scrips que se usan en todo el proyecto */

function base_url() { //retorna la URL base del sistema
  return BASE_URL . '/';
}

/* HEADER Y FOOTER -------------------------------------------------------- */

// HEADER Y FOOTER TIENDA 
function headerTienda($data = '') {
  if (isset($data['header'])) {
    include_once __DIR__ . '/../Views/Template/tienda_header.php';
  }
}

function footerTienda($data = '') {
  if (isset($data['footer'])) {
    include_once __DIR__ . '/../Views/Template/tienda_footer.php';
  }
}

function getModal(string $nameModal, $data) {
  include_once 'Views/Template/Modals/' . $nameModal . '.php';
}

function getDisplay($__DIR__, string $nameModal, $data) {
  include_once $__DIR__ . '/' . $nameModal . '.php';
}

function intClean($param) {
  //Elimina cualquie caracter de la variable y devuelve solo deja numeros  //$param = 'aANx182 29 ().';
  return filter_var($param, FILTER_SANITIZE_NUMBER_INT);
}

/* Elimina exceso de espacios entre palabras -------------------------------------------------------- */

function strClean($string) {
  $string = trim($string);
  $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $patterns = array('/SELECT \* FROM/i', '/DELETE FROM/i', '/INSERT INTO/i', '/SELECT COUNT\(\*\) FROM/i', '/DROP TABLE/i', '/OR "1"="1"/i', '/OR \'1\'=\'1\'/i', '/OR ´1´=´1´/i', '/is NULL; --/i', '/is NULL;--/i', '/LIKE \'%/', '/LIKE \"/', '/LIKE ´/', '/OR \'a\'=\'a\'/i', '/OR "a"="a"/i', '/OR ´a´=´a/i', '/--/', '/  /', '/\^/', '/\[/', '/\]/', '/===/', '/==/');
  $replacements = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
  $string = preg_replace($patterns, $replacements, $string);
  return $string;
}

function clear_cadena(string $cadena): string {
  $search = array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê', 'Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î', 'Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô', 'Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û', 'Ñ', 'ñ', 'Ç', 'ç', ',', '.', ';', ':', '(', ')', '/', '  ');
  $replace = array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e', 'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i', 'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o', 'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u', 'N', 'n', 'C', 'c', '', '', '', '', '', '', '-', ' ');
  return str_replace($search, $replace, $cadena);
}

function passGenerator($length = 10) {
  $cadena = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvxyz1234567890';
  $pass = substr(str_shuffle($cadena), 0, $length);
  return $pass;
}

function token() {
  return sprintf('%s-%s-%s-%s', bin2hex(random_bytes(10)), bin2hex(random_bytes(10)), bin2hex(random_bytes(10)), bin2hex(random_bytes(10)));
}

function url_ws($texto) {
  return urlencode(str_replace('@url_pag', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], $texto));
}

function redondear_decenas($numero) {
  $ceros = $numero > 999 ? 100 : ($numero > 99 ? 10 : 1);
  return ceil($numero / $ceros) * $ceros;
}

function formatMoney($cantidad) {
  return SMONEY . number_format($cantidad, 0, SPD, SPM);
}

//function sessionUser(int $idPersona) {//genera las variables de sesion en el modelo login modelo, login model
//  include_once 'Models/LoginModel.php';
//  return (new LoginModel())->sessionLogin($idPersona);
//}

function sessionLogin($objModel, $idpersona): bool {
  $idpersona = intval($idpersona);
  $request_user['user_data'] = $objModel->select("SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos,
            p.telefono, p.email_user, p.nit, p.nombrefiscal, p.direccionfiscal, p.rolid, p.datecreated,
            p.status, r.nombrerol, r.idrol 
            FROM persona p  INNER JOIN rol r 
            ON p.rolid = r.idrol where p.idpersona =?", array($idpersona));

  if ($request_user['user_data']['status']) {
    $request_user['permisos'] = $objModel->select_all("SELECT p.idmodulo, p.rolid, p.moduloid, m.titulo, p.ver, p.crear, p.actualizar, p.eliminar 
                FROM permisos p  INNER JOIN modulo m ON p.moduloid = m.idmodulo 
                where p.rolid in (SELECT rolid FROM persona where idpersona =?)", array($idpersona));
    $_SESSION['idUser'] = $idpersona;
    $_SESSION['login'] = true;
//  $request_user = $objModel->sessionUserData(intval($idPersona));
    $_SESSION['userData'] = $request_user['user_data']; //guarda los datos extraidos la super SESION
    $_SESSION['userData']['foto_user'] = 'images/Usuario-Icono.jpg';
    $request_permisos = $request_user['permisos'];
    for ($i = 0; $i < count($request_permisos); $i++) {
      $idModulo = $request_permisos[$i]['moduloid'];
      $_SESSION['userPermiso'][$idModulo]['modulo'] = $request_permisos[$i]['titulo'];
      $ver = $request_permisos[$i]['ver'];
      $_SESSION['userPermiso'][$idModulo]['ver'] = $ver;
      if ($ver) {
        $_SESSION['userPermiso'][$idModulo]['crear'] = $request_permisos[$i]['crear'];
        $_SESSION['userPermiso'][$idModulo]['actualizar'] = $request_permisos[$i]['actualizar'];
        $_SESSION['userPermiso'][$idModulo]['eliminar'] = $request_permisos[$i]['eliminar'];
      }
    }
    $objModel = '';
    return true;
  }
  return false;
}

function getDolarHoy(): float {
  return floatval($_SESSION['dolarhoy']['precio']);
}

function encript($param) {
  return openssl_encrypt($param, METHODENCRIPT, KEY);
}

function decript($param) {
  return openssl_decrypt($param, METHODENCRIPT, KEY);
}

/* -------------------------------------------------------------------------------------------------- */

//https://developer.paypal.com/docs/api/get-an-access-token-curl/

function getTokenPayPal() {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => URL_API_PAYPAL . '/v1/oauth2/token',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYHOST => 2,
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_USERPWD => CLIENTE_PAYPAL . ':' . SECRET,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
  ));
  $result = curl_exec($curl);
  if (curl_errno($curl)) {
    $request = 'getTokenCURL Error #: ' . curl_error($curl);
  } else {
    $request = json_decode($result)->access_token;
  }
  curl_close($curl);
  return $request;
}

/* --------------------------------------------------------------------------------------------------- */

function curlConection(string $ruta, string $method = 'GET', string $contentType = 'application/x-www-form-urlencoded', string $token = null) {
  //reemplaza a curlConectionGet y curlConectionPost

  $arrHeader = array('Content-Type: ' . $contentType);
  if ($token != null) {
    $arrHeader[] = 'Authorization: Bearer ' . $token;
  }

  $ch = curl_init();
  curl_setopt_array($ch, array(
    CURLOPT_URL => $ruta,
    CURLOPT_CUSTOMREQUEST => $method,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $arrHeader
  ));
  $result = curl_exec($ch);
  $err = curl_error($ch);
  curl_close($ch);

  return $err ? 'CURL Error #: ' . $err : json_decode($result);
}

function rellena(int $numero): string {
  return str_pad((string) $numero, 5, '0', STR_PAD_LEFT);
}

function mesNumLet(): array {
  return ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
}

function getFile(string $rutaDirVista, $data) {
  ob_start(); // abre el bufer para poder levantar en el el archivo que proximamente sera requerido.
  include_once 'Views/' . $rutaDirVista . '.php'; //requerimos el archivo
  $file = ob_get_clean(); // esencialmente ejecuta tanto ob_get_contents () como ob_end_clean (). lo que permite que el archivo requerido se encuentre en buffer, pueda ejecutar operaciones y variables , antes de ser guardado en la variable 
  //$file = ob_get_contents()(); // Volcar (enviar) el búfer de salida y deshabilitar el almacenamiento en el mismo;//
  //ob_end_clean(); //Limpiar (eliminar) el búfer de salida y deshabilitar el almacenamiento en el mismo
  return $file;
}

/* Envio de Email -------------------------------------------------------------- */

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function sendEmail($data, $bodyMail) {

  $empresa = $data['empresa'];
  $emailDestino = $data['email'];
  $nomb_user = $data['nombreUsuario'];
  $remitente = $empresa['email'];

  if ($empresa['smtp_status']) {

    require_once 'Librerias/vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = $empresa['host_mail'];
      $mail->SMTPAuth = true;
      $mail->Username = $empresa['serv_mail'];
      $mail->Password = $empresa['pass_mail'];
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom($empresa['serv_mail'], $empresa['nombre_comercial']);
      $mail->addAddress($emailDestino, $nomb_user);
      $mail->isHTML(true);
      $mail->Subject = $data['asunto'];
      $mail->Body = $bodyMail;

      return $mail->send();
    } catch (Exception $e) {
      return 'Mailer Error:' . $mail->ErrorInfo;
    }
  } else {
    $parametros = 'MIME-Version: 1.0\r\n';
    $parametros .= 'Content-type: text/html; charset=UTF-8\r\n';
    $parametros .= "From: {$empresa['nombre_comercial']} <{$remitente}>\r\n";
    $send = mail($emailDestino, $data['asunto'], $bodyMail, $parametros);
    return $send;
  }
}

// Function to get the user IP address
//function getUserIP() {
//  $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] :
//      ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] :
//      ( isset($_SERVER['HTTP_X_FORWARDED']) ? $_SERVER['HTTP_X_FORWARDED'] :
//      ( isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) ? $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] :
//      ( isset($_SERVER['HTTP_FORWARDED_FOR']) ? $_SERVER['HTTP_FORWARDED_FOR'] :
//      ( isset($_SERVER['HTTP_FORWARDED']) ? $_SERVER['HTTP_FORWARDED'] :
//      ( isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] :
//      'UNKNOWN'))))));
//
//  return $ip == '::1' ? '186.143.198.99' : $ip;
//}
// Function to get the user IP address
function getUserIP() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  return $ip == '::1' ? '186.143.198.99' : $ip;
}

function detectar_dispositivo() { //chatgpt get_device_type() {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $tablet_devices = array('ipad', 'android', 'tablet');
  foreach ($tablet_devices as $tablet_device) {
    if (stripos($user_agent, $tablet_device) !== false) {
      return 'tablet';
    }
  }
  $mobile_devices = array('iphone', 'ipod', 'android', 'mobile', 'blackberry', 'webos', 'windows phone');
  foreach ($mobile_devices as $mobile_device) {
    if (stripos($user_agent, $mobile_device) !== false) {
      return 'mobile';
    }
  }
  if (preg_match('/Linux|Windows|Macintosh|Ubuntu/', $user_agent)) {
    return 'desktop';
  }
  return 'Other';
}

function dispositivoOS() {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $sistemas_operativos = array(
    'Windows NT 10.0' => 'Windows 10',
    'Windows NT 6.3' => 'Windows 8.1',
    'Windows NT 6.2' => 'Windows 8',
    'Windows NT 6.1' => 'Windows 7',
    'Windows NT 6.0' => 'Windows Vista',
    'Windows NT 5.1' => 'Windows XP',
    'Windows NT 5.0' => 'Windows 2000',
    'Mac' => 'Mac OS',
    'Linux' => 'Linux',
    'Android' => 'Android',
    'iOS' => 'iOS'
  );

  foreach ($sistemas_operativos as $busqueda => $nombre) {
    if (strpos($user_agent, $busqueda) !== false) {
      return $nombre;
    }
  }
  return 'otro';
}

function getUserBrowser() {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  $browser = "Unknown";
  $browser_version = "";

  if (preg_match('/MSIE/i', $user_agent) && !preg_match('/Opera/i', $user_agent)) {
    $browser = 'Internet Explorer';
    $browser_version = preg_replace('/.*MSIE[ ]/', '', $user_agent);
  } elseif (preg_match('/Firefox/i', $user_agent)) {
    $browser = 'Mozilla Firefox';
    $browser_version = preg_replace('/.*Firefox[\/]/', '', $user_agent);
  } elseif (preg_match('/OPR/i', $user_agent)) {
    $browser = 'Opera';
    $browser_version = preg_replace('/.*OPR[\/]/', '', $user_agent);
  } elseif (preg_match('/Opera/i', $user_agent)) {
    $browser = 'Opera';
    $browser_version = preg_replace('/.*Opera[\/]/', '', $user_agent);
} elseif (preg_match('/Chrome/i', $user_agent)) {
    $browser = 'Chrome';
    $browser_version = preg_replace('/.*Chrome[\/]([\d.]+)/', '$1', $user_agent);
  } elseif (preg_match('/Safari/i', $user_agent)) {
    $browser = 'Apple Safari';
    $browser_version = preg_replace('/.*Version[\/]/', '', $user_agent);
  }

  return array('browser' => $browser, 'version' => $browser_version);
}

function isBot($userAgent) {
  $botUserAgents = [
    'Googlebot',
    'Bingbot',
    'Yahoo! Slurp',
    'DuckDuckBot',
    'YandexBot',
    'Baiduspider',
    'Sogou Spider'
  ];
  foreach ($botUserAgents as $botUserAgent) {
    if (strpos($userAgent, $botUserAgent) !== false) {
      return true;
    }
  }
  return false;
}

/* ----------------------------------------------------------------------------------------------- */

function array_sort_by(&$arrIni, $col, $order = SORT_ASC) {
//ordena un array asociativo por medio de una de sus elementos
  array_multisort(array_map(function ($row) use ($col) {
        return is_object($row) ? $row->$col : $row[$col];
      }, $arrIni), $order, $arrIni);
}

function path() {
  $arrUrl = array_filter(explode('/', $_GET['url'] ?? 'home/home'));
  return array_splice($arrUrl, 0, 2);
}

function num_par($num) {
//Comprobamos si num es un número par o no
  return (($num % 2) == 0) ?
      true : //Es un número par
      false; //Es un número impar
}

function obtenerPorcentajeDiferencia($cantidad, $total) {
  return round((((float) $cantidad * 100) / $total), 0);  // Regla de tres y luego Quitar los decimales
}

function calcularPorciento($valor1, $valor2) {
  $porcentaje = (($valor1 - $valor2) / $valor2) * 100;
  return number_format((float) $porcentaje, 0, SPD, SPM) . '%';
}

function clear_int($param) {
  /* Estamos buscando todos los caracteres que no sean números, 
   * por lo que negamos el conjunto de números del 0 al 9 ([^0-9]) como expresión regular y 
   * reemplazamos todas las apariciones con una cadena vacía (''). 
   * Como entrada y tercer parámetro, estamos usando nuestra cadena $s desde el principio. */
  $s = $param;
  $s = preg_replace('/[^0-9]/', '', $s);
  return intval($s);
}

function int_len($n) {//devuelve la cantidad de dígitos de un número entero,
  return strlen(strval($n));
}

function ofertaActiva($data_articulo) {
  $fecha_actual = new DateTime('now');
  $fecha_ini = $data_articulo['oferta_f_ini'] ? new DateTime($data_articulo['oferta_f_ini']) : null;
  $fecha_fin = $data_articulo['oferta_f_fin'] ? new DateTime($data_articulo['oferta_f_fin']) : (new DateTime())->add(new DateInterval('PT1H'));
  return $data_articulo['oferta'] > 0 && $fecha_ini <= $fecha_actual && $fecha_fin >= $fecha_actual;
}

/* carrito------------------------------------------------------------------------------------------- */

function cantCarrito(): int {
// cuenta la cantidad de productos en el carrito de compras almacenados en la variable $_SESSION['arrCarrito']
// utilizar la función nativa de PHP "array_reduce" para sumar las cantidades de los productos en el carrito en lugar de utilizar un bucle "foreach". 
  $cantCarrito = 0;
  if (isset($_SESSION['arrCarrito']) && count($_SESSION['arrCarrito']) > 0) {
    $cantCarrito = array_reduce($_SESSION['arrCarrito'], function ($carry, $producto) {
      return $carry + $producto['cantidad'];
    });
  }
  return $cantCarrito;
}

function html_producto_carrito(array $pro): string {
  $html_oferta = $pro['oferta'] != 0 ? "<span class='header-cart-item-info p-l-15 p-r-10'>{$pro['cantidad']} X " . formatMoney($pro['oferta']) . '</span>' : '';
  $html_precio = $pro['oferta'] !== 0 ? "<span class='header-cart-item-info del_precio_oferta stext-107 cl12'>" . formatMoney($pro['precio']) . '</span>' : "<span class='header-cart-item-info p-l-15 p-r-10'>{$pro['cantidad']} X " . formatMoney($pro['precio']) . '</span>';

  return "<!-- Item carrito -->
          <li class='header-cart-item flex-w flex-t m-b-15'>
            <div loading='lazy' class='header-cart-item-img prod-pic-rel' style='background: url({$pro['img']})' idpr='{$pro['idproducto']}' op = '1' onclick='fntdelItem(this)' >
              <img loading='lazy' class='prod-scale-img' src='{$pro['img']}' alt='{$pro['nombre']}'>
            </div>
            <div class='header-cart-item-txt p-t-6 '>
              <a href='" . base_url() . "tienda/producto/{$pro['idproducto']}/{$pro['ruta']}' 
                class='header-cart-item-name m-b-18 hov-cl1 trans-04 text-recort'>{$pro['nombre']}</a>
                <div class='row'>  
                {$html_oferta}
                {$html_precio}
                </div>
            </div>
          </li>";
}

function is_number(string $str): bool {
  $str = str_replace(',', '.', $str);
  $str = is_numeric($str) ? (int) $str : false;
  return is_integer($str) || is_float($str);
}

/* ================================================================================================== */

function set_notificacion($tipo, $id_tipo) {
  include_once __DIR__ . '/../Controllers/Notificacion.php';
  return(new Notificacion())->set_notificacion($tipo, $id_tipo);
}

/* HEADER Y FOOTER ADMIN =========================================================================== */

function headerAdmin($data = '') {
  include_once 'Views/Template/admin_header.php';
}

function footerAdmin($data = '') {
  include_once'Views/Template/admin_footer.php';
}

/* Tratamiento de imagenes y archivos ============================================================== */

function uploadImage(array $data_foto, string $namefoto): bool { // recibimos los paramtros array de datos de la foto y nombre de la foto recien generado
  $url_temp = $data_foto['tmp_name']; // capturamos la ruta temporal de la imagen
  $micarpeta = 'uploads/' . FILE_SISTEM_CLIENTE;
  !file_exists($micarpeta) ? mkdir($micarpeta, 0777, true) : '';
  if (!is_writable($micarpeta)) {
    chmod($micarpeta, 0777);
  }
  $destino = $micarpeta . $namefoto; //generamos la ruta de destino concatenando el nuevo nombre de la imagen
  return move_uploaded_file($url_temp, $destino); // ejecutampos una funcion de php donde el primer parametro indica la ubicacion de la imagen y el segundo indica el destino con el nuevo nombre
}

function deleteFile(string $name): int {
  $destination = 'uploads/' . FILE_SISTEM_CLIENTE . $name;
  return file_exists($destination) ? (unlink($destination) ? 1 : 0) : 1;
}

function thumbImage(string $dirArchivo, string $nombreFinal, int|float $ancho, int|float $alto, $calidad = 70): string {
  /* En este código optimizado se realizan las siguientes mejoras:
    Se usa el tipo de retorno "string" para la función "thumbImage".
    Se usa "match" en lugar de "switch" para la selección del tipo de imagen.
    Se agregan validaciones adicionales para verificar que la imagen sea válida antes de realizar la operación de copia y creación de la miniatura.
    Se usan operadores ternarios y asignaciones combinadas para reducir el número de líneas de código. */
  $pathinfo = pathinfo($nombreFinal);
  $nombre = $pathinfo['filename'];
  $extension = strtolower($pathinfo['extension']);
  $ancho = intval($ancho);
  $alto = intval($alto);
  $thumb = imagecreatetruecolor($ancho, $alto);

  if (!file_exists($dirArchivo)) {
    return '';
  }

  $nuevo = match ($extension) {
    'jpg', 'jpeg' => imagecreatefromjpeg($dirArchivo) ?? imagecreatefromstring(file_get_contents($dirArchivo)),
    'png' => imagecreatefrompng($dirArchivo),
    'gif' => imagecreatefromgif($dirArchivo),
    'webp' => imagecreatefromwebp($dirArchivo),
    default => null
  };

  if (!$nuevo) {
    return '';
  }

  $thumb_file_name = asisThumbImage($thumb, $nuevo, $ancho, $alto, $nombre, $extension);

  match ($extension) {
    'jpg', 'jpeg' => imagejpeg($thumb, $thumb_file_name, $calidad),
    'png' => imagepng($thumb, $thumb_file_name, (round($calidad / 10, 0))),
    'gif' => imagegif($thumb, $thumb_file_name),
    'webp' => imagewebp($thumb, $thumb_file_name, $calidad),
  };

  return $thumb_file_name;
}

function asisThumbImage($thumb, $nuevo, $ancho, $alto, $nombre, $extension) {
  $ancho_original = imagesx($nuevo);
  $alto_original = imagesy($nuevo);
  imagecopyresampled($thumb, $nuevo, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
  return './uploads/' . FILE_SISTEM_CLIENTE . 'thumb_' . $nombre . '.' . $extension;
}

function convertImageToWebP(string $imgArchivo, int $quality = 75): string {
  /*
    En esta versión, se utiliza la nueva sintaxis match para simplificar el código que antes estaba en una estructura switch.
    También se especifica el tipo de parámetros de entrada para evitar problemas de tipo en tiempo de ejecución
    y se utiliza una excepción InvalidArgumentException si se intenta procesar un tipo de imagen no soportado.
    Además, se utiliza la interpolación de cadenas para construir la ruta de destino de la imagen.
   */
  $nombre = pathinfo($imgArchivo, PATHINFO_FILENAME);
  $extension = pathinfo($imgArchivo, PATHINFO_EXTENSION);
  $image = match ($extension) {
    'jpg', 'jpeg' => imagecreatefromjpeg($imgArchivo),
    'png' => imagecreatefrompng($imgArchivo),
    'gif' => imagecreatefromgif($imgArchivo),
    'webp' => imagecreatefromwebp($imgArchivo),
    default => throw new InvalidArgumentException("Unsupported image type: $extension"),
  };
  $destination = "./uploads/" . FILE_SISTEM_CLIENTE . $nombre . ".webp";
  imagewebp($image, $destination, $quality);
  return $nombre . ".webp";
}

function convertImageToJPG(string $imgArchivo, string $nombfinal, int $quality = 75): string {
  /*
    En este código, se utiliza la estructura "match" introducida en PHP 8 para simplificar el switch utilizado en la función original.
    Además, se utiliza el tipo de retorno "string" en lugar de la palabra clave "mixed" para indicar que la función siempre devolverá una cadena de texto.
    También se eliminó la variable $nombre ya que no se utiliza más en el código.
   */
  $extension = pathinfo($imgArchivo, PATHINFO_EXTENSION);
  $image = match ($extension) {
    'jpg', 'jpeg' => imagecreatefromjpeg($imgArchivo),
    'png' => imagecreatefrompng($imgArchivo),
    'gif' => imagecreatefromgif($imgArchivo),
    'webp' => imagecreatefromwebp($imgArchivo),
    default => null,
  };
  if ($image !== null) {
    $destination = './uploads/' . FILE_SISTEM_CLIENTE . $nombfinal;
    imagejpeg($image, $destination, $quality);
    return $destination;
  }
  return '';
}

function img_alto_ancho($directorio_foto, $medida_alto_ancho) {

  if (file_exists($directorio_foto)) {
    $imagen = getimagesize($directorio_foto); //Sacamos la información
    $ancho = $imagen[0];
    $alto = $imagen[1];
    $proporcion = $alto / $ancho;
    if ($proporcion > 1) {
// Imagen vertical
      $alto_max = $medida_alto_ancho;
      $ancho_max = round($alto_max / $proporcion);
    } else if ($proporcion < 1) {
// Imagen horizontal
      $ancho_max = $medida_alto_ancho;
      $alto_max = round($ancho_max * $proporcion);
    } else {
// Imagen cuadrada
      $alto_max = $medida_alto_ancho;
      $ancho_max = $medida_alto_ancho;
    }
  } else {
    $ancho_max = null;
    $alto_max = null;
  }
  return array(intval($ancho_max), intval($alto_max));
}

/* --------------------------------------------------------------- */

function convertirXlsCSvEnArray($inputFileName) {
  include_once ('Librerias/vendor/autoload.php'); //include_once __DIR__ . '/.././Librerias/ 

  /**  Identify the type of $inputFileName  * */
  $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

  /**  Create a new Reader of the type that has been identified  * */
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType); //es igual a reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
//  if ($inputFileType == 'Csv') {
//    $reader->setEnclosure('');
//    $reader->setDelimiter('>');
//    $reader->setDelimiter(',');}

  /**  Load $inputFileName to a Spreadsheet Object  * */
  $spreadsheet = $reader->load($inputFileName);
  return $spreadsheet->getActiveSheet()->toArray();
}

function estadoFoto(string $nombre_foto, string $foto_actual, int|string $foto_remove): string {
  
  //recibe 3 parametros referente la fofografia y para determinar si es nueva, actualizada, borrada o sin cambios
  //Este código utiliza una estructura condicional más clara y simple para determinar el estado de la foto. 
  //También he eliminado algunos casos redundantes que ya estaban cubiertos en otras ramas de la estructura condicional.
  $estado_foto = '';

  if ($nombre_foto === '' && $foto_remove === 1) {
    $estado_foto = 'eliminada';
  } elseif ($nombre_foto === $foto_actual) {
    $estado_foto = 'sin_mov';
  } elseif ($foto_actual === 'portada_categoria.png') {
    $estado_foto = $nombre_foto === '' ? 'sin_mov_def' : 'nueva';
  } else {
    $estado_foto = 'actualizada';
  }
  return $estado_foto;
}

/* Generar CVS====================================================================================== */

function generarCSV(array $arreglo, string $ruta, string $delimitador, string $encapsulador): void {
  $file_handle = fopen($ruta, 'w');
  foreach ($arreglo as $linea) {
    fputcsv($file_handle, $linea, $delimitador, $encapsulador);
  }
  rewind($file_handle);
  fclose($file_handle);
}

/* para las notificaciones del lado server ========================================================= */

function diferencia_entre_fechas($fecha1, $fecha2) {
  $intervalo = ($fecha1)->diff(new DateTime($fecha2));
  return ($intervalo->m ? $intervalo->m . ' meses ' : '')
      . ($intervalo->d ? $intervalo->d . ' días ' : '')
      . (($intervalo->h || $intervalo->i) ? $intervalo->format('%h horas %i minutos') : '');
}

function ofertaArtiva($data_articulo) { // toma los datos de un producto y devuelve si una oferta esta activa o no
  $fecha_actual = strtotime(date('d-m-Y H:i:00', time()));
  $fecha_ini = $data_articulo['oferta_f_fin'] != null ? strtotime($data_articulo['oferta_f_ini'] . ' 00:00:00') : '';
  $fecha_fin = $data_articulo['oferta_f_fin'] != null ? strtotime($data_articulo['oferta_f_fin'] . ' 23:59:59') : strtotime('+1 hour', $fecha_actual);

  return ( $data_articulo['oferta'] > 0 &&
      $fecha_ini <= $fecha_actual &&
      $fecha_fin >= $fecha_actual) ? 1 : 0;
}
