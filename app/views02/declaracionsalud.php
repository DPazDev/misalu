<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Registrar preguntas Declaraci&oacute;n Salud</td>  
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">Registrar pregunta:</td>
        <td class="tdcampos"><TEXTAREA COLS=65 ROWS=3 id="preguntasalu" class="campos"></TEXTAREA></td>  
        <td class="tdcampos"><input type="hidden" id="idpregunta" class="campos" size="5"></td>  
    </tr>
	
	<tr>
	   <td  title="Guardar Pregunta"><label class="boton" style="cursor:pointer" onclick="guardapregunta()" >Guardar </label></td> 
	</tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />   
<div id='laspreguntas'>
<?
$mostpre=("select declaracion.id_declaracion,declaracion.declaracion from declaracion order by declaracion.id_declaracion;");
$repmostrpe=ejecutar($mostpre);
$cuantspr=num_filas($repmostrpe);
if($cuantspr>=1){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Preguntas registradas</td>  
     </tr>
</table>	
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Pregunta.</th>
        <th class="tdtitulos">Opci&oacute;n.</th>
	</tr>
	<? $i=1;
	      while($laspregunta=asignar_a($repmostrpe,NULL,PGSQL_ASSOC)){
    ?>
	<tr>
	    <td class="tdcampos"><?echo $i;?></td>
		<td class="tdcampos"><?echo $laspregunta[declaracion];?></td> 
		<td  title="Modificar pregunta"><label class="boton" style="cursor:pointer" onclick="modifpregunta(<?echo $laspregunta[id_declaracion];?>,'<?echo $laspregunta[declaracion];?>')" >Modificar </label></td>
	<?
	$i++;
	}
	?>
</table>	

</div>
<?}?>