<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' );

$no_factura=$_REQUEST['factura'];//0001
$serie=$_REQUEST['serie'];//10

$id_admin= $_SESSION['id_usuario_'.empresa];


/* **** se buscan los datos para volverlos a registrar **** */
//buscamos los datos de la factura
$q_factura=("select *
		from tbl_facturas,tbl_series
		where tbl_facturas.numero_factura='$no_factura' and
		      tbl_facturas.id_serie=tbl_series.id_serie and tbl_series.id_serie='$serie'
		      ;");
$r_factura=ejecutar($q_factura);
//if(num_filas($r_factura)==0)	mensaje("No existe una factura con esos parámetros.");
$f_factura=asignar_a($r_factura);

$descuento = $f_factura[descuento];

if($f_factura['condicion_pago']==2 && !empty($f_factura['fecha_credito'])){
	list($ano_c,$mes_c,$dia_c)=explode("-",$f_factura['fecha_credito']);
	$fecha_credito="<td align=\"right\" width=\"150\">A $dia_c DIAS</td>";
}

$b_IGTF= ("select * from variables_globales where  nombre_var='IGTF' ");
$t_IGTF=ejecutar($b_IGTF);
$q_IGTF=asignar_a($t_IGTF);
$IGTF= $q_IGTF['cantidad'];
$porcientoIGTF= $q_IGTF['comprasconfig'].' %';
//
$q_cliente=("select  clientes.id_ciudad as ciudadcliente,clientes.*,titulares.*,entes.*,tbl_procesos_claves.*,procesos.*
from clientes,titulares,entes,tbl_procesos_claves,procesos
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and
titulares.id_ente=entes.id_ente and titulares.id_cliente=clientes.id_cliente
;");
		      $r_cliente=ejecutar($q_cliente);

            if(num_filas($r_cliente)==0){
                  $q_cliente=("select  entes.*,tbl_procesos_claves.*,tbl_recibo_contrato.*,tbl_contratos_entes.*
from entes,tbl_procesos_claves,tbl_recibo_contrato,tbl_contratos_entes,tbl_facturas
where tbl_facturas.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and
tbl_facturas.id_factura=tbl_procesos_claves.id_factura and tbl_facturas.id_factura=$f_factura[id_factura] and
tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente and
tbl_contratos_entes.id_ente=entes.id_ente
;");
                     $r_cliente=ejecutar($q_cliente);
                  }


		      $f_cliente=asignar_a($r_cliente);


$q_ciudad=("select  ciudad.*,estados.* from ciudad,estados where ciudad.id_ciudad=$f_cliente[ciudadcliente]  and ciudad.id_estado=estados.id_estado
;");
		      $r_ciudad=ejecutar($q_ciudad);
		      $f_ciudad=asignar_a($r_ciudad);


$q_bene=("select  * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]
;");
                      $r_bene=ejecutar($q_bene);
		      $f_bene=asignar_a($r_bene);

$q_gasto=("select  titulares.*,gastos_t_b.*,tbl_procesos_claves.*
from titulares,gastos_t_b,tbl_procesos_claves,procesos
where tbl_procesos_claves.id_proceso=procesos.id_proceso and
tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and
gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>='0'
;");

                     $r_gasto=ejecutar($q_gasto);
                     $cuantosp=num_filas($r_gasto);

//$q_noaprovo=("select sum(cast(gastos_t_b.monto_reserva as double precision)),gastos_t_b.descripcion from titulares,gastos_t_b,tbl_procesos_claves,procesos where tbl_procesos_claves.id_proceso=procesos.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>'0' and (gastos_t_b.id_tipo_servicio=27 or gastos_t_b.id_tipo_servicio=28) group by gastos_t_b.descripcion;  ");
$busidfactura = ejecutar("select tbl_facturas.id_factura from tbl_facturas where id_serie=$serie and numero_factura='$no_factura';");
$repdusidfactura = assoc_a($busidfactura);
$laidfactura = $repdusidfactura['id_factura'];
$buslaplanilla = ejecutar("select procesos.nu_planilla from tbl_procesos_claves,procesos
where tbl_procesos_claves.id_factura = $laidfactura and
tbl_procesos_claves.id_proceso = procesos.id_proceso limit 1;");
$repbusplanilla = assoc_a($buslaplanilla);
$laplanillaes = $repbusplanilla['nu_planilla'];
//$laplanillaes = '8012891';

if($laplanillaes>0){
$q_noaprovo=("select sum(cast(gastos_t_b.monto_reserva as double precision)),gastos_t_b.descripcion from
gastos_t_b,procesos
where
procesos.nu_planilla='$laplanillaes' and
gastos_t_b.id_proceso=procesos.id_proceso and
gastos_t_b.monto_aceptado>'0' and
(gastos_t_b.id_tipo_servicio=27 or gastos_t_b.id_tipo_servicio=28) group by gastos_t_b.descripcion;");
$r_qnoaprovo=ejecutar($q_noaprovo);
$cuantosnap=num_filas($r_qnoaprovo);
$datmontonoapo=assoc_a($r_qnoaprovo);
$elmontonoaprobado=$datmontonoapo[sum];
$ladescripcionmono=$datmontonoapo['descripcion'];
}


$moneda            =("select * from tbl_oper_multi where  tbl_oper_multi.id_factura=$f_factura[id_factura] ");
$tipo_monedas      = ejecutar($moneda);


$IGTFactivo=0;
$montoigtf=0;
while($resul_tipo_monedas= asignar_a($tipo_monedas)){

	$id_moneda=$resul_tipo_monedas['id_moneda'];

   	if($id_moneda!=1){

		$monto_o_m=$resul_tipo_monedas['monto']+$monto_o_m;
		$IGTFactivo=1;

	} else if ($descuento > 0){

		$monto_o_m=$resul_tipo_monedas['monto']+$monto_o_m;
		
	}
}

if ($descuento > 0) {
	$montoConDescuento = $monto_o_m - ($monto_o_m * ($descuento / 100));
	// Si hay descuento sacamos el igtf en base a él
	if ($IGTFactivo == 1) {
		$montoigtf= $montoConDescuento * $IGTF;
	}
} else {
	$montoigtf= $monto_o_m * $IGTF;
}




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
  if(($serie == '2')){
?>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>

<div align="center">
<table width="100%" cellspacing=0 cellpadding=0 border=0><tr>

		<td width="70%" valign="top"></td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right;" valign="top"><b>CONTRIBUYENTE FORMAL</b></td>
	</tr>

	<tr>
		<td width="70%" valign="top"></td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right;" valign="top"><b>Serie <?php echo $f_factura['nomenclatura']; ?></b> <b>No. de factura 00<?php echo $no_factura; ?></b></td>
	</tr>
		<tr>
		<td width="70%" valign="top"><b> <?php if ($f_factura[codigosap]>0){ echo "Codigo Sap $f_factura[codigosap]";} ?> </b></td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right;" valign="top"> </td>
	</tr>
</table>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="right">Fecha de Emision:<?php
		                        $fech=explode("-",$f_factura['fecha_emision']);
														$fechaEmision="$fech[2]/$fech[1]/$fech[0]";
																	echo "$fechaEmision";

								                ?></td>
	</tr>
</table>

<?php if ($f_factura[con_ente]>0)
{
	?>
	<table width="100%" cellspacing=0 cellpadding=0 border=0>

		<tr>
			<td width="80%" align="left">Nombre o razon socialss: <?php echo $f_cliente[nombre];?>
		</tr>
		<tr>
			<td align="left">RIF: <?php echo $f_cliente[rif]; ?></td>
		</tr>

		<tr>
			<td width="80%" align="left">Direccion: <?php echo $f_cliente[direccion];?></td>
		</tr>
		<tr>
			<td width="80%" align="left">Telefono: <?php echo $f_cliente[telefonos];?></td>
		</tr>

		<?php
		if ($f_cliente[id_recibo_contrato]>0)
		{
			?>
			<tr>
				<td colspan=3  width="80%" align="left"><?php echo "Nro. Recibo:  $f_cliente[num_recibo_prima] Vigencia del $f_cliente[fecha_ini_vigencia] al $f_cliente[fecha_fin_vigencia]"?> </td>
			</tr>
			<?php
		}
		?>

		<tr>
			<td  colspan=3 align="center">__________________________________________________________________</td>
		</tr>
	</table>

	<?php
}
else
{
	?>


	<?php
	if ($f_cliente[no_clave]>'0' and  $f_cliente[nombre]!='PARTICULAR'){
		?>
		<table width="100%" cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td width="80%" align="left">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
			<tr>
				<td align="left">RIF: <?php echo $f_cliente[rif]; ?></td>
			</tr>

			<tr>
				<td width="80%" align="left">Direccion: <?php echo $f_cliente[direccion];?></td>
			</tr>
			<tr>
				<td width="80%" align="left">Telefono: <?php echo $f_cliente[telefonos];?></td>
			</tr>
			<tr>
				<td  colspan=3 align="center">__________________________________________________________________</td>
			</tr>

			<tr>
				<td width="80%" align="left">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
			</tr>

			<tr>
				<td width="80%" align="left">Titular: <?php echo "$f_cliente[nombres]   $f_cliente[apellidos]";?></td>
				<td align="right">Cedula <?php echo $f_cliente[cedula] ?></td>
			</tr>

			<?php
			if ($f_cliente[id_beneficiario]>'0') {
				?>
				<tr>
					<td width="80%" align="left">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>
					<td align="right">Cedula <?php echo $f_bene[cedula] ?></td>
				</tr>
				<?php
			}
			?>


		</table>

		<?php
	}
	else
	{
		?>

		<table width="100%" cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td width="80%" align="left">Nombre o razon social: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]"?></td>
				<td>&nbsp;</td>
				<td align="right">Cedula/ RIF: <?php echo $f_cliente[cedula]; ?></td>
			</tr>
			<tr>
				<td colspan=3  width="80%" align="left">Direccion: <?php echo $f_cliente[direccion_hab]?> <?php echo $f_ciudad[ciudad]?>  edo <?php echo $f_ciudad[estado]?>   Telefonos: <?php echo $f_cliente[telefono_hab]?></td>
			</tr>

		</table>

		<?php
	}
}
?>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td  align="right">Condicion Pago:<?php
		if($f_factura['condicion_pago']==1)
			echo "Pagada";
		else if($f_factura['condicion_pago']==2)
			echo "Cr&eacute;dito $fecha_credito";
		else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 || $f_factura['condicion_pago']==6 || $f_factura['condicion_pago']==7 || $f_factura['condicion_pago']==8 )
			echo "Pagada";

		?>
		</td>
	</tr>
</table>



<br><br>
<table width="100%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
		<td width="70%" align="left">Concepto o Descripcion</td>
		<td width="10%" align="left"></td>
		<td width="20%" align="right">Total Bs.</td>
	</tr>
	<?php
		if(($cuantosp==0) and ($cuantosnap>0)){
			?>
			<tr>
				<td width="70%" align="left">
					<?php echo "$ladescripcionmono";  ?>
				</td>
				<td width="10%" align="left"></td>
				<td width="20%" align="right"><?php echo  montos_print($elmontonoaprobado)?></td>
			</tr>
	<?  }
	$total=0;

	if  ($f_factura[concepto]<>"") {
		if ($f_cliente[id_recibo_contrato]>0)
		{
        	$total=$f_cliente[monto];
        }
        else
        {
			while($f_gasto=asignar_a($r_gasto)){
				$total= ($total + $f_gasto[monto_aceptado]);
			}

		}
		?>
		<tr>
			<td width="70%" align="left"><?php echo $f_factura[concepto];  ?></td>
			<td width="10%" align="left"></td>

			<td width="20%" align="right"><?php echo  montos_print($total)?></td>
		</tr>
		<?php

	}

	else
	{
		while($f_gasto=asignar_a($r_gasto)){
			$total= ($total + $f_gasto[monto_aceptado]);
			?>
			<tr>
				<td width="70%" align="left"><?php echo "$f_gasto[nombre] ($f_gasto[descripcion] )";  ?></td>
				<td width="10%" align="left"></td>

				<td width="20%" align="right"><?php echo montos_print($f_gasto[monto_aceptado])?></td>
			</tr>
			<?php
		}
	}
	?>


	<tr>
		<td colspan=3>
		<br><br><br>
		</td>
	</tr>

</table>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<?php

	$cantidad=explode(".",$total);
	$cadenas=count($cantidad);
	if ($cantidad[1]<=9) {
		$cero=0;
	}

	else {
		$cero="";
	}

	$cantidad[1]=substr($cantidad[1],0,2);
	$cantida[1]=substr($cantidad[1],0,1);

	if ($cantida[1]==0){
		$cero="";
	}
	?>

	<tr>
		<td  valign="top" align="right">MONTO TOTAL EXENTO O EXONERADO Bs.</td>
		<td width="200" valign="top" align="right"> <?php echo montos_print($total); echo  $cero;?></td>
	</tr>

	<tr>
		<td  valign="top" align="right">Base imponible segun alicuota_% Bs.
		</td>

		<td width="200" valign="top" align="right"> </td>
	</tr>

	</tr>
	<tr>
		<td  valign="top" align="right" style="font-size: 07pt">Monto Total del impuesto segun alicuota_% Bs.
		</td>

		<td width="200" valign="top" align="right"> </td>
	</tr>


	<?php
	$q_deducible=("select sum(tbl_procesos_claves.fac_deducible)
	from
		tbl_procesos_claves
	where
		tbl_procesos_claves.id_factura='$f_factura[id_factura]' ");

	$r_deducible=ejecutar($q_deducible);
	$f_deducible=asignar_a($r_deducible);


	if (($f_deducible[sum]>0) || ($elmontonoaprobado>=1))
	{
        ?>

		</tr>
		<tr>
			<td  valign="top" align="right">MONTO DEDUCIBLE Bs.
    		</td>

			<td width="200" valign="top" align="right"><?php echo montos_print(abs($f_deducible[sum]));  ?> </td>
		</tr>
		<tr>
			<td  valign="top" align="right">MONTO NO APROBADO Bs.
    		</td>

			<td width="200" valign="top" align="right"><?php echo montos_print(abs($elmontonoaprobado));  ?> </td>
		</tr>
		<?php

		//calculo de IGTF
		if($IGTFactivo==1){

			?>

			<tr>
				<td  valign="top" align="right">MONTO TOTAL DE LA VENTA Bs.</td>

				<td width="200" valign="top" align="right"> <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado));  ?></td>
			</tr>

			<tr>
				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido
    			</td>
				<td width="200" valign="top" align="right"><?php echo montos_print(abs($montoigtf));  ?> </td>
			</tr>
			<?php

 			//fin del calculo IGTF
		}
		?>

		<tr>
			<td  valign="top" align="right">MONTO TOTAL A PAGAR Bs...
    		</td>

			<td width="200" valign="top" align="right"> <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado + $montoigtf));  ?></td>
		</tr>

</table>

        <?php
    }
	else
	{
		?>
		<?php

		//calculo de IGTF
		if($IGTFactivo==1){

			?>

			<tr>

				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido
				</td>

				<td width="200" valign="top" align="right"><?php echo montos_print(abs($montoigtf));  ?> </td>
			</tr>
			<?php

			//fin del calculo IGTF
		}?>

				</tr>
					<tr>
					<td  valign="top" align="right">MONTO TOTAL A PAGAR Bs...
				</td>


					<td width="200" valign="top" align="right"> <?php echo montos_print(abs($total + $montoigtf));  echo  $cero;?></td>
				</tr>

</table>
				<?php
	}

        /* **** Se registra lo que hizo el usuario**** */
        ?>
<?php
  }
  //Apartir de esta linea es MEDIA CARTA HORIZONTAL
  if( ($serie == 0)  ){
?>
 <br>
<div align="center">
<table width="100%" cellspacing=0 cellpadding=0 border=0><tr>

		<td width="70%" valign="top"></td>
		<td width="30%" style="padding-top: 01px;height: 30px;text-align: right; font-size: 08pt;" valign="top"><b>CONTRIBUYENTE FORMAL</b></td>
	</tr>

	<tr>
		<td width="70%" valign="top"></td>
		<td width="30%" style="padding-top: 01px;height: 30px;text-align: right; font-size: 08pt;" valign="top"><b><br>Serie <?php echo $f_factura['nomenclatura']; ?></b> <b>No. de factura 00<?php echo $no_factura; ?></b></td>
	</tr>
		<tr>
		<td width="70%" valign="top"><b> <?php if ($f_factura[codigosap]>0){ echo "Codigo Sap $f_factura[codigosap]";} ?> </b></td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right; font-size: 08pt;" valign="top"> </td>
	</tr>
</table>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="right" style="font-size: 08pt">Fecha de Emision:<?php
		                        $fech=explode("-",$f_factura['fecha_emision']);
														$fechaEmision="$fech[2]/$fech[1]/$fech[0]";
					                        echo "$fechaEmision";


								                ?></td>
	</tr>
</table>

	<?php if ($f_factura[con_ente]>0)
	{
        ?>
	<table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="80%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
		 <tr>
		 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="80%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>

	 </tr>
<tr>
   		<td width="80%" align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>

	 </tr>

	<?php
			 if ($f_cliente[id_recibo_contrato]>0)
	{
		?>
         <tr>
	         <td colspan=3  width="80%" align="left"><?php echo "Nro. Recibo:  $f_cliente[num_recibo_prima] Vigencia del $f_cliente[fecha_ini_vigencia] al $f_cliente[fecha_fin_vigencia]"?> </td>
	</tr>
        <?php
		}
		?>

 	<tr>
                 <td  colspan=3 align="center">__________________________________________________________________</td>
	 </tr>
	</table>

		<?php
		}
		else
		{
		?>



<?php
if ($f_cliente[no_clave]>'0' and  $f_cliente[nombre]!='PARTICULAR'){
?>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="80%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
		 <tr>
		 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="80%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion];?></td>

	 </tr>
<tr>
   		<td width="80%" align="left" style="font-size: 08pt">Telefono: <?php echo $f_cliente[telefonos];?></td>

	 </tr>
 	<tr>
                 <td  colspan=3 align="center">__________________________________________________________________</td>
	 </tr>

<tr>
 	<td width="80%" align="left" style="font-size: 08pt">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
</tr>

	 <tr>
		<td width="80%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]   $f_cliente[apellidos]";?></td>
		<td align="right" style="font-size: 08pt">Cedula <?php echo $f_cliente[cedula] ?></td>                                                               </tr>

<?php
if ($f_cliente[id_beneficiario]>'0') {
?>
	<tr>
                 <td width="80%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>
		<td align="right" style="font-size: 08pt">Cedula <?php echo $f_bene[cedula] ?></td>
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

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="80%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]"?></td>
		<td>&nbsp;</td>
		<td align="right" style="font-size: 08pt">Cedula/ RIF: <?php echo $f_cliente[cedula]; ?></td>
	</tr>
        <tr>
	         <td colspan=3  width="80%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion_hab]?> <?php echo $f_ciudad[ciudad]?>  edo <?php echo $f_ciudad[estado]?>   Telefonos: <?php echo $f_cliente[telefono_hab]?></td>
	</tr>

</table>

<?php
}
}
?>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
			<td  align="right">Condicion Pago:<?php
				if($f_factura['condicion_pago']==1)
					echo "Pagada";
				else if($f_factura['condicion_pago']==2)
					echo "Cr&eacute;dito $fecha_credito";
				else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 || $f_factura['condicion_pago']==6 || $f_factura['condicion_pago']==7 || $f_factura['condicion_pago']==8 )
					echo "Pagada";
		?>
		</td>
		</tr>
</table>



<br>
<table width="100%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
	<td width="70%" align="left" style="font-size: 08pt">Concepto o Descripcion</td>
	<td width="10%" align="left"></td>
	<td width="20%" align="right" style="font-size: 08pt">Total Bs.</td>
	</tr>
	<?php
	 $total=0;

	if  ($f_factura[concepto]<>"") {
		 if ($f_cliente[id_recibo_contrato]>0)
	{
        $total=$f_cliente[monto];
        }
        else
        {
		while($f_gasto=asignar_a($r_gasto)){
		                 $total= ($total + $f_gasto[monto_aceptado]);
						}
                        }
				?>
			<tr>
		<td width="70%" align="left" style="font-size: 07pt"><?php echo $f_factura[concepto];  ?></td>
			<td width="10%" align="left"></td>

			<td width="20%" align="right" style="font-size: 07pt"><?php echo  montos_print($total)?></td>
		</tr>
		<?php

		}

		else
		{
			while($f_gasto=asignar_a($r_gasto)){
		                 $total= ($total + $f_gasto[monto_aceptado]);
				?>
			<tr>
		<td width="70%" align="left" style="font-size: 07pt">
			<?php
			if ($f_gasto[id_tipo_servicio] == 28 || $f_gasto[id_tipo_servicio == 27]) {
				echo $f_gasto["nombre"];
			} else {
				echo "$f_gasto[nombre] ($f_gasto[descripcion] )";
			}
			?>
			</td>
			<td width="10%" align="left"></td>

			<td width="20%" align="right" style="font-size: 07pt"><?php echo montos_print($f_gasto[monto_aceptado])?></td>
		</tr>
	<?php
	}
	}
	?>



<tr>
		<td colspan=3>
		<br>

		</td>
	</tr>

</table>
		<table width="100%" cellspacing=0 cellpadding=0 border=0>
<?php

                                $cantidad=explode(".",$total);
                                $cadenas=count($cantidad);
                                                                 if ($cantidad[1]<=9) {
                                                                        $cero=0;}
                                                                        else
                                                                        {$cero="";}
                                                                $cantidad[1]=substr($cantidad[1],0,2);
                                                                $cantida[1]=substr($cantidad[1],0,1);
                                                                if ($cantida[1]==0){
                                                                        $cero="";
                                                                        }
?>

	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO Bs.
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total); echo  $cero;?></td>
	</tr>

	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">Base imponible segun alicuota_% Bs.
    		</td>


				<td width="200" valign="top" align="right"> </td>
	</tr>


	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">Monto Total del impuesto segun alicuota_% Bs.
    		</td>


				<td width="200" valign="top" align="right"> </td>
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



         if (($f_deducible[sum]>0) || ($elmontonoaprobado>=1))
        {
        ?>

             	</tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO DEDUCIBLE Bs.
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print(abs($f_deducible[sum]));  ?> </td>
			</tr>
<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO NO APROBADO Bs.
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print(abs($elmontonoaprobado));  ?> </td>
			</tr>


				<?php
      //calculo de IGTF

	 if($IGTFactivo==1){



	?>

<tr>
	<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA Bs...
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado ));  ?></td>
			</tr>

				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido
    		</td>


				<td width="200" valign="top" align="right"><?php echo montos_print(abs($montoigtf));  ?> </td>
			</tr>
<?php

 //fin del calculo IGTF
}?>

				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR Bs...
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado + $montoigtf));  ?></td>
			</tr>

		</table>

        <?php
        }
        else
        {
            ?>
	<?php
      //calculo de IGTF

	 if($IGTFactivo==1){



	?>

<tr>

		<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA Bs.
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($total));  echo  $cero;?></td>
			</tr>

				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido
    		</td>

				<td width="200" valign="top" align="right" tyle="font-size: 07pt"><?php echo montos_print(abs($montoigtf));  ?> </td>
			</tr>


			</tr>

<?php

 //fin del calculo IGTF
}?>



            </tr>
				<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR Bs.
    		</td>


				<td width="200" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($total + $montoigtf));  echo  $cero;?></td>
			</tr>

		</table>
            <?php
            }

        /* **** Se registra lo que hizo el usuario**** */
        ?>





<?php
  }
?>

<?php

 //Apartir de esta linea es MEDIA CARTA vertival
if( ($serie == '9')  || ($serie == '3') || ($serie == '1') || ($serie == '5') || ($serie == '6') || ($serie=='7') ||($serie == '4') || ($serie == '8') || ($serie == '10') || ($serie == '11')){
?>

<table width="50%" cellspacing=0 cellpadding=0 border=0>

	<tr>
		<td width="50%" style="padding-top:80px;height: 30px;text-align: left; font-size: 08pt;" valign="top"><b>CONTRIBUYENTE FORMAL</b>
		</td>
	</tr>

	<tr>
		<td width="50%" style="padding-top: 01px;height: 30px;text-align: left; font-size: 08pt;" valign="top"><b>Serie <?php echo $f_factura['nomenclatura']; ?></b> <b>No. de factura 00<?php echo $no_factura; ?></b></td>

		<td width="50%" valign="top"><b>
			<?php
			if ($f_factura[codigosap]>0){
				echo "Codigo Sap $f_factura[codigosap]";
			} ?>
		</b></td>

	</tr>
</table>
<table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="left" style="font-size: 08pt">Fecha de Emision:
		<?php
			$fech=explode("-",$f_factura['fecha_emision']);
			$fechaEmision="$fech[2]/$fech[1]/$fech[0]";
			echo "$fechaEmision";
		?>
		</td>
	</tr>
</table>

	<?php
if ($f_factura[con_ente]>0)
{
	?>
	<table width="50%" cellspacing=0 cellpadding=0 border=0>

        <tr>
	         <td width="75%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo $f_cliente[nombre];?>
		</tr>
		<tr>
			 <td align="left" style="font-size: 08pt">RIF: <?php echo $f_cliente[rif]; ?></td>
	 	</tr>

		<tr>
			<td width="75%" align="left" style="font-size: 08pt">Direccion:<?php echo $f_cliente[direccion];?></td>
		</tr>
		<tr>
   			<td width="75%" align="left" style="font-size: 08pt">Telefono:<?php echo $f_cliente[telefonos];?></td>
	 	</tr>

		<?php
		if ($f_cliente[id_recibo_contrato]>0)
		{
			?>
         	<tr>
	        	<td colspan=3  width="80%" align="left"><?php echo "Nro. Recibo:  $f_cliente[num_recibo_prima] Vigencia del $f_cliente[fecha_ini_vigencia] al $f_cliente[fecha_fin_vigencia]"?> </td>
			</tr>
        	<?php
		}
		?>
        <tr>
 			<td width="60%" align="left">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
		</tr>

 		<tr>
			<td  colspan=3 align="center">____________________________________________________________</td>
	 	</tr>
	 	<tr>
			<td width="60%" align="left" style="font-size: 08pt">Titular: <?php echo "$f_cliente[nombres]   $f_cliente[apellidos]";?></td>
			<td align="right" style="font-size: 08pt">Cedula <?php echo $f_cliente[cedula] ?>
			</td>
		</tr>

		<?php
		if ($f_cliente[id_beneficiario]>'0') {
			?>
			<tr>
                <td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>
				<td align="right" style="font-size: 08pt">Cedula <?php echo $f_bene[cedula] ?></td>
			</tr>

			<?php
		}
		?>
	</table>
		<?php
}
else
{
	?>



	<?php
	if ($f_cliente[no_clave]>'0' and  $f_cliente[nombre]!='PARTICULAR'){
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
				<td align="right" style="font-size: 08pt">Cedula <?php echo $f_cliente[cedula] ?></td>
			</tr>

			<?php
			if ($f_cliente[id_beneficiario]>'0') {
				?>

				<tr>
                	<td width="60%" align="left" style="font-size: 08pt">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>
					<td align="right" style="font-size: 08pt">Cedula <?php echo $f_bene[cedula] ?></td>
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
				<td width="60%" align="left" style="font-size: 08pt">Nombre o razon social: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]"?></td>
				<td>&nbsp;</td>
				<td align="right" style="font-size: 08pt">Cedula/ RIF: <?php echo $f_cliente[cedula]; ?></td>
			</tr>
        	<tr>
	        	<td colspan=3  width="60%" align="left" style="font-size: 08pt">Direccion: <?php echo $f_cliente[direccion_hab]?> <?php echo $f_ciudad[ciudad]?>  edo <?php echo $f_ciudad[estado]?>   Telefonos: <?php echo $f_cliente[telefono_hab]?></td>
			</tr>

		</table>

		<?php
	}
}
?>

<table width="50%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td  align="right">Condicion Pago:<?php
			if($f_factura['condicion_pago']==1)
				echo "Pagada";
			else if($f_factura['condicion_pago']==2)
				echo "Cr&eacute;dito $fecha_credito";
			else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 || $f_factura['condicion_pago']==6 || $f_factura['condicion_pago']==7 || $f_factura['condicion_pago']==8 )
				echo "Pagada";
			?>
		</td>
	</tr>
</table>

<br>

<table width="50%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
		<td width="50%" align="left" style="font-size: 08pt">Concepto o Descripcion</td>
		<td width="5%" align="left"></td>
		<td width="10%" align="right" style="font-size: 08pt">Total Bs.</td>
	</tr>
	<?php
	$total=0;

	if  ($f_factura[concepto]<>"") {

		if ($f_cliente[id_recibo_contrato]>0)
		{
        	$total=$f_cliente[monto];
        }
        else
        {
			while($f_gasto=asignar_a($r_gasto)){
				$total= ($total + $f_gasto[monto_aceptado]);
			}

        }
				?>
		<tr>
			<td width="70%" align="left" style="font-size: 07pt"><?php echo $f_factura[concepto];  ?></td>
			<td width="10%" align="left"></td>

			<td width="20%" align="right" style="font-size: 07pt"><?php echo  montos_print($total)?></td>
		</tr>
			<?php

	}
	else
	{
		while($f_gasto=asignar_a($r_gasto)){
			$total= ($total + $f_gasto[monto_aceptado]);
			?>
			<tr>
				<td width="50%" align="left" style="font-size: 07pt">
					<?php
						if ($f_gasto[id_tipo_servicio] == 28 || $f_gasto[id_tipo_servicio] == 27) {
							echo $f_gasto["nombre"];
						} else {
							echo "$f_gasto[nombre] ($f_gasto[descripcion] )";
						}
					
					?>
				</td>
				<td width="5%" align="left"></td>
				<td width="10%" align="right" style="font-size: 07pt">
					<?php echo montos_print($f_gasto[monto_aceptado])?>
				</td>
			</tr>
			<?php
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
	if ($cantidad[1]<=9) {
		$cero=0;
	}
	else
	{
		$cero="";
	}

	$cantidad[1]=substr($cantidad[1],0,2);
	$cantida[1]=substr($cantidad[1],0,1);

	if ($cantida[1]==0){
		$cero="";
    }
	?>

	<tr>
		<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL EXENTO O EXONERADO Bs. </td>

		<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total); echo  $cero;?></td>
	</tr>

	<tr>
		<td  valign="top" align="right" style="font-size: 07pt">Base imponible segun alicuota_% Bs.</td>
		<td  valign="top" align="right" style="font-size: 07pt"></td>
	</tr>

	<tr>
		<td  valign="top" align="right" style="font-size: 07pt">Monto Total del impuesto segun alicuota_% Bs.</td>
		<td  valign="top" align="right"> </td>
	</tr>

	<?php
	if ($descuento > 0) {
		?>

		<tr>
			<td  valign="top" align="right" style="font-size: 07pt">DESCUENTO</td>
			<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo $descuento?>%</td>
		</tr>
		
		<tr>
			<td  valign="top" align="right" style="font-size: 07pt">MONTO CON DESCUENTO</td>
			<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($montoConDescuento) . $cero?></td>
		</tr>

		<?php
	}
	
	?>


	<?php
	$q_deducible=("select
		sum(tbl_procesos_claves.fac_deducible)
	from
		tbl_procesos_claves
	where
		tbl_procesos_claves.id_factura='$f_factura[id_factura]' ");
	
	$r_deducible=ejecutar($q_deducible);
	$f_deducible=asignar_a($r_deducible);



    if (($f_deducible[sum]>0) || ($elmontonoaprobado>=1))
	{
        ?>
		<tr>
			<td  valign="top" align="right" style="font-size: 07pt">MONTO DEDUCIBLE Bs. </td>
			<td width="130" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print(abs($f_deducible[sum]));  ?> </td>
		</tr>

		<tr>
			<td  valign="top" align="right" style="font-size: 07pt">MONTO NO APROBADO Bs.</td>
			<td width="130" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print(abs($elmontonoaprobado));  ?> </td>
		</tr>

		<?php

      	//calculo de IGTF
		if($IGTFactivo==1){
			?>
			<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA</td>

				<td width="130" valign="top" align="right" style="font-size: 07pt">  <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado));  ?> </td>
			</tr>

			<tr>
				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido</td>

				<td width="130" valign="top" align="right" style="font-size: 07pt"><?php echo montos_print(abs($montoigtf));  ?> </td>
			</tr>
			<?php

		}	//fin del calculo IGTF
		?>

		<tr>
			<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR Bs </td>

			<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php  echo montos_print(abs($total - $f_deducible[sum] - $elmontonoaprobado + $montoigtf));  ?></td>
		</tr>

</table>
        <?php
    }
    else
	{
        ?>
		<?php


		//calculo de IGTF
		if($IGTFactivo==1){
			?>
			<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL DE LA VENTA</td>

				<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print($total); echo  $cero;?></td>
			</tr>

			<tr>
				<td  valign="top" align="right" style= "font-size: 07pt">Monto impuesto <?php echo $porcientoIGTF; ?> IGTF percibido </td>

				<td width="130" valign="top" align="right"  style="font-size: 07pt"><?php echo  montos_print(abs($montoigtf))  ?>  </td>
			</tr>
			<?php

 			//fin del calculo IGTF
		}
		?>

		</tr>
		<?php
		if ($descuento > 0) {
			?>
		
			<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR</td>

				<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($montoConDescuento + $montoigtf));  echo  $cero;?></td>
			</tr>
		
			<?php
		} else {
			?>
			<tr>
				<td  valign="top" align="right" style="font-size: 07pt">MONTO TOTAL A PAGAR</td>

				<td width="130" valign="top" align="right" style="font-size: 07pt"> <?php echo montos_print(abs($total + $montoigtf));  echo  $cero;?></td>
			</tr>
			<?php
		}
			?>

</table>
        <?php
    }

        /* **** Se registra lo que hizo el usuario**** */
        ?>
	<?php
}
?>


<?php
$log="Imprimio la Factura numero $no_factura - fecha Emision $fechaEmision";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
?>



</div>
</body>
</html>
