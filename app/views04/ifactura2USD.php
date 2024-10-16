<?php
/*
ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);
*/
?>
<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' );

$no_factura=$_REQUEST['factura'];
$serie=$_REQUEST['serie'];
$id_admin= $_SESSION['id_usuario_'.empresa];
//buscamos los datos de la factura
$q_factura=("select *
		from tbl_facturas,tbl_series
		where tbl_facturas.numero_factura='$no_factura' and
		      tbl_facturas.id_serie=tbl_series.id_serie and tbl_series.id_serie='$serie'
		      ;");
$r_factura=ejecutar($q_factura);
//if(num_filas($r_factura)==0)	mensaje("No existe una factura con esos parámetros.");
$f_factura=asignar_a($r_factura);
$fechaFactura=$f_factura['fecha_emision'];
$IdCambioMoneda=$f_factura['id_moneda_cambio'];
///////////////////////////////////
//consulta de moneda
	$mostrarNotaCambio=0;
	$sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and id_moneda_cambio='$IdCambioMoneda' order by fecha_cambio DESC , hora_cambio DESC limit 1";
	$DataCambio=ejecutar($sqlCambio);
	$NumFilas=num_filas($DataCambio);
	if($NumFilas>0)
		{$mostrarNotaCambio=1;
		}else{
			$sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and tbl_monedas_cambios.fecha_cambio='$fechaFactura' order by fecha_cambio DESC , hora_cambio DESC limit 1";
			$DataCambio=ejecutar($sqlCambio);
			$NumFilas=num_filas($DataCambio);
			if($NumFilas>0){
				$mostrarNotaCambio=1;
			}else {
				$sqlCambio="select * from tbl_monedas_cambios,tbl_monedas where tbl_monedas_cambios.id_moneda='2' and tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda and tbl_monedas_cambios.fecha_cambio>'$fechaFactura' order by fecha_cambio ASC , hora_cambio DESC limit 1";
				$DataCambio=ejecutar($sqlCambio);
				$NumFilas=num_filas($DataCambio);
				if($NumFilas>0){
					$mostrarNotaCambio=1;
				}
			}
		}

if($mostrarNotaCambio==1){
	$cambio   = assoc_a($DataCambio);
	$id_moneda=$cambio['id_moneda'];//ID ASOCIADO A LA MONEDA
	$moneda_valor=$cambio['valor'];//valor cambiario actaul
	$id_cambio=$cambio['id_moneda_cambio'];//id de cambio actual
}


/////////////////////////////

if($f_factura['condicion_pago']==2 && !empty($f_factura['fecha_credito'])){
	list($ano_c,$mes_c,$dia_c)=explode("-",$f_factura['fecha_credito']);
	$fecha_credito="<td align=\"right\" width=\"150\">A $dia_c DIAS</td>";
}

$q_cliente=("select  clientes.id_ciudad as ciudadcliente,clientes.*,titulares.*,entes.*,tbl_procesos_claves.*,procesos.*
from clientes,titulares,entes,tbl_procesos_claves,procesos
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente and titulares.id_cliente=clientes.id_cliente
;");
		      $r_cliente=ejecutar($q_cliente);
		      $f_cliente=asignar_a($r_cliente);


$q_ciudad=("select  ciudad.*,estados.* from ciudad,estados where ciudad.id_ciudad=$f_cliente[ciudadcliente]  and ciudad.id_estado=estados.id_estado
;");
		      $r_ciudad=ejecutar($q_ciudad);
		      $f_ciudad=asignar_a($r_ciudad);


$q_bene=("select  * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]
;");
                      $r_bene=ejecutar($q_bene);
		      $f_bene=asignar_a($r_bene);

$q_gasto=("select  tipos_servicios.tipo_servicio,gastos_t_b.id_tipo_servicio,count(gastos_t_b.id_tipo_servicio) from gastos_t_b,tbl_procesos_claves,procesos,tipos_servicios
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura]  and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and
gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>'0' group by  tipos_servicios.tipo_servicio,gastos_t_b.id_tipo_servicio order by tipos_servicios.tipo_servicio  desc;");
                     $r_gasto=ejecutar($q_gasto);


?>
<html>
<head>
<title></title>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
</head>
<body>
<script language="JavaScript">
<!--
function enviar(){
	if(document.jforma.factura.value.length == 0){
		alert("El número de la factura es obligatorio.");
	}else{
		document.jforma.submit();
	}
}

-->
</script>

<?php

//MEDIA CARTA VERTICAL para restingir Formato descomente el if y su llave
 // if(($serie == '9')  || ($serie == '3') || ($serie == '1') || ($serie == '5') || ($serie == '6') || ($serie == '4') ||  ($serie == '7') ||  ($serie == '10') ||  ($serie == '11') ){
?>

<table width="50%" cellspacing=0 cellpadding=0 border=0>
<tr>
                <td width="50%" style="padding-top:80px;height: 30px;text-align: left; font-size: 08pt;" valign="top">
                   <b>CONTRIBUYENTE FORMAL</b>
                </td>
</tr>

	<tr>
		<td width="50%" style="padding-top: 01px;height: 30px;text-align: left; font-size: 08pt;" valign="top"><b>Serie <?php echo $f_factura['nomenclatura']; ?></b> <b>No. de factura 00<?php echo $no_factura; ?></b></td>
	</tr>
	<tr>
		<td width="50%" valign="top"><b> <?php if ($f_factura['codigosap']>0){ echo "Codigo Sap $f_factura[codigosap]";} ?> </b></td>

	</tr>
</table>
<table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="left" style="font-size: 08pt">Fecha de Emision:<?php
		                        list($ano_e,$mes_e,$dia_e)=explode("-",$f_factura['fecha_emision']);
					                        echo "$dia_e/$mes_e/$ano_e";

								                ?></td>
	</tr>

 </table>

 	<?php if ($f_factura['con_ente']>0 )

	{
?>
	<table width="50%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="60%" align="left" style="font-size: 08pt">Nombres o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
<?php if ($f_cliente[nombre]=="PARTICULAR")
{
	}
	else
	{
?>
<tr>
		 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>

	 </tr>
<tr>
   		<td width="60%" align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>

	 </tr>
	<?php
	}
	?>
 	<tr>
                 <td  colspan=3 align="center">____________________________________________________________</td>
	 </tr>
	</table>

		<?php
		}
		else
		{
		?>



<?php
if ($f_cliente['no_clave']>'0'){

?>
<table width="50%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="60%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
		 <tr>
		 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>

	 </tr>
<tr>
   		<td width="60%" align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>

	 </tr>
 	<tr>
                 <td  colspan=3 align="center">____________________________________________________________</td>
	 </tr>

<tr>
 	<td width="60%" align="left" style="font-size: 08pt">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
</tr>

	 <tr>
		<td width="60%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]   $f_cliente[apellidos]";?></td>
		<td align="right">Cedula <?php echo $f_cliente[cedula] ?></td>                                                               </tr>

<?php
if ($f_cliente[id_beneficiario]>'0') {
?>
	<tr>
                 <td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres]  $f_bene[apellidos]";?></td>
		<td align="right">Cedula <?php echo $f_bene[cedula] ?></td>
	</tr>

<?php
 }
  else
  {
  }
?>


</table>

<?php
 }
 else
 {
 ?>

<table width="50%" cellspacing=0 cellpadding=0 border=0>

<tr>
	         <td width="80%" align="left" style="font-size: 08pt">Cliente: <?php echo $f_cliente['nombre'];?>
			</tr>
			<?php if ($f_cliente['nombre']=="PARTICULAR")
{
	}
	else
	{
?>
		 <tr>
		 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente['rif']; ?></td>
	 </tr>

   	<tr>
   		<td width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente['direccion'];?></td>

	 </tr>

	<?php
	}
	?>
 	<tr>
                 <td  colspan=3 align="center">____________________________________________________________</td>
	 </tr>
     <table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="60%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]"?></td>
		<td>&nbsp;</td>
		<td align="right" style="font-size: 08pt">Cedula <?php echo $f_cliente['cedula']; ?></td>
	</tr>

    </table>
<?php
if ($f_cliente['id_beneficiario']>'0') {
?>
<table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
                 <td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo $f_bene['nombres']."".$f_bene['apellidos'];?></td>
		 <td align="right" style="font-size: 08pt">Cedula <?php echo $f_bene['cedula'] ?></td>
	</tr>
    <?php
    }
    ?>
</table>

<?php
}
}
?>

<table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
			<td  align="right">Condicion Pago:<?php
				if($f_factura['condicion_pago']==1)
					echo "Contado";
				else if($f_factura['condicion_pago']==2)
					echo "Cr&eacute;dito $fecha_credito";
				else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 || $f_factura['condicion_pago']==6 || $f_factura['condicion_pago']==7 )
					echo "Contado";
		?>
		</td>
		</tr>
</table>



<br>
<table width="50%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
	<td colspan=2 width="50%" align="left" style="font-size: 08pt">Concepto o Descripcion</td>

	<td width="10%" align="right" style="font-size: 08pt">Total Bs.S.</td>
	</tr>
	<?php
	 $total=0;

		if  ($f_factura['concepto']<>"") {
		 if ($f_cliente['id_recibo_contrato']>0)
	{
        $total=$f_cliente['monto'];
        }
        else
        {
            $q_gasto=("select  titulares.*,gastos_t_b.*,tbl_procesos_claves.*
from titulares,gastos_t_b,tbl_procesos_claves,procesos
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and
gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>'0'
;");
                     $r_gasto=ejecutar($q_gasto);

		while($f_gasto=asignar_a($r_gasto)){
		                 $total= ($total + $f_gasto['monto_aceptado']);
						}

                        }
				?>
			<tr>
		<td width="50%" align="left" style="font-size: 07pt"><?php echo $f_factura['concepto'];  ?></td>
			<td width="5%" align="left"></td>

			<td width="10%" align="right" style="font-size: 07pt"><?php echo  montos_print($total)?></td>
		</tr>
		<?php

		}

		else
		{
		while($f_gasto=asignar_a($r_gasto)){


                ?>
            <tr>
		<td colspan=2 width="70%" align="left" style="font-size: 07pt"><?php echo $f_gasto['tipo_servicio'];  ?></td>
		 <td width="5%" align="left"></td>

		</tr>
            <?php

                $q_gasto1=("select gastos_t_b.*,tbl_procesos_claves.*
from gastos_t_b,tbl_procesos_claves
where tbl_procesos_claves.id_proceso=gastos_t_b.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura]  and gastos_t_b.id_tipo_servicio=$f_gasto[id_tipo_servicio]
 and gastos_t_b.monto_aceptado>'0';");
                     $r_gasto1=ejecutar($q_gasto1);


             $serviciosclinicos="";
             $monto=0;
             $deducible=0;
           while($f_gasto1=asignar_a($r_gasto1))
		{

 		$monto= $monto + $f_gasto1['monto_aceptado'];
		$total= ($total + $f_gasto1['monto_aceptado']);

                  if ($f_gasto['id_tipo_servicio']==14 || $f_gasto['id_tipo_servicio']==15 || $f_gasto['id_tipo_servicio']==16 || $f_gasto['id_tipo_servicio']==17)
	                {
	                  $q_p=("select especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
		                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
		                s_p_proveedores,proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
		                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor
		                and proveedores.id_proveedor=$f_gasto1[id_proveedor] and
		                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
		                order by personas_proveedores.nombres_prov");
		                $r_p=ejecutar($q_p);
		                $f_p=asignar_a($r_p);
		                $proveedor="$f_p[nombres_prov] $f_p[apellidos_prov] RIF: $f_p[rifcheque]";
			?>
			<tr>
            		<td width="5%" align="left"></td>
			<td width="60%" align="left" style="font-size: 07pt"><?php echo "$proveedor";?></td>
			<td width="5%" align="right" style="font-size: 07pt"><?php echo montos_print($f_gasto1['monto_aceptado']);?></td>
			</tr>
			<?php

	                }
	                   if ($f_gasto['id_tipo_servicio']==9 || $f_gasto['id_tipo_servicio']==13 )
				{
			                $serviciosclinicos=" ";
		                }
		                else
		                {
			                $serviciosclinicos=" ";
		                }

		}
 			if ($f_gasto['id_tipo_servicio']==14 || $f_gasto['id_tipo_servicio']==15 || $f_gasto['id_tipo_servicio']==16 || $f_gasto['id_tipo_servicio']==17)
	                {
?>
<tr>
            		<td width="5%" align="left"> .</td>
			<td width="60%" align="left">
			</td>
			<td width="5%" align="right"> </td>
		</tr>
<?php
			}
			else
			{
				?>

			<tr>
            		<td width="5%" align="left"></td>
			<td width="60%" align="left" style="font-size: 07pt"><?php if ($f_gasto['id_tipo_servicio']==22 || $f_gasto['id_tipo_servicio']==23 )
							{
						                echo " $serviciosclinicos ";
					                }
					                else
					                {
					                       echo " $serviciosclinicos ";
					                }?>
			</td>
			<td width="5%" align="right" style="font-size: 07pt"><?php echo montos_print($monto);?></td>
		</tr>
		<?php
		}
    }
    }
    ?>
<tr>
		<td colspan=3>
		<br>

		</td>
	</tr>

</table>
		<table width="50%" cellspacing=0 cellpadding=0 border=0>
<?php
																$cantidad=explode(".",$total);
                                $cadenas=count($cantidad);
                                if ($cantidad[1]<=9)
																	{$cero=0;}
                                else
                                	{$cero="";}
                                $cantidad[1]=substr($cantidad[1],0,2);
                                $cantida[1]=substr($cantidad[1],0,1);
                                if ($cantida[1]==0){
                                	$cero="";
                                }
?>

	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO Bs.S.
    		</td>


				<td width="180" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total); echo  $cero;?></td>
			</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">Base imponible segun alicuota_% Bs.S.
    		</td>


				<td width="180" valign="top" align="right"> </td>
			</tr>
			</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">Monto Total del impuesto segun alicuota_% Bs.S.
    		</td>


				<td width="180" valign="top" align="right"> </td>
			</tr>
			</tr>



        <?php
         $q_deducible=("select
                                                sum(tbl_procesos_claves.fac_deducible)
                                        from
                                                tbl_procesos_claves
                                        where
                                                tbl_procesos_claves.id_factura='$f_factura[id_factura]' ");
                     $r_deducible=ejecutar($q_deducible);
                    $f_deducible=asignar_a($r_deducible);



         if ($f_deducible['sum']>0)
        {
        ?>

             	</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO DEDUCIBLE Bs.S.
    		</td>


				<td width="180" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print($f_deducible[sum]);  ?> </td>
			</tr>
			</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA Bs.S.
    		</td>


				<td width="180" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total-$f_deducible[sum]);  ?></td>
			</tr>

		</table>

        <?php
        }
        else
        {
            ?>
            	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA Bs.S.
    		</td>


				<td width="180" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total);  echo  $cero;?></td>
			</tr>
            </table>
            <?php

            }

            $tazaCambio=$moneda_valor;
            $totalUSD=$total/$tazaCambio;
            $totalUSD=round($totalUSD, 2);
           ?>
           <table width="50%" cellspacing=0 cellpadding=0 border=0 >
           <tr ><td colspan="2" style="font-size: 07pt"><q >A los efectos de lo previsto en el Art. 25 de la ley del Impuesto al Valor Agregado, se expresa los montos de la factura en tasa de cambio establecida por BCV. 1/USD por BS. </q>
      				<br> Tasa de cambio BCV: <?php echo $tazaCambio; ?> BS.  </td></tr>
           <tr><td style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO: USD <?php echo $totalUSD; ?></td></tr>
           <tr><td style="font-size: 07pt">MONTO TOTAL DE LA VENTA USD <?php echo $totalUSD; ?> </td></tr>
           </table>

<?php
 /* **** Se registra lo que hizo el usuario**** */
$log="Imprimio la Factura numero $no_factura";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
/// restccion de MEDIA CARTA a SERIE }
        ?>




</div>
</body>
</html>
