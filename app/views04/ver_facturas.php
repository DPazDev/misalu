<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="cita">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Ver o Modificar Facturas</td>	</tr>
	<tr>
		<td class="tdtitulos">* Numero de Factura</td>
		<td class="tdcampos"><input class="campos" type="text" id="factura" name="factura" maxlength=128 size=20 value="" onkeypress="return soloNumeros(event)"   onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="buscarfactura();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

		</tr>
</table>
<div id="buscarfactura"></div>

</form>
