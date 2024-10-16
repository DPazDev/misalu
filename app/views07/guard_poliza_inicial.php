<?php

/* Nombre del Archivo: guard_poliza_asig.php
   Descripción:  INSERTAR la Informacion de la Poliza asignada a un Cliente ya Registrado en la base de datos, para ser utilizado posteriormente  
*/

include ("../../lib/jfunciones.php");
sesion();



	list($ente,$id_titular,$id_beneficiario)=explode("-",$_REQUEST['selecttitu']);
	
	list($ente1,$id_titular1,$id_beneficiario1)=explode("-",$_REQUEST['selectbeni']);

	list($id_poliza,$nombre_poliza)=explode("@",$_REQUEST['polizass']);

	$id_organo=0;


	$fecha_creado = date("Y-m-d");
	$hora_creado = date("h:m:s");

$pro="";
$monto="";

if($ente>0){


	$poli_titular = "SELECT id_poliza FROM titulares_polizas WHERE id_titular='$id_titular'";
	$r_poli_titular = ejecutar($poli_titular);
	
	// Inicializar el array para almacenar los id_poliza
	$polizas_titular = [];
	
	// Procesar los resultados de la consulta
	while ($poliza = asignar_a($r_poli_titular)) {
		$polizas_titular[] = $poliza['id_poliza'];
	}

if(in_array($id_poliza, $polizas_titular)){?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "YA TIENE COBERTURA ASIGNADA TITULAR $id_titular"; ?></td>
	</tr>
	<? }
else  {

	$q_cobertura=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.monto_nuevo  from propiedades_poliza where 
	propiedades_poliza.id_poliza='$id_poliza';");//buscar en propiedades poliza solo con el id poliza q traigo de bus poliza
	$r_cobertura=ejecutar($q_cobertura);
				  while($f_cobertura=asignar_a($r_cobertura,NULL,PGSQL_ASSOC)){?>
	<tr> <td>&nbsp;</td>	</tr>

<?
	$pro=$f_cobertura[id_propiedad_poliza];
	$monto=$f_cobertura[monto_nuevo];



	$q_poliza="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$pro','$id_titular','$id_beneficiario','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$id_organo','$monto','$monto')";
	$r_poliza=ejecutar($q_poliza);
}



$q_titu_poli="insert into titulares_polizas
(id_titular,id_poliza,fecha_creado,hora_creado,fecha_modificado,hora_modificado)
values
('$id_titular','$id_poliza','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')" ;
$r_titu_poli=ejecutar($q_titu_poli);

?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "ASIGNADA COBERTURA"; ?></td>
	</tr>
<?}
}


if($ente1>0){

$q_cobertu=("select coberturas_t_b.id_propiedad_poliza,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario from coberturas_t_b where coberturas_t_b.id_titular='$id_titular1' and coberturas_t_b.id_beneficiario='$id_beneficiario1'");
$r_cobertu=ejecutar($q_cobertu);
	$f_cobertu=asignar_a($r_cobertu);

	
	if($f_cobertu[id_titular]==$id_titular1 && $f_cobertu[id_beneficiario]==$id_beneficiario1 ){?>

	<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "YA TIENE COBERTURA ASIGNADA BENEFICIARIO"; ?></td>
	</tr>
	<? }
else  {

	$q_cobertura=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.monto_nuevo  from propiedades_poliza where 
	propiedades_poliza.id_poliza='$id_poliza';");
	$r_cobertura=ejecutar($q_cobertura);
				  while($f_cobertura=asignar_a($r_cobertura,NULL,PGSQL_ASSOC)){?>
	<tr> <td>&nbsp;</td>	</tr>

<?
	$pro=$f_cobertura[id_propiedad_poliza];
	$monto=$f_cobertura[monto_nuevo];



	$q_poliza="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$pro','$id_titular1','$id_beneficiario1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$id_organo','$monto','$monto')";
	$r_poliza=ejecutar($q_poliza);
}
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "ASIGNADA COBERTURA"; ?></td>
	</tr>
<?}

}


