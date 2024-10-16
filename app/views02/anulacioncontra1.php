<?php
  include ("../../lib/jfunciones.php");
  sesion();
?>
 <form method="get" onsubmit="return false;" name="regclien" id="regclien">
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Anulaci&oacute;n de Contrato</td>  
     </tr>
     <tr>
	 <td class="tdtitulos">* C&eacute;dula del titular</td>
         <td class="tdcampos"><input class="campos" type="text" name="cedulclien" id="cedulclien"   ></td> 
         <td class="tdcampos" title="Registrar cliente al sistema"><label class="boton" style="cursor:pointer" onclick="contratoanular()" >Buscar</label>
	 <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>

 </tr>
  </table>
  <div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
  </div>
    <div id="datacontrato"></div>
 </form>

