<?php
include ("../../lib/jfunciones.php");
sesion();
$entetitu=$_POST['elenteti'];
$querypolizas=("select polizas_entes.id_poliza from polizas_entes where polizas_entes.id_ente='$entetitu'");
$respolizas=ejecutar($querypolizas);
  ?>
    <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
         <td class="titulo_seccion" colspan="1">P&oacute;liza</td>
         <td class="titulo_seccion" colspan="1">Selecci&oacute;n</td>
     </tr>
  <?
  $campo='cual';
  $polinu=0;
  $lapoli="cual$polinu";  
   while($verpoliza=asignar_a($respolizas,NULL,PGSQL_ASSOC)){
      $lapoliza=$verpoliza['id_poliza'];
      $querytipopoliza=("select polizas.id_poliza,polizas.nombre_poliza from polizas where polizas.id_poliza=$lapoliza");
      $repuestipopoliza=ejecutar($querytipopoliza);
      $datapolizas=assoc_a($repuestipopoliza);
      $nompoliza=$datapolizas['nombre_poliza'];
      $idpoliza=$datapolizas['id_poliza'];
    ?>
    <tr>
        <td class="tdtitulos" colspan="1"><?echo $nompoliza;?></td>
        <td class="tdtitulos" colspan="1"><input type="checkbox" id="caja_<? echo $polinu;?>" name="group1" value="<?echo $idpoliza;?>" ></td>      
    </tr> 
<? $polinu++;
   $lapoli="cual$polinu";
  } 

?>
   <input type="hidden" id="cajafinal" value="<?echo $polinu?>">
   </table>
