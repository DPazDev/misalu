<?php
include ("../../lib/jfunciones.php");
sesion();
$proveedor=$_REQUEST['elidprovee'];
$buscarelpro=("select proveedores.id_clinica_proveedor,proveedores.id_s_p_proveedor from proveedores where id_proveedor=$proveedor;");
$repbuscaelpro=ejecutar($buscarelpro);
$dataelpro=assoc_a($repbuscaelpro);
$procli=$dataelpro['id_clinica_proveedor'];
$proper=$dataelpro['id_s_p_proveedor'];
  if($procli>0){
        $daprovee=("select clinicas_proveedores.nombre from clinicas_proveedores where id_clinica_proveedor=$procli;");
        $repdatprovee=ejecutar($daprovee);
        $vernombre=assoc_a($repdatprovee);
        $datnoprov="$vernombre[nombre]";
      }else{
            $daprovee=("select personas_proveedores.nombres_prov,apellidos_prov from
personas_proveedores,s_p_proveedores,proveedores where
proveedores.id_s_p_proveedor=$proper and
proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor;");
            $repdatprovee=ejecutar($daprovee);
            $vernombre=assoc_a($repdatprovee);
            $datnoprov="$vernombre[nombres_prov] $vernombre[apellidos_prov]";
          }
  //Buscamos si tiene facturas
   $busfactura=("select tbl_ordenes_compras.id_orden_compra,tbl_ordenes_compras.no_factura,tbl_ordenes_compras.no_control_fact,
                           tbl_ordenes_compras.fecha_emi_factura 
                       from 
                              tbl_ordenes_compras
                       where
                            tbl_ordenes_compras.id_proveedor_insumo=$proveedor;");
   $repbusfactu=ejecutar($busfactura);              
   $cuantfactu=num_filas($repbusfactu);
   if($cuantfactu==0){?>
         <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">El proveedor <?echo $datnoprov?> no pose facturas</td>  
            </tr>
          </table>
   <?}else{?>
          <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">Control de facturas del proveedor <?echo $datnoprov?></td>  
            </tr>
          </table>
           <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">Fecha factura.</th>
                 <th class="tdtitulos">No. factura.</th>
        	     <th class="tdtitulos">No. control.</th>      
                 <th class="tdtitulos">Opci&oacute;n</th>    
            </tr>
            <?php 
			    while($compras=asignar_a($repbusfactu,NULL,PGSQL_ASSOC)){
            ?>
			    <tr>
				   <td class="tdcampos"><?echo $compras['fecha_emi_factura'];?></td>
                   <td class="tdcampos"><?echo $compras['no_factura'];?></td>
                   <td class="tdcampos"><?echo $compras['no_control_fact'];?></td>
                   <td class="tdcampos"><? echo"<a href=\"views06/lafacturaprov.php?numfactu=$compras[id_orden_compra]\" title=\"Ver factura\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;\">Ver Factura</a>
                                                                       <a href=\"views06/notafacturaprov.php?numfactu=$compras[id_orden_compra]\" title=\"Generar Nota de Credito\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;\">Nota de Credito</a>";?>                                                         
                                             </td>
                 </tr>  
      <? }?>
        </table>
    <?}?>    