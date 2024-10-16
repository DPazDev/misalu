<?php

/* Nombre del Archivo: guardar_estado.php
   Descripción: Inserta un ESTADO en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$pais = $_REQUEST['pais'];
$estado = $_REQUEST['estado'];
$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$estado = strtoupper($estado);


/*echo "******************* <br>";
echo $estado."<br>"; 
echo "******************* <br>";
echo $pais."<br>";*/ 

$q_estado = "insert into estados (id_pais,estado,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$pais','$estado','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_estado = ejecutar($q_estado);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Estado <?php echo $estado;?></td>	
</tr>	

	<tr>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_estado();" class="boton">Insertar otro Estado</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
<br>
</table>
