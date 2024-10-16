<?php

/* Nombre del Archivo: mod_pass que muestra los datos para MODIFICAR algunos datos de un USUARIO en la base de datos 
*/
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");



 $respuesta=$_REQUEST['respuesta'];
 //$resp=$_REQUEST['resp'];//
 $valor=$_REQUEST['valor'];
 $id=$_REQUEST['id'];
 $id_pre=$_REQUEST['id_pre'];
/*echo "----".$valor."****";*/




if($valor==1) {/*echo "hola 1"*/;

$q_pregunta=("select 
			tbl_preguntas.id_pregunta,
			tbl_preguntas.pregunta,
			tbl_respuestas.respuesta,
			tbl_respuestas.id_admin
		from 
			tbl_preguntas,
			tbl_respuestas 
		where 
			tbl_preguntas.id_pregunta=tbl_respuestas.id_pregunta and
			tbl_respuestas.id_admin='$id' and tbl_respuestas.id_pregunta=$id_pre ");
$r_pregunta=ejecutar($q_pregunta);
$f_pregunta=asignar_a($r_pregunta);

$resp= $f_pregunta[respuesta];




$q_contadores=("select admin.contador from admin where admin.id_admin='$id'");
$r_contadores=ejecutar($q_contadores);
$f_contadores=asignar_a($r_contadores);


//echo $f_contadores[contador]."*****";
	$con=$f_contadores[contador];
//echo "------".$con."*****";



if($respuesta!=$resp) { /*colocar el contador de respuesta incorrecta y la variable en tabla admin que lleve el contador, sera hasta 3 */ 
$con=$con+1;
//echo $con;

$int=(3 -$con);


if($con<=3){

$q_contar="update admin set contador='$con' where admin.id_admin='$id';";
$r_contar=ejecutar($q_contar);

?> 
<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0> 
	<tr> 

		<td colspan=4 class="titulo_seccion"> &nbsp;&nbsp;&nbsp;&nbsp; SU RESPUESTA ES INCORRECTA, COLOQUE NUEVAMENTE SU RESPUESTA ...   USTED CUENTA CON <? echo  $int; ?>   INTENTOS</td> 
	</tr> 
	<? 
}
else{

?> 
<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0> 
	<tr> 

		<td colspan=4 class="titulo_seccion"> &nbsp;&nbsp;&nbsp;&nbsp; SU CUENTA HA SIDO BLOQUEADA, POR FAVOR COMUNICARSE CON EL DEPARTAMENTO DE SISTEMAS ...</td> 
	</tr> 
	<?  
		}}



if($resp==$respuesta){
/*echo $respuesta;
echo $valor;
echo $resp;*/
$q_usuario="select admin.* from admin where admin.id_admin='$id';";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);

if($con>=3){


?> 
<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0> 
	<tr> 

		<td colspan=4 class="titulo_seccion"> &nbsp;&nbsp;&nbsp;&nbsp; SU CUENTA HA SIDO BLOQUEADA, POR FAVOR COMUNICARSE CON EL DEPARTAMENTO DE SISTEMAS ...</td> 
	</tr> 
	<?


}
else {
$q_contar="update admin set contador='0' where admin.id_admin='$id';";
$r_contar=ejecutar($q_contar);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<form action="guardar_pass.php" method="post" name="usuario">

<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0>



	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Password </td>
	</tr>	
<tr><td colspan=4>&nbsp;</td></tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Login</td>
		<td colspan=1 class="tdcampos"><?php echo $f_usuario['login'];?></td>
		<input type="hidden" name="login" value="<?php echo $f_usuario['login'];?>">
		<input type="hidden" name="id_admin1" value="<?php echo $f_usuario['id_admin'];?>">
		<input type="hidden" name="antepass" value="<?php echo $f_usuario['password'];?>">


</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Password</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw" maxlength=65 size=25 value=""></td>
		<td colspan=1 class="tdcampos">El Password debe contener entre 8 y 15 caracteres, formados por Letras y Números</td>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
<tr>

		<td colspan=1 class="tdtitulos">* Re-Password</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw1" maxlength=65 size=25 value=""></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="guardar_pass();" class="boton">Guardar</a> 
<a href="logout.php" OnClick="ir_principal();" class="boton">Salir</a>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
</table>
</form>


<?}}}




else  { /*echo "Hola 2"*/;







sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];






$q_usuario="select admin.* from admin where admin.id_admin='$elid';";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<form action="guardar_pass.php" method="post" name="usuario">

<table  class="tabla_cabecera3" colspan=4 cellpadding=0 cellspacing=0>



	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Password </td>
	</tr>	
<tr><td colspan=4>&nbsp;</td></tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Login</td>
		<td colspan=1 class="tdcampos"><?php echo $f_usuario['login'];?></td>
		<input type="hidden" name="login" value="<?php echo $f_usuario['login'];?>">
		<input type="hidden" name="id_admin1" value="<?php echo $f_usuario['id_admin'];?>">
		<input type="hidden" name="antepass" value="<?php echo $f_usuario['password'];?>">


</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Password</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw" maxlength=65 size=25 value=""></td>
		<td colspan=1 class="tdcampos">El Password debe contener entre 8 y 15 caracteres, formados por Letras y Números</td>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
<tr>

		<td colspan=1 class="tdtitulos">* Re-Password</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw1" maxlength=65 size=25 value=""></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="guardar_pass();" class="boton">Guardar</a> 
<a href="logout.php" OnClick="ir_principal();" class="boton">Salir</a>
	</tr>

<tr><td colspan=4>&nbsp;</td></tr>
</table>
</form>


<?}?>
