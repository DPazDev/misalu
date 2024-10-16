<?php

/* Nombre del Archivo: guardar_usuario1.php
   Descripción:  MODIFICAR un USUARIO en la base de datos, para ser utilizado posteriormente 
*/

include ("../../lib/jfunciones.php");
sesion();

$log=noacento($_SESSION['login_admin_'.empresa].", ha modificado un usuario.");
logs($log,$ip,$_SESSION['id_usuario_'.empresa]);


$nombres=$_REQUEST['nombres'];
$id_admin1=$_REQUEST['id_admin1'];
$apellidos=$_REQUEST['apellidos'];
$cedula=$_REQUEST['cedula'];
$cargo=$_REQUEST['cargo'];
$depart=$_REQUEST['depart'];
$sucursal=$_REQUEST['sucursal'];
$login=$_REQUEST['login'];
$email=$_REQUEST['email'];
$telef=$_REQUEST['telef'];
$celular=$_REQUEST['celular'];
list($ente)=explode("@",$_REQUEST['ente']);
$direccion=$_REQUEST['direccion'];
$pais=$_REQUEST['pais'];
$estado=$_REQUEST['estado'];
$ciudad=$_REQUEST['ciudad'];
$tipo=$_REQUEST['tipo'];
$acti1 = $_REQUEST['ac'];
$comentarios=$_REQUEST['comentarios'];
$dia = $_REQUEST['dia'];
$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];

$nombres = strtoupper($nombres);
$apellidos=strtoupper($apellidos);
$direccion=strtoupper($direccion);
$comentarios=strtoupper($comentarios);

$nu_modulo = $_REQUEST['nu_modulo'];
$valor1 = $_REQUEST['valor1'];

   $valor2=split("@",$valor1);

/*
echo "****";
echo $nombres."**";
echo $id_admin1."***";
echo $apellidos."**";
echo $cedula."**";
echo $cargo."**";
echo $depart."**";
echo $sucursal."**";
echo $login."**";
echo $passw."**";
echo $email."**";
echo $telef."**";
echo $celular."**";
echo $direccion."**";
echo $pais."--";
echo $estado."--";
echo $ciudad."--";
echo $tipo."**";
echo $comentarios."**";
echo $dia."**" ;
echo $mes."//" ;
echo $ano."//";
echo $ente."//";

*/

$fecha_nac="$ano-$mes-$dia";

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$q_usuario="update admin set id_departamento='$depart',login='$login',
            nombres='$nombres',apellidos='$apellidos',cedula='$cedula',
            email='$email',telefonos='$telef',direccion='$direccion',
            fecha_nac='$fecha_nac',comentarios='$comentarios',
            fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',
            activar='$acti1',celular='$celular',id_ciudad='$ciudad',id_cargo='$cargo',
            id_tipo_admin='$tipo',id_sucursal='$sucursal',id_ente='$ente' where admin.id_admin='$id_admin1';";

$r_usuario=ejecutar($q_usuario);


$q_borrar="delete from permisos where permisos.id_admin='$id_admin1'";
$r_borrar=ejecutar($q_borrar);

for($i=0;$i<=$nu_modulo;$i++){
	$valor=$valor2[$i];

$q_modulo="insert into permisos(id_admin,id_modulo,permiso,fecha_creado,hora_creado,fecha_modificado,hora_modificado)
values
('$id_admin1','$valor','1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_modulo=ejecutar($q_modulo);

/*echo $valor."//";*/
}


?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Usuario <?php echo $nombres." ".$apellidos;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_usuario1();" class="boton">Modificar otro Usuario</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
</table>
