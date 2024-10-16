<?php
include ("../../lib/jfunciones.php");


$proceso=$_REQUEST['proceso'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];



/* **** Actualizo la cobertura del cliente existente y modifico el id_cobertura de sus gastos por el nuevo **** */
/* seletecciono las fecha de contrato del cliente viejo */
$q_ente="select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob,procesos.id_titular,procesos.id_beneficiario from entes,titulares,procesos where
 entes.id_ente=titulares.id_ente and 
titulares.id_titular=procesos.id_titular and
procesos.id_proceso='$proceso'";
$r_ente=ejecutar($q_ente); 
$f_ente=asignar_a($r_ente);

if ($f_ente[id_beneficiario]==0){
$fechainicio="$f_ente[fecha_inicio_contratob]";
$fechafinal="$f_ente[fecha_renovacion_contratob]";
}
else
{
$fechainicio="$f_ente[fecha_inicio_contrato]";
$fechafinal="$f_ente[fecha_renovacion_contrato]";
}

/* selecciono las id_cobertura del cliente actual */
$q_actualizar="select gastos_t_b.id_cobertura_t_b,gastos_t_b.id_servicio,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b,gastos_t_b.id_servicio";
$r_actualizar=ejecutar($q_actualizar);
$num_filas=num_filas($r_actualizar);

if ($num_filas == 0) { 
/* modifico en la tabla proceso id_estado_proceso sin tocar el id_servicio_aux */
$mod_proceso="update procesos set id_estado_proceso=13 where procesos.id_proceso='$proceso' ";
$fmod_proceso=ejecutar($mod_proceso);
}
else
{
/* elimino gastos  */
$q_eliminar="delete from gastos_t_b where gastos_t_b.id_proceso='$proceso'";
$f_eliminar=ejecutar($q_eliminar);

/* actualizo cobertura */
while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){
$id_servicio=$f_actualizar[id_servicio];
$monto_actua=0;
$monto_gastos=0;
$q_propiedad="select * from propiedades_poliza,coberturas_t_b
where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
coberturas_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);

$q_cgastos="select * from gastos_t_b,procesos where
gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
gastos_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]"; 
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos +
$f_cgastos[monto_aceptado];
}
$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;

$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
coberturas_t_b.id_titular='$f_ente[id_titular]' and
coberturas_t_b.id_beneficiario='$f_ente[id_beneficiario]'";
$fmod_cobertura=ejecutar($mod_cobertura);

}
/* **** Actualizar la cobertura del cliente existente y elimino sus gastos **** */

/* modifico en la tabla id_estado_proceso y su id_servicio_aux */
$mod_proceso="update procesos set id_estado_proceso=13,id_servicio_aux=$id_servicio where procesos.id_proceso='$proceso' ";
$fmod_proceso=ejecutar($mod_proceso);

}


/* **** Se registra lo que hizo el usuario**** */

$log="COLOCO LA ORDEN CON ORDEN NUMERO $proceso EN ESPERA";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se Coloco en Espera <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso?>"   > </td>	
</tr>	

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
			<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>







