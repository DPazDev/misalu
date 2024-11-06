<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$nombre=$_REQUEST['nombre'];
$rif=$_REQUEST['rif'];
$numero_contrato=$_REQUEST['numero_contrato'];
$numero_rec_prima=$_REQUEST['numero_rec_prima'];
$concepto=$_REQUEST['concepto'];
$monto=$_REQUEST['monto'];
$saldo_favor=$_REQUEST['saldo_favor'];
$saldo_deudor=$_REQUEST['saldo_deudor'];
$fecha_pago=$_REQUEST['fecha_pago'];
$fecha_proxima_pago=$_REQUEST['fecha_proxima_pago'];
$serie=$_REQUEST['serie'];
$numero_recibo=$_REQUEST['numero_recibo'];
$total_prima=$_REQUEST['total_prima'];
$cuota=$_REQUEST['cuota'];
$id_recibo_pago=$_REQUEST['id_recibo_contrato'];
$id_comisionado = $_REQUEST["id_comisionado"];
$direccion_cobro = $_REQUEST["direccion_cobro"];

$fechaimpreso=date("d-m-Y");

$sqlComisionado="select
        comisionados.nombres,
        comisionados.apellidos,
        comisionados.codigo
    from
        comisionados 
    where
        comisionados.id_comisionado=$id_comisionado;";
$consultaComisionado = ejecutar($sqlComisionado);
$comisionado = assoc_a($consultaComisionado);

$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_bus_rec_pagos= "select * from tbl_recibo_pago,tbl_series where tbl_recibo_pago.id_recibo_pago=$id_recibo_pago and tbl_recibo_pago.id_serie=tbl_series.id_serie";
$r_bus_rec_pagos = ejecutar($q_bus_rec_pagos);
$f_bus_rec_pagos=asignar_a($r_bus_rec_pagos);

$fecha=date("Y-m-d");
$hora=date("h:i:s");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin:0 0.1cm;
            width:21.5cm;
        }

        /* Etiquetas directas */
        p {
            margin:0;
            font-size:0.4cm;
        }

        hr {
            margin:0;
            width:100%;
            border-color:black;
            justify-self:end;
            align-self:end;
        }
        /* Fin etiquetas directas */



        /* Clases generales */
        .letra-pequenia {
            font-size:0.32cm;
        }

        .negrita {
            font-weight:bold;
        }

        .derecha {
            text-align:right;
        }

        .mb-3 {
            margin-bottom:0.5cm;
        }

        .centrar {
            align-content:center;
        }

        .border-l {
            border-left:1px solid black;
        }
        /* Fin Clases generales */


        .cabecera1 {
            display: grid;
            grid-template-columns:repeat(2, 1fr);
            margin-bottom:0.5cm;
        }

        .cabecera1__separador {
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            text-align:right;
        }

        .cabecera2 {
            display:flex;
            justify-content:space-between;
        }

        .cuadro-concepto {
            margin-top:0.3cm;
            border:1px solid black;
            height:2.5cm;
        }

        .cuadro-concepto {
            display:flex;
            justify-content:space-between;
        }

        .separado {
            display:grid;
            grid-template-rows:min-content 1fr;
        }

        .centrar-total {
            align-content:center;
            text-align:center;
        }

        .firmas {
            margin-top:1.5cm;
            display:flex;
            justify-content:space-around;
        }

        .grid {
            text-align:center;
            width:5cm;
            display:grid;
            grid-template-rows:1fr;
        }

    </style>
</head>
<body>

    <div class="cabecera1">
        <div>
            <img src="../../public/images/head.png" alt="Logo">
            <p class="letra-pequenia"><span class="negrita">RIF:</span> J-31180863-9</p>
        </div>
        <div class="cabecera1__separador">
            <p><span class="negrita">Fecha de Emisión Recibo:</span> <?php echo $fecha_pago; ?></p>
            <p><span class="negrita">Recibo de Pago No:</span> <?php echo $numero_recibo; ?></p>
        </div>
    </div>
    <div class="cabecera2">
        <div>
            <p><span class="negrita">Usuario Contratante:</span> <?php echo $nombre; ?></p>
            <p><span class="negrita">Cédula / RIF:</span> <?php echo $rif; ?></p>
        </div>
        <div class="derecha">
            <p><span class="negrita">Número de Contrato:</span> <?php echo $numero_contrato; ?></p>
            <p><span class="negrita">Número de Recibo Prima:</span> <?php echo $numero_rec_prima; ?></p>
            <p><span class="negrita">Monto Total:</span> <?php echo $total_prima; ?>$</p>
        </div>
    </div>
    <div class="cuadro-concepto">
        <div class="separado">
            <p class="negrita">Concepto o Descripción:</p>
            <p class="centrar"><?php echo $concepto; ?></p>
        </div>
        <div class="separado border-l">
            <p class="negrita">Total Pagado:</p>
            <p class="centrar-total"><?php echo number_format($monto, 2, ',', ''); ?>$</p>
            </div>
        </div>
    </div>

    <div class="cabecera2">
        <p class="mb-3"><?php echo  $f_bus_rec_pagos[descripcion]?></p>
        <p class="mb-3">Son: 
            <?php
            // Mostramos el precio en palabra

            $cantidad=explode(".",$monto);
            $cadenas=count($cantidad);
            
            if ($cantidad[1]<=9) {
                $cero=0;
            } else{
                $cero="";
            }
            $cantidad[1]=substr($cantidad[1],0,2);
            $cantida[1]=substr($cantidad[1],0,1);
                
            if ($cantida[1]==0) {
                $cero="";
            }    


            if($cadenas==2){
                    echo ucwords(numtolet($cantidad[0],"os"))." con ".$cantidad[1]."$cero";
                }else{
                echo ucwords(numtolet($cantidad[0],"os"))." Bolivares. ";
                $cero="";
                }
            
            ?>
        </p>
    </div>

    <p class="centrar-total letra-pequenia mb-3">Yo <?php echo $nombre;?> obrando en nombre propio y de manera voluntaria declaro bajo fé de juramento, que el dinero utilizado para el pago del plan de salud no tiene relación alguna con dinero, capitales, bienes, haberes o títulos valores, producto de las actividades o acciones a que se refiere el artículo 35 de la Ley Orgánica Contra la Delincuencia Organizada y Financiamiento al Terrorismo; todo ello de conformidad con lo establecido en el artículo 52, declaración del origen de los fondos de acuerdo a la Providencia Administrativa Nº SAA-8-004-2021 emanada por la Superintendencia de la Actividad Aseguradora, de fecha 8 de febrero de 2021 y publicada en la Gaceta Oficial de la República Bolivariana de Venezuela N° 42.128 de fecha 17 de mayo del 2021, y/o otras  conductas tipificadas en la legislación Venezolana.</p>

    <div class="cabecera2">
        <div>
            <p class="letra-pequenia"><span class="negrita">Asesor de Venta:</span> <?php echo $comisionado[nombres] . " " . $comisionado[apellidos]; ?></p>
            <p class="letra-pequenia"><span class="negrita">Código:</span> <?php echo $comisionado[codigo]; ?></p>
        </div>
    </div>

    <div class="firmas">
        <div class="grid">
            <hr>
            <p class="negrita">Firma del Operador</p>
        </div>
        <div class="grid">
            <hr>
            <p class="negrita">Firma del Usuario</p>
        </div>
    </div>
</body>
</html>