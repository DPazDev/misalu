<?php

/* Nombre del Archivo: modificar_var_global.php
   Descripción: Solicita los datos para MODIFICAR una VARIABLE GLOBAL en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_variable_global1 = $_REQUEST['nombre1'];

$q_var = "select variables_globales.id_variable_global,variables_globales.nombre_var, variables_globales.cantidad, variables_globales.comprasconfig, variables_globales.iva from variables_globales where variables_globales.id_variable_global='$id_variable_global1';";
$r_var = ejecutar($q_var);
$f_var = asignar_a($r_var);
?>

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js">

</script>
<form method="POST" action="modificar_var_global.php" name="variables">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Modificar Variables Globales</td>
</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Nuevo Nombre de la Variable</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="variable_nuevo"  size=30 value="<?php echo $f_var['nombre_var']; ?>"></td>
		<input type="hidden" name="id_variable_global2" value="<?php echo $id_variable_global1;?>">
		<td colspan=1 class="tdtitulos">* Nueva Cantidad</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cant_nuevo" maxlength=128 size=15 value="<?php echo $f_var['cantidad']; ?>"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
	<td colspan=1 class="tdtitulos">* Nueva Variable Formulario de Compras</td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" name="compra_nuevo" maxlength=128 size=20 value="<?php echo $f_var['comprasconfig']; ?>"></td>
	<td colspan=1 class="tdtitulos">* Nuevo Iva</td>
	<td colspan=1 class="tdcampos"><input class="campos" type="text" name="iva_nuevo" maxlength=128 size=15 value="<?php echo $f_var['iva']; ?>"></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_var_global1();" class="boton">Guardar</a> <a href="#" OnClick="var_globales();" class="boton">Insertar o Modificar otra Variable Global</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
<tr><td>&nbsp;</td></tr>
</table>
</from>
