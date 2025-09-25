<?php

declare(strict_types=1);

trait TCategoria {

    private $con;

    /* [CATEGORIAS]============================================================================================================================ */

    private function ordanarImagenesBanner($imagenes, $dispositivo) {// funcion interna para ordenar imagenes
        foreach ($imagenes as $i => $imagen) {
            if ($imagenes[$i]['img'] === 'banner.png') {
                $imagenes[$i]['url_img'] = DIR_MEDIA . 'images/' . $imagen['img']; //url Img portada
            } else {
                $imagenes[$i]['url_img'] = $dispositivo === 'mobile' ? DIR_IMAGEN . 'thumb_2_' . $imagen['img'] :
                        ($dispositivo === 'tablet' ? DIR_IMAGEN . 'thumb_1_' . $imagen['img'] : DIR_IMAGEN . $imagen['img']);
            }
        }
        return $imagenes;
    }

    function getBannerT($dispositivo) {
        $this->con = new Mysql();
        $request = $this->con->select_all("SELECT nombre, descripcion, img, ruta FROM home_banner WHERE status = 1 AND img != 'banner.png'");
        return count($request) > 0 ? $this->ordanarImagenesBanner($request, $dispositivo) : '';
    }

    function getMetaCategoriaT(string $ruta) {
        $this->con = new Mysql();
        $this->strRuta = $ruta;
        $request = $this->con->select("SELECT idcategoria, nombre, descripcion, img , ruta , tags FROM producto_categoria WHERE ruta = '{$this->strRuta}'");

        $request['url_img'] = $request['img'] == 'portada_categoria.png' ?
                DIR_MEDIA . $request['img'] :
                DIR_IMAGEN . $request['img'];

        return $request;
    }

    function getCategoriasFooterT() {
        $this->con = new Mysql();
        return $this->con->select_all("SELECT nombre, ruta FROM producto_categoria WHERE status = 1 LIMIT 4");
    }

    function getCategoriasMenuTiendaH() {
        $this->con = new Mysql();
        $arrData = array();
        $cats = $this->con->select_all("SELECT c.idcategoria, c.nombre, c.ruta , COUNT(p.categoriaid) AS cantidad 
      FROM producto p INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
      WHERE padre_cat_id IS NULL AND c.status = 1 GROUP BY c.nombre");
        foreach ($cats as $cat) {
            $cat['nombre'] = strtoupper($cat['nombre']);
            array_push($arrData, $cat);
            $subCats = $this->con->select_all("SELECT c.nombre, c.ruta , COUNT(p.categoriaid) AS cantidad 
        FROM producto p INNER JOIN producto_categoria c ON p.categoriaid = c.idcategoria 
        WHERE padre_cat_id = {$cat['idcategoria']} AND c.status = 1 GROUP BY c.nombre");
            if (count($subCats) > 0) {
                foreach ($subCats as $subCat) {
                    $subCat['nombre'] = '· ' . ucwords(strtolower($subCat['nombre']));
                    array_push($arrData, $subCat);
                }
            }
        }
        return $arrData;
    }

    /* ============================================================================================================================ */
}
