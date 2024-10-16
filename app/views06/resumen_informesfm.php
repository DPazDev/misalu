
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
//scrip busqueda de doctores y cuantos estudios ha hecho en un intervalo de fecha y hora v0
 /*
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;
*/
?>

<!--<form action="POST" method="post" name="informesMedicos">*/-->
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=2 class="titulo_seccion">consultar la cantidad de reportes en un rango de fecha y hora</td>	</tr>	

<tr>
		<td  class="tdtitulos" > 
Seleccione la Fecha Inicio:   
 <input readonly type="text" size="10" id="fechainicio" name="fechainicio" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechainicio', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>


<td class="tdtitulos"> Seleccione la Fecha final:   
 <input readonly type="text" size="10" id="fechafin" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechafin', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
     </td>
         
</tr>
<tr>
	<td class="tdtitulos"> 
	Seleccione hora de inico:
	<select id='horai'> 
		<option value="null">hora</option>
		<option value="1"> 01</option>
		<option value="2">02</option>
		<option value="3">03</option>
		<option value="4">04</option>
		<option value="5">05</option>
		<option value="6">06</option>
		<option value="7">07</option>
		<option value="8">08</option>
		<option value="9">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
	</select>
	
	<input type="radio" id="AM1" name="HMeridi" value="AM" checked>AM
  
  	<input type="radio" id="PM1" name="HMeridi" value="PM">PM
	</td>
	<td class="tdtitulos"> 
	Seleccione hora de fin:
	<select id='horaf'> 
		<option value="null">hora</option>
		<option value="1"> 01</option>
		<option value="2">02</option>
		<option value="3">03</option>
		<option value="4">04</option>
		<option value="5">05</option>
		<option value="6">06</option>
		<option value="7">07</option>
		<option value="8">08</option>
		<option value="9">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
	</select>
	
	<input type="radio" id="AM2" name="HMeridf" value="AM" checked>AM
  
  	<input type="radio" id="PM2" name="HMeridf" value="PM">PM</td>
</tr>
		<tr>
		
		     <td colspan="2" align="center">
<!--boton de busqueda  -->     
         <label class="boton" style="cursor:pointer"  onclick="frepormedico();return false;" >Buscar</label></td> 
         
		</tr>
		
</table>
<img alt="spinner" id="spinnerARTI" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="reportinforme"></div>

<!-- </form> /-->