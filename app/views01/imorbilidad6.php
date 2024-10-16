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


$q_persona=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$proveedor
                order by clinicas_proveedores.nombre");
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
</tr>
<tr>
<td class="datos_cliente">
Rif: J-31180863-9
</td>
</tr>
</table>
<br>
</br>
<br>
</br>
<br>
</br>
<br>
</br>
<br>
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

align="center" class="datos_cliente22"> 
<?php echo "$f_persona[nombres]  "?> 
Fecha Cita: <?php 		if ($fechainicio==$fechafin ){
																					echo $fechainicio;
																					}
																					else{
																							echo "$fechainicio al $fechafin";
																							}
?>
 
 </td>
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
		<td class="datos_cliente2">Ente</td>
		<td class="datos_cliente2">Analista</td>
		<td class="datos_cliente2">Telefonos</td>
		
	</tr>
	<?php		
		while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){
   	
			$q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,clientes.telefono_hab,clientes.telefono_otro,clientes.celular from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
$r_benerficiario=ejecutar($q_benerficiario);
$f_benerficiario=asignar_a($r_benerficiario);

$q_ordenes=("select admin.nombres ,admin.apellidos,gastos_t_b.* from gastos_t_b,admin,procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso=$f_morbilidad[id_proceso] and procesos.id_admin=admin.id_admin ");
$r_ordenes=ejecutar($q_ordenes);
$descripcion="";
	while($f_ordenes=asignar_a($r_ordenes,NULL,PGSQL_ASSOC)){
		$nombread="$f_ordenes[nombres] $f_ordenes[apellidos]";
		$nombre=$f_ordenes[nombre];
		$descripcion.=" $f_ordenes[descripcion], ";
		}
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
		 <td class="tdcamposcc"><?php 	echo "$nombre ($descripcion)" ?></td>
						
		<?php if ($f_morbilidad[id_tipo_ente]==4) 
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
		}
		?>
		<td class="datos_cliente3"><?php echo $nombread?></td>
		<td class="datos_cliente3"><?php echo "$f_morbilidad[telefono_hab]---$f_morbilidad[telefono_otro]---$f_morbilidad[celular]---$f_benerficiario[telefono_hab]---$f_benerficiario[telefono_otro]---$f_benerficiario[celular]"; ?></td>
		
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


