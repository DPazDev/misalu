<?php
include ("../../lib/jfunciones.php");
sesion();
$id_cobertura_t_b=$_REQUEST['id_cobertura_t_b'];
$id_cobertura=$_REQUEST['id_cobertura'];
$formp=$_REQUEST['formp'];
$monto=$_REQUEST['monto'];
$id_proveedor=$_REQUEST['id_proveedor'];
$fechacita=$_REQUEST['fechacita'];
$tipcon=$_REQUEST['tipcon'];
$proceso=$_REQUEST['proceso'];
$diagnostico=strtoupper($_REQUEST['diagnostico']);
$comenope=strtoupper($_REQUEST['comenope']);
$paso=1;
$admin= $_SESSION['id_usuario_'.empresa];
/* **** busco si el usuario registra factura**** */
$q_factura="select * from tbl_003 where tbl_003.id_modulo='4' and tbl_003.id_usuario='$admin'";
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);
/* **** fin  busco si el usuario registra factura**** */
if ($tipcon==0){
$descripcion='CONSULTA MEDICA';
}
if ($tipcon==24){
$descripcion='VALORACION PRE OPERATORIA';
}
if ($tipcon==25){
$descripcion='CONSULTA MEDICA + CITOLOGIA';
}
if ($tipcon==26){
$descripcion='CONSULTA PREVENTIVA';
}

if ($tipcon==28){
$descripcion='CONSULTA POR ESPECIALIDAD EMERGENCIA';
}
if ($tipcon==30){
$descripcion='CONSULTA POS OPERATORIA';
}
if ($tipcon==29){
$descripcion='CITOLOGIA';
}
if ($tipcon==32){
$descripcion='CONSULTA MEDICA + LECTURA DE EXAMEN';
}
if ($tipcon==31){
$descripcion='LECTURA DE EXAMEN';
}
if ($id_cobertura_t_b==0)
{
	$id_cobertura_t_b=$id_cobertura;
	}
	


/*echo $id_cobertura_t_b;
echo "****";
echo $id_cobertura;
echo "****";
echo $monto;
echo "****";
echo $id_proveedor;
echo "****";
echo $fechacita;
echo "****";
echo $tipcon;*/
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");

/*echo $fechacreado;
echo "****";*/
$q_cobertura="select * from entes,titulares,coberturas_t_b where entes.id_ente=titulares.id_ente and titulares.id_titular=coberturas_t_b.id_titular and coberturas_t_b.id_cobertura_t_b='$id_cobertura'";
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);

if ($f_cobertura[id_beneficiario]==0){
$fechainicio="$f_cobertura[fecha_inicio_contratob]";
$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
}
else
{
$fechainicio="$f_cobertura[fecha_inicio_contrato]";
$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
}

/*echo $f_cobertura[id_titular];
echo $f_cobertura[id_beneficiario];*/

$q_variables="select * from variables_globales where variables_globales.id_variable_global='$tipcon'";
$r_variables=ejecutar($q_variables);
$f_variables=asignar_a($r_variables); 

/*echo $f_variables[cantidad];*/

$q_especialidad="select 
										especialidades_medicas.id_especialidad_medica,
										especialidades_medicas.especialidad_medica,
										especialidades_medicas.monto,
										tbl_baremos_precios.precio
								from 
										especialidades_medicas,
										s_p_proveedores,
										tbl_baremos_entes,
										tbl_baremos_precios,
										proveedores 
								where 
										especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
										s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
										tbl_baremos_entes.id_ente=$f_cobertura[id_ente] and
										tbl_baremos_entes.id_baremo=tbl_baremos_precios.id_baremo and
									tbl_baremos_precios.id_especialidad_medica=especialidades_medicas.id_especialidad_medica and
										 proveedores.id_proveedor='$id_proveedor'";
$r_especialidad=ejecutar($q_especialidad);
$num_filase=num_filas($r_especialidad);

if ($num_filase==0){

					$q_especialidad="select 
														especialidades_medicas.id_especialidad_medica,
														especialidades_medicas.especialidad_medica,
														especialidades_medicas.monto
												from 
														especialidades_medicas,
														s_p_proveedores,
														proveedores 
												where 
														especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and 
														s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
														 proveedores.id_proveedor='$id_proveedor'";
					$r_especialidad=ejecutar($q_especialidad);
					$f_especialidad=asignar_a($r_especialidad); 
					$montototal=$f_especialidad[monto];
	}
	else
	{
$f_especialidad=asignar_a($r_especialidad); 
$montototal=$f_especialidad[precio];
	}

$especialidad=$f_especialidad[especialidad_medica];
/*echo $especialidad;
echo $f_especialidad[monto];
*/


/*echo $montototal;
echo "-------";*/
$admin= $_SESSION['id_usuario_'.empresa];
/*echo $admin;*/





if ($tipcon==26)
{
$mod_gastos="update gastos_t_b set id_cobertura_t_b='0',descripcion='$descripcion',monto_reserva='$montototal',monto_aceptado='$montototal',monto_pagado='$montototal',fecha_cita='$fechacita',enfermedad='$diagnostico',id_proveedor='$id_proveedor',id_tipo_servicio='5' where gastos_t_b.id_proceso=$proceso";
$fmod_gastos=ejecutar($mod_gastos); 

$q_conpre=("select * from consultas_preventivas where consultas_preventivas.id_proceso=$proceso");
$r_conpre=ejecutar($q_conpre) or mensaje(ERROR_BD);
$num_filas=num_filas($r_conpre);

if ($num_filas == 0) 
	{ 

$r_preventiva="insert into consultas_preventivas (id_titular,id_beneficiario,id_especialidad_medica,especialidad_medica,fecha_creado,hora_creado,id_proceso) 
values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','$f_especialidad[id_especialidad_medica]','$f_especialidad[especialidad_medica]','$fechacreado','$hora','$proceso');";
$f_preventiva=ejecutar($r_preventiva);
	}

}
else
{
	
	$q_monto="select * from coberturas_t_b where id_cobertura_t_b='$id_cobertura'";
$r_monto=ejecutar($q_monto); 
$f_monto=asignar_a($r_monto);

if ($f_monto[monto_actual]<$montototal)
{	
$paso=0;
	?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">El Monto de la Orden es Mayor al de la Cobertura Seleccionada
		<a href="#" OnClick="reg_cita();" class="boton">Registrar otra Cita</a>
			<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>	
</tr>	
</table>





<?php
}
else
{
	
	
	$mod_gastos="update gastos_t_b set id_cobertura_t_b='$id_cobertura',descripcion='$descripcion',monto_reserva='$montototal',monto_aceptado='$montototal',monto_pagado='$montototal',fecha_cita='$fechacita',enfermedad='$diagnostico',id_proveedor='$id_proveedor',id_tipo_servicio='7' where gastos_t_b.id_proceso=$proceso";
$fmod_gastos=ejecutar($mod_gastos); 
	
	
$r_preventiva="delete from consultas_preventivas where consultas_preventivas.id_proceso=$proceso";
$f_preventiva=ejecutar($r_preventiva);
}



$mod_proceso="update procesos set comentarios='$comenope' where procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso); 

/* **** actualizo la cobertura anterior **** */

if ($id_cobertura_t_b!=$id_cobertura)
{
	
	$q_propiedad="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza 
and coberturas_t_b.id_cobertura_t_b=$id_cobertura_t_b";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);


$q_cgastos="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura_t_b and gastos_t_b.id_tipo_servicio!=5";
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos + $f_cgastos[monto_aceptado];
}
$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;


$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where coberturas_t_b.id_cobertura_t_b='$id_cobertura_t_b' ";
$fmod_cobertura=ejecutar($mod_cobertura); 
}

/* **** actualizo la cobertura nueva **** */

$q_propiedad1="select * from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza 
and coberturas_t_b.id_cobertura_t_b=$id_cobertura";
$r_propiedad1=ejecutar($q_propiedad1);
$f_propiedad1=asignar_a($r_propiedad1);


$q_cgastos1="select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and gastos_t_b.id_cobertura_t_b=$id_cobertura and gastos_t_b.id_tipo_servicio!=5 ";
$r_cgastos1=ejecutar($q_cgastos1);
while($f_cgastos1=asignar_a($r_cgastos1,NULL,PGSQL_ASSOC)){
$monto_gastos1= $monto_gastos1 + $f_cgastos1[monto_aceptado];
}
$monto_actual1= $f_propiedad1[monto_nuevo] - $monto_gastos1;


$mod_cobertura1="update coberturas_t_b set monto_actual='$monto_actual1' where coberturas_t_b.id_cobertura_t_b='$id_cobertura' and coberturas_t_b.id_titular='$f_cobertura[id_titular]' and coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
$fmod_cobertura1=ejecutar($mod_cobertura1); 
}

if ($paso==1){
$log="ACTUALIZO LA CITA CON ORDEN NUMERO $proceso, $descripcion, ENFERMEDAD ES $diagnostico  con fecha de cita $fechacita id_proveedor $id_proveedor ";
logs($log,$ip,$admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo "$proceso $descripcion, ENFERMEDAD ES $diagnostico  con fecha de cita $fechacita" ?> se Actualizo con Exito <a href="#" OnClick="reg_cita();" class="boton">Registrar otra Cita</a>
			<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>	
</tr>	
<tr>		
<td colspan=4 class="titulo_seccion">Imprimir </td>	
</tr>	
<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/iordenb.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a><?php
			$url="'views01/iordenb.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden ente privado logo viejo  </a>
			
	</td>
	</tr>
	<?php
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

