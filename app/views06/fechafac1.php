<?
include ("../../lib/jfunciones.php");
sesion();
   $fecre1=$_REQUEST['fechainicio'];
   $fecre2=$_REQUEST['fechafin'];
   $repprove=$_REQUEST['proveedor'];   
   $sucupert=$_REQUEST['lasucur'];
   $procesta=$_REQUEST['estpro'];   

?>

<table   cellpadding=0 cellspacing=0>
 <tr>
 <td colspan="1"><label style='color:#071918'>Indique el n&uacute;mero de factura ACTUAL al que desea cambiar:</label></td>
 <td colspan="1"><input type='text' id='actualfac' name='actualfac' size='11'></td> 
 </tr>
 <tr> 
 <td colspan="1"><label style='color:#071918'>Nuevo n&uacute;mero de factura:</label></td>
 <td colspan="1"><input type='text' id='nuevonumfac' size='11'></td>&nbsp&nbsp&nbsp
</tr>
<tr> 
 <td colspan="1"><label style='color:#071918'>N&uacute;mero de control:</label></td>
 <td colspan="1"><input type='text' id='controlfac' size='11'></td>&nbsp&nbsp&nbsp
</tr>
 <tr>
 <td colspan="1"><label style='color:#071918'>Fecha emisi&oacute;n factura: (A&ntilde;o-Mes-D&iacute;a)</label></td>
 <td colspan="1"><input type='text' id='nuefechafac' size='11'></td> 
 </tr>
 <tr>
     <td class="tdcampos"> <label title="Procesar nuevos cambios" class="boton" style="cursor:pointer" onclick="ProceCFac1('<?echo $fecre1?>', '<?echo $fecre2?>', <?echo $repprove?>, <?echo $sucupert?>, <?echo $procesta?>); return false;" >Procesar</label></td>
 </tr>
</table>
