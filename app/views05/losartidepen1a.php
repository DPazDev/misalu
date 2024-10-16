<?
include ("../../lib/jfunciones.php");
sesion();
$idpedido=$_POST['elidpedido'];
$buscarpedido=("select
          tbl_insumos_ordenes_pedidos.cantidad,
          tbl_laboratorios.laboratorio,
          tbl_insumos.insumo  
       from 
          tbl_insumos_ordenes_pedidos,tbl_laboratorios,tbl_insumos
       where
           tbl_insumos_ordenes_pedidos.id_orden_pedido=$idpedido and
           tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and
           tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio
           order by tbl_insumos.insumo;");
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
							 
            </tr>
			 <?php 				
			    while($articulos=asignar_a($repbuspedido,NULL,PGSQL_ASSOC)){				
				?>
			    <tr>
				   <td class="tdcampos"><?echo $articulos['insumo'];?></td>
				   <td class="tdcampos"><?echo $articulos['laboratorio'];?></td> 				   
				   <td class="tdcampos"><?echo $articulos[cantidad];?></td>      				    
				</tr>
			<?}?>				
			<input type=hidden id='elidpedido' value='<?echo $idpedido?>'>
</table>	
<hr>
<div id='modifpedido'></div>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
   <tr>
    	<td class="tdcampos">Comentario de despacho:</td>  
		<td class="tdcampos"><TEXTAREA COLS=65 ROWS=3 class="campos" readonly><?echo $datacoment[comentarios];?></TEXTAREA></td>
	</tr>
</table>
<hr>

<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="ira()" >Salir</label>
   </td>   
</tr>
</table>
<br>   
