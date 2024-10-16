<?
/* Nombre del Archivo: asig_poliza.php
   Descripción: Solicitar los datos para Asignar Póliza a Cliente ya Registrado
*/

	include ("../../lib/jfunciones.php");
	sesion();
	
	    		  
?>

<form method="POST" name="r_poliza" id="r_poliza">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

        <tr>
		<td colspan=4 class="titulo_seccion">Asignar Póliza a Cliente ya Registrado</td>
	</tr>
	<tr> <td>&nbsp;</td>
	</tr>
	<tr>
		<td  class="tdtitulos">&nbsp; N&uacute;mero de C&eacute;dula</td>
                <td  class="tdcampos"><input class="campos" type="text" id="ci" name="ci" maxlength=170 size=22 value=""></td>		
		<td  class="tdcampos"><a href="#" OnClick="asig_poliza1();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
	<tr> 
		<td colspan="4">&nbsp;</td>
	</tr>
</table>

<div id="asig_poliza1"></div>

</form>

