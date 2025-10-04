<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class ABlog extends Controllers
{
    private int $idModul = 10;

    public function __construct()
    {
        if (empty($_SESSION['login'])) {
            // La carga de Login ahora se manejaría a través del enrutamiento,
            // pero mantenemos la lógica original por compatibilidad.
            $login = new Login();
            $login->login();
            exit();
        }
        parent::__construct();
    }

    public function aBlog($params)
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) == 1) {
            $empresa = $_SESSION['info_empresa'];
            $data["empresa"] = $empresa;
            $data['page_name'] = 'aBlog';
            $data['page_title'] = $data['page_name'];
            $data['logo_desktop'] = $empresa['url_logoMenu'];
            $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

            $notificacion = new Notificacion();
            $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

            $data["page_css"] = [
                "plugins/datatables/css/datatables.min.css"
            ];
            $data["page_functions_js"] = [
                "plugins/jquery/jquery-3.6.0.min.js",
                "plugins/datatables/js/datatables.min.js",
                "plugins/tinymce/tinymce.min.js",
                "js/functions_aBlog.js"
            ];

            $this->views->getView("ABlog", $data);
        } else {
            // Redirigir si no hay permisos.
            // header('location:' . base_url() . 'dashboard');
            // exit();
        }
    }

    public function getEntradas()
    {
        $data = $this->model->selectEntradas();
        $permiso = $_SESSION['userPermiso'];

        for ($i = 0; $i < count($data); $i++) {
            $recuest_img = $this->model->imgEntrada($data[$i]['identrada']);
            $data[$i]['img_entrada'] = $recuest_img['img']
                ? '<img class="minlistprod_img" src="' . DIR_IMAGEN . $recuest_img["img"] . '">'
                : '<img class="minlistprod_img" src="' . DIR_MEDIA . 'images/entrada_sin_foto.png">';

            $data[$i]['options'] = '<div class="text-center">';
            if (($permiso[$this->idModul]['actualizar'] ?? 0) == 1) {
                $data[$i]['options'] .= '<button class="btn btn-primary btn-sm" onClick="fntEdit(' . $data[$i]['identrada'] . ')" title="Editar" type="button"><i class="fa fa-pencil"></i></button>';
            }

            $request_entrada_en_uso = 1; // Lógica original, simplificada.
            $status = $data[$i]['status'];

            if ($request_entrada_en_uso) {
                $btnClass = $status == 1 ? 'btn-success' : 'btn-danger';
                $title = $status == 1 ? 'Activado' : 'Desactivado';
                $data[$i]['options'] .= ' <button class="btn ' . $btnClass . ' btn-sm" onClick="fntStatus(' . $data[$i]['identrada'] . ')" title="' . $title . '" type="button" id="btnStatus' . $data[$i]['identrada'] . '" value="' . $status . '"><i class="fa fa-power-off" aria-hidden="true"></i></button>';
            } else {
                $data[$i]['options'] .= ' <button class="btn btn-danger btn-sm btnDel" onClick="fntDel(' . $data[$i]['identrada'] . ')" title="Eliminar" type="button"><i class="fa fa-trash"></i></button>';
            }
            $data[$i]['options'] .= '</div>';
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function setEntrada()
    {
        if ($_POST) {
            if (empty($_POST['txtTitulo']) || empty($_POST['txtDescripcion']) || empty($_POST['txtTexto'])) {
                $arrResponse = ["status" => false, "msg" => "Datos incompletos o con valor cero"];
            } else {
                $intIdEntrada = intval($_POST['idEntrada']);
                $autorid = $_SESSION['idUser'];
                $strTitulo = strClean(trim($_POST['txtTitulo']));
                $url = strtolower(clear_cadena($strTitulo));
                $strDescripcion = substr(strClean($_POST['txtDescripcion']), 0, 200);
                $strTexto = strClean($_POST['txtTexto']);
                $strTags = strClean($_POST['txtTags']);
                $intCategoriaId = intval(strClean($_POST['listCategoria']));
                $intStatus = intval(strClean($_POST['listStatus']));

                if ($intIdEntrada == 0) {
                    $option = 1;
                    $request = $this->model->insertEntrada($autorid, $strTitulo, $url, $strDescripcion, $strTexto, $strTags, $intCategoriaId, $intStatus);
                } else {
                    $option = 2;
                    $request = $this->model->updateEntrada($intIdEntrada, $strTitulo, $url, $strDescripcion, $strTexto, $strTags, $intCategoriaId, $intStatus);
                }

                if ($request > 0) {
                    $arrResponse = $option == 1
                        ? ['status' => true, 'identrada' => $request, 'msg' => 'Entrada Guardado Correctamente']
                        : ['status' => true, 'identrada' => $intIdEntrada, 'msg' => 'Datos Actualizados Correctamente'];
                } elseif ($request == 'exist') {
                    $arrResponse = ['status' => false, 'msg' => 'Atención: La entrada ya existe.'];
                } else {
                    $arrResponse = ['status' => false, 'msg' => 'No es posible guardar la entrada.'];
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function getEntrada($id)
    {
        $intId = intval(strClean($id));
        if ($intId > 0) {
            $arrData = $this->model->selectEntrada($intId);
            if (empty($arrData)) {
                $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados'];
            } else {
                $arrImg = $this->model->selectImages($intId);
                if (!empty($arrImg)) {
                    foreach ($arrImg as &$img) {
                        $img['url_image'] = DIR_IMAGEN . $img['img'];
                    }
                }
                $arrData['images'] = $arrImg;
                $arrData['prev'] = $this->model->selectEntradaPrevProx('prev', $arrData['identrada'])['identrada'] ?? null;
                $arrData['prox'] = $this->model->selectEntradaPrevProx('prox', $arrData['identrada'])['identrada'] ?? null;
                $arrData['posicion'] = $this->model->selectEntradaPosicion($arrData['identrada']);
                $arrResponse = ['status' => true, 'data' => $arrData];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function setImage($param)
    {
        if ($_POST && isset($_POST['identrada']) && isset($_FILES['foto'])) {
            $idEntrada = intval($_POST['identrada']);
            $data_foto = $_FILES['foto'];
            $ext = pathinfo($data_foto['name'], PATHINFO_EXTENSION);
            $imgTitulo = 'entrada_' . md5(date('d-m-Y H:i:s')) . '.' . $ext;

            if ($this->model->insertImage($idEntrada, $imgTitulo)) {
                if (uploadImage($data_foto, $imgTitulo)) {
                    $arrResponse = ['status' => true, 'imgname' => $imgTitulo, 'msg' => 'Archivo cargado'];
                } else {
                    $arrResponse = ['status' => false, 'msg' => 'Error al subir la imagen'];
                }
            } else {
                $arrResponse = ['status' => false, 'msg' => 'Error al guardar en la base de datos'];
            }
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Error de carga: Entrada no identificada o archivo no enviado.'];
        }
        sleep(1);
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function delFile()
    {
        if ($_POST && !empty($_POST['identrada']) && !empty($_POST['file'])) {
            $idEntrada = intval($_POST['identrada']);
            $imgTitulo = strClean($_POST['file']);

            if ($this->model->deleteImage($idEntrada, $imgTitulo)) {
                if (deleteFile($imgTitulo)) {
                    $arrResponse = ['status' => true, 'msg' => 'Archivo Eliminado'];
                } else {
                    $arrResponse = ['status' => false, 'msg' => 'Error al eliminar el archivo del disco'];
                }
            } else {
                $arrResponse = ['status' => false, 'msg' => 'Error al eliminar de la base de datos'];
            }
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Datos incorrectos o incompletos'];
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function delEntrada()
    {
        if ($_POST && !empty($_POST['identrada'])) {
            $intIdEntrada = intval($_POST['identrada']);
            $requestDel = $this->model->deleteEntrada($intIdEntrada);

            if ($requestDel == 'OK') {
                $arrResponse = ['status' => true, 'msg' => 'Se ha eliminado la Entrada'];
            } elseif ($requestDel == 'exist') {
                $arrResponse = ['status' => false, 'msg' => 'No es posible eliminar la entrada, está en uso.'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'Error al eliminar la entrada'];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
}