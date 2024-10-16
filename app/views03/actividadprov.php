<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $tipodepro=$_REQUEST['actprop'];
  $buscaractividad=("select actividades_pro.id_act_pro,actividades_pro.actividad
                    from
                      actividades_pro
                    where
                      actividades_pro.id_tipo_pro=$tipodepro
                   order by
                      actividades_pro.actividad;");
 $repsbuscaractivi=ejecutar($buscaractividad);
?>

              <select id="activprovee" class="campos"  style="width: 210px;" >
                <option value="0"></option>
	        <?php  while($lasactivi=asignar_a($repsbuscaractivi,NULL,PGSQL_ASSOC)){?>
		       <option value="<?php echo $lasactivi[id_act_pro]?>"> <?php echo "$lasactivi[actividad]"?></option>
			 <?php
                             }
                         ?>
	       </select>
