<?php

/* Nombre del Archivo: guardar_usuario.php
   Descripción:  INSERTAR un USUARIO en la base de datos, para ser utilizado posteriormente  
*/

include ("../../lib/jfunciones.php");
sesion();

$log=noacento($_SESSION['login_admin_'.empresa].", ha guardado un usuario.");
logs($log,$ip,$_SESSION['id_usuario_'.empresa]);


$nombres=$_REQUEST['nombres'];
$apellidos=$_REQUEST['apellidos'];
$cedula=$_REQUEST['cedula'];
$cargo=$_REQUEST['cargo'];
$depart=$_REQUEST['depart'];
$sucursal=$_REQUEST['sucursal'];
$login=$_REQUEST['login'];
$passw=$_REQUEST['passw'];
$email=$_REQUEST['email'];
$telef=$_REQUEST['telef'];
$celular=$_REQUEST['celular'];

   list($ente)=explode("@",$_REQUEST['ente']);
/*$ente=$_REQUEST['ente'];*/
$direccion=$_REQUEST['direccion'];
$pais=$_REQUEST['pais'];
$estado=$_REQUEST['estado'];
$ciudad=$_REQUEST['ciudad'];
$tipo=$_REQUEST['tipo'];
$comentarios=$_REQUEST['comentarios'];
$dia = $_REQUEST['dia'];
$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];
$acti1 = $_REQUEST['ac'];

$nombres = strtoupper($nombres);
$apellidos=strtoupper($apellidos);
$direccion=strtoupper($direccion);
$comentarios=strtoupper($comentarios);


$nu_modulo = $_REQUEST['nu_modulo'];
$valor1 = $_REQUEST['valor1'];

   $valor2=split("@",$valor1);




/*echo $acti1."@@@";
echo "****";
echo $nombres."**";
echo $apellidos."**";
echo $cedula."**";
echo $cargo."**";
echo $depart."**";
echo $sucursal."**";
echo $login."**";
echo $passw."¬¬¬¬¬¬";
echo $ente."¬¬¬¬¬¬";
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
echo $ano."????????";
echo $nu_modulo."+++++";*/





$fecha_nac="$ano-$mes-$dia";

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");


$q_usuario="insert into admin(id_departamento,login,password,nombres,apellidos,cedula,email,telefonos,direccion,fecha_nac,comentarios,fecha_creado,hora_creado,fecha_modificado,hora_modificado,activar,celular,id_ciudad,id_cargo,id_tipo_admin,id_sucursal,id_ente)
values('$depart','$login','$passw','$nombres','$apellidos','$cedula','$email','$telef','$direccion','$fecha_nac','$comentarios','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$acti1','$celular','$ciudad','$cargo','$tipo','$sucursal','$ente')";
$r_usuario=ejecutar($q_usuario);


$q_admin="select admin.id_admin from admin where admin.cedula='$cedula'";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$admin=$f_admin['id_admin'];


for($i=0;$i<=$nu_modulo;$i++){//este repita lee el vector con la informacion de la cantidad de permisos asignados a cada usuario, para luego guardarlos en la base de datos 
	$valor=$valor2[$i];

$q_modulo="insert into permisos(id_admin,id_modulo,permiso,fecha_creado,hora_creado,fecha_modificado,hora_modificado)
values
('$admin','$valor','1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_modulo=ejecutar($q_modulo);

/*echo $valor."//";*/
}



?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Usuario <?php echo $nombres." ".$apellidos;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_usuario1();" class="boton">Insertar otro Usuario</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a> <a href="#" OnClick="buscar_permisos();" class="boton">Permisologia de Usuario</a></td>

	</tr>
	

</table>

