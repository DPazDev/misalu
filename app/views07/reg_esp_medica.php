<?php

/* Nombre del Archivo: reg_esp_medica.php
   Descripción: Solicita los datos para INSERTAR o MODIFICAR una ESPECIALIDAD MEDICA en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$q_medica0 = "select especialidades_medicas.id_especialidad_medica,especialidades_medicas.especialidad_medica from especialidades_medicas";
$r_medica0 = ejecutar($q_medica0);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<form action="guardar_esp_medica.php" method="post" name="medica">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Registrar y Modificar Especialidad Médica</td>	</tr>	

	<tr>
                <td colspan=1 class="tdtitulos">* Verificar Especialidad Médica</td>
                <td colspan=1 ><select name="medica0" class="campos">
		<option value="">-- Seleccione una Especialidad Médica --</option>
		<?php
		while($f_medica0 = asignar_a($r_medica0)){
			echo "<option value=".$f_medica0['id_especialidad_medica'].">".$f_medica0['especialidad_medica']."</option>";
		}
		?>
		</select>
	<td colspan=1 class="tdcampos"><a href="#" OnClick="modificar_esp_medica();" class="boton">Modificar</a>
		</td>
		
        </tr>
	<tr>
<br>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Nombre de la Esp_Médica</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="medica" maxlength=128 size=20 value=""></td>
	</tr>

	<tr>
<br>
	</tr>

	<!--<tr>
	        <td colspan=1 class="tdtitulos"><input type="radio" name="gratis" id="gratis" value="Mi"> Milk<br>
<input type="radio" name="gratis" id="gratis" value="Bu" checked> Butter<br></td>
	</tr>

	<tr>
<br>
	</tr> -->
	<tr>

		<td colspan=1 class="tdtitulos">* Monto</td>
		<td colspan=1 class="tdtitulos"><input class="campos" type="text" name="monto" id="monto" maxlength=128 size=20 value=""></td>

		<td colspan=2 class="tdcampos"><a href="#" OnClick="guardar_esp_medica();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<br>
</table>

<div id="guardar_esp_medica"></div>
<div id="modificar_esp_medica"></div>
</form>

