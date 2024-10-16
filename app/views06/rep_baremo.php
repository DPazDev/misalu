<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="baremo" id="baremo">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Codificacion de Baremos y Servicios </td>	</tr>	
	
<tr>
<td  class="tdtitulos">* Seleccione  el Baremo.</td>
<td  class="tdcampos">
		<select name="tbaremo"  id="tbaremo"  class="campos" >
		
		<option value="0@DE ESTUDIOS ESPECIALES"> de Estudios Especiales</option>
		<option value="1@VARIOS"> Varios (Laboratorios Emergencias Otros)</option>
		<option value="2@MEDICO"> Especialidades Medicas</option>
        <option value="3@MEDICAMENTOS E INSUMOS"> Medicamentos e Insumos</option>
        <option value="4@SERVICIOS"> Codificacion de Servicio</option>
		
		</select>
		<a href="#" OnClick="rep_baremo1();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
		</tr>

</table>
	<div id="bus_rep_baremo1"></div>

</form>
