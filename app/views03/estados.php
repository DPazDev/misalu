<?
include ("../../lib/jfunciones.php");
sesion();
$ciudadabuscar=$_POST['laciudad'];
$lasciudades=("select id_ciudad,ciudad from ciudad where id_estado='$ciudadabuscar' order by ciudad");
$ejecuciu=ejecutar($lasciudades);
?>
<select id="ciuclin" class="campos"  style="width: 210px;" >
	       <?php  while($tciudad=asignar_a($ejecuciu,NULL,PGSQL_ASSOC)){?>
	                  <option value="<?php echo $tciudad[id_ciudad]?>"> <?php echo "$tciudad[ciudad]"?></option>
	       <?php     }    
	       ?>                                                                                                                                       </select>
