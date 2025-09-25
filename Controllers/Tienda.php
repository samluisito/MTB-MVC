<?php

declare(strict_types=1);

class Tienda extends Controllers {

  public function __construct() {

    parent::__construct();
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function tienda() {
    /*     * ******************************************* */
    require_once __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Tienda');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;
    $data['dispositivo'] = detectar_dispositivo();

    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => $empresa['nombre_comercial'],
      'description' => substr(strClean(strip_tags($empresa['descripcion'])), 0, 160),
      'keywords' => $empresa['tags'],
      'url' => base_url(),
      'image' => $empresa['url_logoImpreso'],
      'image:type' => explode('.', $empresa['logo_imp'])[1],
      'og:type' => 'website'
    );
    /* paginador */
    $pagina = isset($_GET['page']) ? (is_numeric($_GET['page']) ? intval($_GET['page']) : 1) : 1;
    $data += $this->paginar(24, $pagina);

// las funciones de la pagina van de ultimo 
    $data['page_css'] = array();
    $data['page_functions_js'] = array();
    unset($empresa);

    $this->views->getView('Tienda', $data);
  }

  function paginar(int $limit_productos, int $pagina, int $id_cat = null, string $ruta_cat = null) {
    $total_registro = $this->model->countProductos($id_cat, $ruta_cat)['total_registros'];
    $desde = ($pagina - 1) * $limit_productos; // determinamos la cantidad de registros que no se mostrarian, dicho de otra forma desde dode comenzaremos a contar
    $total_paginas = ceil($total_registro / $limit_productos); // Ceil convierte un numero con decimales a un numero entero, retirando los decimales 
    $productos = $this->model->getProductosPaginado($desde, $limit_productos, $id_cat, $ruta_cat);
    return [
      'pagina' => $pagina,
      'total_pagina' => $total_paginas,
      'productos' => $productos
    ];
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function categoria($param) {
    empty($param) ? $this->tienda() : null; // si no hay parametros muestra el catalogo de la tienda
    /*     * ******************************************* */
    require_once __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Categorias');
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;
    $data['dispositivo'] = detectar_dispositivo();

    $arrParam = explode(',', $param);

    if (is_number($param[0])) {
      $id_cat = intval($arrParam[0]);
      $ruta_cat = isset($arrParam[1]) ? strClean($arrParam[1]) : 0;
    } else {
      $id_cat = 0;
      $ruta_cat = isset($arrParam[0]) ? strClean($arrParam[0]) : 0;
    }

    $meta = $this->model->getMetaCategoria($id_cat, $ruta_cat);

    if ($meta) {
      $data['meta'] = array(
        'robots' => 'index, follow, archive',
        'title' => 'Categoria: ' . $meta['nombre'],
        'description' => substr(strClean(strip_tags($meta['descripcion'])), 0, 160),
        'keywords' => $meta['tags'],
        'url' => base_url() . 'tienda/Categoria/' . $meta['idcategoria'] . '/' . $meta['ruta'],
        'image' => $meta['url_img'],
        'image:type' => explode('.', $meta['img'])[1],
        'og:type' => 'product'
      );
      /* Breadcums */

      $data['categoria'] = array('nombre' => $meta['nombre'], 'ruta' => $meta['ruta']);
      $pagina = isset($_GET['page']) ? (is_numeric($_GET['page']) ? intval($_GET['page']) : 1) : 1;
      $data += $this->paginar(24, $pagina, $id_cat, $ruta_cat);
    }
    $data['page_css'] = array();
    $data['page_functions_js'] = array();

    $this->views->getView(( empty($data['meta']) ? 'ErrorTienda' : 'Tienda'), $data);
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function search($param) {
    empty($param) ? $this->tienda() : null; // si no hay parametros muestra el catalogo de la tienda
    $search = strClean($param);

    /*     * ******************************************* */
    require_once __DIR__ . '/../Controllers/Home.php';
    $this->data = new Home();
    $data['header'] = $this->data->data_header('Buscar: ' . $search);
    $data['footer'] = $this->data->data_footer();
    /*     * ******************************************* */
    $empresa = $_SESSION['info_empresa'];
    $data['empresa'] = $empresa;
    $data['dispositivo'] = detectar_dispositivo();

    $data['meta'] = array(
      'robots' => 'index, follow, archive',
      'title' => 'Busqueda: ' . $search,
      'description' => substr(strClean(strip_tags($search)), 0, 160),
      'keywords' => '',
      'url' => base_url() . 'tienda/search/' . $search,
      'image' => '', //$meta['url_img'],
      'image:type' => '', //explode('.', $meta['img'])[1],
      'og:type' => 'product'
    );

    $data['busqueda'] = $search;
    /* paginador */
    $limit_productos = 24; // cantodad de prosuctos a contar 

    $pagina = isset($_GET['page']) ? (is_numeric($_GET['page']) ? intval($_GET['page']) : 1) : 1;
    $total_registro = $this->model->countProductosPorBusquedaT($search);
    $desde = ($pagina - 1) * $limit_productos; // determinamos la cantidad de registros que no se mostrarian, dicho de otra forma desde dode comenzaremos a contar
    $total_paginas = ceil($total_registro['total_registros'] / $limit_productos); // Ceil convierte un numero con decimales a un numero entero, retirando los decimales 
    $data['pagina'] = $pagina;
    $data['total_pagina'] = $total_paginas;
    $data['productos'] = $this->model->getProductosBusquedaPaginado($search, $desde, $limit_productos); // ingresar limite de productos por pag
    $data['page_css'] = array();
    $data['page_functions_js'] = array();
    $this->views->getView((empty($data['productos']) ? 'ErrorTienda' : 'Tienda'), $data);
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function producto($param) {
    if (empty($param)) {
//      header('Location:' . base_url());
      empty($param) ? $this->tienda() : null; // si no hay parametros muestra el catalogo de la tienda
    } else {
      /*       * ******************************************* */
      require_once __DIR__ . '/../Controllers/Home.php';
      $this->data = new Home();
      $data['header'] = $this->data->data_header('Producto no Encontrado');
      $data['footer'] = $this->data->data_footer();
      /*       * ******************************************* */
      $empresa = $_SESSION['info_empresa'];
      $data['empresa'] = $empresa;
      $data['dispositivo'] = detectar_dispositivo();

      $arrParam = explode(',', $param);

      if (is_number($arrParam[0])) {
        $id_prod = intval($arrParam[0]);
        $ruta_prod = isset($arrParam[1]) ? strClean($arrParam[1]) : 0;
      } else {
        $id_prod = 0;
        $ruta_prod = isset($arrParam[0]) ? strClean($arrParam[0]) : 0;
      }

      $data['producto'] = $this->model->getProductoId($id_prod, $ruta_prod);
      $data['page_title'] = $data['producto']['nombre'] ?? 'Error 404';
      if ($data['producto']) {
        /* Breadcums */
        $data['cat_padre'] = $this->model->selectCatPadre($data['producto']['categoriaid']);
        $data['categoria'] = $data['producto']['categoria'];

        $meta = $data['producto'];
        $data['meta'] = array(
          'robots' => 'index, follow, archive',
          'title' => $meta['nombre'],
          'description' => substr(strip_tags($meta['descripcion']), 0, 160),
          'keywords' => $meta['etiquetas'],
          'url' => base_url() . 'tienda/producto/' . $meta['ruta'],
          'image' => $meta['images'][0]['url_img_thumb_og'],
          'image:type' => 'jpg', //explode('.', $meta['images'][0]['img'])[1],
          'og:type' => 'product'
        );
        $data['header']['page_title'] = $meta['nombre'];
        $data['prod_relac'] = $this->model->getProductosRamdom(8, 'r', intval($data['producto']['idproducto']), ($data['producto']['nombre'] . ' ' . $data['producto']['etiquetas'])); // requiere 3 parametros, el idcategoria, el limite de articulos a buscar y el orden que puede ser r=RANDOM a=ASCENDENTE y d=DESCENDENTE 
      }

      $data['page_css'] = array('tienda/vendor/MagnificPopup/magnific-popup', 'tienda/vendor/select2/select2');
      $data['page_functions_js'] = array('tienda/vendor/MagnificPopup/jquery.magnific-popup', 'tienda/vendor/select2/select2');
      $this->views->getView(empty($data['meta']) ? 'ErrorTienda' : 'Producto', $data);
    }
  }

  /* [CARRITO]============================================================================================================================================================================== */

  public function addCarrito() {
    if ($_POST) {
      $arrCarrito = array();  //creamos el array carrito
      $cantCarrito = 0;  // suma el total de articulos del carrito 
      $talle = strClean($_POST['talle']);
      $color = strClean($_POST['color']);
      $idProducto = intval($_POST['id']);
      $cantidad = intval($_POST['cant']);
      if (is_numeric($idProducto) and is_numeric($cantidad)) { //validamos que los datos recividos sean numericos 
        $arrDataProducto = $this->model->getProductoIdInfoCar($idProducto);
        !isset($arrDataProducto) ? $arrResponse = array('status' => false, 'msg' => 'productono no existe') : null;
//validamos qye vengan datos 
      }
//pasamos los datos al carrito en la variable sesion
      $arrProducto = array(
        'idproducto' => $idProducto,
        'nombre' => $arrDataProducto['nombre'], //(strlen($arrDataProducto['nombre']) > 20 ? substr(strClean(strip_tags($arrDataProducto['nombre'])), 0, 23) . '...' : $arrDataProducto['nombre']),
        'cantidad' => $cantidad,
        'precio' => $arrDataProducto['precio'],
        'oferta' => $arrDataProducto['oferta_activa'] === 1 ? $arrDataProducto['oferta'] : 0,
        'talle' => $talle,
        'color' => $color,
        'ruta' => $arrDataProducto['ruta'],
        'img' => $arrDataProducto['images']
      );
      if (isset($_SESSION['arrCarrito'])) {   //si la variable carrito existe en $_session, significa que ya fue iniciada y que hay un producto  
        $on = true; // variable guia para identificar si un producto se actualizo o se creo 
        $arrCarrito = $_SESSION['arrCarrito']; // se pasan los datos de session-carrito a una vatiable para se repasados
        $countCar = count($arrCarrito);
        for ($pr = 0; $pr < $countCar; $pr++) {  //repasamos el parray para alterar la cantidad de los productos con el id existente.
          if (isset($arrCarrito[$pr])) {
            if ($arrCarrito[$pr]['idproducto'] === $idProducto) {//si en el array hay un id producto === al que estamos enviaando por _post
              $arrCarrito[$pr]['cantidad'] += $cantidad; // le sumamos la cantidad que viene en _post a la cantidad que ya tienen en el array de session
              $on = false;  // cambiamos el estado de la variable on a falso 
            }
          }
        }
        $on ? array_push($arrCarrito, $arrProducto) : ''; //agrega un elemento en el array

        $_SESSION['arrCarrito'] = $arrCarrito; // pasamos el array actualizado a la variable session  
      } else { // si la cariable no existe, entonces se cre y se le agrega un producto en el array 
        array_push($arrCarrito, $arrProducto); //agrega un elemento en el array
        $_SESSION['arrCarrito'] = $arrCarrito; // pasamos este elenemto a la variable session  
      }
      $totalCarrito = 0;
      $htmlCarrito = '';
      foreach ($_SESSION['arrCarrito'] as $pro) {//por cada vuelta de la lista se pasan los items a $pro 
        $totalCarrito += $pro['cantidad'] * $pro['precio'];
        $cantCarrito += $pro['cantidad']; // y se suma la cantidad de $pro-cantidad y se guardan en una variable       
        $htmlCarrito .= html_producto_carrito($pro);
      }
      $arrResponse = array('status' => true,
        'msg' => "Agregaste {$arrDataProducto['nombre']} al carrito",
        'cantCarrito' => $cantCarrito,
        'htmlCarrito' => $htmlCarrito,
        'totalCarrito' => formatMoney($totalCarrito));
    } else {
      $arrResponse = array('status' => false, 'msg' => 'dato incorrecto');
    }
    echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function updCarrito() {
    if ($_POST) {
      $arrCarrito = array();
      $totalproducto = 0;
      $subtotal = 0;
      $total = 0;
      $idProducto = $_POST['id'];
      $cantidad = intval($_POST['cant']);

      if (is_numeric($idProducto) and ($cantidad) > 0) { //validamos que los datos recividos sean numericos 
        $arrCarrito = $_SESSION['arrCarrito'];      // se pasan los diatos de session-carrito a una vatiable para ser repasados
        $countCar = count($arrCarrito);
        for ($pr = 0; $pr < $countCar; $pr++) {    //repasamos el parray para alterar la cantidad de los productos con el id existente.
          if (isset($arrCarrito[$pr])) {
            if ($arrCarrito[$pr]['idproducto'] == $idProducto) {    //si en el array hay un id producto en la posicion pr
              $arrCarrito[$pr]['cantidad'] = $cantidad;
              $totalproducto = $cantidad * ($arrCarrito[$pr]['oferta'] != 0 ? $arrCarrito[$pr]['oferta'] : $arrCarrito[$pr]['precio']); // guardo la multiplicacion de precio * cantidad 
            }
          }
        }

        $_SESSION['arrCarrito'] = $arrCarrito;

        foreach ($_SESSION['arrCarrito'] as $pro) {//por cada vuelta de la lista se pasan los items a $pro 
          $subtotal += $pro['cantidad'] * ($pro['oferta'] != 0 ? $pro['oferta'] : $pro['precio']); // y se suma la cantidad de $pro-cantidad y se guardan en una variable
        }


        $costo_envio = $_SESSION['info_empresa']['costo_envio'];
        $total += $subtotal + $costo_envio;

        $arrResponse = array('status' => true,
          'msg' => '!Producto Actualizado¡',
          'totalProducto' => formatMoney($totalproducto),
          'subTotal' => formatMoney($subtotal),
          'total' => formatMoney($total)
        );
      } else {
        $arrResponse = array('status' => false, 'msg' => '!Darto incorrecto¡');
      }
    } else {
      $arrResponse = array('status' => false, 'msg' => 'No se recibieron datos');
    }

    echo (json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function delCarrito($param) {
    if ($_POST) {

      $arrCarrito = array();  //creamos el array carrito
      $cantCarrito = 0;  // suma el total de articulos del carrito 
      $subtotal = 0;

      $idProducto = intval($_POST['id']);
      $option = intval($_POST['option']);

      if (is_numeric($idProducto) and ($option === 1 || $option === 2)) { //validamos que los datos recividos sean numericos y que la opcion sea 1 o 2
        $arrCarrito = $_SESSION['arrCarrito'];
        // se pasan los diatos de session-carrito a una vatiable para ser repasados
        foreach ($arrCarrito as $i => $item) {//repasamos el array para alterar la cantidad de los productos con el id existente.
          if (intval($item['idproducto']) === $idProducto) {    //si en el array hay un id producto en la posicion pr
            unset($arrCarrito[$i]);  // eliminamos el elemento del array que se encuentre en la posicion pr
          }
        }
        //sort($arrCarrito);
        $_SESSION['arrCarrito'] = $arrCarrito; // se pasan los diatos de de la variable, a session-carrito
        unset($arrCarrito);
        foreach ($_SESSION['arrCarrito'] as $pro) {//por cada vuelta de la lista se pasan los items a $pro 
          $cantCarrito += $pro['cantidad']; // y se suma la cantidad de $pro-cantidad y se guardan en una variable
          $subtotal += $pro['cantidad'] * $pro['precio'];
        }
        if ($option === 1) {
          $htmlCarrito = '';
          foreach ($_SESSION['arrCarrito'] as $pro) {//por cada vuelta de la lista se pasan los items a $pro 
            $cantCarrito += $pro['cantidad']; // y se suma la cantidad de $pro-cantidad y se guardan en una variable
            $htmlCarrito .= html_producto_carrito($pro);
          }
          $arrResponse = array('status' => true,
            'msg' => '!Producto Eliminado¡',
            'cantCarrito' => $cantCarrito,
            'htmlCarrito' => $htmlCarrito,
            'subTotal' => formatMoney($subtotal),
            'total' => formatMoney($subtotal)
          );
        } elseif ($option === 2) {
          $arrResponse = array('status' => true,
            'msg' => '!Producto Eliminado¡',
            'cantCarrito' => $cantCarrito,
            'subTotal' => formatMoney($subtotal),
            'total' => formatMoney($subtotal));
        }
      } else {
        $arrResponse = array('status' => false, 'msg' => '!Darto incorrecto¡');
      }
      exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }
  }

  /* FAVORITOS  ----------------------------------------------------------------------------------------------------------------------- */

  public function addFavorito() {
    if ($_POST) {
      $idprod = intval($_POST['id']);
      $idpers = intval($_SESSION['idUser']);

      $request = $this->model->addFav($idprod, $idpers);
      if ($request === 'ok') {
        $arrResponse = array('status' => true, 'msg' => 'ok');
      } else if ($request === 'existe') {
        $arrResponse = array('status' => true, 'msg' => 'Guardado anteriormente');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'favorito no guardado');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }

  public function delFavorito() {
    if ($_POST) {
      $idprod = intval($_POST['id']);
      $idpers = intval($_SESSION['idUser']);

      $request = $this->model->delFav($idprod, $idpers);

      if ($request === 'ok') {
        $arrResponse = array('status' => true, 'msg' => 'ok');
      } else if ($request === 'inexistente') {
        $arrResponse = array('status' => true, 'msg' => 'ya se habia borrado');
      } else {
        $arrResponse = array('status' => false, 'msg' => 'favorito no borrado');
      }
      echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
    }
  }
}
