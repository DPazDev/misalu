<?php

/* Nombre del Archivo: r_entpriv.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación Entes Privados
*/

    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $q_sucursal=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursal");
    $r_sucursal=ejecutar($q_sucursal);
    $q_servicio=("select servicios.id_servicio,servicios.servicio from servicios order by servicio");
    $r_servicio=ejecutar($q_servicio);
    $q_estpro=("select estados_procesos.id_estado_proceso,estados_procesos.estado_proceso from 
    estados_procesos where id_estado_proceso=4 or id_estado_proceso=2 or id_estado_proceso=7 or
    id_estado_proceso=6 or id_estado_proceso=11 or id_estado_proceso=10 or id_estado_proceso=11
or id_estado_proceso=16 or id_estado_proceso=15 order by estado_proceso;");
    $r_estpro=ejecutar($q_estpro);
    $q_enpri=("select entes.id_ente,entes.nombre,entes.id_tipo_ente,tbl_tipos_entes.tipo_ente from entes,tbl_tipos_entes where 
		 (tbl_tipos_entes.id_tipo_ente=4 or tbl_tipos_entes.id_tipo_ente=8 or tbl_tipos_entes.id_tipo_ente=6) and entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente order by entes.nombre;");
    $r_enpri=ejecutar($q_enpri);		  


  $q_partida=("select tbl_partidas.id_partida,tbl_partidas.tipo_partida from tbl_partidas order by tipo_partida");
    $r_partida=ejecutar($q_partida);

?>

 <form method="POST" name="r_enpri" id="r_enpri">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Ordenes de Entes Privados</td>
	</tr>
<tr><td>&nbsp;</td></tr>
 
	<tr>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Inicio:
		<input readonly type="text" size="10" id="dateField1" name="fecha1" class="campos" maxlength="10" >
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		<td colspan=2 class="tdtitulos">* Seleccione Fecha Final:
		<input readonly type="text" size="10" id="dateField2" name="fecha2" class="campos" maxlength="10">
		<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" 	title="Show popup calendar">
		<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione la Sucursal:</td>
	       <td class="tdcampos"  colspan="1"><select name="sucur" class="campos"  style="width: 210px;" >
                                     <option value="0@Todas las Sucursales">Todas las Sucursales</option>
                                     <option value="-01@Todas las Sucursales - Sucursal Vigia">Todas las Sucursales - Sucursal Vigia</option>
				     <?php  while($sucur=asignar_a($r_sucursal,NULL,PGSQL_ASSOC)){
		echo "<option value=\"$sucur[id_sucursal]@$sucur[sucursal]\">$sucur[sucursal]</option>";

}?> </td>
	        <td class="tdtitulos" colspan="1">* Seleccione el Servicio:</td>
	        <td class="tdcampos"  colspan="1"><select id="servic" class="campos"  style="width: 210px;" >
	                              <option value="0@Todos los Servicios">Todos los Servicios</option>
	                              <option value="-01@EMERGENCIA + ORDEN DE ATENCION">EMERGENCIA + ORDEN DE ATENCION</option>
				      <?php  while($servicios=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		echo "<option value=\"$servicios[id_servicio]@$servicios[servicio]\">$servicios[servicio]</option>";


}?> </td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	        <td class="tdtitulos" colspan="1">* Seleccione el Ente Privado:</td>
		<td class="tdcampos"  colspan="1"><select id="enpriv" class="campos"  style="width: 210px;" >
				<option value="-04@PRIVADO">ENTES PRIVADOS</option>
				<?php  while($entpri=asignar_a($r_enpri,NULL,PGSQL_ASSOC)){

		echo "<option value=\"$entpri[id_ente]@$entpri[nombre]\">$entpri[nombre] $entpri[tipo_ente]</option>"; ?>
				<?php
}?> </td>
				
	        <td class="tdtitulos" colspan="1">* Seleccione Estado del Proceso:</td>
		<td class="tdcampos"  colspan="1"><select id="estapro" class="campos"  style="width: 210px;" >
	                              <option value="0@Todos los Estados">Todos los Estados</option>
                                   <option value="*@Todos los Estados">Combo CP,CPRA,CE,CG,PE</option>

				      <?php  while($estproceso=asignar_a($r_estpro,NULL,PGSQL_ASSOC)){

		echo "<option value=\"$estproceso[id_estado_proceso]@$estproceso[estado_proceso]\">$estproceso[estado_proceso]</option>";
		                   

}?> </td>		
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_entpriv();" class="boton">Buscar</a> <a href="#" OnClick="imp_entpriv();" class="boton">Imprimir</a> <a href="#" OnClick="exc_entpriv();"  <img border="0" src="../public/images/excel.jpg"></a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

  <tr><td>&nbsp;</td></tr>

	<tr>			
		     <td class="tdtitulos" colspan="1">* Seleccione Partida:</td> 
		<td class="tdcampos"  colspan="1"><select id="partida" class="campos"  style="width: 210px;" >
	                              <option value="0@PARTIDA">PARTIDA</option>

				      <?php  while($f_partida=asignar_a($r_partida,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_partida['id_partida']?>"> <?php echo "$f_partida[tipo_partida]"?></option>
				     <?php
}?> </td>

		     <td class="tdtitulos" colspan="2"> *-* SOLO UTILIZAR PARA LOS REPORTES DE PDVSA *-* </td> 
	</tr>
  <tr><td>&nbsp;</td></tr>
<tr>
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="imp_entpriv_pdvsa();" class="boton">Imprimir PDVSA</a> <a href="#" OnClick="imp_entpriv();" class="boton">Imprimir</a> <a href="#" OnClick="exc_entpriv();"  <img border="0" src="../public/images/excel.jpg"></a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

  <tr><td>&nbsp;</td></tr>

</table>

  <div id="reporte_entpriv"></div>

</form>

