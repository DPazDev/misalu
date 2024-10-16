<?php

/* Nombre del Archivo: guardar_var_global.php
   Descripción: Guarda la Variable Global en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$nombre = $_REQUEST['nombre'];
$cant = $_REQUEST['cant'];
$compra = $_REQUEST['compra'];
$iva = $_REQUEST['iva'];

$nombre = strtoupper($nombre);
$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$q_var_global = "insert into variables_globales (nombre_var,cantidad,fecha_creado,hora_creado,fecha_modificado,hora_modificado,comprasconfig,iva) values('$nombre','$cant','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$compra','$iva')";
$r_var_global = ejecutar($q_var_global);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Variable Global <?php echo $nombre;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="var_globales();" class="boton">Insertar o Modificar otra Variable Global</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>	
	</tr>
<br>
</table>
