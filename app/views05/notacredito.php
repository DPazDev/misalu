<?php
include ("../../lib/jfunciones.php");
sesion();
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">Generar nota de credito a proveedor</td>  
            </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <td class="tdtitulos">Tipo de proveedor:</td>
        <td class="tdcampos">
                <input type="radio" name="proveedor1" value=1  onclick="VertipProv(this.value)" >Persona
                <input type="radio" name="proveedor1" value=2 onclick="VertipProv(this.value)" >Clinica
       </td>
       <td class="tdtitulos">El proveedor: </td>
        <td class="tdcampos"  colspan="1">
          <div id='elprovee'>
                    <select  class="campos" style="width: 210px;" disabled >
                              <option value="0"><?echo $tipopro?></option>
                    </select>
           </div>         
           <div id='elprovee1'></div>
        </td>
     </tr>
     <tr>
        <td class="tdtitulos"><label class="boton" style="cursor:pointer" onclick="ProveeNotC(); return false;" >Buscar Facturas</label></td>
    </tr>  
 </table>    
 <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='lasfacturasprovee'></div>