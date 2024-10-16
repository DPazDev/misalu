<?php
include ("../../lib/jfunciones.php");
sesion();

$numcheque=$_REQUEST[numcheque];
$banco=$_REQUEST[banco];
$codigo=$_REQUEST[codigo];
$cedula=$_REQUEST[cedula];
$nombreprov=$_REQUEST[nombreprov];
$id_proveedor=$_REQUEST[id_proveedor];

$r=$nombreprov;

$prov=$_REQUEST[prov];
$ente=$_REQUEST[ente];
$fecha_emision=$_REQUEST[fecha_emision];
$direccionpro=$_REQUEST[direccionpro];
$compro_retiva_seniat=$_REQUEST[compro_retiva_seniat];
$periodo=split("-",$compro_retiva_seniat);
$id_admin= $_SESSION['id_usuario_'.empresa];
/* busco el admin*/
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* **** Se registra lo que hizo el usuario**** */

$log="Imprimio o vio el cheque o recibo   con codigo numero '$codigo'";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
/* **** compraro si busco proveedor persona o proveedor clinica **** */
if ($prov==1){
/* **** BUSCO EL PROVEEDOR  persona**** */

$q_proveedor=("select   personas_proveedores.*,actividades_pro.codigo,actividades_pro.porcentaje,actividades_pro.sustraendo
		                from personas_proveedores,actividades_pro where personas_proveedores.id_persona_proveedor='$id_proveedor' and personas_proveedores.id_act_pro=actividades_pro.id_act_pro");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nombres_prov] $f_proveedor[apellidos_prov]";
$rifpro=$f_proveedor[cedula_prov];
$direccionpro=$f_proveedor[direccion_prov];
$telefonospro=$f_proveedor[celular_pro];

}
else
{

/* **** BUSCO EL PROVEEDOR  clinica**** */

$q_proveedor="select clinicas_proveedores.*,actividades_pro.codigo,actividades_pro.porcentaje,actividades_pro.sustraendo from
 clinicas_proveedores,proveedores,actividades_pro where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
 proveedores.id_proveedor='$id_proveedor' and clinicas_proveedores.id_act_pro=actividades_pro.id_act_pro";
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nombre]";
$rifpro=$f_proveedor[rif];
$direccionpro=$f_proveedor[direccion];
$telefonospro=$f_proveedor[telefonos];

/* **** FIN DE BUSCAR PROVEEDOR **** */
}

	$q_facturas=("select * from facturas_procesos where facturas_procesos.codigo='$codigo'
");
$r_facturas=ejecutar($q_facturas);
$num_filas=num_filas($r_facturas);
if ($num_filas==0){
?>


<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=6 class="titulo3">No hay Facturas en esta Fecha</td>	
</tr>
</table>
<?
		}
		else
		{
	?>

			<table class="tabla_citas"  cellpadding=0 cellspacing=0>
			
			<tr>		
<td colspan=9 class="titulo_seccion">Comprobante de Retenci&oacute;n IVA</td>	
</tr>
<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">Fecha Emisi&oacute;n</td>
		<td colspan=1 class="tdcampos"><?php echo $fecha_emision?></td>
		<td colspan=1 class="datos_cliente"></td>
		<td colspan=1 class="tdtitulos">Nro. de Comprobante</td>
		<td colspan=2 class="tdcampos"><?php echo $compro_retiva_seniat?></td>
		<td colspan=1 class="tdtitulos">Periodo Fiscal</td>
		<td colspan=2 class="tdcampos"><?php echo "A&ntilde;o: $periodo[0] / Mes $periodo[1]"?></td>
	</tr>
	<tr>		
<td colspan=9 class="titulo3"><hr></hr></td>	
</tr>
	<tr>		
<td colspan=9 class="tdtitulos">Datos del Agente de Retenci&oacute;n</td>	
</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Nombre o Raz&oacute;n Social </td>
		<td colspan=2 class="tdcampos">CLINISALUD MEDICINA PREPAGADA S.A.</td>
		<td colspan=2 class="tdtitulos">Rif </td>
		<td colspan=1 class="tdcampos">J-31180863-9</td>
		<td colspan=1 class="tdtitulos"></td>
	</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Domicilio Fiscal </td>
		<td colspan=6 class="tdcampos">Av. las Americas C.C. Mayeya nivel Mezanina Locales 16,17 y 24 Sector Los Sauzales M&eacute;rida, estado M&eacute;rida</td>
		
	</tr>
	<tr>		
<td colspan=9 class="titulo3"><hr></hr></td>	
</tr>
	<tr>		
<td colspan=9 class="tdtitulos">Datos Del Proveedor</td>	
</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Nombre o Raz&oacute;n Social </td>
		<td colspan=2 class="tdcampos"><?php echo $nombrepro?></td>
		<td colspan=2 class="tdtitulos">Rif </td>
		<td colspan=1 class="tdcampos"><?php echo $rifpro?></td>
		<td colspan=1 class="tdtitulos"></td>
	</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Domicilio Fiscal  </td>
		<td colspan=6 class="tdcampos"><?php echo $direccionpro?></td>
		
	</tr>
	
	
	<tr>
		<td colspan=9 class="tdtitulos"><hr></hr></td>
		
	</tr>
	<tr>		
<td colspan=9 class="tdtitulos">Datos De las Facturas</td>	
</tr>
		<tr>
		<td colspan=1 class="tdtitulos">Num Control </td>
		<td colspan=1 class="tdtitulos">Num Factura</td>
		<td colspan=1 class="tdtitulos">Fecha Emisi&oacute;n </td>
		<td colspan=1 class="tdtitulos">Monto Total </td>
		<td colspan=1 class="tdtitulos">Monto Exento</td>
		<td colspan=1 class="tdtitulos">Base Imponible</td>
		<td colspan=1 class="tdtitulos">I.V.A. Facturado</td>
		<td colspan=1 class="tdtitulos">I.V.A. Retenido</td>
		<td colspan=1 class="tdtitulos">Total Neto A Pagar</td>
	</tr>

<?php 
while($f_facturas=asignar_a($r_facturas,NULL,PGSQL_ASSOC)){
	$total_iva_ret= 	$total_iva_ret + $f_facturas[iva_retenido];
    $total_iva_fac= 	$total_iva_fac + $f_facturas[iva];
    $monto_exento= $monto_exento + $f_facturas[monto_sin_retencion];
    $baseimponible= $baseimponible + $f_facturas[monto_con_retencion];
    $montototal= $montototal + $f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva];
	$total_neto=$total_neto +  (($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva]) - $f_facturas[iva_retenido]);
	$fecha_emision
	?>
	<?php 
	$buscarseparador=strpos($f_facturas[no_control_fact],'-');
if($buscarseparador==false) {	//no hay resultados
$NumeroControl='00-'.$f_facturas[no_control_fact];
}else {
$NumeroControl=$f_facturas[no_control_fact];
}

?>
	<tr>
		<td colspan=1 class="tdcampos"><?php echo $NumeroControl?></td>
		<td colspan=1 class="tdcampos"><?php echo $f_facturas[factura]?></td>
		<td colspan=1 class="tdcampos"><?php echo $f_facturas[fecha_emision_fact]?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva],2,',','')?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format($f_facturas[monto_sin_retencion],2,',','')?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format($f_facturas[monto_con_retencion],2,',','')?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format($f_facturas[iva],2,',','')?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format($f_facturas[iva_retenido],2,',','')?></td>
		<td colspan=1 class="tdcampos"><?php echo number_format(($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva]) - $f_facturas[iva_retenido],2,',','') ?></td>
	</tr>
	
	<?php
	}
?>
</tr>
		<tr>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($montototal,2,',','')?></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($monto_exento,2,',','')?></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($baseimponible,2,',','')?></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($total_iva_fac,2,',','')?></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($total_iva_ret,2,',','')?></td>
		<td colspan=1 class="tdtitulos"><?php echo number_format($total_neto,2,',','')?></td>
	</tr>
	<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
<tr>
<td colspan=9 class="tdtitulos">Ley IVA - Art. 11: "Seran responsables del pago del impuesto en calidad de agentes de retenci&oacute;n los compradores o adquirientes de determinados bienes inmuebles y los receptores de ciertos servicios, a quienes la Administraci&oacute;n Tributaria designe como tal"</td>
</tr>
<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
<tr>
<td colspan=9 class="tdtitulos"><br></br></td>
			
</tr>
</table>
<?php
}
?>


