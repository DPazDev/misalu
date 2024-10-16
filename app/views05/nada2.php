<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$idordepedido=$_POST['elnpedido'];
/*
//primero tengo q saber a  que dependencia se le sumaran los articulos del pedido
$comoeselpedido=("select tbl_ordenes_entregas.id_orden_entrega,
                  tbl_ordenes_pedidos.id_dependencia,
                  tbl_ordenes_pedidos.id_dependencia_saliente
                from
                  tbl_ordenes_pedidos,tbl_ordenes_entregas
                where
                 tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
                 tbl_ordenes_pedidos.id_orden_pedido=$idordepedido;");
$repcomoeselpedido=ejecutar($comoeselpedido);
$pedidodata=assoc_a($repcomoeselpedido);
$dependasumar=$pedidodata['id_dependencia'];
$depenbuscar=$pedidodata['id_dependencia_saliente'];
$cambiarestado=$pedidodata['id_orden_pedido'];
$elidentr=$pedidodata['id_orden_entrega'];
//buscamos todos los articulos del pedido
$buscartodo=("select tbl_insumos_ordenes_entregas.cantidad,tbl_insumos_ordenes_entregas.id_insumo
from  tbl_insumos_ordenes_entregas where tbl_insumos_ordenes_entregas.id_orden_entrega=$elidentr");
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
			echo "No esta el articulo";
			}else{
				  $cantidactual=$acutalmente['cantidad'];
				  $paractualiza=$cantidactual+$nuevosaritulos['cantidad'];
				  $actulizoelpedido=("update tbl_insumos_almacen set cantidad=$paractualiza where tbl_insumos_almacen.id_insumo=$eliddelarticulo and tbl_insumos_almacen.id_dependencia=$dependasumar;");
				  $repactulizoelpedido=ejecutar($actulizoelpedido);
				}
	}
*/
	//actualizamos la fecha y el estado del pedido
       $updateproceso=("update tbl_ordenes_pedidos set estatus=4 where tbl_ordenes_pedidos.id_orden_pedido=$idordepedido");
       $repupdatpro=ejecutar($updateproceso);
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=3 class=\"titulo_seccion\">El pedido ha sido anulado satisfactoriamente !!</td>
         <td class=\"titulo_seccion\"><label title=\"volver a pedidos\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ver_pedidepen()\" >Ver Pedido Dependencia</label></td>
         <td class=\"titulo_seccion\"><label title=\"Salir del proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ira()\" >Salir</label></td>
     </tr>
</table>";
?>
