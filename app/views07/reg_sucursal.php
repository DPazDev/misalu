<?php
/* Nombre del Archivo: reg_sucursal.php
   Descripción: Solicita los datos para REGISTRAR o MODIFICAR una SUCURSAL en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$q_sucursal0 = "select sucursales.id_sucursal,sucursales.sucursal from sucursales";
$r_sucursal0 = ejecutar($q_sucursal0);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="guardar_sucursal.php" method="post" name="sucursal">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>	<td colspan=4 class="titulo_seccion">Registrar o Modificar Sucursal</td>	</tr>
	<tr> <td>&nbsp;</td>
	<tr>
                <td colspan=1 class="tdtitulos">* Verificar nombre de Sucursal</td>
                <td colspan=1><select name="sucursal0" class="campos">
		<option value="">-- Seleccione una Sucursal --</option>
		<?php
		while($f_sucursal0 = asignar_a($r_sucursal0)){
			echo "<option value=".$f_sucursal0['id_sucursal'].">".$f_sucursal0['sucursal']."</option>";
		}
		?>
		</select>
		</td>
		<td colspan=1></td>
		<td colspan=1 class="tdcampos"><a href="#" OnClick="modificar_sucursal();" class="boton">Modificar</a></td>
        </tr>

	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
                <td colspan=1 class="tdtitulos">* Nombre de Sucursal</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="sucursal" maxlength=128 size=20 value=""></td>
	
                <td colspan=1 class="tdtitulos">* N&uacute;mero de Tel&eacute;fono</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="telefono" maxlength=128 size=20 value=""></td>

	</tr>

	<tr>
                <td colspan=1 class="tdtitulos">* N&uacute;mero de Fax</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="fax" maxlength=128 size=20 value=""></td>
	
	        <td colspan=1 class="tdtitulos">* Direcci&oacute;n</td>

	        <td colspan=1><textarea class="campos" name="direccion" cols="40" rows="3"></textarea></td>
	
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_sucursal();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
	<tr> <td>&nbsp;</td>
</table>

<div id="guardar_sucursal"></div>
<div id="modificar_sucursal"></div>
</form>
