<?php
include("../lib/jfunciones.php");
sesion();

$login=$_SESSION['login_usuario_'.empresa];
$id=$_SESSION['id_usuario_'.empresa];
$_SESSION=array();
session_destroy();

$log=noacento($login.", cerró sesión.");
logs($log,$ip,$id);

die(header("location: index.php"));

?>
