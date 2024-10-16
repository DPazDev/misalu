<?php

/* Nombre del Archivo: guardar_esp_medica1.php
   Descripción: Modifica una ESPECIALIDAD MEDICA en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$medica_nuevo = $_REQUEST['medica_nuevo'];
$id_esp_medica2 = $_REQUEST['id_esp_medica2'];

$monto_nuevo = $_REQUEST['monto_nuevo'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$medica_nuevo = strtoupper($medica_nuevo);

echo $medica_nuevo."<br>"; 

echo "******************* <br>";
echo $monto_nuevo."<br>";
echo "******************* <br>";
echo $id_esp_medica2."***";


$q_medica1 = "update  especialidades_medicas set especialidad_medica='$medica_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',especial='1',monto='$monto_nuevo' where id_especialidad_medica='$id_esp_medica2'";
$r_medica1 = ejecutar($q_medica1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Especialidad M&eacute;dica <?php echo $medica_nuevo;?></td>	
</tr>	

	<tr>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_esp_medica();" class="boton">Modificar otra Especialidad M&eacute;dcica</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
</table>

