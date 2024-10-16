<div id="lOrdenes"> <span class="tdtitulos"></span>
<?php

//control de pedido: Procesar pedido
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
$sqlOrdenes=("
SELECT tbl_ordenes_compras.id_orden_compra, 
  proveedores.id_clinica_proveedor, 
  clinicas_proveedores.nombre as proveedor, 
  tbl_ordenes_compras.fecha_compra, 
  tbl_ordenes_compras.id_admin,
  tbl_ordenes_compras.no_factura, 
  tbl_ordenes_compras.no_control_fact,
  (admin.nombres ||' '|| admin.apellidos) as usuario
FROM 
  public.tbl_ordenes_compras, 
  public.proveedores, 
  public.clinicas_proveedores,admin
WHERE 
  tbl_ordenes_compras.id_admin = admin.id_admin  AND tbl_ordenes_compras.id_proveedor_insumo = proveedores.id_proveedor AND
  clinicas_proveedores.id_clinica_proveedor = proveedores.id_clinica_proveedor AND tbl_ordenes_compras.orden_compra='1';
	");
$cosOrdenes=ejecutar($sqlOrdenes);
$numOrd=num_filas($cosOrdenes);
if($numOrd<=0){
	
?>

 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion"> No Hay Ordenes de Compras relacionadas </td>
     </tr>
</table>
<?php }else{?>
     <table class="tabla_cabecera5"  id='colortable' cellpadding=0 cellspacing=0> 
             <tr>
                 <th class="tdtitulos">Num. Ord</th>
                 <th class="tdtitulos">Proveedor</th>  
                 <th class="tdtitulos">Fecha de compra</th>
                 <th class="tdtitulos">Realizado</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>
              </tr>   
       <?php
              while($ord=asignar_a($cosOrdenes,NULL,PGSQL_ASSOC)){
        	
              	?>
              
              
                <tr>
                    <td class="tdcampos"><?php echo $ord['id_orden_compra'];?></td>
                    <td class="tdcampos"><?php echo $ord['proveedor']; ?></td>
                    <td class="tdcampos"><?php echo $ord['fecha_compra'];?></td>
                    <td class="tdcampos"><?php echo $ord['usuario'];?></td>
                    <td class="tdcampos">
                        <a href="views05/ordenes_compras1.php?numfactu=<?php echo $ord[id_orden_compra]?>" title="Orden de Compra" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;">Ver Orden Compra</a>
                      <a href="#" title="Convercion a Factura" class="boton" onclick="convercion_orden_datos('<?php echo $ord['id_orden_compra'];?>')">Convertir a Factura</a> 
                    </td>
               </tr>
       <?php }?>
    </table>   
<?php }?>

</div>
<div id="Orden"></div>