<?php
include ("../../lib/jfunciones.php");
sesion();
//Reporte para buscar las ordenes usadas en cualquiera de los servicios prestados a prima a riesgo y lo muestra de acuerdo al estado en que se encuentre el servicio
$admin= $_SESSION['id_usuario_'.empresa];
$dateField1=$_POST[dateField1];
$dateField2=$_POST[dateField2];
$sucursal=$_POST[sucursal];
$servicio=$_POST[servicio];
list($tipo_ente,$nom_tipo_ente)=explode("@",$_POST['tipo_ente']);
list($ente,$nom_ente)=explode("@",$_POST['ente']);
$tipo_cliente=$_POST[tipo_cliente];
$tipo_proveedor=$_POST[tipo_proveedor];
$codigot=time();
$codigo=$admin . $codigot;



if  ($sucursal==0){
	$sucursales="and admin.id_sucursal>0";
	}
	else
	{
		$sucursales="and admin.id_sucursal=$sucursal";
		}

if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio>0";
	$servicios1="servicios.id_servicio>0";
	}
	else
	{
		$servicios="and gastos_t_b.id_servicio=$servicio";
		$servicios1="servicios.id_servicio=$servicio";
		}
		if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}
	
		if  ($ente==0){
	$entes="and entes.id_ente>0";
	$entes1="entes.id_ente>0";
	}
	else
	{
		$entes="and entes.id_ente=$ente";
		$entes1="entes.id_ente=$ente";
	}
			if  ($tipo_cliente==0){
	$tipo_clientes="and procesos.id_beneficiario=0";
	$tipo='Titulares';
	}
			if  ($tipo_cliente==1){
			$tipo_clientes="and procesos.id_beneficiario>0";
			$tipo='Beneficiarios';
	}
	if  ($tipo_cliente==2){
			$tipo_clientes="and procesos.id_beneficiario>=0";
			$tipo='Titulares + Beneficiarios';
	}
    /* **** creamos la tabla temporal para realizar la consulta**** */
 $q_tabla_tem = "create table 
											tabla_gastos_tmp_$codigo as 
									select 
											gastos_t_b.id_proceso,
											gastos_t_b.monto_aceptado,
											gastos_t_b.monto_reserva,
											gastos_t_b.id_servicio,
											estados_procesos.id_estado_proceso,
											estados_procesos.tipo_siniestro,
											entes.id_ente 
									from 
											procesos,
											gastos_t_b,
											entes,
											titulares,
											admin,
											estados_procesos,
											proveedores 
									where 
											procesos.id_proceso=gastos_t_b.id_proceso and 
											gastos_t_b.id_proveedor=proveedores.id_proveedor 
											$condiproveedor and 
											procesos.fecha_recibido>='$dateField1' and 
											procesos.fecha_recibido<='$dateField2' and 
											procesos.id_admin=admin.id_admin $sucursales and 
											procesos.id_titular=titulares.id_titular and 
											titulares.id_ente=entes.id_ente 	
											$entes 
											$tipo_entes  
											$servicios  and 
											procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
											estados_procesos.tipo_siniestro<=1 $tipo_clientes
									order by 
											procesos.id_estado_proceso";
$r_tabla_tem = ejecutar($q_tabla_tem);

/* **** Si registra el nombre de la tabla creada en la tabla tbl_eliminar_tabla por si el proceso no se termina de ejucutar desde el sistema en seguridad ejecutar auditar tablas temporada creadas y eliminar las que hayan quedado **** */

$r_tbl_tabla_eli="insert into 
									tbl_eliminar_tablas 
									(nombre_tbl_eli) 
									values 
									('tabla_gastos_tmp_$codigo');";
$f_tbl_tabla_eli=ejecutar($r_tbl_tabla_eli);
?>

 <?php 
$q_ente="select 
						entes.id_ente,
						entes.nombre 
				from 
						entes
				where 
						$entes1 
						$tipo_entes";
$r_ente = ejecutar($q_ente);

			$q_servicios = "select 
												* 
										from 
												servicios 
										where 
												$servicios1 and 
												servicios.id_servicio<>12  
										order by 
												servicios.servicio";
$r_servicios = ejecutar($q_servicios);
$num_s=num_filas($r_servicios);
     		
?>

<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>

  <tr>
  <td  colspan=<?php echo "$num_s +3"?> class="titulo_seccion"> Gastos de <?php echo  $tipo?> </td>
  </tr>
  <tr>
	<td class="tdcamposc">Ente</td>  
    
	<?php 
		while($f_servicios = asignar_a($r_servicios)){
	
	?>
	<td class="tdcamposc"><?php echo $f_servicios[codigo] ?></td>
	<?php
	}
	?>
	
	<td class="tdcamposc">TS</td>
    
	
	<td class="tdcamposc">Monto</td>	
    </tr>
<?php


		while($f_ente = asignar_a($r_ente)){
			$monto=0;
			$num_filas1=0;
			$q_servicios1 = "select 
													* 
											from 
													servicios 
											where 
													$servicios1 and 
													servicios.id_servicio<>12  
											order by 
													servicios.servicio";
$r_servicios1 = ejecutar($q_servicios1);
			?>
			<tr>
    <td class="tdcamposcc"><?php echo $f_ente[nombre];?></td>
	
			<?php
	
			while($f_servicios1 = asignar_a($r_servicios1)){
			
			$q_procesos = "select 
												tabla_gastos_tmp_$codigo.id_proceso,
												count(tabla_gastos_tmp_$codigo.id_proceso) 
										from 
												tabla_gastos_tmp_$codigo 
										where  
												tabla_gastos_tmp_$codigo.id_servicio=$f_servicios1[id_servicio] and 
												tabla_gastos_tmp_$codigo.id_ente=$f_ente[id_ente] 
										group by 
												tabla_gastos_tmp_$codigo.id_proceso";
$r_procesos = ejecutar($q_procesos);
$num_filas=num_filas($r_procesos);
$num_filas1=$num_filas1 + $num_filas;
$num_filas2=$num_filas2 + $num_filas;


$q_gastos = "select 
									tabla_gastos_tmp_$codigo.id_proceso,
									tabla_gastos_tmp_$codigo.monto_aceptado,
									tabla_gastos_tmp_$codigo.monto_reserva,
									tabla_gastos_tmp_$codigo.tipo_siniestro
							from 
									tabla_gastos_tmp_$codigo 
							where   
									tabla_gastos_tmp_$codigo.id_servicio=$f_servicios1[id_servicio] and 
									tabla_gastos_tmp_$codigo.id_ente=$f_ente[id_ente]";
					$r_gastos = ejecutar($q_gastos);
					
		while($f_gastos = asignar_a($r_gastos)){
		$monto= $monto + $f_gastos[monto_aceptado];
		$monto1= $monto1 + $f_gastos[monto_aceptado];
		}
		?>

	<td class="tdcamposcc"><?php echo $num_filas;?></td>
	<?php
	}
	?>
	
    <td class="tdcamposcc"><?php echo $num_filas1;?></td>	
    <td class="tdcamposcc"><?php echo montos_print($monto);?></td>

</tr>
	<?php
	}
    /* **** Eliminar tabla temporal**** */

  $e_tabla_tem = "drop table tabla_gastos_tmp_$codigo";
$re_tabla_tem = ejecutar($e_tabla_tem);
	?>
<tr>
	<td colspan=<?php echo "$num_s +3"?> class="tdcamposc"></td>  
    
	
	<td class="tdcamposc"></td>
	
	
	<td  class="tdcamposc"><?php echo $num_filas2;?></td>
    
	
	<td class="tdcamposc"><?php echo montos_print($monto1);?></td>	
    </tr>
</table>

