<?php

/* Nombre del Archivo: reg_ciudad.php
   Descripción: Solicita los datos para INSERTAR o MODIFICAR una CIUDAD en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$q_ciudad0 = "select ciudad.id_ciudad,ciudad.ciudad from ciudad";
$r_ciudad0 = ejecutar($q_ciudad0);

$q_pais = "select pais.id_pais,pais.pais from pais";
$r_pais = ejecutar($q_pais);

$q_estado = "select estados.id_estado,estados.estado,estados.id_pais from estados ";
$r_estado = ejecutar($q_estado);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<form action="guardar_ciudad.php" method="post" name="ciudadd">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Registrar o Modificar Ciudad</td>	</tr>	
	<tr> <td>&nbsp;</td>
<tr>
                <td colspan=1 class="tdtitulos">* Verificar nombre de la Ciudad</td>
                <td colspan=1><select name="ciudad0" class="campos">
		<option value="">-- Seleccione una Ciudad --</option>
		<?php
		while($f_ciudad0 = asignar_a($r_ciudad0)){
			echo "<option value=".$f_ciudad0['id_ciudad'].">".$f_ciudad0['ciudad']."</option>";
		}
		?>
		</select>
		</td>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="modificar_ciudad();" class="boton">Modificar</a></td>
        </tr>


	<tr> <td>&nbsp;</td>


	<tr>
                <td colspan=1 class="tdtitulos">* Nombre del País</td>
                <td colspan=1><select name="pais" class="campos">
		<option value="">-- Seleccione un país --</option>
		<?php
		while($f_pais = asignar_a($r_pais)){
			echo "<option value=".$f_pais['id_pais'].">".$f_pais['pais']."</option>";
		}
		?>
		</select>
		</td>

  		<td colspan=2 class="tdcampos"><a href="#" OnClick="guardar_ciudad();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr> <td>&nbsp;</td>
</table>

<div id="guardar_estado"></div>

</form>
