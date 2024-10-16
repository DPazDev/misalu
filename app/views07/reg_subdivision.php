<?php
/* Nombre del Archivo: reg_subdivision.php
   Descripción: Solicita los datos para REGISTRAR o MODIFICAR una SUBDIVISION en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$q_subdi = "select subdivisiones.id_subdivision,subdivisiones.subdivision from subdivisiones order by subdivisiones.subdivision ASC";
$r_subdi = ejecutar($q_subdi);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="reg_subdivision.php" method="post" name="subdiv">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>	<td colspan=4 class="titulo_seccion">Registrar o Modificar Subdivisión</td>	</tr>
	<tr> <td>&nbsp;</td>
	<tr>
                <td colspan=1 class="tdtitulos">* Verificar nombre de Subdivisión</td>
                <td colspan=1><select name="subdivi" class="campos">
		<option value="">-- Seleccione una Subdivision --</option>
		<?php
		while($f_subdi = asignar_a($r_subdi)){
			echo "<option value=".$f_subdi['id_subdivision'].">".$f_subdi['subdivision']."</option>";
		}
		?>
		</select>
		</td>
	</tr>
	<tr> <td>&nbsp;</td>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp;</td>

		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="mod_subdivision();" class="boton">Modificar</a></td>
        </tr>

		<td>&nbsp;</td>

	
	<tr>
		<td colspan=1 class="tdtitulos">* Nombre de Subdivisión</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="subdivi1" maxlength=128 size=30 value=""></td>
	</tr>
	<tr> <td>&nbsp;</td>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp;</td>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="guardar_subdivision();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
		<td>&nbsp;</td>
</table>
</form>
