<?php
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
$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,tipos_servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as procomen from entes,procesos,gastos_t_b,servicios,tipos_servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
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

</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
<?php echo "$f_proceso[servicio]  Num. $proceso" ?>
</td>
</tr>
<tr>
<td colspan=4>

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
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_proceso[id_beneficiario]");
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
DATOS DEL REEMBOLSO
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



<?php if ($si==0){
	?>
<tr>
<td colspan=1  class="datos_cliente">
DESCRIPCION
</td>
<td colspan=3  class="datos_cliente">
<?php
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
echo "$f_gastos[nombre] ($f_gastos[descripcion]), ";
}
?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
MONTO RESERVA
</td>

<td colspan=3 class="datos_cliente">
<?php echo formato_montos($monto);?> Bs.S.
</td>
</tr>
<?php
}
else
{
?>

<tr>
<td colspan=1  class="datos_cliente">
DESCRIPCION
</td>
<td colspan=3  class="datos_cliente">
SE RECIBE DOCUMENTACION DE REEMBOLSO PARA SER ANALIZADO.
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
MONTO RESERVA
</td>

<td colspan=3 class="datos_cliente">
<?php echo formato_montos($f_proceso[monto_temporal]);?> Bs.S.
</td>
</tr>

<?php
}
?>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
"HORARIO DE CAJA PARA RETIRO DE PAGOS EN EFECTIVOS DE LUNES A JUEVES DE 8:00AM A 12:00PM Y PAGOS EN CHEQUES DE LUNES A JUEVES DE 8:00AM A 12:00PM Y DE 2:00PM A 4:00PM LA CANCELACION DEL SINIESTRO SE HARA EFECTIVA AL TITULAR O EN SU DEFECTO A LA PERSONA QUE PRESENTE SU AUTORIZACION POR ESCRITO Y FOTOCOPIA DE LA CEDULA DE IDENTIDAD DEL TITULAR ADJUNTA."
</td>
</tr>
<tr>
<td colspan=4>

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

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>

</tr>
</table>


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
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
<?php echo "$f_proceso[servicio]  Num. $proceso" ?>
</td>
</tr>
<tr>
<td colspan=4>

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
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_proceso[id_beneficiario]");
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
DATOS DEL REEMBOLSO
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
<?php echo "$f_proceso[procomen]" ?>
</td>
<td colspan=1 class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">

</td>
</tr>

<?php if ($si==0){
	?>
<tr>
<td colspan=1  class="datos_cliente">
DESCRIPCION
</td>
<td colspan=3  class="datos_cliente">
<?php
 while($f_gastos1=asignar_a($r_gastos1,NULL,PGSQL_ASSOC)){
$monto1=$monto1 + $f_gastos1[monto_reserva];
echo "$f_gastos1[nombre] ($f_gastos1[descripcion]), ";
}
?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
MONTO RESERVA
</td>

<td colspan=3 class="datos_cliente">
<?php echo formato_montos($monto1);?> Bs.S.
</td>
</tr>
<?php
}
else
{
?>

<tr>
<td colspan=1  class="datos_cliente">
DESCRIPCION
</td>
<td colspan=3  class="datos_cliente">
SE RECIBE DOCUMENTACION DE REEMBOLSO PARA SER ANALIZADO.
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
MONTO RESERVA
</td>

<td colspan=3 class="datos_cliente">
<?php echo formato_montos($f_proceso[monto_temporal]);?> Bs.S.
</td>
</tr>

<?php
}
?>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
"HORARIO DE CAJA PARA RETIRO DE PAGOS EN EFECTIVOS DE LUNES A JUEVES DE 8:00AM A 12:00PM Y PAGOS EN CHEQUES DE LUNES A JUEVES DE 8:00AM A 12:00PM Y DE 2:00PM A 4:00PM LA CANCELACION DEL SINIESTRO SE HARA EFECTIVA AL TITULAR O EN SU DEFECTO A LA PERSONA QUE PRESENTE SU AUTORIZACION POR ESCRITO Y FOTOCOPIA DE LA CEDULA DE IDENTIDAD DEL TITULAR ADJUNTA."
</td>
</tr>
<tr>
<td colspan=4>
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
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>

</tr>
</table>

