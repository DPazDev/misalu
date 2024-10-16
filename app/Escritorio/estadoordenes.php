<?
include ("../../lib/jfunciones.php");
sesion();
$operacion=$_REQUEST['elproceso'];
$lafecha=$_REQUEST['fechac'];
$actorden=("update procesos set id_estado_proceso=7,fecha_recibido='$lafecha' where id_proceso=$operacion;");
$repactorden=ejecutar($actorden);
$actogtb=("update gastos_t_b set fecha_cita='$lafecha' where id_proceso=$operacion;");
$repactgtb=ejecutar($actogtb);
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha actualizado el proceso No.$operacion al estado proceso Candidato a Pago ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>
    <td colspan=8 class="titulo_seccion">El proceso No. <?echo $operacion?> se ha actualizado exitosamente!!</td>
   </tr>
</table>
