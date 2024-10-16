<?php
include ("../../lib/jfunciones.php");
sesion();
$dependencia=$_REQUEST['dependencia'];
list($id_tipo_insumo,$tipo_insumo)=explode("@",$_REQUEST['tipo_insumo']);

if($id_tipo_insumo==0)	        $condicion_tipo_insumo="";
else			$condicion_tipo_insumo="and tbl_tipos_insumos.id_tipo_insumo=$id_tipo_insumo";

  $r_tipo_insumo=pg_query("select * from tbl_tipos_insumos where id_tipo_insumo=$id_tipo_insumo");
	($f_tipo_insumo=pg_fetch_array($r_tipo_insumo, NULL, PGSQL_ASSOC));

$r_insumos=ejecutar("select * from tbl_insumos where tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo $condicion_tipo_insumo order by tbl_insumos.insumo");


$f_insumos=asignar_a($r_insumos);


?>

<p align="left"><img src="clinisalud_files/logo_cs.gif">&nbsp;<span class="datos_cliente4">Servicio de Aten<strong></strong>ci&oacute;n Ambulatoria CliniSalud C.A.<br>
&nbsp;Rif: J-31315427-0</span></p>


<table align="center" >
	<tr>
<td colspan=4  align="center" class="titulo_seccion">Inventario de Almacen de <?php echo $f_tipo_insumo[tipo_insumo]?>  <?php echo $tipo_insumo?> &nbsp;</td>
	</tr>
	<tr>		<td colspan=4 class="titulos"></td>	</tr>
	<tr>
		<td colspan=4>
		<table border=1 cellpadding=0 cellspacing=0>
		<tr align="center">
			<td ><b>id_insumo</b></td>
			<td ><b>Nombre</b></td>
			<td ><b>Cantidad Orden de Compra</b></td>
			<td  ><b>Cantidad Almacen</b></td>
			<td ><b>Cantidad por Dependencia</b></td>
			<td  ><b>Cantidad salidas por dependecia</b></td>
			<td  ><b>Cantidad donativos</b></td>
			<td  ><b>Auditoria</b></td>
		</tr>
	<?php
	while($f_insumos=pg_fetch_array($r_insumos,NULL,PGSQL_ASSOC)){
	
		$cantidadoc=0;
		$cantidadoe=0;
		$cantidadtb=0;
		$cantidadod=0;
		$auditado=0;

		$r_orden_compra=pg_query("select * from tbl_insumos_ordenes_compras where id_insumo=$f_insumos[id_insumo]");

	while($f_orden_compra=pg_fetch_array($r_orden_compra, NULL, PGSQL_ASSOC)){

	$cantidadoc=$cantidadoc + $f_orden_compra[cantidad];


	}
		pg_free_result($r_orden_compra);



	$r_orden_donativos=pg_query("select * from tbl_insumos_ordenes_donativos where id_insumo=$f_insumos[id_insumo]");

	while($f_orden_donativos=pg_fetch_array($r_orden_donativos, NULL, PGSQL_ASSOC)){

	$cantidadod=$cantidadod + $f_orden_donativos[cantidad];


	}
		pg_free_result($r_orden_donativos);


		$r_insumos_almacen=pg_query("select * from tbl_insumos_almacen where id_insumo=$f_insumos[id_insumo] and id_dependencia=$dependencia");
		($f_insumos_almacen=pg_fetch_array($r_insumos_almacen, NULL, PGSQL_ASSOC));

		$r_orden_entrega=pg_query("select * from tbl_insumos_almacen where id_insumo=$f_insumos[id_insumo] and id_dependencia<>$dependencia");

	while($f_orden_entrega=pg_fetch_array($r_orden_entrega, NULL, PGSQL_ASSOC)){

	$cantidadoe=$cantidadoe + $f_orden_entrega[cantidad];


	}
	pg_free_result($r_orden_entrega);

		
	
	$auditado=$cantidadoc-($f_insumos_almacen[cantidad] + $cantidadoe +  $cantidadod);
		echo "	
	
	
	
		<tr>
			<td class=\"titulos\">$f_insumos[id_insumo]</td>
			<td class=\"titulos\">$f_insumos[insumo]</td>
			<td class=\"titulos\">$cantidadoc</td>
			<td class=\"titulos\">$f_insumos_almacen[cantidad]</td>
			<td class=\"titulos\">$cantidadoe</td>
			<td class=\"titulos\">$cantidadtb</td>
			<td class=\"titulos\">$cantidadod</td>
			<td class=\"titulos\">$auditado</td>
		</tr>
		";
	}
	pg_free_result($r_insumos);

	?>
		</table>
		</td>
	</tr>
	
</table>
<?php
echo pie();
?>
