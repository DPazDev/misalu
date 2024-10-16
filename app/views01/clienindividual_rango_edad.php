<?php

include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$idPoliza=$_REQUEST['poliid'];
$buscarEdadRangos=("select
								polizas.id_moneda,
								polizas_rangos_edades.id_poliza_rango,
								polizas_rangos_edades.edad_inicio,
								polizas_rangos_edades.edad_fin
							from
								polizas,polizas_rangos_edades
							where
								polizas_rangos_edades.id_poliza_rango=polizas.id_poliza_rango and
								polizas.id_poliza=$idPoliza;");
$EdadRAngos=ejecutar($buscarEdadRangos);

?>
	<style>
			#tb_rangos td,th{ height:30px; border: 10px; border-top: 5px red;}"
	</style>
<table class="tabla_citas colortable" id="tb_rangos" cellpadding=0 cellspacing=0 >
              <tr>
			     <th class="tdtitulos">Edades.</th>
                 <th class="tdtitulos">Hombre.</th>
                 <th class="tdtitulos">Mujer.</th>
             </tr>

<?php

$i=0;
while($EdRang=asignar_a($EdadRAngos,NULL,PGSQL_ASSOC)){
	$i++;
$rangoEdad=$EdRang['edad_inicio']."-".$EdRang['edad_fin'];//Edad inicio



?>
              <tr>
								<td class="tdcampos" >
									<input type="hidden" id="edadr<?php echo $i;?>" class="campos"  style="width: 70px;" value="<?php echo $rangoEdad;?>" readonly><span style="font-size:20px"><?php echo $rangoEdad;?></span>
								</td>
                   <td class="tdcampos" >
											<input type="number"  id="edadh<?php echo $i;?>" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
									 </td>
			             <td class="tdcampos" >
			                <input type="number"  id="edadm<?php echo $i;?>" class="campos" style="width: 70px;" min="0" max="20" step="1" value="0">
						       </td>
              </tr>


<?php } ?>
  <tr><tb><input type="hidden" id="cantidadrango" value="<?php echo $i;?>"><tb><tr>
</table>
