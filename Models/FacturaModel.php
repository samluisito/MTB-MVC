<?php

declare(strict_types=1);

class FacturaModel extends Mysql {

  //Pedidos
  public $intIdProducto;
  public $strNomProducto;
  public $strDescripcion;
  public $intCodigo;
  public $intCategoriaId;
  public $intPrecio;
  public $intStock;
  public $strRuta;
  public $intStatus;
  public $strImagen;

  public function __construct() {
    parent::__construct();
  }

  public function selectPedidoId($idpedido) {
    if (is_numeric($idpedido)) {
      //echo "La cadena $idpedido consiste completamente de dígitos.\n";
      $recuest_idPer = $this->select("SELECT personaid  FROM pedido WHERE idpedido = {$idpedido}"); // validamos que el id pedido exista, y extraemos el idpersona
    } else {
      //echo "La cadena $idpedido no consiste completamente de dígitos.\n";
      $recuest_idPer = $this->select("SELECT personaid, idpedido  FROM pedido WHERE transaccionid = '{$idpedido}'"); // validamos que el id pedido exista, y extraemos el idpersona

      $idpedido = $recuest_idPer ['idpedido'];
    }

    if (empty($recuest_idPer)) {     //si el resulatado de la consulta es null retornamos pedido inexistente, de lo contrario continuamos
      $recuest = "pedido_no_existe";
    } else {
      $recuest_idPer = $recuest_idPer ['personaid'];
      if ($_SESSION['userData']['rolid'] == 2) {      // si el rol del usuario logueado es de cliente, validamos que idpersona del pedido corespoda con el idpersona de la sesion
        if ($_SESSION['userData']['idpersona'] != $recuest_idPer) {     // si no corresponde retornamos Pedido_de_otro_cliente
          return "Pedido_de_otro_cliente";
          exit();
        }
      }
      /* extraemos el pedido--------------------------- */
      $sql_ped = "SELECT p.idpedido, 
                p.referenciadecobro, 
                p.transaccionid, 
                p.personaid, 
                p.fecha, 
                p.subtotal, 
                p.costo_envio, 
                p.monto, 
                p.tipopagoid, t.tipopago, t.nombre_tpago, 
                p.direccionenvio, 
                p.status 
                 
                 FROM pedido as p INNER JOIN tipopago t 
                 ON p.tipopagoid = t.idtipopago
                 WHERE p.idpedido = {$idpedido}";
      $recuest_pedido = $this->select($sql_ped);

      /* extraemos los datos del usuario que realizo el pedido--------------------------- */
      $sql_per = "SELECT  idpersona,
                            identificacion,
                            nombres,
                            apellidos,
                            telefono,
                            email_user,
                            nit,
                            nombrefiscal,
                            direccionfiscal
                    FROM persona WHERE idpersona = {$recuest_pedido['personaid']}";
      $recuest_persona = $this->select($sql_per);
      /* extraemos el detalle del pedido--------------------------- */

      $sql_det = "SELECT  dp.pedidoid,
                            dp.productoid,
                            p.nombre,
                            p.ruta,
                            dp.precio,
                            dp.cantidad
                            FROM pedido_detalle as dp
                            INNER JOIN producto p
                            ON dp.productoid = p.idproducto
                            WHERE dp.pedidoid = {$recuest_pedido['idpedido']}";
      $recuest_detalle = $this->select_all($sql_det);

      //$tipospago = $this->select_all('SELECT * FROM tipopago');
      $recuest = array(
        'usuario' => $recuest_persona,
        'orden' => $recuest_pedido,
        'detalle' => $recuest_detalle//, 'tiposdepago' => $tipospago
      );
    }

    return $recuest;
  }

}
