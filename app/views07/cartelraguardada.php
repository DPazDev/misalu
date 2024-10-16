<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$fechiniaviso = $_REQUEST['ffechaini'];
$fechfinaviso = $_REQUEST['ffechafin'];
$elaviso = strtoupper($_REQUEST['faviso']);
$losdepartamentos = $_REQUEST['iddepartamento'];

	$usuarios = $_REQUEST['fusuario'];

$guardoaviso = ("insert into cartelera(id_admin,fechaini,fechafin,aviso,id_usuarios,id_departamento) 
                 values($elid,'$fechiniaviso','$fechfinaviso','$elaviso',$usuarios,$losdepartamentos);");	
                 echo $guardoaviso;
$repguaraviso = ejecutar($guardoaviso);                 
if(!$repguaraviso){
   $mensaje = "Error en proceso, No se pudo guardar el Aviso!!!";
}else{
	$mensaje = "Se ha registrado exitosamente el Aviso!!!";
	}

?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <tr>  
    <td colspan=8 class="titulo_seccion"> <?echo $mensaje; ?></td>   
   </tr> 
</table>
