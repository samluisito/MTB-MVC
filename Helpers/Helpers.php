<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Funciones de Ayuda Globales (Helpers)v
|--------------------------------------------------------------------------
|
| Este archivo contiene un conjunto de funciones de ayuda de propósito general
| utilizadas en todo el proyecto.
|
*/

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Devuelve la URL base completa del sistema.
 */
function base_url(): string
{
    return BASE_URL . '/';
}

// -----------------------------------------------------------------------------
// FUNCIONES DE PLANTILLAS Y VISTAS
// -----------------------------------------------------------------------------

/**
 * Incluye el encabezado de la plantilla de la tienda.
 */
function headerTienda(array $data = []): void
{
    if (!empty($data['header'])) {
        include_once __DIR__ . '/../Views/Template/tienda_header.php';
    }
}

/**
 * Incluye el pie de página de la plantilla de la tienda.
 */
function footerTienda(array $data = []): void
{
    if (!empty($data['footer'])) {
        include_once __DIR__ . '/../Views/Template/tienda_footer.php';
    }
}

/**
 * Incluye un archivo modal desde el directorio de plantillas.
 */
function getModal(string $nameModal, array $data = []): void
{
    include_once 'Views/Template/Modals/' . $nameModal . '.php';
}

function getDisplay(string $__DIR__, string $nameModal, array $data = []): void
{
    include_once $__DIR__ . '/' . $nameModal . '.php';
}

function getFile(string $rutaDirVista, array $data = []): string
{
  ob_start();
  include 'Views/' . $rutaDirVista . '.php';
  return ob_get_clean();
}

/**
 * Incluye el header del panel de administración.
 */
function headerAdmin(array $data = []): void
{
  include_once 'Views/Template/admin_header.php';
}

/**
 * Incluye el footer del panel de administración.
 */
function footerAdmin(array $data = []): void
{
  include_once 'Views/Template/admin_footer.php';
}

// -----------------------------------------------------------------------------
// FUNCIONES DE LIMPIEZA Y SANITIZACIÓN DE DATOS
// -----------------------------------------------------------------------------

/**
 * Limpia un valor para que contenga solo números enteros.
 */
function intClean(mixed $param): int
{
    return (int) filter_var($param, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Limpia un string de posibles inyecciones SQL y caracteres no deseados.
 */
function strClean(string $string): string
{
    $string = trim($string);
    $string = htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $patterns = [
        '/SELECT \* FROM/i', '/DELETE FROM/i', '/INSERT INTO/i', '/SELECT COUNT\(\*\) FROM/i',
        '/DROP TABLE/i', '/OR "1"="1"/i', '/OR \'1\'=\'1\'/i', '/OR ´1´=´1´/i',
        '/is NULL; --/i', '/is NULL;--/i', '/LIKE \'%/i', '/LIKE \"/i', '/LIKE ´/i',
        '/OR \'a\'=\'a\'/i', '/OR "a"="a"/i', '/OR ´a´=´a´/i', '/--/', '/\s\s+/',
        '/\^/', '/\[/', '/\]/', '/===/', '/==/'
    ];
    return preg_replace($patterns, '', $string);
}

/**
 * Limpia y convierte un string a un formato seguro para URLs (slug).
 */
function clear_cadena(string $cadena): string
{
    $cadena = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $cadena);
    $cadena = preg_replace('/[^a-z0-9_]+/', '-', $cadena);
    return trim($cadena, '-');
}

/**
 * Limpia una cadena y devuelve solo números enteros.
 */
function clear_int($param): int
{
  return (int) preg_replace('/[^0-9]/', '', $param);
}

// -----------------------------------------------------------------------------
// FUNCIONES DE GENERACIÓN Y ENCRIPTACIÓN
// -----------------------------------------------------------------------------

/**
 * Genera una contraseña o token aleatorio y criptográficamente seguro.
 */
function passGenerator(int $length = 10): string
{
    return substr(bin2hex(random_bytes((int)ceil($length / 2))), 0, $length);
}

/**
 * Genera un token de formato único.
 */
function token(): string
{
    return sprintf(
        '%s-%s-%s-%s',
        bin2hex(random_bytes(10)),
        bin2hex(random_bytes(10)),
        bin2hex(random_bytes(10)),
        bin2hex(random_bytes(10))
    );
}

/**
 * Encripta datos usando el método seguro AES-256-GCM.
 */
function encript(string $data): string
{
    $key = hash('sha256', KEY, true);
    $iv_len = openssl_cipher_iv_length('aes-256-gcm');
    $iv = openssl_random_pseudo_bytes($iv_len);
    $tag = '';
    $ciphertext = openssl_encrypt($data, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    return 'V2:' . base64_encode($iv . $tag . $ciphertext);
}

/**
 * Desencripta datos, manejando tanto el nuevo método seguro como el antiguo.
 */
function decript(string $data): string|false
{
    if (str_starts_with($data, 'V2:')) {
        $payload = substr($data, 3);
        $decoded = base64_decode($payload, true);
        if ($decoded === false) return false;

        $key = hash('sha256', KEY, true);
        $iv_len = openssl_cipher_iv_length('aes-256-gcm');
        $tag_len = 16;

        if (strlen($decoded) < $iv_len + $tag_len) return false;

        $iv = substr($decoded, 0, $iv_len);
        $tag = substr($decoded, $iv_len, $tag_len);
        $ciphertext = substr($decoded, $iv_len + $tag_len);

        return openssl_decrypt($ciphertext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
    return openssl_decrypt($data, METHODENCRIPT, KEY);
}

// -----------------------------------------------------------------------------
// FUNCIONES DE SESIÓN Y DATOS DE USUARIO
// -----------------------------------------------------------------------------

/**
 * Inicia la sesión de un usuario y carga sus datos y permisos.
 */
function sessionLogin(object $objModel, int $idpersona): bool
{
    $userData = $objModel->select(
        "SELECT p.idpersona, p.identificacion, p.nombres, p.apellidos, p.telefono, p.email_user, p.nit, p.nombrefiscal, p.direccionfiscal, p.rolid, p.datecreated, p.status, r.nombrerol, r.idrol FROM persona p INNER JOIN rol r ON p.rolid = r.idrol WHERE p.idpersona = ?",
        [$idpersona]
    );

    if (empty($userData) || !$userData['status']) {
        return false;
    }

    $permisos = $objModel->select_all(
        "SELECT p.idmodulo, p.rolid, p.moduloid, m.titulo, p.ver, p.crear, p.actualizar, p.eliminar FROM permisos p INNER JOIN modulo m ON p.moduloid = m.idmodulo WHERE p.rolid = ?",
        [$userData['rolid']]
    );

    $_SESSION['idUser'] = $idpersona;
    $_SESSION['login'] = true;
    $_SESSION['userData'] = $userData;
    $_SESSION['userData']['foto_user'] = 'images/Usuario-Icono.jpg';
    $_SESSION['userPermiso'] = [];

    foreach ($permisos as $permiso) {
        $idModulo = $permiso['moduloid'];
        $_SESSION['userPermiso'][$idModulo] = [
            'modulo' => $permiso['titulo'],
            'ver' => (bool)$permiso['ver'],
            'crear' => (bool)$permiso['crear'],
            'actualizar' => (bool)$permiso['actualizar'],
            'eliminar' => (bool)$permiso['eliminar'],
        ];
    }

    return true;
}

/**
 * Obtiene el precio actual del dólar desde la sesión.
 */
function getDolarHoy(): float
{
    return floatval($_SESSION['dolarhoy']['precio'] ?? 0);
}

// -----------------------------------------------------------------------------
// FUNCIONES DE DETECCIÓN DE ENTORNO
// -----------------------------------------------------------------------------

/**
 * Obtiene la dirección IP del usuario.
 */
function getUserIP(): string
{
    $ip = $_SERVER['HTTP_CLIENT_IP']
        ?? $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['REMOTE_ADDR']
        ?? 'UNKNOWN';
    return $ip === '::1' ? '127.0.0.1' : $ip;
}

/**
 * Detecta el tipo de dispositivo basado en el User Agent.
 */
function detectar_dispositivo(): string {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Cambiado el delimitador de / a ~ para evitar conflictos con rutas o nombres internos.
    $tabletPattern = '~(tablet|ipad|playbook|silk)|(android(?!.*mobile))~i';
    
    // Cambiado el delimitador de / a ~
    $mobilePattern = '~(mobi|ip(hone|od)|adroid|opera m(ob|in)i|windows (phone|ce)|blackberry|s(ymbian|eries60|amsung)|p(alm|rofile/midp|laystation portable)|nokia|fennec|htc[-_]?|up\.browser|up\.link|vodafone|philips|series80|alcatel|amoi|ktouch|nexian|samsung|sprint|zte|kddi|softbank|docomo|sanyo|sharp|tsm|minimo|audiovox|motorola|mmp|sagem|wap-)|(symbianos|palm os|ipod|blackberry|opera mini|windows ce|nokia|fennec|hiptop|kindle|p(alm|rofile/midp|ocket|o)|s(60|amsung|cr)|w(ebos|ap|es))~i';
    
    $desktopPattern = '/Linux|Windows|Macintosh|Ubuntu/'; // No necesita cambio ya que no contiene /

    if (preg_match($tabletPattern, $userAgent)) {
        return 'tablet';
    }
    if (preg_match($mobilePattern, $userAgent)) {
        return 'mobile';
    }
    if (preg_match($desktopPattern, $userAgent)) {
        return 'desktop';
    }

    return 'Other';
}

/**
 * Identifica el sistema operativo del usuario.
 */
function dispositivoOS(): string
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $osMap = [
        'Windows NT 10.0' => 'Windows 10',
        'Windows NT 6.3'  => 'Windows 8.1',
        'Windows NT 6.2'  => 'Windows 8',
        'Windows NT 6.1'  => 'Windows 7',
        'iPhone'          => 'iOS',
        'iPad'            => 'iOS',
        'Mac'             => 'macOS',
        'Android'         => 'Android',
        'Linux'           => 'Linux',
    ];

    foreach ($osMap as $pattern => $platform) {
        if (str_contains($userAgent, $pattern)) return $platform;
    }
    return 'Desconocido';
}

/**
 * Identifica el navegador del usuario.
 */
function getUserBrowser(): array
{
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $browser = "Desconocido";

    $browserMap = [
        '/msie/i'       => 'Internet Explorer',
        '/firefox/i'    => 'Firefox',
        '/safari/i'     => 'Safari',
        '/chrome/i'     => 'Chrome',
        '/edge/i'       => 'Edge',
        '/opera/i'      => 'Opera',
    ];

    foreach ($browserMap as $pattern => $name) {
        if (preg_match($pattern, $userAgent)) {
            $browser = $name;
            break;
        }
    }
    return ['browser' => $browser, 'version' => ''];
}

/**
 * Verifica si un User Agent pertenece a un bot de búsqueda conocido.
 */
function isBot(string $userAgent): bool
{
    $botUserAgents = ['Googlebot', 'Bingbot', 'Yahoo! Slurp', 'DuckDuckBot', 'Baiduspider', 'YandexBot'];
    foreach ($botUserAgents as $bot) {
        if (stripos($userAgent, $bot) !== false) return true;
    }
    return false;
}

// -----------------------------------------------------------------------------
// FUNCIONES DE UTILIDADES VARIAS
// -----------------------------------------------------------------------------

/**
 * Codifica un texto para ser usado en una URL de WhatsApp.
 */
function url_ws(string $texto): string
{
    $baseUrl = ($_SERVER['REQUEST_SCHEME'] ?? 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
    return urlencode(str_replace('@url_pag', $baseUrl, $texto));
}

/**
 * Redondea un número a la siguiente decena o centena superior.
 */
function redondear_decenas(float $numero): float
{
    $ceros = $numero > 999 ? 100 : ($numero > 99 ? 10 : 1);
    return ceil($numero / $ceros) * $ceros;
}

/**
 * Formatea un número como moneda según las constantes definidas.
 */
function formatMoney(float $cantidad): string
{
    return SMONEY . number_format($cantidad, 0, SPD, SPM);
}

/**
 * Rellena un número con ceros a la izquierda hasta una longitud de 5.
 */
function rellena(int $numero): string
{
    return str_pad((string) $numero, 5, '0', STR_PAD_LEFT);
}

/**
 * Devuelve un array con las abreviaturas de los meses.
 */
function mesNumLet(): array
{
    return ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
}

/**
 * Ordena un array de objetos o un array asociativo por una columna/clave específica.
 */
function array_sort_by(array &$arr, string $col, int $order = SORT_ASC): void
{
    array_multisort(array_column($arr, $col), $order, $arr);
}

/**
 * Devuelve los dos primeros segmentos de la URL.
 */
function path(): array
{
    return array_slice(explode('/', $_GET['url'] ?? 'home/home'), 0, 2);
}

/**
 * Verifica si un número es par.
 */
function num_par(int $num): bool
{
    return ($num % 2) === 0;
}

/**
 * Calcula la diferencia porcentual entre dos valores.
 */
function obtenerPorcentajeDiferencia(int|float $cantidad, int|float $total): int
{
  return (int) round((($cantidad * 100) / $total), 0);
}

/**
 * Calcula la diferencia porcentual entre dos valores.
 */
function calcularPorciento(float $valor1, float $valor2): string
{
    if ($valor2 == 0) return "0%";
    $porcentaje = (($valor1 - $valor2) / $valor2) * 100;
    return number_format($porcentaje, 0, SPD, SPM) . '%';
}

/**
 * Verifica si un string es un número válido (entero o flotante).
 */
function is_number(string $str): bool
{
    return is_numeric(str_replace(',', '.', $str));
}

/**
 * Verifica si una oferta de producto está activa basándose en su precio y rango de fechas.
 */
function ofertaActiva(array $data_articulo): bool
{
    if (empty($data_articulo['oferta']) || (float)$data_articulo['oferta'] <= 0) return false;

    try {
        $ahora = new DateTimeImmutable();
        $fecha_ini = !empty($data_articulo['oferta_f_ini']) ? new DateTimeImmutable($data_articulo['oferta_f_ini'] . ' 00:00:00') : $ahora->modify('-1 day');
        $fecha_fin = !empty($data_articulo['oferta_f_fin']) ? new DateTimeImmutable($data_articulo['oferta_f_fin'] . ' 23:59:59') : $ahora->modify('+1 year');
        return ($ahora >= $fecha_ini && $ahora <= $fecha_fin);
    } catch (Exception $e) {
        error_log('Error al parsear fecha en ofertaActiva: ' . $e->getMessage());
        return false;
    }
}

// -----------------------------------------------------------------------------
// FUNCIONES RELACIONADAS CON EL CARRITO DE COMPRAS
// -----------------------------------------------------------------------------

/**
 * Calcula el número total de artículos en el carrito de compras de la sesión.
 */
function cantCarrito(): int
{
    if (empty($_SESSION['arrCarrito'])) return 0;
    return array_reduce($_SESSION['arrCarrito'], fn($carry, $producto) => $carry + $producto['cantidad'], 0);
}

/**
 * Genera el HTML para un producto en el mini-carrito de la cabecera.
 */
function html_producto_carrito(array $pro): string
{
    $precioMostrado = $pro['oferta'] > 0 ? $pro['oferta'] : $pro['precio'];
    $precioTachado = $pro['oferta'] > 0 ? formatMoney($pro['precio']) : '';

    $htmlPrecio = "<span class='header-cart-item-info'>{$pro['cantidad']} x " . formatMoney($precioMostrado) . "</span>";
    if ($precioTachado) {
        $htmlPrecio .= "<span class='header-cart-item-info del_precio_oferta stext-107 cl12'>{$precioTachado}</span>";
    }

    return <<<HTML
    <li class='header-cart-item flex-w flex-t m-b-15'>
        <div loading='lazy' class='header-cart-item-img prod-pic-rel' style='background: url({$pro['img']})' data-idpr='{$pro['idproducto']}' onclick='fntdelItem(this)'>
            <img loading='lazy' class='prod-scale-img' src='{$pro['img']}' alt='{$pro['nombre']}'>
        </div>
        <div class='header-cart-item-txt p-t-6'>
            <a href='" . base_url() . "tienda/producto/{$pro['idproducto']}/{$pro['ruta']}' class='header-cart-item-name m-b-18 hov-cl1 trans-04 text-recort'>
                {$pro['nombre']}
            </a>
            <div class='row'>{$htmlPrecio}</div>
        </div>
    </li>
    HTML;
}

// -----------------------------------------------------------------------------
// FUNCIONES DE ENVÍO DE CORREO Y NOTIFICACIONES
// -----------------------------------------------------------------------------

/**
 * Envía un correo electrónico usando SMTP o la función mail() de PHP como fallback.
 */
function sendEmail(array $data, string $bodyMail): bool|string
{
    $empresa = $data['empresa'];
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
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($empresa['serv_mail'], $empresa['nombre_comercial']);
            $mail->addAddress($data['email'], $data['nombreUsuario']);
            $mail->isHTML(true);
            $mail->Subject = $data['asunto'];
            $mail->Body = $bodyMail;

            return $mail->send();
        } catch (Exception $e) {
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
            return 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$empresa['nombre_comercial']} <{$empresa['email']}>\r\n";
        return mail($data['email'], $data['asunto'], $bodyMail, $headers);
    }
}

function set_notificacion(string $tipo, int $id_tipo)
{
    include_once __DIR__ . '/../Controllers/Notificacion.php';
    return (new Notificacion())->set_notificacion($tipo, $id_tipo);
}

// -----------------------------------------------------------------------------
// FUNCIONES DE MANEJO DE IMÁGENES Y ARCHIVOS
// -----------------------------------------------------------------------------

function uploadImage(array $data_foto, string $namefoto): bool
{
    $url_temp = $data_foto['tmp_name'];
    $micarpeta = 'uploads/' . FILE_SISTEM_CLIENTE;
    if (!file_exists($micarpeta)) {
        mkdir($micarpeta, 0777, true);
    }
    $destino = $micarpeta . $namefoto;
    return move_uploaded_file($url_temp, $destino);
}

function deleteFile(string $name): bool
{
    $destination = 'uploads/' . FILE_SISTEM_CLIENTE . $name;
    return !file_exists($destination) || unlink($destination);
}

function thumbImage(string $dirArchivo, string $nombreFinal, int|float $ancho, int|float $alto, int $calidad = 70): string
{
    if (!file_exists($dirArchivo)) return '';

    $pathinfo = pathinfo($nombreFinal);
    $extension = strtolower($pathinfo['extension']);
    $thumb = imagecreatetruecolor((int)$ancho, (int)$alto);

    $sourceImage = match ($extension) {
        'jpg', 'jpeg' => imagecreatefromjpeg($dirArchivo),
        'png' => imagecreatefrompng($dirArchivo),
        'gif' => imagecreatefromgif($dirArchivo),
        'webp' => imagecreatefromwebp($dirArchivo),
        default => null
    };

    if (!$sourceImage) return '';

    $thumb_file_name = asisThumbImage($thumb, $sourceImage, (int)$ancho, (int)$alto, $pathinfo['filename'], $extension);

    match ($extension) {
        'jpg', 'jpeg' => imagejpeg($thumb, $thumb_file_name, $calidad),
        'png' => imagepng($thumb, $thumb_file_name, (int)round($calidad / 10)),
        'gif' => imagegif($thumb, $thumb_file_name),
        'webp' => imagewebp($thumb, $thumb_file_name, $calidad),
    };

    imagedestroy($sourceImage);
    imagedestroy($thumb);

    return $thumb_file_name;
}

function asisThumbImage(GdImage $thumb, GdImage $nuevo, int $ancho, int $alto, string $nombre, string $extension): string
{
  $ancho_original = imagesx($nuevo);
  $alto_original = imagesy($nuevo);
  imagecopyresampled($thumb, $nuevo, 0, 0, 0, 0, $ancho, $alto, $ancho_original, $alto_original);
  return './uploads/' . FILE_SISTEM_CLIENTE . 'thumb_' . $nombre . '.' . $extension;
}

function convertImageToWebP(string $imgArchivo, int $quality = 75): string
{
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
    imagedestroy($image);
    return $nombre . ".webp";
}

function convertImageToJPG(string $imgArchivo, string $nombfinal, int $quality = 75): string
{
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
        imagedestroy($image);
        return $destination;
    }
    return '';
}

function img_alto_ancho(string $directorio_foto, int $medida_alto_ancho): array
{
    if (!file_exists($directorio_foto)) return [0, 0];

    [$ancho, $alto] = getimagesize($directorio_foto);
    $proporcion = $alto / $ancho;

    if ($proporcion > 1) { // Imagen vertical
        $alto_max = $medida_alto_ancho;
        $ancho_max = round($alto_max / $proporcion);
    } else { // Imagen horizontal o cuadrada
        $ancho_max = $medida_alto_ancho;
        $alto_max = round($ancho_max * $proporcion);
    }
    return [(int)$ancho_max, (int)$alto_max];
}

function convertirXlsCSvEnArray(string $inputFileName): array
{
    require_once 'Librerias/vendor/autoload.php';
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    $spreadsheet = $reader->load($inputFileName);
    return $spreadsheet->getActiveSheet()->toArray();
}

function estadoFoto(string $nombre_foto, string $foto_actual, int|string $foto_remove): string
{
    if ($nombre_foto === '' && $foto_remove == 1) {
        return 'eliminada';
    }
    if ($nombre_foto === $foto_actual) {
        return 'sin_mov';
    }
    if ($foto_actual === 'portada_categoria.png') {
        return $nombre_foto === '' ? 'sin_mov_def' : 'nueva';
    }
    return 'actualizada';
}

function generarCSV(array $arreglo, string $ruta, string $delimitador = ',', string $encapsulador = '"'): void
{
    $file_handle = fopen($ruta, 'w');
    if ($file_handle) {
        foreach ($arreglo as $linea) {
            fputcsv($file_handle, $linea, $delimitador, $encapsulador);
        }
        fclose($file_handle);
    } else {
        error_log("Error al abrir el archivo CSV: " . $ruta);
    }
}

function diferencia_entre_fechas(DateTimeInterface $fecha1, DateTimeInterface $fecha2): string
{
    $intervalo = $fecha1->diff($fecha2);
    $partes = [];
    if ($intervalo->m > 0) $partes[] = $intervalo->m . ' meses';
    if ($intervalo->d > 0) $partes[] = $intervalo->d . ' días';
    if ($intervalo->h > 0) $partes[] = $intervalo->h . ' horas';
    if ($intervalo->i > 0) $partes[] = $intervalo->i . ' minutos';
    return implode(' ', $partes) ?: 'ahora';
}

// -----------------------------------------------------------------------------
// FUNCIONES DE PAYPAL (requieren constantes no definidas en este archivo)
// -----------------------------------------------------------------------------

function getTokenPayPal(): ?string
{
    if (!defined('URL_API_PAYPAL') || !defined('CLIENTE_PAYPAL') || !defined('SECRET')) {
        error_log("Constantes de PayPal no definidas.");
        return null;
    }
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => URL_API_PAYPAL . '/v1/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERPWD => CLIENTE_PAYPAL . ':' . SECRET,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
    ]);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
        error_log('getTokenCURL Error #: ' . curl_error($curl));
        return null;
    }
    curl_close($curl);
    $decoded = json_decode($result);
    return $decoded->access_token ?? null;
}

function curlConection(string $ruta, string $method = 'GET', string $contentType = 'application/x-www-form-urlencoded', ?string $token = null)
{
    $arrHeader = ['Content-Type: ' . $contentType];
    if ($token !== null) {
        $arrHeader[] = 'Authorization: Bearer ' . $token;
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $ruta,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_SSL_VERIFYPEER => false, // ¡ADVERTENCIA: Inseguro en producción!
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $arrHeader,
    ]);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        error_log('CURL Error #: ' . $err);
        return 'CURL Error #: ' . $err;
    }
    return json_decode($result);
}





/**
 * Devuelve la cantidad de dígitos de un número entero.
 *
 * @param int $n Número entero.
 * @return int Cantidad de dígitos.
 */
function int_len(int $n): int {
  return strlen(strval($n));
}

/**
 * Verifica si una oferta está activa (versión mejorada).
 *
 * @param array $data_articulo Datos del artículo.
 * @return bool True si la oferta está activa, false en caso contrario.
 */
function ofertaArtiva(array $data_articulo): bool {
  $fecha_actual = new DateTime('now');
  $fecha_ini = $data_articulo['oferta_f_ini'] ? new DateTime($data_articulo['oferta_f_ini']) : null;
  $fecha_fin = $data_articulo['oferta_f_fin'] ? new DateTime($data_articulo['oferta_f_fin']) : (new DateTime())->add(new DateInterval('PT1H')); // Sumar una hora si no hay fecha fin

  return $data_articulo['oferta'] > 0 && $fecha_ini <= $fecha_actual && $fecha_fin >= $fecha_actual;
}

/**
hace una instancia de la clase Error.php y genera una vista Error XXX
 */
function showError($mensaje) {
    // Incluye el controlador y la vista de error
    require_once __DIR__ . '/../Controllers/Error.php'; // Ajusta la ruta si es necesario
    $errorController = new Error();
    $errorController->index($mensaje); // Llama a un método en el controlador de error para mostrar la vista
}

/**
 * Convierte una imagen a otro formato (JPG o WebP).
 *
 * @param string $sourcePath Ruta del archivo de imagen de origen.
 * @param string $destinationPath Ruta del archivo de imagen de destino.
 * @param string $format Formato de la imagen de destino ('jpg' o 'webp').
 * @param int $quality Calidad de la imagen (0-100).
 * @return bool True si la conversión fue exitosa, false en caso contrario.
 */
function convertImage(string $sourcePath, string $destinationPath, string $format = 'webp', int $quality = 80): bool {
  if (!file_exists($sourcePath)) {
    return false;
  }

  $imageInfo = getimagesize($sourcePath);
  if (!$imageInfo) {
    return false;
  }

  $image = match ($imageInfo[2]) {
    IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
    IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
    IMAGETYPE_GIF => imagecreatefromgif($sourcePath),
    default => null, // Retornar null si el tipo no es soportado
  };

  if ($image === null) { // Verificar si se creó la imagen correctamente
    return false;
  }

  $success = match ($format) {
    'jpg', 'jpeg' => imagejpeg($image, $destinationPath, $quality),
    'webp' => imagewebp($image, $destinationPath, $quality),
    default => false, // Manejar formatos no soportados
  };

  imagedestroy($image);
  return $success;
}
