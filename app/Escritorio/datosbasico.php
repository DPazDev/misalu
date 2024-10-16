<?php
include ("../../lib/jfunciones.php");
sesion();
?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Modificar datos basicos del cliente</td>  
     </tr>
     <tr>
	 <td class="tdtitulos">* C&eacute;dula</td>
         <td class="tdcampos"><input class="campos" type="text" name="cedulclien" id="cedulclien"   ></td> 
         <td class="tdcampos" title="Buscar cliente"><label class="boton" style="cursor:pointer" onclick="modifdaclien()" >Buscar</label>
     </tr>     
   </table>  
   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
    <div id='nuevosdatclien'></div>