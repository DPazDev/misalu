<?php
/* Nombre del Archivo: r_consultas_medicas_x_ente_empresas.php
   Descripción: Solicitar los datos para Reporte de Impresión: Consultas Médicas por Entes para usuarios autorizados de una determinada Empresa*/

	include ("../../lib/jfunciones.php");
	sesion();



?>

<form method="POST" name="r_consultas_medicas_x_ente" id="r_consultas_medicas_x_ente">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Consultas M&eacute;dicas por Entes Empresas Autorizadas</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:
		<input readonly type="text" size="10" id="dateField1" name="fecha1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

		<td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
		<input readonly type="text" size="10" id="dateField2" name="fecha2" class="campos" maxlength="10">
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
	       <td colspan=1>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
	       <td class="tdcampos"  colspan="1"><select id="estado" class="campos"  style="width: 210px;" >
                                     <option> ANULADOS </option>
                                     <option> ASISTIDOS </option>
      					</select>	     
		</td>


	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
<tr>


	
		</tr>

<tr><td>&nbsp;</td></tr>
</table>

	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_consultas_medicas_x_ente_empresas();" class="boton">Buscar Fecha Cita</a> <a href="#" OnClick="reporte_consultas_medicas_x_ente_empresas2();" class="boton">Buscar Fecha Orden</a>
		</td>

	</tr>

	<tr> <td colspan=4>&nbsp;</td></tr>
</table>

<div id="reporte_consultas_medicas_x_ente_empresas"></div>


</form>

