<?php
include ("../../lib/jfunciones.php");
sesion();
$proveedor=$_REQUEST['proveedor'];
$fechainicio=$_REQUEST['fechainicio'];
$fechafin=$_REQUEST['fechafin'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */
$q_morbilidad=("select entes.id_tipo_ente,entes.nombre,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.edad,
clientes.sexo,clientes.fecha_nacimiento,procesos.id_proceso,procesos.fecha_recibido,procesos.comentarios,
admin.nombres as adminnombre,procesos.id_titular,procesos.id_beneficiario,personas_proveedores.nombres_prov,
personas_proveedores.apellidos_prov,personas_proveedores.cedula_prov,especialidades_medicas.especialidad_medica,
gastos_t_b.fecha_cita,gastos_t_b.enfermedad from clientes,procesos,gastos_t_b,proveedores,s_p_proveedores,personas_proveedores,
especialidades_medicas,entes,admin,titulares where gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_proveedor=proveedores.id_proveedor
and gastos_t_b.id_proveedor='$proveedor' and gastos_t_b.fecha_cita>='$fechainicio' and gastos_t_b.fecha_cita<='$fechafin'
 and proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor
and s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor
and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
and procesos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente
and procesos.id_admin=admin.id_admin and procesos.id_estado_proceso<>14 and (gastos_t_b.descripcion<>'SERVICIOS POR TERCEROS' and gastos_t_b.nombre<>'SERVICIOS POR TERCEROS');
");
$r_morbilidad=ejecutar($q_morbilidad);


$q_persona=("select * from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor='$proveedor' ");
$r_persona=ejecutar($q_persona);
$f_persona=asignar_a($r_persona);


?>

<table   border=1 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

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

align="center" class="titulo_seccion">Morbilidad del Dr.(a) <?php echo "$f_persona[nombres_prov] $f_persona[apellidos_prov] "?> Fecha Cita: <?php 		if ($fechainicio==$fechafin ){
																					echo $fechainicio;
																					}
																					else{
																							echo "$fechainicio al $fechafin";
																							}
?> </td>
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
		<td class="tdcamposc">Ente</td>
		<td class="tdcamposc">Operador</td>

	</tr>
	<?php
	while($f_morbilidad=asignar_a($r_morbilidad,NULL,PGSQL_ASSOC)){
          $q_benerficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento
          from beneficiarios,clientes where beneficiarios.id_beneficiario=$f_morbilidad[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente");
          $r_benerficiario=ejecutar($q_benerficiario);
          $f_benerficiario=asignar_a($r_benerficiario);

          $q_ordenes=("select gastos_t_b.id_proveedor,count(proveedores.id_proveedor) from gastos_t_b,proveedores,procesos where gastos_t_b.id_proveedor='$proveedor'  and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_titular=$f_morbilidad[id_titular] and procesos.id_beneficiario=$f_morbilidad[id_beneficiario] and gastos_t_b.fecha_cita<='$fechafin' and gastos_t_b.id_proveedor=proveedores.id_proveedor group by gastos_t_b.id_proveedor");
          $r_ordenes=ejecutar($q_ordenes);
          $f_ordenes=asignar_a($r_ordenes);
    ?>

    		<tr>
    		<td class="tdcamposcc"><?php echo $f_morbilidad[id_proceso]?></td>
    			<?php
    		if ($fechainicio==$fechafin ){

    		}
    		else
    		{
    		?>
    		<td class="tdcamposcc"><?php
                   $dias=array("domingo","lunes","martes","miércoles" ,"jueves","viernes","sábado");
      	       $dia=substr($f_morbilidad[fecha_cita],8,2);
                   $mes=substr($f_morbilidad[fecha_cita],5,2);
                   $anio=substr($f_morbilidad[fecha_cita],0,4);
                   $pru=strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);
            	echo "$pru $dia-$mes-$anio";
                    ?></td>

    		<?php
    		}
    		?>
    		<td class="tdcamposcc"><?php
                    $dias=array("domingo","lunes","martes","miércoles" ,"jueves","viernes","sábado");
    		$dia=substr($f_morbilidad[fecha_recibido],8,2);
                    $mes=substr($f_morbilidad[fecha_recibido],5,2);
                    $anio=substr($f_morbilidad[fecha_recibido],0,4);
     	        $pru=strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);
    		echo "$pru $dia-$mes-$anio";
    ?></td>
    		<td class="tdcamposcc"><?php echo "$f_morbilidad[nombres] $f_morbilidad[apellidos]"?></td>
    		<td class="tdcamposcc"><?php echo $f_morbilidad[cedula]?></td>
    		<td class="tdcamposcc"><?php echo "$f_benerficiario[nombres] $f_benerficiario[apellidos]"?></td>
    		<td class="tdcamposcc"><?php echo $f_benerficiario[cedula]?></td>
    		<td class="tdcamposcc"><?php  if ($f_morbilidad[id_beneficiario]==0){
    																	echo calcular_edad($f_morbilidad[fecha_nacimiento]);
    																	}
    																	else
    																	{
    																		echo calcular_edad($f_benerficiario[fecha_nacimiento]);
    																		}
    													?>
    		</td>
    		 <td class="tdcamposcc"><?php



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

    			}

    						?></td>

    		<?php if ($f_morbilidad[id_tipo_ente]==4)
    		{
    			?>
    		<td class="tdcamposrc"><?php echo $f_morbilidad[nombre]?></td>
    		<?php
    		}
    		else
    		{
    		?>
    		<td class="tdcamposcc"><?php echo $f_morbilidad[nombre]?></td>
    		<?php
    		}
    		?>


    		<td class="tdcamposcc"><?php echo $f_morbilidad[adminnombre]?></td>
    	</tr>

    			<?php
		}
		?>

  <tr>
         <td colspan=3 class="tdcamposrc"> TTOTAL DE CITAS PRIMERA VEZ <?php echo $countp?></td>
         <td colspan=3 class="tdcamposrc">TOTAL DE CITAS POR CONTROL <?php echo $countc?></td>
         <td colspan=4 class="tdcamposrc">TOTAL DE CITAS <?php echo $countc + $countp?></td>

        </tr>
</table>
