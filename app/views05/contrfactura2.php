<?
include ("../../lib/jfunciones.php");
sesion();
$idorden=$_REQUEST['numfactu'];
$actualizafac=("select tbl_ordenes_compras.id_orden_compra,tbl_ordenes_compras.no_factura,
tbl_ordenes_compras.no_control_fact,tbl_ordenes_compras.fecha_emi_factura,tbl_ordenes_compras.id_proveedor_insumo 
from
 tbl_ordenes_compras
where
  tbl_ordenes_compras.id_orden_compra=$idorden;");
$repactualfac=ejecutar($actualizafac);  
$datosf=assoc_a($repactualfac);
$elprove=$datosf['id_proveedor_insumo'];
$queprove=("select proveedores.id_clinica_proveedor,proveedores.id_s_p_proveedor from proveedores where 
                       proveedores.id_proveedor=$elprove;");
$repqprove=ejecutar($queprove);
$datqprove=assoc_a($repqprove);
if($datqprove['id_clinica_proveedor']>0){
  $buscelprove=("select clinicas_proveedores.nombre from clinicas_proveedores where 
                            clinicas_proveedores.id_clinica_proveedor=$datqprove[id_clinica_proveedor]");
   $rebusclprove=ejecutar($buscelprove);   
   $datdelpro=assoc_a($rebusclprove);
   $nomprclin=$datdelpro['nombre'];
   $busctodosclin=("select proveedores.id_proveedor,clinicas_proveedores.nombre from proveedores,clinicas_proveedores 
                                     where
                                clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor order by
                                clinicas_proveedores.nombre;");
   $rebustodosclin=ejecutar($busctodosclin);                                
}else{
    $persoprove=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,s_p_proveedores.direccion_prov from
personas_proveedores,s_p_proveedores
 where
 s_p_proveedores.id_s_p_proveedor=$datqprove[id_s_p_proveedor] and
 s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor");
   $reppersoprove=ejecutar($persoprove);
   $datperprove=assoc_a($reppersoprove);
   $nomprclin="$datperprove[nombres_prov] $datperprove[apellidos_prov] -- $datperprove[direccion_prov]";
   $demperprov=("select proveedores.id_proveedor,personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,
       s_p_proveedores.direccion_prov 
  from
   proveedores,personas_proveedores,s_p_proveedores
  where
     personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
     s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor 
  order by personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov;");
    $rebustodosclin=ejecutar($demperprov);
    $personp=1;
    }
?>
<table   cellpadding=0 cellspacing=0>
 <input type='hidden' id='elcontf' value='<?echo $idorden?>'>
 <tr> 
 <td colspan="1"><label style='color:#071918'>N&uacute;mero de factura:</label></td>
 <td colspan="1"><input type='text' id='nuevonumfac' size='11' value='<?echo $datosf[no_factura]?>'></td>&nbsp&nbsp&nbsp
</tr>
<tr> 
 <td colspan="1"><label style='color:#071918'>N&uacute;mero de control:</label></td>
 <td colspan="1"><input type='text' id='controlfac' size='11' value='<?echo $datosf[no_control_fact]?>'></td>&nbsp&nbsp&nbsp
</tr>
 <tr>
 <td colspan="1"><label style='color:#071918'>Fecha emisi&oacute;n factura: (A&ntilde;o-Mes-D&iacute;a)</label></td>
 <td colspan="1"><input type='text' id='nuefechafac' size='11' value='<?echo $datosf[fecha_emi_factura]?>'></td> 
 </tr>
 <tr>
 <td colspan="1"><label style='color:#071918'>Proveedor</label></td>
 <td colspan="1">
        <select id="proveedor"  style="width: 230px;" >
           <option value="<?echo $elprove?>"><?echo $nomprclin?></option>
           <?php
              while($gruproveedor=asignar_a($rebustodosclin,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $gruproveedor[id_proveedor]?>">
                     <?php 
                          if($personp<>1)
                            echo "$gruproveedor[nombre]";
                          else
                          echo "$datperprove[nombres_prov] $datperprove[apellidos_prov] -- $datperprove[direccion_prov]";
                    ?>
               </option>
             <?}?>
          </select>
 </td> 
 </tr>
 <tr>
     <td class="tdcampos"> <label title="Procesar nuevos cambios" class="boton" style="cursor:pointer" onclick="ProceCFac2(); return false;" >Procesar</label></td>
 </tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='finfacturas'></div>
