<?php
include ("../../lib/jfunciones.php");
sesion();
$propiedadpolid=$_REQUEST['idpropiedad'];
$elmontonue=$_REQUEST['montonuevo'];
$ladescrip=strtoupper($_REQUEST['nudescri']);
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$actualpropie=("update propiedades_poliza set monto='$elmontonue',monto_nuevo='$elmontonue',descripcion='$ladescrip' where id_propiedad_poliza=$propiedadpolid");
$repactualpropie=ejecutar($actualpropie);
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado el monto de la id_propiedad_poliza No.  $propiedadpolid";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<img src="../public/images/ok.gif" alt="" width="20" height="20" />
