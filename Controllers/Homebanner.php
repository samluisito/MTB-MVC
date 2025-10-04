<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Homebanner extends Controllers
{
    private int $idModul = 4;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function homebanner($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Configuracion Home banner';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = [
            "vadmin/libs/choices.js/css/choices.min.css",
            "plugins/datatables/css/datatables.min.css",
            "plugins/cropper/css/cropper.min.css",
        ];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "vadmin/libs/choices.js/js/choices.min.js",
            "plugins/datatables/js/datatables.min.js",
            "plugins/cropper/js/cropper.min.js",
            "plugins/tinymce/tinymce.min.js",
            "js/functions_config_home.js"
        ];

        $this->views->getView("Homebanner", $data);
    }

    public function setBanner()
    {
        if (!$_POST) {
            exit();
        }

        if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) {
            $arrResponse = ["status" => false, "msg" => "Datos incompletos"];
        } else {
            $idUnico = uniqid();
            $intId = intval($_POST['idBanner']);
            $strNombre = strClean($_POST['txtNombre']);
            $strDescripcion = strClean($_POST['txtDescripcion']);
            $strListTpo = strClean($_POST['listTpo']);
            $intListItem = intval($_POST['listItem']);
            $intStatus = intval($_POST['listStatus']);

            $foto_actual = $_POST['foto_actual'];
            $foto_remove = $_POST['foto_remove'];
            $nombre_foto = $_POST['foto_blob_name'] ?? '';
            $type = isset($_POST['foto_blob_type']) && $_POST['foto_blob_type'] ? explode('/', strClean($_POST['foto_blob_type']))[1] : '';
            $type = $type === 'jpeg' ? 'jpg' : $type;

            $foto = $_FILES['foto'] ?? null;

            $ruta_completa = $this->model->selectUrlItem($strListTpo, $intListItem, 'ruta');
            $nombre_img_base = $this->model->selectUrlItem($strListTpo, $intListItem, 'nombre');

            // Logic to determine image name based on state
            $imgNombre = 'banner.png'; // default
            if ($nombre_foto && $nombre_foto !== $foto_actual && $foto_remove) { // Updated
                $imgNombre = 'banner_' . $nombre_img_base . '-' . $idUnico . '.' . $type;
            } elseif ($nombre_foto && !$foto_actual) { // New
                $imgNombre = 'banner_' . $nombre_img_base . '-' . $idUnico . '.' . $type;
            } elseif ($foto_actual && !$foto_remove) { // Unchanged
                $imgNombre = $foto_actual;
            }

            if ($intId == 0) {
                $request = $this->model->insertBanner($strNombre, $strDescripcion, $imgNombre, $strListTpo, $intListItem, $ruta_completa, $intStatus);
            } else {
                $request = $this->model->updateBanner($intId, $strNombre, $strDescripcion, $imgNombre, $strListTpo, $intListItem, $ruta_completa, $intStatus);
            }

            if ($request > 0) {
                if ($foto && $foto['error'] === UPLOAD_ERR_OK) {
                    uploadImage($foto, $imgNombre);
                    // Thumbnail generation logic would go here
                }
                $arrResponse = ['status' => true, 'msg' => 'Datos guardados correctamente.'];
            } elseif ($request === 'exist') {
                $arrResponse = ['status' => false, 'msg' => 'Atención: El Banner ya existe.'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No es posible guardar el Banner.'];
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getBanners()
    {
        $arrData = $this->model->selectBanners();
        foreach ($arrData as &$item) {
            $id = $item['idbanner'];
            $item['img'] = $item['img']
                ? "<img class='minlistprod_img' src='" . DIR_IMAGEN . "thumb_3_{$item["img"]}'>"
                : "<img class='minlistprod_img' src='" . DIR_MEDIA . "images/producto_sin_foto.png'>";

            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1
                ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'><i class='fa fa-power-off'></i></button>"
                : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado'><i class='fa fa-power-off'></i></button>";
            if (($_SESSION['userPermiso'][$this->idModul]['eliminar'] ?? 0) == 1) {
                $opciones .= $this->model->bannerEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar'><i class='fas fa-trash-alt'></i></button>";
            }
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getBanner(int $id)
    {
        if ($id > 0) {
            $arrData = $this->model->selectBanner($id);
            if (empty($arrData)) {
                $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados'];
            } else {
                $arrData['url_img'] = ($arrData['img'] == 'banner.png')
                    ? DIR_MEDIA . 'images/' . $arrData['img']
                    : DIR_IMAGEN . $arrData['img'];
                $arrResponse = ['status' => true, 'data' => $arrData];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    // Other methods would be refactored similarly...
}