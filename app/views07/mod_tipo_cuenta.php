<?php

/* Nombre del Archivo: mod_tipo_cuenta.php
   Descripción: Solicita los datos para MODIFICAR un TIPO DE CUENTA en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_tipocuenta = $_REQUEST['tcuenta'];

$q_cuenta = "select * from tbl_tiposcuentas where tbl_tiposcuentas.id_tipocuenta='$id_tipocuenta';";
$r_cuenta = ejecutar($q_cuenta);
$f_cuenta = asignar_a($r_cuenta);

?>
<script>

</script>
<form method="POST" action="mod_tipo_cuenta.php" name="tcuen">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Tipo de Cuenta</td>	</tr>		
<br>
	<tr>

		<td colspan=1 class="tdtitulos">* Nuevo Tipo de Cuenta</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cuenta2" maxlength=128 size=30 value="<?php echo $f_cuenta['tipo_cuenta']; ?>"></td>
		<input type="hidden" name="id_cuenta" value="<?php echo $f_cuenta[id_tipocuenta];?>">
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="guardar_tipo_cuenta1();" class="boton">Guardar</a> <a href="#" OnClick="reg_tipo_cuenta();" class="boton">Insertar o Modificar otro Tipo de Cuenta</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
</tr>
<br>
</table>
