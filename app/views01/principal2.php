<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$proceso=$_REQUEST['proceso'];
$fecharefi=$_REQUEST['fecharefi'];
$clave=$_REQUEST['clave'];

$fecha_egreso=$_REQUEST['fecha_egreso'];
$hora_egreso=$_REQUEST['hora_egreso'];
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** actualizo el estado del proceso a 7 candidato a pago **** */
if ($proceso>0){
	
	if ($fecharefi=="")
	{
	$fecharefi=$fechacreado;
	}
	
$mod_proceso="update 
									procesos 
							set 
									id_estado_proceso='7',
									fecha_modificado='$fechacreado',
									hora_modificado='$hora' ,
									no_clave='$clave',
									fecha_ent_pri='$fecharefi'
							where 
									procesos.nu_planilla='$proceso'";
$fmod_proceso=ejecutar($mod_proceso);

$mod_gasto="update 
									gastos_t_b 
							set 
									fecha_cita='$fecha_egreso',
									hora_cita='$hora_egreso' 
							from
									procesos
							where 
									procesos.id_proceso=gastos_t_b.id_proceso and

									procesos.nu_planilla='$proceso'";
$fmod_gasto=ejecutar($mod_gasto);
/* **** fin de actualizar el estado del proceso a 7 cndidato a pago **** */

/* **** Se registra lo que hizo el usuario**** **/


$log="ACTUALIZO LOS PROCESOS   con  numero de planilla  $proceso  le coloco el numero de clave $clave y fecha_ente_pri  $fecharefi ";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<?php

$q_ver_ingre=("select * from permisos where permisos.id_admin=$f_admin[id_admin] and permisos.id_modulo=8");
$r_ver_ingre=ejecutar($q_ver_ingre);
$f_ver_ingre=asignar_a($r_ver_ingre);

if ($f_ver_ingre[permiso]=='1'){

$q_procesos=("select 
								procesos.id_proceso, 
								procesos.nu_planilla,
								procesos.id_beneficiario,
								procesos.fecha_recibido,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								gastos_t_b.fecha_cita,
								entes.nombre,
								count(gastos_t_b.id_proceso)
						from 
								procesos,
								gastos_t_b,
								admin,
								titulares,
								entes,
								clientes
						where 
								procesos.id_proceso=gastos_t_b.id_proceso and
								gastos_t_b.id_tipo_servicio='9' and
								gastos_t_b.fecha_cita='1900-01-01' and
								procesos.id_admin=admin.id_admin and
								procesos.id_estado_proceso=2 and
								admin.id_sucursal='$f_admin[id_sucursal]' and 
								procesos.fecha_recibido>='2014-01-01' and
								procesos.id_titular=titulares.id_titular and
								titulares.id_ente=entes.id_ente and
								titulares.id_cliente=clientes.id_cliente
						group by
								procesos.id_proceso, 
								procesos.nu_planilla,
								procesos.id_beneficiario,
								procesos.fecha_recibido,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								gastos_t_b.fecha_cita,
								entes.nombre
						order by
								procesos.id_proceso");
$r_procesos=ejecutar($q_procesos);
?>
 <tr>		<td colspan=9 class="titulo_seccion"> Ingresos Por Emergencia </td>	</tr>
<tr>		
<td colspan=1 class="tdtitulos">Titular </td>	
<td colspan=1 class="tdtitulos">Cedula T </td>	
<td colspan=1 class="tdtitulos"> Beneficiario</td>	
<td colspan=1 class="tdtitulos">Cedula B </td>	
<td colspan=1 class="tdtitulos"> Ente</td>	
<td colspan=1 class="tdtitulos">Planilla </td>	
<td colspan=1 class="tdtitulos">Proceso </td>	
<td colspan=1 class="tdtitulos"> Fecha Ingreso</td>
<td colspan=1 class="tdtitulos"> </td>

</tr>
<?php
$i=0;
     while($f_procesos=asignar_a($r_procesos,NULL,PGSQL_ASSOC)){
		
		$q_bene=("select  
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula
						from 
								beneficiarios,
								clientes
						where 
								beneficiarios.id_beneficiario=$f_procesos[id_beneficiario] and
								beneficiarios.id_cliente=clientes.id_cliente
						");
$r_bene=ejecutar($q_bene);
$f_bene=asignar_a($r_bene);
$i++;
?>

<tr>		
<td colspan=1 class="tdcampos"><?php echo "$f_procesos[nombres] $f_procesos[apellidos]"?> </td>	
<td colspan=1 class="tdcampos"><?php echo $f_procesos[cedula]?> </td>	
<td colspan=1 class="tdcampos"><?php echo "$f_bene[nombres] $f_bene[apellidos]"?> </td>	
<td colspan=1 class="tdcampos"><?php echo "$f_bene[cedula]"?> </td>	
<td colspan=1 class="tdcampos"> <?php echo $f_procesos[nombre]?></td>	
<td colspan=1 class="tdcamposr">
<?php
			$url="'views01/ipresupuestop.php?proceso=$f_procesos[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Ver Registros de la Planilla" class="tdcamposr"> <?php echo $f_procesos[nu_planilla]?></a></td>	
<td colspan=1 class="tdcamposr">
<?php 
/* **** Verifico si es Asesor Medico Para Activarle la opcion de Informe Medico**** */
if ($f_admin[id_tipo_admin]==9){

?>

 <input type="hidden" size="10" id="id_proceso_<?php echo $i?>" name="id_proceso_<?php echo $i?>" class="campos" maxlength="15" value="<?php echo $f_procesos[id_proceso]?> ">
<a href="#" OnClick="inf_medico(<?php echo $f_procesos[id_proceso]?>);" class="tdcamposr" title="Realizar Informe Medico"><?php echo $f_procesos[id_proceso]?></a> 
<?php
}
else
{
echo $f_procesos[id_proceso];
}

/* **** FIN Verifico si es Asesor Medico Para Activarle la opcion de Informe Medico**** */
?>
</td>	
<td colspan=1 class="tdcamposr">
 
<?php echo $f_procesos[fecha_recibido]?> 
</td>
<td colspan=1 class="tdcamposr">
<?php 
/* **** Verifico si es Asesor Medico Para Activarle la opcion de planilla de solicitud de medicamento**** */
if ($f_admin[id_tipo_admin]==9){


			$url="'views01/isolicitudmedicamento.php?proceso=$f_procesos[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Planilla de Solicitud de Medicamentos"> PM </a>
 <?php
 }
$q_ver_info=("select * from tbl_informedico where tbl_informedico.id_proceso=$f_procesos[id_proceso]");
$r_ver_info=ejecutar($q_ver_info);
$num_filas=num_filas($r_ver_info);
if ($num_filas>0){
$f_ver_info=asignar_a($r_ver_info);

 $url="'views01/plantillainforme.php?procesoid=$f_ver_info[id_proceso]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Ver Informe Medico" class="tdcamposr"> Informe</a>
<?php
}
?></td>	
<td colspan=1 class="tdcamposr">
</td>	
</tr>
 <tr>		<td colspan=9 class="tdcamposr"> <hr> </hr> </td>	</tr>
<?php
}
}
?>



<?php

$q_ver_ingre=("select * from permisos where permisos.id_admin=$f_admin[id_admin] and permisos.id_modulo=9");
$r_ver_ingre=ejecutar($q_ver_ingre);
$f_ver_ingre=asignar_a($r_ver_ingre);

$q_p_c=("select * from permisos where permisos.id_admin='$f_admin[id_admin]' and permisos.id_modulo=11");
$r_p_c=ejecutar($q_p_c);
$f_p_c=asignar_a($r_p_c);

if ($f_ver_ingre[permiso]=='1'){

	$q_procesos=("select 
								procesos.id_proceso, 
								procesos.nu_planilla,
								procesos.id_beneficiario,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								gastos_t_b.fecha_cita,
								gastos_t_b.hora_cita,
								entes.nombre,
								count(gastos_t_b.id_proceso)
						from 
								procesos,
								gastos_t_b,
								admin,
								titulares,
								entes,
								clientes
						where 
								procesos.id_proceso=gastos_t_b.id_proceso and
								gastos_t_b.id_tipo_servicio='9' and
								gastos_t_b.fecha_cita>'1900-01-01' and
								procesos.id_admin=admin.id_admin and
								procesos.id_estado_proceso=2 and
								admin.id_sucursal='$f_admin[id_sucursal]' and 
								procesos.fecha_recibido>='2014-01-01' and
								procesos.id_titular=titulares.id_titular and
								titulares.id_ente=entes.id_ente and
								titulares.id_cliente=clientes.id_cliente
						group by
								procesos.id_proceso, 
								procesos.nu_planilla,
								procesos.id_beneficiario,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								gastos_t_b.fecha_cita,
								gastos_t_b.hora_cita,
								entes.nombre
						order by
								procesos.id_proceso");
$r_procesos=ejecutar($q_procesos);
?>
   <tr>		<td colspan=9 class="titulo_seccion">Egresos Por Emergencia </td>	</tr>
<tr>		
<td colspan=1 class="tdtitulos">Titular </td>	
<td colspan=1 class="tdtitulos">Cedula T </td>	
<td colspan=1 class="tdtitulos"> Beneficiario</td>	
<td colspan=1 class="tdtitulos"> Ente</td>	
<td colspan=1 class="tdtitulos">Planilla </td>	
<td colspan=1 class="tdtitulos">Proceso </td>	
<td colspan=1 class="tdtitulos">Fecha Egreso </td>	
</tr>
<?php
$ic=0;
     while($f_procesos=asignar_a($r_procesos,NULL,PGSQL_ASSOC)){
		$ic++;
		$q_bene=("select  
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula
						from 
								beneficiarios,
								clientes
						where 
								beneficiarios.id_beneficiario=$f_procesos[id_beneficiario] and
								beneficiarios.id_cliente=clientes.id_cliente
						");
$r_bene=ejecutar($q_bene);
$f_bene=asignar_a($r_bene);
?>

<tr>		
<td colspan=1 class="tdcampos"><?php echo "$f_procesos[nombres] $f_procesos[apellidos]"?> </td>	
<td colspan=1 class="tdcampos"><?php echo $f_procesos[cedula]?> </td>	
<td colspan=1 class="tdcampos"><?php echo "$f_bene[nombres] $f_bene[apellidos]"?> </td>	
<td colspan=1 class="tdcampos"> <?php echo $f_procesos[nombre]?></td>	
<td colspan=1 class="tdcampos"> 
<?php
			$url="'views01/ipresupuestop.php?proceso=$f_procesos[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" title="Ver Registros de la Planilla" class="tdcamposr"> <?php echo $f_procesos[nu_planilla]?></a>
</td>	
<td colspan=1 class="tdcamposr"><?php echo $f_procesos[id_proceso]?> </td>	
<td colspan=1 class="tdcamposr"> <?php echo $f_procesos[fecha_cita]?></td>	
<td colspan=1 class="tdcamposr">
</td>	
</tr>
<?php 
if ($f_p_c[permiso]==1)
{
?>
<tr>		
<td colspan=1  class="tdtitulos">Clave</td>
              	<td colspan=2 class="tdcampos"><input class="campos" type="text" id="clave<?php echo $ic?>" name="clave<?php echo $ic?>"  maxlength=128 size=20 Disabled value="<?php echo $f_procesos[no_clave]?>"   ></td>
			           
		<td class="tdtitulos">Fecha Relacion Ente Privado</td>
              	<td colspan=2 class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe<?php echo $ic?>" name="dateFieldfe<?php echo $ic?>" class="campos" maxlength="10" value="<?php echo  $f_procesos[fecha_ent_pri]?>"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe<?php echo $ic?>', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos">

<a href="#" OnClick="ir_principal2(<?php echo "'$f_procesos[nu_planilla]','$f_procesos[fecha_cita]','$f_procesos[hora_cita]','$ic'"?>);" class="boton" title="Pasar a Candidato a Pago">CP</a> </td>	
</tr>
<?php
}
?>
 <tr>		<td colspan=9 class="tdcamposr"> <hr> </hr> </td>	</tr>
<?php
}
}
?>
	
	
	
</table>


</form>
