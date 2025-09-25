<?php

declare(strict_types=1);

class ConfiguracionModel extends Mysql {

  private $idempresa;
  private $nombre_comercial;
  private $nombre_fiscal;
  private $id_fiscal;
  private $email;
  private $telefono;
  private $direccion;
  private $descripcion;
  private $tags;
  private $facebook;
  private $instagram;
  private $twitter;
  private $logo_menu;
  private $logo_imp;
  private $shortcut_icon;
  /* smtp_mail */
  private $smtp_status;
  private $serv_mail;
  private $pass_mail;
  private $host_mail;
  private $fecha_mantenimiento_hasta;
  private $costo_envio;
  private $modo_entrega;
  private $guardar_webp;
  /* tipo pago */
  private $idtipopago;
  private $tipopago;
  private $nombre_tpago;
  private $status;
  /* tipo pago detalle */
  private $tipopagoid;
  private $tpopago_label;
  private $tpopago_value;
  private $login_facebook;
  private $clave_app_fb;
  private $id_app_fb;

  public function __construct() {
    //echo 'mensaje desde el modelo home';
    parent::__construct();
  }

  public function selectConfig($idempresa) {
    $recuest = $this->select("SELECT * FROM config_gral WHERE idempresa = {$idempresa}");
    $dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
    $recuest['costo_envio'] = $recuest['costo_envio'] * $dolar;
    //agregamos u nuevo elemento en el array con la ruta completa de la imagen 
    $recuest['url_shortcutIcon'] = DIR_IMAGEN . $recuest['shortcut_icon'];
    $recuest['url_logoMenu'] = DIR_IMAGEN . $recuest['logo_menu'];
    $recuest['url_logoImpreso'] = DIR_IMAGEN . $recuest['logo_imp'];
    return $recuest;
  }

  public function updateConfig(int $intIdEmpresa, string $strNombrecomercial, string $strNombreFiscal,
      string $strIdFiscal, string $strEmail, string $strTelefono, string $strDireccion,
      string $strDescripcion, string $strTags, string $strLinkFacebook, string $strLinkInstagram, string $intTelfWhatsApp, string $strTextoWhatsApp = null,
      string $strLinkTwitter, string $img_logoMenu, string $img_logoImpreso, string $img_shrotcutIcon,
      int $int_smtp_status, string $istrServMail, string $strServPassword, string $strServHost,
      string $mantenimientoHasta, int|float $intCostoEnvio, string $strModoEntrega, int $intGuardar_webp,
      int $intLoginFacebook, string $txtClaveAppFb = null, string $txtIdAppFb = null, int $intPixelFacebook, int $txtIdPixelFb = null, $txtMetaDominioFb = null, string $txtExcluirIP = null) {


    $sqlFacebook = ($intLoginFacebook === 1) ? ", clave_app_fb=?, id_app_fb=?" : '';
    $sqlPixelFacebook = ($intPixelFacebook === 1) ? ",pixel_fb_id=?" : '';

    $query_update = "UPDATE config_gral SET nombre_comercial=?,
                                                nombre_fiscal=?,
                                                id_fiscal=?,
                                                email=?,
                                                telefono=?,
                                                direccion=?,
                                                descripcion=?,
                                                tags=?,
                                                facebook=?,
                                                instagram=?,
                                                whatsapp_numero=?,
                                                whatsapp_texto=?,
                                                twitter=?,
                                                logo_menu=?,
                                                logo_imp=?,
                                                shortcut_icon=?,
                                                smtp_status=?,
                                                serv_mail=?,
                                                pass_mail=?,
                                                host_mail=?,
                                                fecha_mantenimiento_hasta=?,
                                                costo_envio=?,
                                                modo_entrega=?,
                                                guardar_webp=?,
                                                login_facebook=? $sqlFacebook,
                                                pixel_facebook=? $sqlPixelFacebook,
                                                meta_dominio=?,
                                                excluir_ip=?
                                                WHERE idempresa = 1";

    $arrData = array(
      $strNombrecomercial,
      $strNombreFiscal,
      $strIdFiscal,
      $strEmail,
      $strTelefono,
      $strDireccion,
      $strDescripcion,
      $strTags,
      $strLinkFacebook,
      $strLinkInstagram,
      $intTelfWhatsApp,
      $strTextoWhatsApp,
      $strLinkTwitter,
      $img_logoMenu,
      $img_logoImpreso,
      $img_shrotcutIcon,
      $int_smtp_status,
      $istrServMail,
      $strServPassword,
      $strServHost,
      $mantenimientoHasta,
      $intCostoEnvio,
      $strModoEntrega,
      $intGuardar_webp,
      $intLoginFacebook,
    );
    ($intLoginFacebook === 1) ? array_push($arrData, $txtClaveAppFb, $txtIdAppFb) : '';
    array_push($arrData, $intPixelFacebook);
    ($intPixelFacebook === 1) ? array_push($arrData, $txtIdPixelFb) : '';
    array_push($arrData, $txtMetaDominioFb, $txtExcluirIP);

    return $this->update($query_update, $arrData);
  }

  /* tipos de pago ----- */

  public function selectTiposPagos() {
    return $this->select_all("SELECT * FROM tipopago");
  }

  public function selectTiposPagoDetalles($tipopagoid) {
    return $this->select_all("SELECT * FROM tipopago_detalle WHERE tipopagoid = $tipopagoid ");
  }

  /* configuracion -------------------------------------------------------------------------------------------------- */

  public function updStatusTP(int $idtipopago, int $estado) {
    $this->idtipopago = $idtipopago;
    $this->status = $estado;
    return $this->update("UPDATE tipopago SET status = ? WHERE idtipopago = $this->idtipopago", array($this->status));
  }

  public function updTPDetalle(int $tipopagoid, string $tpopago_label, string $tpopago_value) {
    $this->tipopagoid = $tipopagoid;
    $this->tpopago_label = $tpopago_label;
    $this->tpopago_value = $tpopago_value;
    $existe = $this->select("SELECT id FROM tipopago_detalle WHERE tipopagoid = $this->tipopagoid AND tpopago_label = '{$this->tpopago_label}'");
    if ($existe) {
      return $this->update("UPDATE tipopago_detalle SET tpopago_label = ?, tpopago_value = ? WHERE id = {$existe['id']}",
              array($this->tpopago_label, $this->tpopago_value));
    } else {

      return $this->insert("INSERT INTO tipopago_detalle (tipopagoid, tpopago_label, tpopago_value) VALUES (?,?,?)",
              array($this->tipopagoid, $this->tpopago_label, $this->tpopago_value));
    }
  }

  /* CONFIG REGION */

  /* Lista de regiones */

  public function selectRegiones() {
    //EXTRAE ROLES
    $sql = "SELECT * FROM config_regional";
    $recuest = $this->select_all($sql);
    return $recuest;
  }

  public function configRegionEnUso($idregion) {
    return $this->select("SELECT idempresa FROM config_gral WHERE regionid = $idregion");
  }

  public function selecRegionId(int $id) {
    //Consulta para obtener los datos del registro y la información de posición
    /* La subconsulta SELECT id FROM regiones WHERE id < r.id ORDER BY id DESC LIMIT 1 
     * se utiliza para obtener el ID del registro anterior. 
     * Ordena los registros por ID en orden descendente y selecciona el primer ID
     *  que sea menor que el ID del registro actual (r.id). */
    /* Se utiliza la función IFNULL para devolver 0 en caso de que no haya registros anterior o siguient */
    /* La parte CONCAT((SELECT COUNT(*) FROM regiones WHERE id <= r.id), ' de ', (SELECT COUNT(*) FROM regiones)) 
     * se utiliza para obtener la posición del registro en el conjunto de resultados. 
     * Cuenta el número de registros cuyo ID es menor o igual que el ID del registro actual 
     * (r.id) y lo concatena con el total de registros en la tabla. */
    return $this->select("
SELECT  r.*,
  IFNULL((SELECT idregion FROM config_regional WHERE idregion < r.idregion ORDER BY idregion DESC LIMIT 1), 0) AS prev,
  IFNULL((SELECT idregion FROM config_regional WHERE idregion > r.idregion ORDER BY idregion ASC LIMIT 1), 0) AS prox,
  CONCAT((SELECT COUNT(*) FROM config_regional WHERE idregion <= r.idregion), ' de ', (SELECT COUNT(*) FROM config_regional)) AS posicion
FROM config_regional AS r
WHERE r.idregion = $id ");
  }
}
