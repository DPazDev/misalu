<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

?>
<?php
include ("../../lib/jfunciones.php");
sesion();
$banco=$_REQUEST['banco'];
$honorarios1=$_REQUEST['honorarios1'];
$conexa=$_REQUEST['conexa'];
$numcheque=$_REQUEST['numcheque'];
$rif=$_REQUEST['rif'];
$nombreprov=$_REQUEST['nombreprov'];
$direccionprov=$_REQUEST['direccionprov'];

$factura1=$_REQUEST['factura1'];
$iva_fact1=$_REQUEST['iva_fact1'];
$montoexento1=$_REQUEST['montoexento1'];
$baseimponible1=$_REQUEST['baseimponible1'];
$confactura1=$_REQUEST['confactura1'];
$fecha_emision1=$_REQUEST['fecha_emision1'];
$idproveedor=$_REQUEST['idproveedor'];
$iva_ret1=$_REQUEST['iva_ret1'];
$factura2=explode("@",$factura1);
$honorarios2=explode("@",$honorarios1);
$idordcom1=$_REQUEST['idordcom1'];
$idordcom2=explode("@",$idordcom1);
$iva_fact2=explode("@",$iva_fact1);
$montoexento2=explode("@",$montoexento1);
$baseimponible2=explode("@",$baseimponible1);
$iva_ret2=explode("@",$iva_ret1);
$confactura2=explode("@",$confactura1);
$fecha_emision2=explode("@",$fecha_emision1);
$idsproveedores=explode("@",$idproveedor);
$anombrede=$_REQUEST['anombrede'];
$cedularif=$_REQUEST['cedularif'];
$motivo=$_REQUEST['motivo'];
$tipocuenta=$_REQUEST['tipocuenta'];
$iva_rettt=$_REQUEST['iva_rettt'];
$codigomas=$_REQUEST['codigomas'];

if ($banco==8)
{
  $numcheque=0;
}
$fecha=date("Y");
$anio=date("Y");
$mes=date("m");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
// **** busco el usuario admin **** //
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
// **** fin de buscar el usuario admin **** //
// **** buscar act proveedor **** //
$CantDeFacturas=count($idsproveedores);
$procesot='';
for($i=0;$i<$CantDeFacturas;$i++){
			$proveedor=$idsproveedores[$i];
      $honorarios=$honorarios2[$i];
			$idordcom=$idordcom2[$i];
			$factura=$factura2[$i];
			$montoexento=$montoexento2[$i];
			$baseimponible=$baseimponible2[$i];
			$iva_fact=$iva_fact2[$i];
			$iva_ret=$iva_ret2[$i];
			$iva_rettt=$iva_ret;
			$fecha_emision=$fecha_emision2[$i];
			$confactura=$confactura2[$i];

			$q_act="select clinicas_proveedores.id_act_pro,clinicas_proveedores.rif from clinicas_proveedores,proveedores
			where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
			proveedores.id_proveedor=$proveedor ";
			$r_act=ejecutar($q_act);
			$f_act=asignar_a($r_act);
      $rif=$f_act['rif'];
		// **** buscar act proveedor **** //
			$codigot=time();
			$codigo=$admin . $codigot;
		// **** busco el ultimo comprobante **** //
			$q_facturap="select * from facturas_procesos order by facturas_procesos.id_factura_proceso desc limit 1;";
			$r_facturap=ejecutar($q_facturap);
    	if(num_filas($r_facturap)==0){
				$no_comprobante="1";
			}
			else
			{
				$f_facturap=asignar_a($r_facturap);
				$no_comprobante=$f_facturap['comprobante'];
				$no_comprobante++;
			}
			// **** fin buscar el ultimo comprobante **** //

			// **** busco el ultimo comprobante retencion iva*** //
			$q_comretiva="select * from facturas_procesos where facturas_procesos.corre_retiva_seniat>0 and facturas_procesos.id_banco<>9 order by facturas_procesos.corre_retiva_seniat desc limit 1;";
			$r_comretiva=ejecutar($q_comretiva);
	  if ($iva_rettt>0){
				if(num_filas($r_comretiva)==0){
					$no_comp_ret_iva="1";
					$ceros="00000000";
					$length = strlen($no_comp_ret_iva);
					$ceros= substr($ceros,0, - $length);
					$no_comp_ret_iva1= $anio . "-" . $mes . "-" . $ceros . $no_comp_ret_iva;
					}
					else
					{
						$f_comretiva=asignar_a($r_comretiva);
          	$no_comp_ret_iva=$f_comretiva['corre_retiva_seniat'];
						$no_comp_ret_iva++;
						$ceros="00000000";
						$length = strlen($no_comp_ret_iva);
						$ceros= substr($ceros,0, - $length);
						$no_comp_ret_iva1= $anio . "-" . $mes . "-" . $ceros . $no_comp_ret_iva;
					}
			}
			else
			{
				$no_comp_ret_iva1=0;
				$no_comp_ret_iva=0;
			}

			// **** fin buscar el ultimo comprobante **** //

			// **** comparo si voy agregar mas gastos a un pago**** //

		    echo$q_codigomas="select * from facturas_procesos where facturas_procesos.codigo='$codigomas';";
			$r_codigomas=ejecutar($q_codigomas);
			if(num_filas($r_codigomas)>0){
			$f_codigomas=asignar_a($r_codigomas);

			$no_comp_ret_iva1=$f_codigomas[compro_retiva_seniat];
			$no_comp_ret_iva=$f_codigomas[corre_retiva_seniat];
			$no_comp_ret_islr1=$f_codigomas[corre_compr_islr];
			$no_comp_ret_islr=$f_codigomas[corre_ret_islr];
			$no_comprobante=$f_codigomas[comprobante];
			$codigo=$f_codigomas[codigo];
			}

			// **** fin de comparar si voy agregar mas gastos a un pago**** //

			// **** busco el ultimo recibo segun la sucursal que pertenezca el admin **** //
			if ($banco==8)
			{
			$q_facturap1="select * from facturas_procesos where facturas_procesos.id_admin=admin.id_admin and admin.id_sucursal=$f_admin[id_sucursal] and facturas_procesos.id_banco=8 order by num_recibo desc limit 1;";
			$r_facturap1=ejecutar($q_facturap1);
			if(num_filas($r_facturap1)==0){
				$no_factura1="1";
				$descripcion="Recibo numero $no_factura1";
			}
			else
			{
				$f_facturap1=asignar_a($r_facturap1);
				$no_factura1=$f_facturap1[num_recibo];
				$no_factura1++;
				$descripcion="Recibo numero $no_factura1";
			}
			}
			else
			{
				$descripcion="Cheque numero $numcheque";
				$no_factura1=0;
				}
			// **** fin de buscar el ultimo recibo segun la sucursal que pertenezca el admin **** //

			$q="
			begin work;
			";


			$moontosin=0;
			if(!empty($factura) && $factura<>''){
					$procesot .=$idordcom .",";
					$montosin=$baseimponible + $iva_fact;

				if ($banco==8)
				{
					$q.="
					insert into facturas_procesos (id_proceso,fecha_creado,hora_creado,id_admin,id_servicio,codigo,id_proveedor,numero_cheque,comprobante,monto_con_retencion,retencion,descuento,iva,monto_sin_retencion,id_banco,cedula,tipo_proveedor,factura,num_recibo,id_orden_compra,compro_retiva_seniat,corre_retiva_seniat,iva_retenido,no_control_fact,fecha_emision_fact,id_act_pro)
					values ('0','$fechacreado','$hora','$admin','0','$codigo','$proveedor','$numcheque','$no_comprobante','0','0','0','0','$montosin','$banco','$rif','0','$factura','$no_factura1','$idordcom','0','0','0','$confactura','$fecha_emision','$f_act[id_act_pro]');
					";
				}
				else
				{$q.="
				insert into facturas_procesos (id_proceso,fecha_creado,hora_creado,id_admin,id_servicio,codigo,id_proveedor,numero_cheque,comprobante,monto_con_retencion,retencion,descuento,iva,monto_sin_retencion,id_banco,cedula,tipo_proveedor,factura,num_recibo,id_orden_compra,compro_retiva_seniat,corre_retiva_seniat,iva_retenido,no_control_fact,fecha_emision_fact,id_act_pro)
				values ('0','$fechacreado','$hora','$admin','0','$codigo','$proveedor','$numcheque','$no_comprobante','$baseimponible','0','0','$iva_fact','$montoexento','$banco','$rif','3','$factura','$no_factura1','$idordcom','$no_comp_ret_iva1','$no_comp_ret_iva','$iva_ret','$confactura','$fecha_emision','$f_act[id_act_pro]');
				";
				}


			}
			$q.="
			commit work;
			";
			$r=ejecutar($q);
}
?>
<TABLE ID='NOENCONTRADOS' class="tabla_citas">
  <Tr>
      <Th class="titulo_seccion">RELACION DE FACTURAS COMPRAS SIN RETENCION</Th>
  </Tr>
  <Tr>
      <Td class="titulo_seccion">RESGISTRO EXITOSO!!</Td>
  </Tr>
  <Tr>
      <Td class="tdcampos"><a href="#" title="Regresar al inicio" onclick="trasladar_facturasCompras();" class="boton">volver a buscar</a>
   </Td>
  </Tr>
</TABLE>
<?php
/*
// * **** Se registra lo que hizo el usuario**** * /


$log="Genero el $descripcion  para los id_orden_compra  ($procesot) y codigo numero $codigo";
logs($log,$ip,$admin);

/// * **** Fin de lo que hizo el usuario **** * /
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">Cheque Generado Con Exito</td>	</tr>
<tr>
		<td class="tdtitulos"><?php

if ($f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11) {
			$url="'views04/icheque_prov.php?codigo=$codigo&fechaemision=$fechacreado&numcheque=$numcheque&motivo=$motivo&tipocuenta=$tipocuenta&banco=$banco&nombreprov=$nombreprov&cedula=$rif&prov=$prov&mod=1&id_proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Cheque o Recibo"> Imprimir Cheque</a>
			<?php
	}
			$url="'views04/icom_ret_iva.php?codigo=$codigo&banco=$banco&nombreprov=$nombreprov&cedula=$rif&prov=$prov&fecha_emision=$fechacreado&compro_retiva_seniat=$no_comp_ret_iva1&direccionpro=$direccionprov&id_proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Comprobante"> Imprimir Comprobante</a>

			<a href="#" OnClick="reg_che_prov();" class="boton" title="Ir a Chueques Reembolsos">Crear Otro Cheque de Proveedor</a><a href="#" OnClick="ir_principal();" class="boton">salir</a>
			</td>
	</tr>

</table>
<?php */ ?>
