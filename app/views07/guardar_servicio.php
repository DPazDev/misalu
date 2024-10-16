<?php

/* Nombre del Archivo: guardar_servicio.php
   Descripción:  GUARDAR LA MODIFICACION DE UN NUEVO SERVICIO EN UNA ORDEN ESPECIFICA, para ser utilizado posteriormente 
*/

include ("../../lib/jfunciones.php");
sesion();


$orden=$_REQUEST['orden'];
$ser=$_REQUEST['ser'];
$proser=$_REQUEST['proser'];

/*echo $orden."-----";
echo $ser."/////";
echo $proser;*/

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$q_servicio="update gastos_t_b set fecha_modificado='$fecha_creado',hora_modificado='$hora_creado', id_tipo_servicio='$proser', id_servicio='$ser' 
		where gastos_t_b.id_proceso='$orden';";

$r_servicio=ejecutar($q_servicio);

?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "MODIFICADO"; ?></td>
	</tr>

