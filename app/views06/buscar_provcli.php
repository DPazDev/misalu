<?php
include ("../../lib/jfunciones.php");
sesion();

/* Nombre del Archivo: buscar_provcli.php
   Descripción: Realiza la busqueda para el PROVEEDOR CLINICA: Relación PARAMETROS PARA ENTES
*/

$q_proclinica=("select proveedores.id_proveedor, clinicas_proveedores.id_clinica_proveedor, clinicas_proveedores.nombre from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor order by nombre");
$r_proclinica=ejecutar($q_proclinica);


?>


	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>

		<td colspan=4 class="tdcamposcc" ><select id="proveedor" name="proveedor" class="campos"  style="width: 300px;" >
	                              <option value="*@Todos las clinicas">Todas las Clinicas.</option>
				      <?php  while($f_proclinica=asignar_a($r_proclinica,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_proclinica[id_proveedor]?>"> <?php echo "$f_proclinica[nombre]"?></option>
				     <?php }?> 
		</td>	
	</tr>


</table>            

