<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Asignar Cobertura Auxiliar a una Orden</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero de Orden</td>
		<td class="tdcampos"><input class="campos" type="hidden" name="edoproceso" 
					maxlength=128 size=20 value="0" ><input class="campos" type="text" name="proceso" maxlength=128 size=20 value=""    onkeyUp="return ValNumero(this);" onkeypress="return event.keyCode!=13"><input class="campos" type="hidden" id="donativo" name="donativo" value="0"></td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="asigcober();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="asigcober"></div>

</form>
