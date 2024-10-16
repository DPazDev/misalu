<?php
include ("../../lib/jfunciones.php");
sesion();
$j=1;
/* propiedades para armar el reporte de excel */
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
/** Include PHPExcel */
require_once '../../lib/phpexcel/Classes/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$sheet = $objPHPExcel->getActiveSheet();

   
/* fin propiedades para armar el reporte de excel */
// recepcion de datos enviados

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
$nomcuenta="";
if ($num_cheque>0){
$andnumcheque="and tbl_facturas.numero_cheque='$num_cheque' ";
}
else
{
		$andnumcheque="";
}
list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
    $serviciosp="and g.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
}
else
{
	$servicios="and gastos_t_b.id_servicio=$servicio";
	$serviciosp="and g.id_servicio=$servicio";
	$servicios1="servicios.id_servicio=$servicio";
}

if ($forma_pago==0){
	$tipo_pago="and tbl_facturas.id_estado_factura>=0";
	}
if ($forma_pago=='*'){
	$tipo_pago="and tbl_facturas.id_estado_factura>=1 and tbl_facturas.id_estado_factura<=2";

}
if ($forma_pago>0){
	$tipo_pago="and tbl_facturas.id_estado_factura=$forma_pago";
}
	
	
	
if ($ente==0)
{
	$elente="and entes.id_ente>=$ente"; 
    $elentep="e.id_ente>=$ente and";

} else {
	$elente="and entes.id_ente=$ente";
	$elentep="e.id_ente=$ente and";
}
        
if ($ente=='*')
{
    $elente="and entes.id_ente!=53";
}
	
if ($tipo_ente==0)
{
	$eltipo_ente="tbl_tipos_entes.id_tipo_ente>=$tipo_ente"; 
} else {

	$eltipo_ente="tbl_tipos_entes.id_tipo_ente=$tipo_ente";
}
        
        
if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
	$serie="Todas Las Series";
} else {
	$id_serie="and tbl_series.id_serie=$sucursal";
   
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	$serie=$f_serie[nomenclatura];
}


if ($forma_pago==1)
{
	$descripcion="Pagadas";
}
	
if ($forma_pago==2)
{
	$descripcion="Por Cobrar";
}
	
	
if ($forma_pago==3)
{
	$descripcion="Anuladas";
}
if ($forma_pago=='*')
{
	$descripcion="Por Cobrar y Pagadas";
}

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

if($sucursal==4){
	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//
 $totalq = ("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	$anu=0;	 
	$peof++;
	$estafactura = $f_factura[id_estado_factura];
	$deducible   = $f_factura[sum_deducible];
	$montodfactu = $f_factura[sum];
	$nomdelente  = $f_factura[nombre];
	$numefactu   = $f_factura[numero_factura];
	$nomrifente  = str_replace("-", "",$f_factura['rif']);
	$lafechfact  = $f_factura[fecha_emision];
	list($elano,$elmes,$eldia)=explode("-",$lafechfact);
	$lafechfact ="$eldia/$elmes/$elano";
	$formfecha   =  "$lafechfact 12:00:00 a.m.";
	$j++;
	if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$factprincipal = $numefactu;	      
	}else {
		if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			$cuentadescrip='Cuentas por Cobrar Clientes';
			$cuentacontable="1.01.03.01.001";
			$sumafactura=$sumafactura+$montodfactu; 
			$enelhaber="0,000"; 
			$lalinea++;
			$alinea      = "$lalinea,000";
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$soniguales++;
			if(($pasfactu == "Anulada") and ($soniguales<=1)){
				$factprincipal = $numefactu;
			}
			$pasoente = $nomdelente;
		} else {//comienzo1
				//los entes son distintos
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')) {
				$soniguales=2;
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);
					if(($pasfactu == "Anulada") and ($sipaso == "anu")){
						$factprincipal = $factprincipal-1;
						$sipaso="";
					}
					if($laulfacqpaso <> $factprincipal){
						$numfafinal = "$factprincipal-$cortfact";
					}else {
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$factprincipal = $numefactu;	      
						
				}
					  
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=$sumafactura+$montodfactu; 
				$enelhaber="0,000"; 
				$lalinea++;
				$alinea      = "$lalinea,000";
                      
                      
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				
			}//fin de los entes son distintos
				   
			//los entes son iguales pero la factura es anulada
			if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					
					$cortfact = substr($laulfacqpaso,-2);
					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else{
						$numfafinal = $factprincipal;
					}
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$montodfactu=$montodfactu;
						$sumafactura=0;
						$j++;
						$soniguales=0;
						
				}
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";
							  
							  
			}//fin de los entes son iguales pero la factura es anulada


					      //los entes son distintos pero la factura es anulada
			if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)) {
				if($soniguales>=1){
								  
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else {
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
					$factprincipal = $f_factura[numero_factura]-1;	  
					$anu=1;    
				} 
							  
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$sipaso="anu";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";

			} //fin de los entes son distintos pero la factura es anulada


			//los entes son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);
					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else {
							$numfafinal = $factprincipal;
					}
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$montodfactu=$montodfactu;
						$sumafactura=0;
						$j++;
						$soniguales=0;
						
						
					}

						$cuentadescrip='Cuentas por Cobrar Clientes';
						$cuentacontable="1.01.03.01.001";
						$sumafactura=0; 
						$montodfactu = $montodfactu - $deducible;
						$enelhaber="0,000"; 
						$pasopanu=1;
						$lalinea++;
						$alinea      = "$lalinea,000";
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$j++;
						$entetompral = "$nomdelente (DEDUCIBLE)";
						$lalinea++;
						$alinea      = "$lalinea,000";
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$j++;//+
						$cuentadescrip='Ingresos por Servicios a Particulares';
						$cuentacontable="4.01.01.02.001";
						$sumafactura=0; 
						$enelhaber="0,000"; 
						$pasopanu=1;
						$lalinea++;
						$alinea      = "$lalinea,000";
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$j++;
						$entetompral = "$nomdelente (DEDUCIBLE)";
						$lalinea++;
						$alinea      = "$lalinea,000";
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						
			}//fin de los entes son iguales la factura no esta anulada pero tiene deducible


					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else{
						$numfafinal = $factprincipal;
					}

					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
								 
				}
				
				$factprincipal = $numefactu;
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactu = $montodfactu - $deducible;
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$cuentadescrip='Ingresos por Servicios a Particulares';
				$cuentacontable="4.01.01.02.001";
				$sumafactura=0; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);

			} //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible

		}//fin de comienzo1
		  
	}
   
   
   ////
                 
	if($peof==$cuantoquery){
		$j=$j+1;
      	if(($estafactura <> 3) and ($deducible <= '0')){
			$cuentadescrip='Ingresos por Servicios a Particulares';
			$cuentacontable="4.01.01.02.001";
			$sumafactura=($sumafactura+$montodfactu-$montodfactu); 
			$montodfactut=0;
			$enelhaber=$sumafactura;  
			$lalinea++;
			$alinea      = "$lalinea,000";
			$cortfact = substr($laulfacqpaso,-2);

			if($laulfacqpaso <> $factprincipal){
				$numfafinal = "$factprincipal-$cortfact";
			} else {
				$numfafinal = $factprincipal;
			}
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$montodfactu=$montodfactu;
			$sumafactura=0;
			$j++;
			$factprincipal = $numefactu;	      
						  
		}
	}
   

	$elrifqpaso = $nomrifente;
	$pasoente = $nomdelente;
	$laulfacqpaso = $f_factura[numero_factura];
	$fechqpaso = $formfecha;
}
      

$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);

$sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}
if($sucursal==5){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//
 $totalq = ("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";



////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////




while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	$anu=0;	 
	$peof++;
	$estafactura = $f_factura[id_estado_factura];
	$deducible   = $f_factura[sum_deducible];
	$montodfactu = $f_factura[sum];
	$nomdelente  = $f_factura[nombre];
	$numefactu   = $f_factura[numero_factura];

	if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   	}else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	}
   
   
	$lafechfact  = $f_factura[fecha_emision];
	list($elano,$elmes,$eldia)=explode("-",$lafechfact);
	$lafechfact ="$eldia/$elmes/$elano";
	$formfecha   =  "$lafechfact 12:00:00 a.m.";
	$j++;
	if($j <= 2){
		$cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
		$sumafactura=$sumafactura+$montodfactu; 
		$enelhaber="0,000";       
		$lalinea++;
		$alinea      = "$lalinea,000";
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$factprincipal = $numefactu;	      
	}else{
		if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			$cuentadescrip='Cuentas por Cobrar Clientes';
			$cuentacontable="1.01.03.01.001";
			$sumafactura=$sumafactura+$montodfactu; 
			$enelhaber="0,000"; 
			$lalinea++;
			$alinea      = "$lalinea,000";
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$soniguales++;

			if(($pasfactu == "Anulada") and ($soniguales<=1)){
				$factprincipal = $numefactu;
			}


		}else {//comienzo1

			//los entes son distintos
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				$soniguales=2;

				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if(($pasfactu == "Anulada") and ($sipaso == "anu")){
						$factprincipal = $factprincipal-1;
						$sipaso="";
					}

					if($laulfacqpaso <> $factprincipal){
						$numfafinal = "$factprincipal-$cortfact";
					}else{
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$factprincipal = $numefactu;	      
						  
				}
					  
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=$sumafactura+$montodfactu; 
				$enelhaber="0,000"; 
				$lalinea++;
				$alinea      = "$lalinea,000";
				
				
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
			}//fin de los entes son distintos
				 

			//los entes son iguales pero la factura es anulada
			if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else{
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
								  
								  
				} 
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";
							  
			}//fin de los entes son iguales pero la factura es anulada



			//los entes son distintos pero la factura es anulada
			if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
				if($soniguales>=1){
								  
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					} else {
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
					$factprincipal = $f_factura[numero_factura]-1;	  
					$anu=1;    
				} 
							  
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$sipaso="anu";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";

			}//fin de los entes son distintos pero la factura es anulada
					      
					      
			//los entes son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
						}else{
							$numfafinal = $factprincipal;
						}

					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
					
								  
				} 

				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactu = $montodfactu - $deducible;
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;//+
				$cuentadescrip='Ingresos por Servicios a Particulares';
				$cuentacontable="4.01.01.02.001";
				$sumafactura=0; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				
			}//fin de los entes son iguales la factura no esta anulada pero tiene deducible


			//los entes NO son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					} else {
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
								 
				} 
				$factprincipal = $numefactu;
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactu = $montodfactu - $deducible;
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";

				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$cuentadescrip='Ingresos por Servicios a Particulares';
				$cuentacontable="4.01.01.02.001";
				$sumafactura=0; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);

			} //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
					      

			
		}//fin de comienzo1
		  
	}
   
   
   ////
   
    if($peof==$cuantoquery){
			$j=$j+1;
		if(($estafactura <> 3) and ($deducible <= '0')){
			$cuentadescrip='Ingresos por Servicios a Particulares';
			$cuentacontable="4.01.01.02.001";
			$sumafactura=($sumafactura+$montodfactu-$montodfactu); 
			$montodfactut=0;
			$enelhaber=$sumafactura;  
			$lalinea++;
			$alinea      = "$lalinea,000";
			$cortfact = substr($laulfacqpaso,-2);

			if ($laulfacqpaso <> $factprincipal) {
				$numfafinal = "$factprincipal-$cortfact";
			} else {
			      $numfafinal = $factprincipal;
			}
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$montodfactu=$montodfactu;
			$sumafactura=0;
			$j++;
			$factprincipal = $numefactu;	      
						  
		}
	}

   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}



//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////




   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}

if($sucursal==1){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//
 $totalq = ("select 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif,
	sum(tbl_procesos_claves.monto),
	sum(tbl_procesos_claves.fac_deducible) as sum_deducible
from 
	tbl_facturas,
	tbl_procesos_claves,
	procesos,
	titulares,
	entes,
	tbl_tipos_entes,
	tbl_series
where 
	tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
	tbl_facturas.id_serie=tbl_series.id_serie
	$id_serie and
	tbl_facturas.fecha_emision>='$fechaini' and 
	tbl_facturas.fecha_emision<='$fechafin' and 
	tbl_procesos_claves.id_proceso=procesos.id_proceso and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente 
	$elente and
	entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
	$eltipo_ente 
	$tipo_pago  
	$andnumcheque
group by 
	tbl_facturas.numero_factura,
	tbl_facturas.numcontrol,
	tbl_facturas.id_factura,
	tbl_facturas.id_estado_factura,
	tbl_facturas.fecha_emision,
	tbl_series.nomenclatura,
	tbl_series.nombre,
	tbl_facturas.id_serie,
	tbl_facturas.id_banco,
	tbl_facturas.numero_cheque,
	entes.nombre,entes.rif
order by 
	tbl_series.nomenclatura,
	tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	$anu=0;	 
	$peof++;
	$estafactura = $f_factura[id_estado_factura];
	$deducible   = $f_factura[sum_deducible];
	$montodfactu = $f_factura[sum];
	$nomdelente  = $f_factura[nombre];
	$numefactu   = $f_factura[numero_factura];
	if($nomdelente === 'PARTICULAR'){
		$nomrifente = 'AMBVGA';
	}else{
		$nomrifente  = str_replace("-", "",$f_factura['rif']);
	}
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
	if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";

		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
		$factprincipal = $numefactu;

	}else{
		if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			$cuentadescrip='Cuentas por Cobrar Clientes';
			$cuentacontable="1.01.03.01.001";
			$sumafactura=$sumafactura+$montodfactu; 
			$enelhaber="0,000"; 
			$lalinea++;
			$alinea      = "$lalinea,000";
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$soniguales++;

			if(($pasfactu == "Anulada") and ($soniguales<=1)){
				$factprincipal = $numefactu;
			}



		} else { //comienzo1


			//los entes son distintos
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){

				$soniguales=2;
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);
					
					if(($pasfactu == "Anulada") and ($sipaso == "anu")) {
						$factprincipal = $factprincipal-1;
						$sipaso="";
					}
					if($laulfacqpaso <> $factprincipal){
						$numfafinal = "$factprincipal-$cortfact";
					}else{
										$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$factprincipal = $numefactu;	      

				}
				
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=$sumafactura+$montodfactu; 
				$enelhaber="0,000"; 
				$lalinea++;
				$alinea      = "$lalinea,000";
					
					
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					
			}//fin de los entes son distintos
				


			//los entes son iguales pero la factura es anulada
			if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){

				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					}else{
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
					
				} 
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";
							
			}//fin de los entes son iguales pero la factura es anulada



			//los entes son distintos pero la factura es anulada
			if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
				if($soniguales>=1){
								
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					} else {
						$numfafinal = $factprincipal;
					}
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$montodfactu=$montodfactu;
						$sumafactura=0;
						$j++;
						$soniguales=0;
						$factprincipal = $f_factura[numero_factura]-1;	  
						$anu=1;    
				} 
							
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactut="0,000"; 
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$sipaso="anu";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$factprincipal = $numefactu;
				$pasfactu="Anulada";

			}//fin de los entes son distintos pero la factura es anulada
						

						
			//los entes son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					} else {
						$numfafinal = $factprincipal;
					}
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						$montodfactu=$montodfactu;
						$sumafactura=0;
						$j++;
						$soniguales=0;
				} 

				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactu = $montodfactu - $deducible;
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";

				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;//+
				$cuentadescrip='Ingresos por Servicios a Particulares';
				$cuentacontable="4.01.01.02.001";
				$sumafactura=0; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							
			}//fin de los entes son iguales la factura no esta anulada pero tiene deducible



			//los entes NO son iguales la factura no esta anulada pero tiene deducible
			if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							
				if($soniguales>=1){
					$cuentadescrip='Ingresos por Servicios a Particulares';
					$cuentacontable="4.01.01.02.001";
					$sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					$montodfactut=0;
					$enelhaber=$sumafactura;  
					$lalinea++;
					$alinea      = "$lalinea,000";
					$cortfact = substr($laulfacqpaso,-2);

					if($laulfacqpaso <> $factprincipal){
						$numfafinal="$factprincipal-$cortfact";
					} else {
						$numfafinal = $factprincipal;
					}
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					$montodfactu=$montodfactu;
					$sumafactura=0;
					$j++;
					$soniguales=0;
								
				} 

				$factprincipal = $numefactu;
				$cuentadescrip='Cuentas por Cobrar Clientes';
				$cuentacontable="1.01.03.01.001";
				$sumafactura=0; 
				$montodfactu = $montodfactu - $deducible;
				$enelhaber="0,000"; 
				$pasopanu=0;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$cuentadescrip='Ingresos por Servicios a Particulares';
				$cuentacontable="4.01.01.02.001";
				$sumafactura=0; 
				$enelhaber="0,000"; 
				$pasopanu=1;
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$j++;
				$entetompral = "$nomdelente (DEDUCIBLE)";
				$lalinea++;
				$alinea      = "$lalinea,000";
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			
			}//Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible

				
			
		}//fin de comienzo1
		  
	}
   
   
   
    if($peof==$cuantoquery){
		$j=$j+1;
      	if(($estafactura <> 3) and ($deducible <= '0')){
			$cuentadescrip='Ingresos por Servicios a Particulares';
			$cuentacontable="4.01.01.02.001";
			$sumafactura=($sumafactura+$montodfactu-$montodfactu); 
			$montodfactut=0;
			$enelhaber=$sumafactura;  
			$lalinea++;
			$alinea      = "$lalinea,000";
			$cortfact = substr($laulfacqpaso,-2);

			if($laulfacqpaso <> $factprincipal){
		     	$numfafinal = "$factprincipal-$cortfact";
			}else{
				$numfafinal = $factprincipal;
			}

			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			$montodfactu=$montodfactu;
			$sumafactura=0;
			$j++;
			$factprincipal = $numefactu;	      
			
		}
	}


	///
	$elrifqpaso = $nomrifente;
	$pasoente = $nomdelente;
	$laulfacqpaso = $f_factura[numero_factura];
	$fechqpaso = $formfecha;
}



//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////
//////////////////////////////////////////////////




   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}


if($sucursal==7){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'SERIEG';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0,000"; 
              $lalinea++;
              $alinea      = "$lalinea,000";
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT-", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea,000";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0,000"; 
					  $lalinea++;
                      $alinea      = "$lalinea,000";
                      
                      
                      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $sipaso="anu";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
  if($peof==$cuantoquery){
		  $j=$j+1;
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea,000";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}

                    
 

   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}

if($sucursal==8){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'SERIEH';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0,000"; 
              $lalinea++;
              $alinea      = "$lalinea,000";
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea,000";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0,000"; 
					  $lalinea++;
                      $alinea      = "$lalinea,000";
                      
                      
                      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $sipaso="anu";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof==$cuantoquery){
		  $j=$j+1;
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea,000";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}

                    
 

   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}
//******Nueva programacion de la serie F****//
if($sucursal==6){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0,000"; 
              $lalinea++;
              $alinea      = "$lalinea,000";
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT-", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea,000";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0,000"; 
					  $lalinea++;
                      $alinea      = "$lalinea,000";
                      
                      
                      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $sipaso="anu";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
  if($peof==$cuantoquery){
		  $j=$j+1;
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea,000";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}

                    
 

   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}

//******Nueva programacion de la serie D****//

if($sucursal==9){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0,000"; 
              $lalinea++;
              $alinea      = "$lalinea,000";
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT-", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea,000";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0,000"; 
					  $lalinea++;
                      $alinea      = "$lalinea,000";
                      
                      
                      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $sipaso="anu";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
  if($peof==$cuantoquery){
		  $j=$j+1;
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea,000";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}

                    
 

   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}

//******Nueva programacion de la serie E****//

if($sucursal==3){	
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'Rg')
                    ->setCellValue("B".$i, 'Cuenta')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Comentario')
                    ->setCellValue("E".$i, 'Debe')
					->setCellValue("F".$i, 'Haber')
                    ->setCellValue("G".$i, 'C.Costo')
                    ->setCellValue("H".$i, 'Moneda')						
                    ->setCellValue("I".$i, 'Cambio')	
                    ->setCellValue("J".$i, 'Tipo Doc.')	
					->setCellValue("K".$i, 'Doc Ref')
					->setCellValue("L".$i, 'Cu. Gastos')	
					 ->setCellValue("M".$i, 'Auxiliar')
                    ->setCellValue("N".$i, 'Atributo1')
                    ->setCellValue("O".$i, 'Atributo2')
					->setCellValue("P".$i, 'Atributo3')	
					 ->setCellValue("Q".$i, 'Regla')
                    ->setCellValue("R".$i, 'Fec. Doc')
                    ->setCellValue("S".$i, 'Afecta Flujo Efec.')
					->setCellValue("T".$i, 'Monto no afecta')	
					 ->setCellValue("U".$i, 'Afecta posición monetaria')
                    ->setCellValue("V".$i, 'Monto no afecta posición')
                    ->setCellValue("W".$i, 'Modifica Patrimonio');


$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   if($nomdelente === 'PARTICULAR'){
	   $nomrifente = 'AMBVGA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   
   
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0,000";       
        $lalinea++;
        $alinea      = "$lalinea,000";
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
	      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0,000"; 
              $lalinea++;
              $alinea      = "$lalinea,000";
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
		      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT-", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
			  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea,000";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0,000"; 
					  $lalinea++;
                      $alinea      = "$lalinea,000";
                      
                      
                      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
					  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0,000"; 
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $sipaso="anu";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, 'FACTURA ANULADA', PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j,  $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,  $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING); 
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea,000";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
                                  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
								  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0,000"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $formfecha, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0,000"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $nomdelente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $montodfactu, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea,000";
                              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $entetompral, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $deducible, PHPExcel_Cell_DataType::TYPE_NUMERIC);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numefactu, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j,$nomrifente, PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
							  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
  if($peof==$cuantoquery){
		  $j=$j+1;
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea,000";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
                          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$j, $alinea, PHPExcel_Cell_DataType::TYPE_STRING);
					      $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$j, $cuentacontable, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$j, $cuentadescrip, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$j, $pasoente, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$j, $montodfactut, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$j, $enelhaber, PHPExcel_Cell_DataType::TYPE_NUMERIC);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$j, "01", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$j, "BSF", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$j, "FACT", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$j, $numfafinal, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$j, $fechqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$j, $elrifqpaso, PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$j, "0,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$j, "1,000", PHPExcel_Cell_DataType::TYPE_STRING);
						  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$j, "false", PHPExcel_Cell_DataType::TYPE_STRING);
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}

                    
 

   $style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );

    $sheet->getDefaultStyle()->applyFromArray($style);
    
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8.859); 
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(8.85); 
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setWidth(8.85); 
// Set cell number formats

$objPHPExcel->getActiveSheet()->getStyle("E2:E$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


$objPHPExcel->getActiveSheet()->getStyle("F2:F$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getActiveSheet()->getStyle("G2:G$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


$objPHPExcel->getActiveSheet()->getStyle("K2:K$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

$objPHPExcel->getActiveSheet()->getStyle("A2:A$j")->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
}

?>
