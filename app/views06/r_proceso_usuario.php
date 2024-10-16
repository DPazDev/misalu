<?php
/* Nombre del Archivo: r_proceso_usuario.php
   Descripción: Solicitar los datos para Reporte de Impresión: Procesos realizados por un usuario especifico
*/

	include ("../../lib/jfunciones.php");
	sesion();

	$q_usuario=("select admin.id_admin,admin.nombres,admin.apellidos from admin order by admin.nombres");
	$r_usuario=ejecutar($q_usuario);
	$q_servicio=("select servicios.id_servicio,servicios.servicio from servicios order by servicios.servicio");
	$r_servicio=ejecutar($q_servicio);
	$q_sucursal=("select sucursales.id_sucursal, sucursales.sucursal from sucursales order by sucursales.sucursal");
	$r_sucursal=ejecutar($q_sucursal);
    		  
?>

<form method="POST" name="r_prousu"  onsubmit="return false;"  id="r_prousu">
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Procesos por Usuarios</td>
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
	        <td class="tdtitulos" colspan="1">* Seleccione el Servicio:</td>
	        <td class="tdcampos"  colspan="1"><select id="servic" class="campos" onchange="procesos()" style="width: 210px;" >
					<option value="">-- SELECCIONE UN SERVICIO --</option>
	                              <option value="0@TODOS LOS SERVICIOS">TODOS LOS SERVICIOS</option>
                                      <option value="-01@TODOS LOS SERVICIOS MENOS HOSPITALIZACION Y EMERGENCIA">TODOS LOS SERVICIOS MENOS  HOSPITALIZACION Y EMERGENCIA</option>
				      <option value="-02@HOSPITALIZACION Y EMERGENCIA">HOSPITALIZACION Y EMERGENCIA</option>
				      <?php  while($servicios=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
					$value="$servicios[id_servicio]@$servicios[servicio]";
					?>
		                      <option value="<?php echo $value?>"> <?php echo "$servicios[servicio]"?></option>
				     <?php                                                                                                   
				     }?> 

	        <td class="tdtitulos" colspan="1">* Seleccione la Sucursal:</td>
	        <td class="tdcampos"  colspan="1"><select id="sucur" class="campos" onchange="procesos()" style="width: 210px;" >
					<option value="">-- SELECCIONE UNA SUCURSAL --</option>
	                              <option value="0@TODAS LAS SUCURSALES">TODAS LAS SUCURSALES</option>
                                      <?php  while($f_sucursal=asignar_a($r_sucursal,NULL,PGSQL_ASSOC)){

					?>
		                      <option value="<?php echo "$f_sucursal[id_sucursal]@$f_sucursal[sucursal]"?>"> <?php echo "$f_sucursal[sucursal]"?></option>
				     <?php                                                                                                   
				     }?> 

		</td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:
		<input readonly type="text" size="10" id="dateField1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>

		<td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
		<input readonly type="text" size="10" id="dateField2" class="campos" maxlength="10">
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
	</tr>

	<tr><td>&nbsp;</td></tr>
	
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Usuario: </td>
		<td colspan=1 class="tdcamposcc" ><select id="usuario"  class="campos" onchange="procesos()" style="width: 300px;" >
					<option value="">-- SELECCIONE UN USUARIO --</option>
	                              <option value="0@TODOS LOS USUARIOS">TODOS LOS USUARIOS</option>
	                              <option value="-01@SIN USUARIOS">SIN USUARIOS</option>
				      <?php  while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){
					?>
		                      <option value="<?php echo "$f_usuario[id_admin]@$f_usuario[nombres] $f_usuario[apellidos]"?>"> <?php echo "$f_usuario[nombres] $f_usuario[apellidos]"?></option>
				     <?php }?> 
		</td>

	</tr>
<tr><td>&nbsp;</td></tr>
</table>
    <div id="procusu"></div>
<!--
	<tr>
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_proceso_usuario();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_paraente();" class="boton">Imprimir</a> 
<a href="#" title="ACTUARIO" OnClick="exc_paraente();" ><img border="0" src="../public/images/excel.jpg"></a> 
<a href="#"  title="DPTO ESTADISTICO " OnClick="exc_paraente1();" class="boton">EXCEL 2</a> 
<a href="#"  OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<br>
</table>

<div id="reporte_paraente"></div>


</form>

