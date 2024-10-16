<?php
include ("../../lib/jfunciones.php");
sesion();
//Reporte para dar la opcion de buscar las ordenes usadas en cualquiera de los servicios prestados a prima a riesgo y lo muestra de acuerdo al estado en que se encuentre el servicio Formato de Impresion
//$servicio=$_REQUEST[servicio];
$admin= $_SESSION['id_usuario_'.empresa];
$dateField1=$_REQUEST[dateField1];
$dateField2=$_REQUEST[dateField2];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicio];
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
$tipo_cliente=$_REQUEST[tipo_cliente];
$tipo_proveedor=$_REQUEST[tipo_proveedor];
$codigot=time();
$codigo=$admin . $codigot;

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select 
							admin.*,
							sucursales.*
					from 
							admin
					where
							admin.id_admin='$id_admin' and
							admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




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
	$tiposentes="Todos los Tipos";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
		
		if  ($tipo_ente==1){
		$tiposentes="Natural";
	}
		if  ($tipo_ente==2){
		$tiposentes="Juridica";
	}
		if  ($tipo_ente==3){
		$tiposentes="Gubernamental";
	}
		if  ($tipo_ente==4){
		$tiposentes="Privados";
	}
	if  ($tipo_ente==5){
		$tiposentes="Sindicatos";
	}
		
		
	}
	
		if  ($ente==0){
	$entes="and entes.id_ente>0";
	$todosentes="Todos los Entes";
	$entes1="entes.id_ente>0";
	}
	else
	{
		$entes="and entes.id_ente=$ente";
		$entes1="entes.id_ente=$ente";
		$r_ente1=pg_query("select entes.id_ente,entes.nombre from entes where entes.id_ente=$ente");
	($f_ente1=pg_fetch_array($r_ente1, NULL, PGSQL_ASSOC));
	$todosentes=$f_ente1[nombre];
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
										select gastos_t_b.id_proceso,
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
										procesos.id_admin=admin.id_admin 
										$sucursales and 
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
	
$q_ente="select entes.id_ente,entes.nombre from entes where $entes1 $tipo_entes";
$r_ente = ejecutar($q_ente);

			$q_servicios = "select * from servicios where $servicios1 and servicios.id_servicio<>12  order by servicios.servicio";
$r_servicios = ejecutar($q_servicios);
$num_s=num_filas($r_servicios);

$q_servicios3 = "select * from servicios where $servicios1 and servicios.id_servicio<>12  order by servicios.servicio";
$r_servicios3 = ejecutar($q_servicios3);

?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>


<tr>
<td colspan=1 class="datos_cliente">
Ente(s):
</td>
<td colspan=2 class="datos_cliente">
<?php echo "$nom_ente";?>
</td>
<td colspan=1 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente">
Tipo(s) de Ente:
</td>
<td colspan=2 class="datos_cliente">
<?php echo "$nom_tipo_ente";?>
</td>
<td colspan=1 class="datos_cliente">
</td>
</tr>
<tr>
<td>
<br>
</br>
</td>
</tr>
 <tr>

  <td  class="titulo3"> Gastos de <?php echo  $tipo?> </td>
  </tr>

<tr>
<td>
<br>
</br>
</td>
</tr>

</table>
<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>


  <tr>
	<td class="titulo3">Ente</td>  
    
	<?php 
		while($f_servicios = asignar_a($r_servicios)){
	
	?>
	<td class="titulo3"><?php echo $f_servicios[codigo] ?></td>
	<?php
	}
	?>
	
	<td class="titulo3">TS</td>
    <td class="titulo3">MSPen</td>
    <td class="titulo3">MSPag</td>
    <td class="titulo3">Monto (MSPen+MSPag)</td>	
    </tr>
<?php


		while($f_ente = asignar_a($r_ente)){
			$monto=0;
            $siniestro_pen=0;
            $siniestro_pag=0;
			$num_filas1=0;
			$q_servicios1 = "select * from servicios where $servicios1 and servicios.id_servicio<>12  order by servicios.servicio";
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
        
            if ($f_gastos[tipo_siniestro]==0)
                    {
                        $siniestro_pen= $siniestro_pen + $f_gastos[monto_aceptado];
                        $siniestro_pent= $siniestro_pent + $f_gastos[monto_aceptado];
                    }
                        else
                    {
                        $siniestro_pag=$siniestro_pag + $f_gastos[monto_aceptado];
                        $siniestro_pagt=$siniestro_pagt + $f_gastos[monto_aceptado];
                    }
		
		
		}
		?>

	<td class="titulo3"><?php echo $num_filas;?></td>
	<?php
	}
	?>
	
    <td class="titulo3"><?php echo $num_filas1;?></td>	
    <td class="titulo3"><?php echo montos_print($siniestro_pen);?></td>	
    <td class="titulo3"><?php echo montos_print($siniestro_pag);?></td>	
    <td class="titulo3"><?php echo montos_print($monto);?></td>

</tr>
	<?php
	}
        /* **** Eliminar tabla temporal**** */

  $e_tabla_tem = "drop table tabla_gastos_tmp_$codigo";
$re_tabla_tem = ejecutar($e_tabla_tem);
	?>
<tr>
	<td colspan=<?php echo "$num_s +3"?> class="tdcamposc"></td>  
    
	
	<td class="titulo3"></td>
	
	
	<td  class="titulo3"><?php echo $num_filas2;?></td>
    
		<td class="titulo3"><?php echo montos_print($siniestro_pent);?></td>
        <td class="titulo3"><?php echo montos_print($siniestro_pagt);?></td>
	<td class="titulo3"><?php echo montos_print($monto1);?></td>	
    </tr>

</table>
<br>
</br>
<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>


  <tr>
        <td class="titulo3">Leyenda</td>
</tr>
        <?php
                while($f_servicios3 = asignar_a($r_servicios3)){

        ?>
<tr>
        <td class="tdcamposc"><?php echo $f_servicios3[servicio] ?></td>
        <td class="titulo3"><?php echo $f_servicios3[codigo] ?></td>
</tr>    
    <?php
        }
        ?>
        <tr>
        <td class="tdcamposc">TOTALES SERVICIOS </td>
        <td class="titulo3">TS </td>
        </tr>
        <tr>
        <td class="tdcamposc">MONTO SINIESTROS PENDIENTES </td>
        <td class="titulo3">MSPen</td>
        </tr>
        <tr>
        <td class="tdcamposc">MONTO SINIESTROS PAGADOS </td>
        <td class="titulo3">MSPag</td>
    </tr>
</table>
