<?php
include ("../../lib/jfunciones.php");
sesion();

	$q_estpro=("select estados_procesos.id_estado_proceso,estados_procesos.estado_proceso from estados_procesos order by estado_proceso");
	$r_estpro=ejecutar($q_estpro);


?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="reemesp">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>	<td colspan=4 class="titulo_seccion">Buscar Reembolsos con Monto > , < o = al Seleccionado </td>	</tr>	

<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=2 class="tdtitulos">* Fecha Inicio:   
 <input readonly type="text" size="10" id="dateField1" name="fechainicio" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
<td colspan=2 class="tdtitulos">* Fecha final:   
 <input readonly type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="tdtitulos" colspan="1">* Monto</td>
		<td class="tdcampos" colspan="1"><select name="igualdad" id="igualdad" class="campos"style="width: 40px;">
			<option value=">">></option>
			<option value="<"><</option>
			<option value="=">=</option>
    		</select><input class="campos" type="text" id="monto" name="monto" maxlength=128 size=5 value=""    onkeyUp="return ValNumero(this);" onkeypress="return event.keyCode!=13">
		
		</td>
				
	        <td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
		<td class="tdcampos"  colspan="1"><select id="estapro" name="estapro" class="campos"  style="width: 210px;" >
	                              <option value="0@TODOS LOS ESTADOS DEL PROCESO">TODOS LOS ESTADOS DEL PROCESO</option>
				      <?php  while($estproceso=asignar_a($r_estpro,NULL,PGSQL_ASSOC)){
				$value="$estproceso[id_estado_proceso]@$estproceso[estado_proceso]";	?>

		                      <option value="<?php echo $value?>"> <?php echo "$estproceso[estado_proceso]"?></option>
				     <?php                                                                                                   
				     }?> 
		</td>	
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
	
	<td>
              <td colspan=4 class="tdcamposcc"><a href="#" OnClick="bus_rep_reem_esp();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>

</table>
<div id="bus_rep_reem_esp"></div>
</form>
