<!-- Modal Ver Producto-->
    <?php           
            if ($data) {
                $trs = $data->purchase_units[0];
                $cte = $data->payer;
                
                $moneda = $trs->payments->captures[0]->amount->currency_code;
                $idtransaccion = $trs->payments->captures[0]->id;

                /* datos clientes */
                $nombreCte = $cte->name->given_name . ' ' . $cte->name->surname;
                $emailCte = $cte->email_address;
                if (!empty($cte->phone->phone_number->national_number)) {
                    $telfCte = $cte->phone->phone_number->national_number;
                }
                $cod_cdad = $cte->address->country_code;
                $direccion1 = $trs->shipping->address->address_line_1;
                $direccion2 = $trs->shipping->address->admin_area_2;
                $direccion3 = $trs->shipping->address->admin_area_1;
                $cod_postal = $trs->shipping->address->postal_code;

                //detalle de montos
                $importe_bruto = $trs->payments->captures[0]->seller_receivable_breakdown->gross_amount->value;
                $comision = $trs->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value;
                $importe_neto = $trs->payments->captures[0]->seller_receivable_breakdown->net_amount->value;
            }
            ?>
<div class="modal fade-in" id="modalReembolso" tabindex="-1" role="dialog"  aria-hidden="true" aria-labelledby="Datos del Producto">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header header-primary"> <!-- headerRegister   una segunda clase para agregar en los estilos ccs-->
                <h5 class="modal-title" id="titleModal">Reembolsar Transaccion por <?= $importe_bruto . ' ' . $moneda ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
       
            <div class="modal-body " ><!--div class="tile  container-fluid ">div class="tile-body">  <div class="tile-body"-->
                <!--Tabla ver datos -->
                <table class="table table-bordered">
                    <input type="hidden" name="idtransaccion" id="idtransaccion" value="<?= $idtransaccion ?>">
                    <tbody>
                        <tr>
                            <td>Tansaccion: </td>
                            <td><?= $idtransaccion ?></td>
                        </tr>
                        <tr>
                            <td>Datos Contacto: </td>
                            <td>
                                <br>Nombre: <?= $nombreCte ?>
                                <br>Email: <?= $emailCte ?>
                                <?php if (!empty($cte->phone->phone_number->national_number)) { ?>
                                    <br>Email: <?= $telfCte ?>
                                <?php } ?>
                                <br>Direccion: <?= $direccion1 ?>
                                <?= $direccion2 . ' ' . $direccion3 . ' ' . $cod_postal ?>
                                <?= $cod_cdad ?>

                            </td>
                        </tr>
                        <tr>
                            <td>Importe Total Reembolso: </td>
                            <td><?= $importe_bruto . ' ' . $moneda ?></td>
                        </tr>
                        <tr>
                            <td>Importe Neto Reembolso: </td>
                            <td><?= $comision . ' ' . $moneda ?></td>
                        </tr>
                        <tr>
                            <td>Comision Paypal Reembolso : </td>
                            <td><?= $importe_neto . ' ' . $moneda ?></td>
                        </tr>
                        <tr>
                            <td>Observacion: </td>
                            <td><textarea id="txtObservacion" name="txtObservacion" class="form-control" rows="5" cols="10" required=""></textarea></td>
                        </tr>
                    </tbody>
                </table>
                <div class="tile-footer ">
                    <button class="btn btn-primary" onclick="fntReembolsar()"><i class="fa fa-fw fa-lg fa-reply" aria-hidden="true"></i><span>Reembolsar</span></button>
                    <button class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle" aria-hidden="true"></i><span>Cerrar</span></button>
                </div>
            </div>
        </div>
    </div>
</div>