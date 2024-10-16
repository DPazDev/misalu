<?php

/* Nombre del Archivo: guard_prop_poliza.php
   Descripción:  INSERTAR la Informacion de la Poliza asignada a un a uun Ente con Cliente ya Registrados en la base de datos, para ser utilizado posteriormente  
*/

include ("../../lib/jfunciones.php");
sesion();



	list($id_ente,$nombre)=explode("@",$_REQUEST['ente']);
	
	list($cualidad,$descripcion)=explode("@",$_REQUEST['poliza']);



	$id_organo=0;


	$fecha_creado = date("Y-m-d");
	$hora_creado = date("h:m:s");


/*echo $id_ente;
echo $cualidad;*/

$q_poliza_ente=("select polizas_entes.id_poliza, titulares.id_titular from polizas_entes,titulares where polizas_entes.id_ente='$id_ente' and titulares.id_ente='$id_ente' ");
$r_poliza_ente=ejecutar($q_poliza_ente);
	$f_poliza_ente=asignar_a($r_poliza_ente);

/*echo $f_poliza_ente[id_poliza]."--";
echo $f_poliza_ente[id_titular]."--";*/



$q_prop=("select propiedades_poliza.id_poliza,propiedades_poliza.cualidad,propiedades_poliza.id_propiedad_poliza from propiedades_poliza where propiedades_poliza.id_poliza='$f_poliza_ente[id_poliza]' and propiedades_poliza.cualidad='$cualidad' ");
$r_prop=ejecutar($q_prop);
	$f_prop=asignar_a($r_prop);



/*echo $f_prop[id_poliza]."---";
echo $f_prop[cualidad]."+++";
echo $f_prop[id_propiedad_poliza]."+++";*/

if($f_prop[id_poliza]==$f_poliza_ente[id_poliza] && $f_prop[cualidad]==$cualidad ){?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?php echo "YA TIENE PROPIEDAD POLIZA ASIGNADA"; ?></td>
	</tr>
	<tr> <td>&nbsp;</td>
	<?php  }


$q_cobert=("select coberturas_t_b.id_propiedad_poliza,coberturas_t_b.id_titular from coberturas_t_b where coberturas_t_b.id_propiedad_poliza=$f_prop[id_propiedad_poliza] and coberturas_t_b.id_titular=$f_poliza_ente[id_titular] ");
$r_cobert=ejecutar($q_cobert);
	$f_cobert=asignar_a($r_cobert);




if($f_cobert[id_propiedad_poliza]==$f_prop[id_propiedad_poliza] && $f_cobert[id_titular]==$f_poliza_ente[id_titular] ){?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?php echo "YA TIENE COBERTURA ASIGNADA"; ?></td>
	</tr>
	<tr> <td>&nbsp;</td>
	<?php  }

else {
$q_titular=("select titulares.id_titular,propiedades_poliza.id_propiedad_poliza from titulares,propiedades_poliza where titulares.id_ente='$id_ente' and propiedades_poliza.id_poliza='$f_poliza_ente[id_poliza]' and propiedades_poliza.cualidad='$cualidad' ");
$r_titular=ejecutar($q_titular);
				  while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC)){

/*echo $q_titular;
echo $f_titular[id_propiedad_poliza];
echo $f_titular[id_titular];*/

$q_cobertura="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$f_titular[id_propiedad_poliza]','$f_titular[id_titular]','0','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','0','0','0')";
	$r_cobertura=ejecutar($q_cobertura);

}


$q_benefi=("select titulares.id_titular,beneficiarios.id_beneficiario,propiedades_poliza.id_propiedad_poliza from titulares,propiedades_poliza,beneficiarios where titulares.id_ente='$id_ente' and propiedades_poliza.id_poliza='$f_poliza_ente[id_poliza]' and propiedades_poliza.cualidad='$cualidad' and beneficiarios.id_titular=titulares.id_titular ");
$r_benefi=ejecutar($q_benefi);
				  while($f_benefi=asignar_a($r_benefi,NULL,PGSQL_ASSOC)){

/*echo $q_benefi;
echo $f_benefi[id_propiedad_poliza];
echo $f_benefi[id_titular];*/

$q_cobertura="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$f_benefi[id_propiedad_poliza]','$f_benefi[id_titular]','$f_benefi[id_beneficiario]','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','0','0','0')";
	$r_cobertura=ejecutar($q_cobertura);

}?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?php echo "ASIGNADA COBERTURA..."; ?></td>
	</tr>
	<tr> <td>&nbsp;</td>
<?}

if($f_prop[id_poliza]!=$f_poliza_ente[id_poliza] && $f_prop[cualidad]!=$cualidad ){



$q_poliza="insert into propiedades_poliza 	(id_poliza,cualidad,descripcion,fecha_creado,hora_creado,fecha_modificado,hora_modificado,monto,reembolso,carta_compromiso,clave_emergencia,orden_atencion,orden_medica,servicio_general,sexo,organo,monto_nuevo)
	values	('$f_poliza_ente[id_poliza]','$cualidad','$descripcion','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','0','1','1','1','1','1','1','2','0','0')";
	$r_poliza=ejecutar($q_poliza);



$q_titular=("select titulares.id_titular,propiedades_poliza.id_propiedad_poliza from titulares,propiedades_poliza where titulares.id_ente='$id_ente' and propiedades_poliza.id_poliza='$f_poliza_ente[id_poliza]' and propiedades_poliza.cualidad='$cualidad' ");
$r_titular=ejecutar($q_titular);
				  while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC)){

/*echo $q_titular;
echo $f_titular[id_propiedad_poliza];
echo $f_titular[id_titular];*/

$q_cobertura="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$f_titular[id_propiedad_poliza]','$f_titular[id_titular]','0','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','0','0','0')";
	$r_cobertura=ejecutar($q_cobertura);

}


$q_benefi=("select titulares.id_titular,beneficiarios.id_beneficiario,propiedades_poliza.id_propiedad_poliza from titulares,propiedades_poliza,beneficiarios where titulares.id_ente='$id_ente' and propiedades_poliza.id_poliza='$f_poliza_ente[id_poliza]' and propiedades_poliza.cualidad='$cualidad' and beneficiarios.id_titular=titulares.id_titular ");
$r_benefi=ejecutar($q_benefi);
				  while($f_benefi=asignar_a($r_benefi,NULL,PGSQL_ASSOC)){

/*echo $q_benefi;
echo $f_benefi[id_propiedad_poliza];
echo $f_benefi[id_titular];*/

$q_cobertura="insert into coberturas_t_b 	(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,fecha_modificado,hora_modificado,id_organo,monto_actual,monto_previo)
	values	('$f_benefi[id_propiedad_poliza]','$f_benefi[id_titular]','$f_benefi[id_beneficiario]','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','0','0','0')";
	$r_cobertura=ejecutar($q_cobertura);

}?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?php echo "ASIGNADA PROPIEDAD POLIZA Y COBERTURA... "; ?></td>
	</tr>
	<tr> <td>&nbsp;</td>
<?}?>





