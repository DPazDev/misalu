<?php
include ("../../lib/jfunciones.php");
sesion();
$bdepartamentos = ("select departamentos.id_departamento,departamentos.departamento from 
                      departamentos where id_departamento <> 16 order by departamento");
$rbdepartamentos = ejecutar($bdepartamentos);                      
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
 <tr>  
    <td colspan=8 class="titulo_seccion">Crear Aviso en Cartelera</td>   
   </tr> 
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
<tr>
        <td class="tdtitulos">Para el Departamento:</td>
       <td class="tdcampos"  colspan="1">
		<select id="iddepartamento" class="campos"  style="width: 230px;" onchange="$('qusuarios').hide(),usuadepartamento(); return false;" >
        <option value=""></option>
        <option value="0">TODOS</option>
           <?php
              while($losdeparta = asignar_a($rbdepartamentos,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $losdeparta[id_departamento]?>">
                     <?php echo "$losdeparta[departamento]"?>
               </option>
             <?}?>
          </select>
        </td>
</tr>
<tr>
        <td class="tdtitulos">Para el / los Usuario(s):</td>
        <td class="tdcampos"  colspan="1">
        <div id="qusuarios">
		  <select id="qusur" class="campos"  style="width: 230px;" disabled="disabled">
             <option value=""></option>
          </select>
        </div>
        <div id="lusuarios"></div>
        </td>
</tr>
<tr>
     <td class="tdtitulos">Desde:</td>
      <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="feini" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feini', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
     <td class="tdtitulos">Hasta:</td>
      <td class="tdcampos"  colspan="1"><input  type="text" size="10" id="feifi" class="campos" maxlength="10" value="<?echo date("Y-m-d")?>">
	                  <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'feifi', 'yyyy-mm-dd')" title="Ver calendario">
	                 <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>  
</tr>
<tr> 
	   <td class="tdtitulos" colspan="1">Aviso:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=60 ROWS=6 id="aviso" class="campos"></TEXTAREA></td>
	</tr> 
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<br> 
        <td  title="Guardar Aviso"><label class="boton" style="cursor:pointer" onclick="GuardAviso(); return false;" >Guardar</label></td>
        <td><label  title="Salir del Proceso actual"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td> 
	 </tr> 
</table>
<div align= 'center' id='avisoguardado'>
     <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
</div>
