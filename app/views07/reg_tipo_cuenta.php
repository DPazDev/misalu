<?php
/* Nombre del Archivo: reg_tipo_cuenta.php
   Descripción: Solicita los datos para REGISTRAR o MODIFICAR un TIPO DE CUENTA en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$q_tipcuenta = "select *  from tbl_tiposcuentas order by tbl_tiposcuentas.tipo_cuenta ASC";
$r_tipcuenta = ejecutar($q_tipcuenta);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="reg_tipo_cuenta.php" method="post" name="cuenta">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>	<td colspan=4 class="titulo_seccion">Registrar o Modificar Tipo de Cuenta</td>	</tr>
<br>
	<tr>
                <td colspan=1 class="tdtitulos">* Verificar Tipo de Cuenta</td>
                <td colspan=1><select name="tcuenta" class="campos">
		<option value="">-- Seleccione Tipo de Cuenta --</option>
		<?php
		while($f_tipcuenta = asignar_a($r_tipcuenta)){
			echo "<option value=".$f_tipcuenta['id_tipocuenta'].">".$f_tipcuenta['tipo_cuenta']."</option>";
		}
		?>
		</select>
		</td>

		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="mod_tipo_cuenta();" class="boton">Modificar</a></td>
        </tr>

	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=1 class="tdtitulos">* Nombre del Tipo de Cuenta</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cuenta1" maxlength=128 size=30 value=""></td>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="guardar_tipo_cuenta();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
<br>
</table>
</form>
