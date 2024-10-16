<?php

/* Nombre del Archivo: guardar_ciudad.php
   Descripción: INSERTAR una CIUDAD en la base de datos, para ser utilizado posteriormente 
*/

include("../../lib/jfunciones.php");
sesion();

$id_pais = $_REQUEST['pais'];
$estado = $_REQUEST['estado'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$estado = strtoupper($estado);


/*echo "******************* <br>";
echo $estado."<br>"; 
echo "******************* <br>";
echo $id_pais."<br>";
echo "******************* <br>";
echo $ciudad."<br>";*/

 
$q_estado = "select estados.id_estado,estados.estado,estados.id_pais from estados where estados.id_pais='$id_pais'";
$r_estado = ejecutar($q_estado);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<form action="guardar_ciudad1.php" method="post" name="ciudadd1">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Registrar Ciudad</td>	</tr>	
	<tr> <td>&nbsp;</td>
	<tr>
                <td colspan=1 class="tdtitulos">* Nombre del estado</td>
                <td colspan=1><select name="estado" class="campos">
		<option value="">-- Seleccione un Estado --</option>
		<?php
		while($f_estado = asignar_a($r_estado)){
			echo "<option value=".$f_estado['id_estado'].">".$f_estado['estado']."</option>";
		}
		?>
		</select>
		</td>

        </tr>

	<tr> <td>&nbsp;</td>

	<tr>

		<td colspan=1 class="tdtitulos">* Nombre de la ciudad</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="ciudad" maxlength=128 size=20 value=""></td>

		<td colspan=1 class="tdcampos"><a href="#" OnClick="guardar_ciudad1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
	<tr> <td>&nbsp;</td>
</table>
</from>
