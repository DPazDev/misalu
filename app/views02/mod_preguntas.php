<?php
include ("../../lib/jfunciones.php");
sesion();
$idtitular=$_REQUEST['titular'];
list($eltitu,$tipotitu)=explode('-',$idtitular);
if($tipotitu=='t'){
    $querytipo="declaracion_t.id_titular=$eltitu ";    
    }else{
        $querytipo="declaracion_t.id_beneficiario=$eltitu ";    
        }
$buscarespuesta=("select declaracion.declaracion,declaracion_t.respuesta,declaracion.id_declaracion from declaracion,declaracion_t 
   where 
  declaracion.id_declaracion=declaracion_t.id_declaracion and
  $querytipo
order by declaracion.id_declaracion;");
   $repbuscarespuesta=ejecutar($buscarespuesta);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Declaraci&oacute;n de Salud</td>           
     </tr>
</table>
<table class="tabla_cabecera5" >
     <tr>
        <th class="tdtitulos">Pregunta.</th>
        <th class="tdtitulos">Respuesta.</th>
      </tr>  
  <?
    $r=1;
    while($lasrepuestas=asignar_a($repbuscarespuesta,NULL,PGSQL_ASSOC)){
      $paso="combo$r";   
     ?>   
     <tr>
	    <td class="tdcampos"><?echo $lasrepuestas['declaracion'];?></td>
		<td  class="tdcampos" >
        <?if($lasrepuestas[respuesta]==1){?>
            <input type="radio" name="<?echo $paso?>"  id="<?echo $paso?>" value="<? echo "$lasrepuestas[id_declaracion]@1"?>" checked><label style="font-size: 8pt">Si</label>
         </td>   
        <td class="tdcampos" colspan="4">
            <input type="radio" name="<?echo $paso?>"   id="<?echo $paso?>" value="<?echo "$lasrepuestas[id_declaracion]@0"?>" ><label style="font-size: 8pt">No</label>
        </td>
        <?}else{?>
           <input type="radio" name="<?echo $paso?>" id="<?echo $paso?>"  value="<? echo "$lasrepuestas[id_declaracion]@1"?>" ><label style="font-size: 8pt">Si</label>
         </td>   
        <td class="tdcampos" colspan="4">
            <input type="radio" name="<?echo $paso?>" id="<?echo $paso?>"  value="<?echo "$lasrepuestas[id_declaracion]@0"?>" checked><label style="font-size: 8pt">No</label>
        </td>
        <?}?>
     </tr>
     <?
       $r++;
     }?>
     <tr>
         <input type="hidden" id="cuantpregun" value="<?echo $r-1?>">
         <input type="hidden" id="usuarioid" value="<?echo $eltitu?>">
         <input type="hidden" id="tipousuario" value="<?echo $tipotitu?>">
        <td  title="Guardar cambios declaraci&oacute;n de salud"><label class="boton" style="cursor:pointer" onclick="GuardaCDS(); return false;" >Guardar</label></td>
    </tr>
</table>
<img alt="spinner" id="spinnerP2" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='respuestasmodif'></div>