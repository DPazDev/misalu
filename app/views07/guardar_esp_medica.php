<?php

/* Nombre del Archivo: guardar_esp_medica.php
   Descripción: Inserta una ESPECIALIDAD MEDICA	en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$medica = $_REQUEST['medica'];

$monto = $_REQUEST['monto'];
$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$medica = strtoupper($medica);

echo $medica."<br>"; 

echo "******************* <br>";
echo $monto."<br>";


$q_medica = "insert into especialidades_medicas (especialidad_medica,fecha_creado,hora_creado,fecha_modificado,hora_modificado,especial,monto) values('$medica','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','1','$monto')";
$r_medica = ejecutar($q_medica);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Especialidad M&eacute;dica <?php echo $medica;?></td>	
</tr>	

	<tr>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_esp_medica();" class="boton">Insertar otra Especialidad M&eacute;dica</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
<br>
</table>

