<?php
  include ("../../lib/jfunciones.php");
  sesion();
  $ser=$_REQUEST['ser'];
	$q_tiposerv=("select tipos_servicios.id_tipo_servicio,tipos_servicios.id_servicio,tipos_servicios.tipo_servicio from tipos_servicios where tipos_servicios.id_servicio='$ser' ");
	$r_tiposerv=ejecutar($q_tiposerv);

/*echo $ser." ***** ";*/

?>

             <select id="proservi" class="campos"  style="width: 210px;" >
                <option value="0"></option>
	        <?php  while($f_tiposerv=asignar_a($r_tiposerv,NULL,PGSQL_ASSOC)){?>
		       <option value="<?php echo $f_tiposerv[id_tipo_servicio]?>"> <?php echo "$f_tiposerv[tipo_servicio]"?></option>
			 <?php
                             }
                         ?>
	       </select>




       
