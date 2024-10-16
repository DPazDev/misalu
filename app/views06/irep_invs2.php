<?php
include ("../../lib/jfunciones.php");
sesion();

list($id_tipo_insumo,$tipo_insumo)=explode("@",$_REQUEST['tipo_insumo']);

$letra = $_REQUEST['letra'];
$signo = $_REQUEST['signo'];
$cantidad = $_REQUEST['cantidad'];
$dependencia=$_REQUEST['dependencia'];
$id_laboratorio = $_REQUEST['laboratorio'];
$tipo_insumo = $_REQUEST['tipo_insumo'];
$monto = $_REQUEST['monto'];

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_dependencia=("select * from tbl_dependencias where id_dependencia=$dependencia");
$r_dependencia=ejecutar($q_dependencia);
$f_dependencia=asignar_a($r_dependencia);

$condicion_tipo_insumo="";
if($id_tipo_insumo!=0){
	$condicion_tipo_insumo=" tbl_tipos_insumos.id_tipo_insumo=$id_tipo_insumo and";
}

$condicion_letra="";
if($letra!="0"){
	$condicion_letra = "tbl_insumos.insumo like '$letra%' and";
}

$condicion_cantidad="";
if(is_numeric($cantidad) && $signo!="0"){
	$condicion_cantidad="tbl_insumos_almacen.cantidad $signo $cantidad and";
}

$condicion_laboratorio="";
if($id_laboratorio!="0"){
	$condicion_laboratorio="tbl_insumos.id_laboratorio=$id_laboratorio and";
}

$q_inventario = "select tbl_insumos_almacen.monto_unidad_publico,tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_insumos.codigo_barras,
                tbl_insumos.id_insumo,tbl_insumos_almacen.cantidad, tbl_laboratorios.laboratorio
         from tbl_insumos,tbl_laboratorios,tbl_insumos_almacen,tbl_tipos_insumos
          where tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio and 	$condicion_tipo_insumo tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and $condicion_laboratorio $condicion_cantidad $condicion_letra tbl_insumos_almacen.id_dependencia=$dependencia and tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo  order by tbl_insumos.insumo, tbl_tipos_insumos.tipo_insumo asc;";

$r_inventario = ejecutar($q_inventario);

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
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>
<tr>
	<tr>
		<td colspan=4 align="center" class="titulo3">Inventario de Dependencia <?php echo $f_dependencia[dependencia] ?></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
	<tr>
		<?php
		if(num_filas($r_inventario)==0){
		echo "
	<td colspan=4 align=\"center\" class=\"titulo_seccion\">No hay resultados con esos parametros.</td>
		</tr>
		";
		}else{
		?>
	<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>
		<tr>
			<td class="titulo3"><b>Nombre</b></td>
			<td class="titulo3"><b>Codigo Barra</b></td>
			<td class="titulo3"><b>Cantidad</b></td>
			<td class="titulo3"><b>Costo Por Unidad</b></td>
			<td class="titulo3"><b>Marca</b></td>
		</tr>
		<?php
		$monto_total_final = 0;
		$monto_unidad_final = 0;
		$cantidad_final = 0;
		while($f=pg_fetch_assoc($r_inventario)){
			$total = $f['cantidad'] * $f['monto_unidad_cs'];

			$monto_total_final += $total;
			$monto_unidad_final += $f['monto_unidad_cs'];
			$cantidad_final += $f['cantidad'];

			$total = montos_print($total);

			if ($f[id_tipo_insumo]==2 and $dependencia<>89){ //SUMINISTRO HOSPITALARIO
				///1)Buscar el precio Almacen Control de precios id=2
						$q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=2");
						$r_precio=ejecutar($q_precio);
				///2) Si no se encuentra busca el precio en el Almacen temporal merida
							if(num_filas($r_precio)==0){
									 $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=89");
									 $r_precio=ejecutar($q_precio);
								}
				///3) Si no se encuentra busca el precio del almacen actual
							 if(num_filas($r_precio)==0){
									 $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.id_dependencia=$dependencia");
									 $r_precio=ejecutar($q_precio);
								}

						$f_precio=asignar_a($r_precio);
						$monto_unidad = montos_print($f_precio['monto_unidad_publico']);
			}

			if ($f[id_tipo_insumo]==1 and $dependencia<>64){//MEDICAMENTOS
				///1)Buscar el precio Almacen Control de precios id=2
						$q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=2");
						$r_precio=ejecutar($q_precio);
				///2) Si no se encuentra busca el precio en el Almacen temporal merida
							if(num_filas($r_precio)==0){
									 $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.monto_unidad_publico>'0' and tbl_insumos_almacen.id_dependencia=64");
									 $r_precio=ejecutar($q_precio);
								}
				///3) Si no se encuentra busca el precio del almacen actual
							 if(num_filas($r_precio)==0){
									 $q_precio=("select * from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$f[id_insumo] and tbl_insumos_almacen.id_dependencia=$dependencia");
									 $r_precio=ejecutar($q_precio);
								}
			$f_precio=asignar_a($r_precio);
			$monto_unidad = montos_print($f_precio['monto_unidad_publico']);
			}


		echo "
		<tr>
			<td class=\"tdcamposcc\">$f[insumo]</td>
			<td class=\"tdcamposcc\">$f[codigo_barras]</td>
			<td class=\"tdcamposcc\" align=\"center\">$f[cantidad]</td>
			<td class=\"tdcamposcc\" align=\"center\"> $monto_unidad</td>
			<td class=\"tdcamposcc\" align=\"center\">$f[laboratorio]</td>
		</tr>";
		}
		$monto_unidad_final = montos_print($monto_unidad_final);
		$monto_total_final = montos_print($monto_total_final);
		?>
		<?php
		echo "
		<tr>
			<td class=\"tdcamposc\" align=\"right\"></td>
			<td class=\"tdcamposc\" align=\"right\">TOTALES</td>
			<td class=\"tdcamposcc\" align=\"center\">$cantidad_final</td>
			<td class=\"tdcamposc\" align=\"center\"></td>
			<td class=\"tdcamposc\" align=\"center\">&nbsp;</td>
		</tr>
			";

		?>
		</table>
		<?php
		}
		?>
		</td>
	</tr>

</table>
<?php
echo pie();
?>
