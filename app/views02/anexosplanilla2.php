<?php
include ("../../lib/jfunciones.php");
sesion();
$clientedato=$_REQUEST['varidpersona'];
list($positob,$tipodeclien)=explode("-",$clientedato);
if($tipodeclien=='T'){
	$busanexo=("select tbl_anexos_repuesta.pregunta from tbl_anexos_repuesta where id_titular=$positob;");
}else{
	$busanexo=("select tbl_anexos_repuesta.pregunta from tbl_anexos_repuesta where id_beneficiario=$positob;"); 
}
   $repbusanexo=ejecutar($busanexo);
   $datanexo=assoc_a($repbusanexo);
   $larepuesta=$datanexo['pregunta'];
?>
<input type="hidden" id="tipoclien" value="<?echo $clientedato?>">
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos" colspan="1">Anexo:</td>
       <td class="tdcampos" colspan="3"><TEXTAREA COLS=90 ROWS=5 id="clienanexo" class="campos"><?echo $larepuesta?></TEXTAREA></td>
	</tr> 
	<tr>
	   <td title="Guardar anexo"><label id="titularboton" class="boton" style="cursor:pointer" onclick="GuardaAnexoC()" >Guardar</label>
	</tr>
</table>	
<div id="finanexo"></div>
