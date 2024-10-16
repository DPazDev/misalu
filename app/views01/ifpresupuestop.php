<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

$q_clave = "select procesos.*,subdivisiones.*,entes.* from procesos,subdivisiones,entes,titulares,titulares_subdivisiones where procesos.nu_planilla='$proceso' and procesos.id_titular=titulares.id_titular and titulares.id_titular=titulares_subdivisiones.id_titular and titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and titulares.id_ente=entes.id_ente;";
$r_clave = ejecutar($q_clave);
$f_clave = asignar_a($r_clave);


/* **** busco las citas medicas *** */

$q_proceso=("select subdivisiones.subdivision,entes.nombre as ente,clientes.*,procesos.*,servicios.*,tipos_servicios.*,gastos_t_b.* from entes,procesos,gastos_t_b,servicios,tipos_servicios,clientes,titulares,subdivisiones,titulares_subdivisiones where procesos.nu_planilla='$proceso' and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and titulares.id_titular=titulares_subdivisiones.id_titular and titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and procesos.id_estado_proceso<>14");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";


$q_gastos=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.nu_planilla='$proceso' and procesos.id_estado_proceso<>14 and gastos_t_b.id_tipo_servicio<>27
and gastos_t_b.id_tipo_servicio<>28");
$r_gastos=ejecutar($q_gastos);


$montosnoaprobado=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and 
                                                     procesos.nu_planilla='$proceso' and procesos.id_estado_proceso<>14 and
                                                     gastos_t_b.id_tipo_servicio=27 and 
                                                     gastos_t_b.id_servicio=4");

$r_gastosnoapro=ejecutar($montosnoaprobado);
$datmontonoapo=assoc_a($r_gastosnoapro);
$elmontonoaprobado=$datmontonoapo[monto_reserva];



$q_proveedor=("select * from proveedores where proveedores.id_proveedor='$f_proceso[id_proveedor]'");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);

if  ($f_proveedor[id_s_p_proveedor]>0){
	$q_proveedorp=("select personas_proveedores.*,s_p_proveedores.* from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor='$f_proveedor[id_proveedor]'");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
	}
	else
	{
		$q_proveedorp=("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor='$f_proveedor[id_proveedor]'");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
		}


if  ($si==1){
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
<?php echo "$f_admin[sucursal] $f_proceso[fecha_recibido] "?>
</td>
</tr>


<tr>
<td colspan=4 class="titulo3">
<?php echo "$f_proceso[servicio] FINIQUITO Num. $proceso Clave: $f_proceso[no_clave]" ?>
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
<?php echo formato_montos($f_gastos[monto_aceptado]);?>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan=2  class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">
MONTO NO APROBADO Bs.S.
</td>
<td colspan=1 class="datos_cliente">
<?php echo montos_print($elmontonoaprobado) ?> Bs.S. 
</td>

</tr>
<tr>
<td colspan=2  class="datos_cliente">

</td>
<td colspan=1 class="datos_cliente">
Total
</td>
<td colspan=1 class="datos_cliente">
<?php echo formato_montos($monto - $elmontonoaprobado) ?> Bs.S. 
</td>

</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">&nbsp;&nbsp;HE RECIBIDO DE <?php echo $_REQUEST[ente]; ?>, POR CONCEPTOS ARRIBA MENCIONADOS, LA CANTIDAD DE: <?php echo formato_montos($monto); ?> Bs. <?php echo numeros_a_letras($monto); ?>. 
	<!--No teniendo por lo tanto nada mas que reclamar por concepto de los gastos incurridos y amparados por la reclamacion referencia.--></td>
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

</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
APROBADO POR
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
________________________________________
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
RECIBI CONFORME 
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo $_REQUEST[ente]; ?>
</td>

</tr>
<tr>
		<td colspan=4 class="datos_cliente">
		 <?php echo $f_clave[direccion]; ?>
		</td>
		</tr>
	<tr>
		<td colspan=4 class="datos_cliente">
		 RIF: <?php echo $f_clave[rif]; ?>
		</td>
	</tr>
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
SERVICIO DE ADMINISTRACION DE SALUD
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

