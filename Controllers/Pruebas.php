<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Pruebas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function pruebas($params)
    {
        $empresa = $_SESSION['info_empresa'];
        $data["empresa"] = $empresa;

        $the_date = strtotime($empresa['fecha_mantenimiento_hasta']);

        $data['page_name'] = 'Dashboard';
        $data['page_title'] = $data['page_name'];
        $data['logo_desktop'] = $empresa['url_logoMenu'];
        $data['shortcut_icon'] = $empresa['url_shortcutIcon'];

        $data["page_css"] = [];
        $data["page_functions_js"] = [];

        $this->views->getView("ordenar", $data);
    }

    public function useragent($param)
    {
        // Test function for user agent detection
    }

    public function varsession($param)
    {
        // Test function for session and server variables
        phpinfo();
    }

    public function procesarfoto($param)
    {
        if (isset($_FILES["file"]) && in_array($_FILES["file"]["type"], ["image/jpeg", "image/png", "image/gif"])) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $_FILES['file']['name'])) {
                echo "images/" . $_FILES['file']['name'];
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }

    public function changeAvatar()
    {
        // Test function for avatar change
    }

    public function img_area_select()
    {
        if (isset($_POST["imagen"])) {
            $x1 = $_POST["x1"];
            $y1 = $_POST["y1"];
            $anchura = $_POST["anchura"];
            $altura = $_POST["altura"];
            $imagen = $_POST["imagen"];

            $imagenDeOrigen = '../images/' . $imagen;
            $manejadorDeOrigen = imagecreatefromjpeg($imagenDeOrigen);
            $manejadorDeDestino = ImageCreateTrueColor($anchura, $altura);

            imagecopyresampled($manejadorDeDestino, $manejadorDeOrigen, 0, 0, $x1, $y1, $anchura, $altura, $anchura, $altura);

            imagejpeg($manejadorDeDestino, "../images/prueba.jpg", 100);
        }
    }

    public function cropper()
    {
        if ($json = file_get_contents("php://input")) {
            $post = json_decode($json, true);
            $parts = explode(";base64,", $post["image"]);
            $strblob = base64_decode($parts[1]);
            $uuid = uniqid();
            $pathfile = "./Views/Pruebas/$uuid.png";

            file_put_contents($pathfile, $strblob);
            $pathfile = "/tienda-virtual/Views/Pruebas/$uuid.png";

            echo json_encode([
                "message" => "image uploaded successfully.",
                "file" => $pathfile
            ]);
            exit;
        }
    }
}