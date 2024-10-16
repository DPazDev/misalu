<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' );
$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco el titular *** */

$q_proceso=("select
                                tbl_tipos_entes.tipo_ente,
                                entes.nombre as ente,
                                clientes.*,
                                procesos.*,
                                servicios.*,
                                tipos_servicios.*,
                                gastos_t_b.*
                        from
                                entes,
                                procesos,
                                gastos_t_b,
                                servicios,
                                tipos_servicios,
                                clientes,
                                titulares,
                                tbl_tipos_entes
                        where
                                procesos.id_proceso=$proceso and
                                gastos_t_b.id_proceso=procesos.id_proceso and
                                gastos_t_b.id_servicio=servicios.id_servicio and
                                gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and
                                titulares.id_titular=procesos.id_titular and
                                titulares.id_cliente=clientes.id_cliente and
                                titulares.id_ente=entes.id_ente and
                                entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and (monto_aceptado :: real)>0.01 ORDER BY id_gasto_t_b asc");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";

list($ano,$mes,$dia)=split("-",$f_proceso[fecha_recibido],3);
list($ano1,$mes1,$dia1)=split("-",$f_proceso[fecha_cita],3);


$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos=ejecutar($q_gastos);
$q_gastos1=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos1=ejecutar($q_gastos1);

$q_proveedor=("select * from proveedores where id_proveedor=$f_proceso[id_proveedor]");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);

if  ($f_proveedor[id_s_p_proveedor]>0)
	{
	$q_proveedorp=("select personas_proveedores.*,s_p_proveedores.*,especialidades_medicas.* from personas_proveedores,s_p_proveedores,especialidades_medicas,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor=$f_proveedor[id_proveedor] and especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
	}
	else
	{
		$q_proveedorp=("select * from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$f_proveedor[id_proveedor]");
$r_proveedorp=ejecutar($q_proveedorp);
$f_proveedorp=asignar_a($r_proveedorp);
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
<?php echo "$f_proceso[servicio] $f_proceso[tipo_servicio] Num. $proceso" ?>
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
<?php echo "$f_proceso[ente]" ?>

</td>

</tr>
<tr>
<td colspan=1 class="datos_cliente">
TIPO ENTE
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[tipo_ente] " ?>

</td>

</tr>
<tr>
<td colspan=4 class="titulo3">
DATOS DE LA ORDEN
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
FECHA EMISION
</td>
<td colspan=1 class="datos_cliente">
<?php echo " $dia-$mes-$ano  " ?>

</td>
<td colspan=1 class="factura">
FECHA DE CITA
</td>
<td colspan=1 class="factura">
<?php
if ($f_proceso[fecha_cita]=="1900-01-01"){
	echo "SOLICITAR  CITA" ;
	}
else
{
echo "$dia1-$mes1-$ano1 HORA: $f_proceso[hora_cita]";
}
?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
"SE ATENDERA POR ORDEN DE LLEGADA A TODOS LOS AFILIADOS A PARTIR DE LA HORA INDICADA"
</td>
</tr>

<tr>
<td colspan=1  class="datos_cliente">
DR(A)./LUGAR
</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] $f_proveedorp[nombre] "?>
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
<td colspan=1  class="factura">
HORARIO
</td>
<td colspan=3 class="factura">
<?php echo "$f_proveedorp[horario]"?>
</td>


</tr>

<tr>
<td colspan=1  class="datos_cliente">
MOTIVO
</td>
<td colspan=3  class="datos_cliente">
<?php
echo "$f_proveedorp[especialidad_medica] ";
if ($f_proceso[id_tipo_servicio]==6)
{
	echo "$f_proceso[nombre]  ";
}
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
echo "$f_gastos[descripcion] $f_gastos[comentarios], ";
}
if ($si==1){
echo formato_montos($monto); echo"Bs.S.";
}
?>
</td>
</tr>
<?php
if ($f_proveedorp[especialidad_medica]=="ODONTOLOGIA")
{
?>
<tr>
<td colspan=4  class="titulo3">
Nota: SI ES TRATAMIENTO DE CONDUCTO, EXODONCIA DENTAL Y CIRUGIAS LLEVAR PANORAMICA Y EXAMENES DE LABORATORIO ACTUALIZADO
</td>
</tr>
<?php
}
?>
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
<td colspan=4 class="titulo3">
INFORMACIÓN:  ESTIMADO USUARIO; NOS COMPLACE OFRECERLE NUESTROS PLANES DE SALUD, RECUERDE SU SALUD NO ES UN GASTO, ES UNA SANA INVERSION. CONSULTE CON NUESTROS EJECUTIVOS. SEDE MÉRIDA:0274-2510092 SEDE VIGÍA:0275-8811520.
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<hr></hr>
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
<?php echo "$f_proceso[servicio] $f_proceso[tipo_servicio] Num. $proceso" ?>
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
<?php echo "$f_proceso[ente]" ?>

</td>

</tr>
<tr>
<td colspan=1 class="datos_cliente">
TIPO ENTE
</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[tipo_ente]" ?>

</td>

</tr>

<tr>
<td colspan=4 class="titulo3">
DATOS DE LA ORDEN
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
FECHA EMISION
</td>
<td colspan=1 class="datos_cliente">
<?php echo " $dia-$mes-$ano  " ?>

</td>
<td colspan=1 class="factura">
FECHA DE CITA
</td>
<td colspan=1 class="factura">
<?php
if ($f_proceso[fecha_cita]=="1900-01-01"){
	echo "SOLICITAR  CITA" ;
	}
else
{
echo "$dia1-$mes1-$ano1 HORA: $f_proceso[hora_cita]";
}
?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
OBSERVACION
</td>

<td colspan=3 class="datos_cliente">
"SE ATENDERA POR ORDEN DE LLEGADA A TODOS LOS AFILIADOS A PARTIR DE LA HORA INDICADA"
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
<td colspan=1  class="factura">
HORARIO
</td>
<td colspan=3 class="factura">
<?php echo "$f_proveedorp[horario]"?>
</td>
</tr>
<tr>
<td colspan=1  class="datos_cliente">
MOTIVO
</td>
<td colspan=3  class="datos_cliente">
<?php
echo "$f_proveedorp[especialidad_medica] ";
if ($f_proceso[id_tipo_servicio]==6)
{
	echo "$f_proceso[nombre]  ";
	}
 while($f_gastos1=asignar_a($r_gastos1,NULL,PGSQL_ASSOC)){
$monto1=$monto1 + $f_gastos1[monto_reserva];
echo "$f_gastos1[descripcion] $f_gastos1[comentarios], ";
}
if ($si==1){
echo formato_montos($monto1); echo"Bs.S.";
}

?>
</td>
</tr>
<?php
if ($f_proveedorp[especialidad_medica]=="ODONTOLOGIA")
{
?>
<tr>
<td colspan=4  class="titulo3">
Nota: SI ES TRATAMIENTO DE CONDUCTO, EXODONCIA DENTAL Y CIRUGIAS LLEVAR PANORAMICA Y EXAMENES DE LABORATORIO ACTUALIZADO
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
/* **** modificar el procesos y gastos de este proceso si es donativo **** */
$mod_prodon="update procesos set id_estado_proceso=2 where
procesos.id_proceso=$proceso";
$fmod_prodon=ejecutar($mod_prodon);
/* **** modificar el procesos  si es donativo **** */
/* **** Se registra lo que hizo el usuario**** */
$log="COLOCO LA ORDEN NUMERO $proceso EN ESTADO OPERADOR";
logs($log,$ip,$id_admin);
/* **** Fin de lo que hizo el usuario **** */
?>
