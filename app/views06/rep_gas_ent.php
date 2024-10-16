<?php
include ("../../lib/jfunciones.php");
sesion();
//Reporte para dar la opcion de buscar las ordenes usadas en cualquiera de los servicios prestados a prima a riesgo y lo muestra de acuerdo al estado en que se encuentre el servicio
$q_sucursal = "select * from sucursales order by sucursales.sucursal";
$r_sucursal = ejecutar($q_sucursal);
$q_servicios = "select * from servicios where servicios.id_servicio<>7 and servicios.id_servicio<>12 order by servicios.servicio";
$r_servicios = ejecutar($q_servicios);
$q_tipo_ente= "select * from tbl_tipos_entes order by tipo_ente";
$r_tipo_ente = ejecutar($q_tipo_ente);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion"></td>	</tr>	
<tr>		<td colspan=4 class="titulo_seccion">Reporte que Funciona Totalizando las Ordenes de un Servicio (la opcion de proveedores directo o indirecto funciona con todos porque hay que actualizar proveedores para identificarlos )</td>	</tr>	
<tr>		<td colspan=4 class="titulo_seccion">Reporte de Gastos Generales de los Entes </td>	</tr>	
<tr> 
		<td  class="tdtitulos">* Fecha Inicio    </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
				<td  class="tdtitulos">*  Fecha Fin   </td>
				<td> 
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value=""> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>
    <tr>
	  <td class="tdtitulos" colspan="1">Seleccione Tipo de Proveedor</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_proveedor" name="tipo_proveedor">
		<option value="0">Todos</option>
		<option value="1">Directo</option>
		<option value="2">Indirecto</option>
		
		</select> </td>
	
	    <td class="tdtitulos" colspan="1"></td>
        <td class="tdcampos" colspan="1" >
		</td>
	</tr>
	<tr>
	  <td class="tdtitulos" colspan="1">Seleccione Tipo de Cliente</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_cliente" name="tipo_cliente">
		<option value="">--Seleccione un Tipo de Cliente--</option>
		<option value="0">Titulares</option>
		<option value="1">Beneficiarios</option>
		<option value="2">Titulares + Beneficiarios</option>
		
		</select> </td>
	
	    <td class="tdtitulos" colspan="1">Seleccione la Sucursal </td>
        <td class="tdcampos" colspan="1" >
		<select class="campos"  style="width: 200px;" id="sucursal" name="sucursal">
		<option value="">--Seleccione una Sucursal--</option>
		<option value="0">Todas las sucursales</option>
		<?php
		while($f_sucursal = asignar_a($r_sucursal)){
			echo "<option value=\"$f_sucursal[id_sucursal]\">$f_sucursal[sucursal]</option>";
		}
		?>
		</select>
		</td>
	</tr>
	<tr> 
	    <td class="tdtitulos" colspan="1">Seleccione el Servicio </td>
        <td class="tdcampos" colspan="1" >
		<select class="campos"  style="width: 200px;"  id="servicio" name="servicio">
		<option value="">--Seleccione un Servicio--</option>
		<option value="0">Todos los Servicios</option>
		<?php
		while($f_servicios = asignar_a($r_servicios)){
		echo "<option value=\"$f_servicios[id_servicio]\">$f_servicios[servicio]</option>";
		}
		?>
		</select>
		</td>
	
	  <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(1)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@Todos los Tipos">Todos los Tipos</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
		
		 
		</tr>
</table>
<div id="bus_ent"></div>
