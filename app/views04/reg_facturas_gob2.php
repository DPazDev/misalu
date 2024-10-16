<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');

?>
<script language="JavaScript">

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);

	}
</script>
<?php
$registrar= $_REQUEST['registrar'];


list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
list($entes,$nom_ente)=explode("@",$_REQUEST['entes']);
$dateField1 = $_REQUEST['dateField1'];
$dateField2 = $_REQUEST['dateField2'];
$dateField3 = $_REQUEST['dateField3'];
$dateField4 = $_REQUEST['dateField4'];
$dateField5 = $_REQUEST['dateField5'];
$forma_pago = $_REQUEST['forma_pago'];
$nom_tarjeta = $_REQUEST['nom_tarjeta'];
$banco = $_REQUEST['banco'];
$no_cheque = $_REQUEST['no_cheque'];
$concepto = utf8_decode(strtoupper($_REQUEST['concepto']));
$no_factura = $_REQUEST['factura'];
$serie = $_REQUEST['serie'];
$controlfactura = $_REQUEST['controlfactura'];
$monto = $_REQUEST['monto'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$sucursal = $_REQUEST['sucursal'];
$servicios = $_REQUEST['servicios'];

/* *** variables operaciones multiples*** */
$forma_pago1 = $_REQUEST['forma_pago1'];
$nom_tarjeta1 = $_REQUEST['nom_tarjeta1'];
$banco1 = $_REQUEST['banco1'];
$no_cheque1 = $_REQUEST['no_cheque1'];
$monto1 = $_REQUEST['monto1'];
$forma_pago2 = $_REQUEST['forma_pago2'];
$nom_tarjeta2 = $_REQUEST['nom_tarjeta2'];
$banco2 = $_REQUEST['banco2'];
$no_cheque2 = $_REQUEST['no_cheque2'];
$monto2 = $_REQUEST['monto2'];
$forma_pago3 = $_REQUEST['forma_pago3'];
$nom_tarjeta3 = $_REQUEST['nom_tarjeta3'];
$banco3 = $_REQUEST['banco3'];
$no_cheque3= $_REQUEST['no_cheque3'];
$monto3 = $_REQUEST['monto3'];
$forma_pago4 = $_REQUEST['forma_pago4'];
$nom_tarjeta4 = $_REQUEST['nom_tarjeta4'];
$banco4 = $_REQUEST['banco4'];
$no_cheque4 = $_REQUEST['no_cheque4'];
$monto4 = $_REQUEST['monto4'];
/* *** fin de variables operaciones multiples*** */
if ($sucursal=='*'){
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal>0";
}
else
{
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal=$sucursal";

}

if ($servicios=='*'){
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio>0";
}
else
{
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio=$servicios";

}

if ($forma_pago<>6){

if(!empty($dateField5) && $forma_pago==2){
	$Cfecha_credito="fecha_credito,";
	$Vfecha_credito="'$dateField5',";
	$Cestado_factura="id_estado_factura,";
	$Vestado_factura="'2', ";
	
}


if(!empty($banco) && !empty($no_cheque) && ($forma_pago==3 || $forma_pago==5 || $forma_pago==4 || $forma_pago==4)){
	$Cbanco="id_banco,";
	$Vbanco="'$banco', ";
	
	$Ccheque="numero_cheque,";
	$Vcheque="'$no_cheque',";

}

}

$codigo=time();

//busco si ya existe esa factura en la bd.
$q="select * from tbl_facturas where numero_factura='$no_factura' and id_serie=$serie;";
$r=ejecutar($q) or die($q);
$q_factura="";
if(num_filas($r)==0){

	
	//no existe, hay que insertarla.
	$fecha_emision=$dateField3;
    
    
if ($forma_pago<>6){
	$r_factura="insert into tbl_facturas (id_serie,
	 				       numero_factura,
					       condicion_pago,
					       fecha_emision,
					       $Cfecha_credito
					      $Cestado_factura
					       id_admin,
							codigo,
							concepto,
							con_ente,
							id_nom_tarjeta,
							numcontrol) 
					values('$serie',
					       '$no_factura',
					       '$forma_pago',
					       '$fecha_emision',
					       $Vfecha_credito
					       $Vestado_factura
					       '$id_admin',
					       '$codigo',
							'$concepto',
							'$entes',
						'$nom_tarjeta',
						'$controlfactura');";
	$f_factura=ejecutar($r_factura);
	$q_factura="(select id_factura from tbl_facturas where codigo='$codigo' and id_admin='$id_admin')";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];



$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       $Cbanco
					       $Ccheque
					        id_nom_tarjeta,
						    monto,
							condicion_pago) 
					values('$id_factura',
						    $Vbanco
					        $Vcheque
						    '$nom_tarjeta',
					        '$monto',
							'$forma_pago'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	else
	{
		
			
		$r_factura="insert into tbl_facturas (id_serie,
	 				       numero_factura,
					       condicion_pago,
					       fecha_emision,
					       id_admin,
							codigo,
							concepto,
							con_ente,
							numcontrol) 
					values('$serie',
					       '$no_factura',
					       '$forma_pago',
					       '$fecha_emision',
					       '$id_admin',
					       '$codigo',
						   '$concepto',
						   '$entes',
						   '$controlfactura');";
	$f_factura=ejecutar($r_factura);
	$q_factura="(select id_factura from tbl_facturas where codigo='$codigo' and id_admin='$id_admin')";
	$r_factura=ejecutar($q_factura);
	$s_factura=asignar_a($r_factura);
	$id_factura=$s_factura['id_factura'];
	
		if ($monto1>0){
			if ($forma_pago1==1){
				$no_cheque1=0;
				$nom_tarjeta1=0;
				}
		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago) 
					values('$id_factura',
						    '$banco1',
					        '$no_cheque1',
						    '$nom_tarjeta1',
					        '$monto1',
							'$forma_pago1'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto2>0){
			if ($forma_pago2==1){
				$no_cheque2=0;
				$nom_tarjeta2=0;
				}
		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago) 
					values('$id_factura',
						    '$banco2',
					        '$no_cheque2',
						    '$nom_tarjeta2',
					        '$monto2',
							'$forma_pago2'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto3>0){
			if ($forma_pago3==1){
				$no_cheque3=0;
				$nom_tarjeta3=0;
				}
		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago) 
					values('$id_factura',
						    '$banco3',
					        '$no_cheque3',
						    '$nom_tarjeta3',
					        '$monto3',
							'$forma_pago3'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
	if ($monto4>0){
			if ($forma_pago4==1){
				$no_cheque4=0;
				$nom_tarjeta4=0;
				}
		$r_facturaom="insert into tbl_oper_multi (id_factura,
	 				       id_banco,
					       numero_cheque,
					        id_nom_tarjeta,
						    monto,
							condicion_pago) 
					values('$id_factura',
						    '$banco4',
					        '$no_cheque4',
						    '$nom_tarjeta4',
					        '$monto4',
							'$forma_pago4'
					      	);";
	$r_facturaom=ejecutar($r_facturaom);
	}
		}
}else{

	$f_factura=asignar_a($r);
	$id_factura=$f_factura['id_factura'];
}

/* comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/

/* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==4) {
$q= "select  
                                      procesos.id_proceso,
                                    count(gastos_t_b.id_proceso) 
                            from 
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin 
                            where 
                                    procesos.id_proceso=gastos_t_b.id_proceso and 
                                    gastos_t_b.id_servicio=$servicios and
                                    gastos_t_b.fecha_cita>='$dateField1 ' and 
                                    gastos_t_b.fecha_cita<='$dateField2' and 
                                    procesos.id_titular=titulares.id_titular and 
                                    titulares.id_ente=entes.id_ente and 
                                    entes.id_tipo_ente=$tipo_ente and 
                                    procesos.id_admin=admin.id_admin and 
                                    admin.id_sucursal=$sucursal and 
                                    (procesos.id_estado_proceso=7 or 
                                    procesos.id_estado_proceso=2 or 
                                    procesos.id_estado_proceso=10 or 
                                    procesos.id_estado_proceso=11 or 
                                    procesos.id_estado_proceso=15 or 
                                    procesos.id_estado_proceso=16 ) 
                            group by 
                                    procesos.id_proceso";
}
/*fin de  comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/
/* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==6 || $servicios==9) {
    
$q= "select  
                                    procesos.nu_planilla,
                                    count(procesos.nu_planilla) 
                            from 
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin 
                            where 
                                    procesos.id_proceso=gastos_t_b.id_proceso and 
                                    gastos_t_b.id_servicio=$servicios and
                                    procesos.fecha_recibido>='$dateField1 ' and 
                                    procesos.fecha_recibido<='$dateField2' and 
                                    procesos.id_titular=titulares.id_titular and 
                                    titulares.id_ente=entes.id_ente and 
                                    entes.id_tipo_ente=$tipo_ente and 
                                    procesos.id_admin=admin.id_admin and 
                                    admin.id_sucursal=$sucursal and 
                                    (procesos.id_estado_proceso=7 or 
                                    procesos.id_estado_proceso=2 or 
                                    procesos.id_estado_proceso=10 or 
                                    procesos.id_estado_proceso=11 or 
                                    procesos.id_estado_proceso=15 or 
                                    procesos.id_estado_proceso=16 ) 
                            group by 
                                    procesos.nu_planilla";
}
/*fin de  comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/
	$r_procesos = ejecutar($q);
//Busco los procesos que estan afiliados a la clave.
	pg_result_seek($r_procesos,0);
    if ($servicios==4) {
	while($f_proceso = asignar_a($r_procesos)){
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_proceso[id_proceso]");
			
	$r_gastos=ejecutar($q_gastos);
			$monto=0;
			while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}
	
		$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto) 
					values('$f_proceso[id_proceso]',
					       '$f_proceso[no_clave]',
					       '$id_factura',
							'$monto');";
		
		$f_procesos_claves=ejecutar($r_procesos_claves);
		}
}

 if ($servicios==6 || $servicios==9) {
     
     
	while($f_proceso = asignar_a($r_procesos)){
        
        
        $q_pro_num= "select  
                                    procesos.id_proceso,
                                    procesos.no_clave
                            from 
                                    
                                    procesos
                                  
                            where 
                                   procesos.nu_planilla='$f_proceso[nu_planilla]' and 
                                    (procesos.id_estado_proceso=7 or 
                                    procesos.id_estado_proceso=2 or 
                                    procesos.id_estado_proceso=10 or 
                                    procesos.id_estado_proceso=11 or 
                                    procesos.id_estado_proceso=15 or 
                                    procesos.id_estado_proceso=16 ) 
                            ";
        
        $r_pro_num = ejecutar($q_pro_num);
        
        	while($f_pro_num = asignar_a($r_pro_num)){
	$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$f_pro_num[id_proceso]");
			
	$r_gastos=ejecutar($q_gastos);
			$monto=0;
			while($f_gastos = asignar_a($r_gastos)){
				$monto=$monto + $f_gastos[monto_aceptado];
				}
	
		$r_procesos_claves="insert into tbl_procesos_claves (id_proceso,
	 				       no_clave,
					       id_factura,
							monto) 
					values('$f_pro_num[id_proceso]',
					       '$f_pro_num[no_clave]',
					       '$id_factura',
							'$monto');";
		
		$f_procesos_claves=ejecutar($r_procesos_claves);
		}
        }
}


		/* **** Se registra lo que hizo el usuario**** */

$log="REGISTRO la Factura numero $no_factura";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
		
?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
	<tr>
		<td align="right" colspan=4  class="titulo_seccion">Factura Generada 
<?php $url="'views04/ifacturagob.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura"> Imprimir Formato 1  Factura   <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/irelafacturagob.php?factura=$no_factura&serie=$serie&servicios=$servicios','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Relacion de Factura"> Imprimir Relacion  <?php echo "$no_factura Serie $serie" ?></a>

</td> 
	</tr>
	
	</table>
