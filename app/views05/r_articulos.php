<?php
/* Nombre del Archivo: r_articulos.php
   Descripción: Solicitar los datos para Reporte de Impresión: Reporte por estatus de un articulo desde una fecha especifica
*/

	include ("../../lib/jfunciones.php");
	sesion();
  
?>

<form method="POST" name="r_art"  onsubmit="return false;"  id="r_art">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> Reporte por Estatus de un Artículo desde una Fecha Especifica</td>
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Artículo</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="nombre" name="nombre" maxlength=150 size=25 value=""></td>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Estatus del Artículo</td>
		<td class="tdcampos" colspan="1"><select id="estatus" name="estatus" class="campos"  style="width: 210px;" >
                       			<option value="0@SELECCIONAR ESTATUS">SELECCIONAR ESTATUS</option>
				      	<option value="2@DESPACHADO">DESPACHADO</option>
				      	<option value="3@RECIBIDO">RECIBIDO</option>
		</td>

	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=2 class="tdtitulos">&nbsp; &nbsp; * Seleccione Fecha:
		<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>		
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="his_arts();" class="boton"> Buscar </a> 
	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
    <div id="art"></div>
