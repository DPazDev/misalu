<?php
include ("../../lib/jfunciones.php");

$proceso=$_POST['proceso'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** busco el usuario admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$admin' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* **** **** */

/* **** Actualizo la tabla gastos_t_b la fecha de egreso (campo fecha de cita) **** */
$mod_fpro="update gastos_t_b set fecha_cita='$fechacreado',hora_cita='$hora'  where  gastos_t_b.id_proceso=$proceso";
$fmod_fpro=ejecutar($mod_fpro);

/* **** Se registra lo que hizo el usuario**** */
$log="Modifico la fecha de egreso del proceso $proceso de gasto de servicio clinico de  emergencia con la fecha $fechacreado a las $hora ";
logs($log,$ip,$admin);
?>



