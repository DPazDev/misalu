<?
include ("../../lib/jfunciones.php");
sesion();
$paiabuscar=$_POST['elpaises'];
$ciudadpais=("select id_estado,estado from estados where id_pais='$paiabuscar' order by estado");
$ejecutarpais=ejecutar($ciudadpais);
?>
   <select id="estclin" class="campos" onChange="$('prue2').hide(),estados(); return false"  style="width: 210px;" >
       <?php  while($testados=asignar_a($ejecutarpais,NULL,PGSQL_ASSOC)){?>
           <option value="<?php echo $testados[id_estado]?>"> <?php echo "$testados[estado]"?></option>
      <?php
     }															                              ?>														                       </select>
