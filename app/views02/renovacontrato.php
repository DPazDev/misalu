<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">Renovaci&oacute;n de Contrato</td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
   <td colspan=1 class="tdtitulos">N&uacute;mero de Cedula:</td>
   <td colspan=3 class="tdcampos" ><input type='text' class="campos" id='ncedula' size='15'></td>
   <td title="Buscar titular"><label id="titularboton" class="boton" style="cursor:pointer" onclick="TitularRenova()" >Buscar</label>
 </tr>
</table> 
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='nuevocontrato'></div>

