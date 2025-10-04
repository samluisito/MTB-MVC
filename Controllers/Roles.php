<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Roles extends Controllers
{
    private int $idModul = 2;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function roles($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Rol_Usuario';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_roles.js"
        ];

        $this->views->getView("Roles", $data);
    }

    public function getRoles()
    {
        $arrData = $this->model->selectRoles();
        foreach ($arrData as &$item) {
            $id = $item['idrol'];
            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntPermisos({$id})' title='Ver'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1
                ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'><i class='fa fa-power-off'></i></button>"
                : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->rolEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getRol(int $idRol)
    {
        if ($idRol > 0) {
            $arrData = $this->model->selectRol($idRol);
            $arrResponse = empty($arrData)
                ? ['status' => false, 'msg' => 'Datos no encontrados']
                : ['status' => true, 'data' => $arrData];
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function setRol()
    {
        $intIdRol = intval($_POST['idRol']);
        $strRol = strClean($_POST['txtNombre']);
        $strDescripcion = strClean($_POST['txtDescripcion']);
        $intStatus = intval($_POST['listStatus']);

        if ($intIdRol == 0) {
            $request_rol = $this->model->insertRol($strRol, $strDescripcion, $intStatus);
            $option = 1;
        } else {
            $request_rol = $this->model->updateRol($intIdRol, $strRol, $strDescripcion, $intStatus);
            $option = 2;
        }

        if ($request_rol > 0) {
            $arrResponse = ($option == 1)
                ? ['status' => true, 'msg' => 'Datos Guardados Correctamente']
                : ['status' => true, 'msg' => 'Datos Actualizados Correctamente'];
        } elseif ($request_rol == 'exist') {
            $arrResponse = ['status' => false, 'msg' => 'Atención: El Rol ya existe.'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'No es posible guardar el rol.'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function delRol()
    {
        if ($_POST) {
            $intIdRol = intval($_POST['idrol']);
            $requestDel = $this->model->deleteRol($intIdRol);
            if ($requestDel == 'OK') {
                $arrResponse = ['status' => true, 'msg' => 'Se ha eliminado el Rol'];
            } elseif ($requestDel == 'exist') {
                $arrResponse = ['status' => false, 'msg' => 'No es posible eliminar el rol, está en uso.'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'Error al eliminar el rol'];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
}