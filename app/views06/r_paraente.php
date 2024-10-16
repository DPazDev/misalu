<?php
/* Nombre del Archivo: r_paraente.php
   Descripción: Solicitar los datos para Reporte de Impresión: por Parámetros (para Entes)
*/

	include ("../../lib/jfunciones.php");
	sesion();

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
    		  
?>

<form method="POST" name="r_paraente"  onsubmit="return false;"  id="r_paraente">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n por Par&aacute;metros (para Entes)</td>
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
	                              <option value="0@TODOS LOS SERVICIOS">TODOS LOS SERVICIOS</option>
                                      <option value="-01@TODOS LOS SERVICIOS MENOS HOSPITALIZACION Y EMERGENCIA">TODOS LOS SERVICIOS MENOS  HOSPITALIZACION Y EMERGENCIA</option>
				      <option value="-02@HOSPITALIZACION Y EMERGENCIA">HOSPITALIZACION Y EMERGENCIA</option>
				      <?php  while($servicios=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
					$value="$servicios[id_servicio]@$servicios[servicio]";
					?>
		                      <option value="<?php echo $value?>"> <?php echo "$servicios[servicio]"?></option>
				     <?php                                                                                                   
				     }?> 
		</td>
	</tr>
		<tr><td>&nbsp;</td></tr>

				
	        <td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
		<td class="tdcampos"  colspan="1"><select id="estapro" class="campos"  style="width: 210px;" >
	                              <option value="0@TODOS LOS ESTADOS DEL PROCESO">TODOS LOS ESTADOS DEL PROCESO</option>
				      <option value="-01@SINIESTROS PENDIENTES Y PAGADOS">SINIESTROS PENDIENTES Y PAGADOS</option>
				      <option value="-02@CANDIDATO A PAGO + CANDIDATO A PAGO RECIBIDO POR ADMINISTRACION">CANDIDATO A PAGO + CANDIDATO A PAGO RECIBIDO POR ADMINISTRACION</option>
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
	<td colspan=4 class="tdcamposcc"><a href="#" OnClick="buscar_provper();" class="boton">* Proveedor Persona</a> <a href="#" OnClick="buscar_provcli();" class="boton">* Proveedor Clinica</a> <a href="#" OnClick="buscar_provtotal();" class="boton">* Todos o Ning&uacute;n Proveedor</a></td>
	</tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=4>
<div id="buscar_provper"></div>
</td>
</tr>

<tr><td>&nbsp;</td></tr>
	<tr>     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_paraente();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_paraente();" class="boton">Imprimir</a> 
<a href="#" title="ACTUARIO" OnClick="exc_paraente();" ><img border="0" src="../public/images/excel.jpg"></a> 
<a href="#"  title="DPTO ESTADISTICO " OnClick="exc_paraente1();" class="boton">EXCEL 2</a> 
<a href="#"  OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
	<br>
</table>

<div id="reporte_paraente"></div>
</form>
