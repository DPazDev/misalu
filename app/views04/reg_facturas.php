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
$q_partidas= "select * from tbl_partidas";
$r_partidas = ejecutar($q_partidas);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="factura">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Facturas</td>	</tr>	
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
		<td class="tdtitulos">Proceso</td>
		<td class="tdcampos"><input class="campos" type="text" id="proceso" name="proceso" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"> ó</td>
		
		</tr>
		<tr>
		<td class="tdtitulos">Clave</td>
		<td class="tdcampos"><input class="campos" type="text" id="clave" name="clave" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"> ó</td>
		
		</tr>
		<tr>
		<td class="tdtitulos">Presupuesto o Planilla</td>
		<td class="tdcampos"><input class="campos" type="text" id="planilla" name="planilla" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"> ó</td>
		
		</tr>
        <td class="tdtitulos">Cuadro Recibo de Prima</td>
		<td class="tdcampos"><input class="campos" type="text" id="cua_rec_prim" name="cua_rec_prim" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"> ó</td>
		
		</tr>
		<tr>
		
	
        <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente"  >
		<option value="0">--Sin Tipo de Ente--</option>
	
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
      	
</tr>
		<tr>
		<td class="tdtitulos">Ente</td>
		<td>
		 <select  style="width: 300px;" id="entes" name="entes" class="campos">
                <option value="0"> Sin Ente</option>
                <?php
				 while($f_entes=asignar_a($r_entes,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_entes[id_ente]?>"> <?php echo " $f_entes[nombre]   " ?>
                </option>
                <?php
                }
                ?>
                </select>
		</td>
		<td class="tdcampos"></td>
		</tr>
        		<tr>
		<td class="tdtitulos">Tipo Partida</td>
		<td>
		 <select  style="width: 300px;" id="partidas" name="partidas" class="campos">
                <option value="0"> Sin Partida</option>
                <?php
				 while($f_partidas=asignar_a($r_partidas,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_partidas[id_partida]?>"> <?php echo $f_partidas[tipo_partida]; ?>
                </option>
                <?php
                }
                ?>
                </select>
		</td>
		<td class="tdcampos"></td>
		</tr>

		 <tr>
                <td class="tdtitulos">Seleccione la Sucursal si Selecciona un Ente</td>
<td>
                 <select  style="width: 300px;" id="sucursal" name="sucursal" class="campos">
                <option value="0"> Sin Sucursal</option>
		<option value="*"> Todas las Sucursales</option>
		<option value="**"> Todas las Sucursales - La Sucursal del Vigia</option>
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
                <td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#"
OnClick="buscar_procesosf();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

                </tr>


</table>
<div id="buscar_procesosf"></div>

</form>
