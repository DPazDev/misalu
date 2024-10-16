<?php

/* Nombre del Archivo: mod_telefono.php
   Descripción: Formulario que muestra los datos para MODIFICAR un NUMERO TELEFONICO en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$ci=$_REQUEST['ci'];

$q_tele = "select clientes.id_cliente, clientes.nombres,clientes.apellidos, clientes.telefono_hab,clientes.telefono_otro, clientes.celular, clientes.cedula from clientes where clientes.cedula='$ci';";
$r_tele = ejecutar($q_tele);
$f_tele = asignar_a($r_tele);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<form action="mod_telefono.php" method="post" name="tel">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<?php if($ci==$f_tele[cedula]){?>

	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Teléfono</td>
	</tr>	
<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=150 size=25 value="<?php echo $f_tele['nombres'];?>"></td>
			<input type="hidden" name="id_cli" value="<?php echo $f_tele['id_cliente'];?>">
	<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" maxlength=150  size=25 value="<?php echo $f_tele['apellidos'];?>">    </td>
	</tr>
<tr><td>&nbsp;</td></tr>
<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono Residencial</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="telef" maxlength=32  size=25 value="<?php echo $f_tele['telefono_hab'];?>"   onkeypress="return ValNumero(this);"></td>
	
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono Celular 1</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular1" maxlength=32 size=25 value="<?php echo $f_tele['telefono_otro'];?>"  onkeypress="return ValNumero(this);" ></td>
	</tr>
<tr><td>&nbsp;</td></tr>
<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono Celular 2</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular2" maxlength=32 size=25  value="<?php echo $f_tele['celular'];?>" onkeypress="return ValNumero(this);"   ></td>
	</tr>

<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_telefono();" class="boton">Guardar</a> <a href="#" OnClick="reg_telefono();" class="boton">Insertar o Modificar otro Número Telefónico</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<tr><td>&nbsp;</td></tr>
</table>

<?}
else {?>
<tr>
		<td colspan=4 class="titulo_seccion"> El Cliente no esta Registrado</td>
	</tr>

<?}?>

