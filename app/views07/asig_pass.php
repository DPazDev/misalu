<?php

/* Nombre del Archivo: mod_pass que muestra los datos para MODIFICAR algunos datos de un USUARIO en la base de datos 
*/
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");

$ci=$_REQUEST['ci'];

$q_usuario="select admin.* from admin where admin.cedula='$ci';";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<form action="guardar_pass.php" method="post" name="usuario">

<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0>



	<tr>
		<td colspan=4 class="titulo_seccion"> Reiniciar Password a Usuario por Olvido de Contraseña </td>
	</tr>	
<tr><td colspan=4>&nbsp;</td></tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Login</td>
		<td colspan=1 class="tdcampos"><?php echo $f_usuario['login'];?></td>
		<input type="hidden" name="login" id="login" value="<?php echo $f_usuario['login'];?>">
		<input type="hidden" name="id_admin1" id="id_admin1" value="<?php echo $f_usuario['id_admin'];?>">
		<input type="hidden" name="antepass" id="antepass" value="<?php echo $f_usuario['password'];?>">


</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Password</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw" id="passw" maxlength=65 size=25 value="nuevopass"></td>
		<td colspan=1 class="tdcampos"></td>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
<tr>

		<td colspan=1 class="tdtitulos">* Re-Password</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw1" id="passw1" maxlength=65 size=25 value="nuevopass"></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="guardar_pass1();" class="boton">Guardar</a> 
<a href="logout.php" OnClick="ir_principal();" class="boton">Salir</a>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
</table>
<div id="asig_pass1"></div>
</form>
