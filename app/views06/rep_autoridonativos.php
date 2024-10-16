<?php
include ("../../lib/jfunciones.php");
sesion();
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];

/* **** verifico de tiene activado el permiso de refrezcar panel automaticamente **** */
$q_solo_proceso=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=15");
$r_solo_proceso=ejecutar($q_solo_proceso);
$f_solo_proceso=asignar_a($r_solo_proceso);
echo $f_solo_proceso[permiso];

$r_proveedor_clinica=pg_query("select proveedores.id_proveedor,clinicas_proveedores.id_clinica_proveedor,clinicas_proveedores.nombre,clinicas_proveedores.direccion from proveedores,clinicas_proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor  order by clinicas_proveedores.nombre ");

$r_ente=pg_query("select * from  entes where entes.id_tipo_ente=4 or entes.id_tipo_ente=6  order by nombre");


$r_serie=pg_query("select sucursales.sucursal,tbl_series.* from sucursales,tbl_series where tbl_series.id_sucursal=sucursales.id_sucursal order by tbl_series.nomenclatura");
$q_servicios = "select * from servicios where servicios.id_servicio<>7 and servicios.id_servicio<>12 order by servicios.servicio";
$r_servicios = ejecutar($q_servicios);
$q_tipo_ente= "select * from tbl_tipos_entes order by tipo_ente";
$r_tipo_ente = ejecutar($q_tipo_ente);
?>




<form action="" method="POST" name="oa" id="oa" target="_blank">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 align="center" class="titulo_seccion">Reporte Donativos</td>
	</tr>

<tr><td>&nbsp;</td></tr>

<tr>
     <tr><td>&nbsp;</td></tr>
<td colspan=2  class="titulosa">
     SELECCIONE FECHAS
     </td>
     <tr><td>&nbsp;</td></tr>

</tr>

	<tr> 
		<td  class="titulosa">* Fecha Inicio    </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		<td  class="titulosa">*  Fecha Fin   </td>
		<td>
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value=""> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>

	
	<tr>
	<td> <a href="#" title="Buscar Donativos realizados" OnClick="rep_autoridonativos1();" class="boton">Buscar</a></td>
    </tr>

</table>
<div id="rep_autoridonativos"></div>
</form>
