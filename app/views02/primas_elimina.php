<?
include ("../../lib/jfunciones.php");
sesion();
$idprim=$_REQUEST['laprima'];

//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$modprima=("delete from primas where id_prima=$idprim");
$repmdprima=ejecutar($modprima);      

//Guardar los datos en la tabla logs;
$mensaje="$elus, ha eliminado la prima con id_prima= $idprim";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>