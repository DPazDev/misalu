<?php

/* Nombre del Archivo: guardar_vendedor1.php
   Descripción:  MODIFICAR un VENDEDOR en la base de datos, para ser utilizado posteriormente 
*/

include ("../../lib/jfunciones.php");
sesion();

$nombres=$_REQUEST['nombres'];
$id_admin1=$_REQUEST['id_admin1'];
$id_admin=$_REQUEST['id_admin'];
$apellidos=$_REQUEST['apellidos'];
$cedula=$_REQUEST['cedula'];
$direccion=$_REQUEST['direccion'];
$celular=$_REQUEST['celular'];
$email=$_REQUEST['email'];
$tipo=$_REQUEST['tipo'];
$tipodeprovee=$_REQUEST['tipodeprovee']; 
$proact=$_REQUEST['proact'];

$nombres = strtoupper($nombres);
$apellidos=strtoupper($apellidos);
$direccion=strtoupper($direccion);
$comentarios=strtoupper($comentarios);


/*echo "****";
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
echo $ano."//";*/

$fecha_nac="$ano-$mes-$dia";

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");


$q_usuario="update comisionados set
            nombres='$nombres',apellidos='$apellidos',cedula='$cedula',
	    fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',
direccion='$direccion',celular='$celular',email='$email',id_admin='$id_admin',id_tipo_comisionado='$tipo',id_tipo_pro='$tipodeprovee',id_act_pro='$proact'
           where comisionados.id_comisionado='$id_admin1';";

$r_usuario=ejecutar($q_usuario);




$q_codigo=("select comisionados.id_comisionado, comisionados.id_tipo_comisionado from comisionados where comisionados.cedula='$cedula' and comisionados.fecha_creado='$fecha_creado'");
$r_codigo=ejecutar($q_codigo);
$f_codigo=asignar_a($r_codigo);

$codigo_vendedor="$f_codigo[id_tipo_comisionado]00$f_codigo[id_comisionado]";


$q_usuario1="update comisionados set
            codigo='$codigo_vendedor' where comisionados.id_comisionado='$f_codigo[id_comisionado]';";

$r_usuario1=ejecutar($q_usuario1);


?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



	<tr>		
	<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Usuario <?php echo $nombres." ".$apellidos;?></td>	
	</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
</table>
