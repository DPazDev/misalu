<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<hr>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Crear un nuevo tipo de beneficiario para la p&oacute;liza</td>  
	</tr>	 
 </table>	
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td class="tdtitulos">Nombre del nuevo tipo de beneficiario para la p&oacute;liza:</td>
<td class="tdcampos"><input type="text" id="nuevotipobeni" class="campos" size="35"></td>
<td  title="Guardar nuevo ramo y recargar la pagina"><label class="boton" style="cursor:pointer" onclick="guardar_nuevo_tipobe(); return false;" >Guardar</label></td>
<td  title="Cerrar proceso actual"><label class="boton" style="cursor:pointer" onclick="cerrar_actual(); return false;" >Cerrar</label></td>
<td  title="Salir"><label class="boton" style="cursor:pointer" onclick="ira(); return false;" >Salir</label></td>
</tr>
</table>