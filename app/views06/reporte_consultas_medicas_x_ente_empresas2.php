<?php
/* Nombre del Archivo: reporte_consultas_medicas_x_ente_empresas2.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Consultas Médicas Por Entes (Totales por Entes)
*/  

 include ("../../lib/jfunciones.php");
   sesion();

/*seleccionar el admin asignado al usuario autorizado de la empresa especifica que desea realizar la consulta*/

$admin= $_SESSION['id_usuario_'.empresa];

   $fecha1=$_REQUEST['fecha1'];
   $fecha2=$_REQUEST['fecha2'];

list($estado)=explode("@",$_REQUEST['estado']);
if($estado=='ANULADOS')	        $condicion_estado= "and (procesos.id_estado_proceso='14')";
if($estado=='ASISTIDOS')	$condicion_estado= "and (procesos.id_estado_proceso='2' or procesos.id_estado_proceso='7')";


	$q_ente=("select entes.id_ente, entes.nombre from entes,admin where entes.id_ente=admin.id_ente and admin.id_admin='$admin'");
   	$r_ente = ejecutar($q_ente);
   	$f_ente=asignar_a($r_ente);

/*echo $fecha1;
echo $fecha2;
echo $estado;
echo $f_ente['nombre'];
echo $f_ente['id_ente'];
echo $condicion_estado;*/
?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="19">Consultas M&eacute;dicas por Entes, Empresas Autorizadas, Relaci&oacute;n Orden de Atenci&oacute;n y Emergencias con Fecha de Emisión de la Orden</td>
</table>	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="tdtitulosd" colspan=19> Relaci&oacute;n de <?php echo "$fecha1 al $fecha2";?></td>
	</tr> 
		<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> 
<td class="tdcampos" colspan=15>&nbsp; </td>
		<td class="tdcampos" colspan=4><?php if ($id_sucursal==0) echo " TODAS LAS SUCURSALES" ;
else echo $fsucur[sucursal]?> &nbsp;  ****      CONSULTAS <?php if($estado=='ANULADOS') echo "ANULADAS"; else if($estado=='ASISTIDOS') echo "ASISTIDAS";?>  </td>
	</tr>
<tr><td>&nbsp;</td></tr>
</table>


<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 

	<tr> 
	<td class="tdcampos">ENTE:</td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_ente['nombre']; ?>
	<td class="tdcampos">ESPECIALIDAD:</td>
	<td class="tdcampos">TOTAL:</td>
</tr>

<?php
$qreporte=("select especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica, count(especialidades_medicas.id_especialidad_medica) from procesos,gastos_t_b,especialidades_medicas,admin,titulares,proveedores, s_p_proveedores
where   gastos_t_b.id_proceso=procesos.id_proceso and 
	procesos.fecha_recibido between '$fecha1' and '$fecha2' and 
	(gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6' ) and 
	procesos.id_admin=admin.id_admin $condicion_estado and 
	gastos_t_b.id_proveedor=proveedores.id_proveedor and 
	s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
	(s_p_proveedores.nomina='1' or s_p_proveedores.nomina='0') and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente='$f_ente[id_ente]' and 
        admin.id_sucursal>0 and
	s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica 
	group by especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica 
	ORDER BY especialidades_medicas.especialidad_medica");

/*echo $qreporte;*/

 $contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{?><?php
			


	    $url="views06/ireporte_consultas_medicas_x_ente_empresas2.php?fecha1=$fecha1&fecha2=$fecha2&estado=$estado&especialidad=$freporte[id_especialidad_medica]";
                        ?>
<tr>
<?php
echo"

	        <td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	        <td class=\"tdtitulos\">$freporte[especialidad_medica]</td> 
	        <td class=\"tdtitulos\"> &nbsp;&nbsp;&nbsp;&nbsp; $freporte[count]</td> 
<td class=\"tdcamposcc\">  <a href=\"$url\" title=\"Pacientes por Especialidad\" OnClick=\"Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;\" class=\"boton\">$freporte[especialidad_medica]</a> 
		</td>
</tr>

";
       $contador=$contador+$freporte['count'];
}?>
<tr>
	<td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	<td class="tdcampos">TOTAL CONSULTAS:</td>
        <td class="tdcampos"> &nbsp;&nbsp;&nbsp; <?php echo $contador; ?></td>
</tr>

</table>	

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>


