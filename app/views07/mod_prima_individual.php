<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("h:i:s A");
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" id="factura" name="factura">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Modificar las Primas de un Cuadro Recibo de Primas</td>	</tr>	
	<tr>
		<td class="tdtitulos">* C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" id="cedula" name="cedula" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13">ó</td>
		<td colspan=1 class="tdtitulos"></td>
<td colspan=1 class="tdcampos">
	</td>
		</tr>
        <tr>
		<td class="tdtitulos">* Numero Recibo Prima</td>
		<td class="tdcampos"><input class="campos" type="text" id="numero_contrato" name="numero_contrato" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"></td>
		<td colspan=1 class="tdtitulos"></td>
<td colspan=1 class="tdcampos">
	<a href="#" OnClick="bus_mod_prima_indi();" title="buscar el contrato del usuario y sus cuadro recibo de prima " class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
	
	</td>
		</tr>
		
</table>
<div id="mod_prima_indi"></div>

</form>
