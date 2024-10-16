<?php

/* Nombre del Archivo: guardar_estado1.php
   Descripción: modifica un ESTADO en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$estado_nuevo = $_REQUEST['estado_nuevo'];
$id_pais2 = $_REQUEST['id_pais2'];
$id_estado2 = $_REQUEST['id_estado2'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$estado_nuevo = strtoupper($estado_nuevo);

/*echo "******************* <br>";
echo $estado_nuevo."<br>"; 
echo "******************* <br>";
echo "******************* <br>";
echo $id_pais2."<br>"; 
echo "******************* <br>";
echo $id_estado2."<br>"; 
echo "******************* <br>";*/



$q_estado1 = "update estados set estado='$estado_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado' where id_estado='$id_estado2'";
$r_estado1 = ejecutar($q_estado1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Estado <?php echo $estado_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_estado();" class="boton">Modificar otro Estado</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
<br>
</table>
