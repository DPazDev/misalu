<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $contrato=$_REQUEST['lacotiza'];
  $quienlahizo=("select comisionados.id_comisionado,comisionados.id_admin,comisionados.nombres,comisionados.apellidos from tbl_cliente_cotizacion,comisionados where 
                           tbl_cliente_cotizacion.id_cliente_cotizacion=$contrato and
tbl_cliente_cotizacion.id_admin=comisionados.id_admin;");
$repquinhizo=ejecutar($quienlahizo);
$dataquinhizo=assoc_a($repquinhizo);
$nombres="$dataquinhizo[nombres] $dataquinhizo[apellidos]";
$idcomision=$dataquinhizo[id_comisionado];
$quienlahizo1=("select comisionados.id_comisionado,comisionados.id_admin,comisionados.nombres,comisionados.apellidos 
                from comisionados order by comisionados.nombres;");
$repquinhizo1=ejecutar($quienlahizo1);
?>

 <td class="tdcampos"  colspan="1"><select id="comisionado" class="campos" style="width: 210px;" >
     <option value="<?php echo $idcomision?>"> <?php echo "$nombres"?></option>
       <?php  while($comision=asignar_a($repquinhizo1,NULL,PGSQL_ASSOC)){?>
           <option value="<?php echo $comision[id_comisionado]?>"> <?php echo "$comision[nombres] $comision[apellidos]"?></option>
      <?php
     }  ?>
</select>
</td>