<?
include ("../../lib/jfunciones.php");
sesion();
$idpedido=$_POST['elidpedido'];
$buscarpedido=("select tbl_insumos_ordenes_pedidos.id_orden_pedido,tbl_ordenes_pedidos.id_dependencia,tbl_insumos_ordenes_pedidos.id_insumo,tbl_insumos_ordenes_pedidos.cantidad,tbl_insumos.insumo,tbl_laboratorios.laboratorio  from 
tbl_insumos_ordenes_pedidos,tbl_ordenes_pedidos,tbl_insumos,tbl_laboratorios where
tbl_ordenes_pedidos.id_orden_pedido=tbl_insumos_ordenes_pedidos.id_orden_pedido and
tbl_ordenes_pedidos.id_orden_pedido=$idpedido and tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio order by tbl_insumos.insumo;");
$repbuspedido=ejecutar($buscarpedido);
$cometpedido=("select tbl_ordenes_pedidos.comentarios from tbl_ordenes_pedidos where id_orden_pedido=$idpedido");
$repcometpedido=ejecutar($cometpedido);
$datacoment=assoc_a($repcometpedido);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Art&iacute;culo.</th>
                 <th class="tdtitulos">Laboratorio.</th>
				 <th class="tdtitulos">Cantidad pedida.</th> 
				 <th class="tdtitulos">Existencia.</th> 				 
            </tr>
			 <?php 
				$i=1; 
			    while($articulos=asignar_a($repbuspedido,NULL,PGSQL_ASSOC)){
				$caja="caja".$i;
				$caja2="cajas".$i;
				$caja3="cajaac".$i; 
				$elartpabus=$articulos['id_insumo'];
				$ladearticul=$articulos['id_dependencia'];
				$buscarcanti=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$elartpabus and tbl_insumos_almacen.id_dependencia=$ladearticul;");
				$repbuscarcanti=ejecutar($buscarcanti);
				$datadebusque=assoc_a($repbuscarcanti);
				$existencia=$datadebusque['cantidad'];
				?>
			    <tr>
				   <td class="tdcampos"><?echo $articulos['insumo'];?></td>
				   <td class="tdcampos"><?echo $articulos['laboratorio'];?></td> 
				   
				   <td class="tdcampos"><input type=text id='<?echo $caja?>'  value='<?echo $articulos[cantidad]?>' size="4"></td>      
				    <input type=hidden id='<?echo $caja2?>' value='<?echo $articulos[id_insumo]?>'>   
				   <td class="tdcampos"><b><input type=text id='<?echo $caja3?>'  value='<?echo $existencia;?>' size="4" disabled=yes style=color:#1B1112></b></td></td>      
				</tr>
			<?
			$i++;
			}?>	
			<input type=hidden id='totalcaja' value='<?echo $i-1?>'>
			<input type=hidden id='elidpedido' value='<?echo $idpedido?>'>
</table>	
<hr>
<div id='modifpedido'></div>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
    <td class="tdtitulos" colspan="1">Comentario de pedido:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=7 id="comentpedi" class="campos" disabled="yes"=><?echo $datacoment['comentarios']?></TEXTAREA></td>
</tr>
<tr>
			   <td colspan=6><label style="color: #ff0000"> Nota: Si el pedido tiene alg&uacute;n comentario, cargarlo al finalizar el despacho.</label> </td>  
			</tr>
			<tr>
			    <td class="tdcampos">Comentario de despacho:</td>  
				<td class="tdcampos"><TEXTAREA COLS=65 ROWS=3 id="comentdespacho" class="campos"></TEXTAREA></td>                <td class="tdcampos"></td>  
				<td class="tdcampos"></td>
			</tr>
</table>

<hr>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="despachopedido" title="Procesar pedido" class="boton" style="cursor:pointer" onclick="Vercandespacho()" >Procesar</label>
   </td>
      
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="Psalir" >Salir</label>
   </td>   
</tr>
<br>   
