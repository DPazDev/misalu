<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form  name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Actualizar Orden</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero de Orden</td>
		<td class="tdcampos"><input class="campos" type="text" name="proceso" maxlength=128 size=20 value=""  onchange="return ValNumero(this);" onkeypress="return event.keyCode!=13"></td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a OnClick="act_orden2();" class="boton">Buscar</a><a OnClick="ir_principal();" class="boton" title="Ir Al Inicio">Salir</a></td>
		</tr>
</table>
<div id="actorden"></div>

</form>
