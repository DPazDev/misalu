<?
include ("../../lib/jfunciones.php");
sesion();
$nuevpropiedap=strtoupper($_POST['nombrenuevapoliz']);
$_SESSION['nuevapropiedadpo']=$nuevpropiedap;
?>