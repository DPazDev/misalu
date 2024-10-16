<?php
  include ("../../lib/jfunciones.php");
  sesion();
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Registro de clientes individual</td>  
     </tr>
<tr>
	 <td class="tdtitulos">* C&eacute;dula o Cotizaci&oacute;n</td>
         <td class="tdcampos"><input class="campos" type="text"  id="ceducoti"/></td> 
         <td class="tdcampos" title="Buscar cotizaci&oacute;n o Cliente"><label class="boton" style="cursor:pointer" onclick="coticedula()" >Buscar</label>
	 <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>

 </tr>
  </table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
<div id="regicliindi"></div>