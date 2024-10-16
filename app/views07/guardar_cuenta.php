<?php

/* Nombre del Archivo: guardar_cuenta.php
   Descripción: Guarda los números de Cuentas en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$nombre = $_REQUEST['nombre'];
$id_banco = $_REQUEST['id_banco'];
$num = $_REQUEST['num'];
$tipo = $_REQUEST['tipo'];
$tipo = strtoupper($tipo);

$q_cuenta = "insert into bancos (nombre_banco,numero_cuenta,tipo_cuenta,id_ban) values('$nombre','$num','$tipo','$id_banco')";
$r_cuenta = ejecutar($q_cuenta);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>

	<tr>		<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Cuenta Bancaria <?php echo $nombre;?></td>	
	</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="banco();" class="boton">Insertar o Modificar otra Cuenta</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>	
	</tr>
	<tr>
		<td colspan=4>&nbsp;</td>
	</tr> 
</table>
