<?php
  include ("../../lib/jfunciones.php");
  sesion();
?>
 <form method="get" onsubmit="return false;" name="prover" id="prover">
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Registrar Proveedor Persona</td>  
     </tr>
     <tr>
	 <td class="tdtitulos">* C&eacute;dula &oacute; Nombre</td>
         <td class="tdcampos"><input class="campos" type="text" name="cedulap" id="cedulap"   ></td> 
         <td class="tdcampos" title="Buscar Proveedor"><label class="boton" style="cursor:pointer" onclick="buscarprovee()" >Buscar</label>
	 <label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>

 </tr>
  </table>
    <div id="inprove"></div>
 </form>

