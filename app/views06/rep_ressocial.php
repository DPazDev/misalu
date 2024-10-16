<?php
include ("../../lib/jfunciones.php");
sesion();


$r_proveedor_clinica=pg_query("select proveedores.id_proveedor,clinicas_proveedores.id_clinica_proveedor,clinicas_proveedores.nombre,clinicas_proveedores.direccion from proveedores,clinicas_proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor  order by clinicas_proveedores.nombre ");

$r_ente=pg_query("select * from  entes where id_tipo_ente=4  order by nombre");


?>
<script>
function enviar(){
	if(document.relacionr.textfechainicio.value.length ==0 || document.relacionr.textfechafinal.value.length ==0)
		alert("Algunos de los campos de las fechas esta vacio");
	else
		document.relacionr.submit();
		
}
</script>





<form action="" method="POST" name="res_ressocial" target="_blank">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 align="center" class="titulo_seccion">Relacion  de Donativos de Responsabilidad Social</td>
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
	  <td align="right" colspan=2><div align="center"><span class="Estilo3"></span></div></td>
  </tr>
	<tr>
	<td class="tdtitulos">Seleccione el Tipo Donativo</td>
		<td class="tdcampos">
	<select id="donativo" name="donativo" class="campos" style="width: 200px;"  >
		<option value="1" >Donativo por Responsabilidad Social</option>
        <option value="3" >Donativo por CiniSalud</option>
    
	</select>
	</td>
	 <td class="tdcampos" ></td>
</tr>
	<tr>
	<td class="tdtitulos">Seleccione el Tipo</td>
		<td class="tdcampos">
	<select id="procesado" name="procesado" class="campos" style="width: 200px;"  >
		<option value="1" >Pendiente</option>
		<option value="2" >Procesado</option>
	</select>
	</td>
	 <td class="tdcampos" title="Buscar"><label class="boton" style="cursor:pointer" onclick="bus_rep_ressocial()" >Buscar</label> </td>
</tr>
</table>
<div id="bus_rep_ressocial"></div>
</form>

