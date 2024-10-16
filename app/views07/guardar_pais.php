<?php

/* Nombre del Archivo: guardar_pais.php
   Descripción: Inserta un PAIS en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$pais = $_REQUEST['pais'];
$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$pais = strtoupper($pais);


/*echo "******************* <br>";
echo $pais."<br>"; 
echo "******************* <br>";*/


$q_pais = "insert into pais (pais,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$pais','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_pais = ejecutar($q_pais);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Pa&iacute;s <?php echo $pais;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_pais();" class="boton">Insertar otro Pa&iacute;s</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
	<br>	
</table>

