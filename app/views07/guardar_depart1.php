<?php

/* Nombre del Archivo: guardar_depart1.php
   Descripción: modifica un DEPARTAMENTO en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$depart_nuevo = $_REQUEST['depart_nuevo'];
$id_depart2 = $_REQUEST['id_depart2'];
$coment_nuevo = $_REQUEST['coment_nuevo'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$depart_nuevo = strtoupper($depart_nuevo);
$coment_nuevo = strtoupper($coment_nuevo);

/*echo "******************* <br>";
echo $depart_nuevo."<br>"; 
echo "******************* <br>";
echo $coment_nuevo."<br>"; 
echo "******************* <br>";
echo $id_depart2."<br>";*/


$q_depart1 = "update departamentos set departamento='$depart_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',comentarios='$coment_nuevo' where id_departamento='$id_depart2'";
$r_depart1 = ejecutar($q_depart1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Departamento <?php echo $depart_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_depart();" class="boton">Modificar otra Sucursal</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
</table>
