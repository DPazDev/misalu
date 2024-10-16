<?
include ("../../lib/jfunciones.php");
sesion();
$idpedido=$_POST['elidpedido'];
$depenid=$_POST['depend'];
$estpedido=$_SESSION['opcionpe'];
$quiespro=("select tbl_insumos_ordenes_compras.monto_unidad,tbl_insumos_ordenes_compras.cantidad, tbl_insumos_ordenes_compras.iva, tbl_insumos_ordenes_compras.aumento,tbl_insumos.insumo,tbl_laboratorios.laboratorio from tbl_insumos_ordenes_compras,tbl_insumos,tbl_laboratorios where 
tbl_insumos_ordenes_compras.id_orden_compra=$idpedido and
tbl_insumos_ordenes_compras.id_insumo=tbl_insumos.id_insumo and
tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio order by tbl_insumos.insumo;");
$requiespro=ejecutar($quiespro);
$datafactura=("select tbl_ordenes_compras.no_factura,clinicas_proveedores.nombre from tbl_ordenes_compras,clinicas_proveedores,proveedores where tbl_ordenes_compras.id_orden_compra=$idpedido and tbl_ordenes_compras.id_proveedor_insumo=proveedores.id_proveedor and
proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor;");
$repdatafactura=ejecutar($datafactura);
$darafactura=assoc_a($repdatafactura);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Art&iacute;culos pertenecientes a la factura
No. <?echo $darafactura['no_factura']?> del proveedor <?echo $darafactura[nombre]?> </td>
     </tr>
</table>	 
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Art&iacute;culo.</th>
				<th class="tdtitulos">Laboratorio.</th> 
                 <th class="tdtitulos">Cantidad.</th> 
				 <th class="tdtitulos">Monto unidad.</th> 				 
                 <th class="tdtitulos">IVA.</th>  
                 <th class="tdtitulos">% Aumento.</th>  
			</tr>
			 <?php 
				while($articulos=asignar_a($requiespro,NULL,PGSQL_ASSOC)){
				$coniva=$articulos['iva'];
				$conaum=$articulos['aumento'];
				if($coniva==1)
				  $m1="Si";
				else
				  $m1="No";
				if($conaum==1)  
				  $m2="Si";
				else
				  $m2="No";
				?>
			    <tr>
				   <td class="tdcampos"><?echo $articulos['insumo'];?></td>
				   <td class="tdcampos"><?echo $articulos['laboratorio'];?></td> 
				   <td class="tdcampos"><?echo $articulos['cantidad'];?></td>
				   <td class="tdcampos"><?echo $articulos['monto_unidad'];?></td> 
				   <td class="tdcampos"><?echo $m1;?></td> 
				  <td class="tdcampos"><?echo $m2;?></td>    
				</tr>
			<?
			
			}?>	
</table>	
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=5 class="titulo_seccion">
		 <?php
			$url="'views05/reportpediprov.php?idordepe=$idpedido'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
		</td>  
         <td class="titulo_seccion"><label title="Regresar al Modulo"class="boton" style="cursor:pointer" onclick="buspPro2('<?echo $estpedido?>','<?echo $depenid?>')" >Regresar</label></td>
	  </tr>  
</table>
