<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Categorias extends Controllers
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

    public function categorias()
    {
        if (($_SESSION['userPermiso'][$this->idModul]['ver'] ?? 0) != 1) {
            header('location:' . base_url() . 'dashboard');
            exit();
        }

        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;
        $data['page_name'] = 'Categorias de Productos';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $notificacion = new Notificacion();
        $data['notificaciones'] = $notificacion->getNotificacionesNoLeidasMenu();

        $data['categorias_facebook_n1'] = $this->getArrayCatFbGgNivel('facebook');
        $data['categorias_google_n1'] = $this->getArrayCatFbGgNivel('google');

        $data["page_css"] = [
            "plugins/datatables/css/datatables.min.css",
            "plugins/cropper/css/cropper.min.css"
        ];
        $data["page_functions_js"] = [
            "plugins/jquery/jquery-3.6.0.min.js",
            "plugins/datatables/js/datatables.min.js",
            "plugins/cropper/js/cropper.min.js",
            "js/functions_categorias.js"
        ];

        $this->views->getView("Categorias", $data);
    }

    public function setCategoria()
    {
        if (empty($_POST)) {
            exit(json_encode(["status" => false, "msg" => "Datos incompletos"], JSON_UNESCAPED_UNICODE));
        }

        if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion'])) {
            $arrResponse = ["status" => false, "msg" => "Datos incompletos"];
        } else {
            $idUnico = uniqid();
            $intIdCat = intval($_POST['idCategoria']) ?: 0;
            $intIdCatPadre = intval($_POST['idCatPadre']) ?: null;
            $strCat = strClean($_POST['txtNombre']);
            $strDescripcion = strClean($_POST['txtDescripcion']);
            $strTags = strClean($_POST['txtTags']);
            $intCatFb = intval($_POST['listCatFB']);
            $intCatGg = intval($_POST['listCatGoogle']);
            $intStatus = intval($_POST['listStatus']);

            $foto_actual = $_POST['foto_actual'];
            $foto_remove = intval($_POST['foto_remove']);
            $nombre_foto = $_POST['foto_blob_name'] === '' ? '' : $_POST['foto_blob_name'];
            $type = $_POST['foto_blob_type'] === '' ? '' : explode('/', strClean($_POST['foto_blob_type']))[1];
            $type = $type === 'jpeg' ? 'jpg' : $type;

            $foto = $_FILES['foto'];
            $img_portada = "portada_categoria.png";
            $ruta = str_replace(" ", "-", strtolower(clear_cadena($strCat)));
            $estado_foto = estadoFoto($nombre_foto, $foto_actual, $foto_remove);

            $img_portada = match ($estado_foto) {
                'nueva', 'actualizada' => 'img-' . $ruta . '-' . $idUnico . '.' . $type,
                'sin_mov' => $foto_actual,
                default => 'portada_categoria.png',
            };

            if ($intIdCat === 0) {
                $request_cat = $this->model->insertCategoria($intIdCatPadre, $strCat, $strDescripcion, $intCatFb, $intCatGg, $img_portada, $strTags, $ruta, $intStatus);
                $option = 'new';
            } else {
                $request_cat = $this->model->updateCategoria($intIdCat, $intIdCatPadre, $strCat, $strDescripcion, $intCatFb, $intCatGg, $img_portada, $strTags, $ruta, $intStatus);
                $option = 'update';
            }

            if ($request_cat === 'e') {
                $arrResponse = ['status' => false, 'msg' => 'Atención: La categoría ya existe.'];
            } elseif ($request_cat > 0) {
                if ($option === 'new') {
                    if ($estado_foto === 'nueva') {
                        uploadImage($foto, $img_portada);
                    }
                    $arrResponse = ['status' => true, 'id' => $request_cat, 'nombre' => $strCat, 'msg' => "Se ha creado la categoría $strCat"];
                } else { // update
                    if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') {
                        uploadImage($foto, $img_portada);
                    }
                    if ($estado_foto === 'eliminada' || $estado_foto === 'actualizada') {
                        if ($foto_actual != 'portada_categoria.png') {
                            deleteFile($foto_actual);
                            deleteFile('thumb_1_' . $foto_actual);
                            deleteFile('thumb_2_' . $foto_actual);
                            deleteFile('thumb_3_' . $foto_actual);
                        }
                    }
                    $arrResponse = ['status' => true, 'id' => $intIdCat, 'nombre' => $strCat, 'msg' => "Categoría $strCat actualizada"];
                }

                if ($estado_foto === 'nueva' || $estado_foto === 'actualizada') {
                    $img_orig = './uploads/' . FILE_SISTEM_CLIENTE . '/' . $img_portada;
                    thumbImage($img_orig, '1_' . $img_portada, 720, 460);
                    thumbImage($img_orig, '2_' . $img_portada, 432, 276);
                    thumbImage($img_orig, '3_' . $img_portada, 144, 92);
                }
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No es posible guardar la categoría.'];
            }
        }
        exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    public function getCategorias()
    {
        $arrData = $this->model->selectCategorias();
        foreach ($arrData as &$item) {
            $item['img'] = ($item["img"] != 'portada_categoria.png')
                ? '<img class="minlistprod_img" src="' . DIR_IMAGEN . 'thumb_3_' . $item["img"] . '">'
                : '<img class="minlistprod_img" src="' . DIR_MEDIA . 'images/portada_categoria.png">';

            $id = $item['idcategoria'];
            $opciones = "<div class='text-center'>";
            $opciones .= "<button class='btn btn-secondary m-1' onClick='fntVer({$id})' title='Ver' type='button'><i class='fas fa-eye'></i></button>";
            $opciones .= ($_SESSION['userPermiso'][$this->idModul]['actualizar'] ?? 0) == 1 ? "<button class='btn btn-primary m-1' onClick='fntEdit({$id})' title='Editar' type='button'><i class='fas fa-edit'></i></button>" : '';
            $opciones .= $item['status'] == 1
                ? "<button class='btn btn-success m-1' onClick='fntStatus({$id})' title='Activado' type='button' id='btnStatus{$id}' value='1'><i class='fa fa-power-off'></i></button>"
                : "<button class='btn btn-danger m-1' onClick='fntStatus({$id})' title='Desactivado' type='button' id='btnStatus{$id}' value='0'><i class='fa fa-power-off'></i></button>";
            $opciones .= $this->model->categoriaEnUso($id) ? '' : "<button class='btn btn-danger m-1' onClick='fntDel({$id})' title='Eliminar' type='button'><i class='fas fa-trash-alt'></i></button>";
            $item['options'] = $opciones . "</div>";
            $item['status'] = $item['status'] == 1 ? "<span class='badge bg-success'>Activo</span>" : "<span class='badge bg-danger'>Inactivo</span>";
        }
        exit(json_encode($arrData, JSON_UNESCAPED_UNICODE));
    }

    public function get(int $id)
    {
        if ($id > 0) {
            $arrData = $this->model->selectCat($id);
            if (empty($arrData)) {
                $arrResponse = ['status' => false, 'msg' => 'Datos no encontrados'];
            } else {
                $arrData['url_img'] = ($arrData['img'] === 'portada_categoria.png')
                    ? DIR_MEDIA . 'images/' . $arrData['img']
                    : DIR_IMAGEN . $arrData['img'];

                $arrData['prev'] = $this->model->selectCatPrevProx('prev', $arrData['idcategoria'])['idcategoria'] ?? null;
                $arrData['prox'] = $this->model->selectCatPrevProx('prox', $arrData['idcategoria'])['idcategoria'] ?? null;
                $arrData['posicion'] = $this->model->selectCatPosicion($arrData['idcategoria']);
                $arrData['subCategorias'] = $this->model->selectAllSubCat($id);
                $arrResponse = ['status' => true, 'data' => $arrData];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        exit();
    }

    public function delCategoria()
    {
        if (empty($_POST)) {
            exit(json_encode(['status' => false, 'msg' => 'No hay datos'], JSON_UNESCAPED_UNICODE));
        }

        $intId = intval($_POST['id']);
        $imagen = $this->model->selectImgCategoria($intId);
        $img_del = true;

        if ($imagen && $imagen !== 'portada_categoria.png') {
            $del1 = deleteFile('thumb_1_' . $imagen);
            $del2 = deleteFile($imagen);
            $img_del = $del1 && $del2;
        }

        if ($img_del) {
            $arrResponse = $this->model->deleteCategoria($intId)
                ? ['status' => true, 'msg' => 'Categoría eliminada']
                : ['status' => false, 'msg' => 'Error al borrar la categoría'];
        } else {
            $arrResponse = ['status' => false, 'msg' => 'Error al borrar imágenes'];
        }
        exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    public function getSelectCategorias()
    {
        $htmlOption = "";
        $arrData = $this->model->selectCategorias();
        if (!empty($arrData)) {
            foreach ($arrData as $cat) {
                if ($cat['status'] == 1) {
                    $htmlOption .= "<option value='{$cat['idcategoria']}'>{$cat['nombre']}</option>";
                }
            }
        }
        exit($htmlOption);
    }

    public function getSelectCategoriasChoise()
    {
        $arrResponse = [];
        $arrCats = $this->model->selectCategorias();
        if (!empty($arrCats)) {
            foreach ($arrCats as $cat) {
                $arrResponse[] = ['value' => $cat['idcategoria'], 'label' => strtoupper($cat['nombre']), 'disabled' => $cat['status'] == 0];
                $arrSubCats = $this->model->selectCategorias($cat['idcategoria']);
                if (!empty($arrSubCats)) {
                    foreach ($arrSubCats as $subCat) {
                        $arrResponse[] = ['value' => $subCat['idcategoria'], 'label' => '- ' . ucwords($subCat['nombre']), 'disabled' => $subCat['status'] == 0];
                    }
                }
            }
        }
        exit(json_encode($arrResponse, JSON_UNESCAPED_UNICODE));
    }

    public function statusCategoriaChange()
    {
        if (isset($_GET['id']) && isset($_GET['intStatus'])) {
            $intId = intval($_GET['id']);
            $intStatus = intval($_GET['intStatus']);
            $requestStatus = $this->model->editCategoriaStatus($intId, $intStatus);

            if ($requestStatus == 'OK') {
                $arrResponse = ['status' => true, 'msg' => $intStatus === 1 ? 'Se ha desactivado el item' : 'Se ha activado el item'];
            } else {
                $arrResponse = ['status' => false, 'msg' => 'No es posible cambiar el estado del item'];
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
    }
}