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



/* **** busco las citas medicas *** */

$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as comen from entes,procesos,gastos_t_b,servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
$cedula="$f_proceso[cedula]";




$q_clave = "select procesos.*,subdivisiones.*,entes.* from procesos,subdivisiones,entes,titulares,titulares_subdivisiones where procesos.id_proceso='$proceso' and procesos.id_titular=titulares.id_titular and titulares.id_titular=titulares_subdivisiones.id_titular and titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and titulares.id_ente=entes.id_ente;";
$r_clave = ejecutar($q_clave);
$f_clave = asignar_a($r_clave);




$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos=ejecutar($q_gastos);
$q_gastos1=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos1=ejecutar($q_gastos1);


if  ($f_proceso[id_servicio]==1)
{
	
	}
	else
	{

$q_proveedor=("select * from proveedores where id_proveedor=$f_proceso[id_proveedor]");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);

if  ($f_proveedor[id_s_p_proveedor]>0){
	$q_proveedorp=("select personas_proveedores.*,s_p_proveedores.* from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor=$f_proveedor[id_proveedor]");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
	}
	else
	{
		$q_proveedorp=("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$f_proveedor[id_proveedor]");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
		}


}




if ($si==1)
{

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
<?php echo "FINIQUITO DE $f_proceso[servicio] Num. $proceso " ?>
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
<td colspan=4>
<hr>
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
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[fecha_recibido] " ?>

</td>
<?php 
 if  ($f_proceso[id_servicio]<>1) {
	?> 
<td colspan=1 class="datos_cliente">
FACTURA NUM
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[factura_final]" ?>

</td>
<?php
}
?>

</tr>
</table>
<br>
<br>

<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4>
<hr>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
DESCRIPCION DE GASTOS
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
CONCEPTO
</td>
<td colspan=1 class="datos_cliente">
DESCRIPCION
</td>
<td colspan=1 class="datos_cliente">
MONTO SOPORTE
</td>
<td colspan=1 class="datos_cliente">
MONTO RECONOCIDO
</td>
</tr>
<?php

 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
$monto1=$monto1 + $f_gastos[monto_aceptado];
?>
<tr>
<td colspan=1  class="datos_cliente">
<?php  if  ($f_proceso[id_servicio]==1)
{ echo "$f_gastos[nombre] FACTURA ($f_gastos[factura])"; }
else
{
	echo "$f_gastos[enfermedad]";
	}
?>
</td>
<td colspan=1  class="datos_cliente">
<?php 
	echo "$f_gastos[descripcion] ";
	?>

</td>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[monto_reserva] " ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[monto_aceptado] " ?>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan=1  class="datos_cliente">
</td>
<td colspan=1  class="datos_cliente">
TOTALES (BS.)</td>
<td colspan=1  class="datos_cliente">
<?php echo "$monto" ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$monto1" ?>
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
<hr>
</td>
</tr>
<tr>
<td colspan=4>
HE RECIBIDO DE CLINISALUD MEDICINA PREPAGADA S.A., POR CONCEPTOS ARRIBA MENCIONADOS, LA CANTIDAD DE <?php echo  formato_montos($monto1) ?> BS. 
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
RECIBI CONFORME CONSTANCIA DE RECEPCION.

</td>

</tr>

	<?php
	if  ($f_proceso[id_servicio]==1)
{?>

<tr>
<td colspan=4 class="datos_cliente">
<?php echo "$nombre_cliente $apellido_cliente C.I. $cedula"?>
</td>

<?php
	
	}
	else
	{
	
	
	
	
		$num_filas=num_filas($r_proveedor);
		if ($num_filas == 0) { 
		
	?>
	<input class="campos" type="hidden" name="id_proveedor" maxlength=128 size=20 value="0"   >
	<?php
		}
		else
		{
		$f_proveedor=asignar_a($r_proveedor);
		$q_proveedorc="select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores,gastos_t_b where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
               and proveedores.id_proveedor=gastos_t_b.id_proveedor and gastos_t_b.id_proceso='$proceso'";
$r_proveedorc=ejecutar($q_proveedorc);
$num_filasc=num_filas($r_proveedorc);
	if ($num_filasc == 0) { 
$value=0;
$proveedor="Seleccione la Clinica";
}
else
{
$f_proveedorc=asignar_a($r_proveedorc);
$value="$f_proveedorc[id_proveedor]";
$proveedor="$f_proveedorc[nombre]";
}

				$q_proveedorp="select especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
                personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
                s_p_proveedores,proveedores,gastos_t_b where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  
                and
                especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
                and proveedores.id_proveedor=gastos_t_b.id_proveedor and gastos_t_b.id_proceso='$proceso'";
$r_proveedorp=ejecutar($q_proveedorp);
$num_filasp=num_filas($r_proveedorp);
	if ($num_filasp == 0) { 
$value=0;
$proveedor="Seleccione  el Dr(a).";
}
else
{
$f_proveedorp=asignar_a($r_proveedorp);
$value="$f_proveedorc[id_proveedor]";
$proveedor="$f_proveedorp[nombre_prov] $f_proveedorp[apellidos_prov] ";
}
}

			?>
			




<tr>
<td colspan=4 class="datos_cliente">
DR(a)  <?php echo $proveedor?>
</td>
<?php
}
?>
</tr>


<?php
}
else
{
?>

<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>

<?php if ($si==2) {
?>

<td colspan=1 class="logo">
<img src="../../public/images/logo.jpg">
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

<?php 
}
else
{
?>
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

<?php
}
?>


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
<?php echo "FINIQUITO DE $f_proceso[servicio] Num. $proceso CLAVE $f_clave[no_clave]" ?>
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
<td colspan=4>
<hr>
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
<?php echo "$f_proceso[ente]  ( $f_clave[subdivision] )" ?>

</td>

</tr>
<tr>
<td colspan=1 class="datos_cliente">
FECHA EMISION
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[fecha_recibido] " ?>

</td>

<td colspan=1 class="datos_cliente">
FACTURA NUM
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[factura_final] " ?>

</td>


</tr>
</table>
<br>
<br>

<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=4>
<hr>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3">
DESCRIPCION DE GASTOS
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
CONCEPTO
</td>
<td colspan=1 class="datos_cliente">
DESCRIPCION
</td>
<td colspan=1 class="datos_cliente">
MONTO SOPORTE
</td>
<td colspan=1 class="datos_cliente">
MONTO RECONOCIDO
</td>
</tr>
<?php

 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
$monto1=$monto1 + $f_gastos[monto_aceptado];
?>
<tr>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[nombre] " ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[descripcion] " ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[monto_aceptado] " ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$f_gastos[monto_aceptado] " ?>
</td>
</tr>
<?php
}
?>
<tr>
<td colspan=1  class="datos_cliente">
</td>
<td colspan=1  class="datos_cliente">
TOTALES (BS.)</td>
<td colspan=1  class="datos_cliente">
<?php echo "$monto1" ?>
</td>
<td colspan=1  class="datos_cliente">
<?php echo "$monto1" ?>
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
<hr>
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente">&nbsp;&nbsp;HE RECIBIDO DE <?php echo $_REQUEST[ente]; ?>, POR CONCEPTOS ARRIBA MENCIONADOS, LA CANTIDAD DE: <?php echo formato_montos($monto1); ?> Bs.S. <?php echo numeros_a_letras($monto1); ?>. 
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
<td colspan=1  class="titulo3"></td>
<td colspan=2 class="titulo3">
<?php
if ($_REQUEST[ente]=="GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD")
{
?>

<?php
}
?>
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
ELABORADO POR:

</td>
<td colspan=1 class="titulo3">
</td>
<td colspan=2 class="titulo3">
<?php
if ($_REQUEST[ente]=="GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD")
{
?>

<?php
}
?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<?php
if ($_REQUEST[ente]=="GOBERNACION DEL ESTADO MERIDA - PLAN DE SALUD")
{
}
else
{
?>
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
<?php

}
?>
</table>


<?php
}
?>
