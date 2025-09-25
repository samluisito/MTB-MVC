<?php

gd_info();
GD_VERSION;

function_exists('imagewebp');

/* https://bluelytics.com.ar/#!/api */
$meta = json_decode(file_get_contents('https://api.bluelytics.com.ar/v2/latest'));
//dep($meta);
//dep($meta->blue->value_sell);
$ahora = date("Y-m-d H:i:s");

dep(empty($_SESSION['dolarhoy']['caduca']).' - '. $_SESSION['dolarhoy']['caduca'] .' <= '. $ahora);

if (empty($_SESSION['dolarhoy']['caduca']) || strtotime($_SESSION['dolarhoy']['caduca']) <= strtotime( $ahora)) {
   $fecha = new DateTime();
   $mifecha = $fecha->modify('+1 hours');
   $mifecha = $mifecha->format('d-m-Y H:i:s');
   dep('nueva fecha : '.$mifecha);

   $_SESSION['dolarhoy'] = array('precio' => intval($meta->blue->value_sell), 'caduca' => $mifecha);
}
//$_SESSION['dolarhoy'] = array('precio' => intval($meta->blue->value_sell), 'caduca' => '2022-01-16 23:12:00');
unset($_SESSION['dolarhoy']);
dep($_SESSION);


//  $content_type = $contentType != null ? $contentType : "application/x-www-form-urlencoded"; // si $contentType es distinto de null pordefecto sera application/x-www-form-urlencoded
//  
//  if ($token != null) {
//      $arrHeader = array('Content-Type: ' . $content_type, 'Authorization: Bearer ' . $token);
//   } else {
//      $arrHeader = array('Content-Type: ' . $content_type);
//   }
//
//   $ch = curl_init(); // iniciamos el curl pero no le indicamos la url 
//   curl_setopt($ch, CURLOPT_URL, $ruta);      //insertamos la url
//   curl_setopt($ch, CURLOPT_POST, true);      //indicamos que la peticion sera tipo post
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);      //verifica el certificado ssl 
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);       // indica que retornara informacion 
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $arrHeader);       // indicamos los Header 
//
//   $result = curl_exec($ch);     //ejecuta la configuracion de la variable payLogin
//
//   $err = curl_error($ch); //consultamos la devolucion de error 
//
//   curl_close($ch); //cerramos el curl
//
//   if ($err) { //si hay un error retornamos el mensaje, de lo contrario retornamos el array de datos 
//      $request = "CURL Error #: " . $err;
//   } else {
//      $objData = json_decode($result);
//      $request = $objData;
//   }

 