<?php

/* Nombre del Archivo: guardar_var_global1.php
   Descripción: Modifica una VARIABLE GLOBAL en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$variable_nuevo = $_REQUEST['variable_nuevo'];
$cant_nuevo = $_REQUEST['cant_nuevo'];
$compra_nuevo = $_REQUEST['compra_nuevo'];
$id_variable_global2 = $_REQUEST['id_variable_global2'];
$iva_nuevo = $_REQUEST['iva_nuevo'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$variable_nuevo = strtoupper($variable_nuevo);

$q_var = "update variables_globales set nombre_var='$variable_nuevo',cantidad='$cant_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',comprasconfig='$compra_nuevo',iva='$iva_nuevo'  where id_variable_global='$id_variable_global2'";
$r_var = ejecutar($q_var);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito la Variable Global <? echo $variable_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="var_globales();" class="boton">Insertar o Modificar otra Variable Global</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
</table>
