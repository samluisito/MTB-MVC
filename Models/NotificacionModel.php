<?php

declare(strict_types=1);

class NotificacionModel extends Mysql {

  public function __construct() {
    parent::__construct();
  }

  function insertNotificacion(string $tipo, $id_tipo, $leido = NULL) {
    $return = "";
    //consultamos la existencia de categorias duplicadas
    $recuest = $this->selectNotificacion($tipo, $id_tipo);

    if (empty($recuest)) {//'pedido',1 , NULL
      $query_insert = "INSERT INTO `notificaciones` (`tipo`, `id_tipo`, `leido`) VALUES (?,?,?)";
      $arrData = array($tipo, $id_tipo, $leido);
      $return = $this->insert($query_insert, $arrData);
    } else {
      $return = 'existe';
    }
    return $return;
  }

  function updateNotificacion(string $tipo, $id_tipo, $leido = NULL) {
    $recuest = $this->selectNotificacion($tipo, $id_tipo);

    if ($recuest > 0) {//'pedido',1 , NULL
      $leido = $leido ? 1 : NULL;
      $query_insert = "UPDATE `notificaciones` SET `leido` = ? WHERE `notificaciones`.`id_not` = ?";
      $arrData = array($leido, $recuest);
      $return = $this->update($query_insert, $arrData);
    }
  }

  function selectNotificacion(string $tipo, int $id_tipo) {
    return $this->select_column("SELECT id_not FROM notificaciones WHERE tipo = '{$tipo}' AND id_tipo = {$id_tipo} AND leido IS NULL");
  }

  function selectNotificacionesNoLeidas() {
    return $this->select_all("SELECT `tipo`,`datecreated` ,COUNT(`id_not`) AS cantidad FROM notificaciones WHERE `leido` IS NULL GROUP BY `tipo`");
  }

  function selectIdNotificacionestipo($tipo, $id_tipo, $leido = null) {
    $leido = $leido ? '= 1' : 'IS NULL';
    return $this->select_all_column("SELECT id_not FROM notificaciones WHERE tipo = '{$tipo}' AND id_tipo = {$id_tipo} AND leido = $leido");
  }

}
