<?php
include ("../../lib/jfunciones.php");
sesion();
$elpedidoes=$_POST['pedidoid'];
$eliorden=("update tbl_ordenes_pedidos set estatus=4 where id_orden_pedido=$elpedidoes");
$repeliorden=ejecutar($eliorden);
$consultapedido=("select tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia
 from tbl_ordenes_pedidos where id_orden_pedido=$elpedidoes");
$datapedido=ejecutar($consultapedido);
$dataPed=assoc_a($datapedido);
$dep=$dataPed['id_dependencia'];
$idPedido=$dataPed['id_orden_pedido'];
echo"$dep,$idPedido";
/*$eliordenp=("delete from tbl_insumos_ordenes_pedidos where id_orden_pedido=$elpedidoes");
$repeliordenp=ejecutar($eliordenp);*/
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha ANULADO el pedido con id_orden_pedido=$elpedidoes";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
