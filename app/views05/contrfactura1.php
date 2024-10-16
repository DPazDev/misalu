<?
include ("../../lib/jfunciones.php");
sesion();
$lproclin=$_REQUEST['provclin'];
$lproper=$_REQUEST['propers'];
if(!empty($lproclin))
 $provedfin=$lproclin;
else
 $provedfin=$lproper;
$busfacprov=("select tbl_ordenes_compras.id_orden_compra,tbl_ordenes_compras.no_factura,
tbl_ordenes_compras.fecha_compra,tbl_ordenes_compras.no_control_fact,tbl_ordenes_compras.fecha_emi_factura
from
 tbl_ordenes_compras
where
  tbl_ordenes_compras.id_proveedor_insumo=$provedfin
order by tbl_ordenes_compras.fecha_compra desc;");
$repbusprov=ejecutar($busfacprov);
$cuantfactu=num_filas($repbusprov);
if($cuantfactu<=0){
?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Historial de factura seg&uacute;n proveedor</td>
     </tr>
</table>
<?}else{?>
     <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0> 
             <tr>
                 <th class="tdtitulos">No factura.</th>
                 <th class="tdtitulos">Fecha compra.</th>  
                 <th class="tdtitulos">No Control.</th>
                 <th class="tdtitulos">Fecha emisi&oacute;n factura.</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>
              </tr>   
       <?php
              while($losdatfact=asignar_a($repbusprov,NULL,PGSQL_ASSOC)){?>
                <tr>
                    <td class="tdcampos"><?echo $losdatfact['no_factura']?></td>
                    <td class="tdcampos"><?echo $losdatfact['fecha_compra']?></td>
                    <td class="tdcampos"><?echo $losdatfact['no_control_fact']?></td>
                    <td class="tdcampos"><?echo $losdatfact['fecha_emi_factura']?></td>
                    <td class="tdcampos">
                        <a href="views06/lafacturaprov.php?numfactu=<?echo $losdatfact[id_orden_compra]?>" title="Ver factura" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;">Ver Factura</a>
                        <a href="views05/contrfactura2.php?numfactu=<?echo $losdatfact[id_orden_compra]?>" title="Modificar factura" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:600,height:200, overlayClose: false}); return false;">Modificar</a>
                    </td>
               </tr>
       <?}?>
    </table>   
<?}?>