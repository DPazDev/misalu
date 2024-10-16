<?php
include ("../../lib/jfunciones.php");
sesion();
$idorden=$_POST['elidorden'];
$cometpedido=("select tbl_ordenes_donativos.comentarios,tbl_ordenes_donativos.id_dependencia,tbl_ordenes_donativos.estatus from tbl_ordenes_donativos where id_orden_donativo=$idorden");
$repcometpedido=ejecutar($cometpedido);
$datacoment=assoc_a($repcometpedido);
$ladepen=$datacoment['id_dependencia'];
$elestado=$datacoment['estatus'];
$buscarpedido=("select tbl_insumos.insumo,tbl_insumos_almacen.cantidad as existencia,
                tbl_insumos_ordenes_donativos.cantidad,tbl_insumos_ordenes_donativos.id_insumo
from tbl_insumos,tbl_insumos_ordenes_donativos,tbl_insumos_almacen where
tbl_insumos_ordenes_donativos.id_insumo=tbl_insumos.id_insumo and
tbl_insumos_ordenes_donativos.id_insumo=tbl_insumos_almacen.id_insumo and
tbl_insumos_almacen.id_dependencia=$ladepen and tbl_insumos_ordenes_donativos.id_orden_donativo=$idorden order by tbl_insumos.insumo;");
$repbuspedido=ejecutar($buscarpedido);
?>
 <? if ($elestado==1){
	    $mensajencabe="Cantidad pedida";
	 }else {
		$mensajencabe="Cantidad despachada";
		} 	
	?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Art&iacute;culo.</th>
                 <th class="tdtitulos"><?echo $mensajencabe;?></th> 
		 <th class="tdtitulos">Existencia.</th> 				 
            </tr>
			 <?php 
				$i=1; 
			    while($articulos=asignar_a($repbuspedido,NULL,PGSQL_ASSOC)){
				$caja="caja".$i;
				$caja2="cajas".$i;
				$caja3="cajaac".$i; 
				$existencia=$articulos[existencia];
				?>
			    <tr>
				   <td class="tdcampos"><?echo $articulos['insumo'];?></td>
				   <td class="tdcampos"><input type=text id='<?echo $caja?>'  value='<?echo $articulos[cantidad]?>' size="4"></td>      
				    <input type=hidden id='<?echo $caja2?>' value='<?echo $articulos[id_insumo]?>'>   
				   <td class="tdcampos"><b><input type=text id='<?echo $caja3?>'  value='<?echo $existencia;?>' size="4" disabled=yes style=color:#1B1112></b></td></td>      
				</tr>
			<?
			$i++;
			}?>	
			<input type=hidden id='totalcaja' value='<?echo $i-1?>'>
			<input type=hidden id='elidpedido' value='<?echo $idorden?>'>
</table>	
<hr>
<div id='modifpedido'></div>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
    <td class="tdtitulos" colspan="1">Comentario del donativo:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=3 id="comentpedi" class="campos" disabled="yes"=><?echo $datacoment['comentarios']?></TEXTAREA></td>
</tr> 
	
</table>

<hr>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <? if ($elestado==1){?>
   <td><label title="Procesar pedido" class="boton" style="cursor:pointer" onclick="Verdonafinal()" >Procesar</label>
   </td>
   <?}?>   
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="ira()" >Salir</label>
   </td>   
</tr>
<br>   
