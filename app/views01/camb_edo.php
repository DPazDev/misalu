<?php
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$cooperador=$_REQUEST['cooperador'];
$cooperador=noacento("$cooperador");
$estado_proceso=$_REQUEST['estado_proceso'];
$Enfermedad=$_GET['enfermedad'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];


/* **** modifico el edo del proceso **** */
$mod_proceso="update procesos set id_estado_proceso='$estado_proceso' where procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso); 


/**************** ACTUALIZAR CUADRO MEDICO****************/
$mod_gostos="update gastos_t_b set enfermedad='$Enfermedad' where id_proceso=$proceso ;";
$mod_gostos=ejecutar($mod_gostos); 

/* **** verifico si tiene alguna carta realizada si es asi se anula en la tabla tbl_control_cartas **** */
    $q_carta_rechazo="select * from tbl_control_cartas where tbl_control_cartas.id_proceso='$proceso'";
    $r_carta_rechazo=ejecutar($q_carta_rechazo);
    $num_filascr=num_filas($r_carta_rechazo);
    if ($num_filascr>0){
    $f_carta_rechazo=asignar_a($r_carta_rechazo);

$mod_tbl_control_car="update tbl_control_cartas set activar='1' where tbl_control_cartas.id_proceso=$proceso";
$rmod_tbl_control_car=ejecutar($mod_tbl_control_car); 
}

$log="CAMBIO EL ESTADO DE LA ORDEN CON NUMERO $proceso A $estado_proceso ";
logs($log,$ip,$admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se le Cambio su Estado con Exito <a href="#" 	OnClick="act_orden();" class="boton">Actualizar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>	
</tr>	

	
</table>

<?php

?>

