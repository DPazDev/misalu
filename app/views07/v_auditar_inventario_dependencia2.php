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
			<td ><b>Cantidad Orden de Entrega</b></td>
			<td ><b>Cantidad de la  Dependencia</b></td>
			<td  ><b>Cantidad salidas por dependecia</b></td>
			<td  ><b>Inven Muer</b></td>
			<td  ><b>gastos_T_b</b></td>
			<td  ><b>Auditoria</b></td>
		</tr>
	<?php
	while($f_insumos=pg_fetch_array($r_insumos,NULL,PGSQL_ASSOC)){
	
		$cantidadoe=0;
		$cantidadoe1=0;
		$cantidadoe2=0;
		$cantidadtb=0;
		$cantidadod=0;
		$auditado=0;
		$auditado1=0;
		$cantidadga=0;

		$r_orden_entrega=pg_query("select * from tbl_insumos_ordenes_entregas where 
tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega 
and tbl_ordenes_entregas.id_dependencia=$dependencia 
and tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_insumo=$f_insumos[id_insumo] and tbl_ordenes_entregas.id_orden_pedido>0");

	while($f_orden_entrega=pg_fetch_array($r_orden_entrega, NULL, PGSQL_ASSOC)){

	$cantidadoe=$cantidadoe + $f_orden_entrega[cantidad];


	}
		pg_free_result($r_orden_entrega);

  $r_orden_entrega1=pg_query("select * from tbl_insumos_ordenes_entregas where 
tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega
and tbl_ordenes_entregas.id_dependencia=$dependencia                                                                                          
and tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_insumo=$f_insumos[id_insumo] 
and tbl_ordenes_entregas.id_orden_pedido=0");
        while($f_orden_entrega1=pg_fetch_array($r_orden_entrega1, NULL, PGSQL_ASSOC)){

        $cantidadoe1=$cantidadoe1 + $f_orden_entrega1[cantidad];


        }
                pg_free_result($r_orden_entrega1);


$r_orden_entrega2=pg_query("select * from tbl_insumos_ordenes_entregas where
tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega
and tbl_ordenes_entregas.id_dependencia=tbl_ordenes_pedidos.id_dependencia_saliente and tbl_ordenes_pedidos.id_dependencia=$dependencia 
and tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido 
and tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_insumo=$f_insumos[id_insumo]");
        while($f_orden_entrega2=pg_fetch_array($r_orden_entrega2, NULL, PGSQL_ASSOC)){

        $cantidadoe2=$cantidadoe2 + $f_orden_entrega2[cantidad];


        }
                pg_free_result($r_orden_entrega2);

$r_gastos=pg_query("select * from gastos_t_b where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_admin=admin.id_admin and admin.id_admin=tbl_admin_dependencias.id_admin and tbl_admin_dependencias.id_dependencia=$dependencia and gastos_t_b.id_insumo=$f_insumos[id_insumo] and procesos.fecha_creado>='2011-01-01'");

	while($f_gastos=pg_fetch_array($r_gastos, NULL, PGSQL_ASSOC)){

	$cantidadga=$cantidadga + $f_gastos[unidades];


	}
		pg_free_result($r_gastos);

		$r_insumos_dependencia=pg_query("select * from tbl_insumos_almacen where id_insumo=$f_insumos[id_insumo] 
and id_dependencia=$dependencia ");
		($f_insumos_dependencia=pg_fetch_array($r_insumos_dependencia, NULL, PGSQL_ASSOC));

	

		

	$auditado=$cantidadoe - ($f_insumos_dependencia[cantidad] + $cantidadoe1 + $cantidadoe2 + $cantidadga);

		echo "	
	
	
	
		<tr>
			<td class=\"titulos\">$f_insumos[id_insumo]</td>
			<td class=\"titulos\">$f_insumos[insumo]</td>
			<td class=\"titulos\">$cantidadoe</td>
			<td class=\"titulos\">$f_insumos_dependencia[cantidad]</td>
			<td class=\"titulos\">$cantidadoe2</td>
			<td class=\"titulos\">$cantidadoe1</td>
			<td class=\"titulos\">$cantidadga</td>
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
