<?
include ("../../lib/jfunciones.php");
sesion();
$idprim=$_REQUEST['elpri'];
$edad1=$_REQUEST['laeda1'];
$edad2=$_REQUEST['laeda2'];
$parent=$_REQUEST['elparen'];
$montan=$_REQUEST['elma'];
$montse=$_REQUEST['elms'];
$monttr=$_REQUEST['elmt'];
$montm=$_REQUEST['elmm'];
$coment=strtoupper($_REQUEST['elcom']);
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$modprima=("update primas set id_parentesco=$parent,descripcion='$coment',anual='$montan',semestral='$montse',trimestral='$monttr',mensual='$montm',fecha_modificado='$fecha',edad_inicio=$edad1,edad_fin=$edad2 
                        where id_prima=$idprim");
$repmdprima=ejecutar($modprima);      

//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado la prima con id_prima= $idprim";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>