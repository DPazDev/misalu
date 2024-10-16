<?php
/* Nombre del Archivo: reporte_ventas_individuales.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Ventas de Polizas Individuales
*/  
header("Content-Type: text/html;charset=utf-8");
 include ("../../lib/jfunciones.php");
 sesion();
$fecha = date("Y-m-d");

$fecre1=$_REQUEST['fecha1'];
$fecre2=$_REQUEST['fecha2'];

$condicion_fecha="and tbl_recibo_pago.fecha_creado between '$fecre1' and '$fecre2'";

   list($id_tipo_pago,$tipo_pago)=explode("@",$_REQUEST['pagos']);
	if($id_tipo_pago==0)	        $condicion_pagos="";
	else
	$condicion_pagos="and tbl_tipos_pagos.id_tipo_pago=$id_tipo_pago";
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 


	<tr>
		<td class="titulo_seccion" colspan="19">REPORTE VENTA DE POLIZAS INDIVIDUALES - POR FECHA DE CREADO DESDE EL <?php echo "$fecre1 AL $fecre2";?></td>

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>


<table class="tabla_citas"  cellpadding=0 cellspacing=0  border=3> 



	<tr> 
	
	<td class="tdcampos centrar-texto negrita">Número Contrato</td>
	<td class="tdcampos centrar-texto negrita">Num Cuadro Recibo Prima</td>
	<td class="tdcampos centrar-texto negrita">Fecha Creado</td>
	<td class="tdcampos centrar-texto negrita">Fecha Efectivo Pago </td>
	<td class="tdcampos centrar-texto negrita">Forma de Pago </td>
	<td class="tdcampos centrar-texto negrita">Serie</td>
	<td class="tdcampos centrar-texto negrita">Recibo Número</td>
	<td class="tdcampos centrar-texto negrita">Banco</td>
	<td class="tdcampos centrar-texto negrita">Monto ($)</td>
</tr>

<?php

$q_venta=("select 
			tbl_contratos_entes.numero_contrato,
			tbl_contratos_entes.cuotacon, 
			tbl_contratos_entes.id_contrato_ente,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_pago.saldo_deudor,
			tbl_recibo_pago.id_serie,
			tbl_recibo_pago.fecha_pago,
			tbl_recibo_pago.fecha_creado,
			tbl_recibo_pago.fecha_efec_pago,
			tbl_recibo_pago.id_tipo_pago,
			tbl_tipos_pagos.tipo_pago, 
			tbl_recibo_pago.id_recibo_pago,
			tbl_recibo_pago.monto,
			tbl_recibo_pago.numero_recibo,
			sum(tbl_caract_recibo_prima.monto_prima)
		from 
			tbl_contratos_entes,
			tbl_caract_recibo_prima,
			tbl_recibo_contrato,
			tbl_recibo_pago,
			tbl_tipos_pagos
		where
			tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
			tbl_recibo_contrato.id_recibo_contrato=tbl_caract_recibo_prima.id_recibo_contrato and 
			tbl_recibo_pago.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and tbl_recibo_pago.estado_recibo=1  and
			tbl_recibo_pago.id_tipo_pago=tbl_tipos_pagos.id_tipo_pago $condicion_fecha
			$condicion_pagos
		group by
			tbl_contratos_entes.numero_contrato,
			tbl_contratos_entes.cuotacon,
			tbl_contratos_entes.id_contrato_ente,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_pago.saldo_deudor,
			tbl_recibo_pago.id_serie, 
			tbl_recibo_pago.fecha_pago,
			tbl_recibo_pago.id_tipo_pago,
			tbl_tipos_pagos.tipo_pago,
			tbl_recibo_pago.fecha_creado,
			tbl_recibo_pago.fecha_efec_pago, 
			tbl_recibo_pago.id_recibo_pago,
			tbl_recibo_pago.monto,
			tbl_recibo_pago.numero_recibo 
			order by 
			tbl_recibo_pago.numero_recibo,
			tbl_contratos_entes.id_contrato_ente; ");
$r_venta=ejecutar($q_venta);

$cont=0;

	while($f_venta=asignar_a($r_venta)){
$cont++;

		$obtenerFecha = explode(" ", $f_venta[fecha_pago])[0];

$q_banco=("select tbl_oper_multi.id_banco, tbl_oper_multi.id_nom_tarjeta,tbl_oper_multi.numero_cheque,tbl_oper_multi.id_recibo_pago,tbl_tipos_pagos.tipo_pago,tbl_oper_multi.monto from tbl_oper_multi,tbl_tipos_pagos where tbl_oper_multi.id_recibo_pago=$f_venta[id_recibo_pago] and tbl_oper_multi.condicion_pago=tbl_tipos_pagos.id_tipo_pago");
$r_banco=ejecutar($q_banco);

$q_serie=("select tbl_series.nomenclatura from tbl_series where tbl_series.id_serie=$f_venta[id_serie]");
$r_serie=ejecutar($q_serie);
$f_serie=asignar_a($r_serie);


echo  " 
		<tr>
			<td class=\"tdtitulos izquierda-texto\">$f_venta[numero_contrato] </td>
			<td class=\"tdtitulos izquierda-texto\">$f_venta[num_recibo_prima] </td>
			<td class=\"tdcamposc izquierda-texto\">$obtenerFecha </td>
			<td class=\"tdcamposc izquierda-texto\">$f_venta[fecha_efec_pago] </td>
			<td class=\"tdcamposr izquierda-texto\">$f_venta[tipo_pago] </td>
			<td class=\"tdcamposc izquierda-texto\">$f_serie[nomenclatura] </td>
			<td class=\"tdcamposc izquierda-texto\">$f_venta[numero_recibo] </td>";
$banco2="";
$cheque2="";
while($f_banco=asignar_a($r_banco)){
	$q_cuenta=("select tbl_bancos.nombanco from tbl_bancos where tbl_bancos.id_ban=$f_banco[id_banco]");
	$r_cuenta=ejecutar($q_cuenta);
	$f_cuenta=asignar_a($r_cuenta);

	if($f_banco[id_banco]=='0'){
		$banco3="EFECTIVO";}
	else{
		$banco3="$f_cuenta[nombanco]";
	}

	$banco2 .="$banco3".",";

}

echo "<td class=\"tdcamposc izquierda-texto\">$banco2 </td>";

echo "<td class=\"tdcamposc izquierda-texto\">$f_venta[monto]</td>"; 
?>	              
	        </tr>
<?php  
}
?>

</table>

<table > 
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
	        <td colspan=4 class="tdcamposs" title="Imprimir reporte">
			  <?php
			 $url="'views06/excel_pago_ventas_ind.php?fecha1=$fecre1&fecha2=$fecre2&pagos=$id_tipo_pago@$tipo_pago&tipfecha=$tipfecha'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>




		</td>




		</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
</table>

