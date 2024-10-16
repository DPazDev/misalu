<?php
include ("../../lib/jfunciones.php");
sesion();
$r_sucursal=pg_query("select * from sucursales order by sucursal asc;");
?>


<form action="POST" method="post" name="con_relacion">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Relacion de Cotizaciones</td>	</tr>	
		<tr>
		<td colspan=2 class="tdtitulos">* Seleccione la Fecha Inicio:   
 <input readonly type="text" size="10" id="dateField1" name="fechainicio" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
<td colspan=2 class="tdtitulos">* Seleccione la Fecha final:   
 <input readonly type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>

                </td>
		</tr>


<tr>
		<td align="right" class="tdtitulos">
		Seleccione la Sucursal          
		</td>
		<td>
		<select name="sucursal" id="sucursal" class="campos"style="width: 200px;">
			<option value="0@Todas las sucursales">Todas las sucursales</option>
    			<?php
			while($f_sucursal=pg_fetch_array($r_sucursal, NULL, PGSQL_ASSOC))
				echo "<option value=\"$f_sucursal[id_sucursal]@$f_sucursal[sucursal]\">$f_sucursal[sucursal]</option>";
			?>
      		</select>
            <a href="#" OnClick="bus_rel_coti();" class="boton">Buscar</a>
            <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
</tr>	

</table>
<div id="busrelcoti"></div>
</form>
