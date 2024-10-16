<?php
include ("../../lib/jfunciones.php");
sesion();
//Reporte para buscar las ordenes usadas en cualquiera de los servicios prestados a prima a riesgo y lo muestra de acuerdo al estado en que se encuentre el servicio en Formato de Impresion
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

if ($tipo_proveedor==0){
    $condiproveedor="and proveedores.tipo_proveedor>=0";
    }
if ($tipo_proveedor==1){
    $condiproveedor="and proveedores.tipo_proveedor=1";
    }
    if ($tipo_proveedor==2){
    $condiproveedor="and proveedores.tipo_proveedor=0";
    }
    
    if  ($sucursal==0){
	$sucursales="and admin.id_sucursal>0";
	}
	else
	{
		$sucursales="and admin.id_sucursal=$sucursal";
		}

if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
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
	}
	else
	{
		$entes="and entes.id_ente=$ente";
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
	
    
    
  /*  echo $condiproveedor;
    echo "******";
     echo $sucursales;
    echo "******";
     echo $entes;
    echo "******";
     echo $tipo_entes;
    echo "******";
      echo $servicios;
    echo "******";
      echo $tipo_clientes;
    echo "******";
    */
/* **** creamos la tabla temporal para realizar la consulta**** */
 $q_tabla_tem = "create table 
										tabla_gastos_tmp_$codigo as 
										select gastos_t_b.id_proceso,
										gastos_t_b.monto_aceptado,
										gastos_t_b.monto_reserva,
										gastos_t_b.id_servicio,
										estados_procesos.id_estado_proceso,
										estados_procesos.tipo_siniestro 
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
										estados_procesos.tipo_siniestro<=1 
										$tipo_clientes
								order by 
										procesos.id_estado_proceso";
$r_tabla_tem = ejecutar($q_tabla_tem);

	$r_ente=pg_query("select entes.id_ente,entes.nombre from entes where entes.id_ente=$ente");
	($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC));
	
	/* **** Si registra el nombre de la tabla creada en la tabla tbl_eliminar_tabla por si el proceso no se termina de ejucutar desde el sistema en seguridad ejecutar auditar tablas temporada creadas y eliminar las que hayan quedado **** */

$r_tbl_tabla_eli="insert into 
									tbl_eliminar_tablas 
									(nombre_tbl_eli) 
									values 
									('tabla_gastos_tmp_$codigo');";
$f_tbl_tabla_eli=ejecutar($r_tbl_tabla_eli);
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
<td colspan=4>
<br>
</br>
</td>
</tr>
 <?php 
 $q_edopro = "select 
									* 
							from 
									estados_procesos 
							where 
									estados_procesos.id_estado_proceso<>'1' and 
									estados_procesos.id_estado_proceso<>'6' and 
									estados_procesos.id_estado_proceso<>'13' and 
									estados_procesos.id_estado_proceso<>'14'
							order by 
									estados_procesos.id_estado_proceso";
$r_edopro = ejecutar($q_edopro);
while($f_edopro  = asignar_a($r_edopro )){

$q_servicios = "select 
									* 
							from 
									servicios
							where 
									$servicios1 and 
									servicios.id_servicio<>'12'  
							order by
									servicios.servicio";
$r_servicios = ejecutar($q_servicios);


?>




   <tr>
    <td colspan="6" class="titulo3"> Gastos de <?php echo  "$tipo  estado $f_edopro[estado_proceso]";?> </td>
  </tr>
  <tr>
	<td class="datos_cliente2">Servicios</td>  
    <td class="datos_cliente2">Cantidad</td>
    <td class="datos_cliente2">Monto Reserva</td>	
    <td class="datos_cliente2">Monto Aceptado</td>
    <td class="datos_cliente2">% Frecuencia Sini.</td>
    <td class="datos_cliente2"></td>
  </tr>
  <?php
$num_filas=0;
if ($servicio==0)  {

$q_tprocesos = "select 
									tabla_gastos_tmp_$codigo.id_proceso,
									count(tabla_gastos_tmp_$codigo.id_proceso) 
							from 
									tabla_gastos_tmp_$codigo 
							where 
									tabla_gastos_tmp_$codigo.id_estado_proceso=$f_edopro[id_estado_proceso] 
							group by 
									tabla_gastos_tmp_$codigo.id_proceso";
$r_tprocesos = ejecutar($q_tprocesos);
$num_filas=num_filas($r_tprocesos);
$num_filas2=$num_filas2 +$num_filas;
}
$num_filas1=0;
$monto1=0;
$montor1=0;
		while($f_servicios = asignar_a($r_servicios)){
          
			$q_procesos = "select 
												tabla_gastos_tmp_$codigo.id_proceso,
												count(tabla_gastos_tmp_$codigo.id_proceso) 
										from 
												tabla_gastos_tmp_$codigo 
										where 
												tabla_gastos_tmp_$codigo.id_estado_proceso=$f_edopro[id_estado_proceso] and 
												tabla_gastos_tmp_$codigo.id_servicio=$f_servicios[id_servicio]
										group by 
												tabla_gastos_tmp_$codigo.id_proceso";
$r_procesos = ejecutar($q_procesos);
$num_filas1=num_filas($r_procesos);

$monto=0;
$montor=0;

$q_gastos = "select 
								tabla_gastos_tmp_$codigo.id_proceso,
								tabla_gastos_tmp_$codigo.monto_aceptado,
								tabla_gastos_tmp_$codigo.monto_reserva,
								tabla_gastos_tmp_$codigo.tipo_siniestro 
						from 
								tabla_gastos_tmp_$codigo 
						where  
								tabla_gastos_tmp_$codigo.id_estado_proceso=$f_edopro[id_estado_proceso] and 
								tabla_gastos_tmp_$codigo.id_servicio=$f_servicios[id_servicio]
 ";
					$r_gastos = ejecutar($q_gastos);
		
		while($f_gastos = asignar_a($r_gastos)){
		
		$monto= $monto + $f_gastos[monto_aceptado];
		$monto1= $monto1 + $f_gastos[monto_aceptado];
        $monto2= $monto2 + $f_gastos[monto_aceptado];
        $montor= $montor + $f_gastos[monto_reserva];
		$montor1= $montor1 + $f_gastos[monto_reserva];
        $montor2= $montor2 + $f_gastos[monto_reserva];
		  if ($f_gastos[tipo_siniestro]==0){
            $siniestro_pen= $siniestro_pen + $f_gastos[monto_aceptado];
            }
            else
            {
                $siniestro_pag=$siniestro_pag + $f_gastos[monto_aceptado];
                }
		
		}
		
		



		?>
	
	<tr>
    <td class="datos_cliente3"><?php echo $f_servicios[servicio];?></td>
	<td class="datos_cliente3"><?php echo $num_filas1;?></td>
    <td class="datos_cliente3"><?php echo montos_print($montor);?></td>	
        <td class="datos_cliente3"><?php echo montos_print($monto);?></td>	
    <td class="datos_cliente3"><?php echo montos_print(($num_filas1 / $num_filas) * 100 );?></td>
	<td class="datos_cliente3"></td>
    </tr>
		
		
		
		<?php
		}
        
		?>
<tr>
    <td class="datos_cliente2">Total</td>
	<td class="datos_cliente3"><?php echo $num_filas;?></td>
    <td class="datos_cliente3"><?php echo montos_print($montor1);?></td>	
    <td class="datos_cliente3"><?php echo montos_print($monto1);?></td>	
    <td class="datos_cliente3"><?php echo montos_print (($num_filas / $num_filas) * 100 );?></td>
	<td class="datos_cliente3"></td>
 </tr>
 <?php
 }
 /* **** Eliminar tabla temporal**** */

  $e_tabla_tem = "drop table tabla_gastos_tmp_$codigo";
$re_tabla_tem = ejecutar($e_tabla_tem);
 ?>
 
 <tr>
    <td colspan="6" class="titulo_seccion"> <hr></hr></td>
</tr>
 
 <tr>
    <td class="datos_cliente2">Total Final</td>
	<td class="datos_cliente2"><?php echo $num_filas2;?></td>
    <td class="datos_cliente2"><?php echo montos_print($montor2);?></td>	
    <td class="datos_cliente2"><?php echo montos_print($monto2);?></td>	
    <td class="datos_cliente2"><?php echo montos_print (($num_filas / $num_filas) * 100 );?></td>
	<td class="datos_cliente2"></td>
 </tr>
<tr>
    <td colspan="6" class="titulo_seccion"> </td>
</tr>
 <tr>
    <td colspan="6" class="titulo3"> Formula </td>
  </tr>
  <tr>
    <td colspan="6" class="titulo3"><?php echo " Siniestros Pendientes ($siniestro_pen) + Siniestros Pagados ( $siniestro_pag)=$monto2 ";?></td>
	
   
 </tr>

</table>

