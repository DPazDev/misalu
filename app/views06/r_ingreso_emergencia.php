<?php
/* Nombre del Archivo: r_ingreso_emergencia.php
   Descripción: Solicitar los datos para Reporte de Impresión: Ingresos por Emergencia
*/

	include ("../../lib/jfunciones.php");
	sesion();

	$inicio="";
	$fin="";


	$q_sucursal=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursal");
	$r_sucursal=ejecutar($q_sucursal);
	$q_servicio=("select servicios.id_servicio,servicios.servicio from servicios order by servicio");
	$r_servicio=ejecutar($q_servicio);
	$q_estpro=("select estados_procesos.id_estado_proceso,estados_procesos.estado_proceso from estados_procesos order by estado_proceso");
	$r_estpro=ejecutar($q_estpro);

	$q_entes=("select entes.id_ente,entes.nombre,entes.id_tipo_ente from entes order by nombre");
	$r_entes=ejecutar($q_entes);
	
	$q_tipo_ente=("select * from tbl_tipos_entes order by tipo_ente");
	$r_tipo_ente = ejecutar($q_tipo_ente);

	$q_doctor=("select * from admin where admin.id_departamento='4' order by nombres");
	$r_doctor = ejecutar($q_doctor);


    		  
?>

<form method="POST" name="r_paraente"  onsubmit="return false;"  id="r_paraente">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">RELACION INGRESOS POR EMERGENCIA </td>
	</tr>
<tr><td>&nbsp;</td></tr>
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
	<tr><td>&nbsp;</td></tr>
	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione la Sucursal:</td>
	       <td class="tdcampos"  colspan="1"><select name="sucur" id="sucur"class="campos"  style="width: 210px;" >
                                     <option value="0@TODAS LAS SUCURSALES">TODAS LAS SUCURSALES</option>
				     <?php  while($sucur=asignar_a($r_sucursal,NULL,PGSQL_ASSOC)){
					$value="$sucur[id_sucursal]@$sucur[sucursal]";			
					?>
				     <option value="<?php echo $value ?>"> <?php echo "$sucur[sucursal]"?></option>
				    <?php
				    }?> 
		</td>

	        <td class="tdtitulos" colspan="1">* Seleccione el Servicio:</td>
	        <td class="tdcampos"  colspan="1"><select id="servic" name="servic" class="campos"  style="width: 210px;" >
	                              <option value="6@EMERGENCIA">EMERGENCIA</option>
                                      <option value="9@HOSPITALIZACION">HOSPITALIZACION</option>
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>

				
	        <td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
		<td class="tdcampos"  colspan="1"><select id="estapro" class="campos"  style="width: 210px;" >
	                              <option value="0@TODOS LOS ESTADOS DEL PROCESO">TODOS LOS ESTADOS DEL PROCESO</option>
				      <option value="-01@SINIESTROS PENDIENTES Y PAGADOS">SINIESTROS PENDIENTES Y PAGADOS</option>
				      <?php  while($estproceso=asignar_a($r_estpro,NULL,PGSQL_ASSOC)){
				$value="$estproceso[id_estado_proceso]@$estproceso[estado_proceso]";	?>

		                      <option value="<?php echo $value?>"> <?php echo "$estproceso[estado_proceso]"?></option>
				     <?php                                                                                                   
				     }?> 
		</td>	
			

	
		<td class="tdtitulos" colspan="1">* Seleccione el Tipo de Cliente:</td>
		<td class="tdcampos"  colspan="1"><select id="tipcliente" class="campos"  style="width: 210px;" >
                        	      <option>TODOS</option>
				      <option>TITULAR</option>
	                              <option>BENEFICIARIO</option>
		</td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
		<td class="tdtitulos" >* Seleccione Intervalo de Horas:</td>
		<td class="tdcampos"  ><select id="inicio" class="campos"  style="width: 100px;" >
                        	      <option>00:00:00</option>
                        	      <option>01:00:00</option>
                        	      <option>02:00:00</option>
                        	      <option>03:00:00</option>
                        	      <option>04:00:00</option>
                        	      <option>05:00:00</option>
                        	      <option>06:00:00</option>
                        	      <option>07:00:00</option>
                        	      <option>08:00:00</option>
                        	      <option>09:00:00</option>
                        	      <option>10:00:00</option>
                        	      <option>11:00:00</option>
                        	      <option>12:00:00</option>
                        	      <option>13:00:00</option>
                        	      <option>14:00:00</option>
                        	      <option>15:00:00</option>
                        	      <option>16:00:00</option>
                        	      <option>17:00:00</option>
                        	      <option>18:00:00</option>
                        	      <option>19:00:00</option>
                        	      <option>20:00:00</option>
                        	      <option>21:00:00</option>
                        	      <option>22:00:00</option>
                        	      <option>23:00:00</option>
                        	      <option>23:59:59</option>

		</td>

		<td class="tdtitulos" >hasta:</td>
		<td class="tdcampos"  ><select id="fin" class="campos"  style="width: 100px;" >
                        	      <option>00:00:00</option>
                        	      <option>01:00:00</option>
                        	      <option>02:00:00</option>
                        	      <option>03:00:00</option>
                        	      <option>04:00:00</option>
                        	      <option>05:00:00</option>
                        	      <option>06:00:00</option>
                        	      <option>07:00:00</option>
                        	      <option>08:00:00</option>
                        	      <option>09:00:00</option>
                        	      <option>10:00:00</option>
                        	      <option>11:00:00</option>
                        	      <option>12:00:00</option>
                        	      <option>13:00:00</option>
                        	      <option>14:00:00</option>
                        	      <option>15:00:00</option>
                        	      <option>16:00:00</option>
                        	      <option>17:00:00</option>
                        	      <option>18:00:00</option>
                        	      <option>19:00:00</option>
                        	      <option>20:00:00</option>
                        	      <option>21:00:00</option>
                        	      <option>22:00:00</option>
                        	      <option>23:00:00</option>
                        	      <option>23:59:59</option>

		</td>
	</tr>
		


<tr><td>&nbsp;</td></tr>
	<tr>

	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	      <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(4)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@TODOS LOS TIPOS">TODOS LOS TIPOS</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
		</tr>

<tr><td>&nbsp;</td></tr>

</table>
<div id="bus_ent"></div>
		

<table>
<tr><td>&nbsp;</td></tr>

	<tr>
	<td colspan=4 class="tdcamposcc"> <a href="#" OnClick="buscar_provtotal();" class="boton">* Todos o Ning&uacute;n Proveedor</a></td>
	</tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=4>
<div id="buscar_provper"></div>
</td>
</tr>

<tr><td>&nbsp;</td></tr>
	<tr>     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="rep_ingreso_emergencia();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_ingreso_emergencia();" class="boton">Imprimir</a>
<a href="#"  OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
	<br>
</table>

<div id="rep_ingreso_emergencia"></div>
</form>
