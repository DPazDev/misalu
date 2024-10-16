<?php
include ("../../lib/jfunciones.php");
sesion();
$activi=$_REQUEST['estatupoli'];
$politipo=$_REQUEST['eltipopoli'];
$elnombre=strtoupper($_REQUEST['nuevnombre']);
$interme=$_REQUEST['esinterme'];
$idpoliza=$_REQUEST['poliid'];
$id_moneda=$_REQUEST['nuevmoneda'];
$descripci=strtoupper($_REQUEST['ladescrip']);
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$actualpropie=("update polizas set nombre_poliza='$elnombre',intermediario=$interme,activar=$activi,particular=$politipo,descripcion='$descripci',id_moneda='$id_moneda' where id_poliza=$idpoliza;");
$repactualpropie=ejecutar($actualpropie);
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado el nombre a la poliza con id_No.  $idpoliza";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Poliza actualizada!!!</td>  
     </tr>
</table>
