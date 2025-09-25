<?php

declare(strict_types=1);


  /* ============================================================================================================================ */

class ProfileModel extends Mysql {

  public function ultimosPedidos() {

    return $this->select_all("SELECT p.idpedido, CONCAT(pr.nombres,' ',pr.apellidos) AS nombre, p.monto, p.status
                 FROM pedido p
                 INNER JOIN persona pr
                 on p.personaid = pr.idpersona
                 WHERE p.personaid = {$_SESSION['userData']['idpersona']}
                 ORDER BY p.idpedido  DESC LIMIT 12");
  }

  /* ============================================================================================================================ */
}
