<?php

/* Nombre del Archivo: modificar_sucursal.php
   Descripción: Solicita los datos para MODIFICAR una SUCURSAL en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_sucursal1 = $_REQUEST['sucursal0'];

/*echo "******************* <br>";
echo $id_sucursal1."<br>"; 
echo "******************* <br>";*/

$q_sucursal = "select sucursales.sucursal, sucursales.telefonos_suc, sucursales.fax_suc, direccion_suc from sucursales where sucursales.id_sucursal='$id_sucursal1';";
$r_sucursal = ejecutar($q_sucursal);
$f_sucursal = asignar_a($r_sucursal);

?>
<script>

</script>
<form method="POST" action="guardar_sucursal1.php" name="sucursal">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Sucursal</td>	</tr>		
	<tr> <td>&nbsp;</td>
	<tr>

		<td colspan=1 class="tdtitulos">* Nuevo Nombre de la Sucursal</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="sucursal_nuevo" maxlength=128 size=20 value="<?php echo $f_sucursal['sucursal']; ?>"></td>
		<input type="hidden" name="id_sucursal2" value="<?php echo $id_sucursal1;?>">
		

		<td colspan=1 class="tdtitulos">* N&uacute;mero de Tel&eacute;fono</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="telefono_nuevo" maxlength=128 size=20 value="<?php echo $f_sucursal['telefonos_suc']; ?>"></td>

	</tr>

	<tr>
                <td colspan=1 class="tdtitulos">* N&uacute;mero de Fax</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="fax_nuevo" maxlength=128 size=20 value="<?php echo $f_sucursal['fax_suc'];?>"></td>
	
	        <td colspan=1 class="tdtitulos">* Direcci&oacute;n</td>

	        <td colspan=1 ><textarea class="campos" name="direccion_nuevo" cols="40" rows="3"><?php echo $f_sucursal['direccion_suc'];?></textarea></td>
	
	</tr>
		<tr> <td>&nbsp;</td>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_sucursal1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr> <td>&nbsp;</td>
</table>

</form>
										
