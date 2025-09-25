<?php

declare(strict_types=1);

class Visitas extends Controllers {

  public function __construct() {
    parent::__construct();
  }

  /* ----------------------------------------------------------------------------------------------------------------------- */

  public function Visitas() {//primer metodo del controlador es llamdo i no hay un metodo definido en la url, entonces retornamos a home
    header('location:' . base_url());
    exit();
  }

  /* registrar visita --------------------------------------------------------- */

  function registrar_visita() {
    if (isBot($_SERVER['HTTP_USER_AGENT'])) {
      exit("bot");
    }
    
    $payload = json_decode(file_get_contents("php://input"));
    if (!$payload) {
      exit();
    }
    $ip = getUserIP() ?? "";
    $meta = $this->geolocaliuzarPorIp($ip);

    $dispositivo = detectar_dispositivo(); //dispositivoTipo();
    $dispositivoOS = dispositivoOS();
    $pais = $meta['pais'];
    $ciudad = $meta['ciudad'];
    $localidad = $meta['localidad'];
    $idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : null;
//      dep($payload);
    $request = $this->model->registrarVisita($ip, $payload->pagina, $payload->url, $dispositivo, $dispositivoOS, $pais, $ciudad, $localidad, $payload->idnav, $idUser);
    echo json_encode(array($request));
  }

  public function getUnicoId() {
    $id = uniqid('', true);
    echo json_encode(array('id' => $id));
  }

//----------------------------------------------------------------------------------
  function mi_ip() {
    echo getUserIP();
  }

  function geolocalizar_visita($cant) {
    $cant = intval($cant);
    $cant = $cant ?: 1;
    $visitas = $this->model->getVisitasSinCity($cant);

    foreach ($visitas as $key => $visita) {
      print $key;

      dep($visita);
      $id = $visita['idvisita'];
      $ip = $visita['ip'];

      $meta = $this->geolocaliuzarPorIp($ip);
      $pais = $meta['pais'];
      $ciudad = $meta['ciudad'];
      $localidad = $meta['localidad'];

      $request = $this->model->updateGeolocalizacionVisita($pais, $ciudad, $localidad, $id);

      if ($request) {
        print('Actualizado ---------------------------------------');
      } else {
        print('no actualizado ---------------------------------------');
      }
      dep(array($id, $meta));
      print("==============================================================================");
    }
  }

  private function geolocaliuzarPorIp($ip) {
    /* Consulta a 3 proveedores de geolocalizacion ip, si el primero no tiene estatus ok, pasa al seguendo y luego al 3ro de ser necesaro */
    $url = 'https://ipgeolocation.abstractapi.com/v1/?api_key=d7a1659b36a34c48a946376560b1b421&ip_address=' . $ip;
    $operador = 'error';
    $meta = (curlConection($url));
    if (empty($meta->error) && ($meta->country != null && $meta->region != null && $meta->city != null)) {
      $operador = "1-ipgeo ";
      $pais = $meta->country ?: null;
      $ciudad = $meta->region ?: null;
      $localidad = $meta->city ?: null;
    } else {
      $url = "http://api.ipapi.com/api/" . $ip . "?access_key=5b3be379c97d841411f9760677bfbab9";
      $meta = json_decode(file_get_contents($url), true);
      if (empty($meta['success'])) {
        $operador = "2-ipapi";
        $pais = $meta['country_name'] ?: null;
        $ciudad = $meta['region_name'] ?: null;
        $localidad = $meta['city'] ?: null;
      } else {
        $operador = "3-Geoplugin ";
        $meta = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));
        $pais = $meta['geoplugin_countryName'] ?: null;
        $ciudad = $meta['geoplugin_regionName'] ?: ($meta['geoplugin_region'] ?: null);
        $localidad = $meta['geoplugin_city'] ?: null;
      }
    }
    return array(
      'operador' => $operador,
      'pais' => $pais,
      'ciudad' => $ciudad,
      'localidad' => $localidad);
  }

}
