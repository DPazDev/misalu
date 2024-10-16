<?php
include ("../../lib/jfunciones.php");
sesion();
$dateField1=$_REQUEST[dateField1];
$dateField2=$_REQUEST[dateField2];
$procesado=$_REQUEST[procesado];
$q_var = "select * from variables_globales where id_variable_global=33";
$r_var = ejecutar($q_var);
$f_var = asignar_a($r_var);
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
if ($procesado==1)
{
	$procesado="procesos.id_estado_proceso=1 and";
		$procesado1="tbl_ordenes_donativos.estatus=1 and";
		$motivo="Pendiente";
	}
	else
	{
		$procesado="procesos.id_estado_proceso<>1 and procesos.id_estado_proceso<>14 and";
			$procesado1="tbl_ordenes_donativos.estatus=2 and";
		$motivo="Procesado";
		}
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
 
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
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
<td colspan=9 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>
<tr>
<td colspan=9>
<br>
</br>
</td>
</tr>
  <tr>
    <td colspan="9" class="titulo3"> Relacion de Gastos de Donativos <?php echo $motivo?></td>
  </tr>
  <tr>
	<td class="titulo3">Num</td>  
    <td class="titulo3">Titular</td>
    <td class="titulo3">Cedula</td>	
    <td class="titulo3">Beneficiario</td>
    <td class="titulo3">Cedula</td>
	<td class="titulo3">Ente</td>
	<td class="titulo3">Fecha Recibido</td>
	<td class="titulo3">Descripcion</td>
	<td class="titulo3">Monto</td>
  </tr>
<?php
	$q_proceso = "select clientes.nombres,clientes.apellidos,clientes.cedula,procesos.id_proceso,procesos.id_estado_proceso,procesos.fecha_recibido,procesos.id_titular,procesos.id_beneficiario,entes.nombre ,count(gastos_t_b.id_proceso) from procesos,clientes,gastos_t_b,entes,titulares where $procesado procesos.donativo=1 and procesos.fecha_recibido>='$dateField1' and procesos.fecha_recibido<='$dateField2' and procesos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_proceso=gastos_t_b.id_proceso group by clientes.nombres,clientes.apellidos,clientes.cedula,procesos.id_proceso,procesos.id_estado_proceso,procesos.fecha_recibido,procesos.id_titular,procesos.id_beneficiario,entes.nombre";
$r_proceso = ejecutar($q_proceso);
while($f_proceso = asignar_a($r_proceso)){
	if ($f_proceso[id_beneficiario]>0){
		$q_bene = "select clientes.nombres,clientes.apellidos,clientes.cedula from clientes where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_proceso[id_beneficiario]";
$r_bene = ejecutar($q_bene);
$f_bene = asignar_a($r_bene);
		$nombre="$f_bene[nombres] $f_bene[apellidos]";
		$cedula=$f_bene[cedula];
		}
		else
		{
			$nombre="";
		$cedula="";
			}
			
			$q_gasto = "select procesos.id_proceso,procesos.id_estado_proceso,procesos.fecha_recibido,procesos.id_titular,procesos.id_beneficiario ,gastos_t_b.nombre,gastos_t_b.descripcion,gastos_t_b.enfermedad ,gastos_t_b.monto_aceptado from procesos,gastos_t_b where  procesos.id_proceso=$f_proceso[id_proceso] and procesos.donativo=1  and procesos.id_proceso=gastos_t_b.id_proceso ";
$r_gasto = ejecutar($q_gasto);
$monto=0;
while($f_gasto = asignar_a($r_gasto)){
	$descripcion=$f_gasto[nombre];
	$monto= $monto + $f_gasto[monto_aceptado];
	$montot=$montot + $f_gasto[monto_aceptado];
	}
		
?>

  <tr>
	<td class="datos_cliente"><?php echo $f_proceso[id_proceso]?></td>  
    <td class="datos_cliente"><?php echo "$f_proceso[nombres] $f_proceso[apellidos]"?></td>
    <td class="datos_cliente"><?php echo $f_proceso[cedula]?></td>	
    <td class="datos_cliente"><?php echo $nombre?></td>
    <td class="datos_cliente"><?php echo $cedula?></td></td>
	 <td class="datos_cliente"><?php echo $f_proceso[nombre]?></td>
    <td class="datos_cliente"><?php echo $f_proceso[fecha_recibido]?></td>
	<td class="datos_cliente"><?php echo $descripcion?></td>
	<td class="datos_cliente"><?php echo formato_montos($monto)?></td>
  </tr>
<?php
}
?>


<?php
	$q_donativo = "select entes.nombre,clientes.nombres,clientes.apellidos,clientes.cedula,tbl_ordenes_donativos.comentarios,tbl_ordenes_donativos.no_orden_donativo,tbl_ordenes_donativos.id_orden_donativo,count(tbl_insumos_ordenes_donativos.id_orden_donativo) from clientes,tbl_ordenes_donativos,tbl_insumos_ordenes_donativos where $procesado1 tbl_ordenes_donativos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and tbl_ordenes_donativos.id_orden_donativo=tbl_insumos_ordenes_donativos.id_orden_donativo and tbl_ordenes_donativos.fecha_donativo>='$dateField1' and  tbl_ordenes_donativos.fecha_donativo<='$dateField2' group by entes.nombre,clientes.nombres,clientes.apellidos,clientes.cedula,tbl_ordenes_donativos.comentarios,tbl_ordenes_donativos.no_orden_donativo,tbl_ordenes_donativos.id_orden_donativo order by tbl_ordenes_donativos.no_orden_donativo ";
$r_donativo = ejecutar($q_donativo);
while($f_donativo = asignar_a($r_donativo)){
	if ($f_donativo[id_beneficiario]>0){
		$q_bene = "select clientes.nombres,clientes.apellidos,clientes.cedula from clientes where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_donativo[id_beneficiario]";
$r_bene = ejecutar($q_bene);
$f_bene = asignar_a($r_bene);
		$nombre="$f_bene[nombres] $f_bene[apellidos]";
		$cedula=$f_bene[cedula];
		}
		else
		{
			$nombre="";
		$cedula="";
			}
			
			$q_gastod = "select tbl_ordenes_donativos.no_orden_donativo,tbl_ordenes_donativos.id_orden_donativo,tbl_insumos_ordenes_donativos.id_insumo,tbl_insumos_ordenes_donativos.cantidad,tbl_insumos_ordenes_donativos.costo from tbl_ordenes_donativos,tbl_insumos_ordenes_donativos where $procesado1 tbl_ordenes_donativos.id_orden_donativo=tbl_insumos_ordenes_donativos.id_orden_donativo and tbl_insumos_ordenes_donativos.id_orden_donativo=$f_donativo[id_orden_donativo]  ";
$r_gastod = ejecutar($q_gastod);
$montod=0;
while($f_gastod = asignar_a($r_gastod)){
	$montod= $montod + $f_gastod[costo];
	$montodt=$montodt + $f_gastod[costo];
	}
		
?>

  <tr>
	<td class="datos_cliente"><?php echo $f_donativo[no_orden_donativo]?></td>  
    <td class="datos_cliente"><?php echo "$f_donativo[nombres] $f_donativo[apellidos]"?></td>
    <td class="datos_cliente"><?php echo $f_donativo[cedula]?></td>	
    <td class="datos_cliente"><?php echo $nombre?></td>
    <td class="datos_cliente"><?php echo $cedula?></td></td>
	 <td class="datos_cliente"><?php echo $f_donativo[nombre]?></td>
    <td class="datos_cliente"><?php echo $f_donativo[fecha_donativo]?></td>
	<td class="datos_cliente"><?php echo $f_donativo[comentarios]?></td>
	<td class="datos_cliente"><?php echo formato_montos($montod)?></td>
  </tr>
<?php
}
?>



<tr>
	

	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	    <td class="titulo3">Total</td>
	<td class="titulo3"><?php echo formato_montos($montot + $montodt)?></td>
 </tr>
<tr>
	

	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	    <td class="titulo3">Monto a Cumplir</td>
	<td class="titulo3"><?php echo formato_montos($f_var[cantidad])?></td>
 </tr> 
<tr>
	

	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
	<td class="tdcamposc"></td>
    <td class="tdcamposc"></td>	
    <td class="tdcamposc"></td>
	    <td class="titulo3">Resta</td>
	<td class="titulo3"><?php echo formato_montos($f_var[cantidad] - ($montot + $montodt))?></td>
 </tr>  


</table>

