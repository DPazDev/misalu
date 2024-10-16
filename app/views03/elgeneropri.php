<?
include ("../../lib/jfunciones.php");
sesion();
$genero=$_POST['elgenero'];
$lospartentesco=("select parentesco.id_parentesco,parentesco.parentesco from parentesco where parentesco.genero='$genero' order by parentesco.parentesco;");
$replosparentes=ejecutar($lospartentesco);
?>

			  <select id="elparentesco" class="campos"  style="width: 230px;" >
			        <option value=""></option>
              <?php  
			         while($parentescos=asignar_a($replosparentes,NULL,PGSQL_ASSOC)){
				?>
					<option value="<?php echo $parentescos[id_parentesco]?>"> <?php echo "$parentescos[parentesco]"?>    
					</option>
			      <?}?>
			 </select>  
		  