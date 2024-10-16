<?php

include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$cooperador=$_REQUEST['cooperador'];
$cooperador=strtoupper("$cooperador");

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];

$q_espera="select * from procesos where procesos.id_proceso='$proceso'";
$r_espera=ejecutar($q_espera);
$f_espera=asignar_a($r_espera);
if ($f_espera[id_estado_proceso]==13)  {

$mod_proceso="update procesos set id_estado_proceso='14',fecha_modificado='$fechacreado',hora_modificado='$hora',comentarios='$cooperador' where procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso); 
}
else
{


/* **** buscar fechas de inicio de contrato y renovacion para auditar y modificar coberturas de un cliente**** */
$q_fecha="select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob,procesos.id_titular,procesos.id_beneficiario from entes,titulares,procesos where entes.id_ente=titulares.id_ente and titulares.id_titular=procesos.id_titular and procesos.id_proceso=$proceso";
$r_fecha=ejecutar($q_fecha);
$f_fecha=asignar_a($r_fecha);

if ($f_fecha[id_beneficiario]==0){
	$fechainicio="$f_fecha[fecha_inicio_contrato]";
$fechafinal="$f_fecha[fecha_renovacion_contrato]";
	}
else
{
$fechainicio="$f_fecha[fecha_inicio_contratob]";
$fechafinal="$f_fecha[fecha_renovacion_contratob]";
}
/* **** fin buscar fechas de inicio de contrato y renovacion **** */

/* ****  busco los id_coberturas de un gasto**** */

$q_cobertura="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from gastos_t_b,coberturas_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso' group by gastos_t_b.id_cobertura_t_b";
$r_cobertura=ejecutar($q_cobertura);

/* ****  fin busco los id_coberturas de un gasto**** */

/* ****  coloco los montos en 0 ya que el proceso se esta anulando **** */
$mod_gastos="update gastos_t_b set monto_aceptado='0',monto_pagado='0' where gastos_t_b.id_proceso=$proceso";
$fmod_gastos=ejecutar($mod_gastos); 

/* ****  coloco los el estado del proceso en anulado  **** */
$mod_proceso="update procesos set id_estado_proceso='14',fecha_modificado='$fechacreado',hora_modificado='$hora',comentarios='$cooperador' where procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso); 

/* **** actualizo las coberturas  **** */

while($f_cobertura=asignar_a($r_cobertura,NULL,PGSQL_ASSOC)){
	
	$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza 
and coberturas_t_b.id_cobertura_t_b=$f_cobertura[id_cobertura_t_b]";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);

$monto_gastos=0;
$monto_actual=0;
$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$f_cobertura[id_cobertura_t_b] and gastos_t_b.id_tipo_servicio!=5";
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
}
if ($f_fecha[id_beneficiario]==0) 
{
	$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
	
	}
else
{

$monto_actual= $f_propiedad[monto] - $monto_gastos;

}


$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b=$f_cobertura[id_cobertura_t_b]";
$fmod_cobertura=ejecutar($mod_cobertura); 

}
}
$log="ANULO LA ORDEN CON NUMERO $proceso";
logs($log,$ip,$admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se Anulo con Exito </td>	
</tr>	

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>

<?php

?>

