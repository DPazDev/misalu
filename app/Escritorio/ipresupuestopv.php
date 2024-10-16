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

$q_proceso=("select subdivisiones.subdivision,entes.nombre as ente,clientes.*,procesos.*,servicios.*,tipos_servicios.*,gastos_t_b.* from entes,procesos,gastos_t_b,servicios,tipos_servicios,clientes,titulares,subdivisiones where procesos.nu_planilla='$proceso' and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and titulares.id_titular=titulares_subdivisiones.id_titular and titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and procesos.id_estado_proceso<>14");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";


$q_gastos=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.nu_planilla='$proceso' and procesos.id_estado_proceso<>14");
$r_gastos=ejecutar($q_gastos);




if  ($si==1){
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head1.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-29605724-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $f_proceso[fecha_recibido] "?>
</td>
</tr>


<tr>
<td colspan=4 class="titulo3">
<?php echo "$f_proceso[servicio] PRESUPUESTO Num. $proceso Clave: $f_proceso[no_clave]" ?>
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
DATOS DE LA EMPRESA
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
NOMBRE  O RAZON SOCIAL: CLINISALUD MEDICINA PREPAGADA S.A.
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
DOMICILIO FISCAL: <?php echo DIRECCION_FISCAL; ?>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
TELEFONOS: (0274) 2459101-2459229 FAX: (0274) 2459285
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
DATOS DEL PACIENTE
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
TITULAR:
<?php echo "$f_proceso[nombres] $f_proceso[apellidos] " ?>
C.I.: <?php echo "$f_proceso[cedula] " ?>
EDAD: <?php echo calcular_edad($f_proceso[fecha_nacimiento]) ?> 
ENTE: <?php echo "$f_proceso[ente]  ($f_proceso[subdivision])" ?>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
DIRECCION: <?php echo "$f_proceso[direccion_hab]  TLF. $f_proceso[telefono_hab]" ?>

</td>
</tr>
<?php 
if ($f_proceso[id_beneficiario]>0 )
{
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]'");
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
<td colspan=4>
<br>
</br>
</td>
</tr>


<tr>
<td colspan=4  class="datos_cliente">
DIAGNOSTICO: <?php echo "$f_proceso[enfermedad]";?>
</td>

</tr>
<tr>
<td colspan=4 class="titulo3">
GASTOS MEDICOS
</td>
<tr>
<tr>
<td colspan=2  class="datos_cliente">
CONCEPTO
</td>
<td colspan=1 class="datos_cliente">
CANTIDAD
</td>
<td colspan=1 class="datos_cliente">
BS.
</td>

</tr>


<?php
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_aceptado];

?>
<tr>
<td colspan=2  class="datos_cliente">
<?php echo "PROCESO: $f_gastos[id_proceso] ($f_gastos[nombre] $f_gastos[descripcion])";?>
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_gastos[unidades]";?>
</td>
<td colspan=1 class="datos_cliente">
<?php echo montos_print($f_gastos[monto_aceptado]);?>
</td>
</tr>
<?php
}
?>

<tr>
<td colspan=2  class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">
Total
</td>
<td colspan=1 class="datos_cliente">
<?php echo montos_print($monto) ?> BS. 
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
<td colspan=2  class="titulo3">

</td>
<td colspan=1 class="titulo3">

</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
FIRMA Y SELLO
</td>
<td colspan=2 class="titulo3">

</td>
<td colspan=1 class="titulo3">

</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_MERIDA; ?>
</td>
</tr>
	
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>
</tr>
</table>
<?php
}
else
{
	?>
	<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table    class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
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
<?php echo "Fecha: $f_proceso[fecha_recibido] Hora: $f_proceso[hora_cita]"?>
</td>
</tr>


<tr>
<td colspan=4 class="titulo3">
<?php echo "SOLICITUD DE PROCEDIMIENTO $f_proceso[servicio] PLANILLA Num. $proceso" ?>
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
DATOS DEL PACIENTE
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
TITULAR:
<?php echo "$f_proceso[nombres] $f_proceso[apellidos] " ?>
C.I.: <?php echo "$f_proceso[cedula] " ?>
EDAD: <?php echo calcular_edad($f_proceso[fecha_nacimiento]) ?> 
ENTE: <?php echo "$f_proceso[ente]  " ?>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
DIRECCION: <?php echo "$f_proceso[direccion_hab]  TLF. $f_proceso[telefono_hab]" ?>

</td>
</tr>
<?php 
if ($f_proceso[id_beneficiario]>0 )
{
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]'");
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
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
ENFERMERIA
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
TA:_______________/______________                            
</td>
<td colspan=1 class="datos_cliente">
P:________________
</td>
<td colspan=1 class="datos_cliente">
T:_________________
</td>
<td colspan=1 class="datos_cliente">
PESO:_____________
</td>
</tr>
<tr>
<td colspan=4 class="titulo3" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
INFORME MEDICO
</td>
<tr>
<td colspan=4 style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
<br>
</br>
</td>
</tr>
</tr>
<tr>
<td colspan=4 style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
DIAGNOSTICO:
</td>

</tr>
<tr>
<td colspan=4 class="titulo3" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
GASTOS MEDICOS
</td>
<tr>
<tr>
<td colspan=2  class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
CONCEPTO
</td>
<td colspan=1 class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
CANTIDAD
</td>
<td colspan=1 class="datos_cliente" style="fborder-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
BS.
</td>

</tr>


<?php
	for( $i=0; $i<20; $i++){
?>
<tr>
<td colspan=2  class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">
.
</td>
<td  colspan=1 class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">

</td>
<td  colspan=1 class="datos_cliente" style="border-left: thin solid black;border-right:  
                        thin solid black;border-bottom: thin solid black;">

</td>
</tr>
<?php
}
?>

<tr>
<td colspan=2  class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">
Total
</td>
<td colspan=1 class="datos_cliente">
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
NOMBRE, FIRMA Y SELLO DEL MEDICO
</td>
<td colspan=1 class="titulo3">
FIRMA DEL PACIENTE
</td>
<td colspan=2 class="titulo3">
COBERTURA AUTORIZADA POR
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
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
	
	
	
	
	<?php
	}
	?>

