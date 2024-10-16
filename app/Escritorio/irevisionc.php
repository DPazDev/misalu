<?php
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */

$q_proceso=("select entes.nombre as ente,clientes.nombres,clientes.apellidos,clientes.cedula,procesos.*,servicios.*,gastos_t_b.id_servicio,gastos_t_b.id_tipo_servicio from entes,procesos,gastos_t_b,servicios,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";


$q_gastos=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso=$proceso");
$r_gastos=ejecutar($q_gastos);

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
<?php echo "$f_admin[sucursal] $fechaimpreso "?>
</td>
</tr>


<tr>
<td colspan=4 class="titulo3">
<?php echo "REVISION ADMINISTRATIVA Y MEDICA DE $f_proceso[servicio] PROCESO Num. $proceso" ?>
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
<td colspan=1 class="datos_cliente">
FECHA EMISION
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[fecha_recibido]  " ?>

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
ANALISIS TECNICO
</td>
<tr>
<td colspan=4>
<?php echo "$f_proceso[comentarios]" ?>
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
OBSERVACION MEDICA
</td>
</tr>
<tr>
<td colspan=4>
<?php echo "$f_proceso[comentarios_medico]" ?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="titulo3">
OBSERVACION ADMINISTRATIVA
</td>

</tr>

<tr>
<td colspan=4>
<?php echo "$f_proceso[comentarios_gerente]" ?>
<br>
</br>
</td>
</tr>

<?php if ($f_proceso[id_servicio]==1)
{
	?>

<tr>
<td colspan=4 class="titulo3">
Relacion de Tratamiento Continuo
</td>
</tr>
<tr>
<td class="datos_cliente">
Descripcion
</td>
<td class="datos_cliente">
Fecha Inicio
</td>
<td class="datos_cliente">
Fecha Final
</td>
<td class="datos_cliente">
Unidades
</td>
</tr>

<?php
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
	if ($f_gastos[continuo]=="on"){
 $int_nodias = floor(abs(strtotime($f_gastos[fecha_cita]) - strtotime("$f_gastos[fecha_continuo]"))/86400);
$d=$int_nodias+1;
$dd=$f_gastos[unidades] / ($d);
?>
<tr>
<td class="datos_cliente">
<?php echo "$f_gastos[nombre] $f_gastos[descripcion] Factura $f_gastos[factura]"?>
</td>
<td class="datos_cliente">
<?php echo "$f_gastos[fecha_cita] "?>
</td>
<td class="datos_cliente">
<?php echo "$f_gastos[fecha_continuo] "?>
</td>
<td class="datos_cliente">
<?php echo "dias  $d Dosis Diaria $dd Total $f_gastos[unidades] "?>

</td>
</tr>
<?php
}
}
}
?>
<tr>
<td colspan=4 class="titulo3">
<br>
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

