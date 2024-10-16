<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form  name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Verificar Planilla o Numero de Presupuesto</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero de Planilla o Presupuesto</td>
		<td class="tdcampos"><input class="campos" type="text" id="numpre" name="numpre" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a OnClick="verificar_planilla2();" class="boton">Buscar</a><a OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="verificar_planilla2"></div>

</form>
