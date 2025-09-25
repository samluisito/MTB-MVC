<?php
headerAdmin($data);
$empresa = $data['empresa'];
$fecha_mantenimiento = explode(' ', $empresa['fecha_mantenimiento_hasta'])[0];
$hora_mantenimiento = explode(' ', $empresa['fecha_mantenimiento_hasta'])[1];
$hora_mantenimiento = explode(':', $hora_mantenimiento);
$hora_mantenimiento = $hora_mantenimiento[0] . ':' . $hora_mantenimiento[1];
//$dolar = $_SESSION['base']['region_abrev'] == 'VE' ? 1 : getDolarHoy();
?>
<div class="container-fluid" >
  <div class="row" >
    <div class="col-12" >
      <div class="card" >
        <div class="card-header justify-content-between d-flex align-items-center">
          <h4 class="card-title">Configuracion</h4>
        </div>
        <div class="card-body"> 
          <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" aria-selected="true" href="#DatosEmpresa">Datos de la Empresa</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#rSocial">Redes Sociales</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#imagenes">Imagenes</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#configEmail">Configuracion de Email</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#operaciones">Operaciones</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#login">Login</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" aria-selected="false" href="#facebook">Facebook</a></li>
          </ul>
          <form type id="formConfiguracion">

            <div class="tab-content p-3 text-muted">
              <!-- datos empresa -->
              <div class="tab-pane active" role="tabpanel" id="DatosEmpresa">
                <input type="hidden" id="idEmpresa" name="idEmpresa" value="<?= $empresa ['idempresa']; ?>"><!-- este elemento estara oculto y su funcion es setear el id del Producto a actualizar -->
                <p class="text-primary">los campos con asterisco (<span class="required">*</span>)</p>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="control-label" >Nombre Comercial <span class="required">*</span></label>
                      <input type="text" class="form-control" id="txtNombreComercial"name="txtNombreComercial"type="text" required="" value="<?= $empresa['nombre_comercial']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Email <span class="required">*</span></label>
                      <input type="email" class="form-control" id="txtEmail" name="txtEmail" rows="6" required="" value="<?= $empresa['email']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Telefono <span class="required">*</span></label>
                      <input type="tel" class="form-control" id="txtTelefono" name="txtTelefono" rows="6" required="" value="<?= $empresa['telefono']; ?>" >
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label class="control-label">Nombre Fiscal <span class="required"> </span></label>
                      <input type="text" class="form-control" id="txtNombreFiscal"name="txtNombreFiscal"type="text" value="<?= $empresa['nombre_fiscal']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">ID Fiscal <span class="required"> </span></label>
                      <input type="text" class="form-control" id="txtIdFiscal" name="txtIdFiscal" value="<?= $empresa['id_fiscal']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Direccion <span class="required">*</span></label>
                      <textarea type="text" class="form-control" id="txtDireccion" name="txtDireccion" rows="2" required="" > <?= $empresa['direccion']; ?> </textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="mb-3">
                      <label class="control-label">Descripcion (150 caracteres) <span class="required"> </span></label>
                      <input type="text" class="form-control" id="txtDescripcion" name="txtDescripcion" value="<?= $empresa['descripcion']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Etiquetas <span class="required"> </span></label>
                      <input type="text" class="form-control" id="txtEtiquetas" name="txtEtiquetas" value="<?= $empresa['tags']; ?>" >
                    </div>
                  </div>
                </div> 
              </div>

              <div class="tab-pane" role="tabpanel" id="rSocial">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3">
                      <label class="control-label">Facebook <span class="required"></span></label>
                      <input type="text" class="form-control" id="txtLinkFacebook"name="txtLinkFacebook"type="text" value="<?= $empresa['facebook']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Instagram <span class="required"></span></label>
                      <input type="text" class="form-control" id="txtLinkInstagram" name="txtLinkInstagram" rows="6" value="<?= $empresa['instagram']; ?>" >
                    </div>
                    <div class="mb-3">
                      <div class="row">
                        <div class="col-sm-4 col-md-3 col-lg-2">    
                          <label class="control-label">WhatsApp Numero <span class="required"></span></label>
                          <input type="tel" class="form-control" id="intTelfWhatsApp" name="intTelfWhatsApp" rows="2" value="<?= $empresa['whatsapp_numero']; ?>">
                        </div>
                        <div class="col-sm-8 col-md-9 col-lg-10">    
                          <label class="control-label">WhatsApp Texto @url_pag para posicionar la url<span class="required"></span></label>
                          <textarea type="text" class="form-control" id="txtTextoWhatsApp" name="txtTextoWhatsApp" rows="2" ><?= $empresa['whatsapp_texto']; ?></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Twitter <span class="required"></span></label>
                      <input type="text" class="form-control" id="txtLinkTwitter" name="txtLinkTwitter" rows="6" value="<?= $empresa['twitter'] ?>" >
                    </div>
                  </div>
                </div> 
              </div>
              <div class="tab-pane" role="tabpanel" id="imagenes">
                <br>
                <div class="row">
                  <div class="col-md-6">
                    <input type="hidden" id="foto_actual_shrotcutIcon" name="foto_actual_shrotcutIcon" value="<?= $empresa['shortcut_icon']; ?>" ><!-- -->
                    <input type="hidden" id="foto_remove_shrotcutIcon" name="foto_remove_shrotcutIcon" value=""><!-- -->
                    <div class="photo photo-shrotcuticon"> <!-- Estilos de la imagen -->
                      <label for="shrotcutIcon">Sortcut Icono (32x32) .png o .ico</label>
                      <div class="prevPhoto prevPhoto-shrotcutIcon"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                        <span class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                        <label for="shrotcutIcon"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                        <div>
                          <img id="imgshrotcutIcon" src="<?= $empresa['url_shortcutIcon']; ?>" > <!-- imagen previa -->
                        </div>
                      </div>
                      <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                        <input class="fotoinput" type="file" name="shrotcutIcon" id="shrotcutIcon">
                      </div>
                      <div id="form_alertshrotcutIcon"></div> <!-- aca se mostrata un texto -->
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-6">
                    <input type="hidden" id="foto_actual_logoMenu" name="foto_actual_logoMenu" value="<?= $empresa['logo_menu']; ?>" ><!-- -->
                    <input type="hidden" id="foto_remove_logoMenu" name="foto_remove_logoMenu" value=""><!-- --> 

                    <div class="photo"> <!-- Estilos de la imagen -->
                      <label for="logoMenu">Logo Menu (266x40})</label>
                      <div class="prevPhoto prevPhoto-logoMenu"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                        <span class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                        <label for="logoMenu"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                        <div>
                          <img id="imglogoMenu" src="<?= $empresa['url_logoMenu']; ?>" "> <!-- imagen previa -->
                        </div>
                      </div>
                      <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                        <input class="fotoinput" type="file" name="logoMenu" id="logoMenu">
                      </div>
                      <div id="form_alertlogoMenu"></div> <!-- aca se mostrata un texto -->
                    </div>
                  </div>
                </div> 
                <br>
                <div class="row">
                  <div class="col-md-6">
                    <input type="hidden" id="foto_actual_logoImpreso" name="foto_actual_logoImpreso" value="<?= $empresa['logo_imp']; ?>" ><!-- -->
                    <input type="hidden" id="foto_remove_logoImpreso" name="foto_remove_logoImpreso" value=""><!-- --> 
                    <div class="photo"> <!-- Estilos de la imagen -->
                      <label for="logoImpreso">Logo Impresion (250x250)</label>
                      <div class="prevPhoto prevPhoto-logoImpreso"> <!-- Donde se mostrara la vistaa previa de la imagen-->
                        <span class="delPhoto notBlock">X</span> <!-- no estara visible y se le aplicaran algunos estilos -->
                        <label for="logoImpreso"></label> <!-- ocupara el ancho para poder seleccionar la foto -->
                        <div>
                          <img id="imglogoImpreso" src=" <?= $empresa['url_logoImpreso']; ?> "> <!-- imagen previa -->
                        </div>
                      </div>
                      <div class="upimg"> <!-- junto al imput tipo file serviran para cargar la foto -->
                        <input class="fotoinput" type="file" name="logoImpreso" id="logoImpreso">
                      </div>
                      <div id="form_alertlogoImpreso"></div> <!-- aca se mostrata un texto -->
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" role="tabpanel" id="configEmail">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3">
                      <br>
                      <label class="control-label">Habilitar smtp </label>
                      <input type="checkbox" name="smtp_status" value="" class="mb-3" <?= $empresa ['smtp_status'] == 1 ? 'checked="checked"' : ''; ?> >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Host smtp <span class="required">*</span></label>
                      <input type="text" class="form-control" id="txtServHost" name="txtServHost" rows="6" required="" value="<?= $empresa['host_mail']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Email <span class="required">*</span></label>
                      <input type="text" class="form-control" id="txtServEmail"name="txtServEmail"type="text" required=""value="<?= $empresa['serv_mail']; ?>" >
                    </div>
                    <div class="mb-3">
                      <label class="control-label">Contraseña <span class="required">*</span></label>
                      <input type="text" class="form-control" id="txtServPassword" name="txtServPassword" rows="6" required="" value="<?= $empresa['pass_mail']; ?>" >
                    </div>
                  </div>
                </div> 
              </div>
              <div class="tab-pane" role="tabpanel" id="operaciones">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3 col-xl-4">
                      <label class="control-label">Costo de Envio <span class="required">*</span></label>
                      <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">$USD</span></div>
                        <input type="number" class="form-control text-right" id="intCostoEnvio" name="intCostoEnvio" placeholder="Monto" value="<?= number_format($empresa['costo_envio'], 0); ?>" step="">
                        <!--<div class="input-group-append"><span class="input-group-text">.00</span></div>-->
                      </div>
                      <br>
                      <label class="control-label">Guradar imagenes en formato webp <span class="required">*</span></label>
                      <div class="input-group"> 
                        <div class="toggle-flip">
                          <label>
                            <input type="checkbox" name="guardar_webp" <?= $empresa ['guardar_webp'] == 1 ? 'checked="checked"' : ''; ?>>
                          </label>
                        </div>
                      </div>
                      <br>
                      <label class="control-label">Forma de Entrega <span class="required">*</span></label>
                      <select class="form-select" id="ModoEntrega" name="modoEntrega">
                        <option <?= $empresa['modo_entrega'] == 'rd' ? 'selected=""' : '' ?> value="rd">Retiro en tienda y Delivery</option>
                        <!-- <option <?= $empresa['modo_entrega'] == 'r' ? 'selected=""' : '' ?> value="r" >Retiro en Tienda</option>-->
                        <option <?= $empresa['modo_entrega'] == 'd' ? 'selected=""' : '' ?> value="d">Delivery</option>
                      </select>
                    </div>
                    <div class="mb-3 col-xl-4">
                      <label class="control-label"> En Mantenimiento Hasta <span class="required">*</span></label>
                      <input type="date" class="form-control" id="fecha_mantenimiento_hasta"name="fecha_mantenimiento_hasta" value="<?= $fecha_mantenimiento ?>" > 
                      <input type="time" class="form-control" id="hora_mantenimiento_hasta"name="hora_mantenimiento_hasta" value="<?= $hora_mantenimiento ?>" >
                      <!-- <input class="date-picker mantenimiento_hasta" name="mantenimiento_hasta" placeholder="Mes y año" minlength="4" maxlength="7" onclick="btnMantenimiento_hasta()" value="" > <!--<!-- <?php echo date("m-Y"); ?> -->
                    </div>
                  </div>
                </div> 
              </div>
              <div class="tab-pane" role="tabpanel" id="login">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3 col-xl-4">

                      <br>
                      <label class="control-label">Login con Facebook <span class="required">*</span></label>
                      <div class="input-group"> 
                        <div class="toggle-flip">
                          <label>
                            <input type="checkbox" name="login_facebook" <?= $empresa ['login_facebook'] == 1 ? 'checked="checked"' : ''; ?>>
                          </label>
                        </div>
                      </div>
                      <br>
                      <div class="mb-3">
                        <label class="control-label">ID APP </label>
                        <input type="text" class="form-control" id="txtIdAppFb"name="txtIdAppFb"type="text" value="<?= $empresa['id_app_fb']; ?>" >
                      </div>
                      <div class="mb-3">
                        <label class="control-label">CLAVE SECRETA APP FB </label>
                        <input type="text" class="form-control" id="txtClaveAppFb"name="txtClaveAppFb"type="text" value="<?= $empresa['clave_app_fb']; ?>" >
                      </div>
                    </div>
                  </div> 
                </div>
              </div>
              <div class="tab-pane" role="tabpanel" id="facebook">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="mb-3 col-xl-8">
                      <br>
                      <label class="control-label">Pixel de Facebook <span class="required">*</span></label>
                      <div class="input-group"> 
                        <div class="toggle-flip">
                          <label>
                            <input type="checkbox" name="pixel_facebook" <?= $empresa ['pixel_facebook'] == 1 ? 'checked="checked"' : ''; ?>>
                          </label>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="control-label">Pixel ID </label>
                        <!--<textarea class="form-control" id="txtIdPixelFb" name="txtIdPixelFb" rows="6" cols="10" ><?= "{$empresa['pixel_fb_id']}" ?> </textarea>-->
                        <input type="tel" class="form-control" id="txtIdPixelFb" name="txtIdPixelFb" value="<?= $empresa['pixel_fb_id'] ?>">

                      </div>
                    </div>
                    <div class="mb-9 col-xl-8">
                      <br>
                      <div class="mb-3">
                        <label class="control-label">Meta-Tag para verificacion de Dominio </label>
                        <textarea class="form-control" id="txtMetaDominio" name="txtMetaDominio" rows="2" cols="10" ><?= "{$empresa['meta_dominio']}" ?> </textarea>
                      </div>
                      <div class="mb-3">
                        <label class="control-label">Excluir IP - Separar con comas </label>
                        <input type="texto" class="form-control" id="txtExcuirIP" name="txtExcuirIP" value="<?= $empresa['excluir_ip'] ?>">
                      </div>
                    </div>
                  </div> 
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <button id="btnActionForm" type="submit" form="formConfiguracion" class="btn btn-primary btn-block" ><i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;Guardar</button>
          &nbsp;&nbsp;&nbsp; <!-- &nbsp; Espacio en blanco irrompible -->
          <button class="btn btn-danger btn-block" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;Cerrar</button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<?= footerAdmin($data) ?> 