<?php
include ("../../lib/jfunciones.php");
sesion();
$numepresupuesto    =$_REQUEST['fpresupuesto'];
$montonapro         =$_REQUEST['fmonto'];
$cuadromedic		=strtoupper($_REQUEST['fcuadmedico']);
$ladescripcion      =strtoupper($_REQUEST['fdescripcion']);
$elcomentario       =strtoupper($_REQUEST['fcmonetario']);
$clientetipo        =$_REQUEST['ftipoclient'];
list($ente,$titular,$benefi) = explode('@',$_REQUEST['fente']);
$elservicios        =$_REQUEST['fservicio'];
$eltiposervicio     =$_REQUEST['ftiposervico'];
$cobertura			=$_REQUEST['fcobertura'];
//campos de control
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$codigot=time();
$codigo=$elid . $codigot;
//primero guardaremos el proceso


$guardaproceso = ("insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,
                                         comentarios_gerente,id_admin,nu_planilla,fecha_emision_factura,codigo) 
                                         values($titular,$benefi,2,'$fecha','$fecha','$hora','$elcomentario','$cuadromedic',$elid,
                                                '$numepresupuesto','$fecha','$codigo')");
$repguadproceso = ejecutar($guardaproceso);
$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
$r_cproceso=ejecutar($q_cproceso);
$d_cproceso=assoc_a($r_cproceso);
$elidproceso=$d_cproceso[id_proceso];

$r_gastos="insert into gastos_t_b 
(id_proceso,nombre,descripcion,fecha_creado,hora_creado,id_cobertura_t_b,enfermedad,
id_tipo_servicio,id_servicio,monto_reserva,monto_aceptado,monto_pagado,fecha_cita,hora_cita) 
values ($elidproceso,'$ladescripcion','$ladescripcion','$fecha','$hora',$cobertura,' 
-','$eltiposervicio','$elservicios','$montonapro','$montonapro','$montonapro','$fecha','$fecha');"; 
$f_gastos=ejecutar($r_gastos);

//Guardar los datos en la tabla logs;
$mensaje="$elus, ha registrado un proceso con MONTO NO APROBADO No. Proceso $elidproceso";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $elidproceso ?> se Registro con Exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>	
</tr>	
</table>
