<?php 
include ("../../lib/jfunciones.php"); 
sesion(); 
$nombre=$_POST['nombre']; 

$fecre1=$_REQUEST['fecha1'];
list($id_estat,$estat)=explode("@",$_REQUEST['estatus']);
$nombre = strtoupper($nombre);

echo $nombre;
echo $id_estat;
echo $fecre1;

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td class="titulo_seccion" colspan="19">REPORTE HISTORIAL DE ARTICULOS DESDE EL <?php echo "$fecre1 ";?>  </td>

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>	

<table class="tabla_citas"  cellpadding=0 cellspacing=0  border=3> 

	<tr> 
		<td class="tdcampos">ARTICULO</td>   	   	
		<td class="tdcampos">MARCA</td>
	        <td class="tdcampos">CANTIDAD</td> 
		<td class="tdcampos">FECHA DESPACHO</td>
	        <td class="tdcampos">ESTATUS</td>
		<td class="tdcampos">DEPENDENCIA</td> 
		
	</tr>

<?php


$q_art=("select tbl_insumos.insumo,
	tbl_laboratorios.laboratorio,
	tbl_insumos_ordenes_pedidos.cantidad,
	tbl_ordenes_pedidos.fecha_despachado,
	tbl_dependencias.dependencia
	from tbl_insumos, tbl_laboratorios,tbl_insumos_ordenes_pedidos,tbl_ordenes_pedidos,tbl_dependencias
	where tbl_insumos.insumo like UPPER ('%$nombre%') and tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
	tbl_insumos.id_insumo=tbl_insumos_ordenes_pedidos.id_insumo and
	tbl_ordenes_pedidos.fecha_despachado>='$fecre1' and
	tbl_insumos_ordenes_pedidos.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and 
	tbl_ordenes_pedidos.estatus='$id_estat' and 
	tbl_ordenes_pedidos.id_dependencia_saliente>'0' and
	tbl_ordenes_pedidos.id_dependencia_saliente=tbl_dependencias.id_dependencia
 order by tbl_ordenes_pedidos.fecha_despachado DESC ");


$r_art=ejecutar($q_art);

	while($f_art=asignar_a($r_art)){



echo  " 
		<tr>
		<td class=\"tdtitulos\">$f_art[insumo] </td>  
		<td class=\"tdtitulos\">$f_art[laboratorio] </td> 
		<td class=\"tdtitulos\">$f_art[cantidad] </td> 
		<td class=\"tdtitulos\">$f_art[fecha_despachado] </td>
		<td class=\"tdcamposc\">$estat </td>
		<td class=\"tdcamposc\">$f_art[dependencia] </td>
		
"; 
?>	              
	        </tr>
<?php  
}
?>
</table>
<table>
	<tr><td>&nbsp;</td></tr> 
	<tr><td>&nbsp;</td></tr>




