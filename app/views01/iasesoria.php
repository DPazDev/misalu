<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */
if ($si==0){
$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as procomen from entes,procesos,gastos_t_b,servicios,tipos_servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$num_filas=num_filas($r_proceso);

if ($num_filas==0){
	
	$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as procomen from entes,procesos,gastos_t_b,servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio and servicios.id_servicio=10 and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
	}
	else
		
		{


$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
}





list($ano,$mes,$dia)=split("-",$f_proceso[fecha_recibido],3);
list($ano1,$mes1,$dia1)=split("-",$f_proceso[fecha_cita],3);


$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos=ejecutar($q_gastos);
$q_gastos1=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos1=ejecutar($q_gastos1);
}
else
{
	$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as procomen from entes,procesos,servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and procesos.id_servicio_aux=servicios.id_servicio and  titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
list($ano,$mes,$dia)=split("-",$f_proceso[fecha_recibido],3);
list($ano1,$mes1,$dia1)=split("-",$f_proceso[fecha_cita],3);


	
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
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>

<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
<?php echo "ASESORIA MEDICA DE RECECPCION DE $f_proceso[servicio]  Num. $proceso" ?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
DATOS DEL CLIENTE
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
TITULAR
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[nombres] $f_proceso[apellidos] " ?>

</td>
<td colspan=1 class="datos_cliente">
CEDULA
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[cedula] " ?>

</td>
</tr>
<?php 
if ($f_proceso[id_beneficiario]>0 )
{
	$q_beneficiario=("
		SELECT
			*
		FROM
			clientes
		JOIN beneficiarios ON clientes.id_cliente = beneficiarios.id_cliente
		WHERE
			beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]'");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
$nombre_cliente="$f_beneficiario[nombres]";
$apellido_cliente="$f_beneficiario[apellidos]";
	
?>
<tr>
<td colspan=1 class="datos_cliente">
BENEFICIARIO
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos] " ?>

</td>
<td colspan=1 class="datos_cliente">
CEDULA
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_beneficiario[cedula] " ?>

</td>
</tr>
<?php
}
?>
<tr>
<td colspan=1 class="datos_cliente">
ENTE
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[ente]  " ?>

</td>

</tr>

<tr>
<td colspan=4 class="titulo3">
DATOS DE LA RECEPCION
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
FECHA SOLICITUD
</td>
<td colspan=1 class="datos_cliente">
<?php echo " $dia-$mes-$ano  " ?>

</td>
<td colspan=1 class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">

</td>
</tr>

<tr>
<td colspan=1 class="datos_cliente">
COMENTARIOS
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[procomen]";?>
</td>
<td colspan=1 class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">

</td>
</tr>



<tr>
<td colspan=1  class="datos_cliente">
DESCRIPCION
</td>
<td colspan=3  class="datos_cliente">
SE RECIBE DOCUMENTACION  PARA SER ANALIZADO.
</td>
</tr>
<tr>
<td colspan=4>
<hr></hr>
</td>
</tr>
<tr>
<td colspan=1  class="datos_cliente">
DIAGNOSTICO
</td>
<td colspan=3  class="datos_cliente">
<?php echo "$f_proceso[enfermedad]";?>
</td>
</tr>
<tr>
<td colspan=1  class="datos_cliente">
ESTUDIOS SOLICITADOS
</td>
<td colspan=3  class="datos_cliente"><?php
echo "$f_proveedorp[especialidad_medica] ";
if ($f_proceso[id_tipo_servicio]==6)
{
	echo "$f_proceso[nombre]  ";
	}
 while($f_gastos1=asignar_a($r_gastos1,NULL,PGSQL_ASSOC)){
$monto1=$monto1 + $f_gastos1[monto_reserva];
echo "$f_gastos1[descripcion] $f_gastos1[comentarios], ";
}
?>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
OBSERVACION MEDICA
</td>
</tr>
<tr>
<td colspan=4>
<hr></hr>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4>
<hr></hr>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
OBSERVACION GERENCIA OPERATIVA
</td>
</tr>
<tr>
<td colspan=4>
<hr></hr>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4>
<hr></hr>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
SE LLAMO EL DIA
</td>
<td colspan=1  class="titulo3">
HORA:
</td>
<td colspan=2 class="titulo3">
ATENDIO
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
__________________
</td>
<td colspan=1  class="titulo3">
__________________
</td>
<td colspan=2 class="titulo3">
__________________
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
ELABORADO POR:
<?php echo "$f_proceso[adnom] $f_proceso[adapell]" ?>
</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
RECIBI CONFORME CONSTANCIA DE RECEPCION
<? echo "$nombre_cliente $apellido_cliente";?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>

</table>
<br>
</br>


<?php 
/* **** modificar el procesos y gastos de este proceso si es donativo **** */ 
$mod_prodon="update procesos set id_estado_proceso=4 where 
procesos.id_proceso=$proceso"; 
$fmod_prodon=ejecutar($mod_prodon);
/* **** modificar el procesos  si es donativo **** */ 
/* **** Se registra lo que hizo el usuario**** */
$admin= $_SESSION['id_usuario_'.empresa];
$log="COLOCO LA ORDEN NUMERO $proceso EN ESTADO DE RECEPCION MEDICA";
logs($log,$ip,$admin);
/* **** Fin de lo que hizo el usuario **** */
?>
