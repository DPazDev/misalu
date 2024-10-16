<?php
/* Nombre del Archivo: guardar_depart
   Descripción: Inserta un DEPARTAMENTO en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$depart = $_REQUEST['depart'];
$coment = $_REQUEST['coment'];
$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$depart = strtoupper($depart);
$coment = strtoupper($coment);

/*echo "******************* <br>";
echo $depart."<br>"; 
echo "******************* <br>";
echo $coment."<br>"; */

$q_depart = "insert into departamentos (departamento,fecha_creado,hora_creado,fecha_modificado,hora_modificado,comentarios) values('$depart','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado', '$coment')";
$r_depart = ejecutar($q_depart);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Departamento <?php echo $depart;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_depart();" class="boton">Insertar otro Departamento</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
<br>
</table>
