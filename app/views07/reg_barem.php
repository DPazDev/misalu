<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="baremo" id="baremo">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar o Modificar Baremos de Examenes y Estudios Especiales </td>	</tr>	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Baremo.</td>
<td  class="tdcampos">
		<select name="tbaremo" class="campos" >
		
		<option value="0"> de Estudios Especiales</option>
		<option value="1"> Varios (Laboratorios Emergencias Otros)</option>
		<option value="2"> Especialidades Medicas</option>
		
		</select>
		<a href="#" OnClick="reg_baremo1();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>

</table>
	<div id="reg_baremo1"></div>

</form>
