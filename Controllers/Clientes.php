<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Clientes extends Controllers
{
    private int $idModul = 3;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function clientes($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 0) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Clientes';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data["page_css"] = ["plugins/datatables/css/datatables.min.css"];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "js/functions_clientes.js"
        ];

        $this->views->getView("Clientes", $data);
    }

    public function setCliente()
    {
        if (!$_POST) {
            return;
        }

        if (
            empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) ||
            empty($_POST['txtApellido']) || empty($_POST['txtEmail']) ||
            empty($_POST['txtTelefono']) || empty($_POST['texNit']) ||
            empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal'])
        ) {
            $arrResponse = ["status" => false, "msg" => 'Revisa el formulario, faltan datos o están incorrectos'];
        } else {
            $idUser = intval($_POST['idUsuario']);
            $strIdentificacion = strClean($_POST['txtIdentificacion']);
            $strNombre = ucwords(strClean($_POST['txtNombre']));
            $strApellido = ucwords(strClean($_POST['txtApellido']));
            $strEmail = strtolower(strClean($_POST['txtEmail']));
            $intTelefono = intval($_POST['txtTelefono']);
            $intNit = strClean($_POST['texNit']);
            $srtNombreFiscal = strClean($_POST['txtNombreFiscal']);
            $srtDirFiscal = strClean($_POST['txtDirFiscal']);
            $idTpoRol = 2;

            if ($idUser == 0) {
                $option = 'nuevo';
                $strPassword = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
                $strPasswordEncript = hash("SHA256", $strPassword);

                $request_user = $this->model->insertCliente(
                    $strIdentificacion,
                    $strNombre,
                    $strApellido,
                    $intTelefono,
                    $strEmail,
                    $strPasswordEncript,
                    $idTpoRol,
                    $intNit,
                    $srtNombreFiscal,
                    $srtDirFiscal
                );

                $empresa = $_SESSION['info_empresa'];
                $arrDataEmail = [
                    'empresa' => $empresa,
                    'nombreUsuario' => $strNombre . ' ' . $strApellido,
                    'email' => $strEmail,
                    'asunto' => 'Bienvenido a ' . $empresa['nombre_comercial'],
                    'password' => $strPassword,
                ];
                $bodyMail = getFile("Template/Email/email_bienvenida", $arrDataEmail);
                sendEmail($arrDataEmail, $bodyMail);
            } else {
                $option = 'actualizado';
                $strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);
                $request_user = $this->model->updateCliente(
                    $idUser,
                    $strIdentificacion,
                    $strNombre,
                    $strApellido,
                    $intTelefono,
                    $strEmail,
                    $strPassword,
                    $intNit,
                    $srtNombreFiscal,
                    $srtDirFiscal
                );
            }

            if ($request_user > 0) {
                $arrResponse = ($option == 'nuevo')
                    ? ['status' => true, 'msg' => $strNombre . ' ya es un nuevo cliente']
                    : ['status' => true, 'msg' => 'Los datos de ' . $strNombre . ' han sido actualizados'];
            } elseif ($request_user == 'exist') {
                $arrResponse = ['status' => false, 'msg' => '!ATENCIÓN! El Email o la Identificación ya existen'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No se pudo guardar el usuario, intente más tarde'];
            }
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function getClientes()
    {
        $arrData = $this->model->selectClientes();
        foreach ($arrData as &$item) {
            $id = $item['idpersona'];
            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1
                ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado' type='button'><i class='fa fa-power-off'></i></button>"
                : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado' type='button'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->cteEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function getCte(int $idpersona)
    {
        if ($idpersona > 0) {
            $arrData = $this->model->selectCliente($idpersona);
            $arrResponse = empty($arrData)
                ? ['status' => false, 'msg' => 'Datos no encontrados']
                : ['status' => true, 'data' => $arrData];
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function delCliente()
    {
        if ($_POST) {
            $intIdUser = intval($_POST['idUsuario']);
            $requestDel = $this->model->deleteCliente($intIdUser);
            $arrResponse = $requestDel
                ? ['status' => true, 'msg' => 'Se ha eliminado el Usuario']
                : ['status' => false, 'msg' => 'Error al eliminar el Usuario'];
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
}