<?php ?>
<?php
$orden = $data['pedido'];
$detalle = $data['detalle'];
$empresa = $data['empresa'];
$subtotal = 0;

//dep($empresa['url_logoImpreso']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1"><!-- Ensures optimal rendering on mobile devices. -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Optimal Internet -->

        <title>Orden</title>
        <style type="text/css">
            p { font-family: arial; letter-spacing: 1px; color: #7f7f7f; font-size: 12px; }
            hr { border: 0px;border-top: 1px solid #CCC; }
            h4 { font-family: arial; margin: 0; }
            table { width: 100%; max-width: 700px; margin: 10px auto; border: 1px solid #CCC; border-spacing: 0; }
            table tr td, table tr th { padding: 5px 10px; font-family: arial; font-size: 12px ;}
            .logo{ 
                max-width: 180px; 
                max-height:  150px;
                vertical-align: middle; 
                border-style: none;
                display: flex;
                align-content: center;
                margin: 15px; }
            #detalleOrden tr td{border: 1px solid #CCC;}
            #dataEmpresa td {width: 33.3% ;}
            .table-active{background-color: #CCC;}
            .text-center{text-align: center;}
            .text-right{text-align: right;}
            .col-3 {colspan: 3;}
            @media screen and (max-width: 500px){
                p, table tr td, table tr th{ font-size: 8px }
            }
            @media screen and (max-width: 750px){
                p, table tr td, table tr th{ font-size: 10px }
            }
        </style>
    </head>
    <body>
        <div>
            <br>
            <p class="text-center">   Se ha generado una orden, a continuacion encontraras los datos </p>
            <br>
            <hr>
            <br>
            <table><!-- Datos de la empresa encabezado 3 columnas -->
                <tr id="dataEmpresa">
                    <td>
                        <img class="logo" src="<?= $empresa['url_logoImpreso'] ?>"  alt="logo<?= $empresa ?>"/>
                    </td>
                    <td>
                        <div class="text-center">
                            <h4><strong> <?= strtoupper($empresa['nombre_comercial']); ?> </strong></h4>
                            <p>                                   
                                <?= $empresa['direccion']; ?><br>
                                Telefono: <?= $empresa['telefono']; ?>.<br>
                                Email: <?= strtolower($empresa['email']); ?><br>
                            </p>
                        </div>
                    </td>
                    <td>
                        <div class="text-right">
                            <p> Orden N°: <strong> <?= rellena($orden['idpedido']); ?> </strong><br>                                  
                                Fecha: <?= date_format(new DateTime($orden['fecha']), "d/m/y h:m"); ?> <br>
                                Medio de Pago: <?= $orden['tipopago']; ?><br>
                                <?php if ($orden['tipopagoid'] != 2) { ?>
                                    Transaccion: <?= $orden['referenciadecobro']; ?>
                                <?php } ?>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
            <table><!-- DaTOS DEL CLIENTE SIN ENCABEZADO 3 COLUMNAS -->
                <tr>
                    <td width="140"> Nombre: </td>                    
                    <td> <?= $orden['nombres'] . " " . $orden['apellidos']; ?></td>                    
                </tr>
                <tr>
                    <td width="140"> telefono: </td>                    
                    <td> <?= $orden['telefono']; ?></td>                    
                </tr>
                <tr>
                    <td width="140"> Direccion de envio: </td>                    
                    <td> <?= $orden['direccionenvio']; ?></td>                    
                </tr>
            </table>
            <table>
                <thead class="table-active">
                    <tr>
                        <td> Descripcion: </td>                    
                        <td class="text-right"> Precio: </td>                    
                        <td class="text-center"> Cantidad: </td>                    
                        <td class="text-right"> Importe: </td>                    
                    </tr>
                </thead>
                <tbody id="detalleOrden">

                    <?php                    foreach ($detalle as $item) {                          ?>

                        <tr>
                            <td> <a target="_blank" href="<?= base_url() . 'tienda/producto/' . $item['productoid'] . '/' . $item['ruta']; ?>"><?= $item['nombre']; ?></a>  </td>                    
                            <td class="text-right"> <?= formatMoney($item['precio']); ?> </td>                    
                            <td class="text-center"> <?= $item['cantidad']; ?> </td>                    
                            <td class="text-right">  <?= formatMoney($item['precio'] * $item['cantidad']); ?></td>                    
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
            </table>
            <br>
            <div class="text-center">
                <p>Si tenés un problema, estamos para ayudarte. Contactanos: <a target="blank" href="mailto:<?= strtolower($empresa['email']); ?>"><?= strtolower($empresa['email']); ?></a>  telefono <?= $empresa['telefono']; ?>. </p>
                <h4>¡Gracias por tu compra!</h4>
            </div>
        </div>
    </body>
</html>
