<?php
include ("../../lib/jfunciones.php");
sesion();
$contratoid=$_REQUEST['elidcontrato'];
$cabezcontrato=$_REQUEST['elencavebe'];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Anulaci&oacute;n del Contrato No. <?echo $cabezcontrato?></td> 
         <td  title="Modificar art&iacute;culo" class="titulo_seccion"><label class="boton" style="cursor:pointer" onclick="Losarti(); return false;" >Ver art&iacute;culo</label></td>            
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr>
             <td class="tdtitulos">N&uacute;mero del art&iacute;culo :</td>
             <td class="tdcampos"><input type="text" id="numarti" class="campos" size="10" onkeypress="return elnumero(event);" value="1146"></td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    <tr><td class="tdcampos" title="Anular Contrato"><label class="boton" style="cursor:pointer" onclick="Findcontrato('<?echo $contratoid?>','<?echo $cabezcontrato?>')" >Anular Contrato</label></td></tr>
   
</table>
