<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$reciboid=$_REQUEST['elrecibo'];
$comiid=$_REQUEST['elcomi'];
$comiviejo=$_REQUEST['viejocomi'];
$actualicomi=("update tbl_recibo_contrato set id_comisionado=$comiid where id_recibo_contrato=$reciboid");
$repactualcomi=ejecutar($actualicomi);
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha cambiado el comisionado con id $comiviejo al nuevo id comisionado $comiid";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">Se ha actualizado el comisionado exitosamente!!</td>
</tr>
</table>
