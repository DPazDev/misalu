<?php

/* Nombre del Archivo: reporte_permiso.php
   Descripción: Reporte de permisologia de usuarios 
*/

include ("../../lib/jfunciones.php");
sesion();

$nomb=$_REQUEST['nomb'];
$nomb = strtoupper($nomb);

echo $nomb;



?>
