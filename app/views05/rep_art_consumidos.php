<?php 
include ("../../lib/jfunciones.php"); 
sesion(); 
$nombre=$_POST['nombre']; 

$fecre1=$_REQUEST['fecha1'];
$fecre2=$_REQUEST['fecha2'];

$nombre = strtoupper($nombre);

/*echo $nombre;

echo $fecre1;
echo $fecre2;*/
$cantidad=0;
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td class="titulo_seccion" colspan="19">REPORTE HISTORIAL DE ARTICULOS DESDE EL <?php echo "$fecre1 ";?> HASTA EL <?php echo "$fecre2 ";?>  </td>

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>	

<table class="tabla_citas"  cellpadding=0 cellspacing=0  border=3> 

	<tr> 
		<td class="tdcampos">ARTICULO</td>   	   	
	        <td class="tdcampos">CANTIDAD PEDIDA</td> 		
		<td class="tdcampos">MARCA</td>
	        <td class="tdcampos">CANTIDAD DESPACHADA</td> 
		<td class="tdcampos">FECHA DESPACHO</td>
		<td class="tdcampos">DEPENDENCIA</td> 
		
	</tr>

<?php


$q_art=("select tbl_insumos.insumo,
	tbl_laboratorios.laboratorio,
	tbl_insumos_ordenes_pedidos.cantidad,
	(tbl_insumos_ordenes_entregas.cantidad) AS p,
	tbl_ordenes_pedidos.fecha_despachado,
	tbl_insumos.id_insumo,
	tbl_dependencias.dependencia,
	tbl_dependencias.id_dependencia,
tbl_ordenes_pedidos.id_dependencia_saliente,
(tbl_ordenes_pedidos.id_dependencia) AS w

	from tbl_insumos, tbl_laboratorios,
tbl_insumos_ordenes_pedidos,tbl_ordenes_pedidos,
tbl_dependencias,
tbl_insumos_ordenes_entregas,tbl_ordenes_entregas
	where tbl_insumos.insumo like UPPER ('%$nombre%') and 
	tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
	tbl_insumos.id_insumo=tbl_insumos_ordenes_pedidos.id_insumo and
	tbl_insumos.id_insumo=tbl_insumos_ordenes_entregas.id_insumo and
	tbl_ordenes_pedidos.fecha_despachado between '$fecre1' and '$fecre2' and
tbl_insumos_ordenes_pedidos.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and 

tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and

tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and  

tbl_ordenes_pedidos.id_dependencia=89 and

	tbl_ordenes_pedidos.id_dependencia_saliente>'0' and
	tbl_ordenes_pedidos.id_dependencia_saliente=tbl_dependencias.id_dependencia
 order by tbl_ordenes_pedidos.fecha_despachado DESC ");

/*echo $q_art;*/


$r_art=ejecutar($q_art);
$cant=0;
	while($f_art=asignar_a($r_art)){



$cant=($f_art[p]) + $cant;


echo  " 
		<tr>
		<td class=\"tdcamposc\">$f_art[insumo] </td>  
		<td class=\"tdcamposc\">$f_art[cantidad] </td> 
		<td class=\"tdcamposc\">$f_art[laboratorio] </td> 
		<td class=\"tdcamposc\">$f_art[p] </td> 
		<td class=\"tdcamposc\">$f_art[fecha_despachado] </td>
		<td class=\"tdcamposc\">$f_art[dependencia] </td>
		
"; ?>	              
	        </tr>

<?php  
}
?>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
<tr><td colspan=19>&nbsp;</td></tr>
	<tr>
	        <td colspan=19 class="tdcampos" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CANTIDAD TOTAL DE ARTICULOS DESPACHADOS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <?php echo  $cant; ?> 
	<tr> <td colspan=4>&nbsp;</td></tr>





<table>
	<tr><td>&nbsp;</td></tr> 
	<tr><td>&nbsp;</td></tr>


