<?php

//control de pedido: Procesar pedido,Ver pedido,Anular pedido
include ("../../lib/jfunciones.php");
sesion();
$estpedido=$_POST['estapedido'];
$dependcia=$_POST['dependencia'];
if ($estpedido==1){
	 $mensaje1='Procesar pedido';
	}else{
		 if($estpedido==2){
			 $mensaje1='Ver pedido';
			 }else{
				  if($estpedido==3){
					  $mensaje1='Anular pedido';
					  }
				 }
		}
$buscpedidos=("select tbl_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.no_orden_pedido,tbl_ordenes_pedidos.fecha_pedido,
admin.nombres,admin.apellidos,clinicas_proveedores.nombre
from tbl_ordenes_pedidos,admin,tbl_admin_dependencias,clinicas_proveedores,proveedores
where
 tbl_ordenes_pedidos.id_admin=admin.id_admin and
 tbl_admin_dependencias.id_admin=tbl_ordenes_pedidos.id_admin and
 tbl_ordenes_pedidos.id_admin=admin.id_admin and
 tbl_admin_dependencias.id_dependencia=$dependcia and tbl_ordenes_pedidos.estatus=$estpedido and
 tbl_ordenes_pedidos.id_proveedor=proveedores.id_proveedor and
proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and
tbl_ordenes_pedidos.id_dependencia_saliente=0 and tbl_ordenes_pedidos.id_dependencia=0 order by tbl_ordenes_pedidos.fecha_pedido;");

$repbuspedido=ejecutar($buscpedidos);
$cuantopeido=num_filas($repbuspedido);
if ($cuantopeido<=0){
	echo"
      <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
<br>
     <tr>
         <td colspan=4 class=\"titulo_seccion\">No hay pedidos en estado $mensaje1</td>
     </tr>
</table>
     ";
	}else{
		if($estpedido==1){?>
<table class="tabla_cabecera5 colortable"  cellpadding=0 cellspacing=0>
             <tr>
                 <th class="tdtitulos">No. Pedido.</th>
                 <th class="tdtitulos">Realizado por.</th>
				 <th class="tdtitulos">Para el proveedor.</th>
				 <th class="tdtitulos">Fecha de pedido.</th>
				 <th class="tdtitulos">Opci&oacute;n.</th>
			</tr>
  <?php while($datpedido=asignar_a($repbuspedido,NULL,PGSQL_ASSOC)){
	  $numeamostrar=$datpedido['no_orden_pedido'];
	  $personamostrar="$datpedido[nombres] $datpedido[apellidos]";
	  $nomprovee= $datpedido['nombre'];
	  $fechamostrar=$datpedido['fecha_pedido'];
	  $opcamostrar="<label title='Procesar el pedido' class='boton' style='cursor:pointer' onclick='pediprovee($datpedido[id_orden_pedido] )' >Procesar</label><label title='Modificar el pedido' class='boton' style='cursor:pointer' onclick='ModfElPedpro($datpedido[id_orden_pedido] )' >Modificar</label>";
      echo"
             <tr>
                               <td class=\"tdcampos\">$numeamostrar</td>
							   <td class=\"tdcampos\">$personamostrar</td>
                               <td class=\"tdcampos\">$nomprovee</td>
							   <td class=\"tdcampos\">$fechamostrar</td>
						       <td  class=\"tdcampos\">$opcamostrar</td>
                         </tr>
              ";
  }?>

</table>
<?php }else{
	$buslascompras=("select tbl_ordenes_compras.id_orden_compra,tbl_ordenes_compras.no_factura,
tbl_ordenes_compras.fecha_compra,
clinicas_proveedores.nombre from tbl_ordenes_compras,clinicas_proveedores,proveedores where
tbl_ordenes_compras.id_proveedor_insumo=proveedores.id_proveedor and
proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor;");
   $repuesbulascompras=ejecutar($buslascompras);

	?>

<table class="tabla_cabecera5 colortable"  cellpadding=0 cellspacing=0>
             <tr>
                 <th class="tdtitulos">No. Factura.</th>
                 <th class="tdtitulos">Fecha cargada.</th>
				 <th class="tdtitulos">Proveedor.</th>
			</tr>
  <?php while($losarticarga=asignar_a($repuesbulascompras,NULL,PGSQL_ASSOC)){
	  $numeamostrar=$losarticarga['no_factura'];
	  $personamostrar=$losarticarga['fecha_compra'];
	  $nomprovee=$losarticarga['nombre'];
	  $opcamostrar="<label title='Ver pedidos cargados en dependencia' class='boton' style='cursor:pointer' onclick='pediprovee1($losarticarga[id_orden_compra] )' >Ver</label>";
      echo"
             <tr>
                               <td class=\"tdcampos\">$numeamostrar</td>
							   <td class=\"tdcampos\">$personamostrar</td>
                               <td class=\"tdcampos\">$nomprovee</td>
						       <td  class=\"tdcampos\">$opcamostrar</td>
                         </tr>
              ";
  }
}  ?>

</table>


<?}?>
<input type="hidden" id="ladepenc" value="<?echo $dependcia;?>">
