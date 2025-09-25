<?php

declare(strict_types=1);

class Productos extends Controllers {

  private $idModul = 5;

  public function __construct() {

    if (empty($_SESSION['login']) &&
        !strpos($_SERVER['REQUEST_URI'], 'getProductosCategoriaCSV')) {
      require "Login.php";
      $login = new Login();
      $login->Login();
      exit();
    }
    parent::__construct();
  }

  public function Productos() {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {

      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Productos';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */

      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array(
        "vadmin/libs/choices.js/css/choices.min.css",
        "vadmin/libs/glightbox/css/glightbox.min.css",
        "plugins/datatables/css/datatables.min.css",
        "plugins/cropper/css/cropper.min.css",
      );
      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "vadmin/libs/choices.js/js/choices.min.js",
        "vadmin/libs/glightbox/js/glightbox.min.js",
        "plugins/datatables/js/datatables.min.js",
        "plugins/cropper/js/cropper.min.js",
        "plugins/tinymce/tinymce.min.js",
        "plugins/JsBarcode/JsBarcode.all.min.js",
        "js/functions_productos.js");

      $this->views->getView("Productos", $data);
    } else {
      // header('location:' . base_url() . 'dashboard');exit();
    }
  }

  public function Proveedores($params) {
    $data["modulo"] = $this->idModul;
    if ($_SESSION['userPermiso'][$data["modulo"]]['ver'] == 1) {

      $empresa = $_SESSION['info_empresa'];
      $data["empresa"] = $empresa;

      $data['page_name'] = 'Productos';
      $data['page_title'] = $data['page_name'];
      $data['logo_desktop'] = $empresa['url_logoMenu'];
      $data['shortcut_icon'] = $empresa['url_shortcutIcon'];
      /*       * ******************************************* */
      include __DIR__ . '/../Controllers/Notificacion.php';
      $notificacion = new Notificacion();
      $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu(); //$_SESSION['userData'];
      /*       * ******************************************* */

      // las funciones de la pagina van de ultimo 
      $data["page_css"] = array(
        "vadmin/libs/choices.js/css/choices.min.css",
        "plugins/datatables/css/datatables.min.css",
        "plugins/cropper/css/cropper.min.css",
      );
      $data["page_functions_js"] = array(
        "plugins/jquery/jquery-3.6.0.min.js",
        "vadmin/libs/choices.js/js/choices.min.js",
        "plugins/datatables/js/datatables.min.js",
        "plugins/cropper/js/cropper.min.js",
        "js/functions_productos_proveedores.js");

      $this->views->getView("Proveedores", $data);
    } else {
      // header('location:' . base_url() . 'dashboard');exit();
    }
  }

  /* --------------------------------------------------------------------------------------------------------------------------------------------------------- */

  //DEVUELVE UN ARRAY CON LOS DATOS DE PRODUCTO Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getProductos() {
    $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
    $premin = isset($_GET['premin']) ? (intval($_GET['premin']) > 0 ? $_GET['premin'] : 0) : 0;
    $premax = isset($_GET['premax']) ? (intval($_GET['premax']) > 0 ? $_GET['premax'] : null) : null;
    $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 't';

    $arrData = $this->model->selectProductos($categoria, $premin, $premax, $estado); //consultamos la tabla y traemos todos los registros 
    //$permiso = $_SESSION['userPermiso'][$this->idModul]; //reemplaza los valores 0 y 1 por inactivo - Activo 
    foreach ($arrData as $i => $item) {
      /* seleccion de imagen -------------------------------- */
      $recuest_img = $this->model->imgProdructo($item['idproducto']); //consultamos la tabla y traemos todos los registros 

      if (isset($recuest_img['img'])) {
        $img_prod = DIR_IMAGEN . 'thumb_4_' . $recuest_img["img"];
        $img_url = DIR_IMAGEN . $recuest_img["img"];
      } else {
        $img_prod = DIR_MEDIA . 'images/producto_sin_foto.png';
        $img_url = DIR_MEDIA . 'images/producto_sin_foto.png';
      }

      $arrData[$i]['img_prod'] = '<img class="minlistprod_img " src=" ' . $img_prod . ' "> ';
      $arrData[$i]['img_url'] = $img_url;

      /* Formato de Precio -------------------------------- */
      $arrData[$i]['ruta'] = base_url() . "tienda/producto/{$item['ruta']}";
      $precio_dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $arrData[$i]['precio'] = formatMoney(redondear_decenas($item['precio'] * $precio_dolar));
      /* Botones de accion -------------------------------- */
      $id = $item['idproducto'];
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado' type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
// if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->productoEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
// }
      $arrData[$i]['options'] = $opciones . "</div>";
    }

    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  public function getProductosCategoriaCSV(): void {
    $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
    $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 'a';

    $arrCSV[0] = array(
      "# Obligatorio | Identificador de contenido único del artículo. Se recomienda usar el SKU del artículo. Cada identificador de contenido debe aparecer una sola vez en el catálogo. Para publicar anuncios dinámicos; este identificador debe coincidir exactamente con el identificador de contenido del mismo artículo en el código del píxel de Facebook. Límite de caracteres: 100.",
      "# Obligatorio | Título específico y relevante para el artículo. Consulta las especificaciones sobre títulos : https://www.facebook.com/business/help/2104231189874655. Límite de caracteres: 150.",
      "# Obligatorio | A short and relevant description of the item. Include specific or unique product features like material or color. Use plain text and don't enter text in all capital letters. See description specifications: https://www.facebook.com/business/help/2302017289821154 Character limit: 9999",
      "# Obligatorio | The current availability of the item. | Supported values: in stock; out of stock | Supported values: in stock; available for order; preorder; out of stock; discontinued",
      "# Obligatorio | The condition of the item. Enter one of the following: new; refurbished; used | Supported values: new; refurbished; used",
      "# Obligatorio | Precio del artículo. El formato del precio debe ser un número seguido del código de divisa de 3 letras (norma ISO 4217). Usa un punto (" . ") como separador decimal; no uses coma.",
      "# Obligatorio | URL de la página del producto específica donde las personas pueden comprar el artículo.",
      "# Obligatorio | URL de la imagen principal de tu artículo. Las imágenes deben estar en un formato compatible (JPG/GIF/PNG) y su tamaño debe ser de 500 x 500 píxeles como mínimo.",
      "# Opcional | URL de la images separadas por coma. Las imágenes deben estar en un formato compatible (JPG/GIF/PNG) y su tamaño debe ser de 500 x 500 píxeles como mínimo.",
      "# Obligatorio | Marca del artículo. En su lugar; también puedes ingresar un número único de pieza del fabricante (MPN) o un número mundial de artículo comercial (GTIN). Un GTIN puede ser uno de los números siguientes: UPC; EAN; JAN o ISBN. Límite de caracteres: 100.",
      "# Opcional | Categoría de productos de Google para el artículo. Obtén más información sobre las categorías de productos: https://www.facebook.com/business/help/526764014610932 .",
      "# Opcional | Categoría de productos de Facebook para el artículo. Obtén más información sobre las categorías de productos: https://www.facebook.com/business/help/526764014610932 .",
      "# Opcional | The quantity of this item you have to sell on Facebook and Instagram with checkout. Must be 1 or higher or the item won't be buyable",
      "# Opcional | Precio con descuento del artículo si está en oferta. El formato del precio debe ser un número seguido del código de divisa de 3 letras (estándar ISO 4217). Usa un punto (.) como separador decimal; no uses coma. Es obligatorio indicar el precio de oferta si quieres usar texto superpuesto para mostrar precios con descuento.",
      "# Opcional | Intervalo del período de oferta; incluidas la fecha y la hora o zona horaria del inicio y la finalización de la oferta. Si no ingresas las fechas; los artículos con el campo 'sale_price' permanecerán en oferta hasta que elimines el precio de oferta. Usa este formato: YYYY-MM-DDT23:59+00:00/YYYY-MM-DDT23:59+00:00. Ingresa la fecha de inicio de la siguiente manera: YYYY-MM-DD. Escribe una 'T'. A continuación; ingresa la hora de inicio en formato de 24 horas (00:00 a 23:59) seguida de la zona horaria UTC (-12:00 a +14:00). Escribe una barra (' / ') y repite el mismo formato para la fecha y hora de finalización. En la siguiente fila de ejemplo se usa la zona horaria del Pacífico (-08:00).",
      "# Opcional | Use this field to create variants of the same item. Enter the same group ID for all variants within a group. Learn more about variants: https://www.facebook.com/business/help/2256580051262113 Character limit: 100.",
      "# Opcional | Género de una persona a la que se dirige el artículo. | Supported values: female; male; unisex",
      "# Opcional | Color del artículo. Usa una o más palabras para describir el color. No uses un código hexadecimal. Límite de caracteres: 200.",
      "# Opcional | Tamaño o talle del artículo escrito como una palabra; abreviatura o número. Por ejemplo: pequeño; XL o 12. Límite de caracteres: 200.",
      "# Opcional | Grupo de edad al que se dirige el artículo. | Supported values: adult; all ages; infant; kids; newborn; teen; toddler",
      "# Opcional | The material the item is made from; such as cotton; denim or leather. Character limit: 200.",
      "# Opcional | Estampado o impresión gráfica del artículo. Límite de caracteres: 100.",
      "# Opcional | Detalles de envío del artículo; con el siguiente formato: 'País:Región:Servicio:Precio'. Incluye el código de divisa ISO 3 de 3 l4217tras en el precio. Para usar el texto superpuesto 'Envío gratuito' en tus anuncios; ingresa un precio de '0.0'. Usa ';' para separar varios detalles de envío para distintas regiones o países. Solo las personas de una región o país especificado verán los detalles de envío correspondientes a su ubicación. Puedes omitir la región (conserva ambos signos '::') si los detalles de envío son los mismos para todo el país.",
      "# Opcional | Peso del envío del artículo. Incluye la unidad de medida (lb/oz/g/kg).",
      "# Opcional | Describe el estilo del artículo.",
      "# Opcional | Controla si el artículo está activo o archivado en el catálogo. En tus anuncios, tiendas u otros canales, las personas solo pueden ver los artículos activos. Valores admitidos: active (activo) o archived (archivado)."
    );
    $arrCSV[1] = array(
      'id', //# Obligatorio | Identificador de contenido único del artículo. Se recomienda usar el SKU del artículo. Cada identificador de contenido debe aparecer una sola vez en el catálogo. Para publicar anuncios dinámicos; este identificador debe coincidir exactamente con el identificador de contenido del mismo artículo en el código del píxel de Facebook. Límite de caracteres: 100.
      'title', //# Obligatorio | Título específico y relevante para el artículo. Consulta las especificaciones sobre títulos : https://www.facebook.com/business/help/2104231189874655. Límite de caracteres: 150.
      'description', //# Obligatorio | A short and relevant description of the item. Include specific or unique product features like material or color. Use plain text and don't enter text in all capital letters. See description specifications: https://www.facebook.com/business/help/2302017289821154 Character limit: 9999
      'availability', //# Obligatorio | Disponibilidad actual del artículo. Valores admitidos: in stock (disponible), available for order (disponible para pedido) o out of stock (agotado). Los artículos agotados se muestran como "agotados" en tu tienda y no se muestran en los anuncios.
      'condition', //# Obligatorio |Estado del producto. Valores admitidos: new (nuevo), refurbished (restaurado) o used (usado).
      'price', //# Obligatorio | Precio del artículo. El formato del precio debe ser un número seguido del código de divisa de 3 letras (norma ISO 4217). Usa un punto (".") como separador decimal; no uses coma.
      'link', //# Obligatorio | URL de la página del producto específica donde las personas pueden comprar el artículo.
      'image_link', //# Obligatorio | URL de la imagen principal de tu artículo. Las imágenes deben estar en un formato compatible (JPG/GIF/PNG) y su tamaño debe ser de 500 x 500 píxeles como mínimo.
      'additional_image_link', //# Opcional | Enlaces de un máximo de 20 imágenes adicionales del artículo, separados por comas. Sigue las mismas especificaciones de imágenes que en image_link.Ejemplo: http://www.jaspersmarket.com/productos/camiseta2.jpg, http://www.jaspersmarket.com/productos/camiseta3.jpg
      'brand', //# Obligatorio | Marca del artículo. En su lugar; también puedes ingresar un número único de pieza del fabricante (MPN) o un número mundial de artículo comercial (GTIN). Un GTIN puede ser uno de los números siguientes: UPC; EAN; JAN o ISBN. Límite de caracteres: 100.
      'google_product_category', //# Opcional | Categoría de productos de Google para el artículo. Obtén más información sobre las categorías de productos: https://www.facebook.com/business/help/526764014610932.
      'fb_product_category', //# Opcional | Categoría de productos de Facebook para el artículo. Obtén más información sobre las categorías de productos: https://www.facebook.com/business/help/526764014610932.
      'quantity_to_sell_on_facebook', //# Opcional | La cantidad de este artículo que venderas en Facebook e Instagram con el pago. Debe ser 1 o superior o el artículo no se podrá comprar
      'sale_price', //# Opcional | Precio con descuento del artículo si está en oferta. El formato del precio debe ser un número seguido del código de divisa de 3 letras (estándar ISO 4217). Usa un punto (.) como separador decimal; no uses coma. Es obligatorio indicar el precio de oferta si quieres usar texto superpuesto para mostrar precios con descuento.
      'sale_price_effective_date', //# Opcional | Intervalo del período de oferta; incluidas la fecha y la hora o zona horaria del inicio y la finalización de la oferta. Si no ingresas las fechas; los artículos con el campo "sale_price" permanecerán en oferta hasta que elimines el precio de oferta. Usa este formato: YYYY-MM-DDT23:59+00:00/YYYY-MM-DDT23:59+00:00. Ingresa la fecha de inicio de la siguiente manera: YYYY-MM-DD. Escribe una "T". A continuación; ingresa la hora de inicio en formato de 24 horas (00:00 a 23:59) seguida de la zona horaria UTC (-12:00 a +14:00). Escribe una barra ("/") y repite el mismo formato para la fecha y hora de finalización. En la siguiente fila de ejemplo se usa la zona horaria del Pacífico (-08:00).
      'item_group_id', //# Opcional | Utilice este campo para crear variantes del mismo artículo. Introduzca el mismo ID de grupo para todas las variantes dentro de un grupo. Obtenga más información sobre las variantes: https://www.facebook.com/business/help/2256580051262113 Límite de caracteres: 100.
      'gender', //# Opcional | Género de una persona a la que se dirige el artículo. | Supported values: female; male; unisex
      'color', //# Opcional | Color del artículo. Usa una o más palabras para describir el color. No uses un código hexadecimal. Límite de caracteres: 200.
      'size', //# Opcional | Tamaño o talle del artículo escrito como una palabra; abreviatura o número. Por ejemplo: pequeño; XL o 12. Límite de caracteres: 200.
      'age_group', //# Opcional | Grupo de edad al que se dirige el artículo. | Supported values: adult; all ages; infant; kids; newborn; teen; toddler
      'material', //# Opcional | El material del que está hecho el artículo; como el algodón; mezclilla o cuero. Límite de caracteres: 200
      'pattern', //# Opcional | Estampado o impresión gráfica del artículo. Límite de caracteres: 100.
      'shipping', //# Opcional | Detalles de envío del artículo; con el siguiente formato: "País:Región:Servicio:Precio". Incluye el código de divisa ISO 3 de 3 l4217tras en el precio. Para usar el texto superpuesto "Envío gratuito" en tus anuncios; ingresa un precio de "0.0". Usa ";" para separar varios detalles de envío para distintas regiones o países. Solo las personas de una región o país especificado verán los detalles de envío correspondientes a su ubicación. Puedes omitir la región (conserva ambos signos "::") si los detalles de envío son los mismos para todo el país.
      'shipping_weight', //# Opcional | Peso del envío del artículo. Incluye la unidad de medida (lb/oz/g/kg).
      'style', //# Opcional | Describe el estilo del artículo.
      'status', //# Opcional | Controla si el artículo está activo o archivado en el catálogo. En tus anuncios, tiendas u otros canales, las personas solo pueden ver los artículos activos. Valores admitidos: active (activo) o archived (archivado).
    );

    $arrData = $this->model->selectProductosPorCategoria($categoria, $estado); //consultamos la tabla y traemos todos los registros 
    require('Librerias/vendor/soundasleep/html2text/html2text.php');
    foreach ($arrData as $i => $item) {

      //establece el estado de disponibilidad de stock
      //in stock; available for order; preorder; out of stock; discontinued
      $availability = match ($item['stock_status']) {
        'instock' => 'in stock',
        'onbackorder' => 'available for order',
        'outofstock' => 'discontinued',
        default => 'in stock',
      };

      //crea una secuencia de links de las imagenes



      $recuest_img = $this->model->imgProdructoCSV($item['idproducto']); //consultamos la tabla y traemos todos los registros 
      $image_link = isset($recuest_img[0]['id']) ? DIR_IMAGEN . 'thumb_1_' . $recuest_img[0]["img"] : DIR_MEDIA . 'images/producto_sin_foto.png';
      $img1 = isset($recuest_img[1]['id']) ? DIR_IMAGEN . 'thumb_1_' . $recuest_img[1]["img"] . ',' : '';
      $img2 = isset($recuest_img[2]['id']) ? DIR_IMAGEN . 'thumb_1_' . $recuest_img[2]["img"] . ',' : '';
      $img3 = isset($recuest_img[3]['id']) ? DIR_IMAGEN . 'thumb_1_' . $recuest_img[3]["img"] : '';
      $additional_image_link = $img1 . $img2 . $img3;

      //crear la fechas de oferta, si el valor de oferta es mayor a 0
      $fechas = '';
      $oferta_activa = ofertaArtiva($item); //analiza si oferta cumple los requisitos para estar activa, devuelve 1 si lo esta y devuelve 0 si no lo esta
      if (ofertaArtiva($item)) {
        $fecha_ini = date_format(date_create("{$item['oferta_f_ini']} 00:00"), "Y-m-d\TH:iT:00");
        $fecha_fin = $item['oferta_f_fin'] ? date_format(date_create("{$item['oferta_f_fin']} 23:59"), "Y-m-d\TH:iT:00") : '';
        $fechas = $fecha_fin ? "$fecha_ini/$fecha_fin" : $fecha_ini;
      }

      $item['gender'] = match ($item['gender']) {
        'F' => 'Female',
        'M' => 'Male',
        default => 'Unisex',
      };
      $item['age_group'] = match ($item['age_group']) {
        'A' => 'adult',
        'AD' => 'teen',
        'N' => 'kids',
        'RN' => 'newborn',
        default => 'all ages',
      };

      $item['status'] = $item['status'] ? 'active' : archived; //active (activo) o archived (archivado)
//Variables para establecer el precio
      $precio_dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
      $moneda = $_SESSION['base']['region_abrev'] == 'VE' ? ' USD' : ' ARS';

//array de datos para el CVS
      $arrCSV[$i + 2] = array(
        $id = $item['idproducto'],
        $title = $item['nombre'],
        $description = convert_html_to_text(html_entity_decode($item['descripcion'])), // la funcion convert_html_to_text pertenece a html2text
        $availability,
        $condition = 'new', //'new'; 'refurbished'; 'used'
        $price = number_format((float) redondear_decenas($item['precio'] * $precio_dolar, 2, SPD, SPM)) . $moneda,
        $link = base_url() . "tienda/producto/{$item['idproducto']}/{$item['ruta']}",
        $image_link,
        $additional_image_link,
        $brand = ($item['marca'] === '' || $item['marca'] == 'GENERICO' || $item['marca'] == 'Generico' ) ? 'MiTiendaBit' : $item['marca'],
        $google_product_category = intval($item['cat_google_id']),
        $fb_product_category = intval($item['cat_facebook_id']),
        $quantity_to_sell_on_facebook = '',
        $sale_price = $oferta_activa ? number_format((float) redondear_decenas($item['oferta'] * $precio_dolar, 2, SPD, SPM)) . $moneda : '',
        $sale_price_effective_date = $oferta_activa ? $fechas : '',
        $item_group_id = '',
        $gender = $item['gender'],
        $color = $item['color'],
        $size = $item['size'],
        $age_group = $item['age_group'],
        $material = $item['material'],
        $pattern = $item['pattern'],
        $shipping = '',
        $shipping_weight = '',
        $style = $item['style'],
        $status = 'active', //active (activo) o archived (archivado)
      );
    }
    // $ruta = DIR_IMAGEN.'/catalog_products.csv';
    $ruta_base = __DIR__ . '/../uploads/';
    $ruta = $ruta_base . FILE_SISTEM_CLIENTE . 'catalog_products.csv';
    //$ruta = DIR_IMAGEN. 'catalog_products.csv';
    generarCSV($arrCSV, $ruta, $delimitador = ',', $encapsulador = '"');
// echo "Your file is being checked. <br>";
// Use la función basename() para devolver el nombre base del archivo
    $file_name = basename($ruta);
    $info = pathinfo($file_name);

// Checking if the file is a CSV file or not
    if ($info["extension"] == "csv") {
      /* Informar al navegador que el tipo de archivo del archivo en cuestión es un tipo MIME (tipo de extensión de correo de Internet multipropósito).
        Por lo tanto, no es necesario reproducir el archivo sino descargarlo directamente en la máquina del cliente. */
//      header("Content-Description: File Transfer");
      header('Content-Type: application/csv');
      header("Content-Disposition: attachment; filename=\"" . $file_name . "\"");
      header('Pragma: no-cache');

// echo "File downloaded successfully";
      readfile($ruta);
    } else {
      //echo "Sorry, that's not a CSV file";
    }
    exit();

    //$arrResponse = array('status' => true, 'url' => DIR_IMAGEN . 'catalog_products.csv', 'msg' => 'Atencion el Producto Ya Existe, segun el codigo ingresado');
// exit(json_encode($arrCSV, JSON_UNESCAPED_UNICODE));
  }

  //CREAR - ACTUALIZAR PRODUCTO---------------------------------------
  public function setProducto() {
//    dep($_POST);
//    exit();
    empty($_POST) ? exit(json_encode(array("status" => false, "msg" => "Datos incompletos o con valor cero"), JSON_UNESCAPED_UNICODE)) : ''; //Validamos que no este vacio el post
    if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || //empty($_POST['txtCodigo']) ||
        empty($_POST['precioUSD']) || empty($_POST['listCategoria'])) {
      $n = empty($_POST['txtNombre']) ? 'Nombre,' : '';
      $d = empty($_POST['txtDescripcion']) ? 'Descripcion,' : '';
      $p = empty($_POST['precioUSD']) ? 'Precio,' : '';
      $c = empty($_POST['listCategoria']) ? 'categoria,' : '';
      $arrResponse = array("status" => false, "msg" => "Datos incompletos: $n $d $p $c");
    } else {
      //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
      //los datos enviados los almacenamos en variables

      $intIdProducto = intval($_POST['idProducto']);
      $strNombre = strClean(clear_cadena(trim($_POST['txtNombre'])));
      $strDescripcion = strClean($_POST['txtDescripcion']);
      $strDetalle = strClean($_POST['txtDetalle']);
      $strMarca = strClean($_POST['txtMarca']);
      $strEtiquetas = strClean($_POST['txtEtiquetas']);
      $strCodigo = $_POST['txtCodigo'] == '' ? 0 : (int) strClean($_POST['txtCodigo']);
      $floCosto = floatval($_POST['costoUSD']);
      $floPrecio = floatval($_POST['precioUSD']);

      $floOferta = floatval($_POST['ofertaDolar']);
      $strOferta_f_ini = $_POST['oferta_f_ini'] != '' ? date_format(date_create($_POST['oferta_f_ini']), "Y-m-d") : null;
      $strOferta_f_fin = $_POST['oferta_f_fin'] != '' ? date_format(date_create($_POST['oferta_f_fin']), "Y-m-d") : null;

      $intStock = intval(strClean($_POST['txtStock']));
      $strStock_status = strClean($_POST['stock_status']);

      $intCategoriaId = intval(strClean($_POST['listCategoria']));
      $intCatFbId = intval(strClean($_POST['listCatFB']));
      $intCatGgId = intval(strClean($_POST['listCatGoogle']));
      $intProveedorId = intval(strClean($_POST['listProveedor']));

      $strGrupoEtario = strClean($_POST['listGrupoEtario']);
      $strGenero = strClean($_POST['listGenero']);
      $strTalla = strClean($_POST['txtTalla']);
      $strColor = strClean($_POST['txtColor']);
      $strMaterial = strClean($_POST['txtMaterial']);
      $strEstilo = strClean($_POST['txtEstilo']);
      $strEstampado = strClean($_POST['txtEstampado']);

      $intStatus = intval(strClean($_POST['listStatus']));
      $ruta = str_replace(" ", "-", strtolower(strClean(clear_cadena($strNombre)))); // reemplaza espacios por giones medio de la cadena nombre, despues de ser limpiada y minimzada
//Intervalo del período de oferta; incluidas la fecha y la hora o zona horaria del inicio y la finalización de la oferta. 
//Si no ingresas las fechas; los artículos con el campo "sale_price" permanecerán en oferta hasta que elimines el precio de oferta. 
//Usa este formato: YYYY-MM-DDT23:59+00:00/YYYY-MM-DDT23:59+00:00. 
//Ingresa la fecha de inicio de la siguiente manera: YYYY-MM-DD. Escribe una "T". 
//A continuación; ingresa la hora de inicio en formato de 24 horas (00:00 a 23:59) seguida de la zona horaria UTC (-12:00 a +14:00). 
//Escribe una barra ("/") y repite el mismo formato para la fecha y hora de finalización. 
//En la siguiente fila de ejemplo se usa la zona horaria del Pacífico (-08:00).


      if ($intIdProducto == 0) {//validamos por medio del id si es un nuevo Producto o si se actualiza una Producto.
        //creamos un nuevo producto, enviamos los datos al modelo
        $option = 1;
        $request = $this->model->insertProducto($strNombre, $strDescripcion, $strDetalle, $strMarca, $strEtiquetas, $strCodigo, $intCategoriaId,
            $intCatFbId, $intCatGgId, $intProveedorId, $floCosto, $floPrecio, $floOferta, $strOferta_f_ini, $strOferta_f_fin, $intStock, $strStock_status, $ruta,
            $strGrupoEtario, $strGenero, $strTalla, $strColor, $strMaterial, $strEstilo, $strEstampado, $intStatus);
        $msg = 'Nuevo producto creado';
      } else { // si intIdProducto es distinto de cero arctuelizamos un producto
        //Actualiamos un producto
        $option = 2;
        $request = $this->model->updateProducto($intIdProducto, $strNombre, $strDescripcion, $strDetalle, $strMarca, $strEtiquetas, $strCodigo, $intCategoriaId,
                $intCatFbId, $intCatGgId, $intProveedorId, $floCosto, $floPrecio, $floOferta, $strOferta_f_ini, $strOferta_f_fin, $intStock, $strStock_status, $ruta,
                $strGrupoEtario, $strGenero, $strTalla, $strColor, $strMaterial, $strEstilo, $strEstampado, $intStatus) ? $intIdProducto : 0;

        $msg = 'Actualizado Correctamente';
      }
      // segun la respuesta enviamos un mensaje

      switch ($request) {
        case "exist": $arrResponse = array('status' => false, 'idproducto' => $request, 'msg' => 'Atencion el Producto Ya Existe');
          break;
        case true: $arrResponse = array('status' => true, 'idproducto' => $request, 'msg' => $msg);
          break;
        default : $arrResponse = array('status' => false, 'idproducto' => 0, 'msg' => "$request");
          break;
      }
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  public function getProducto($id) {
    $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
    $premin = isset($_GET['premin']) ? (intval($_GET['premin']) > 0 ? $_GET['premin'] : 0) : 0;
    $premax = isset($_GET['premax']) ? (intval($_GET['premax']) > 0 ? $_GET['premax'] : null) : null;
    $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 't';

    $intId = intval($id); //limpiamos los datos que vienen dentro de la variable $idProducto
    if ($intId > 0) { //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
      $arrData = $this->model->selectProducto($intId); //buscamos los datos que correspondan a este id

      if (empty($arrData)) { //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {// de lo contrario, estraemos las imagenes
        $arrData['descripcion'] = html_entity_decode($arrData['descripcion']);
        $arrData['detalle'] = html_entity_decode($arrData['detalle']);

        $arrImg = $this->model->selectImages($intId); //buscamos los datos que correspondan a este id
        $count = count($arrImg);
        if ($count > 0) {
          foreach ($arrImg as $key => $dataimg) {// recorremos el array con los datos de imagenes
            if ($key != $dataimg['posicion']) {//si hubo un error al cambiar la posicion de fotos, esta validacion lo corregira
              $this->model->updateOrdenImgId(intval($key), intval($dataimg['id']));
              $arrImg[$key]['posicion'] = $key;
            }
            $arrImg[$key]['url_image'] = DIR_IMAGEN . 'thumb_1_' . $dataimg['img'];
            $arrImg[$key]['url_thumb'] = DIR_IMAGEN . 'thumb_4_' . $dataimg['img'];
          }
        }
        $arrData['images'] = $arrImg;
        // para el paginado de items hacemos 3 consultas
        $arrData['prev'] = $this->model->selectProdPrevProx('prox', $arrData['idproducto'], $categoria, $premin, $premax, $estado);
        $arrData['prox'] = $this->model->selectProdPrevProx('prev', $arrData['idproducto'], $categoria, $premin, $premax, $estado);
        $arrData['posicion'] = $this->model->selectProdPosicion($arrData['idproducto'], $categoria, $premin, $premax, $estado);
        $arrResponse = array('status' => true, 'data' => $arrData);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  /* seccion de imagenes------------------------------------------------------------------------------ */

  public function setImage($param) {
    empty($_POST) ? exit(json_encode(array("status" => false, "msg" => "Sin Data"), JSON_UNESCAPED_UNICODE)) : ''; //Validamos que no este vacio el post
    if ($_POST['idproducto']) {
      $idProducto = intval($_POST['idproducto']);
      $data_foto = $_FILES['foto']; //datos del archivo
      $foto_tipo = $data_foto['type']; //tipo del archivo img/extension(jpg,web,gif,png)


      $imagen = img_alto_ancho($data_foto['tmp_name'], 1000); //Sacamos la información
      $ancho = $imagen[0];
      $alto = $imagen[1];

      $ext1 = explode('/', $foto_tipo)['1']; //obtiene la extension
      $imgNombre = 'prod_' . $idProducto . '_' . uniqid() . '.' . $ext1;

      $posicion = $this->model->getOrdenImage($idProducto);
      $posicion = isset($posicion['posicion']) ? $posicion['posicion'] + 1 : 0;
      $request_image_id = $this->model->insertImage($idProducto, $imgNombre, $posicion);

      if ($request_image_id) {
        uploadImage($data_foto, $imgNombre); //movemos la imagen del temporal a la carpeta cliente 
        $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $imgNombre; // direccion y nombre de la imagen 

        if ($_SESSION['info_empresa']['guardar_webp']) {//$ext1 != 'webp' si la extension es distinta de webp: 1 crea una imagen webp, 2 actualiza el dato en la base, 3 borra la imagen de origen, 4 actualiza la img_origin para crear las miniaturas
          if ($ext1 != 'webp') {
            $imgwebp = convertImageToWebP($img_orig); // entrega la direccion de la imagen y hace una copia webp, devuelve el nombre de la imagen con la extension webp
            $actualizaImgBD = $this->model->actualizaImagenesProd($request_image_id, $imgwebp); // actualizamos el nuevo nombre de la imagen en la base de datos
            if ($actualizaImgBD) {//si la actualizacion es correcta borramos la imagen de origen jpg, gif, png
              deleteFile($imgNombre);
              $imgNombre = $imgwebp;
              $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $imgwebp; //actualiza la img_origin para crear las miniaturas
            }
          }
        }


        thumbImage($img_orig, "1_$imgNombre", ($ancho), ($alto), 90);
        $base_thumb_og = thumbImage($img_orig, "2_$imgNombre", ($ancho * 0.77), ($alto * 0.77), 70);
        thumbImage($img_orig, "3_$imgNombre", ($ancho * 0.38), ($alto * 0.38), 60);
        thumbImage($img_orig, "4_$imgNombre", ($ancho * 0.11), ($alto * 0.11), 50);

        convertImageToJPG($base_thumb_og, 'thumb_og_' . pathinfo($imgNombre, PATHINFO_FILENAME) . '.jpg', 40);

        $imagesid = $this->model->getImagenInsertadaIdPos($request_image_id); //revisamos 

        $arrResponse = array('status' => true, 'imgname' => $imgNombre, 'msg' => 'Archivo cargado', 'data' => $imagesid);
      } else {
        $arrResponse = array('status' => false, 'msg' => 'error al cargar la foto');
      }
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Error de Carga Producto no identificado');
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  public function delFile() {
    empty($_POST) ? exit(json_encode(array("status" => false, "msg" => "Sin Data"), JSON_UNESCAPED_UNICODE)) : ''; //Validamos que no este vacio el post
    if (empty($_POST['idproducto']) || empty($_POST['file'])) {
      $arrResponse = array('status' => false, 'msg' => 'datos incorectos / incompletos');
    } else {
      // Eliminar de BD
      $idProducto = intval($_POST['idproducto']);
      $imgNombre = strClean($_POST['file']);
      $request = $this->model->deleteImage($idProducto, $imgNombre);
      if ($request) {
        deleteFile($imgNombre);
        deleteFile('thumb_1_' . $imgNombre);
        deleteFile('thumb_2_' . $imgNombre);
        deleteFile('thumb_3_' . $imgNombre);
        deleteFile('thumb_4_' . $imgNombre);
        deleteFile('thumb_og_' . pathinfo($imgNombre, PATHINFO_FILENAME) . '.jpg');

        $imagesid = $this->model->getImagenProdPosicion($idProducto); //revisamos 

        foreach ($imagesid as $key => $idimg) {

          $this->model->updateOrdenImgId(intval($key), intval($idimg['id']));
        }
        $imagesid = $this->model->getImagenProdPosicion($idProducto); //revisamos 

        $arrResponse = array('status' => true, 'msg' => 'Archivo Eliminado', 'data' => $imagesid);
      } else {
        $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el archivo');
      }
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  public function setPosicionImg() {
    $a_id = intval(str_ireplace('div', '', $_POST['a_id']));
    $a_pos = intval($_POST['a_pos']);
    $b_id = intval(str_ireplace('div', '', $_POST['b_id']));
    $b_pos = intval($_POST['b_pos']);
    if ($a_id === $b_id) {
      $arrResponse = array('status' => false);
    } else {
      $request_a = $this->model->updateOrdenImgId($b_pos, $a_id);
      $request_b = $this->model->updateOrdenImgId($a_pos, $b_id);
      if ($request_a && $request_b) {
        $arrResponse = array('status' => true);
      } else {
        $arrResponse = array('status' => false);
      }
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON
  }

  public function recreaThumb() {
    $data['ArrJson'] = $this->model->listarImagenesProd();
    $this->views->getView("recreaThumb", $data);
  }

  public function recreaThumbProcesar($id) {
    $requestLista = $this->model->listarImagenesProd($id);
    $response_html = '';
    foreach ($requestLista as $image) {
      $nombre = $image['img'];
      $img_orig = $image['img']; // obtiene el nombre de la imagen 
      $extension = pathinfo($img_orig, PATHINFO_EXTENSION); //obtiene la extension
      $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $image['img']; //armamos la direccion de la imagen

      if ($_SESSION['info_empresa']['guardar_webp'] && $extension != 'webp') {//$ext1 != 'webp' si la extension es distinta de webp: 1 crea una imagen webp, 2 actualiza el dato en la base, 3 borra la imagen de origen, 4 actualiza la img_origin para crear las miniaturas
        print "imagen de origen: " . $img_orig . '<br>';
        $imgwebp = convertImageToWebP($img_orig); // entrega la direccion de la imagen y hace una copia webp, devuelve el nombre de la imagen con la extension webp
        print "imagen creada: " . $imgwebp . '<br>';
        $actualizaImgBD = $this->model->actualizaImagenesProd($image['id'], $imgwebp); // actualizamos el nuevo nombre de la imagen en la base de datos
        if ($actualizaImgBD) {//si la actualizacion es correcta borramos la imagen de origen jpg, gif, png
          if (pathinfo($image['img'], PATHINFO_EXTENSION) != 'webp') {
            deleteFile($image['img']);
            print "imagen borrada: " . $image['img'] . '<br>';
          }
          deleteFile('thumb_1_' . $image['img']) ? print "imagen borrada: thumb_1_" . $image['img'] . '<br>' : '';
          deleteFile('thumb_2_' . $image['img']) ? print "imagen borrada: thumb_2_" . $image['img'] . '<br>' : '';
          deleteFile('thumb_3_' . $image['img']) ? print "imagen borrada: thumb_3_" . $image['img'] . '<br>' : '';
          deleteFile('thumb_4_' . $image['img']) ? print "imagen borrada: thumb_4_" . $image['img'] . '<br>' : '';
          deleteFile('thumb_og_' . $image['img']) ? print "imagen borrada: thumb_og_" . $image['img'] . '<br>' : '';
        }
        $nombre = $imgwebp;
        $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . $imgwebp; //actualiza la img_origin para crear las miniaturas
      }

      if (!file_exists($img_orig)) {
        // Ruta y nombre del archivo original
        $origen = './uploads/' . FILE_SISTEM_CLIENTE . 'thumb_1_' . $nombre;
        // Generar un nuevo nombre y ruta para la copia
        $destino = './uploads/' . FILE_SISTEM_CLIENTE . $nombre;
        // Copiar el archivo
        if (copy($origen, $destino)) {
          echo '<li>Imagen duplicada con éxito como ' . $nombre . '</li>';
        } else {
          echo '<li>Error al duplicar la imagen<li>';
        }
      }
      $imagen = img_alto_ancho($img_orig, 1000);
      $ancho = $imagen[0];
      $alto = $imagen[1];

      $response_html .= '<li><a href="' . base_url() . $img_orig . '" target="_blank">' . 'imagen original: ' . $img_orig . ' ancho:' . ($ancho) . ' - alto:' . ($alto) . '</a></li>';
      $thumb1 = thumbImage($img_orig, '1_' . $nombre, ($ancho), ($alto), 90);
      $response_html .= '<li><a href="' . base_url() . $thumb1 . '" target="_blank">' . 'imagen creada: 1_' . $nombre . ' ancho:' . ($ancho) . ' alto:' . ($alto) . '</a></li>';

      $thumb2 = thumbImage($img_orig, '2_' . $nombre, ($ancho * 0.77), ($alto * 0.77), 70);
      $response_html .= '<li><a href="' . base_url() . $thumb2 . '" target="_blank">' . 'imagen creada: 2_' . $nombre . ' ancho:' . ($ancho * 0.77) . ' - alto:' . ($alto * 0.77) . '</a></li>';

      $thumb3 = thumbImage($img_orig, '3_' . $nombre, ($ancho * 0.38), ($alto * 0.38), 60);
      $response_html .= '<li><a href="' . base_url() . $thumb3 . '" target="_blank">' . 'imagen creada: 3_' . $nombre . ' ancho:' . ($ancho * 0.38) . ' - alto:' . ($alto * 0.38) . '</a></li>';

      $thumb4 = thumbImage($img_orig, '4_' . $nombre, ($ancho * 0.11), ($alto * 0.11), 50);
      $response_html .= '<li><a href="' . base_url() . $thumb4 . '" target="_blank">' . 'imagen creada: 4_' . $nombre . ' ancho:' . ($ancho * 0.11) . ' - alto:' . ($alto * 0.11) . '</a></li>';

      $imgNombreOg = 'thumb_og_' . pathinfo($nombre, PATHINFO_FILENAME) . '.jpg';
      $thumbog = convertImageToJPG($thumb2, $imgNombreOg, 50); //actualiza la img_origin para crear las miniaturas
      $response_html .= '<li><a href="' . base_url() . $thumbog . '" target="_blank"> imagen creada: ' . $imgNombreOg . '</a></li>';

      $response_html .= '<li><span> imagen original: ' . base_url() . $img_orig . '</span></li>';
      $response_html .= '<li><span> ----------------------------------------------------------------------------------------------</span></li>';
    }
    $response_html .= '<li><span> =============================================================================================</span></li>';
    echo $response_html;
  }

  public function recreaThumbProcesar1($id) {
    $requestLista = $this->model->listarImagenesProd($id);
    $response_html = '';
    foreach ($requestLista as $image) {
      $nombre = $image['img'];
      $img_orig = $image['img'];
      $extension = pathinfo($img_orig, PATHINFO_EXTENSION);
      $img_orig_path = './uploads/' . FILE_SISTEM_CLIENTE . $image['img'];

      if ($_SESSION['info_empresa']['guardar_webp'] && $extension != 'webp') {
        $imgwebp = convertImageToWebP($img_orig_path);
        $actualizaImgBD = $this->model->actualizaImagenesProd($image['id'], $imgwebp);
        if ($actualizaImgBD) {
          deleteFile($img_orig_path);
          foreach (['thumb_1_', 'thumb_2_', 'thumb_3_', 'thumb_4_', 'thumb_og_'] as $prefix) {
            deleteFile($prefix . $image['img']);
          }
        }
        $nombre = $imgwebp;
        $img_orig_path = './uploads/' . FILE_SISTEM_CLIENTE . $imgwebp;
      }
      if (!file_exists($img_orig_path)) {
        // Ruta y nombre del archivo original
        $origen = './uploads/' . FILE_SISTEM_CLIENTE . 'thumb_1_' . $nombre;
        // Generar un nuevo nombre y ruta para la copia
        $destino = './uploads/' . FILE_SISTEM_CLIENTE . $nombre;
        // Copiar el archivo
        if (copy($origen, $destino)) {
          echo '<li>Imagen duplicada con éxito como ' . $nombre . '</li>';
        } else {
          echo '<li>Error al duplicar la imagen<li>';
        }
      }
      $imagen = img_alto_ancho($img_orig_path, 1000);
      $ancho = $imagen[0];
      $alto = $imagen[1];

      for ($i = 1; $i <= 4; $i++) {
        $thumb = thumbImage($img_orig_path, $i . '_' . $nombre, ($ancho * (0.6 ** ($i - 1))), ($alto * (0.6 ** ($i - 1))), ($i === 1 ? 100 : 50));
        $response_html .= '<li><a href="' . base_url() . $thumb . '" target="_blank">' . 'imagen creada: ' . $i . '_' . $nombre . ' ancho:' . ($ancho * (0.6 ** ($i - 1))) . ' - alto:' . ($alto * (0.6 ** ($i - 1))) . '</a></li>';
      }
      $imgNombreOg = pathinfo($nombre, PATHINFO_FILENAME) . '.jpg';
      $thumb_og = thumbImage($img_orig_path, 'og_' . $imgNombreOg, 1200, 630, 100);
      $response_html .= '<li><a href="' . base_url() . $thumb_og . '" target="_blank">' . 'imagen creada: og_' . $nombre . ' ancho:1200 - alto:630</a></li>';
    }

    return $response_html;
  }

  public function getCategoriasProducto() {
    $htmlOption = "";
    $htmlOptionSubCat = '';
    $categorias = $this->model->getCategoriasCountProducto();
    $count = count($categorias);
    if ($count > 0) {
      foreach ($categorias as $categoria) { //repasamos la lista y creamos un array html con el valor y el nombre 
        $subCategorias = $this->model->getCategoriasCountProducto($categoria['idcategoria']);
        $htmlOptionSubCat = '';
        if (count($subCategorias) > 0) {
          foreach ($subCategorias as $subCategoria) { //repasamos la lista y creamos un array html con el valor y el nombre 
            $htmlOptionSubCat .= '<option value ="' . $subCategoria['idcategoria'] . '">' . "- ({$subCategoria['count']}) " . strtoupper($subCategoria['nombre']) . '</option>';
            $categoria['count'] = $categoria['count'] + $subCategoria['count'];
          }
        }
        $htmlOption .= '<option value ="' . $categoria['idcategoria'] . '">' . "({$categoria['count']}) " . ucwords(strtolower($categoria['nombre'])) . '</option>';
        $htmlOption .= $htmlOptionSubCat;
      }
    }
    exit($htmlOption);
  }

  public function delProducto() {
    empty($_POST) ? exit(json_encode(array('status' => false, 'msg' => 'no hay datos'), JSON_UNESCAPED_UNICODE)) : '';
    $intId = intval($_POST['id']);

    $imagenes = $this->model->selectImages($intId);
    if ($imagenes) {
      foreach ($imagenes as $img) {
        deleteFile('thumb_1_' . $img['img']);
        deleteFile('thumb_2_' . $img['img']);
        deleteFile('thumb_3_' . $img['img']);
        deleteFile('thumb_og_' . pathinfo($img['img'], PATHINFO_FILENAME) . '.jpg');
        deleteFile($img['img']);
        $img_del = $this->model->deleteImage($intId, $img['img']) ? 1 : 0;
      }
    } else {
      $img_del = 1;
    }

    if ($img_del) {
      $arrResponse = $this->model->deleteProducto($intId) ?
          array('status' => true, 'msg' => 'Se ha eliminado el Producto') :
          array('status' => false, 'msg' => 'Error al borrar Producto');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Error al borrar imagenes');
    }
    echo (json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  public function statusChange() {
    if (isset($_GET['id']) && isset($_GET['intStatus'])) {
      $intId = intval($_GET['id']);
      $intStatus = intval($_GET['intStatus']);
      $requestStatus = $this->model->editStatus($intId, $intStatus);
      if ($requestStatus == 'OK') {
        $arrResponse = $intStatus === 1 ?
            $arrResponse = array('status' => true, 'msg' => 'Se ha Desactivado el item') :
            $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el item');
      } else if ($requestStatus == 'error') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el item');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }

  /* ------------------------------------------------------------------------------------------------------------------------------------------------ */

  public function setProveedor() {
    !empty($_POST) ? '' : exit(array("status" => false, "msg" => "Datos incompletos")); //Validamos que no este vacio el post
    if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['txtDireccion']) || empty($_POST['txtTelefono'])) { // validamos que los datos no esten vacios 
      $arrResponse = array("status" => false, "msg" => "Datos incompletos"); // si uno de los datos esta vacio. entonces se devuelve un mensaje 
    } else {
      //recibe los datos por medio de url y devuelve un mensaje json segun su resultado
      //los datos enviados los almacenamos en variables
      $intId = intval($_POST['idProveedor']);
      $strNombre = strClean(clear_cadena($_POST['txtNombre']));
      $strDescripcion = strClean($_POST['txtDescripcion']);
      $strDireccion = strClean($_POST['txtDireccion']); //
      $strWeb = strClean($_POST['txtWeb']); //
      $strFacebook = strClean($_POST['txtFacebook']); //
      $strInstagram = strClean($_POST['txtInstagram']); //
      $intTelefono = clear_int($_POST['txtTelefono']); //
      $intMobil = clear_int($_POST['txtMobil']); //
      $listStatus = intval($_POST['listStatus']); //
      // pasamos a variables los datos de la foto 
      $foto_actual = $_POST['foto_actual'];
      $foto_remove = $_POST['foto_remove'];
      $nombre_foto = $_POST['foto_blob_name'] === '' ? '' : $_POST['foto_blob_name']; // $foto['name']; // pasamos a variable el nombre 
      $type0 = $_POST['foto_blob_type'] === '' ? '' : explode('/', strClean($_POST['foto_blob_type']))[1]; //el tipo de archivo // $foto['type']
      $type_org = $type0 === 'jpeg' ? 'jpg' : $type0; //

      $foto_file = $_FILES['foto']; //
      //$ext1 = explode('/', $foto['type'])['1']; //obtiene la extension
      /* ESTADO DE IMG ----------------------------- */
      $intGuardar_webp = (isset($formData ['guardar_webp']) && $formData ['guardar_webp'] == 'on') ? 1 : 0;
      $img_name = "portada_categoria.png"; //
      $uniqid = uniqid();
      $estado_foto = $this->estadoFoto($nombre_foto, $foto_actual, $foto_remove);
      if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') {
        $img_orig = 'proveedor_' . str_replace(" ", "-", strtolower(clear_cadena($strNombre))) . '_' . $uniqid . '.' . $type_org; // le generamos un nombre aleatorio a la imagen
        $img_name = 'proveedor_' . str_replace(" ", "-", strtolower(clear_cadena($strNombre))) . '_' . $uniqid . '.' . ($intGuardar_webp ? 'webp' : $type_org); // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'sin_mov') {
        $img_name = $foto_actual; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'sin_mov_def') {
        $img_name = 'portada_categoria.png'; // le generamos un nombre aleatorio a la imagen
      }
      if ($estado_foto === 'eliminada' || $estado_foto === 'sin_mov_def') {
        $img_name = 'portada_categoria.png';
      }
      // NUEVO
      if ($intId === 0) {//validamos por medio del id si es un nuevo Categoria o si se actualiza una Categoria.
        //creamos una una categoria, enviamos los datos al modelo
        $request = $this->model->insertProveedor($strNombre, $strDescripcion, $strDireccion, $img_name, $strWeb, $strFacebook, $strInstagram, $intTelefono, $intMobil, $listStatus);
        $option = 'Datos Guardados Correctamente';
        //ACTUALIZAR
      } else { // si intIdCat es distinto de cero arctuelizamos un categoria
        //Actualiamos una categoria, enviam,os los datos al modelo
        $request = $this->model->updateProveedor($intId, $strNombre, $strDescripcion, $strDireccion, $img_name, $strWeb, $strFacebook, $strInstagram, $intTelefono, $intMobil, $listStatus);
        $option = 'Datos Actualizados Correctamente';
      }
      if ($request > 0) {
        $arrResponse = array('status' => true, 'msg' => $option);
        //Guarda la imagen y crea la miniatura
        if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') { //movemos la imagen del 
          $this->guardaImg($intGuardar_webp, $foto_file, $img_name, $img_orig); // movemos el archivo del temporal a la carpeta image/upload
          $img_dir = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_name;
          thumbImage($img_dir, "1_$img_name", 100, 100);
        }
        if ($estado_foto === 'eliminada' || $estado_foto === 'actualizada') { // elimanos la imagen original si esta es actualizada o eliminada
          if ($foto_actual != 'portada_categoria.png') {
            deleteFile($foto_actual);
            deleteFile("thumb_1_$foto_actual");
          }
        }
      } else if ($request === 'exist') {
        $arrResponse = array('status' => false, 'msg' => 'Atencion la Categoria Ya Existe');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'No es posible Guardar la categoria');
      }
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  private function guardaImg($intGuardar_webp, $foto_file, $img_name, $img_orig) {
    if ($intGuardar_webp) {
      uploadImage($foto_file, $img_orig); // movemos el archivo del temporal a la carpeta image/upload
      $dir_img = 'uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_orig;
      convertImageToWebP($dir_img);
      deleteFile($img_orig);
    } else {
      uploadImage($foto_file, $img_name); // movemos el archivo del temporal a la carpeta image/upload
    }
  }

  private function estadoFoto($nombre_foto, $foto_actual, $foto_remove) {
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

  //DEVUELVE UN ARRAY CON LOS DATOS DE PRODUCTO Y BOTONES DE OPCION BOOSTRAP PARA INSERTAR EN DATATABLE
  public function getProveedores() {
// $categoria = isset($_GET['cat']) ? (intval($_GET['cat']) > 0 ? $_GET['cat'] : null) : null;
// $premin = isset($_GET['premin']) ? (intval($_GET['premin']) > 0 ? $_GET['premin'] : 0) : 0;
// $premax = isset($_GET['premax']) ? (intval($_GET['premax']) > 0 ? $_GET['premax'] : null) : null;
    $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 't';

    $arrData = $this->model->selectProveedores($estado); //consultamos la tabla y traemos todos los registros 
    //$permiso = $_SESSION['userPermiso'][$this->idModul]; //reemplaza los valores 0 y 1 por inactivo - Activo 
    foreach ($arrData as $i => $item) {
      /* seleccion de imagen -------------------------------- */
      $arrData[$i]['img'] = ($item['img'] != 'portada_categoria.png') ?
          '<img class="minlistprod_img" src=" ' . DIR_IMAGEN . 'thumb_1_' . $item["img"] . ' "> ' :
          '<img class="minlistprod_img" src=" ' . DIR_MEDIA . 'images/portada_categoria.png"> ';
// $arrData[$i]['img_url'] = ($item['img'] != 'portada_categoria.png') ?
// DIR_IMAGEN . $item["img"] :
// DIR_MEDIA . 'images/portada_categoria.png';
      /* Botones de accion -------------------------------- */
      $id = $item['idproveedor'];
      $opciones = "<div class= 'text-center'>";
      $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button' ><i class='fas fa-eye'></i></button>";
      $opciones .= $_SESSION['userPermiso'][$this->idModul]['actualizar'] == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
      $opciones .= $item['status'] == 1 ?
          "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado' type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off' aria-hidden='true'></i></button>" :
          "<button class='btn btn-danger m-1 ' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off' aria-hidden='true'></i></button>";
// if ($_SESSION['userPermiso'][$this->idModul]['eliminar'] == 1) { // si el rol esta en uso solo podra ser activado o desactivado
      $opciones .= $this->model->proveedorEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
// }
      $arrData[$i]['options'] = $opciones . "</div>";
    }


    exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
  }

  public function getProveedor($id) {
    $estado = isset($_GET['estado']) ? strClean($_GET['estado']) : 't';
    $intId = intval($id); //limpiamos los datos que vienen dentro de la variable $idProducto

    if ($intId > 0) { //si el contenido de la variable es mayor a 0 significa que hay un id a buscar
      $arrData = $this->model->selectProveedor($intId); //buscamos los datos que correspondan a este id
      if (empty($arrData)) { //si no devuelve ningun dato, respondemos con una array json de dato no encontrado
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
      } else {// de lo contrario, estraemos las imagenes
        $arrData['url_img'] = ($arrData['img'] != 'portada_categoria.png') ?
            DIR_IMAGEN . 'thumb_1_' . $arrData['img'] :
            DIR_MEDIA . 'images/portada_categoria.png';

        // para el paginado de items hacemos 3 consultas
        $arrData['prev'] = $this->model->selectProvPrevProx('prev', $arrData['idproveedor'], $estado);
        $arrData['prox'] = $this->model->selectProvPrevProx('prox', $arrData['idproveedor'], $estado);
        $arrData['posicion'] = $this->model->selectProvPosicion($arrData['idproveedor'], $estado);
        $arrResponse = array('status' => true, 'data' => $arrData);
      }
      //IMPRIMIMOS EL ARRAY DE DATOS EN FORMATO JSON
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
    exit();
  }

  public function statusProveedorChange() {
    if (isset($_GET['id']) && isset($_GET['intStatus'])) {
      $intId = intval($_GET['id']);
      $intStatus = intval($_GET['intStatus']);
      $requestStatus = $this->model->editProveedorStatus($intId, $intStatus);
      if ($requestStatus == 'OK') {
        $arrResponse = $intStatus === 1 ?
            $arrResponse = array('status' => true, 'msg' => 'Se ha Desactivado el item') :
            $arrResponse = array('status' => true, 'msg' => 'Se ha Activado el item');
      } else if ($requestStatus == 'error') {
        $arrResponse = array('status' => false, 'msg' => 'No es posile desactivar el item');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }

  public function delProveedor() {
    empty($_POST) ? exit(json_encode(array('status' => false, 'msg' => 'no hay datos'), JSON_UNESCAPED_UNICODE)) : '';
    $intId = intval($_POST['id']);
    $imagen = $this->model->selectImgProv($intId);
    $img_del = 1;
    if ($imagen) {
      if ($imagen !== 'portada_categoria.png') {
        $del1 = deleteFile('thumb_1_' . $imagen);
        $del2 = deleteFile($imagen);
        $img_del = $del1 === 1 && $del2 === 1 ? 1 : 0;
      }
    }//array('status' => true, 'msg' => 'Se ha eliminado el Producto'),
    if ($img_del) {
      $arrResponse = $this->model->deleteProveedor($intId) ?
          array('status' => true, 'msg' => 'Proveedor Exterminado') :
          array('status' => false, 'msg' => 'Error al borrar Proveedor');
    } else {
      $arrResponse = array('status' => false, 'msg' => 'Error al borrar imagenes');
    }
    exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE)); //inval convierte en entero el parametro que le ingresen
  }

  public function getSelectProveedoresChoise() {
//Realiza una consulta a la tabla cates y devuelve una lista html ID Nombre, para developer.snapappointments.com
    $arrResponse = array();
    $arrData = $this->model->selectProveedoresChoise();
    $count = count($arrData);
    if ($count > 0) {
      foreach ($arrData as $cat) {//si el status es 1 creamos un array html con el id como valor y el nombre 
        $arr = array('value' => $cat['idproveedor'], 'label' => $cat['nombre'], 'disabled' => $cat['status'] == 0 ? true : false);
        array_push($arrResponse, $arr);
      }
    }
    echo (json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  /* -------------------------------------------------------------------------------------- */
  /* -------------------------------------------------------------------------------------- */

  function recreaOrdenImg() {
    $productosid = $this->model->getProductosid();
    foreach ($productosid as $idprod) {
      dep('');
      dep($idprod);

      $imagesid = $this->model->getImagenProdId($idprod);
      dep($imagesid);

      foreach ($imagesid as $key => $idimg) {

        $this->model->setOrdenImgId($key, $idimg);
      }
      $imagesidposicion = $this->model->getImagenOrdenProdId($idprod, 1);
      dep($imagesidposicion);
    }
  }
}
