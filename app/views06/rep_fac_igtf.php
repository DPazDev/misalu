<?php

	include ("../../lib/jfunciones.php");
	sesion();

$q_solo_proceso=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=15");

$r_serie=pg_query("select sucursales.sucursal,tbl_series.* from sucursales,tbl_series where tbl_series.id_sucursal=sucursales.id_sucursal order by tbl_series.nomenclatura");

  
?>

<form method="POST" name="r_aud_reem"  onsubmit="return false;"  id="r_aud_reem">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> Facturas con IGTF </td>
	</tr>
<tr><td>&nbsp;</td></tr>



	<tr>
		<td colspan=2 class="titulosa"> Seleccione Fecha Inicio
		<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

		<td colspan=2 class="titulosa"> Seleccione Fecha Final
		<input readonly type="text" size="10" id="dateField2" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>

	<tr>
	<td width="125" class="titulosa" >
	  	    Seleccione la Sucursal    </td>
			<td>
	      <select id="sucursal" style="width: 200px;"  name="sucursal" class="campos">
		<option value="0" >Todas las Sucursales CliniSalud</option>  
            <?php
	while($f_serie=pg_fetch_array($r_serie, NULL, PGSQL_ASSOC))
		echo "<option value=\"$f_serie[id_serie]\">Serie $f_serie[nomenclatura]  Sucursal $f_serie[sucursal] </option>";
	?>
          </select>
      </td>
	<tr><td>&nbsp;</td></tr>
	<tr>

     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="rep_fac_igtf1();" class="boton"> Buscar </a> 

	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
    <div id="rep_fac_igtf"></div>