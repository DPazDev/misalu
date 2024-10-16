<?php
include ("../../lib/jfunciones.php");
sesion();
$cedula=$_REQUEST['cedula'];

$fechainicio="2005-01-01";
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco si es titular **** */
$q_cliente=("select clientes.nombres,clientes.apellidos,clientes.telefono_hab,clientes.telefono_otro,titulares.id_titular,entes.nombre,estados_clientes.estado_cliente from estados_clientes,clientes,titulares,entes,estados_t_b where clientes.cedula='$cedula' and clientes.id_cliente=titulares.id_cliente and titulares.id_ente=entes.id_ente and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario=0 and estados_t_b.id_titular=titulares.id_titular ");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select clientes.nombres,clientes.apellidos,beneficiarios.id_titular,beneficiarios.id_beneficiario,estados_clientes.estado_cliente from estados_clientes,clientes,beneficiarios,estados_t_b where clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario>0 and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de Cedula no existe  o no tiene ninguna cita asignada</td>
      </tr>

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="reg_cita();" class="boton">Registrar Citas</a></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>
<?php
}
else
{
?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0>

<?php if ($titular==1) {
	
/* ***** repita para buscar al titular **** */
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){

?>
	
<?php
/* **** buscar citas de titulares **** */

$q_citat=("select gastos_t_b.nombre,gastos_t_b.descripcion,gastos_t_b.fecha_cita,personas_proveedores.nombres_prov,
personas_proveedores.apellidos_prov,sucursales.sucursal,tipos_servicios.tipo_servicio,estados_procesos.estado_proceso,
servicios.servicio
 from
 gastos_t_b,personas_proveedores,sucursales,estados_procesos,tipos_servicios,procesos,titulares,proveedores,
s_p_proveedores,servicios where gastos_t_b.id_proceso=procesos.id_proceso and gastos_T_b.fecha_cita>='$fechainicio' and 
procesos.id_estado_proceso=estados_procesos.id_estado_proceso and procesos.id_titular=titulares.id_titular and 
titulares.id_titular=$f_cliente[id_titular] and procesos.id_beneficiario=0 and gastos_t_b.id_proveedor=proveedores.id_proveedor and
proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
s_p_proveedores.id_sucursal=sucursales.id_sucursal and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio 
and 
tipos_servicios.id_servicio=servicios.id_servicio 
order by gastos_t_b.fecha_cita ");
$r_citat=ejecutar($q_citat) or mensaje(ERROR_BD);
?>
<tr> <td colspan=6 class="titulo_seccion">  Citas Medicas del Paciente como Titular</td></tr>
<tr>
                <td class="tdcamposc">Proveedor</td>
                <td class="tdcamposc">Sucursal</td>
                <td class="tdcamposc">Fecha Cita</td>
                <td class="tdcamposc">Estado y Tipo de la Orden </td>
                <td class="tdcamposc">Servicio</td>
        </tr>

<?php

while($f_citat=asignar_a($r_citat,NULL,PGSQL_ASSOC)){

?>

<tr>
                <td class="tdcamposcc"><?php echo "$f_citat[nombres_prov] $f_citat[apellidos_prov] ($f_citat[nombre])"?> </td>
                <td class="tdcamposcc"><?php echo $f_citat[sucursal]?></td>
                <td class="tdcamposcc"><?php echo $f_citat[fecha_cita]?> </td>
		<td class="tdcamposr"><?php echo "$f_citat[estado_proceso] ($f_citat[tipo_servicio])"?></td>
        <td class="tdcamposcc"><?php echo "$f_citat[servicio]"?></td>
        </tr>
		


<?php
}
?>
<?php
/* **** fin de buscar citas titulares ***** */

 }

$q_clienteb=("select clientes.nombres,clientes.apellidos,beneficiarios.id_titular,beneficiarios.id_beneficiario,estados_clientes.estado_cliente from estados_clientes,clientes,beneficiarios,estados_t_b where clientes.cedula='$cedula' and clientes.id_cliente=beneficiarios.id_cliente and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario>0 and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);

if ($num_filasb==0){
}
else
{

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){

$q_clientet=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,
estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre
from clientes,titulares,estados_t_b,estados_clientes,entes
                where titulares.id_titular=$f_clienteb[id_titular] and clientes.id_cliente=titulares.id_cliente and
titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

?>

<?php

$q_citab=("select gastos_t_b.nombre,gastos_t_b.descripcion,gastos_t_b.fecha_cita,personas_proveedores.nombres_prov,
personas_proveedores.apellidos_prov,sucursales.sucursal,tipos_servicios.tipo_servicio,estados_procesos.estado_proceso,
servicios.servicio
 from 
gastos_t_b,personas_proveedores,sucursales,estados_procesos,tipos_servicios,procesos,titulares,proveedores,s_p_proveedores,
servicios 
where gastos_t_b.id_proceso=procesos.id_proceso and gastos_T_b.fecha_cita>='$fechainicio' and 
procesos.id_estado_proceso=estados_procesos.id_estado_proceso and procesos.id_titular=titulares.id_titular and 
titulares.id_titular=$f_clienteb[id_titular] and procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
gastos_t_b.id_proveedor=proveedores.id_proveedor and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
s_p_proveedores.id_sucursal=sucursales.id_sucursal and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio and 
tipos_servicios.id_servicio=servicios.id_servicio
order by gastos_t_b.fecha_cita");
$r_citab=ejecutar($q_citab) or mensaje(ERROR_BD);

?>


<tr> <td colspan=7 class="titulo_seccion">  Citas Medicas Paciente como Beneficiario</td></tr>
<tr>
                <td class="tdcamposc">Proveedor</td>
                <td class="tdcamposc">Sucursal</td>
                <td class="tdcamposc">Fecha Cita</td>
		<td class="tdcamposc">Estado de la Orden</td>
        <td class="tdcamposc">Servicio</td>
        </tr>

<?php

while($f_citab=asignar_a($r_citab,NULL,PGSQL_ASSOC)){

?>
<tr>
    <td colspan=6 class="tdcamposcc" ><hr></hr></td>
</tr>
<tr>
                <td class="tdcamposcc"><?php echo "$f_citab[nombres_prov] $f_citab[apellidos_prov] ($f_citab[especialidad_medica])"?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[sucursal]?></td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_cita]?> </td>
		<td class="tdcamposr"><?php echo "$f_citab[estado_proceso] $f_citab[tipo_servicio]"?></td>
        <td class="tdcamposcc"><?php echo "$f_citab[servicio]"?></td>
        </tr>

<?php
}
?>
<?php

}

}

?>

<?php
}
else
{
if ($titular==0)
{

/* **** repita para beneficiario **** */

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){

$q_clientet=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,
estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre 
from clientes,titulares,estados_t_b,estados_clientes,entes
                where titulares.id_titular=$f_clienteb[id_titular] and clientes.id_cliente=titulares.id_cliente and 
titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and 
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);

?>
<?php

$q_citab=("select gastos_T_b.nombre,gastos_t_b.descripcion,gastos_t_b.fecha_cita,personas_proveedores.nombres_prov,
personas_proveedores.apellidos_prov,sucursales.sucursal,tipos_servicios.tipo_servicio,estados_procesos.estado_proceso,
servicios.servicio 
from 
gastos_t_b,personas_proveedores,sucursales,estados_procesos,tipos_servicios,procesos,titulares,proveedores,
s_p_proveedores,servicios where gastos_t_b.id_proceso=procesos.id_proceso and gastos_T_b.fecha_cita>='$fechainicio' and
 procesos.id_estado_proceso=estados_procesos.id_estado_proceso and procesos.id_titular=titulares.id_titular and 
titulares.id_titular=$f_clienteb[id_titular] and procesos.id_beneficiario=$f_clienteb[id_beneficiario] and 
gastos_t_b.id_proveedor=proveedores.id_proveedor and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
 s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and 
s_p_proveedores.id_sucursal=sucursales.id_sucursal and gastos_t_b.id_tipo_servicio=tipos_servicios.id_tipo_servicio 
and 
tipos_servicios.id_servicio=servicios.id_servicio
order by gastos_t_b.fecha_cita");
$r_citab=ejecutar($q_citab) or mensaje(ERROR_BD);
?>


<tr> <td colspan=7 class="titulo_seccion">  Citas Medicas Paciente  como Beneficiario </td></tr>
<tr>
                <td class="tdcamposc">Proveedor</td>
                <td class="tdcamposc">Sucursal</td>
                <td class="tdcamposc">Fecha Cita</td>
		<td class="tdcamposc">Estado de la Orden</td>
        	<td class="tdcamposc">Servicio</td>
        </tr>

<?php

while($f_citab=asignar_a($r_citab,NULL,PGSQL_ASSOC)){

?>
<tr>
    <td colspan=6 class="tdcamposcc" ><hr></hr></td>
</tr>
<tr>
                <td class="tdcamposcc"><?php echo "$f_citab[nombres_prov] $f_citab[apellidos_prov] $f_citab[especialidad_medica]"?> </td>
                <td class="tdcamposcc"><?php echo $f_citab[sucursal]?></td>
                <td class="tdcamposcc"><?php echo $f_citab[fecha_cita]?> </td>
		<td class="tdcamposr"><?php echo "$f_citab[estado_proceso] $f_citab[servicio] $f_citab[tipo_servicio]"?></td>
        <td class="tdcamposcc"><?php echo "$f_citab[servicio]"?></td>
        </tr>
		
		
<?php
}
?>
<?php

}
?>
			
</table>


<?php
}
}
?>

<?php
}
?>

