<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$tipo_cliente=$_REQUEST['tipo_cliente'];
$cedula=$_REQUEST['cedula'];


if ($tipo_cliente==0){
	$tipo_clientes="and entes.id_tipo_ente<>4 and entes.id_tipo_ente<>6 and entes.id_tipo_ente<>8";
	}
	else
	{

		$tipo_clientes="and (entes.id_tipo_ente=4 or entes.id_tipo_ente=6 or entes.id_tipo_ente=8)";
		}
		
		if ($tipo_cliente!='*'){
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	
		<?php
		/* **** busco si es titular **** */
$q_cliente=("select
							clientes.id_cliente,
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							estados_clientes.estado_cliente,
							titulares.id_titular,
							titulares.id_ente,
							entes.nombre,
							entes.fecha_inicio_contrato,
							entes.fecha_renovacion_contrato,
							entes.fecha_inicio_contratob,
							entes.fecha_renovacion_contratob,
							entes.id_tipo_ente,
							tbl_tipos_entes.tipo_ente,
							titulares.tipocliente
					from 
							clientes,
							titulares,
							estados_t_b,
							estados_clientes,
							entes,
							tbl_tipos_entes
					where 
							clientes.cedula='$cedula' and 
							clientes.id_cliente=titulares.id_cliente and 
							titulares.id_titular=estados_t_b.id_titular and 
							estados_t_b.id_beneficiario=0 and 
							estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
							titulares.id_ente=entes.id_ente and 
							estados_clientes.id_estado_cliente=4 and
							tbl_tipos_entes.id_tipo_ente=entes.id_tipo_ente
							$tipo_clientes");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);

/* **** busco si es beneficiario **** */
$q_clienteb=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								beneficiarios.id_beneficiario,
								beneficiarios.id_titular,
								entes.nombre,
								entes.id_ente,
								tbl_tipos_entes.tipo_ente,
								entes.id_tipo_ente
						from 
								clientes,
								estados_clientes,
								beneficiarios,
								entes,
								titulares,
								estados_t_b,
								tbl_tipos_entes 
						where 
								clientes.cedula='$cedula' and 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								beneficiarios.id_titular=titulares.id_titular and 
								titulares.id_ente=entes.id_ente and 
								estados_clientes.id_estado_cliente=4 and
								tbl_tipos_entes.id_tipo_ente=entes.id_tipo_ente 
								$tipo_clientes");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);

if ($num_filas==0 and $num_filasb==0){
	?>
<tr>
<td  colspan=4 class="titulo_seccion">
No hay Ningun Cliente con ese Numero de Cedula
</td>
</tr>
<?php
	}
	else
	{
		?>
		
		
		<tr>
		<td  class="tdtitulos">* Seleccione  el Ente.</td>
<td   class="tdtitulos">
		<select style="width: 200px;" name="bus_tip_clien" class="campos" >
		<?php

while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){

		?>
		
					
				
		<option value="<?php echo "$f_cliente[id_ente]@$f_cliente[id_titular]@0"?>"> <?php echo "$f_cliente[tipo_ente] $f_cliente[nombre]  Como Titular $f_cliente[nombres] $f_cliente[apellidos]"?>			</option>
		<?php
		
		}
		

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
	
	$q_clientebt=("select 
										clientes.nombres,
										clientes.apellidos,
										clientes.cedula
							from 
										clientes,
										titulares
							where 
										clientes.id_cliente=titulares.id_cliente and
										titulares.id_titular='$f_clienteb[id_titular]'");
	$r_clientebt=ejecutar($q_clientebt) or mensaje(ERROR_BD);
	$f_clientebt=asignar_a($r_clientebt);
		?>
		
					
				
		<option value="<?php echo "$f_clienteb[id_ente]@$f_clienteb[id_titular]@$f_clienteb[id_beneficiario]"?>"> <?php echo "$f_clienteb[tipo_ente] $f_clienteb[nombre]  Como Beneficiario $f_clienteb[nombres] $f_clienteb[apellidos] Titular $f_clientebt[nombres] $f_clienteb[apellidost]"?>			</option>
		<?php
		
		}
		?>
		
		
		</select>
</td>
		
<td  class="tdtitulos">* Seleccione  el Servicio.</td>
<td  class="tdcampos">
		<select name="servicio" class="campos" OnChange="reg_oats();">
		<?php $q_servicio=("select * from servicios  where servicios.id_servicio<>7 and servicios.id_servicio<>12 and servicios.id_servicio<>5 order by servicios.servicio");
		$r_servicio=ejecutar($q_servicio);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_servicio[id_servicio]?>"> <?php echo "$f_servicio[servicio]"?></option>
		<?php
		}
		?>
		</select>
		</td>
		</tr>
		
		

		
		
	<?php 
	}
	?>	

</table>
	<div id="reg_oats"></div>
<?php
}
?>