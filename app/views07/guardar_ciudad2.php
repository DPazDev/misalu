<?php

/* Nombre del Archivo: guardar_ciudad2.php
   Descripción: MODIFICA una CIUDAD en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$ciudad_nuevo = $_REQUEST['ciudad_nuevo'];
$id_ciudad2 = $_REQUEST['id_ciudad2'];
$id_estado2 = $_REQUEST['id_estado2'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$ciudad_nuevo = strtoupper($ciudad_nuevo);

/*echo "******************* <br>";
echo $ciudad_nuevo."<br>"; 
echo "******************* <br>";
echo $id_estado2."<br>"; 
echo "******************* <br>";
echo $id_ciudad2."<br>"; 
echo "******************* <br>";*/



$q_ciudad1 = "update ciudad set ciudad='$ciudad_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado' where id_ciudad='$id_ciudad2'";
$r_ciudad1 = ejecutar($q_ciudad1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito la Ciudad <?php echo $ciudad_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_ciudad();" class="boton">Modificar otra Ciudad</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	
	</tr>
</table>
