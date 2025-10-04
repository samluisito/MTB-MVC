<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Visitas extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function visitas()
    {
        header('location:' . base_url());
        exit();
    }

    public function registrar_visita()
    {
        if (isBot($_SERVER['HTTP_USER_AGENT'])) {
            exit("bot");
        }

        $payload = json_decode(file_get_contents("php://input"));
        if (!$payload) {
            exit();
        }

        $ip = getUserIP() ?? "";
        $meta = $this->geolocalizarPorIp($ip);

        $idUser = $_SESSION['idUser'] ?? null;

        $request = $this->model->registrarVisita(
            $ip,
            $payload->pagina,
            $payload->url,
            detectar_dispositivo(),
            dispositivoOS(),
            $meta['pais'],
            $meta['ciudad'],
            $meta['localidad'],
            $payload->idnav,
            $idUser
        );
        echo json_encode([$request]);
    }

    public function getUnicoId()
    {
        echo json_encode(['id' => uniqid('', true)]);
    }

    private function geolocalizarPorIp($ip)
    {
        // Provider 1: Abstract API
        $url1 = 'https://ipgeolocation.abstractapi.com/v1/?api_key=d7a1659b36a34c48a946376560b1b421&ip_address=' . $ip;
        $meta1 = curlConection($url1);
        if (empty($meta1->error) && ($meta1->country ?? null) && ($meta1->region ?? null) && ($meta1->city ?? null)) {
            return [
                'operador' => "1-ipgeo",
                'pais' => $meta1->country,
                'ciudad' => $meta1->region,
                'localidad' => $meta1->city
            ];
        }

        // Provider 2: ipapi.com
        $url2 = "http://api.ipapi.com/api/" . $ip . "?access_key=5b3be379c97d841411f9760677bfbab9";
        $meta2 = json_decode(file_get_contents($url2), true);
        if (empty($meta2['success']) && ($meta2['country_name'] ?? null)) {
            return [
                'operador' => "2-ipapi",
                'pais' => $meta2['country_name'],
                'ciudad' => $meta2['region_name'],
                'localidad' => $meta2['city']
            ];
        }

        // Provider 3: Geoplugin (Fallback)
        $meta3 = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
        return [
            'operador' => "3-Geoplugin",
            'pais' => $meta3['geoplugin_countryName'] ?? null,
            'ciudad' => $meta3['geoplugin_regionName'] ?? ($meta3['geoplugin_region'] ?? null),
            'localidad' => $meta3['geoplugin_city'] ?? null
        ];
    }
}