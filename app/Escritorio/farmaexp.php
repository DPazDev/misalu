<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Crear orden de m&eacute;dicamento interna / externa</td>  
     </tr>
	  <br>
	<tr>  
	      <td  title="Crear orden de m&eacute;dicamento interna"><label class="boton" style="cursor:pointer" onclick="crearodmedinter('interna')" >Orden de m&eacute;dicamento interna</label></td> 
	      <td  title="Crear orden de m&eacute;dicamento externa"><label class="boton" style="cursor:pointer" onclick="crearodmedexter('externa')" >Orden de m&eacute;dicamento externa</label></td> 
               <td  title="Emergencia y Hospitalizaci&oacute;n"><label class="boton" style="cursor:pointer" onclick="crearodemh('emhp')" >Emergencia y Hospitalizaci&oacute;n</label></td>
		<td><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
	</tr> 
   <br>
        <tr>
 
             <td  title="Ver orden de m&eacute;dicamento interna"><label class="boton" style="cursor:pointer" onclick="veromext('interna')" >Ver orden de m&eacute;dicamento interna</label></td>
             <td  title="Ver orden de m&eacute;dicamento externa"><label class="boton" style="cursor:pointer" onclick="veromext('externa')" >Ver orden de m&eacute;dicamento externa</label></td>
            <td  title="Ver orden de m&eacute;dicamento Emergencia o Hospitalizaci&oacute;n"><label class="boton" style="cursor:pointer" onclick="veromext('emhp')" >Ver orden de Emergencia o Hospitalizaci&oacute;n</label></td>
        </tr>  
</table>
<div id='ordenesmedic'></div>
