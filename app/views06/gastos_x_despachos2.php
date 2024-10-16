<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
?>
  
<?php
/////recepcion de variables
$idped=$_POST['idped']; //estado el pedido 'Pendiente,Despachado, recibido,realizado' 
$std_pedido=$_POST['stat'];  //dependencia a consultar


  
$sqldetall="SELECT 
  tbl_insumos_ordenes_pedidos.id_insumo ,no_orden_pedido,
(SELECT 
  tbl_dependencias.dependencia
FROM 
  public.tbl_dependencias
WHERE 
  tbl_dependencias.id_dependencia = tbl_ordenes_pedidos.id_dependencia_saliente) as saliente,  
  
  (select insumo from tbl_insumos where tbl_insumos.id_insumo=tbl_insumos_ordenes_pedidos.id_insumo) as insumo, 
  tbl_insumos_ordenes_pedidos.cantidad, 
  tbl_insumos_ordenes_pedidos.id_orden_pedido, 
  tbl_ordenes_pedidos.fecha_recibido, 
  tbl_ordenes_pedidos.id_admin, 
  tbl_ordenes_pedidos.estatus
FROM 
  public.tbl_insumos_ordenes_pedidos, 
  public.tbl_ordenes_pedidos
WHERE 
  tbl_insumos_ordenes_pedidos.id_orden_pedido = tbl_ordenes_pedidos.id_orden_pedido  and tbl_insumos_ordenes_pedidos.id_orden_pedido='$idped' order by id_orden_pedido;
";

 	$detallessql=ejecutar($sqldetall);
 	$cantidaddespachos=num_filas($detallessql);
 $TotalTotal=0;
 $tcantidad=0;
 $cantidad=0;
 $totalpreciounitario=0;
 $depen=ejecutar($sqldetall);
 $dep=asignar_a($depen,NULL,PGSQL_ASSOC)
  ?>
<table class="tabla_cabecera5"cellpadding=0 cellspacing=0  >
<tr>
<td colspan="6" class="titulo_seccion"> <?php echo "$dep[saliente] Pedido $dep[no_orden_pedido]"; ?>
</td>
</tr>
<tr>
	<td class="tdtitulos">Id insumo</td>
	<td class="tdtitulos">insumo</td>
	<td class="tdtitulos">cantidad</td>
	<td class="tdtitulos">Monto Unitario</td>
	 <td class="tdtitulos">Mas IVA</td>
	
	<td class="tdtitulos">Monsto Completo</td>

</tr>
<!-- ---- RESULTADOS PARA MOSTRAR ---------------------------- --> 
   <?php  $n=0; while($dped=asignar_a($detallessql,NULL,PGSQL_ASSOC)){//siclo de repeticion para muestra de resultados 
   
$sqlpre="SELECT 
  tbl_insumos.insumo, 
  tbl_insumos_ordenes_compras.monto_producto,
  tbl_insumos_ordenes_compras.monto_unidad, 
  tbl_laboratorios.laboratorio as lab, 
  tbl_insumos_ordenes_compras.iva,
  tbl_ordenes_compras.fecha_compra
FROM 
  public.tbl_insumos_ordenes_compras, 
  public.tbl_insumos, 
  public.tbl_ordenes_compras, 
  public.tbl_laboratorios
WHERE 
  tbl_insumos_ordenes_compras.id_insumo = tbl_insumos.id_insumo AND
  tbl_insumos_ordenes_compras.id_orden_compra = tbl_ordenes_compras.id_orden_compra AND
  tbl_laboratorios.id_laboratorio = tbl_insumos.id_laboratorio and 
tbl_insumos_ordenes_compras.id_insumo='$dped[id_insumo]' order by tbl_ordenes_compras.fecha_compra desc limit 1;";

$insup=ejecutar($sqlpre);
$prec=asignar_a($insup,NULL,PGSQL_ASSOC);
$preciounitario=$prec[monto_unidad];
$tineiva=$prec[iva];
$monto=$prec[monto_producto];
$cantidad=$dped[cantidad];

if($tineiva==1)
{$sqliva="select cantidad from variables_globales where nombre_var='iva';";
	$ivasq=ejecutar($sqliva);
$iv=asignar_a($ivasq,NULL,PGSQL_ASSOC);
//echo "<br>iva $iva[cantidad]";
$iva=$iv[cantidad];
$tiva=$preciounitario*$iva;
$precmasiva=$preciounitario+$tiva;
$subtotal=$precmasiva*$cantidad;
}
else {
$iva=0;
$precmasiva=$preciounitario;
$subtotal=$preciounitario*$cantidad;
}
//contadores
$tcantidad=$tcantidad+$cantidad;
$total=$total+$subtotal;
 $totalpreciounitario=$totalpreciounitario+$preciounitario;  
   ?>
<tr>
	<td class="tdcampos"><?php echo "$dped[id_insumo]"; ?></td>
	<td class="tdcampos"><?php echo "$dped[insumo] ($prec[lab])"; ?></td>
	<td class="tdcampos"><?php echo "$dped[cantidad]"; ?></td>
	<td class="tdcampos"><?php  echo "$preciounitario ";	?></td>
	<td class="tdcampos"><?php  echo "$precmasiva";	?></td>
	
	<td class="tdcampos"><?php  echo "$subtotal ";	?></td>
	
</tr>
<?php }?>
<tr><td colspan="5" class="tdcampos">
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
</tr>
<tr> 
<td class="tdcampos" colspan="2"> Cantidad de despachos </td>
<td class="tdcampos">Total de cantidades</td>
<td class="tdcampos" > Total de Gastos</td>
<td class="tdcampos"> </td>
<td class="tdcampos">Total  </td>

</tr>

<tr> 
<td class="tdcampos"><?php echo" $cantidaddespachos";?></td>
<td class="tdcampos"></td>
<td class="tdcampos" ><?php echo" $tcantidad";?> </td>
<td class="tdcampos"> <?php echo " $totalpreciounitario";?></td>
<td class="tdcampos"></td>
<td class="tdcampos"><?php echo"$total"?> </td>

</tr>
</table>