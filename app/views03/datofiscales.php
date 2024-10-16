<?php
include ("../../lib/jfunciones.php");
sesion();
$proveceulda=$_REQUEST["cedulap"];
$querprov=("select personas_proveedores.id_persona_proveedor,personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov,
                               personas_proveedores.cedula_prov,personas_proveedores.nomcheque,personas_proveedores.rifcheque, 
                               personas_proveedores.direccioncheque,celular_prov,email_prov
                       from  personas_proveedores 
                       where cedula_prov='$proveceulda';");                       
$resprov=ejecutar($querprov);
$datdprov=assoc_a($resprov);
?>
<input type="hidden" id="idpepro" value="<? echo $datdprov[id_persona_proveedor] ?>">
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
      <tr>
       <td class="tdtitulos" colspan="1">C&eacute;dula:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="cedprop"  size="30" class="campos" value='<? echo $datdprov['cedula_prov']?>'></td>
    </tr>
     <tr>
        <td class="tdtitulos" colspan="1">Nombre:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="nompnuea" class="campos" size="30" value='<?echo $datdprov['nombres_prov']?>'></td>
       <td class="tdtitulos" colspan="1">Apellido:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="appepnua"  class="campos" size="30" value='<?echo $datdprov['apellidos_prov']?>'></td>
     </tr>
     
  <tr>
        <td class="tdtitulos" colspan="1">Correo Electr&oacute;nico::</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="correo" class="campos" size="30" value='<?echo $datdprov['email_prov']?>'></td>
       <td class="tdtitulos" colspan="1">Num. Tel&eacute;fono:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="telefono"  class="campos" size="30" value='<?echo $datdprov['celular_prov']?>'></td>
     </tr>     
     
     
     <tr>
        <td class="tdtitulos" colspan="1">Cheque a nombre de:</td>
        <td class="tdcampos"  colspan="1"><input type="text" id="cheqanom" class="campos" size="30" value='<?echo $datdprov['nomcheque']?>'></td>
       <td class="tdtitulos" colspan="1">RIF cheque:</td>
       <td class="tdcampos" colspan="1"><input type="text" id="cheqrifa"  class="campos" size="30" value='<?echo $datdprov['rifcheque']?>'></td>
     </tr>
     <tr>
    <td class="tdtitulos" colspan="1">Direcci&oacute;n chequ:</td>
    <td> <TEXTAREA COLS=25 ROWS=5 id="cheqdir" class="campos"><?echo $datdprov['direccioncheque']?></TEXTAREA> 
    </td>
  </tr>
   <tr>
     <td title="Guardar cambio"><label class="boton" style="cursor:pointer" onclick="gucprovefiscal()" >Guardar cambios</label></td>
     <td title="Salir"><label class="boton" style="cursor:pointer" onclick="salifiscal()" >Salir</label></td>
  </tr> 
 </table>   
 <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
 <div id='fiscalguardado'></div>