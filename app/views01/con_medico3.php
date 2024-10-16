<?php
include ("../../lib/jfunciones.php");
sesion();

$id_proveedor=$_REQUEST['id_proveedor'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$fecha=date("Y-m-d");
$q_proveedor=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$id_proveedor ");

$r_proveedor=ejecutar($q_proveedor) or mensaje(ERROR_BD);
$num_filas=num_filas($r_proveedor);
$f_proveedor=asignar_a($r_proveedor);

$q_cita=("select gastos_t_b.fecha_cita,count(gastos_t_b.fecha_cita) 
from gastos_t_b,procesos where gastos_t_b.id_proveedor=$id_proveedor and 
gastos_t_b.fecha_cita>='$fecha' and gastos_t_b.id_proceso=procesos.id_proceso 
and procesos.id_estado_proceso<>14 group by gastos_t_b.fecha_cita order by gastos_t_b.fecha_cita");
$r_cita=ejecutar($q_cita) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_cita);


?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_citas"  border=1 cellpadding=0 cellspacing=0>
<tr>
                <td colspan=4   class="titulo_seccion">Horario</td>

</tr>
<tr>
                <td colspan=4   class="tdcamposgcc"><?php echo $f_proveedor[horario]?></td>

</tr>
<tr>
                <td colspan=4   class="titulo_seccion">Comentarios</td>

</tr>
<tr>
                <td colspan=4   class="tdcamposgcc"> <?php echo $f_proveedor[comentarios_prov]?></td>

</tr>
<tr>
                <td colspan=4 class="tdcamposc"><input type="hidden" name="monto" value="<?php echo $f_proveedor[monto] ?>" > </td>             
</tr>


<tr> <td colspan=4 class="titulo_seccion">Citas Medicas <?php
                        $url="views01/imorbilidadp.php?fechainicio=$f_cita[fecha_cita]&fechafin=$f_cita[fecha_cita]&proveedor=$id_proveedor&cedula=$cedula";
                        ?> </td>
      </tr>
<tr>
                <td colspan=1  class="tdcamposc"> Fechas de Las Citas</td>
                <td colspan=1  class="tdcamposac">Total de Citas General</td>
                <td colspan=1  class="tdcamposrc">Total de Citas Privadas</td>
				<td colspan=1  class="tdcamposc">Total Citas</td>

</tr>

  <?php
             while($f_cita=asignar_a($r_cita,NULL,PGSQL_ASSOC)){
				
				$q_citaf=("select procesos.id_proceso,entes.id_tipo_ente,gastos_t_b.fecha_cita,
count(gastos_t_b.id_proceso) 
from gastos_t_b,procesos,entes,titulares where gastos_t_b.id_proveedor=$id_proveedor and gastos_t_b.fecha_cita='$f_cita[fecha_cita]' 
and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_titular=titulares.id_titular 
and titulares.id_ente=entes.id_ente and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso<>14  
group by procesos.id_proceso,entes.id_tipo_ente,gastos_t_b.fecha_cita order by gastos_t_b.fecha_cita");
$r_citaf=ejecutar($q_citaf) or mensaje(ERROR_BD);
				$con1=0;
				$con2=0;
				while($f_citaf=asignar_a($r_citaf,NULL,PGSQL_ASSOC)){
					if ($f_citaf[id_tipo_ente]==4){
					$con2++;
					}
					else
					{
						$con1++;
						}
					}
				
                ?>
<tr>
		<td colspan=1 class="tdcamposc"> <?php echo $f_cita[fecha_cita];?></td>
		<td colspan=1 class="tdcamposac"><?php echo $con1;?></td>
		<td colspan=1 class="tdcamposrc"><?php echo $con2;?></td>
		<td colspan=1 class="tdcamposc"><?php echo ($con1 + $con2);?>
<?php
                        $url="views01/imorbilidad7.php?fechainicio=$f_cita[fecha_cita]&fechafin=$f_cita[fecha_cita]&proveedor=$id_proveedor";
                        ?> <a href="<?php echo $url; ?>" title="Morbilidad"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 500, overlayClose: false}); return false;" class="boton">Ver Citas</a>
</td>

</tr>
<?php
}
?>

<tr>
                <td colspan=2 class="tdcamposc"> <input type="hidden" name="fecha" value="<?php echo $fecha ?>"> </td>
</tr>

</table>
</tr>

</table>
