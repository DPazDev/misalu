<?php
include ("../../lib/jfunciones.php");
sesion();
$cdpro=$_REQUEST['pcedula'];
$nopro=strtoupper($_REQUEST['pnombre']);
$appro=strtoupper($_REQUEST['papell']);
$chnom=strtoupper($_REQUEST['pche']);
$chrif=strtoupper($_REQUEST['prif']);
$chedir=strtoupper($_REQUEST['pdir']);
$telefono=strtoupper($_REQUEST['telf']);//cel
$correo=strtoupper($_REQUEST['correo']);//correo
$peproid=$_REQUEST['pridp'];

echo $actualprove=("update personas_proveedores set nombres_prov='$nopro',apellidos_prov='$appro',cedula_prov='$cdpro',
                           celular_prov='$telefono',email_prov='$correo',nomcheque='$chnom',rifcheque='$chrif',direccioncheque='$chedir' where
                          personas_proveedores.id_persona_proveedor=$peproid");
$reactualpro=ejecutar($actualprove);                          
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado el proveedor persona con el id_persona_proveedor=$peproid";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	     <tr>
	         <td colspan=4 class="titulo_seccion">El proveedor se ha modificado exitosamente!!</td>
	     </tr>
</table>         