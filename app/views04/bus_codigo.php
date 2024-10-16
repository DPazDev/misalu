<?php
include ("../../lib/jfunciones.php");
sesion();
$q_ente=("select * from entes  order by entes.nombre");
$r_ente=ejecutar($q_ente);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=5 class="titulo_seccion">Buscar Datos de un Comprobante</td>	</tr>	
	<tr>
		<td class="tdtitulos">* Numero Codigo</td>
		<td class="tdcampos"><input class="campos" type="text" id="codigo" name="codigo" maxlength=128 size=30 value=""   onkeypress="return event.keyCode!=13"></td>
		<td colspan=1  class="tdtitulos"> </td>
		<td class="tdcamposc"> </td>
                <td class="tdcamposc"><a href="#" OnClick="bus_codigo1();" class="boton" title="Buscar Datos del Codigo">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
<div id="bus_codigo1"></div>

</form>
