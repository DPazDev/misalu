<?
include ("../../lib/jfunciones.php");
sesion();
$idpedido=$_POST['elidpedido'];
$bloquears=$_POST['bloquear'];
$elid=$_SESSION['id_usuario_'.empresa];
if($bloquears!=1){
	
$buscaridpeentre=("select tbl_ordenes_entregas.id_orden_entrega from tbl_ordenes_entregas,tbl_ordenes_pedidos 
where tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
tbl_ordenes_pedidos.id_orden_pedido=$idpedido");
$repbuscaridentre=ejecutar($buscaridpeentre);
$dataentrega=assoc_a($repbuscaridentre);
$identrega=$dataentrega['id_orden_entrega'];
}else{
	$buscaridpeentre=("select tbl_ordenes_entregas.id_orden_entrega,tbl_ordenes_pedidos.id_orden_pedido from 
tbl_ordenes_entregas,tbl_ordenes_pedidos where 
tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
tbl_ordenes_entregas.id_orden_entrega=$idpedido");
$repbuscaridentre=ejecutar($buscaridpeentre);
$dataentrega=assoc_a($repbuscaridentre);
$identrega=$dataentrega['id_orden_entrega'];
}
$buscarpedido=("select
          tbl_insumos_ordenes_entregas.cantidad,
          tbl_laboratorios.laboratorio,
          tbl_insumos.insumo  
       from 
          tbl_insumos_ordenes_entregas,tbl_laboratorios,tbl_insumos
       where
           tbl_insumos_ordenes_entregas.id_orden_entrega=$identrega and
           tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and
           tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio
           order by tbl_insumos.insumo;");
$repbuspedido=ejecutar($buscarpedido);
$cometpedido=("select tbl_ordenes_entregas.comentario,tbl_ordenes_entregas.id_orden_pedido from 
tbl_ordenes_entregas where 
id_orden_entrega=$identrega");
$repcometpedido=ejecutar($cometpedido);
$datacoment=assoc_a($repcometpedido);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Art&iacute;culo.</th>
                 <th class="tdtitulos">Laboratorio.</th>
				 <th class="tdtitulos">Cantidad despachada.</th> 
							 
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
		<td class="tdcampos"><TEXTAREA COLS=65 ROWS=3 class="campos" readonly><?echo $datacoment[comentario];?></TEXTAREA></td>
	</tr>
</table>
<hr>
<?if ($bloquears!=1){?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="esconderpedido" title="Aceptar pedido despachado" class="boton" style="cursor:pointer" onclick="Apedido(<?echo $idpedido?>)" >Aceptar Pedido</label>
   </td>
<? if($elid==13){?>  
   <td><label title="Generar archivo excel" class="boton" style="cursor:pointer" onclick="Excelp(<?echo $idpedido?>)" >Excel</label>
   </td>
<?}   ?>
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="ira()" >Salir</label>
   </td>   
</tr>
</table>
<?}?>
<br>   
<img alt="spinner" id="spinner1" src="../public/images/esperar.gif" style="display:none;" />
<div id='excelpedido'></div>