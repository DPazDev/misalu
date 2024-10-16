<?php
/* Nombre del Archivo: r_articulos.php
   Descripción: Solicitar los datos para Reporte de Impresión: Reporte de articulos consumidos en un período de fechas especificas
*/

	include ("../../lib/jfunciones.php");
	sesion();
  
?>

<form method="POST" name="r_art_consum"  onsubmit="return false;"  id="r_art_consum">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> Reporte de  Artículos consumidos en un período de Fechas Especificas</td>
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Artículo</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" id="nombre" name="nombre" maxlength=150 size=25 value=""></td>	
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=2 class="tdtitulos">&nbsp; &nbsp; * Seleccione Fecha:
		<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>	

		<td colspan=2 class="tdtitulos">&nbsp; &nbsp; * Seleccione Fecha:
		<input readonly type="text" size="10" id="dateField2" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>		
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="art_consum();" class="boton"> Buscar </a> 
	</tr>

	<tr><td>&nbsp;</td></tr>
</table>

    <div id="art_consum"></div>
