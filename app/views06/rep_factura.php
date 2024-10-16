<?php
include ("../../lib/jfunciones.php");
sesion();
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];

/* **** verifico de tiene activado el permiso de refrezcar panel automaticamente **** */
$q_solo_proceso=("select * from permisos where permisos.id_admin='$id_admin' and permisos.id_modulo=15");
$r_solo_proceso=ejecutar($q_solo_proceso);
$f_solo_proceso=asignar_a($r_solo_proceso);
echo $f_solo_proceso[permiso];

$r_proveedor_clinica=pg_query("select proveedores.id_proveedor,clinicas_proveedores.id_clinica_proveedor,clinicas_proveedores.nombre,clinicas_proveedores.direccion from proveedores,clinicas_proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor  order by clinicas_proveedores.nombre ");

$r_ente=pg_query("select * from  entes where entes.id_tipo_ente=4 or entes.id_tipo_ente=6  order by nombre");


$r_serie=pg_query("select sucursales.sucursal,tbl_series.* from sucursales,tbl_series where tbl_series.id_sucursal=sucursales.id_sucursal order by tbl_series.nomenclatura");
$q_servicios = "select * from servicios where servicios.id_servicio<>7 and servicios.id_servicio<>12 order by servicios.servicio";
$r_servicios = ejecutar($q_servicios);
$q_tipo_ente= "select * from tbl_tipos_entes order by tipo_ente";
$r_tipo_ente = ejecutar($q_tipo_ente);
	/* **** verifico de tiene activado el permiso de Modificar facturas de edo por cobrar a pagadas **** */
$q_mod_edo=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=12");
$r_mod_edo=ejecutar($q_mod_edo);
$f_mod_edo=asignar_a($r_mod_edo);
?>
<script>
function enviar(){
	if(document.relacionr.textfechainicio.value.length ==0 || document.relacionr.textfechafinal.value.length ==0)
		alert("Algunos de los campos de las fechas esta vacio");
	else
		document.relacionr.submit();
		
}
</script>





<form action="" method="POST" name="oa" id="oa" target="_blank">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 align="center" class="titulo_seccion">Relacion  de facturas &nbsp;</td>
	</tr>
	<tr>
     <td colspan=1  class="tdtitulos">
     Busquedad por Num. Cheque
     </td>
    <td colspan=1  class="tdtitulos">
     <input class="campos" type="text" name="num_cheque" id="num_cheque" title="Coloque el Numero de Cheque que desea Consultar si la Busquedad es por Cheque" maxlength=128 size=10 value="0">
     </td>
    </tr>
	<tr> 
		<td  class="tdtitulos">* Fecha Inicio    </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		<td  class="tdtitulos">*  Fecha Fin   </td>
		<td>
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value=""> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	</tr>
<tr>
	  <td align="right" colspan=2><div align="center"><span class="Estilo3"></span></div></td>
  </tr>
	
	
	<tr>
	<td width="80" class="tdtitulos" >
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
      <td class="tdtitulos">Estado de la Factura</td>
		<td class="tdcampos">
	<select id="forma_pago" name="forma_pago" class="campos" style="width: 200px;"  >
		<option value="0" >Todas</option>
		<option value="*" >Por Cobrar y Pagadas</option>
		<option value="1" >Pagadas</option>
		<option value="2" >Por Cobrar</option>
		<option value="3" >Anuladas</option>
        <option value="4" >Por Facturar</option>
	</select>
	</td>
    </tr>
    <tr>
	  <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(4)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@Todos los Tipos">Todos los Tipos</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
         <td class="tdtitulos">Seleccione el Servicio si Selecciona un Ente</td>
		<td>
                 <select  style="width: 200px;" id="servicios" name="servicios" class="campos">
                <option value="0"> Sin Servicio</option>
                <option value="*"> Todos los Servicios</option>
                <?php
                                 while($f_servicios=asignar_a($r_servicios,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_servicios[id_servicio]?>"> <?php echo " $f_servicios[servicio]   " ?>
                </option>
                <?php
                }
                ?>
                </select>
                </td>
	</tr>	
    <tr>
	   <td class="tdtitulos">Desea crear valija?</td>
	   <td class="tdcampos">
	       <input type="radio" name="valija" value="0" checked>No
	       <input type="radio" name="valija" value="1"> Si</td>
      </tr>
      <tr>

	   <td class="tdtitulos">Filtrar facturas por caracteres en clave:</td>

	   <td class="tdcampos"><input type="text" id="caraclave" class="campos" size="25" ></td>

	</tr>

	<tr>

	  <td class="tdtitulos">De Izquierda a Derecha</td>

	  <td class="tdcampos"> <input type="radio" name="cladire" value="1" checked>	  </td>

	</tr>

	<tr>  

	  <td class="tdtitulos">De Derecha a Izquierda </td>

	  <td class="tdcampos"> <input type="radio" name="cladire" value="2" >	  </td>

	 <tr> 

	   <td class="tdtitulos">Cualquier ubicaci&oacute;n</td>

	  <td class="tdcampos"> <input type="radio" name="cladire" value="3" >	  </td>

	</tr>
    </table>
<td class="tdcampos"><div id="bus_ent"></div>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
    <tr>
	<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value="">
    <a href="#" title="Buscar Facturas Auditando con los Numeros de Procesos por la Cual Fue Generada" OnClick="bus_rep_factura();" class="boton">Buscar Con Auditoria</a>
	<a href="#" title="Exportar a Excel Reporte de Facturas Auditando con los Numeros de Procesos por la Cual Fue Generada" OnClick="bus_rep_excel_factura(1);"> <img border="0" src="../public/images/excel.jpg"></a> 
	<a href="#" title="Exportar a Excel Reporte de Facturas Auditando con los Numeros de Procesos por la Cual Fue Generada Formato 2" OnClick="bus_rep_excel_factura(2);"> <img border="0" src="../public/images/excel.jpg"></a> 
	  <a href="#" title="Buscar Facturas sin Auditar con los Numeros de Procesos por la Cual Fue Generada" OnClick="bus_rep_factura_sin_auditar();" class="boton">Buscar Sin Auditoria</a>


	<a href="#" title="Exportar a Excel Reporte de Facturas Sin Auditar con los Numeros de Procesos por la Cual Fue Generada" OnClick="bus_rep_excel_factura2();"> <img border="0" src="../public/images/excel.jpg"></a> 
         <a href="#" title="Exportar a Excel Reporte Contable" OnClick="bus_rep_excel_contablejp();"> <img border="0" src="../public/images/excel.jpg"></a> 
         <a href="#" title="Exportar a Excel Reporte Contable" OnClick="bus_rep_excel_contablejpxm();"> <img border="0" src="../public/images/xml.png"></a> 
	  </tr>
	   <tr>
	<td colspan=4 class="tdtitulos" >

    <hr></hr>
</td>  
	</tr>    
    <tr>
	<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value="">	
		<a href="#" title="Buscar Monto Total de Facturas Por Serie Aplicacion individual por Estado de la Factura no es necesario seleccionar el servicio ya que es General, Sin Auditarla con los Procesos que la Generaron" OnClick="bus_rep_sin_audi_factura();" class="boton">Buscar Monto General Sin Auditoria</a>
    <a href="#" title="Buscar Monto Total de Facturas Por Serie y Ente  Aplicacion individual por Estado de la Factura no es necesario seleccionar el servicio ya que es General, Sin Auditarla con los Procesos que la Generaron" OnClick="bus_rep_edo_cue_factura();" class="boton">Buscar Monto General por Serie y Ente Sin Auditoria</a>
		<?php
if ($f_solo_proceso[permiso]=='1'){
	?>
	<a href="#" title="Busca solo los Procesos Recibidos en una Determinada Fecha Relacion Para dpto Actuarial" OnClick="bus_rep_sol_pro();" ><img border="0" src="../public/images/excel.jpg"> </a>
    	  <?php
	}
	?>
	<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>  
	</tr>  
    <tr>
	<td colspan=4 class="tdtitulos" >

    <a href="#" OnClick="bus_rep_excel_factura2();"> </a> 
</td>  
	</tr>  
	
	<tr>
	<td>	
	  <hr>  
	   <a href="#" title="Buscar monto total de facturas por serie, incluyendo monto no aprobado, sin auditarla" OnClick="bus_monto_noaprobado();" class="boton">Busqueda General con Monto no Aprobado</a>	    
		<a href="#" title="Buscar monto total de facturas por serie y ente, incluyendo monto no aprobado, sin auditarla" OnClick="bus_fac_monto_noaprobado();" class="boton">Busqueda General Por Serie y Ente con Monto no Aprobado</a>	    
	</td></tr>

</table>
<div id="bus_rep_factura"></div>
</form>
