<?php
declare(strict_types=1);
class Redireccion extends Controllers {

    public function __construct() {
       // if (empty($_SESSION)) {
       //     session_start();
       // }
        parent::__construct();
    }

    public function Redireccion() {
        //ejecuta el contenido del archivo home
        //echo 'Mensaje desde el controlador home';
        $data['page_name'] = 'ERROR 404';
        //$empresa = $_SESSION['info_empresa'];
        //$data['page_tag'] = $empresa['nombre_comercial'];
        //$data['page_title'] = $data['page_name'] ;
        //$data['logo_desktop'] = $empresa['url_logoMenu'];
        //$data['shortcut_icon'] = $empresa['url_shortcutIcon'];
        $this->views->getView("Redireccion");
    }

}
