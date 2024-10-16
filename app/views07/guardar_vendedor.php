<?php

/* Nombre del Archivo: guardar_usuario.php
   Descripción:  INSERTAR un USUARIO en la base de datos, para ser utilizado posteriormente  
*/

include ("../../lib/jfunciones.php");
sesion();

$nombres=$_REQUEST['nombres'];
$id_admin1=$_REQUEST['id_admin1'];
$apellidos=$_REQUEST['apellidos'];
$cedula=$_REQUEST['cedula'];
$direccion=$_REQUEST['direccion'];
$celular=$_REQUEST['celular'];
$email=$_REQUEST['email'];
$tipo=$_REQUEST['tipo'];
$tipodeprovee=$_REQUEST['tipodeprovee']; 
$proact=$_REQUEST['proact'];

$q_vendedor="select comisionados.* from comisionados where comisionados.cedula='$cedula'";
$r_vendedor=ejecutar($q_vendedor);
$f_vendedor=asignar_a($r_vendedor);


$nombres = strtoupper($nombres);
$apellidos=strtoupper($apellidos);
$direccion=strtoupper($direccion);

/*echo $tipodeprovee;
echo $proact;*/


$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");



?>


<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<?

if($f_vendedor[cedula]==$cedula){
 ?>


<tr>		
	<td colspan=4 class="titulo_seccion">  Ya existe el vendedor <?php echo $nombres." ".$apellidos; ?></td>	
	</tr>

<?
}

else {


$q_usuario = "insert into comisionados (nombres,apellidos,cedula,fecha_creado,hora_creado,fecha_modificado,hora_modificado,direccion,celular,email,id_admin,id_tipo_comisionado,id_tipo_pro,id_act_pro) values('$nombres','$apellidos','$cedula','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$direccion','$celular','$email','$id_admin1','$tipo','$tipodeprovee','$proact')";
$r_usuario = ejecutar($q_usuario);


$q_codigo=("select comisionados.id_comisionado, comisionados.id_tipo_comisionado from comisionados where comisionados.cedula='$cedula' and comisionados.fecha_creado='$fecha_creado'");
$r_codigo=ejecutar($q_codigo);
$f_codigo=asignar_a($r_codigo);


$codigo_vendedor="$f_codigo[id_tipo_comisionado]00$f_codigo[id_comisionado]";



$q_usuario1="update comisionados set
            codigo='$codigo_vendedor' where comisionados.id_comisionado='$f_codigo[id_comisionado]';";

$r_usuario1=ejecutar($q_usuario1);

?>

	<tr>		
	<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Vendedor <?php echo $nombres." ".$apellidos;?></td>	
	</tr>	
<? }
?>
	<tr>
		<td colspan=4 class="tdcamposcc"> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
</table>




