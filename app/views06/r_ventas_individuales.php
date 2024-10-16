<?php
/* Nombre del Archivo: r_ventas_individuales.php
   Descripción: Solicitar los datos para Reporte de Impresión: Reporte de Ventas Individuales
*/

	include ("../../lib/jfunciones.php");
	sesion();
/*
	$q_usuario=("select admin.id_admin,admin.nombres,admin.apellidos from admin admin where admin.activar='1' order by admin.nombres");
	$r_usuario=ejecutar($q_usuario);
	$q_servicio=("select servicios.id_servicio,servicios.servicio from servicios order by servicio");
	$r_servicio=ejecutar($q_servicio);*/
	
    		  
?>

<form method="POST" name="r_paventa_ind"  onsubmit="return false;"  id="r_paventa_ind">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">Ventas Individuales</td>
	</tr>
<tr><td>&nbsp;</td></tr>



	<tr>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:
		<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

		<td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
		<input readonly type="text" size="10" id="dateField2" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>


	<tr>
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="ventas();" class="boton">Buscar Ventas Individuales</a> 
		<a href="#" OnClick="contratos();" class="boton">Buscar Contratos Anulados</a> 
	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
    <div id="venta"></div>
