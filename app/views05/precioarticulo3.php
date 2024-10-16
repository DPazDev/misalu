<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$idarti=$_POST['idarticulo'];
$iddepen=$_POST['ladependencia'];
$nmonto=$_POST['nuevomonto'];
$nominsumo=("select tbl_insumos.insumo from tbl_insumos where id_insumo=$idarti");
$repnominu=ejecutar($nominsumo);
$infoinsumo=assoc_a($repnominu);

$insumo=$infoinsumo['insumo'];
$actualizarmonto="update tbl_insumos_almacen set monto_unidad_publico='$nmonto' where id_insumo=$idarti and id_dependencia=$iddepen;";
$repactualizmonto=ejecutar($actualizarmonto);

///Actualice precio En almacen 2 Control de precio
//buscar si existe en el almacen 2
$CrtArticulo="select * from tbl_insumos_almacen where id_insumo=$idarti and id_dependencia=2;";
$CrtArt=ejecutar($CrtArticulo);
$cuantos=num_filas($CrtArt);
if($cuantos>0)
{///actualice el precio
  $sqlControlPrecio="update tbl_insumos_almacen set monto_unidad_publico='$nmonto' where id_insumo=$idarti and id_dependencia=2;";
  $ActuliceCrtPrecio=ejecutar($sqlControlPrecio);
}
else
{//inserte el articulo
  echo$sqlControlPrecio="insert into tbl_insumos_almacen (id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia,comentario)
  VALUES ('$idarti',0,$nmonto,0,'$fecha','2','CONTROL DE PRECIO')";
  $insert=ejecutar($sqlControlPrecio);
}

//registro en el log
   $mensaje="El usuario $elus ha actualizado el monto del articulo $insumo con el nuevo precio de Bs.S $nmonto";
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),$elid,'$fecha','$hora','$ip')");
	 $repactualizoellog=ejecutar($actualizoellog);

?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
   <tr>
     <td colspan=4 class="titulo_seccion">El art&iacute;culo <?echo $insumo?> se ha actualizado exitosamente!!</td>
	<td class="titulo_seccion" title="Regresar al modulo"><label class="boton" style="cursor:pointer" onclick="precio_articulos()" >Regresar </label></td>
   </tr>
</table>
