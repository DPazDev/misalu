<?php
include ("../../lib/jfunciones.php");
sesion();

?>

 
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<?php 
 $q_edopro = "select * from s_p_proveedores where nomina=1";
$r_edopro = ejecutar($q_edopro);
while($f_edopro  = asignar_a($r_edopro )){
    $mod_variable="update proveedores set tipo_proveedor='1' where proveedores.id_s_p_proveedor=$f_edopro[id_s_p_proveedor]";
    $fmod_variable=ejecutar($mod_variable);
    $i++;
    ?>
  <tr>
    <td colspan="6" class="titulo_seccion"> id_s_p_proveedor <?php echo  "$i _____ $f_edopro[id_persona_proveedor]";?> </td>
  </tr>
  
<?php 
 }
    ?>

</table>

