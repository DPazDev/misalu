<?php
include ("../../lib/jfunciones.php");
sesion();
$mostpre=("select declaracion.id_declaracion,declaracion.declaracion from declaracion order by declaracion.id_declaracion;");
$repmostrpe=ejecutar($mostpre);
$cuantspr=num_filas($repmostrpe);
?>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Datos del Cliente</td>  
     </tr>
    </table> 
    <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
         <br>
         
    <tr> 
              <td class="tdtitulos">C&eacute;dula del Cliente:</td>
              <td class="tdcampos"><input type="text" id="ceduclien" class="campos" size="20"></td>
              <td  title="Buscar cliente"><label class="boton" style="cursor:pointer" onclick="BuscaClienDS(); return false;" >Buscar</label></td>
    </tr>
  </table>  
  <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
  <div id='datosclienDS'></div>
 <?
  if($cuantspr>=1){
 ?>
    <div id='modifpregunta'>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Preguntas registradas</td>  
     </tr>
</table>	
<table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Pregunta.</th>
        <th class="tdtitulos" >Activar.</th>
        <th class="tdtitulos" colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp.</th>
	</tr>
	<? $i=1;
         $r=1;
	      while($laspregunta=asignar_a($repmostrpe,NULL,PGSQL_ASSOC)){
              $paso="combo$r";
              
    ?>
	<tr>
	    <td class="tdcampos"><?echo $i;?></td>
		<td class="tdcampos"><?echo $laspregunta[declaracion];?></td> 
		<td  class="tdcampos" >
            <input type="radio" name="<?echo $paso?>" id="<?echo $paso?>"  value="<? echo "$laspregunta[id_declaracion]@1"?>"><label style="font-size: 8pt">Si</label>
         </td>   
        <td class="tdcampos" colspan="4">
            <input type="radio" name="<?echo $paso?>" id="<?echo $paso?>" value="<?echo "$laspregunta[id_declaracion]@0"?>" checked><label style="font-size: 8pt">No</label>
        </td>
	<?
	$i++;
    $r++;
	}
	?>
    <tr></tr>
    <tr>
         <input type="hidden" id="cuantpregun" value="<?echo $i-1?>">
        <td  title="Generar declaraci&oacute;n de salud"><label class="boton" style="cursor:pointer" onclick="GuardaDS(); return false;" >Guardar</label></td>
    </tr>
</table>
<img alt="spinner" id="spinnerP2" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='declaraciongene'></div>
<?
 }
 ?>
 </div>