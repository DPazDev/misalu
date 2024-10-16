<?
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$perprove=("select proveedores.id_proveedor,personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,
       s_p_proveedores.direccion_prov 
  from
   proveedores,personas_proveedores,s_p_proveedores
  where
     personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
     s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor 
  order by personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov;");
$repperprove=ejecutar($perprove);
$cliniprove=("select proveedores.id_proveedor,clinicas_proveedores.nombre,clinicas_proveedores.direccion
from 
  proveedores,clinicas_proveedores
where
  clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
  clinicas_proveedores.activar=1 order by
  clinicas_proveedores.nombre;");
$repcliniprove=ejecutar($cliniprove);  
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Historial de factura seg&uacute;n proveedor</td>
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">Buscar por proveedor cl&iacute;nica:</td>
        <td class="tdcampos"><select id="procli" class="campos"  style="width: 230px;" >
           <option value=""></option>
           <?php
              while($prclinica=asignar_a($repcliniprove,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $prclinica[id_proveedor]?>">
                     <?php echo "$prclinica[nombre]"?>
               </option>
             <?}?>
          </select>
      </td>
     </tr>
     <tr>
	   <td class="tdtitulos">&oacute;</td>
     </tr> 
     <tr>
        <td class="tdtitulos">Buscar por proveedor persona:</td>
        <td class="tdcampos"><select id="proper" class="campos"  style="width: 230px;" >
           <option value=""></option>
           <?php
              while($prpers=asignar_a($repperprove,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $prpers[id_proveedor]?>">
                     <?php echo "$prpers[nombres_prov] $prpers[apellidos_prov] -- $prpers[direccion_prov]"?>
               </option>
             <?}?>
          </select>
      </td>
     </tr>
     <tr>
	   <td><label title="Buscar facturas"  class="boton" style="cursor:pointer" onclick="busfacturprove()" >Buscar</label></td>  
     </tr> 
 </table>   
 <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='lasfacturas'></div>
