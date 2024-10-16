<?php
/* NOMBRE DEL ARCHIVO: r_proveedor.php
   DESCRIPCION: SOLICITAR LOS DATOS PARA REPORTE DE PROVEEDORES
*/

	include ("../../lib/jfunciones.php");
	sesion();
?>
<form method="POST" name="r_proveedor" id="r_proveedor">
	<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Proveedores </td>
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;   </td>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Proveedor: </td>
		
	<td colspan=1 class="tdcamposcc"><a href="#" OnClick="buscar_provper();" class="boton">* Proveedor Persona</a> </td>

<td colspan=1 class="tdcamposcc">
<a href="#" OnClick="buscar_provcli();" class="boton">*  Proveedor Clinica</a> </td>

	</tr>
<tr><td colspan=4>&nbsp;</td></tr>
</table>
<div id="buscar_provper"></div>
<table>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
     
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="reporte_proveedor();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_proveedor();" class="boton">Imprimir</a> 
<a href="#" OnClick="exc_proveedor();"><img border="0" src="../public/images/excel.jpg"></a> 
<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<tr><td colspan=4>&nbsp;</td></tr>
</table>
</form>
<div id="reporte_proveedor"></div>
