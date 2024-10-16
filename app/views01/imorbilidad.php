<?php
include ("../../lib/jfunciones.php");
sesion();
$proveedor=$_REQUEST['proveedor'];
$fechainicio=$_REQUEST['fechainicio'];
$fechafin=$_REQUEST['fechafin'];

$q_date=("select current_date;");
$r_date=ejecutar($q_date);
$f_date=asignar_a($r_date);

$q_hora=("select current_time;");
$r_hora=ejecutar($q_hora);
$f_hora=asignar_a($r_hora);


$fecha_actual = date("d-m-Y");
$hora_actual=date("l jS \of F Y h:i:s A - \G\M\T: P", time());

if ($proveedor==0)
{
$var_proveedor="and gastos_t_b.id_proveedor>'0'";
	}
	else
	{
		$var_proveedor="and gastos_t_b.id_proveedor='$proveedor'";
		}
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */
$q_morbilidad=("select
									gastos_t_b.nombre as nomgasto,
									entes.id_tipo_ente,entes.nombre,
									clientes.nombres,clientes.apellidos,
									clientes.cedula,clientes.edad,
									clientes.sexo,
									clientes.fecha_nacimiento,
									procesos.id_proceso,
									procesos.fecha_recibido,
									procesos.comentarios,
									admin.nombres as adminnombre,
									procesos.id_titular,
									procesos.id_beneficiario,
									personas_proveedores.nombres_prov,
									personas_proveedores.apellidos_prov,
									personas_proveedores.cedula_prov,
									especialidades_medicas.especialidad_medica,
									gastos_t_b.fecha_cita,
									gastos_t_b.enfermedad
								from
									clientes,procesos,
									gastos_t_b,proveedores,
									s_p_proveedores,
									personas_proveedores,
									especialidades_medicas,
									entes,
									admin,
									titulares
						 where
						 			gastos_t_b.id_proceso=procesos.id_proceso and
									gastos_t_b.id_proveedor=proveedores.id_proveedor
									$var_proveedor and
									gastos_t_b.fecha_cita>='$fechainicio' and
									gastos_t_b.fecha_cita<='$fechafin' and
									proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
									s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and
									s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and
									procesos.id_titular=titulares.id_titular and
									titulares.id_cliente=clientes.id_cliente and
									titulares.id_ente=entes.id_ente and
									procesos.id_admin=admin.id_admin and
									procesos.id_estado_proceso<>14
									and procesos.id_estado_proceso<>17
					group by
									nomgasto,
									entes.id_tipo_ente,entes.nombre,
									clientes.nombres,clientes.apellidos,
									clientes.cedula,clientes.edad,
									clientes.sexo,
									clientes.fecha_nacimiento,
									procesos.id_proceso,
									procesos.fecha_recibido,
									procesos.comentarios,
									adminnombre,
									procesos.id_titular,
									procesos.id_beneficiario,
									personas_proveedores.nombres_prov,
									personas_proveedores.apellidos_prov,
									personas_proveedores.cedula_prov,
									especialidades_medicas.especialidad_medica,
									gastos_t_b.fecha_cita,
									gastos_t_b.enfermedad");
$r_morbilidad=ejecutar($q_morbilidad);


$q_persona=("select personas_proveedores.*,especialidades_medicas.*,sucursales.*
from personas_proveedores,especialidades_medicas,sucursales,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor
and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor='$proveedor'
and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
and s_p_proveedores.id_sucursal=sucursales.id_sucursal");
$r_persona=ejecutar($q_persona);
$f_persona=asignar_a($r_persona);


?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table    class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td class="logo">
<img src="../../public/images/head.png">
</td>
<td class="datos_cliente">
Rif: J-31180863-9
</td>
<td class="logo">

</td>
<td class="logo">
</td>
</tr>
<tr>
<td class="datos_cliente">
<?php echo "Impreso el $f_date[date] por $f_admin[nombres] $f_admin[apellidos] a las  $f_hora[timetz]";  ?>
</td>
</tr>
</table>

<table   border=1 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td


<?php
		if ($fechainicio==$fechafin ){
		?>
		colspan=11
		<?php

		}
		else
		{
		?>
colspan=12

<?php
		}
		?>

align="center" class="datos_cliente22">Morbilidad del Dr.(a) <?php echo "$f_persona[nombres_prov] $f_persona[apellidos_prov] "?> Fecha Cita: <?php 		if ($fechainicio==$fechafin ){
																					echo $fechainicio;
																					}
																					else{
																							echo "$fechainicio al $fechafin";
																							}
?> Especialidad: <?php echo $f_persona[especialidad_medica]?> Sucursal <?php echo $f_persona[sucursal]?> </td>
      </tr>
<tr>
		<td class="datos_cliente2">Orden</td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2">Fecha Cita</td>

		<?php
		}
		?>
		<td class="datos_cliente2">Fecha Emision</td>
		<td class="datos_cliente2">Titular</td>
		<td class="datos_cliente2">Cedula</td>
		<td class="datos_cliente2">Beneficiario</td>
		<td class="datos_cliente2">Cedula</td>
		<td class="datos_cliente2">Edad</td>
		<td class="datos_cliente2">Consulta</td>
		<!-- <td class="datos_cliente2">Ente</td> -->
		<td class="datos_cliente2"><div style="width: 80px;">Firma Paciente</div></td>
		<td class="datos_cliente2"><div style="width: 50px;">Hora</div></td>
		<td class="datos_cliente2">Diagnostico</td>
		<td class="datos_cliente2">Reposo</td>

	</tr>
	<?php
		while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){

			$q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
$r_benerficiario=ejecutar($q_benerficiario);
$f_benerficiario=asignar_a($r_benerficiario);

/*$q_ordenes=("select gastos_t_b.id_proveedor,count(proveedores.id_proveedor) from gastos_t_b,proveedores,procesos where gastos_t_b.id_proveedor='$proveedor'  and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_titular=$f_morbilidad[id_titular] and procesos.id_beneficiario=$f_morbilidad[id_beneficiario] and gastos_t_b.fecha_cita<='$fechafin' and gastos_t_b.id_proveedor=proveedores.id_proveedor group by gastos_t_b.id_proveedor");
$r_ordenes=ejecutar($q_ordenes);
$f_ordenes=asignar_a($r_ordenes);*/
?>

		<tr>
		<td class="datos_cliente3"><?php echo $f_morbilidad[id_proceso]?></td>
			<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente3"><?php echo $f_morbilidad[fecha_cita]?></td>

		<?php
		}
		?>
		<td class="datos_cliente3"><?php echo $f_morbilidad[fecha_recibido]?></td>
		<td class="datos_cliente3"><?php echo "$f_morbilidad[nombres] $f_morbilidad[apellidos]"?></td>
		<td class="datos_cliente3"><?php echo $f_morbilidad[cedula]?></td>
		<td class="datos_cliente3"><?php echo "$f_benerficiario[nombres] $f_benerficiario[apellidos]"?></td>
		<td class="datos_cliente3"><?php echo $f_benerficiario[cedula]?></td>
		<td class="datos_cliente3"><?php  if ($f_morbilidad[id_beneficiario]==0){
																	echo calcular_edad($f_morbilidad[fecha_nacimiento]);
																	}
																	else
																	{
																		echo calcular_edad($f_benerficiario[fecha_nacimiento]);
																		}
													?>
		</td>
		 <td class="datos_cliente3"><?php

			if ($f_morbilidad[id_tipo_ente]>0){
			echo $f_morbilidad[comentarios];
			}
			else
			{
							if ($f_ordenes[count]==1)
                                                 {
       	                                        echo "Primera";
                                                $countp++;
						 }
                                                 else
                                                 {
                                                echo "Control";
                                                $countc++;
						}

			}			  ?></td>

		<?php /* if ($f_morbilidad[id_tipo_ente]==4)
		{
			?>
		<td class="datos_cliente3r"><?php echo $f_morbilidad[nombre]?></td>
		<?php
		}
		else
		{
		?>
		<td class="datos_cliente3"><?php echo $f_morbilidad[nombre]?></td>
		<?php
		}*/ //modificacion ing patricia
		?>






			<td class="datos_cliente3"></td>
			<td class="datos_cliente3"></td>

		<td class="datos_cliente3"><?php echo $f_morbilidad[enfermedad]?></td>

		<td>                                   </td>
	</tr>

		<?php
		}
		?>
		<tr>
		<td class="datos_cliente2"></td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2"></td>

		<?php
		}
		?>
		<td class="datos_cliente2">.</td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>

	</tr>
	<tr>
		<td class="datos_cliente2"></td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2"></td>

		<?php
		}
		?>
		<td class="datos_cliente2">.</td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>

	</tr>
	<tr>
		<td class="datos_cliente2"></td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2"></td>

		<?php
		}
		?>
		<td class="datos_cliente2">.</td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>

	</tr>
	<tr>
		<td class="datos_cliente2"></td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2"></td>

		<?php
		}
		?>
		<td class="datos_cliente2">.</td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>

	</tr>
	<tr>
		<td class="datos_cliente2"></td>
		<?php
		if ($fechainicio==$fechafin ){

		}
		else
		{
		?>
		<td class="datos_cliente2"></td>

		<?php
		}
		?>
		<td class="datos_cliente2">.</td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>
		<td class="datos_cliente2"></td>

	</tr>
   <tr>
         <td colspan=3 class="datos_cliente3r"> </td>
         <td colspan=3 class="datos_cliente3r"></td>
         <td colspan=4 class="datos_cliente3r"></td>
   </tr>

     <tr>
       
   </tr>
</table>
