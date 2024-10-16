<?php
header("Content-Type: text/html;charset=utf-8");
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

$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as comen from entes,procesos,gastos_t_b,servicios,admin,titulares,clientes where procesos.id_proceso='$proceso' and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
$cedula="$f_proceso[cedula]";

$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso='$proceso'");
$r_gastos=ejecutar($q_gastos);
$q_gastos1=("select * from gastos_t_b where gastos_t_b.id_proceso='$proceso'");
$r_gastos1=ejecutar($q_gastos1);

$q_proveedor=("select * from proveedores where id_proveedor='$f_proceso[id_proveedor]'");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);

if  ($f_proveedor[id_s_p_proveedor]>0){
	$q_proveedorp=("select personas_proveedores.*,s_p_proveedores.* from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor='$f_proveedor[id_proveedor]'");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
	}
	else
	{
		$q_proveedorp=("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$f_proveedor[id_proveedor]");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
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
<?php echo "RECIBO DE $f_proceso[servicio] Num. $proceso PROFORMA NUM. $f_proceso[factura]" ?>
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
<td colspan=1 class="datos_cliente">
ENTE
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[ente]  " ?>

</td>

</tr>

<tr>
<td colspan=4 class="titulo3">
DATOS DE LA CARTA DE AVAL
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

</td>
<td colspan=1 class="datos_cliente">
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[comen]. ";?>
</td>
</tr>

<tr>
<td colspan=1  class="datos_cliente">
DR(A)./LUGAR
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] $f_proveedorp[nombre]"?>
</td>
<td colspan=1  class="datos_cliente">
TLF:
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proveedorp[telefonos_prov] $f_proveedorp[telefonos]"?>
</td>

</tr>
<tr>
<td colspan=1  class="datos_cliente">
DIRECCION
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proveedorp[direccion_prov] $f_proveedorp[direccion]"?>
</td>


</tr>
<tr>
<td colspan=1  class="datos_cliente">
MOTIVO
</td>
<td colspan=3  class="datos_cliente">
<?php
echo "$f_proceso[enfermedad]. ";
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
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
RECIBIDO POR:
<?php echo "$f_proceso[adnom] $f_proceso[adapell]" ?>
</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
<? echo "$nombre_cliente $apellido_cliente";?>
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
<br>
</br>
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
<?php echo "RECIDO DE $f_proceso[servicio] Num. $proceso PROFORMA NUM. $f_proceso[factura]" ?>
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
<td colspan=1 class="datos_cliente">
ENTE
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[ente]  " ?>

</td>

</tr>

<tr>
<td colspan=4 class="titulo3">
DATOS DE LA CARTA AVAL
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

</td>
<td colspan=1 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[comen]. ";?>
</td>
</tr>

<tr>
<td colspan=1  class="datos_cliente">
DR(A)./LUGAR
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] $f_proveedorp[nombre]"?>
</td>
<td colspan=1  class="datos_cliente">
TLF:
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proveedorp[telefonos_prov] $f_proveedorp[telefonos]"?>
</td>

</tr>
<tr>
<td colspan=1  class="datos_cliente">
DIRECCION
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proveedorp[direccion_prov] $f_proveedorp[direccion]"?>
</td>


</tr>
<tr>
<td colspan=1  class="datos_cliente">
MOTIVO
</td>
<td colspan=3  class="datos_cliente">
<?php
echo "$f_proceso[enfermedad]. ";
 while($f_gastos1=asignar_a($r_gastos1,NULL,PGSQL_ASSOC)){
$monto1=$monto1 + $f_gastos1[monto_reserva];

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
RECIBIDO POR:
<?php echo "$f_proceso[adnom] $f_proceso[adapell]" ?>
</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
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

<?php
}
else
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
<?php echo "$f_proceso[servicio] Num. $proceso PROFORMA NUM. $f_proceso[factura]" ?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
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
$cedula="$f_beneficiario[cedula]";
}	
?>
<tr>
<td colspan=4 class="datos_cliente">
Se&ntilde;ores:
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo "$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] $f_proveedorp[nombre]"?>

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
Estimados Clientes.
</td>
</tr>

<tr>
<td colspan=4 class="datos_cliente">
<br>
</br>
</br>
<br>
</br><br>


</tr>
<?php
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
}

if ($f_proceso[ente]=="PARTICULAR"){
?>
<tr>
<td colspan=4 class="datos_cliente1" style="text-align: justify">
Agradecemos aceptar la presente CARTA AVAL por la cantidad de <?php echo formato_montos($monto);?> presupuesto presentado por ustedes, para la atencion de  <?php echo "$nombre_cliente $apellido_cliente" ?> portador de la C&eacute;dula de identidad n&uacute;mero <?php echo cedula($cedula);?> quien sera ingresado(a) para realizarle intervenci&oacute;n y/o tratamiento por:
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<br>
</br>
</br>
<br>
</br>
</td>
<tr>
<td colspan=4 class="datos_cliente2">
<?php
echo "$f_proceso[nombre]. ";
?>
</td>
</tr>

<tr>
<td colspan=4  class="datos_cliente">
<br>
</br>
</br>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente1"  style="text-align: justify">
La presente Carta Aval tiene una validez de Quince (15) d&iacute;a partir de su fecha de emisi&oacute;n ampara hasta el monto del presupuestados otorgado por ustedes, cualquier modificaci&oacute;n altere algunos de los t&eacute;rminos del Informe M&eacute;dico presentado para conceder esta autorizaci&oacute;n por ejemplo: Prolongaci&oacute;n cambio del tratamiento, acto quir&uacute;rgico, modificaci&oacute;n en el presupuesto o cambio del M&eacute;dico Tratante debe ser notificado a <b>CLINISALUD MEDICINA PREPAGADA S.A.</b>, a la coordinadora de responsabilidad social &eacute;sta Carta Aval queda nula y sin efecto.<br>
			<b>As&iacute; mismo agradecemos pedir las Claves de Emergencia al telf 0424-7105840 </b> posteriormente hacernos llegar las facturas originales a la siguiente direcci&oacute;n Avenida las Americas Centro Comercial Mayeya 2do. Nivel Local 16,17 y 24. Telefonos: (0274) 2459101-2459229.
 Fax: (0274) 2459285.
</td>
</tr>
<?php 
}
else
{
?>
<tr>
<td colspan=4 class="datos_cliente1" style="text-align: justify">
Agradecemos aceptar la presente CARTA AVAL por la cantidad de <?php echo formato_montos($monto);?> presupuesto presentado por ustedes, para la atencion del Afiliado <?php echo "$nombre_cliente $apellido_cliente" ?> portador de la C&eacute;dula de identidad n&uacute;mero <?php echo cedula($cedula);?> amparado por la contratacion colecctiva de <?php echo "$f_proceso[ente]  " ?> quien sera ingresado(a) para realizarle intervenci&oacute;n y/o tratamiento por:
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<br>
</br>
</br>
<br>
</br>
</td>
<tr>
<td colspan=4 class="datos_cliente2">
<?php
echo "$f_proceso[nombre]. ";
?>
</td>
</tr>

<tr>
<td colspan=4  class="datos_cliente">
<br>
</br>
</br>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente1"  style="text-align: justify">
La presente Carta Aval tiene una validez de Quince (15) d&iacute;a partir de su fecha de emisi&oacute;n ampara la totalidad de los gastos presupuestados por ustedes, cualquier modificaci&oacute; altere algunos de los t&eacute;rminos del Informe M&eacute;dico presentado para conceder esta autorizaci&oacute;n por ejemplo: Prolongaci&oacute;n cambio del tratamiento, acto quir&uacute;rgico, modificaci&oacute;n en el presupuesto o cambio del M&eacute;dico Tratante debe ser notificado a <b>CLINISALUD MEDICINA PREPAGADA S.A.</b>, al departamento de Servicios o de lo contrario &eacute;sta Carta Aval queda nula y sin efecto.<br>
			<b>As&iacute; mismo agradecemos pedir las Claves de Emergencia al telf 0424-7105840 </b> posteriormente hacernos llegar las facturas originales a la siguiente direcci&oacute;n Avenida las Americas Centro Comercial Mayeya 2do. Nivel Local 16,17 y 24. Telefonos: (0274) 2459101-2459229.
 Fax: (0274) 2459285.
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
<td colspan=4>
Atentamente,
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
<br>
</br>
<br>
</br><br>
</br>
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
<? echo "$nombre_cliente $apellido_cliente";?>
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

</td></table>


<?php
}

?>
