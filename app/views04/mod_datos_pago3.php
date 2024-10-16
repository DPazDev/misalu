<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$fecha_edi=date("Y-m-d");
$hora_edi=date("H:i:s");

 $monto_fac            = $_REQUEST['monto_fac'];


$id_factura           = $_REQUEST['id_factura'];              
$no_factura           = $_REQUEST['factura'];     
$dateField5           = $_REQUEST['dateField5']; 
$forma_pago           = $_REQUEST['forma_pago']; 
$tipo_moneda          = $_REQUEST['tipo_moneda'];
$nom_tarjeta          = $_REQUEST['nom_tarjeta'];
$banco                = $_REQUEST['banco'];      
$no_cheque            = $_REQUEST['no_cheque'];  
$nombre_p_pago        = $_REQUEST['nombre_p_pago']; 
$cedula_p_pago        = $_REQUEST['cedula_p_pago']; 
$telf_p_pago          = $_REQUEST['telf_p_pago']; 
$correo_p_pago        = $_REQUEST['correo_p_pago'];



/* *** variables operaciones multiples*** */
$forma_pago1    = $_REQUEST['forma_pago1'];
$nom_tarjeta1   = $_REQUEST['nom_tarjeta1'];
$banco1         = $_REQUEST['banco1'];
$no_cheque1     = $_REQUEST['no_cheque1'];
$tipo_moneda1   = $_REQUEST['tipo_moneda1'];
$monto1         = $_REQUEST['monto1'];
$nombre_p_pago1 = $_REQUEST['nombre_p_pago1'];
$cedula_p_pago1 = $_REQUEST['cedula_p_pago1'];
$telf_p_pago1   = $_REQUEST['telf_p_pago1'];
$correo_p_pago1 = $_REQUEST['correo_p_pago1'];

$forma_pago2   = $_REQUEST['forma_pago2'];
$nom_tarjeta2  = $_REQUEST['nom_tarjeta2'];
$banco2        = $_REQUEST['banco2'];
$no_cheque2    = $_REQUEST['no_cheque2'];
$tipo_moneda2  = $_REQUEST['tipo_moneda2'];
$monto2        = $_REQUEST['monto2'];
$nombre_p_pago2 = $_REQUEST['nombre_p_pago2'];
$cedula_p_pago2 = $_REQUEST['cedula_p_pago2'];
$telf_p_pago2   = $_REQUEST['telf_p_pago2'];
$correo_p_pago2 = $_REQUEST['correo_p_pago2'];

$forma_pago3   = $_REQUEST['forma_pago3'];
$nom_tarjeta3  = $_REQUEST['nom_tarjeta3'];
$banco3        = $_REQUEST['banco3'];
$no_cheque3    = $_REQUEST['no_cheque3'];
$tipo_moneda3  = $_REQUEST['tipo_moneda3'];
$monto3        = $_REQUEST['monto3'];
$nombre_p_pago3 = $_REQUEST['nombre_p_pago3'];
$cedula_p_pago3 = $_REQUEST['cedula_p_pago3'];
$telf_p_pago3   = $_REQUEST['telf_p_pago3'];
$correo_p_pago3 = $_REQUEST['correo_p_pago3'];

$forma_pago4   = $_REQUEST['forma_pago4'];
$nom_tarjeta4  = $_REQUEST['nom_tarjeta4'];
$banco4        = $_REQUEST['banco4'];
$no_cheque4    = $_REQUEST['no_cheque4'];
$tipo_moneda4  = $_REQUEST['tipo_moneda4'];
$monto4        = $_REQUEST['monto4'];
$nombre_p_pago4 = $_REQUEST['nombre_p_pago4'];
$cedula_p_pago4 = $_REQUEST['cedula_p_pago4'];
$telf_p_pago4   = $_REQUEST['telf_p_pago4'];
$correo_p_pago4 = $_REQUEST['correo_p_pago4'];
//*****

if ($forma_pago<>6) {


if(!empty($dateField5) && $forma_pago==2){
	$Cfecha_credito="fecha_credito='$dateField5',";
}

$act_datos_pago_factura=("update    tbl_facturas 
        							       set    id_banco='$banco',
					                          $Cfecha_credito
							                      condicion_pago='$forma_pago',
										                numero_cheque='$no_cheque',
										                id_nom_tarjeta='$nom_tarjeta'
                           where    numero_factura='$no_factura'");	
$act_dp_fac=ejecutar($act_datos_pago_factura);


$eliminar_datos_pago_oper_multi=("delete from tbl_oper_multi where id_factura='$id_factura'");
$elim_dp_op_multi=ejecutar($eliminar_datos_pago_oper_multi); 

    $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda' order by fecha_cambio desc limit 1");
	  $r_moneda_cambio=ejecutar($moneda_cambio);
	  $s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

$act_datos_pago_oper_multi=("insert into tbl_oper_multi (id_factura,
																					 				       id_banco,
																									       numero_cheque,
																									       id_nom_tarjeta,
																						 			    	 monto,
																												 condicion_pago,
																												 id_moneda,
																												 id_moneda_cambio,
																												 nombre_p_pago,
																												 cedula_p_pago,
																												 telf_p_pago,
																												 correo_p_pago) 
																												 values('$id_factura',
																												 '$banco',
																											   '$no_cheque',
																												 '$nom_tarjeta',
																											   '$monto_fac',
																												 '$forma_pago',
																												 '$tipo_moneda',
																				                 '$id_moneda_cambio',
																				                 '$nombre_p_pago',
																	                       '$cedula_p_pago',
											  			                           '$telf_p_pago',
																				                 '$correo_p_pago')  ");       
 $act_dp_oper_multi=ejecutar($act_datos_pago_oper_multi);


}
  else
{
      //************ UPDATE CUANDO ES OPERACIONES MULTIPLES

$act_datos_pago_factura=("update    tbl_facturas 
							      set    id_banco='$banco',
								         condicion_pago='$forma_pago',
										 numero_cheque='$no_cheque',
										 id_nom_tarjeta='$nom_tarjeta'
                                where    numero_factura='$no_factura'");	
$act_dp_fac=ejecutar($act_datos_pago_factura);   


$eliminar_datos_pago_oper_multi=("delete from tbl_oper_multi where id_factura='$id_factura'");
$elim_dp_op_multi=ejecutar($eliminar_datos_pago_oper_multi); 


	if ($monto1>0){
			if ($forma_pago1==1){
				$no_cheque1=0;
				$nom_tarjeta1=0;
				}
    $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda1' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

 	$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
							nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago) 
					values('$id_factura',
						    '$banco1',
					        '$no_cheque1',
						    '$nom_tarjeta1',
					        '$monto1',
							'$forma_pago1',
							'$tipo_moneda1',
                            '$id_moneda_cambio',
                            '$nombre_p_pago1',
                            '$cedula_p_pago1',
                            '$telf_p_pago1',
                            '$correo_p_pago1'   
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto2>0){
			if ($forma_pago2==1){
				$no_cheque2=0;
				$nom_tarjeta2=0;
				}
     $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda2' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

	$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
						    nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago) 
					values('$id_factura',
						    '$banco2',
					        '$no_cheque2',
						    '$nom_tarjeta2',
					        '$monto2',
							'$forma_pago2',
							'$tipo_moneda2',
							'$id_moneda_cambio',
							'$nombre_p_pago2',
							'$cedula_p_pago2',
							'$telf_p_pago2',
							'$correo_p_pago2'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto3>0){
			if ($forma_pago3==1){
				$no_cheque3=0;
				$nom_tarjeta3=0;
				}
	$moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda3' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];
		 $r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
		                    nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago) 
					values('$id_factura',
						    '$banco3',
					        '$no_cheque3',
						    '$nom_tarjeta3',
					        '$monto3',
							'$forma_pago3',
							'$tipo_moneda3',
							'$id_moneda_cambio',
					        '$nombre_p_pago3',
							'$cedula_p_pago3',
							'$telf_p_pago3',
							'$correo_p_pago3');";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto4>0){
			if ($forma_pago4==1){
				$no_cheque4=0;
				$nom_tarjeta4=0;
				}
    $moneda_cambio= ("select * from tbl_monedas_cambios where tbl_monedas_cambios.id_moneda='$tipo_moneda4' order by fecha_cambio desc limit 1");
	$r_moneda_cambio=ejecutar($moneda_cambio);
	$s_moneda_cambio=asignar_a($r_moneda_cambio);
    $id_moneda_cambio=$s_moneda_cambio['id_moneda_cambio'];

		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago,
							id_moneda,
							id_moneda_cambio,
							nombre_p_pago,
							cedula_p_pago,
							telf_p_pago,
							correo_p_pago) 
					values('$id_factura',
						    '$banco4',
					        '$no_cheque4',
						    '$nom_tarjeta4',
					        '$monto4',
							'$forma_pago4',
							'$tipo_moneda4',
							'$id_moneda_cambio',
						    '$nombre_p_pago4',
							'$cedula_p_pago4',
							'$telf_p_pago4',
							'$correo_p_pago4'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}

}

?>
<h1>Los datos del pago se han modificado exitosamente</h1>
