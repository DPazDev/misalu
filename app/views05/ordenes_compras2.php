<?php 

//pedir por que almacen decia cargar la factura
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
$idorden=$_POST['nunpedido'];//pedido
$mostrararticulos=false;

if(isset($_POST['dependecia']))//selecion de almacen
{//echo "recivi la dependencia";
	$dependecia=$_POST['dependecia'];
	$mostrararticulos=true;
}else {
	//selecion de la dependencia si hay mas de dos se da la ocion de selecionar la dependencia si 
	//no se usa su dependecia asignada. si no tine almacen designado se le niega la entrada
	
	$elid=$_SESSION['id_usuario_'.empresa];//cargar las dependencias id_admin

	$dependciacentral=("
	select tbl_dependencias.id_dependencia,tbl_dependencias.dependencia,tbl_admin_dependencias.activar 
	from tbl_dependencias,tbl_admin_dependencias tbl_admin_dependencias where 
	tbl_dependencias.esalmacen=1 and 
	tbl_admin_dependencias.activar=1 and
	tbl_dependencias.id_dependencia=tbl_admin_dependencias.id_dependencia and 
	tbl_admin_dependencias.id_admin=tbl_admin_dependencias.id_admin and
	tbl_admin_dependencias.id_admin=$elid order by tbl_dependencias.dependencia;");

	$repdepcentral=ejecutar($dependciacentral);
	$numdependencias=num_filas($repdepcentral);
	//echo "$numdependencias";


 	if($numdependencias <= 0) {//no tine almacen en sus dependecias
	?> <table  >
	<tr>
	<td class="titulo_seccion"> 
		Necesita los permisos para realizar esta acci&oacute;n<br>
		
	 </td>
	</tr>
	</table>
	<?php

 	}
 	else if($numdependencias>1) {//posee mas de dos almacenes
	?>
	<table>
	<tr>
	<td>
	<select id="almacenes" class="campos"  style="width: 310px;" >
			 	<option value="">Seleccione dependencia</option>
           	<?php  while($almacentral=asignar_a($repdepcentral,NULL,PGSQL_ASSOC)){?>
						<option   value='<?php echo "$almacentral[id_dependencia]"?>'> <?php echo "$almacentral[dependencia]"?></option>
			      <?php
			             }
		      ?>
 	</select>
 	</td>
 	</tr>

	<tr>
	<td>
	<a href="#" title="selecion de dependencia" class="boton" onclick="selecion_almacen_oden_compra('<?php echo $idorden; ?>')">Procesar </a>
	</td>
	</tr>

	</table>	
	 
	<?php
	}else {//si solo tiene asignado una dependencia 
	$depen=asignar_a($repdepcentral,NULL,PGSQL_ASSOC);
	$dependecia=$depen['id_dependencia'];
	$mostrararticulos=true;
	}
}//fin selecion de almacen

if($mostrararticulos==true){
///mostrar la factura e insumos

$sqlart="
 select
 tbl_insumos_ordenes_compras.id_insumo_orden_compra,
  tbl_insumos_ordenes_compras.id_insumo,
  tbl_insumos.insumo,
  tbl_laboratorios.laboratorio,
  tbl_tipos_insumos.tipo_insumo,
  tbl_insumos_ordenes_compras.monto_producto,
  tbl_insumos_ordenes_compras.monto_unidad,
  tbl_insumos_ordenes_compras.iva,
  tbl_insumos_ordenes_compras.aumento, tbl_insumos_ordenes_compras.cantidad,
  (SELECT
  tbl_insumos_almacen.cantidad
FROM 
  public.tbl_insumos_almacen
WHERE 
  tbl_insumos_almacen.id_insumo = tbl_insumos_ordenes_compras.id_insumo and tbl_insumos_almacen.id_dependencia='$dependecia') as cantalmacen
  from
  tbl_insumos_ordenes_compras,tbl_insumos,tbl_laboratorios,tbl_tipos_insumos
where
  tbl_insumos_ordenes_compras.id_orden_compra='$idorden'  and
  tbl_insumos_ordenes_compras.id_insumo=tbl_insumos.id_insumo and
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo 
 order by tbl_insumos.insumo;
";
$consart=ejecutar($sqlart);

?>
<table border="0" id='colortable' class="tabla_cabecera5"  cellpadding=0 cellspacing=0 >
<tr ><td colspan="7"> Cargar articulos de la orden de compras </tr></th>
<tr class="tdtitulos">
<td title='Articulo' class="tdtitulos">Articulo</td> 
<td title='laboratorio' class="tdtitulos">laboratorio</td> 
<td title='precio por unidad' class="tdtitulos">Precio unitario</td> 
<td title='cantidad despachada' class="tdtitulos">Cant. despachada</td>
<td title='Cant. Actua' class="tdtitulos">cant. en almacen</td>
<td title='cantidad Final' class="tdtitulos">cant. Final</td>
<td title='aciones por insumo' class="tdtitulos">Occiones</td>
</tr>
<?php
$num=0;
while($datoArt=asignar_a($consart,NULL,PGSQL_ASSOC)){
	$idinsumoorden=$datoArt['id_insumo_orden_compra'];
	
///////////////////////////////////CONSUTA DE LOTES de este INSUMO/////////////////////////////////////////////////////////////////////
$sqllotes="SELECT 
  tbl_lotes_insumos_ordenes.id_insumo_orden_compra, 
  tbl_lotes_insumos_ordenes.cantidad, 
  tbl_lotes.id_lote, 
  tbl_lotes.lote, 
  tbl_lotes.fecha_caduca_lote
FROM 
  public.tbl_lotes, 
  public.tbl_lotes_insumos_ordenes
WHERE 
  tbl_lotes.id_lote = tbl_lotes_insumos_ordenes.id_lote and tbl_lotes_insumos_ordenes.id_insumo_orden_compra='$idinsumoorden';";
 
  $cosultalotes=ejecutar($sqllotes);
  $numreglotes=num_filas($cosultalotes);	
$nunlote=0;  

if($numreglotes>0) {
	$cadenalote='';
	$i=0;
while($lot=asignar_a($cosultalotes,NULL,PGSQL_ASSOC)){
///asisganar separador 
$i++;
if($i==1) {$spa='';}else {$spa='***';}

$idlote=$lot['id_lote'];
$lote=$lot['lote'];
$lotecantidad=$lot['cantidad'];
$lotefecha=$lot['fecha_caduca_lote'];
$statu=0;

$cadena=$lote.'::'.$lotecantidad.'::'.$lotefecha.'::'.$statu;
$cadenalote=$cadenalote.$spa.$cadena;

}
$nunlote=$i; //echo"<h1>$nunlote -</h1>";
}

?>
<tr>
	<td class="tdcampos"><?php echo $datoArt['insumo'];?></td> 
	<td class="tdcampos"><?php echo $datoArt['laboratorio'];?></td> 
	<td class="tdcampos"><?php echo $datoArt['monto_unidad'];?></td> 
	<td class="tdcampos"><?php echo $datoArt['cantidad'];?></td>
	<td class="tdcampos"><?php if($datoArt['cantalmacen']==''){echo "0";}else { echo $datoArt['cantalmacen'];}?></td>
	<td class="tdcampos">
	<?php echo $datoArt['cantalmacen']+$datoArt['cantidad'];?>
			<input type="hidden" name='idarticulo' id='idarcticulo<?php echo $num;?>' value='<?php echo $cadenalote;?>'>
			<input type="hidden" name="crtlotes" id="crtlotes<?php echo $num;?>" value="0">	
			<input type="hidden" name="dellotes" id="dellotes<?php echo $num;?>" value="0">	
	</td>
	<?php echo "<script type='text/javascript'>
	alert(document.getElementById('idarcticulo".$num."').value)
	</script>";
	?>
	<td class="tdcampos"><span id="modlotes<?php echo $num?>"><a href="views05/ordenes_compras2_lotes.php?numfactu=<?php echo $idinsumoorden; ?>&cantidad=<?php echo $datoArt['cantidad'];?>&num=<?php echo $num;?>&nunlote=<?php echo $nunlote;?>&list=<?php echo $cadenalote;?>" title="lostes de insumos" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;">Detalles de lotes</a></span><span id="confirmar<?php echo $num?>"></span></td>

</tr>
<?php
$num++;
 }?>
 
 <tr >
 
<td align='center' colspan='7'> 
<input type="hidden" name="NumArticulos" id="NumArticulos" value="<?php echo $num;?>">

<span class="boton" onclick="convercion_orden_factura('<?php echo $idorden; ?>','<?php echo $dependecia;?>');">crear factura</span> </td> 
 </tr>
</table>

<?php 	}

?>
 <img alt="spinner" id="spinnerOrFact" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='listaOrdenes' class="tdcampos" style="display: none;">-</div>