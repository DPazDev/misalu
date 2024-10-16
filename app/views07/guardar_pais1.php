<?php

/* Nombre del Archivo: guardar_pais1.php
   Descripción: modifica un PAIS en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$pais_nuevo = $_REQUEST['pais_nuevo'];
$id_pais2 = $_REQUEST['id_pais2'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$pais_nuevo = strtoupper($pais_nuevo);

/*echo "******************* <br>";
echo $pais_nuevo."<br>"; 
echo "******************* <br>";
echo "******************* <br>";
echo $id_pais2."<br>"; 
echo "******************* <br>";*/

$q_pais1 = "update pais set pais='$pais_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado' where id_pais='$id_pais2'";
$r_pais1 = ejecutar($q_pais1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Pa&iacute;s <?php echo $pais_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_pais();" class="boton">Modificar otro Pa&iacute;s</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	
	</tr>
<br>
</table>
