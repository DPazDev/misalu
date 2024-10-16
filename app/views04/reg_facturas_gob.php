<?php
include ("../../lib/jfunciones.php");
sesion();
$q_entes=("select * from entes  order by entes.nombre");
$r_entes=ejecutar($q_entes);
$q_sucursales=("select * from sucursales  order by sucursales.sucursal");
$r_sucursales=ejecutar($q_sucursales);
$q_servicios=("select * from servicios  order by servicios.servicio");
$r_servicios=ejecutar($q_servicios);
$q_tipo_ente= "select * from tbl_tipos_entes where tbl_tipos_entes.id_tipo_ente=3  or tbl_tipos_entes.id_tipo_ente=5 order by tipo_ente";
$r_tipo_ente = ejecutar($q_tipo_ente);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="factura">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Facturas por relacion para la gobernacion</td>	</tr>	
<tr> 
		<td  class="tdtitulos">* Fecha Inicio    </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
				<td  class="tdtitulos">*  Fecha Fin   
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value=""> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
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
      	
</tr>
</table>
<td class="tdcampos"><div id="bus_ent"></div>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
    <tr>
                <td class="tdtitulos">Seleccione la Sucursal si Selecciona un Ente</td>
<td>
                 <select  style="width: 300px;" id="sucursal" name="sucursal" class="campos">
                <option value="0"> Sin Sucursal</option>
		
                <?php
                                 while($f_sucursales=asignar_a($r_sucursales,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_sucursales[id_sucursal]?>"> <?php echo " $f_sucursales[sucursal]   " ?>
                </option>
                <?php
                }
                ?>
                </select>
                </td>
                <td class="tdcampos"></td>

                </tr>

		 <tr>
                <td class="tdtitulos">Seleccione el Servicio si Selecciona un Ente</td>
		<td>
                 <select  style="width: 300px;" id="servicios" name="servicios" class="campos">
                <option value="0"> Sin Servicio</option>
               
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
                <td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#"
OnClick="buscar_procesosfgob();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

                </tr>


</table>
<div id="buscar_procesosfgob"></div>

</form>
