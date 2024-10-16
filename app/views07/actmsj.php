<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$userid=$_POST['eladmin'];
$msjid=$_POST['elimsj'];
$actmsj=("update messages set admin_sistema=$userid,fecha_leido='$fecha',estatus=1 where mensaje_id=$msjid;");
$repactmsj=ejecutar($actmsj);
?>
