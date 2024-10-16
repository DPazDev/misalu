<?php
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
//$q_dependencia = "select id_dependencia,dependencia from tbl_dependencias where id_dependencia != '2' order by tbl_dependencias.dependencia";
$q_dependencia = "SELECT tbl_ordenes_pedidos.id_dependencia,( SELECT tbl_dependencias.dependencia FROM public.tbl_dependencias WHERE tbl_dependencias.id_dependencia = tbl_ordenes_pedidos.id_dependencia ) as dependencia
FROM   public.tbl_ordenes_pedidos where id_dependencia != '2' and id_proveedor = '0' group by dependencia,id_dependencia order by dependencia;


";
$r_dependencia = ejecutar($q_dependencia);

?>



<table class="tabla_cabecera3" border="0" cellpadding=3 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Control de pedidos por dependencia</td>
     </tr>
          <br>
        <tr>
              <td class="tdtitulos" colspan="1">Estado del pedido:</td>
              <td class="tdcampos"  colspan="1">
			   <select id="estadopedi" class="campos"  style="width: 200px;" onChange="estadopedido();">
			   	    <option value=0></option>   
			       <!-- <option value=1>Pendiente</option>
				<option value=2>Despachado</option>-->
				<option value=3>Recibido</option>
                       <!--     <?php if($elid==60){
                                 echo "<option value=4>Anular</option>";
                                }?>
				<option value=5>Realizados</option>	-->				
				</select></td> 
			  <td class="tdtitulos" colspan="1">Mis dependencias:</td>
           <td class="tdcampos"  colspan="1">
			<select class="campos" id="dependencia" name="dependencia">
			<option value="null">DEPENDENCIA</option>
			<?php
				while($f_dependencia = asignar_a($r_dependencia)){
					echo "<option value=\"$f_dependencia[id_dependencia]\">$f_dependencia[dependencia]</option>";
					}
					?>
			</select> 
            </td>
			  
	 </tr>
	<tr>
	<td class="tdtitulos">Seleccione la Fecha Inicio: </td> 
	<td class="tdtitulos"> 
 <input readonly type="text" size="10" id="fechainicio" name="fechainicio" class="campos" maxlength="10" value='<?php echo $f_cita1[fecha_cita]; ?>' > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechainicio', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>	</td>
	
	<td class="tdtitulos">Seleccione la Fecha final:</td>
	<td class="tdtitulos">   
 <input readonly type="text" size="10" id="fechafin" name="fechafin" class="campos" maxlength="10" value='<?php echo $f_cita1[fecha_cita]; ?>'> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechafin', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>	</td>
	</tr>
	<tr>
<td colspan="4"><!--boton de busqueda  -->     
        <center> <label class="boton" style="cursor:pointer"  onclick="fgastosdepachos();return false;" >Buscar</label> </center></td> </td>	
	</tr>
</table>
<img alt="spinner" id="spinnerARTI" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="despachos"></div>
