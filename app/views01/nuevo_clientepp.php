<?php
  include ("../../lib/jfunciones.php");
  sesion();
?>
 <form method="get" onsubmit="return false;" name="regclien" id="regclien">
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Registrar Cliente</td>  
     </tr>
     <tr>
	 <td class="tdtitulos">* C&eacute;dula</td>
         <td class="tdcampos"><input class="campos" type="text" name="cedulclien" id="cedulclien"   ></td> 
         <td class="tdcampos" title="Registrar cliente al sistema"><label class="boton" style="cursor:pointer" onclick="cliennuevo()" >Registrar</label>
	 <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>

 </tr>
  </table>
	<img alt="spinner" id="spinner" src="../public/images/spinner.gif" style="display:none;" />  
    <div id="clientenuevo"></div>
	<div id="dataclienteTB"></div>
	<div id="clienTcomoB"></div>
	<div id="mensajeTB"></div>
 </form>

