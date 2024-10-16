<?php
include ("../../lib/jfunciones.php");

$formp=$_REQUEST['formp'];
$proceso=$_REQUEST['proceso'];
$monto=$_REQUEST['monto'];
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];
/* **** busco si el usuario registra factura**** */
$q_factura="select * from tbl_003 where tbl_003.id_modulo='4' and tbl_003.id_usuario='$admin'";
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);
/* **** fin  busco si el usuario registra factura**** */

$q_monto="select * from coberturas_t_b where id_cobertura_t_b='$formp'";
$r_monto=ejecutar($q_monto); 
$f_monto=asignar_a($r_monto);

$q_gastos_t_b="select procesos.id_estado_proceso,procesos.nu_planilla,gastos_t_b.* from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso'";
$r_gastos_t_b=ejecutar($q_gastos_t_b); 
$f_gastos_t_b=asignar_a($r_gastos_t_b);

if  ($f_gastos_t_b[id_cobertura_t_b]==0)
{
	$formp=0;
	}

if ($f_monto[monto_actual]<$monto)
{	
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> El Monto de la Orden es Mayor al de la Cobertura Seleccionada</td>	
</tr>	
<tr>		
<td colspan=4 class="titulo_seccion">
<a href="#" OnClick="act_cli_ord();" class="boton">Regresar a Actualizar Cobertura en Orden</a>
<a href="#" OnClick="ir_principal();" class="boton">salir</a>
</td>	
</tr>	

	
</table>
<?php 
}

else
{
/* **** Actualizo la cobertura del cliente existente y modifico el id_cobertura de sus gastos por el nuevo **** */
/* selelecciono las fecha de contrato del cliente viejo */
$q_ente="select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob,procesos.id_titular,procesos.id_beneficiario from entes,titulares,procesos where
 entes.id_ente=titulares.id_ente and 
titulares.id_titular=procesos.id_titular and
procesos.id_proceso='$proceso'";
$r_ente=ejecutar($q_ente); 
$f_ente=asignar_a($r_ente);

if ($f_ente[id_beneficiario]==0){
$fechainicio="$f_ente[fecha_inicio_contrato]";
$fechafinal="$f_ente[fecha_renovacion_contrato]";
}
else
{
$fechainicio="$f_ente[fecha_inicio_contratob]";
$fechafinal="$f_ente[fecha_renovacion_contratob]";
}

/* selecciono las id_cobertura del cliente actual */
$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso='$proceso'  group by gastos_t_b.id_cobertura_t_b";
$r_actualizar=ejecutar($q_actualizar);

/* modifico el id_cobertura con el cliente nuevo */
$mod_gastos="update gastos_t_b set id_cobertura_t_b='$formp' where gastos_t_b.id_proceso='$proceso'";
$fmod_gastos=ejecutar($mod_gastos);

$formp=$_REQUEST['formp'];

/* actualizo cobertura cliente actual */
while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

$monto_actual=0;
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
if ($f_ente[id_beneficiario]==0) 
{
	$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
	
	}
else
{

$monto_actual= $f_propiedad[monto] - $monto_gastos;

}

$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
coberturas_t_b.id_titular='$f_ente[id_titular]' and
coberturas_t_b.id_beneficiario='$f_ente[id_beneficiario]'";
$fmod_cobertura=ejecutar($mod_cobertura);

}
/* **** Actualizar la cobertura del cliente existente y elimino sus gastos **** */


/* **** Actualizo cobertura y proceso del cliente nuevo **** */


/* selecciono fecha contrato cliente nuevo */
$q_cobertura="select * from entes,titulares,coberturas_t_b where
 entes.id_ente=titulares.id_ente and 
titulares.id_titular=coberturas_t_b.id_titular and 
coberturas_t_b.id_cobertura_t_b='$formp'";
$r_cobertura=ejecutar($q_cobertura); 
$f_cobertura=asignar_a($r_cobertura);



if ($f_cobertura[id_beneficiario]==0){
$fechainicio="$f_cobertura[fecha_inicio_contrato]";
$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
}
else
{
$fechainicio="$f_cobertura[fecha_inicio_contratob]";
$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
}
/* modifico en la tabla proceso id_titular, id_beneficiario cliente nuevo por cliente actual */
$mod_proceso="update procesos set id_titular=$f_cobertura[id_titular], id_beneficiario=$f_cobertura[id_beneficiario] where procesos.id_proceso='$proceso' ";
$fmod_proceso=ejecutar($mod_proceso);


/* empiezo el procedimiento para actualizar coberturas del cliente nuevo */
$q_propiedad="select * from propiedades_poliza,coberturas_t_b 
where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and 
coberturas_t_b.id_cobertura_t_b=$formp"; 
$r_propiedad=ejecutar($q_propiedad); 
$f_propiedad=asignar_a($r_propiedad);
$monto_actual=0;
$monto_gastos=0;
$q_cgastos="select * from gastos_t_b,procesos where 
gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and 
procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and 
gastos_t_b.id_cobertura_t_b=$formp"; $r_cgastos=ejecutar($q_cgastos); 
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){ 
$monto_gastos= $monto_gastos + 
$f_cgastos[monto_aceptado];
}
if ($f_cobertura[id_beneficiario]==0) 
{
	$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
	
	}
else
{

$monto_actual= $f_propiedad[monto] - $monto_gastos;

}

$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where 
coberturas_t_b.id_cobertura_t_b='$formp' and 
coberturas_t_b.id_titular='$f_cobertura[id_titular]' and 
coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'"; 
$fmod_cobertura=ejecutar($mod_cobertura);


/* **** Fin de Actualizar la cobertura cliente nuevo**** */



/* **** Se registra lo que hizo el usuario**** */

$log="MODIFICA EL CLIENTE EN LA ORDEN CON ORDEN NUMERO $proceso";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se le Actualizo su cliente con exito <input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $proceso?>"   > <a href="#" OnClick="ir_principal();" class="boton">salir</a></td>	
</tr>	

	
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir </td>	
</tr>	

<?php
if ($f_gastos_t_b[id_estado_proceso]==13){
?>

<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton">Reembolso en Espera </a></td>
			
	</tr>


<?php

}
else
{

if ($f_gastos_t_b[id_servicio]==1 || $f_gastos_t_b[id_servicio]==10){
?>

<tr>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			</td>
	</tr>


<?php

}
else
{
	if ($f_gastos_t_b[id_tipo_servicio]==0 and ($f_gastos_t_b[id_servicio]==2 || $f_gastos_t_b[id_servicio]==3 || $f_gastos_t_b[id_servicio]==8 || $f_gastos_t_b[id_servicio]==11  || $f_gastos_t_b[id_servicio]==13 || $f_gastos_t_b[id_servicio]==10) )
	{
?>

	<tr>
		<td class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
				</td>
	</tr>
	
	<?php
	}
	else
	{
	?>
<tr>
		<td class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
	if ($f_gastos_t_b[nu_planilla]>=1){
			$url="'views01/iplanilla.php?proceso=$f_gastos_t_b[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Planilla de Emergencia </a>
			<?php
			$url="'views01/iplanilla.php?proceso=$f_gastos_t_b[nu_planilla]&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Formato de Emergencia </a><?php
			$url="'views01/ipresupuesto.php?proceso=$f_gastos_t_b[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
			<?php }
				
			if ($f_gastos_t_b[nu_planilla]>=1 and $f_gastos_t_b[id_tipo_servicio]==18){
			$url="'views01/icartaamb.php?proceso=$nu_planilla&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Carta Cirugia </a>
		<?php }
			?>
			
			
			
			</td>
	</tr>
<?php
}
}
}
if ($num_filasf>0){
?>
<tr> <td colspan=4 class="titulo_seccion"><a href="#" OnClick="reg_factura();" class="boton">Facturacion</a></td></tr>
<?php
}
?>
</table>

<?php
}
?>





