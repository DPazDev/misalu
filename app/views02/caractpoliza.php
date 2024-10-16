<?
include ("../../lib/jfunciones.php");
sesion();
$lapoliza=$_REQUEST[idpoliza];
$propoliza=("select propiedades_poliza.cualidad,propiedades_poliza.monto from propiedades_poliza where id_poliza=$lapoliza order by 
propiedades_poliza.cualidad");
$reppoliza=ejecutar($propoliza);
$datopoliza=("select polizas.descripcion from polizas where id_poliza=$lapoliza");
$repdatpoli=ejecutar($datopoliza);
$descrip=assoc_a($repdatpoli);
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
    <td class="tdcamposc"><?echo $descrip[descripcion];?></td>
 </tr>
</table> 
<br>   
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
    <td class="tdcamposc">Descripci&oacute;n</td>
    <td class="tdcamposc">Monto</td>
    </tr>
<?  while($lapoli=asignar_a($reppoliza,NULL,PGSQL_ASSOC)){
  
?>
  <tr>
   <td class="tdcamposcc"><?php echo $lapoli[cualidad]?>&nbsp;</td>
   <td class="tdcamposcc"><?php echo $lapoli[monto]?>&nbsp;</td>
  </tr>
<?}?>  
</table>  
