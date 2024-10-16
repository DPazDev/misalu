<?php
include ("../../lib/jfunciones.php");
sesion();
$tpregunta=strtoupper($_REQUEST['pregunta']);
$pregunid=$_REQUEST['lapregunid'];
if($pregunid<=0){
$guardapreunt=("insert into declaracion(declaracion) values('$tpregunta');");
$repguarpre=ejecutar($guardapreunt);
}else{
    $actualpregun="update declaracion set declaracion='$tpregunta' where id_declaracion=$pregunid;";
    $repactualpre=ejecutar($actualpregun);
    }
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
<?}?>