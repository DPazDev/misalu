<?php
/* Nombre del Archivo: r_auditar_reembolso.php
   Descripción: Solicitar los datos para Reporte de Impresión: Reporte Auditar Reembolso
*/

	include ("../../lib/jfunciones.php");
	sesion();
  
?>

<form method="POST" name="r_aud_reem"  onsubmit="return false;"  id="r_aud_reem">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> Auditar Reembolsos </td>
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
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reembolsos();" class="boton"> Buscar </a> 

	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
    <div id="reemb"></div>
