<?php
include ("../../lib/jfunciones.php");
sesion();
$eliddepartamento = $_REQUEST['iddepartamento'];
$usuariodepart = ("select admin.nombres,admin.apellidos,admin.id_admin from 
                   admin where id_departamento=$eliddepartamento and activar='1' order by admin.nombres");
$rusuariodepart = ejecutar($usuariodepart);                 
?>
<select id="idusur" class="campos"  style="width: 230px;">
             <option value=""></option>
             <option value="0">TODOS</option>
              <?php
              while($losusudeparta = asignar_a($rusuariodepart,NULL,PGSQL_ASSOC)){
           ?>
              <option value="<?php echo $losusudeparta[id_admin]?>">
                     <?php echo "$losusudeparta[nombres] $losusudeparta[apellidos]"?>
               </option>
             <?}?>
</select>
