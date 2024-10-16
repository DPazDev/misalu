<?php

/* Nombre del Archivo: guardar_banco.php
   Descripción: Guarda los números de Cuentas en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$nombre = $_REQUEST['nombre'];
$nombre = strtoupper($nombre);

$q_banco = "insert into tbl_bancos (nombanco) values('$nombre')";
$r_banco = ejecutar($q_banco);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4>&nbsp;</td>
	</tr> 
	<tr>		
		<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Banco <?php echo $nombre;?></td>	
	</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc">
                <a href="#" OnClick="banco();" class="boton">Insertar o Modificar</a> 
                <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>	
	</tr>
	<tr>
		<td colspan=4>&nbsp;</td>
	</tr> 
</table>
