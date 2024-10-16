<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_date=("select current_date;");
$r_date=ejecutar($q_date);
$f_date=asignar_a($r_date);

$q_hora=("select current_time;");
$r_hora=ejecutar($q_hora);
$f_hora=asignar_a($r_hora);

$si=$_REQUEST['si'];
$proceso=$_REQUEST['proceso'];
/* **** Verifico si viene la informacion despues del formulario de guardar o actualizar orden **** */
if ($si==1)
{
	/* ****  viene de guardar o actualizar orden paso a buscar los datos para la planilla **** */
	$q_proceso=("select 
									subdivisiones.subdivision,
									entes.nombre as ente,
									clientes.nombres,
									clientes.apellidos,
									clientes.cedula,
									clientes.fecha_nacimiento,
									procesos.id_proceso,
									procesos.fecha_recibido,
									procesos.comentarios,
									procesos.no_clave,
									procesos.id_beneficiario,
									servicios.*,
									tipos_servicios.*
						from 
									entes,
									procesos,
									gastos_t_b,
									servicios,
									tipos_servicios,
									subdivisiones,
									titulares,
									clientes,
									titulares_subdivisiones 
						where 
									procesos.nu_planilla='$proceso' and 
									gastos_t_b.id_proceso=procesos.id_proceso and 
									gastos_t_b.id_servicio=servicios.id_servicio and 
									gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and
									titulares.id_titular=procesos.id_titular and 
									titulares.id_cliente=clientes.id_cliente and 
									titulares.id_ente=entes.id_ente and 
									titulares.id_titular=titulares_subdivisiones.id_titular and 
									titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and 
									procesos.id_estado_proceso<>14");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nu_planilla=$_REQUEST['proceso'];
$nombret="$f_proceso[nombres] $f_proceso[apellidos]";
$cedulat=$f_proceso[cedula];
$edadt=calcular_edad($f_proceso['fecha_nacimiento']);
$ente=$f_proceso['ente'];
$comentarios=$f_proceso['comentarios'];
$no_clave=$f_proceso['no_clave'];
list($ano,$mes,$dia)=explode("-",$f_proceso['fecha_recibido']);
$fecha_recibido="$dia-$mes-$ano";
$fechaimpreso=date("d-m-Y");
if ($f_proceso[id_beneficiario]>0 )
{
	$id_beneficiario=$f_proceso[id_beneficiario];
	$q_beneficiario=("select 
										* 
								from 
										clientes,
										beneficiarios,
										parentesco
								where 
										clientes.id_cliente=beneficiarios.id_cliente and 
										beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]' and
										beneficiarios.id_parentesco=parentesco.id_parentesco");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
$nombreb="$f_beneficiario[nombres] $f_beneficiario[apellidos]";
$cedulab=$f_beneficiario[cedula];
$edadb=calcular_edad($f_beneficiario['fecha_nacimiento']);
$parentesco=$f_beneficiario['parentesco'];
}
	
	}
	/* ****  fin de viene de guardar o actualizar orden paso a buscar los datos para la planilla **** */
	else
	{
/* ****  coloco  los datos para la planilla que viene de ver una orden **** */
$nu_planilla=$_REQUEST['nu_planilla'];
$numproceso=$_REQUEST['numproceso'];
$nombret=$_REQUEST['nombret'];
$cedulat=$_REQUEST['cedulat'];
$edadt=$_REQUEST['edadt'];
$nombreb=$_REQUEST['nombreb'];
$cedulab=$_REQUEST['cedulab'];
$edadb=$_REQUEST['edadb'];
$ente=$_REQUEST['ente'];
$parentesco=$_REQUEST['parentesco'];
$comentarios=$_REQUEST['comentarios'];
list($ano,$mes,$dia)=explode("-",$_REQUEST['fecha_ingreso']);
$fecha_recibido="$dia-$mes-$ano";
$fechaimpreso=date("d-m-Y");
}
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">

</tr>

<tr>
<td colspan=1 class="titulo2">
</td>
</td>
<td colspan=5 class="titulo1">
<?php echo "$f_admin[sucursal] fecha de Impresion $fechaimpreso "?>
</td>
</tr>



<tr>
<td colspan=6 class="titulo3"> PLANILLA DE INGRESO Y SOLICITUD DE CLAVE 

</td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <?php echo "Fecha Ingreso $fecha_recibido Hora: $f_hora[timetz]";?>
</td>
</tr>
<tr>
<td colspan=6>
</td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>

<tr>
<td class="datos_cliente">Numero Planilla: </td>
<td class="datos_cliente">&nbsp;&nbsp;<?php echo $nu_planilla?></td>
<td class="datos_cliente">Proceso:  </td>
<td class="datos_cliente">&nbsp;&nbsp;<?php echo "$f_proceso[id_proceso] $numproceso";?></td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">Contratante</td>
<td colspan=3 class="datos_cliente"><?php echo "$ente " ?></td>
</tr>

<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td class="datos_cliente">Titular</td>
<td class="datos_cliente"><?php echo $nombret?> </td>
<td class="datos_cliente">Cedula</td>
<td class="datos_cliente"><?php echo $cedulat?> </td>
<td class="datos_cliente">Edad&nbsp;&nbsp;<?php echo $edadt?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tel&eacute;f:</td>
<td class="datos_cliente"> </td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td class="datos_cliente">Beneficiario</td>
<td class="datos_cliente"><?php echo $nombreb?></td>
<td class="datos_cliente">Cedula </td>
<td class="datos_cliente"><?php echo $cedulab?></td>
<td class="datos_cliente">Edad&nbsp;&nbsp;<?php echo $edadb?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Parentesco:&nbsp;&nbsp;&nbsp;<?php echo $parentesco;?> </td>
<td class="datos_cliente"></td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<td class="datos_cliente">TA:</td>
<td class="datos_cliente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="datos_cliente">Pulso:</td>
<td class="datos_cliente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="datos_cliente">Temperatura:</td>
<td class="datos_cliente">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td class="datos_cliente">Poliza verificada por:</td>
<td class="datos_cliente"></td>
<td class="datos_cliente"></td>
<td class="datos_cliente"></td>
<td class="datos_cliente">No. Clave: </td>
<td class="datos_cliente"></td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<tr>
<td class="datos_cliente">Cobertura Final </td>
<td class="datos_cliente"></td>
<td class="datos_cliente"> </td>
<td class="datos_cliente"></td>
<td class="datos_cliente">Operador que otorga clave:</td>
<td class="datos_cliente"></td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td class="datos_cliente">Analista</td>
<td class="datos_cliente" colspan=5> <?php echo "$f_admin[nombres] $f_admin[apellidos]";?></td>
</tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td class="datos_cliente">Observacion </td>
<td class="datos_cliente" colspan=5></td>
</tr>

<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>

<td class="datos_cliente" colspan=6>.</td>
</tr>
<tr>
<tr>
<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<td class="datos_cliente" colspan=6>.</td>
</tr>
<tr>

<td colspan=6 class="titulo3"> <hr></hr></td>
</tr>
<tr>
<td colspan=3 class="datos_cliente"> <textarea class="campos"  name="cooperador" cols=40 rows=10>
Copia de Cedula Titular
</textarea> </td>
<td colspan=3 class="datos_cliente" > <textarea class="campos"  name="cooperador" cols=40 rows=10>
Copia de Cedula Beneficiario
</textarea> </td>
</tr>
<tr>
<td colspan=3 class="datos_cliente"> <textarea class="campos"  name="cooperador" cols=40 rows=10>
Copia del Carnet
</textarea> </td>
<td colspan=3 class="datos_cliente" >
<textarea class="campos"  name="cooperador" cols=40 rows=8>
Copia del Carnet Beneficiario
</textarea> 

	
	</td>
</tr>
<tr>
<td class="datos_cliente"> </td>
<td class="datos_cliente"> </td>
</tr>
<tr>
<td class="datos_cliente"> </td>
<td class="datos_cliente"> </td>
</tr>

<tr>

<td class="datos_cliente"> </td>
<td class="datos_cliente"> </td>
<td class="datos_cliente"> </td>
<td class="datos_cliente"><br><br><br><br><br><br> Firma del Usuario</td>
</tr>
</table>



