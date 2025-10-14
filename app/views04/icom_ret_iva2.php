<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();

$numcheque=$_REQUEST[numcheque];
$banco=$_REQUEST[banco];
$codigo=$_REQUEST[codigo];
$cedula=$_REQUEST[cedula];
$nombreprov=$_REQUEST[nombreprov];
$prov=$_REQUEST[prov];
$ente=$_REQUEST[ente];
$fecha_emision=$_REQUEST[fecha_emision];
$direccionpro=$_REQUEST[direccionpro];
$personaprov=$_REQUEST['personaprov'];
$id_proveedor=$_REQUEST[id_proveedor];

$compro_retiva_seniat=$_REQUEST[compro_retiva_seniat];
$periodo=split("-",$compro_retiva_seniat);

$id_admin= $_SESSION['id_usuario_'.empresa];
/* busco el admin*/
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* **** Se registra lo que hizo el usuario**** */

$log="Imprimo o vio el cheque o recibo   con codigo numero $codigo";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
/* **** compraro si busco proveedor persona o proveedor clinica **** */
if ($prov==6){
    $q_proveedor=("select   
                                        comisionados.nombres,
                                        comisionados.apellidos,
                                        comisionados.cedula,
                                        actividades_pro.codigo,
                                        actividades_pro.porcentaje,
                                        actividades_pro.actividad,
                                        actividades_pro.sustraendo
                            from
                                        comisionados,actividades_pro,facturas_procesos 
                            where
                                        comisionados.id_comisionado='$id_proveedor' and 
                                        comisionados.id_comisionado=facturas_procesos.id_proveedor and 
                                        facturas_procesos.id_act_pro=actividades_pro.id_act_pro
");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nombres] $f_proveedor[apellidos] ";
$rifpro=$f_proveedor[cedula];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[celular_pro];
$objetoretencion=$f_proveedor[actividad];
$porcentaje=100 * $f_proveedor[porcentaje];
$sustraendo=$f_proveedor[sustraendo];
    
    }
    else
    {
if ($prov==1){
/* **** BUSCO EL PROVEEDOR  persona**** */

$q_proveedor=("select   personas_proveedores.*,actividades_pro.codigo,actividades_pro.actividad,actividades_pro.porcentaje,actividades_pro.sustraendo
		                from personas_proveedores,actividades_pro where personas_proveedores.id_persona_proveedor='$id_proveedor' and personas_proveedores.id_act_pro=actividades_pro.id_act_pro");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nombres_prov] $f_proveedor[apellidos_prov]";
$rifpro=$f_proveedor[cedula_prov];
$direccionpro=$f_proveedor[direccion_prov];
$telefonospro=$f_proveedor[celular_pro];
$actividad=$f_proveedor[actividad];
$codigoact=$f_proveedor[codigo];
$porcentaje=$f_proveedor[porcentaje];
}
else
{

/* **** BUSCO EL PROVEEDOR  clinica**** */

$q_proveedor="select clinicas_proveedores.*,actividades_pro.codigo,actividades_pro.actividad,actividades_pro.porcentaje,actividades_pro.sustraendo from
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
}
/* **** busco las facturas**** */
	$q_numfacturas=("select facturas_procesos.factura,facturas_procesos.no_control_fact,count(facturas_procesos.factura) 
from facturas_procesos where facturas_procesos.codigo='$codigo' group by facturas_procesos.factura,facturas_procesos.no_control_fact
");
$r_numfacturas=ejecutar($q_numfacturas);
$num_filas=num_filas($r_numfacturas);
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
	<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
			<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
</td>
</tr>
			
			<tr>		
<td colspan=9 class="titulo3">Comprobante de Retenci&oacute;n IVA</td>	
</tr>
<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
	</tr>
	<tr>
		<td colspan=1 class="factura">Fecha Emisi&oacute;n</td>
		<td colspan=1 class="datos_cliente"><?php echo $fecha_emision?></td>
		<td colspan=1 class="datos_cliente"></td>
		<td colspan=1 class="factura">Nro. de Comprobante</td>
		<td colspan=2 class="datos_cliente"><?php echo "$periodo[0]$periodo[1]$periodo[2]"?></td>
		<td colspan=1 class="factura">Periodo Fiscal</td>
		<td colspan=2 class="datos_cliente"><?php echo "A&ntilde;o: $periodo[0] / Mes $periodo[1]"?></td>
	</tr>
	<tr>		
<td colspan=9 class="titulo3"><hr></hr></td>	
</tr>
	<tr>		
<td colspan=9 class="titulo3">Datos del Agente de Retenci&oacute;n</td>	
</tr>
	<tr>
		<td colspan=3 class="factura">Nombre o Raz&oacute;n Social </td>
		<td colspan=2 class="tdcampos">CLINISALUD MEDICINA PREPAGADA S.A.</td>
		<td colspan=2 class="factura">Rif </td>
		<td colspan=1 class="tdcampos">J-31180863-9</td>
		<td colspan=1 class="tdtitulos"></td>
	</tr>
	<tr>
		<td colspan=3 class="factura">Domicilio Fiscal </td>
		<td colspan=6 class="tdcampos">AV ANDRES BELLO EDIF LAS TAPIAS PISO 3 LOCAL 44 URB LAS TAPIAS MERIDA MÉRIDA ZONA POSTAL 5101</td>
		
	</tr>
	<tr>		
<td colspan=9 class="titulo3"><hr></hr></td>	
</tr>
	<tr>		
<td colspan=9 class="titulo3">Datos Del Proveedor</td>	
</tr>
	<tr>
		<td colspan=3 class="factura">Nombre o Raz&oacute;n Social </td>
		<td colspan=4 class="tdcampos"><?php echo  $nombrepro?></td>
		<td colspan=1 class="factura">Rif </td>
		<td colspan=1 class="tdcampos"><?php echo $rifpro?></td>
		
	</tr>
	<tr>
		<td colspan=3 class="factura">Domicilio Fiscal  </td>
		<td colspan=4 class="tdcampos"><?php echo $direccionpro?></td>
		<td colspan=1 class="factura">Telefono  </td>
		<td colspan=1 class="tdcampos"><?php echo  $telefonospro?></td>
		
	</tr>
	
	
	<tr>
		<td colspan=9 class="tdtitulos"><hr></hr></td>
		
	</tr>
	<tr>		
<td colspan=9 class="titulo3">Datos De las Facturas</td>	
</tr>
		<tr>
		<td colspan=1 class="factura">Num Control </td>
		<td colspan=1 class="factura">Num Factura</td>
        <td colspan=1 class="factura">Fecha Emision</td>
        
		<td colspan=1 class="factura">Monto Total </td>
		<td colspan=1 class="factura">Monto Exento</td>
		<td colspan=1 class="factura">Base Imponible</td>
		<td colspan=1 class="factura">Segun Alícuota</td>
		<td colspan=1 class="factura">I.V.A. Facturado</td>
		<td colspan=1 class="factura">I.V.A. Retenido</td>
		<td colspan=1 class="factura">Total Neto A Pagar</td>
	</tr>

<?php 
while($f_numfacturas=asignar_a($r_numfacturas,NULL,PGSQL_ASSOC)){
$i++;
$q_facturas=("select * from facturas_procesos where facturas_procesos.codigo='$codigo' and 
facturas_procesos.factura='$f_numfacturas[factura]';
");
$r_facturas=ejecutar($q_facturas);
	$totalgc=0;
	$totalhm=0;
	$iva=0;
	$retiva=0;
	$montoexento=0;
    $montofactura_afectada=0;
while($f_facturas=asignar_a($r_facturas,NULL,PGSQL_ASSOC)){
    
if (($prov==4 || $prov==2) and $f_facturas[iva]==0){
$montoexento= $montoexento +$f_facturas[monto_sin_retencion];
$montoexentot= $montoexentot +$f_facturas[monto_sin_retencion];

}
	$totalgc=$totalgc +$f_facturas[monto_sin_retencion];
	$totalhm=$totalhm + $f_facturas[monto_con_retencion];
	$iva= $iva + $f_facturas[iva];
	$totaliva= $totaliva + $f_facturas[iva];
	$retiva= $retiva + $f_facturas[iva_retenido];

	$total_iva_ret= 	$total_iva_ret + $f_facturas[iva_retenido];
	$total_neto=$total_neto +  (($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva]) - $f_facturas[iva_retenido]);
$fecha_emisio="$f_facturas[fecha_emision_fact]";
/* verificar el tipo de documento*/
if ($f_facturas[tipo_documento]==0){
                                                                    $tipo_documento= "F";
                                                                    $factura_afectada="";
                                                                    }
                                                                    else
                                                                    {
                                                                        $tipo_documento= "NC";
                                                                        $montofactura_afectada=$montofactura_afectada+$f_facturas[monto_sin_retencion];
                                                                        $montofactura_afectadat=$montofactura_afectadat+$f_facturas[monto_sin_retencion];
                                                                        $factura_afectada=$f_facturas[factura_afectada];
                                                                        }
/* fin verificar el tipo de documento*/
}
$mobjret= ($totalgc + $totalhm) - $iva;
$totalgchm=$totalgchm + ($totalgc + $totalhm);
$totalmobjret=$totalmobjret + $mobjret;
	?>
	
	<?php /*
	$buscarseparador=strpos($f_numfacturas[no_control_fact],'-');
if($buscarseparador==false) {	//no hay resultados
$NumeroControl='00-'.$f_numfacturas[no_control_fact];
}else {
}*/
$NumeroControl=$f_numfacturas[no_control_fact];
	
	?>
	<tr>
		<td colspan=1 class="tdcampos"><?php echo "$NumeroControl"?></td>
		<td colspan=1 class="tdcampos"><?php echo $f_numfacturas[factura]?></td>
      	<td colspan=1 class="tdcampos"><?php echo $fecha_emisio?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($totalgc + $totalhm)?></td>
		<td colspan=1 class="tdcampos"><?php 
						if (($prov==4 || $prov==2)and $f_facturas[iva]==0){
						echo montos_print($montoexento);
						}
						else
						{
						echo montos_print($f_facturas[monto_sin_retencion]);
						}
						$baseimponible=$mobjret - $montoexento;
						$baseimponiblet=$totalmobjret - $montoexentot;
						$alicuotare=$iva*100/$baseimponible;
						$alicuota=round($alicuotare, 0, PHP_ROUND_HALF_DOWN);
						$Totalalicuotat=$totaliva*100/$baseimponiblet;
						$Totalalicuotat=round($Totalalicuotat, 0, PHP_ROUND_HALF_DOWN);
						
						?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($mobjret - $montoexento)?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($alicuota)?>%</td><!-- ALICUTA -->
		<td colspan=1 class="tdcampos"><?php echo montos_print($iva)?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($retiva)?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print((($totalgc + $totalhm)-$retiva)) ?></td>
	</tr>
	
	<?php
	}
?>
</tr>
		<tr>
        
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="factura">Total</td>
		<td colspan=1 class="factura"><?php echo montos_print($totalgchm)?></td>
		<td colspan=1 class="factura"><?php echo montos_print($montoexentot)?></td>
		<td colspan=1 class="factura"><?php echo montos_print($totalmobjret - $montoexentot)?></td>
		<td colspan=1 class="factura"><?php echo montos_print($Totalalicuotat)?>%</td><!-- ALICUTA -->
		<td colspan=1 class="factura"><?php echo montos_print($totaliva)?></td>
		<td colspan=1 class="factura"><?php echo montos_print($total_iva_ret)?></td>
		<td colspan=1 class="factura"><?php echo montos_print($totalgchm - $total_iva_ret)?></td>
	</tr>
	<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>

	<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
<tr>
<td colspan=9 class="tdtitulos">Artículo 11. - Serán responsables del pago del impuesto en calidad de agente de retención, los compradores o adquirentes de determinados bienes muebles y los receptores de ciertos servicios, a quienes la Administración Tributaria designe como tales, de acuerdo con lo previsto en el Código Orgánico Tributario.
</td>
</tr>

<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>
<tr>
<td colspan=9 class="tdtitulos"><br></br></td>
			
</tr>
<tr>
<td colspan=4 class="titulo3">_______________________</td>
<td colspan=3 class="titulo3">_______________________</td>
<td colspan=4 class="titulo3">_______________________</td>
		
</tr>
<tr>
<td colspan=4 class="titulo3">Firma del Agente Retencion</td>
<td colspan=3 class="titulo3">Fecha Recibido</td>
<td colspan=4 class="titulo3">Firma del Sujeto Retenido</td>
		
</tr>

<tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</tr>
	<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>

<tr>
<td colspan=9 class="tdtitulos">Este comprobante se emite según lo establecido en la Providencia Administrativa SNAT/2025/0054. Publicada en Gaceta Oficial Número 43.171  de fecha 01 de Agosto de 2025</td>
</tr>
<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>

</table>
<?php
}
?>


