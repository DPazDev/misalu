<?php

/* Nombre del Archivo: guard_poliza_asig.php
   Descripción:  INSERTAR la Informacion de la Poliza asignada a un Cliente ya Registrado en la base de datos, para ser utilizado posteriormente  
*/

include ("../../lib/jfunciones.php");
sesion();



	list($poliza,$id_titular,$id_beneficiario)=explode("-",$_REQUEST['selecttitu']);
	
	list($poliza1,$id_titular1,$id_beneficiario1)=explode("-",$_REQUEST['selectbeni']);

	$id_organo=0;



	$fecha_creado = date("Y-m-d");
	$hora_creado = date("h:m:s");

	/*echo $id_titular."-------";

	echo $poliza."*****";

	echo $id_beneficiario."--";

	echo $id_titular1."////";

	echo $poliza1."*****";

	echo $id_beneficiario1."--";*/

if ($poliza>0){


$q_cober=("select coberturas_t_b.id_propiedad_poliza,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario from coberturas_t_b where coberturas_t_b.id_titular='$id_titular' and coberturas_t_b.id_beneficiario=0");
$r_cober=ejecutar($q_cober);

$cont=0;
while($f_cober=asignar_a($r_cober,NULL,PGSQL_ASSOC)){

if($f_cober[id_titular]==$id_titular && $f_cober[id_beneficiario]=='0' && $f_cober[id_propiedad_poliza]==$poliza){
$cont=1;?>
	<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "YA TIENE ESA COBERTURA ASIGNADA "; ?></td>
	</tr>
	<? }

 	}
	if($cont!=1) {
	$q_monto=("select propiedades_poliza.monto from propiedades_poliza where propiedades_poliza.id_propiedad_poliza='$poliza';");
	$r_monto=ejecutar($q_monto);
	$f_monto=asignar_a($r_monto);

	$monto=$f_monto[monto];


	$q_poliza="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$poliza','$id_titular','$id_beneficiario','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$id_organo','$monto','$monto')";
	$r_poliza=ejecutar($q_poliza);





?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "ASIGNADA POLIZA"; ?></td>
	</tr>


	<?


		}}

	if ($poliza1>0){

$q_cobertu=("select coberturas_t_b.id_propiedad_poliza,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario from coberturas_t_b where coberturas_t_b.id_titular='$id_titular1' and coberturas_t_b.id_beneficiario='$id_beneficiario1'");
$r_cobertu=ejecutar($q_cobertu);

$cont1=0;
	while($f_cobertu=asignar_a($r_cobertu,NULL,PGSQL_ASSOC)){

	
	if($f_cobertu[id_titular]==$id_titular1 && $f_cobertu[id_beneficiario]==$id_beneficiario1 && $f_cobertu[id_propiedad_poliza]==$poliza1 ){
$cont1=1;?>
	<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "YA TIENE ESA COBERTURA ASIGNADA "; ?></td>
	</tr>
	<? }}
	if($cont1!=1) {


	$q_monto=("select propiedades_poliza.monto from propiedades_poliza where propiedades_poliza.id_propiedad_poliza='$poliza1';");
	
	$r_monto=ejecutar($q_monto);
	$f_monto=asignar_a($r_monto);

	$monto=$f_monto[monto];
	$q_poliza="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$poliza1','$id_titular1','$id_beneficiario1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$id_organo','$monto','$monto')";
	$r_poliza=ejecutar($q_poliza);




?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "ASIGNADA POLIZA"; ?></td>
	</tr>


	<?	}}

?>



