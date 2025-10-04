<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Modulos extends Controllers
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

    public function modulos($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Modulos';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_modulos.js"
        ];

        $this->views->getView("Modulos", $data);
    }

    public function setModulo()
    {
        $intIdModulo = intval($_POST['idModulo']);
        $strTitulo = strClean($_POST['txtTitulo']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $intStatus = intval($_POST['listStatus']);

        if ($intIdModulo == 0) {
            $request_modulo = $this->model->insertModulo($strTitulo, $strDescripcion, $intStatus);
            $option = 1;
        } else {
            $request_modulo = $this->model->updateModulo($intIdModulo, $strTitulo, $strDescripcion, $intStatus);
            $option = 2;
        }

        if ($request_modulo > 0) {
            $arrResponse = ($option == 1)
                ? ['status' => true, 'msg' => 'Datos Guardados Correctamente']
                : ['status' => true, 'msg' => 'Datos Actualizados Correctamente'];
        } elseif ($request_modulo == 'exist') {
            $arrResponse = ['status' => false, 'msg' => 'Atención: El Módulo ya existe.'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'No es posible guardar el Módulo.'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getModulos()
    {
        $arrData = $this->model->selectModulos();
        foreach ($arrData as &$item) {
            $id = $item['idmodulo'];
            $opciones = "<div class='text-center'>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1
                ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'><i class='fa fa-power-off'></i></button>"
                : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->moduloEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getModulo(int $idModulo)
    {
        if ($idModulo > 0) {
            $arrData = $this->model->selectModulo($idModulo);
            $arrResponse = empty($arrData)
                ? ['status' => false, 'msg' => 'Datos no encontrados']
                : ['status' => true, 'data' => $arrData];
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function delModulo()
    {
        if ($_POST) {
            $intIdModulo = intval($_POST['idModulo']);
            $requestDel = $this->model->deleteModulo($intIdModulo);
            if ($requestDel == 'OK') {
                $arrResponse = ['status' => true, 'msg' => 'Se ha eliminado el Módulo'];
            } elseif ($requestDel == 'exist') {
                $arrResponse = ['status' => false, 'msg' => 'No es posible eliminar el Módulo'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'Error al eliminar el Módulo'];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function statusModulo()
    {
        if (isset($_GET['idModulo']) && isset($_GET['intStatus'])) {
            $intIdModulo = intval($_GET['idModulo']);
            $intStatus = intval($_GET['intStatus']);
            $requestStatus = $this->model->statusModulo($intIdModulo, $intStatus);

            if ($requestStatus == 'OK') {
                $arrResponse = ['status' => true, 'msg' => $intStatus == 1 ? 'Se ha desactivado el Módulo' : 'Se ha activado el Módulo'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No es posible cambiar el estado del Módulo'];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
}