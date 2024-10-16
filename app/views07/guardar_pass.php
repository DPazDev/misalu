<?php

/* Nombre del Archivo: guardar_pass.php
   Descripción:  MODIFICAR EL PASSWORD DE UN USUARIO en la base de datos, para ser utilizado posteriormente 
*/

include ("../../lib/jfunciones.php");
/*sesion();*/

/*$log=noacento($_SESSION['login_admin_'.empresa].", ha modificado su password.");
logs($log,$ip,$_SESSION['id_usuario_'.empresa]);*/


$id_admin1=$_REQUEST['id_admin1'];
$login=$_REQUEST['login'];
$passw=$_REQUEST['passw'];
$passw1=$_REQUEST['passw1'];
$antepass=$_REQUEST['antepass'];


$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

/*  $q_pass="select admin.* from admin where admin.id_admin='$elid';";
$r_pass=ejecutar($q_pass);
$f_pass=asignar_a($r_pass); 
?>
		<input type="hidden" name="antepass" value="<?php echo $f_pass['password'];?>">
<?
*/
$q_usuario="update admin set password='$passw',fecha_modificado='$fecha', hora_modificado='$hora' where admin.id_admin='$id_admin1';";
$r_usuario=ejecutar($q_usuario);


//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado su password  con el id_admin $id_admin1 ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);

//fin de los registros en la tabla logs;

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Usuario <?php echo $login;?></td>	
</tr>	



	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="logout.php"  class="boton">Salir</a></td>

	</tr>
</table>
