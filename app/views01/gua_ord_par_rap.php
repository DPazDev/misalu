<?php
include ("../../lib/jfunciones.php");
$monto=strtoupper($_POST['monto']);
$id_proveedor=strtoupper($_POST['proveedor']);
$conexa=$_POST['conexa'];
$examen1=$_POST['examen1'];
$idexamen1=$_POST['idexamen1'];
$honorarios1=$_POST['honorarios1'];
$coment1=$_POST['coment1'];
$id_titular=$_POST['id_titular'];
$id_beneficiario=$_POST['beneficiario'];
$id_cobertura=$_POST['id_cobertura'];
list($condicion,$decrip)=explode("@",$_POST['examenes']);
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];
$fecha_privada="1900-01-01";
$codigot=time();
$codigo=$admin . $codigot;




$q_admin="select * from admin where id_admin=$admin";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$comenope="PROCESOS CARGADO POR $f_admin[nombres] $f_admin[apellidos]";

$r_proceso="insert into procesos (id_titular,
				id_beneficiario,
				id_estado_proceso,
				fecha_recibido,
				fecha_creado,
				hora_creado,
				comentarios,
				comentarios_gerente,
				comentarios_medico,
				id_admin,
				gasto_viejo,
				nu_planilla,
				codigo) 
			values ('$id_titular',
				'$id_beneficiario',
				'2',
				'$fechacreado',
				'$fechacreado',
				'$hora',
				'$comenope ',
				' ',
				' ',
				'$admin',
				'0',
				'$numpre',
				'$codigo');";
$f_proceso=ejecutar($r_proceso);

$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
$r_cproceso=ejecutar($q_cproceso);
$f_cproceso=asignar_a($r_cproceso);

$id_examen2=split("@",$idexamen1);
$examen2=split("@",$examen1);
$honorarios2=split("@",$honorarios1);
$coment2=split("@",$coment1);

$q="
begin work;
";
for($i=0;$i<=$conexa;$i++){
	$id_examen=$id_examen2[$i];
	$examen=$examen2[$i];
	$monto=$honorarios2[$i];
	$coment=$coment2[$i];
	if(!empty($id_examen) && $id_examen>0){

		$q.="insert into gastos_t_b 			
			(id_proceso,
			id_organo,
			nombre,
			descripcion,
			fecha_creado,
			hora_creado,
			comentarios,
			id_cobertura_t_b,
			enfermedad,
			id_proveedor,
			id_tipo_servicio,
			id_servicio,
			monto_reserva,
			monto_aceptado,
			monto_pagado,
			fecha_cita,
			hora_cita) 
		values ('$f_cproceso[id_proceso]',
		'0',
		'$decrip',
		'$examen',
		'$fechacreado',
		'$hora',
		'SC',
		'$id_cobertura',
		'$decrip',
		'$id_proveedor',
		'6',
		'4',
		'$monto',
		'$monto',
		'$monto ',
		'$fechacreado',
		'0');";
	}
}
$q.="
commit work;
";
$r=ejecutar($q);


	
	




$log="REGISTRO LA ORDEN NUM  $f_cproceso[id_proceso] para el particular con la descripcion de $decrip";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion">Se Registro el Proceso Num <?php echo $f_cproceso[id_proceso] ?> con Exito <a href="#" OnClick="reg_fac_ord_par();" class="boton">Facturacion</a> 
<input class="campos" type="hidden" id="proceso" name="proceso" maxlength=128 size=20 value="<?php echo $f_cproceso[id_proceso] ?>"   onkeypress="return event.keyCode!=13">
<input  type="hidden" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<input  type="hidden" size="10" id="dateField2" name="fechar" class="campos" maxlength="10" value="" > 
<input  type="hidden" size="10" id="clave" name="clave" class="campos" maxlength="10" value="" > 
<input  type="hidden" size="10" id="entes" name="entes" class="campos" maxlength="10" value="0" > 
<input  type="hidden" size="10" id="planilla" name="planilla" class="campos" maxlength="10" value="" > 
<input  type="hidden" size="10" id="sucursal" name="sucursal" class="campos" maxlength="10" value="0" > 
<input  type="hidden" size="10" id="servicios" name="servicios" class="campos" maxlength="10" value="0" > 
			<?php
			$url="'views01/iorden.php?proceso=$f_cproceso[id_proceso]&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a>
<a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>	
</tr>

</table>
<div id="reg_fac_ord_par"></div>
