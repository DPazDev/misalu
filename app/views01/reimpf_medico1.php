<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $proceson=$_REQUEST["elproceso"]; 
  $busproce=("select tbl_informedico.id_proceso from tbl_informedico where tbl_informedico.id_proceso=$proceson");
  $repbuspro=ejecutar($busproce);
  $cuantospro=num_filas($repbuspro);
  if($cuantospro>=1){?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
   <br>
   <tr>
    <td  title="Imprimir Informe"><label class="boton" style="cursor:pointer" onclick="ImpInforme(<?echo $proceson?>)" >Imprimir</label></td> 
   </tr>
  </table>    
 <? }else{?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<br>
 <tr>  
     <td class="titulo_seccion">El n&uacute;mero de proceso no tiene informe m&eacute;dico!!!</td>
   </tr> 
</table>
<?}?>
