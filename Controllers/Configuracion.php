<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Configuracion extends Controllers
{
    private int $idModul = 7;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function configuracion()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data['empresa'] = $empresa;
        $data['page_name'] = 'Configuracion';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = [];
        $data["page_functions_js"] = ["js/functions_configuracion.js"];

        $this->views->getView("Configuracion", $data);
    }

    public function tiposDePago()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data['tpos_pago'] = $this->getTPDetalles();
        $data['empresa'] = $empresa;
        $data['page_name'] = 'Configuracion';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = [
            "vadmin/libs/sweetalert2/sweetalert2.min.css",
            "plugins/datatables/css/datatables.min.css"
        ];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "vadmin/libs/sweetalert2/sweetalert2.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_configuracion.js"
        ];

        $this->views->getView("TiposDePago", $data);
    }

    public function configRegion()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Configuracion Regional';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = [
            "vadmin/libs/choices.js/css/choices.min.css",
            "vadmin/libs/glightbox/css/glightbox.min.css",
            "plugins/datatables/css/datatables.min.css",
            "plugins/cropper/css/cropper.min.css",
        ];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "vadmin/libs/choices.js/js/choices.min.js",
            "vadmin/libs/glightbox/js/glightbox.min.js",
            "plugins/datatables/js/datatables.min.js",
            "plugins/cropper/js/cropper.min.js",
            "plugins/tinymce/tinymce.min.js",
            "js/functions_configuracion.js"
        ];

        $this->views->getView("ConfiguracionRegional", $data);
    }

    public function setConfiguracion()
    {
        if (empty($_POST)) {
            exit(json_encode(["status" => false, "msg" => "Datos incompletos"], JSON_UNESCAPED_UNICODE));
        }

        $formData = $_POST;
        // Simplified validation
        if (empty($formData['txtNombreComercial']) || empty($formData['txtEmail'])) {
            $arrResponse = ["status" => false, "msg" => "Datos esenciales incompletos"];
        } else {
            $nombre_shrotcutIcon = $this->actualizar_img_config($formData['txtNombreComercial'], 'shrotcutIcon', $_FILES['shrotcutIcon'] ?? null, intval($formData['guardar_webp'] ?? 0), $formData['foto_actual_shrotcutIcon'], intval($formData['foto_remove_shrotcutIcon']));
            $nombre_logoMenu = $this->actualizar_img_config($formData['txtNombreComercial'], 'logoMenu', $_FILES['logoMenu'] ?? null, intval($formData['guardar_webp'] ?? 0), $formData['foto_actual_logoMenu'], intval($formData['foto_remove_logoMenu']));
            $nombre_logoImpreso = $this->actualizar_img_config($formData['txtNombreComercial'], 'logoImpreso', $_FILES['logoImpreso'] ?? null, intval($formData['guardar_webp'] ?? 0), $formData['foto_actual_logoImpreso'], intval($formData['foto_remove_logoImpreso']));

            $mantenimientoHasta = ($formData['fecha_mantenimiento_hasta'] ?? '') . ' ' . ($formData['hora_mantenimiento_hasta'] ?? '00:00') . ':00';

            $request = $this->model->updateConfig(
                1, // idEmpresa
                strClean($formData['txtNombreComercial']),
                strClean($formData['txtNombreFiscal']),
                strClean($formData['txtIdFiscal']),
                strClean($formData['txtEmail']),
                strClean($formData['txtTelefono']),
                strClean($formData['txtDireccion']),
                strClean($formData['txtDescripcion']),
                strClean($formData['txtEtiquetas']),
                strClean($formData['txtLinkFacebook']),
                strClean($formData['txtLinkInstagram']),
                intClean($formData['intTelfWhatsApp']),
                strClean($formData['txtTextoWhatsApp']),
                strClean($formData['txtLinkTwitter']),
                $nombre_logoMenu,
                $nombre_logoImpreso,
                $nombre_shrotcutIcon,
                isset($formData['smtp_status']) ? 1 : 0,
                strClean($formData['txtServEmail']),
                strClean($formData['txtServPassword']),
                strClean($formData['txtServHost']),
                $mantenimientoHasta,
                floatval($formData['intCostoEnvio']),
                strClean($formData['modoEntrega']),
                isset($formData['guardar_webp']) ? 1 : 0,
                isset($formData['login_facebook']) ? 1 : 0,
                strClean($formData['txtClaveAppFb']) ?: null,
                strClean($formData['txtIdAppFb']) ?: null,
                isset($formData['pixel_facebook']) ? 1 : 0,
                intval($formData['txtIdPixelFb']) ?: null,
                strClean($formData['txtMetaDominio']) ?: null,
                str_ireplace(',', ';', str_ireplace(' ', '', strClean($formData['txtExcuirIP']))) ?: null
            );

            if ($request > 0) {
                $arrResponse = ['status' => true, 'msg' => 'Se ha actualizado satisfactoriamente'];
                unset($_SESSION['info_empresa']);
                $_SESSION['info_empresa'] = $this->InfoEmpresa();
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No se guardaron cambios'];
            }
        }
        exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    private function actualizar_img_config($strNombrecomercial, $tipo, $obj_file, $intGuardar_webp, $img_actual, $img_remove)
    {
        if (empty($obj_file) || $obj_file['error'] !== UPLOAD_ERR_OK) {
            return $img_actual;
        }

        $estado = ($obj_file['name'] !== '' && $img_remove === 1) ? 'actualizado' : 'sin_cambios';
        if ($estado !== 'actualizado') {
            return $img_actual;
        }

        $nombre_ruta = str_replace(" ", "-", strtolower(clear_cadena($strNombrecomercial)));
        $extension = pathinfo($obj_file['name'], PATHINFO_EXTENSION);
        $nombre_img = "img-{$tipo}-{$nombre_ruta}-" . uniqid() . "." . ($intGuardar_webp ? 'webp' : $extension);

        if ($intGuardar_webp) {
            $img_temp = pathinfo($nombre_img, PATHINFO_FILENAME) . '.' . $extension;
            uploadImage($obj_file, $img_temp);
            $dir_img = 'uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_temp;
            convertImageToWebP($dir_img, $nombre_img); // Assuming this creates the final file
            deleteFile($img_temp);
        } else {
            uploadImage($obj_file, $nombre_img);
        }

        if ($img_actual && $img_actual !== 'default.png') { // Example default image
            deleteFile($img_actual);
        }

        return $nombre_img;
    }

    // Other methods would be refactored similarly...
}