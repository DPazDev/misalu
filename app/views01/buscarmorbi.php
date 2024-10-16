<?php
include ("../../lib/jfunciones.php");
sesion();
$proveedor=$_REQUEST['proveedor'];
$proveedorc=$_REQUEST['proveedorc'];
if ($proveedor=="*")
{
$var_proveedor="and gastos_t_b.id_proveedor>'0'";
$proveedor=0;
	}
	else
	{
		$var_proveedor="and gastos_t_b.id_proveedor='$proveedor'";
		}

$fechainicio=$_REQUEST['fechainicio'];
$fechafin=$_REQUEST['fechafin'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

if ($f_admin[id_ente]>0){
    $condicionente="entes.id_ente=$f_admin[id_ente] and";
    }

if ($proveedorc=='0'){
/* **** busco las citas medicas *** */
$q_morbilidad=("select
                                entes.id_tipo_ente,
                                entes.nombre,
                                estados_procesos.estado_proceso,
                                clientes.nombres,
                                clientes.apellidos,
                                clientes.cedula,
                                clientes.edad,
                                clientes.sexo,
                                clientes.fecha_nacimiento,
                                procesos.comentarios,
                                procesos.id_proceso,
                                procesos.fecha_recibido,
                                procesos.id_titular,
                                procesos.id_beneficiario,
                                personas_proveedores.nombres_prov,
                                personas_proveedores.apellidos_prov,
                                personas_proveedores.cedula_prov,
                                especialidades_medicas.especialidad_medica,
                                gastos_t_b.fecha_cita,
                                gastos_t_b.enfermedad
                            from
                                clientes,
                                procesos,
                                gastos_t_b,
                                proveedores,
                                s_p_proveedores,
                                personas_proveedores,
                                especialidades_medicas,
                                entes,
                                titulares,
                                admin,
                                estados_procesos

                            where
                                procesos.id_estado_proceso=estados_procesos.id_estado_proceso and
                                gastos_t_b.id_proceso=procesos.id_proceso and
                                gastos_t_b.id_proveedor=proveedores.id_proveedor
                                $var_proveedor and
                                gastos_t_b.fecha_cita>='$fechainicio' and
                                gastos_t_b.fecha_cita<='$fechafin' and
                                proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
                                s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and
                                s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and procesos.id_titular=titulares.id_titular and
                                titulares.id_cliente=clientes.id_cliente and
                                titulares.id_ente=entes.id_ente and
                                $condicionente
                                procesos.id_admin=admin.id_admin and
                                procesos.id_estado_proceso<>14 and
                                procesos.id_estado_proceso<>17
												group by
																entes.id_tipo_ente,
																entes.nombre,
																estados_procesos.estado_proceso,
																clientes.nombres,
																clientes.apellidos,
																clientes.cedula,
																clientes.edad,
																clientes.sexo,
																clientes.fecha_nacimiento,
																procesos.comentarios,
																procesos.id_proceso,
																procesos.fecha_recibido,
																procesos.id_titular,
																procesos.id_beneficiario,
																personas_proveedores.nombres_prov,
																personas_proveedores.apellidos_prov,
																personas_proveedores.cedula_prov,
																especialidades_medicas.especialidad_medica,
																gastos_t_b.fecha_cita,
																gastos_t_b.enfermedad
");
$r_morbilidad=ejecutar($q_morbilidad);
$num_filas=num_filas($r_morbilidad);
$q_horario=("select *  from s_p_proveedores,proveedores where proveedores.id_proveedor='$proveedor' and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor
");
$r_horario=ejecutar($q_horario);
$f_horario=asignar_a($r_horario);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5 colortable"  cellpadding='0' cellspacing='0' >

    <tr> <td colspan='12' class="titulo_seccion">  Citas Medicas</td></tr>
      <?php if ($f_admin[id_ente]>0){

    ?>




    <?php

    }
    else
    {
    ?>
<tr>
<td
<?php
		if ($fechainicio==$fechafin ){
		?>
		colspan=12
		<?php

		}
		else
		{
		?>
colspan=12

<?php
		}
		?>

class="titulo_seccion">Horarios <?php echo "$f_horario[horario]; Cupos $f_horario[comentarios_prov] "?> > </td>
      </tr>
<tr>
<?php
}
?>
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
		<td class="tdcamposc">Ente</td>
		<td class="tdcamposc">Enfermedad</td>
		<td class="tdcamposc">Estado</td>

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
		<td class="tdcamposcc"><?php 	if ($f_morbilidad[id_tipo_ente]>0){
			echo "$f_morbilidad[especialidad_medica] ($f_morbilidad[comentarios])";
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

			}  ?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[nombre]?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[enfermedad]?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad['estado_proceso']?></td>
	</tr>

			<?php
		}
		?>

	<tr> <td


<?php
		if ($fechainicio==$fechafin ){
		?>
		colspan=13
		<?php

		}
		else
		{
		?>
colspan=12

<?php
		}
		?>

class="titulo_seccion">Total de Citas <?php echo $num_filas ?></td>
      </tr>
<tr>
<?php
        if ($f_admin[id_ente]>0){
    }
    else
    {
        ?>
	<tr>
		<td class="tdcamposc"></td>
		<td colspan=3 class="tdtitulos">
		<?php

			$url="'views01/imorbilidad.php?fechainicio=$fechainicio&fechafin=$fechafin&proveedor=$proveedor'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir Morbilidad Medica</a>
</td>
		<td class="tdcamposc">

 <?php
                        $url="'views01/imorbilidad4.php?fechainicio=$fechainicio&fechafin=$fechafin&proveedor=$proveedor'";
                        ?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir2</a>

		</td>
		<td colspan=4 class="tdcamposc">

 <?php
                        $url="'views01/imorbilidad5.php?fechainicio=$fechainicio&fechafin=$fechafin&proveedor=$proveedor'";
                        ?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir Morbilidad Con Tlf Paciente</a>

		</td>
	</tr>
    <?php
    }
    ?>
</table>
<?php
}
    if ($proveedorc>'0' || $proveedorc=='*')
{

            if ($proveedorc=="*")
{

    $var_proveedor=" ";
}
else
{
    $var_proveedor=" gastos_t_b.id_proveedor='$proveedorc' and ";
    }

/* **** busco las citas medicas *** */
$q_morbilidad=("select
                                    entes.id_tipo_ente,
                                    entes.nombre,
                                    clientes.nombres,
                                    clientes.apellidos,
                                    clientes.cedula,
                                    clientes.edad,
                                    clientes.sexo,
                                    clientes.fecha_nacimiento,
                                    procesos.comentarios,
                                    procesos.id_proceso,
                                    procesos.nu_planilla,
                                    procesos.fecha_recibido,
                                    procesos.id_titular,
                                    procesos.id_beneficiario,
                                    gastos_t_b.fecha_cita,
                                    gastos_t_b.enfermedad,
                                    servicios.servicio,
                                    count(gastos_t_b.id_proceso)
                            from
                                    clientes,
                                    procesos,
                                    gastos_t_b,
                                    proveedores,
                                    clinicas_proveedores,
                                    entes,
                                    titulares,
                                    servicios
                            where
                                    gastos_t_b.id_proceso=procesos.id_proceso and
                                    gastos_t_b.id_proveedor=proveedores.id_proveedor and
                                      $var_proveedor
                                    gastos_t_b.fecha_cita>='$fechainicio' and
                                    gastos_t_b.fecha_cita<='$fechafin' and
                                    proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and
                                    procesos.id_titular=titulares.id_titular and
                                    titulares.id_ente=entes.id_ente and
                                    $condicionente
                                    titulares.id_cliente=clientes.id_cliente and
                                    procesos.id_estado_proceso<>14 and
                                    gastos_t_b.id_servicio=servicios.id_servicio
                            group by
                                    entes.id_tipo_ente,
                                    entes.nombre,
                                    clientes.nombres,
                                    clientes.apellidos,
                                    clientes.cedula,
                                    clientes.edad,
                                    clientes.sexo,
                                    clientes.fecha_nacimiento,
                                    procesos.comentarios,
                                    procesos.id_proceso,
                                    procesos.nu_planilla,
                                    procesos.fecha_recibido,
                                    procesos.id_titular,
                                    procesos.id_beneficiario,
                                    gastos_t_b.fecha_cita,
                                    gastos_t_b.enfermedad,
                                    servicios.servicio
");
$r_morbilidad=ejecutar($q_morbilidad);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5 colortable"  cellpadding=0 cellspacing=0>
    <tr> <td colspan=13 class="titulo_seccion">  Citas Medicas</td></tr>
<tr class="titulo_seccion"> <td


<?php
		if ($fechainicio==$fechafin ){
		?>
		colspan=13
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
        <td class="tdcamposc">Servicio</td>
               <?php  if ($proveedorc>0 || $proveedorc=="*")
            {
                ?>
				<td class="tdcamposc">Planilla</td>
                <?php
                }
                else
                {
                    ?>
                <td class="tdcamposc">Descripcion</td>
                <?php
                }
                ?>
		<td class="tdcamposc">Ente</td>
		<td class="tdcamposc">Estado proceso</td>


	</tr>
	<?php
		while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){

			$q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
$r_benerficiario=ejecutar($q_benerficiario);
$f_benerficiario=asignar_a($r_benerficiario);

$q_ordenes=("select * from gastos_t_b,procesos,estados_procesos where gastos_t_b.id_proceso=procesos.id_proceso and estados_procesos.id_estado_proceso=procesos.id_estado_proceso and procesos.id_proceso=$f_morbilidad[id_proceso] ");
$r_ordenes=ejecutar($q_ordenes);
$descripcion="";
	while($f_ordenes=asignar_a($r_ordenes,NULL,PGSQL_ASSOC)){
		$nombre=$f_ordenes[nombre];
		$descripcion.= " $f_ordenes[descripcion], ";
		$estado_pro=$f_ordenes['estado_proceso'];
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
		<td class="tdcamposcc"><?php  if ($f_morbilidad[id_beneficiario]==0)
                                                                    {
																	echo calcular_edad($f_morbilidad[fecha_nacimiento]);
																	}
																	else
																	{
																		echo calcular_edad($f_benerficiario[fecha_nacimiento]);
                                                                    }
													?>
		</td>
		<td class="tdcamposcc"><?php  echo "$f_morbilidad[servicio] ($nombre)"; ?></td>
		<td class="tdcamposcc"><?php
                                                    if ($f_admin[id_ente]>0){
                                                       echo $f_morbilidad[nu_planilla];
                                                        }
                                                        else
                                                        {
                                                    echo $descripcion;
                                                        }?></td>
		<td class="tdcamposcc"><?php echo $f_morbilidad[nombre]?></td>
		<td class="tdcamposc"><?php echo $estado_pro;?></td><!-- ESTADO DEL PROCESO-->
	</tr>

			<?php
		}
		?>


	<tr>
		<td class="tdcamposc"></td>
		<td class="tdcamposc"></td>
		<td class="tdcamposc"></td>
		<td  class="tdtitulos">

</td>
		<td class="tdcamposc">
		</td>
		<td colspan=4 class="tdcamposc">

 <?php

if ($f_admin[id_ente]>0){
    }
    else
    {
                        $url="'views01/imorbilidad6.php?fechainicio=$fechainicio&fechafin=$fechafin&proveedor=$proveedorc'";
                        ?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir Morbilidad Con Tlf Paciente</a>
<?php
}
?>

		</td>
	</tr>
</table>
<?php
}

?>
