<?php
include ("../../lib/jfunciones.php");
sesion();
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Exclusi&oacute;n individual</td>  
     </tr>
     <tr>
	     <td class="tdtitulos">* C&eacute;dula</td>
         <td class="tdcampos"><input class="campos" type="text" name="cedulclien" id="cedulclien"   ></td> 
         <td title="Buscar cliente"><label id="titularboton" class="boton" style="cursor:pointer" onclick="busclienexclu()" >Buscar</label>
         <td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
   </tr>
   </table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='exclusiones'></div>