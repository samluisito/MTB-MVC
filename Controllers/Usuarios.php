<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Usuarios extends Controllers
{
    private int $idModul = 1;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function usuarios($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Usuarios';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_usuarios.js"
        ];

        $this->views->getView("Usuarios", $data);
    }

    public function perfil($params)
    {
        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Perfil';
        $data['page_title'] = 'Perfil de Usuario';
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
            "js/functions_usuarios.js"
        ];

        $this->views->getView("Perfil", $data);
    }

    public function setUsuario()
    {
        if (!$_POST) {
            exit();
        }

        if (empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtEmail']) || empty($_POST['listRolid'])) {
            $arrResponse = ["status" => false, "msg" => 'Datos incorrectos'];
        } else {
            $idUser = intval($_POST['idUsuario']);
            $strIdentificacion = intval($_POST['txtIdentificacion']);
            $strNombre = ucwords(strClean($_POST['txtNombre']));
            $strApellido = ucwords(strClean($_POST['txtApellido']));
            $strEmail = strtolower(strClean($_POST['txtEmail']));
            $intTelefono = intval($_POST['txtTelefono']);
            $intTipoRolId = intval($_POST['listRolid']);
            $intStatus = intval($_POST['listStatus']);

            if ($idUser == 0) {
                $option = 'nuevo';
                $strPassword = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
                $strPasswordEncript = hash("SHA256", $strPassword);
                $request_user = $this->model->insertUsuario($strIdentificacion, $strNombre, $strApellido, $intTelefono, $strEmail, $strPasswordEncript, $intTipoRolId, $intStatus);
            } else {
                $option = 'actualizado';
                $strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);
                $request_user = $this->model->updateUsuario($idUser, $strIdentificacion, $strNombre, $strApellido, $intTelefono, $strEmail, $strPassword, $intTipoRolId, $intStatus);
            }

            if ($request_user > 0) {
                $arrResponse = ['status' => true, 'msg' => 'Datos guardados correctamente'];
            } elseif ($request_user == 'exist') {
                $arrResponse = ['status' => false, 'msg' => '!ATENCIÓN! El Email o la Identificación ya existen'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No se pudo guardar el usuario'];
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getUsuarios()
    {
        $arrData = $this->model->selectListUsuarios();
        foreach ($arrData as &$item) {
            $id = $item['idpersona'];
            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1 ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado'><i class='fa fa-power-off'></i></button>" : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->usuarioEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getUser(int $idpersona)
    {
        if ($idpersona > 0) {
            $arrData = $this->model->selectUser($idpersona);
            $arrResponse = empty($arrData) ? ['status' => false, 'msg' => 'Datos no encontrados'] : ['status' => true, 'data' => $arrData];
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}