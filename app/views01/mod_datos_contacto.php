<?php

/* Nombre del Archivo: mod_telefono.php
   Descripción: Formulario que muestra los datos para MODIFICAR un NUMERO TELEFONICO en la base de datos
*/

include ("../../lib/jfunciones.php");
sesion();

$ci=$_REQUEST['cedula'];
$divContenedor=$_REQUEST['dv'];

$q_tele = "select clientes.id_cliente, clientes.nombres,clientes.apellidos, clientes.telefono_hab,clientes.telefono_otro, clientes.celular, clientes.cedula, clientes.email from clientes where clientes.cedula='$ci';";
$r_tele = ejecutar($q_tele);
$f_tele = asignar_a($r_tele);
$idCliente=$f_tele['id_cliente'];

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<form action="mod_datos_contacto2.php" method="post" name="tel">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<?php if($ci==$f_tele[cedula]){?>

	<tr>
		<td colspan=2 class="titulo_seccion">Modificar datos de contacto Cliente</td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Nombres</td>

		<td colspan=1 class="tdcampos">
			<input class="campos" type="text" name="nombres" readonly maxlength=150 size=25 value="<?php echo $f_tele['nombres'];?>">
			<input type="hidden" name="id_cli" value="<?php echo $f_tele['id_cliente'];?>">
		</td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" readonly maxlength=150  size=25 value="<?php echo $f_tele['apellidos'];?>">    </td>
	</tr>
	<tr>
			<td colspan=1 class="tdtitulos">* Correo Electronico</td>
			<td colspan=1 class="tdcampos"><input class="campos" type="email" name="email" id='email' maxlength=32  size=25 value="<?php echo $f_tele['email'];?>"   ></td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono principal</td>
		<td colspan=1 class="tdcampos">
			<input class="campos" type="tel" name="celular1" id="telef1"  maxlength=32 size=25 value="<?php echo $f_tele['celular'];?>" onkeyup="return formatoTelefono(event);" list="listatelefonos">
					<datalist id="listatelefonos">
					  <option value="0412">
					  <option value="0416">
					  <option value="0426">
					  <option value="0414">
					  <option value="0424">
					  <option value="0274">
					  <option value="0275">
					</datalist>
		</td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono Segundario</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="tel" name="celular2" id="telef2" maxlength=32 size=25  value="<?php echo $f_tele['telefono_hab'];?>" list="listatelefonos" onkeyup="return formatoTelefono(event);"></td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* OtrosTel&eacute;fono</td>
		<td colspan=1 class="tdcampos">
					<input class="campos" type="text" name="celular2" id="telef3" maxlength=32 size=25  value="<?php echo $f_tele['telefono_otro'];?>" list="otrosTelefonos" onkeyup="return formatoTelefono(event);"></td>
					<datalist id="otrosTelefonos">
					  <option value="0274">
					  <option value="0275">
					  <option value="0212">
					  <option value="0281">
					  <option value="0273">
					  <option value="0241">
					  <option value="0295">
					</datalist>
	</tr>


	<tr>
		<td colspan=2 class="tdcamposcc"><br><a href="#" OnClick="mod_datos_contacto(<?php echo $idCliente;?>,'data-cliente');" class="boton">Guardar</a></td>

	</tr>
<tr><td>&nbsp;</td></tr>
</table>

<?}
else {?>
<tr>
		<td colspan=4 class="titulo_seccion"> El Cliente no esta Registrado</td>
	</tr>

<?}?>
