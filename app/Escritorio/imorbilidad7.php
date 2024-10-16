<?php
include ("../../lib/jfunciones.php");
sesion();
$proveedor=$_REQUEST['proveedor'];
$fechainicio=$_REQUEST['fechainicio'];
$fechafin=$_REQUEST['fechafin'];
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
$q_morbilidad=("select entes.id_tipo_ente,entes.nombre ,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,clientes.sexo,clientes.fecha_nacimiento,procesos.comentarios,procesos.id_proceso,procesos.fecha_recibido,procesos.id_titular,procesos.id_beneficiario,gastos_t_b.fecha_cita,gastos_t_b.enfermedad,count(gastos_t_b.id_proceso) from clientes,procesos,gastos_t_b,proveedores,clinicas_proveedores,entes,titulares where gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_proveedor=proveedores.id_proveedor and proveedores.id_proveedor=$proveedor and gastos_t_b.fecha_cita>='$fechainicio' and gastos_t_b.fecha_cita<='$fechafin' and proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and titulares.id_cliente=clientes.id_cliente and procesos.id_estado_proceso<>14 group by entes.id_tipo_ente,entes.nombre,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,clientes.sexo,clientes.fecha_nacimiento,procesos.comentarios,procesos.id_proceso,procesos.fecha_recibido,procesos.id_titular,procesos.id_beneficiario,gastos_t_b.fecha_cita,gastos_t_b.enfermedad
");
$r_morbilidad=ejecutar($q_morbilidad);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td 


<?php 
		if ($fechainicio==$fechafin ){
		?>
		colspan=10
		<?php
		
		}
		else
		{
		?>
colspan=11

<?php
		}
		?>

class="titulo_seccion"></td>
      </tr>
<tr>
		<td class="tdcamposc">Orden</td>
		<?php 
		if ($fechainicio==$fechafin ){
		
		}
		else
		{
		?>
		<td class="tdcamposc">Fecha Cita</td>
		
		<?php
		}
		?>
		<td class="tdcamposc">Fecha Emision</td>
		<td class="tdcamposc">Titular</td>
		<td class="tdcamposc">Cedula</td>
		<td class="tdcamposc">Beneficiario</td>
		<td class="tdcamposc">Cedula</td>
		<td class="tdcamposc">Edad</td>
		<td class="tdcamposc">Consulta</td>
		<td class="tdcamposc">Descripcion</td>
		<td class="tdcamposc">Ente</td>
		
	</tr>
	<?php		
		while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){
			
			$q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
$r_benerficiario=ejecutar($q_benerficiario);
$f_benerficiario=asignar_a($r_benerficiario);

$q_ordenes=("select * from gastos_t_b,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso=$f_morbilidad[id_proceso] ");
$r_ordenes=ejecutar($q_ordenes);
$descripcion="";
	while($f_ordenes=asignar_a($r_ordenes,NULL,PGSQL_ASSOC)){
		$nombre=$f_ordenes[nombre];
		$descripcion.= $f_ordenes[descripcion];
		}
			
?>
		
		<tr>
		<td class="tdcamposcc"><?php echo $f_morbilidad[id_proceso]?></td>
			<?php 
		if ($fechainicio==$fechafin ){
		
		}
		else
		{
		?>
		<td class="tdcamposcc"><?php echo $f_morbilidad[fecha_cita]?></td>
		
		<?php
		}
		?>
		<td class="tdcamposcc"><?php echo $f_morbilidad[fecha_recibido]?></td>
		<td class="tdcamposcc"><?php echo "$f_morbilidad[nombres] $f_morbilidad[apellidos]"?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[cedula]?></td>
		<td class="tdcamposcc"><?php echo "$f_benerficiario[nombres] $f_benerficiario[apellidos]"?></td>
		<td aling="center" class="tdcamposcc"><?php echo $f_benerficiario[cedula]?></td>
		<td class="tdcamposcc"><?php  if ($f_morbilidad[id_beneficiario]==0){
																	echo calcular_edad($f_morbilidad[fecha_nacimiento]);
																	}
																	else
																	{
																		echo calcular_edad($f_benerficiario[fecha_nacimiento]);
																		}
													?>
		</td>
		<td class="tdcamposcc"><?php 	echo "$nombre" ?></td>
		<td class="tdcamposcc"><?php echo $descripcion?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[nombre]?></td>
	</tr>
		
			<?php
		}
		?>
   <tr>
         <td colspan=3 class="datos_cliente3r"> </td>
         <td colspan=3 class="datos_cliente3r"></td>
         <td colspan=4 class="datos_cliente3r"></td>
   </tr>	
</table>


