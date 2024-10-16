<?php
/* Nombre del Archivo: r_pago_ventas_ind.php
   Descripción: Solicitar los datos para Reporte de Impresión: Forma de Pago de las Ventas Individuales
*/

	include ("../../lib/jfunciones.php");
	sesion();

	$q_pagos=("select tbl_tipos_pagos.id_tipo_pago, tbl_tipos_pagos.tipo_pago from tbl_tipos_pagos order by tbl_tipos_pagos.tipo_pago");
    $r_pagos=ejecutar($q_pagos);
    		  
?>

<form method="POST" name="r_paventa_ind"  onsubmit="return false;"  id="r_paventa_ind">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
		<tr>
			<td colspan=4 class="titulo_seccion negrita">Pagos de Ventas Individuales</td>
		</tr>
		<tr><td>&nbsp;</td></tr>

		<tr>
			<td colspan=2 class="tdtitulos negrita">Seleccione Fecha Inicio:
			<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
			<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
			<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

			<td colspan=2 class="tdtitulos negrita">Seleccione Fecha Final:
			<input readonly type="text" size="10" id="dateField2" class="campos" maxlength="10" >
			<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
			<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>

		<td class="tdtitulos negrita" colspan="1">Seleccione Forma de Pago:</td>
		<td class="tdcampos"  colspan="1">
			<select id="pagos" class="campos"  style="width: 210px;" >
				<option value="0@Todos los Tipos de Pagos">Todos los Tipos de Pagos</option>
				<?php
				while($f_pagos=asignar_a($r_pagos,NULL,PGSQL_ASSOC)){
					$value="$f_pagos[id_tipo_pago]@$f_pagos[tipo_pago]";
					?>
					<option value="<?php echo $value?>"> <?php echo "$f_pagos[tipo_pago]"?></option>
					<?php
				}?> 
		</td>

		<tr><td>&nbsp;</td></tr>
		<tr>     
			<td colspan=4 class="tdcamposcc"><a href="#" OnClick="pago_ventas();" class="boton">Buscar</a> 
		</tr>
		<tr><td>&nbsp;</td></tr>

</table>

    <div id="paventa_ind"></div>
