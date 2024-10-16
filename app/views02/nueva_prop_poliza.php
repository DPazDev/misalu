<?
include ("../../lib/jfunciones.php");
sesion();
$lapolizaes=$_POST['lapoliza'];
$idpoliza=$_POST['idpoli'];
?>
<hr>
<input type='hidden' id='lapoliza' value='<?echo $lapolizaes?>'>
<input type='hidden' id='idpoliza' value='<?echo $idpoliza?>'>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Crear nueva propiedad p&oacute;liza</td>  
	</tr>	 
 </table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td class="tdtitulos">Nombre de la propiedad p&oacute;liza:</td>
<td class="tdcampos"><input type="text" id="nuevapropoliza" class="campos" size="35"></td>
<td  title="Guardar nueva propiedad p&oacute;liza"><label class="boton" style="cursor:pointer" onclick="guardar_nueva_propoliza(); return false;" >Guardar</label></td>
<td  title="Cerrar proceso actual"><label class="boton" style="cursor:pointer" onclick="propiedades_poliza(); return false;" >Cerrar</label></td>
<td  title="Salir"><label class="boton" style="cursor:pointer" onclick="ira(); return false;" >Salir</label></td>
</tr>
</table> 