<?php 
//trasformacion a factura una orden de compra
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
$idorden=$_POST['nunpedido'];
echo "<h1>$idorden</h1>";
//$idorden='6693';
$dependecia='89';

$sqlart="
 select
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
<table border="1" id='colortable' class="tabla_cabecera5"  cellpadding=0 cellspacing=0 >
<tr>
<td title='Articulo' class="tdtitulos">Articulo</td> 
<td title='laboratorio' class="tdtitulos">laboratorio</td> 
<td title='precio por unidad' class="tdtitulos">Precio unitario</td> 
<td title='cantidad despachada'>Cant. despachada</td>
<td title='Cant. Actua' class="tdtitulos">cant. en almacen</td>
<td title='cantidad Final' class="tdtitulos">cant. Final</td>
</tr>
<?php
while($datoArt=asignar_a($consart,NULL,PGSQL_ASSOC)){?>
<tr>
<td class="tdcampos"> <?php echo $datoArt['insumo'];?></td> 
<td class="tdcampos"><?php echo $datoArt['laboratorio'];?></td> 
<td class="tdcampos"><?php echo $datoArt['monto_unidad'];?></td> 
<td class="tdcampos"><?php echo $datoArt['cantidad'];?></td>
<td class="tdcampos"><?php if($datoArt['cantalmacen']==''){echo "0";}else { echo $datoArt['cantalmacen'];}?></td>
<td class="tdcampos"><?php echo $datoArt['cantalmacen']+$datoArt['cantidad'];?></td>

</tr>
<?php
 }?>
 
 <tr >
 
<td align='center' colspan='6'> <span onclick="convercion_orden_factura('<?php echo $idorden; ?>','');">crear factura</span> </td> 
 </tr>
</table>
