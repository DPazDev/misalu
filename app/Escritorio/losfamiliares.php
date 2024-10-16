<?
include ("../../lib/jfunciones.php");
sesion();
$generoes=$_POST['paragenero'];
$losgeneros=("select parentesco.id_parentesco,parentesco.parentesco from parentesco where parentesco.genero=$generoes");
$replosgeneros=ejecutar($losgeneros);
?>
<select id="clienparent" class="campos" style="width: 160px;">
                              <option value=""></option>
			   <?php  while($verparen=asignar_a($replosgeneros,NULL,PGSQL_ASSOC)){?>
                              <option value="<?php echo $verparen[id_parentesco]?>"> <?php echo "$verparen[parentesco]"?></option>
			   <?php
			  }
			  ?>
</select>