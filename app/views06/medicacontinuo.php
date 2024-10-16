<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$farmacias=("select proveedores.id_proveedor,clinicas_proveedores.nombre from 
proveedores,clinicas_proveedores where
clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
(clinicas_proveedores.id_clinica_proveedor=238 or clinicas_proveedores.id_clinica_proveedor=291 or clinicas_proveedores.id_clinica_proveedor=180) order by clinicas_proveedores.nombre;");
$repfarmacia=ejecutar($farmacias);
$servicio=("select servicios.id_servicio,servicios.servicio from servicios where (servicios.id_servicio=5 or servicios.id_servicio=7 or servicios.id_servicio=9 or servicios.id_servicio=6) order by servicio;");
$repservicio=ejecutar($servicio);
$entes=("select entes.id_ente,entes.nombre from entes order by entes.nombre;");
$repentes=ejecutar($entes);
$estadospro=("select estados_procesos.id_estado_proceso,estados_procesos.estado_proceso from estados_procesos where
(estados_procesos.id_estado_proceso=7 or estados_procesos.id_estado_proceso=4 or estados_procesos.id_estado_proceso=2 or estados_procesos.id_estado_proceso=14 ) order by estados_procesos.estado_proceso;");
$repestadospro=ejecutar($estadospro);
?>
<br>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Control general de medicamentos entregados</td>
     </tr>
</table>
<br>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0> 

     <tr>
         <td colspan=2 class="tdtitulos">* Seleccione fecha inicio:</td>
	<td class="tdcampos"><input readonly type="text" size="10" id="Fini" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	<td colspan=2 class="tdtitulos">* Seleccione fecha final:</td>
	<td class="tdcampos"><input readonly type="text" size="10" id="Fifi" class="campos" maxlength="10">
	<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')" title="Ver calendario">
	<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
     </td>
     </tr>
     <tr>
        <td colspan=2 class="tdtitulos">* Seleccione farmacia:</td>
        <td class="tdcampos"> <select id="proveedor" class="campos"  style="width: 200px;">
			        <option value=""></option>
           <?php  while($elproveedor=asignar_a($repfarmacia,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$elproveedor[id_proveedor]"?>"> <?php echo "$elproveedor[nombre]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
        <td colspan=2 class="tdtitulos">* Seleccione servicio:</td>
        <td class="tdcampos"> <select id="servicio" class="campos"  style="width: 150px;" >
			        <option value=""></option>
           <?php  while($elservicio=asignar_a($repservicio,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$elservicio[id_servicio]"?>"> <?php echo "$elservicio[servicio]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
     </tr>  
     <tr>
        <td colspan=2 class="tdtitulos">* Seleccione el ente:</td>
         <td class="tdcampos"> <select id="ente" class="campos"  style="width: 150px;">
			        <option value=""></option>
                    <option value="1">Todos</option>
           <?php  while($elente=asignar_a($repentes,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$elente[id_ente]"?>"> <?php echo "$elente[nombre]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
        <td colspan=2 class="tdtitulos">* Seleccione estado del proceso:</td>
        <td class="tdcampos"> <select id="estaproce" class="campos"  style="width: 150px;">
			        <option value=""></option>
                                <option value="0">Todos</option>
           <?php  while($estadproce=asignar_a($repestadospro,NULL,PGSQL_ASSOC)){?>
						<option   value="<?php echo "$estadproce[id_estado_proceso]"?>"> <?php echo "$estadproce[estado_proceso]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
     </tr>
     <tr> 
	    <td colspan=2 class="tdtitulos">* Seleccione tipo de médicamento:</td>
         <td class="tdcampos"> <select id="tipomedic" class="campos"  style="width: 150px;">
			        <option value=""></option>
					            <option value="1">Todos</option>
                                <option value="2">Continuo</option>
                                <option value="3">No Continuo</option>                                
		              </select>
         </td>     
        <td colspan=2 class="tdtitulos">* Seleccione tipo de cliente:</td>
         <td class="tdcampos"> <select id="tipocliente" class="campos"  style="width: 150px;">
			        <option value=""></option>
                                <option value="1">Todos</option>
                                <option value="2">Titulares</option>
                                <option value="3">Beneficiarios</option>
		              </select>
         </td>    
     </tr>   
     <tr>  
     </tr>
	<br>
	<br>
     <tr> 
       <br>
         <td class="tdcampos" title="Generar reporte"><label class="boton" style="cursor:pointer" onclick="Reportcontinuo()" >Procesar</label></td> 
		<td class="tdcampos" title="Imprimir reporte"><label class="boton" style="cursor:pointer" onclick="ImpReportcontinuo()" >Imprimir</label></td> 
     </tr>              
 </table>
<div id="reportemedi"></div>
