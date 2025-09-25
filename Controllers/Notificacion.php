<?php

declare(strict_types=1);

class Notificacion extends Controllers {

  function __construct() {
    parent::__construct();
  }

  function Notificacion() {
    
  }

  function set_notificacion($tipo, $id_tipo, $leido = null) {
    return $this->model->insertNotificacion($tipo, $id_tipo, $leido);
  }

  function getNotificacionesNoLeidasMenu() {

    $fecha1 = new DateTime(); //fecha inicial
    $response = $this->model->selectNotificacionesNoLeidas();
    $notificaciones = array('conteototal' => 0);
    foreach ($response as $notificacion) {
      $notificaciones['conteototal'] = $notificaciones['conteototal'] + $notificacion['cantidad'];

      if ($notificacion['tipo'] == 'pedido') {
        $notificacion['fecha'] = diferencia_entre_fechas($fecha1, $notificacion['datecreated']);
        $notificaciones['pedido'] = $notificacion;
      }
      if ($notificacion['tipo'] == 'contacto') {
        $notificacion['fecha'] = diferencia_entre_fechas($fecha1, $notificacion['datecreated']);
        $notificaciones['contacto'] = $notificacion;
      }
    }
    return $notificaciones;
  }

  function getIdNotificacionesTipo($tipo, $id_tipo) {
    return $this->model->selectNotificacion($tipo, $id_tipo);
  }
  function updateNotificacionID($tipo, $id_tipo,$leido) {
    return $this->model->updateNotificacion($tipo, $id_tipo,$leido);
  }

}

/*
 CREATE TABLE `notificaciones` (
`id_not` INT(11) NOT NULL AUTO_INCREMENT , 
`tipo` VARCHAR(255) NOT NULL , 
`id_tipo` INT(11) NOT NULL , 
`leido` INT(1) NULL , 
`datecreated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`dateupdate` DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
PRIMARY KEY (`id_not`)) ENGINE = InnoDB;

ALTER TABLE `mitienda_dbcli_1`.`notificaciones` ADD INDEX (`tipo`);

 */