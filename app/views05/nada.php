<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$idordepedido=$_POST['elnpedido'];

$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
//primero tengo q saber a  que dependencia se le sumaran los articulos del pedido
$comoeselpedido=("select tbl_ordenes_entregas.id_orden_pedido,tbl_ordenes_entregas.id_orden_entrega,
tbl_ordenes_pedidos.id_dependencia_saliente from tbl_ordenes_pedidos,tbl_ordenes_entregas
where
tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
tbl_ordenes_entregas.id_orden_pedido=$idordepedido;");
$repcomoeselpedido=ejecutar($comoeselpedido);
$pedidodata=assoc_a($repcomoeselpedido);
$dependasumar=$pedidodata['id_dependencia_saliente'];
$seleentrega=$pedidodata['id_orden_entrega'];
$cambiarestado=$pedidodata['id_orden_pedido'];
//buscamos todos los articulos del pedido
$buscartodo=("select tbl_insumos_ordenes_entregas.cantidad,tbl_insumos_ordenes_entregas.id_insumo
from  tbl_insumos_ordenes_entregas where
tbl_insumos_ordenes_entregas.id_orden_entrega=$seleentrega;");
$repbuscartodo=ejecutar($buscartodo);
  while($nuevosaritulos=asignar_a($repbuscartodo,NULL,PGSQL_ASSOC)){
	    $buscarprimero=("select
          tbl_insumos_almacen.id_insumo,tbl_insumos_almacen.id_dependencia,tbl_insumos_almacen.cantidad
          from tbl_insumos_almacen
		 where tbl_insumos_almacen.id_insumo=$nuevosaritulos[id_insumo] and
         tbl_insumos_almacen.id_dependencia=$dependasumar;");
		$repbuscarprimero=ejecutar($buscarprimero);
		$acutalmente=assoc_a($repbuscarprimero);
		$cuantosprimero=num_filas($repbuscarprimero);
		$eliddelarticulo=$nuevosaritulos['id_insumo'];
		$lacaantidaart=$nuevosaritulos['cantidad'];
		if ($cuantosprimero<=0){
      $cantidactual=0;
      $paractualiza=$lacaantidaart;
			$nuevoproducto=("insert into tbl_insumos_almacen(id_insumo,cantidad,fecha_hora_creado,id_dependencia) values($eliddelarticulo,$lacaantidaart,'$fecha',$dependasumar);");
			$repnuevoproducto=ejecutar($nuevoproducto);
			}else{
				  $cantidactual=$acutalmente['cantidad'];
				  $paractualiza=$cantidactual+$nuevosaritulos['cantidad'];
				  $actulizoelpedido=("update tbl_insumos_almacen set cantidad=$paractualiza where tbl_insumos_almacen.id_insumo=$eliddelarticulo and tbl_insumos_almacen.id_dependencia=$dependasumar;");
				  $repactulizoelpedido=ejecutar($actulizoelpedido);
				}

        ///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
        $Movimiento='PEDIDO';///que tipo de movimiento se esta haciendo
        $TipoMovimiento=1;//Es una entrada(1) o una salida(2)
        $Descripcion="ENTRADA SEGUN PEDIDO:$idordepedido";//como se puede comprovar el movimiento
        $idprovee=1495; ///CLINISALUD C.A.
        $MotoUnidad=0;
        $RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
                          VALUES ('$eliddelarticulo','$dependasumar','$idprovee','$Movimiento','$TipoMovimiento','$MotoUnidad','$lacaantidaart','$cantidactual','$paractualiza','$elid','$Descripcion');");
        $RegistroMov=ejecutar($RegistroMovimientossql);
       //fin REGISTRAR MOVIMIENTO DE INVENTARIO

	}
	//actualizamos la fecha y el estado del pedido
	$fechaestado=("update tbl_ordenes_pedidos set fecha_recibido='$fecha',estatus=3 where id_orden_pedido=$cambiarestado;");
	$repfechaestado=ejecutar($fechaestado);
	//actualizamos el log
	 $mensaje="El usuario $elus ha aceptado exitosamente el pedido No. $cambiarestado";
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog);
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">El pedido ha sido recibido satisfactoriamente !!</td>
         <td class=\"titulo_seccion\"><label title=\"Salir del proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ira()\" >Salir</label></td>
     </tr>
</table>";
?>
