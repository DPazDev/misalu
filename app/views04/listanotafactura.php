<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=utf-8');
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

	<fieldset id='listaNotaTable' dir='ltr'>
	<table  WIDTH='85%' border=0 cellpadding=0 cellspacing=0 >
		<tr>
			<td colspan=4 class="titulo_seccion">lista de Notas de Factura</td>
			<td class="titulo_seccion">Lista Todas las Notas</td>
		</tr>
		<tr>
			<td colspan=2 class="tdtitulos">* Seleccione la Fecha Inicio:<br>
	 			<input type="text" size="10" id="dateField1" name="fechainicio" class="campos" maxlength="10" onkeypress="return fechasformato(event,this,1);" value=<?php echo $f_cita1[fecha_cita]; ?>>
				<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
				<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
			</td>
			<td colspan=2 class="tdtitulos">* Seleccione la Fecha Fin:<br>
	 			<input  type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" onkeypress="return fechasformato(event,this,1);" value=<?php echo $f_cita1[fecha_cita]; ?>>
				<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
				<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
			</td>
			<td class="tdcampos" rowspan=3>
				<fieldset id='listarNotas' dir='ltr'>
					<legend class=""></legend>
					<a href="#" OnClick="ver_notafactura();" class="boton">Regresar Busqueda espesifica</a>
				</fieldset>
			</td>

		</tr>
		<tr>
			<td class="tdtitulos">* Tipo de Nota</td>
			<td class="tdcampos" colspan="3">
				<select id="TipoNota" name="TipoNota" class="campos" style="width: 200px;"  >
					<option value="0" >Todas las Notas</option>
					<option value="1" >Nota de Crédito</option>
					<option value="2" >Nota de Débito</option>
				</select>
			</td>

		</tr>
		<tr>
			<td class="tdcampos"></td>
			<td class="tdtitulos" colspan="2"></br></br>
				<input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value="">
				<a href="#" OnClick="listanotafactura2();" class="boton">Buscar</a>
				<a href="#" OnClick="ir_principal();" class="boton">Salir</a>
			</td>
			<td class="tdcampos"></td>
		</tr>
	</table>
</fieldset>


</form>
<div id="listaNotasFactura"></div>
