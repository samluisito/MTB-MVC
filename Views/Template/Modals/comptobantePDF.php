<?php
$empresa = $data['infoEmpresa'];
$usuaro = $data['usuario'];
$orden = $data['orden'];
$detalle = $data['detalle'];
?>
<!doctype html>
<html lang="es">
    <head>
        <title>Factura</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            table{width: 90% ; align-self: center}
            table td,table th {font-size: 12px;}
            h4{margin-bottom: 0px}

            .text-center{text-align: center;}
            .text-right{text-align: right;}
            .left{align-content: left;}
            .logo{ 

                max-width:   100px;
                max-height:  150px;
                vertical-align: middle; 
                border-style: none;
                display: flex;
                align-content: center;
                margin: 15px; }
            .wd33{width: 33.33%}
            .wd10{width: 10%}
            .wd15{width: 15%}
            .wd40{width: 40%}
            .wd55{width: 55%}
            .tbl-cliente{border: 1px solid #CCC; border-radius: 10px; padding: 5px; margin-right: 0px}
            .tbl-detalle{border-collapse: collapse;}
            .tbl-detalle thead td{padding: 5px; background-color: #009688; color: #FFF}
            .tbl-detalle tbody td{padding: 5px;border-bottom: 1px solid #CCC; }


        </style>
    </head>
    <body>

        <table class="tbl-headder">
            <tbody>
                <tr>
                    <td class="left wd33">
                        <img class="logo" src="<?= DIR_IMAGEN . $empresa['logo_imp'] ?>"  alt="logo<?= $empresa['nombre_comercial'] ?>"/>
                    </td>
                    <td class="text-center wd33">
                        <h4><strong> <?= strtoupper($empresa['nombre_comercial']); ?> </strong></h4>
                        <p> 
                            <?= $empresa['direccion']; ?><br>
                            Telefono: <?= $empresa['telefono']; ?>.<br>
                            Email: <?= strtolower($empresa['email']); ?><br>
                        </p>
                    </td>
                    <td class="text-right wd33">
                        <p> Orden N°: <strong> <?= rellena($orden['idpedido']); ?> </strong><br>                                  
                            Fecha: <?= date_format(new DateTime($orden['fecha']), "d/m/y"); ?> <br>
                            Medio de Pago: <?= $orden['nombre_tpago']; ?><br>
                            <?php if ($orden['tipopago'] != 'efe' && $orden['tipopago'] != 'tb') { ?>
                                Transaccion: <?= $orden['transaccionid']; ?>
                            <?php } ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="tbl-cliente">
            <tbody>
                <tr>
                    <td class="wd10"> CUIT: </td>  
                    <td class="wd40"> <?= $usuaro['nit']; ?></td>  
                    <td class="wd10"> Telefono: </td>                    
                    <td class="wd40"> <?= $usuaro['telefono']; ?></td>  
                </tr>
                <tr>
                    <td > Nombre: </td>                    
                    <td > <?= $usuaro['nombres'] . " " . $usuaro['apellidos']; ?></td>     

                    <td > Direccion de envio: </td>                    
                    <td> <?= $orden['direccionenvio']; ?></td>                       
                </tr>
            </tbody>
        </table>
        <table class="tbl-detalle">
            <tbody>
            <thead>
                <tr>
                    <td class="wd55"> Descripcion: </td>                    
                    <td class="text-right wd15"> Precio: </td>                    
                    <td class="text-center wd15"> Cantidad: </td>                    
                    <td class="text-right wd15"> Importe: </td>                    
                </tr>
            </thead>
            <tbody id="detalleOrden">

                <?php foreach ($detalle as $item) { ?>

                    <tr>
                        <td class="wd55"> <a target="_blank" href="<?= base_url() . 'tienda/producto/' . $item['productoid'] . '/' . $item['ruta']; ?>"><?= $item['nombre']; ?></a>  </td>                    
                        <td class="text-right wd15"> <?= formatMoney($item['precio']); ?> </td>                    
                        <td class="text-center wd15"> <?= $item['cantidad']; ?> </td>                    
                        <td class="text-right wd15">  <?= formatMoney($item['precio'] * $item['cantidad']); ?></td>                    
                    </tr>  
                <?php } ?>

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right "> SubTotal: </th>                    
                    <td class="text-center"> <?= formatMoney($orden['subtotal']); ?></td>                    
                </tr>
                <tr>
                    <th colspan="3" class="text-right "> Envio: </th>                    
                    <td class="text-center"> <?= formatMoney($orden['costo_envio']); ?></td>                    
                </tr>
                <tr>
                    <th colspan="3" class="text-right "> Total: </th>                    
                    <td class="text-center"> <?= formatMoney($orden['monto']); ?></td>                    
                </tr>
            </tfoot>
        </tbody>
    </table>
    <br>

    <div class="text-center">
        <p>Si tenés un problema, estamos para ayudarte. Contactanos: <a target="blank" href="mailto:<?= strtolower($empresa['email']); ?>"><?= strtolower($empresa['email']); ?></a>  telefono <?= $empresa['telefono']; ?>. </p>
        <h4>¡Gracias por tu compra!</h4>
    </div>
    <?php
    //dep($data);
    ?> 
</body>

<footer>

</footer>

</html>
