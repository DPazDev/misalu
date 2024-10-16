<?php

/* Nombre del Archivo: guardar_pregun_seg.php
   Descripción: guardar las preguntas de seguridad en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$pregunta1 = $_REQUEST['pregunta1'];
$respuesta1 = $_REQUEST['respuesta1'];

$pregunta2 = $_REQUEST['pregunta2'];
$respuesta2 = $_REQUEST['respuesta2'];

$pregunta3 = $_REQUEST['pregunta3'];
$respuesta3 = $_REQUEST['respuesta3'];

$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

/*$respuesta1 = strtoupper($respuesta1);
$respuesta2 = strtoupper($respuesta2);
$respuesta3 = strtoupper($respuesta3);*/
/*
echo "******************* <br>";
echo $pregunta1."<br>"; 
echo "******************* <br>";
echo "******************* <br>";
echo $respuesta1."<br>"; 
echo "******************* <br>";


echo "******************* <br>";
echo $elid."<br>"; 
echo "******************* <br>";

*/

$q_respuesta1 = "insert into tbl_respuestas (id_admin,id_pregunta,respuesta,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$elid','$pregunta1','$respuesta1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_respuesta1 = ejecutar($q_respuesta1);


$q_respuesta2 = "insert into tbl_respuestas (id_admin,id_pregunta,respuesta,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$elid','$pregunta2','$respuesta2','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_respuesta2 = ejecutar($q_respuesta2);

$q_respuesta3 = "insert into tbl_respuestas (id_admin,id_pregunta,respuesta,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$elid','$pregunta3','$respuesta3','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_respuesta3 = ejecutar($q_respuesta3);


/*echo $q_respuesta;*/
?>


<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "PREGUNTAS ASIGNADAS"; ?></td>
	</tr>
<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="mod_pass()" class="boton">Modificar Password</a> </td>
	</tr>




