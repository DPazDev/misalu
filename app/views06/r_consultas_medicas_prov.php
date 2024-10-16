<?php
/* Nombre del Archivo: r_consultas_medicas_prov.php
   Descripción: Solicitar los datos para Reporte de Impresión: Consultas Médicas por Proveedores 
*/

	include ("../../lib/jfunciones.php");
	sesion();

	$q_servicio=("select servicios.id_servicio,servicios.servicio from servicios where servicios.id_servicio=2 or servicios.id_servicio=3 or servicios.id_servicio=4 or servicios.id_servicio=8 or servicios.id_servicio=11 or servicios.id_servicio=14 order by servicios.servicio");
	$r_servicio=ejecutar($q_servicio);
	
  $q_estado=("select estados_procesos.id_estado_proceso,estados_procesos.estado_proceso from estados_procesos where estados_procesos.id_estado_proceso=2 or estados_procesos.id_estado_proceso=7 or estados_procesos.id_estado_proceso=14 or estados_procesos.id_estado_proceso=17 order by estados_procesos.estado_proceso");
    $r_estado=ejecutar($q_estado);

$q_sucursal=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursales.sucursal");
$r_sucursal=ejecutar($q_sucursal);


?>

<form method="POST" name="r_consultas_medicas_prov" id="r_consultas_medicas_prov">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Consultas M&eacute;dicas por Proveedores</td>
	</tr>
	<tr> <td>&nbsp;</td></tr>
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
	<tr> <td>&nbsp;</td></tr>
	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione el Servicio:</td>
	       <td class="tdcampos"  colspan="1"><select name="serv" id="serv"class="campos"  style="width: 210px;" >
                                     <option value="0">Todos los Servicios</option>
				     <?php  while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_servicio['id_servicio']?>"> <?php echo "$f_servicio[servicio]"?></option>
				    <?php
				    }?> 
		</td>
	        
<td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
	       <td class="tdcampos"  colspan="1"><select name="edo" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">TODOS LOS ESTADOS</option>
                                     <option value="-1@Todos los Estados">CANDIDATO A PAGO + APROBADO OPERADOR</option>
      				     <?php  while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_estado['id_estado_proceso']?>"> <?php echo "$f_estado[estado_proceso]"?></option>
				    <?php
				    }?> 
		</td>
	</tr>
	<tr> <td>&nbsp;</td></tr>
	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione la Sucursal:</td>
	       <td class="tdcampos"  colspan="1"><select name="sucur" id="sucur"class="campos"  style="width: 210px;" >
                                     <option value="0">Todas las Sucursales</option>
				     <?php  while($f_sucursal=asignar_a($r_sucursal,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_sucursal['id_sucursal']?>"> <?php echo "$f_sucursal[sucursal]"?></option>
				    <?php
				    }?> 
		</td>



	<td colspan=4 class="tdcamposcc"><a href="#" OnClick="buscar_provper();" class="boton">* Proveedor Persona</a> <a href="#" OnClick="buscar_provcli();" class="boton">*  Proveedor Clinica</a> </td>
	</tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=4>
<div id="buscar_provper"></div>
</td>
</tr>
	<tr> <td>&nbsp;</td></tr>
<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="reporte_consultas_medicas_prov();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_consultas_medicas_prov();" class="boton">Imprimir</a> 
<a href="#" OnClick="exc_consultas_medicas_prov();" ><img border="0" src="../public/images/excel.jpg"></a> 
<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<tr><td>&nbsp;</td></tr>

</table>

<div id="reporte_consultas_medicas_prov"></div>

</form>

