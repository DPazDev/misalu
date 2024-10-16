<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$id_titular=$_REQUEST['id_titular'];
$id_beneficiario=$_REQUEST['id_beneficiario'];
$proceso=$_REQUEST['proceso'];

$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
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
if ($id_titular>0){
	
	}
	else
	{
$q_proceso=("select subdivisiones.subdivision,entes.nombre as ente,clientes.*,procesos.*,servicios.*,tipos_servicios.*,gastos_t_b.* from entes,procesos,gastos_t_b,servicios,tipos_servicios,subdivisiones,titulares,clientes,titulares_subdivisiones where procesos.nu_planilla='$proceso' and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and titulares.id_titular=titulares_subdivisiones.id_titular and titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and procesos.id_estado_proceso<>14");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
$cedula=$f_proceso[cedula];
if ($f_proceso[id_beneficiario]>0 )
{
	$id_beneficiario=$f_proceso[id_beneficiario];
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]'");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
$nombre_clienteb="$f_beneficiario[nombres]";
$apellido_clienteb="$f_beneficiario[apellidos]";
$cedulab=$f_beneficiario[cedula];
}
}

/* **** busco el cliente titular*** */

$q_clientest=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre from clientes,entes,titulares where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$id_titular and titulares.id_ente=entes.id_ente");
$r_clientest=ejecutar($q_clientest);
$f_clientest=asignar_a($r_clientest);

/* **** busco el cliente Beneficiario*** */

$q_clientesb=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre from clientes,entes,beneficiarios,titulares where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$id_beneficiario and beneficiarios.id_titular=titulares.id_titular and titulares.id_titular=$id_titular and titulares.id_ente=entes.id_ente");
$r_clientesb=ejecutar($q_clientesb);
$f_clientesb=asignar_a($r_clientesb);
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<style type="text/css" media="print">
div.page {
writing-mode: tb-rl;
height: 10%;
margin: 5% 0%;
}
</style>
<div class="page">
<table width="40%" cellspacing=0 cellpadding=0 border=0><tr>
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
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>



<tr>
<td colspan=4 class="titulo3" style="font-size: 07pt"> FORMATO EXCLUSIVO PARA PACIENTES ATENDIDOS EN EL SERVICIO DE ATENCI&oacute;N AMBULATORIA, SERVICIO DE EMERGENCIA 

</td>
</tr>
<tr>
<td colspan=4>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3" style="font-size: 07pt"> Datos del Paciente

</td>
</tr>
<tr>
<td class="datos_cliente" style="font-size: 07pt">Paciente</td>
<td class="datos_cliente" style="font-size: 07pt"><?php if ($id_beneficiario==0)
{
	echo "$f_clientest[nombres] $f_clientest[apellidos] $nombre_cliente $apellido_cliente  ";
	}
	else
	{
		echo "$f_clientesb[nombres] $f_clientesb[apellidos] $nombre_clienteb $apellido_clienteb ";
		}
?></td>
<td class="datos_cliente" style="font-size: 07pt">Cedula</td>
<td class="datos_cliente" style="font-size: 07pt"><?php if ($id_beneficiario==0)
{
	echo "$f_clientest[cedula] $cedula";
	}
	else
	{
		echo "$f_clientesb[cedula] $cedulab";
		}
?></td>
</tr>

<td colspan=1 class="datos_cliente" style="font-size: 07pt">Ente</td>
<td colspan=3 class="datos_cliente" style="font-size: 07pt"><?php 
	echo "$f_clientest[nombre] $f_proceso[ente]" ?>
</td>

</td>
</tr>
</tr>
<td class="datos_cliente" style="font-size: 07pt">Titular</td>
<td class="datos_cliente" style="font-size: 07pt"><?php 
	echo "$f_clientest[nombres] $f_clientest[apellidos] $nombre_cliente $apellido_cliente" ?>
</td>
<td class="datos_cliente" style="font-size: 07pt">Beneficiario </td>
<td class="datos_cliente" style="font-size: 07pt"><?php echo "$f_clientesb[nombres] $f_clientesb[apellidos] $nombre_clienteb $apellido_clienteb "?></td>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr>

</td>
</tr>
<tr>
<td colspan=4 class="titulo3" style="font-size: 07pt"> Laboratorio Solicitado

</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Hematolog&iacute;a completa<input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">TP <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">TPT <input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">VSG <input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Reticulositos Social <input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Gota Gruesa <input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Grupo sangu&iacute;neo y Rh <input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt"><br>Otros ____________________________________________</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> <hr></hr></td>
<tr>
<td colspan=4 class="titulo3" style="font-size: 07pt"> Qu&iacute;mica

</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Glicemia o Clicemia post pandrial o Urea o Creatinina<input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">&Aacute;cido &uacute;rico <input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Curva de tolerancia a la glucosa <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Insulinemia <input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Colesterol <input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Triglic&eacute;ridos <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Na, CI, K <input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Calcio <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente" style="font-size: 07pt">Fosforo <input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Hemoglobina glucocilada <input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Bilirrubinemia o TGO, TGP<input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Fosfatasas Alcalinas o Proteinemia<input type="checkbox"></input></td>
</tr>

<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Orina<input type="checkbox"></input></td>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Heces<input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt">Serolog&iacute;a: o VDRL o HIV<input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt"><br>Hormonas _________________________________________</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente" style="font-size: 07pt"><br>Otros ____________________________________________</td>
</tr>
</table>
<table width="40%" cellspacing=0 cellpadding=0 border=0>
<tr>
		<td colspan=4 class="titulo3"> 
		<br>
		</br>
		</td>
	</tr>
<tr>
<td class="titulo3">______________________
</td>
</tr>
<tr>
<td class="titulo3" style="font-size: 07pt">Firma y Sello del Medico <br>(<?php echo "Impreso el $f_date[date] por $f_admin[nombres] $f_admin[apellidos] a las  $f_hora[timetz]";  ?>)
</td>
</tr>

</table>


</div>
