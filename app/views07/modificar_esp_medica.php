<?php

/* Nombre del Archivo: modificar_esp_medica.php
   Descripción: Solicita los datos para MODIFICAR una ESPECIALIDAD MEDICA en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_esp_medica1 = $_REQUEST['medica0'];

echo "******************* <br>";
echo $id_esp_medica1."<br>"; 
echo "******************* <br>";



$q_esp_medica = "select especialidades_medicas.especialidad_medica, especialidades_medicas.monto from especialidades_medicas where especialidades_medicas.id_especialidad_medica='$id_esp_medica1';";
$r_esp_medica = ejecutar($q_esp_medica);
$f_esp_medica = asignar_a($r_esp_medica);



?>
<script>

</script>
<form method="POST" action="guardar_esp_medica1.php" name="medica">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Especialidad Médica</td>	</tr>		

	<tr>

		<td colspan=1 class="tdtitulos">* Nuevo Nombre de la Especialidad Médica</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="medica_nuevo" maxlength=128 size=20 value="<?php echo $f_esp_medica['especialidad_medica']; ?>"></td>
		<input type="hidden" name="id_esp_medica2" value="<?php echo $id_esp_medica1;?>">

	</tr>

	<!-- <tr>
	        <td colspan=1 class="tdtitulos">* Posee una cantidad de consultas gratis?</td>
	        <td colspan=1 class="tdtitulos"><input class="campos" type="radio" checked="checked" id="gratis_nuevo" name="gratis_nuevo" value="<?php echo $f_esp_medica['gratis'];?>">Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="campos" type="radio" id="gratis_nuevo" name="gratis_nuevo" value="<?php echo $f_esp_medica['gratis'];?>">No</td>
	</tr> -->

	<tr>
<br>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Monto</td>
		<td colspan=1 class="tdtitulos"><input class="campos" type="text" name="monto_nuevo" maxlength=128 size=20 value="<?php echo $f_esp_medica['monto'];?>"></td>
	
	
		<td colspan=1 class="tdcampos"><a href="#" OnClick="guardar_esp_medica1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<br>

</table>

</form>
										
