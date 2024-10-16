<?php
include ("../../lib/jfunciones.php");
sesion();


$r_sucursal=pg_query("select * from sucursales order by sucursal asc;");
$r_entes=pg_query("select * from entes order by nombre asc;");
$r_servicio=pg_query("select * from servicios order by servicio asc;");
$r_estado_proceso=pg_query("select * from estados_procesos where (id_estado_proceso=7 or id_estado_proceso=10 or id_estado_proceso=11) order by estado_proceso asc;");

//proveedores personas
$q_p_personas = "select personas_proveedores.*, s_p_proveedores.*, proveedores.*
			from personas_proveedores, s_p_proveedores, proveedores
			where 
			personas_proveedores.id_persona_proveedor = s_p_proveedores.id_persona_proveedor and
			s_p_proveedores.id_s_p_proveedor = proveedores.id_s_p_proveedor
			order by personas_proveedores.nombres_prov, personas_proveedores.apellidos_prov;";

$r_p_personas = ejecutar($q_p_personas);

//proveedores clinicas
$q_clinicas = "select clinicas_proveedores.*, proveedores.* 
			from clinicas_proveedores, proveedores
			where
			clinicas_proveedores.id_clinica_proveedor = proveedores.id_clinica_proveedor
			order by clinicas_proveedores.nombre;";
$r_clinicas = ejecutar($q_clinicas);
	

?>


<form action="POST" method="post" name="con_relacion">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Relacion de Recibos o Cheques</td>	</tr>	
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
		</td>
<td class="tdtitulos">Tipo Pago</td>
		
		<td colspan=1  class="tdtitulos"> <select id="prov" name="prov" class="campos" style="width: 150px;" OnChange="bus_provp(2);">
           <option value="">Seleccione el Tipo</option>
           <option value="0">Reembolso</option>
			<option value="1">Medico</option>
			<option value="2">Clinica, Laboratorios, opticas </option>
			<option value="3">Compras</option>
			<option value="4">Otros</option>
			<option value="5">Todos Excepto Reembolso</option>
          

</select>
 <input  type="hidden" size="30" id="codigomas" name="codigomas" class="campos" maxlength="50" value="0">                
                <div id="bus_che_pro"></div>
</td>
		
	
	</tr>	

<tr>
<td align="right" class="tdtitulos">
		Seleccione el Tipo          
		</td>
	<td>
			<select name="recibo_che" id="recibo_che" class="campos"style="width: 100px;" OnChange="visibleono(this);">
			<option value="0">Recibo</option>
    		<option value="1">Cheque</option>
            <option value="2">Retencion IVA</option>
            <option value="3">Retencion ISLR</option>
      		</select>
	
		</td>
		
		<td class="tdcamposc">Del Banco </td>
                <td class="tdcamposc"><select id="banco" name="banco" class="campos" style="width: 100px;"  >
<option value="*">Todos los Bancos</option>
                  <?php $q_banco=("select tbl_bancos.*,bancos.* from tbl_bancos,bancos where tbl_bancos.id_ban=bancos.id_ban");
$r_banco=ejecutar($q_banco);
while($f_banco=asignar_a($r_banco,NULL,PGSQL_ASSOC)){

			?>
			<option value="<?php echo $f_banco[id_banco]?>"><?php echo "$f_banco[nombanco] $f_banco[numero_cuenta] "?></option>
<?php 
}
?>
</select>
<a href="#" OnClick="buscarrecibos();" class="boton">Buscar</a>
<a href="#" style="visibility:hidden" id="bus_rep_excel_islr" OnClick="bus_rep_excel_islr();" title="Exportar Relacion de ISLR a Excel"> <img border="0" src="../public/images/excel.jpg"></a> 
<a href="#" style="visibility:hidden" id="bus_rep_excel_iva" OnClick="bus_rep_excel_iva();" title="Exportar Relacion de IVA a Excel"> <img border="0" src="../public/images/excel.jpg"></a> 
<a href="#" OnClick="ir_principal();" class="boton">Salir</a>
</td>
		</tr>


</table>
<div id="buscarrecibos"></div>
</form>


</form>

