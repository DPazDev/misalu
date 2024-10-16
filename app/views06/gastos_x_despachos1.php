<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
?>
  <?php
/////recepcion de variables
$std_pedido=$_POST['estadop']; //estado el pedido 'Pendiente,Despachado, recibido,realizado' 
$dependencia=$_POST['id_dep'];  //dependencia a consultar
$fechai=$_POST['fechainicio'];  //fecha de conusla Inicial
$fechaf=$_POST['fechafin'];  //fecha final de consulta

  
$sqldespacho=" SELECT 
  tbl_ordenes_pedidos.id_orden_pedido, no_orden_pedido,
  tbl_ordenes_pedidos.fecha_despachado, 
  tbl_ordenes_pedidos.fecha_recibido, 
  (SELECT admin.nombres||' '||admin.apellidos as nom FROM  public.admin where admin.id_admin=tbl_ordenes_pedidos.id_admin)as encargado, 
  tbl_ordenes_pedidos.id_dependencia,
  (SELECT 
  tbl_dependencias.dependencia
FROM 
  public.tbl_dependencias
WHERE 
  tbl_dependencias.id_dependencia = tbl_ordenes_pedidos.id_dependencia) as dep,
   (SELECT 
  tbl_dependencias.dependencia
FROM 
  public.tbl_dependencias
WHERE 
  tbl_dependencias.id_dependencia = tbl_ordenes_pedidos.id_dependencia_saliente) as saliente,
  tbl_ordenes_pedidos.estatus
FROM 
  public.tbl_ordenes_pedidos
WHERE 
  tbl_ordenes_pedidos.fecha_recibido BETWEEN '$fechai' AND '$fechaf' AND 
  tbl_ordenes_pedidos.id_dependencia_saliente='$dependencia' AND
  tbl_ordenes_pedidos.estatus ='$std_pedido' AND 
  tbl_ordenes_pedidos.id_dependencia = '89' and tbl_ordenes_pedidos.id_proveedor='0' order by tbl_ordenes_pedidos.fecha_recibido desc ; "; 

 	$despacho=ejecutar($sqldespacho);
 	$cantidaddespachos=num_filas($despacho);
 	if($cantidaddespachos>0){
  ?>
<table class="tabla_cabecera5" cellpadding=0 cellspacing=0  >
<tr height='10%'  >
	<td class="tdtitulos">No. Pedido</td>
	<!--<td class="tdtitulos">encargado</td>-->
	<td class="tdtitulos">dependencia receptora</td>
	<td class="tdtitulos">Dependencia Saliente</td> 
	<td class="tdtitulos">fecha de pedido</td>
	<td class="tdtitulos">Precio</td>
	<td class="tdtitulos">Acci&oacute;n</td>
</tr>
<!-- ---- RESULTADOS PARA MOSTRAR ---------------------------- --> 
   <?php 
$TotalTotal=0;   
    $n=0; while($desp=asignar_a($despacho,NULL,PGSQL_ASSOC)){//siclo de repeticion para muestra de resultados ?>
<tr >
	<td class="tdcampos"><?php echo "$desp[no_orden_pedido]"; ?></td>
	<!--<td class="tdcampos"><?php echo "$desp[encargado]"; ?></td>-->
	<td class="tdcampos"><?php echo "$desp[saliente] ($desp[encargado])"; ?></td>
	<td class="tdcampos"><?php echo "$desp[dep]"; ?></td>
	<td class="tdcampos"><?php echo "$desp[fecha_recibido]"; ?></td>
<td class="tdcampos">
<?php 

$sqldetall="SELECT 
  tbl_insumos_ordenes_pedidos.id_insumo ,
  tbl_insumos_ordenes_pedidos.cantidad
FROM 
  public.tbl_insumos_ordenes_pedidos, 
  public.tbl_ordenes_pedidos
WHERE 
  tbl_insumos_ordenes_pedidos.id_orden_pedido = tbl_ordenes_pedidos.id_orden_pedido and  tbl_insumos_ordenes_pedidos.id_orden_pedido='$desp[id_orden_pedido]' order by tbl_ordenes_pedidos.id_orden_pedido;
";
$ins_depachado=ejecutar($sqldetall);
//$numerosql=num_filas($ins_depachado);
$total=0;
while($insDes=asignar_a($ins_depachado,NULL,PGSQL_ASSOC)){//siclo de repeticion lista de insumos
	
$sqlpre="SELECT 
  tbl_insumos.insumo, 
  tbl_insumos_ordenes_compras.monto_producto,
  tbl_insumos_ordenes_compras.monto_unidad, 
  tbl_laboratorios.laboratorio, 
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
tbl_insumos_ordenes_compras.id_insumo='$insDes[id_insumo]' order by tbl_ordenes_compras.fecha_compra desc limit 1";

$insup=ejecutar($sqlpre);
$prec=asignar_a($insup,NULL,PGSQL_ASSOC);
$preciounitario=$prec[monto_unidad];
$tineiva=$prec[iva];
$monto=$prec[monto_producto];
$cantidad=$insDes[cantidad];
if($tineiva==1)
{
	$sqliva="select cantidad from variables_globales where nombre_var='iva';";
	$ivasq=ejecutar($sqliva);
$iv=asignar_a($ivasq,NULL,PGSQL_ASSOC);
//echo "<br>iva $iva[cantidad]";
$iva=$iv[cantidad];
$tiva=$preciounitario*$iva;
$precmasiva=$preciounitario+$tiva;
$subtotal=$precmasiva*$cantidad;

}
else 
{$iva=0;
$precmasiva=$preciounitario;
$subtotal=$preciounitario*$cantidad;}

$total=$total+$subtotal;

}
echo "$total";
$TotalTotal=$TotalTotal+$total;

?>	
	</td>
	
	
<td class="tdcampos"> 
	<?php
 echo '<label class="boton" style="cursor:pointer"  onclick="fdetallxpedidos( '.$desp[id_orden_pedido].','.$std_pedido.');return false;" >Detalles</label>';	
	?></td>
</tr>
<?php }?>
<tr> 
<td class="tdcampos"> </td>
<td class="tdcampos">Cantidad de despachos</td>
<td class="tdcampos"><?php echo" $cantidaddespachos";?></td>
<td class="tdcampos">Total de Gastos      </td>
<td class="tdcampos"><?php echo"$TotalTotal"?></td>
<td class="tdcampos"> </td>
</tr>
</table>

<?php 
}else {?>
<table class="tabla_cabecera5"cellpadding=0 cellspacing=0  >
<tr>
<td colspan="5" class="titulo_seccion"> No se encontraron resultados!!!<?php echo $cantidaddespachos;?>
</td>
</tr>
</table>	
<?php

}	 ?>
	