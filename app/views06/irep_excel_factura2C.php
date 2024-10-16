<?php
include ("../../lib/jfunciones.php");
sesion();
/* propiedades para armar el reporte de excel */
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
/** Include PHPExcel */
require_once '../../lib/phpexcel/Classes/PHPExcel.php';
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
/* fin propiedades para armar el reporte de excel */
// recepcion de datos enviados



$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
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
	}
	else
	{
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
	}
	else
	{

		$eltipo_ente="tbl_tipos_entes.id_tipo_ente=$tipo_ente";
     }
        
       
        
		if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
		$serie="Todas Las Series";
	}
	else
	{
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


$i=9;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, '')
                    ->setCellValue("B".$i, 'Serie')
                    ->setCellValue("C".$i, 'Factura')
                    ->setCellValue("D".$i, 'Num Control')
                    ->setCellValue("E".$i, 'Fecha Emision')
					->setCellValue("F".$i, 'Estado')
                    ->setCellValue("G".$i, 'Ente')
                    ->setCellValue("H".$i, 'Monto')						
                    ->setCellValue("I".$i, 'Deducible')	
                    ->setCellValue("J".$i, 'Total Monto')	
					->setCellValue("K".$i, 'Nota Credito')
					->setCellValue("L".$i, 'Monto')	
					 ->setCellValue("M".$i, 'Banco')
                    ->setCellValue("N".$i, 'Num Cheque o tarjeta');
                    

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
											entes.nombre,
                                                                                        entes.rif,
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
                                  

 
$contador=0;
$sum_deducible=0;
$montofacturade=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	if ($f_factura[id_estado_factura]==1)
{
	$estado_factura="Pagadas";
	}
	
if ($f_factura[id_estado_factura]==2)
{
	$estado_factura="Por Cobrar";
	}
	
	
if ($f_factura[id_estado_factura]==3)
{
	$estado_factura="Anuladas";
	}
	
    $i++;
	if ($f_factura[id_estado_factura]==3)
			{
				$montofactura=0;
				$sum_deducible=0;
				$montofacturade=0;
			}
			else
			{
				$montofactura=$f_factura[sum];
				$sum_deducible=$f_factura['sum_deducible'];
				$montofacturade=($f_factura[sum] - $f_factura['sum_deducible']);
			}
	
    $totalmontpag1=$totalmontpag1 + $montofactura;
	
		/* **** busco el banco **** */
$q_banco=("select * from tbl_bancos where tbl_bancos.id_ban=$f_factura[id_banco] ");
$r_banco=ejecutar($q_banco);
$f_banco=asignar_a($r_banco);


	
	$contador++;
	
	/* busco si posee una nota de credito*/
$q_notacre="select * from tbl_notacredito where tbl_notacredito.id_factura=$f_factura[id_factura]";
$r_notacre=ejecutar($q_notacre);
$f_notacre=asignar_a($r_notacre);
   		
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_factura[nomenclatura], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, "00".$f_factura[numero_factura], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, "00".$f_factura[numcontrol], PHPExcel_Cell_DataType::TYPE_STRING);            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_factura[fecha_emision], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $estado_factura, PHPExcel_Cell_DataType::TYPE_STRING);          
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, "$f_factura[nombre] - ($f_factura[rif]) ", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, $montofactura );   
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i,  $sum_deducible);  
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, $montofacturade );  
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_notacre[num_notacredito], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("L".$i, $f_notacre[montonc]);   
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, $f_banco[nombanco], PHPExcel_Cell_DataType::TYPE_STRING);                    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, $f_factura[numero_cheque], PHPExcel_Cell_DataType::TYPE_STRING);   
}
$j=$i;
$i++;
pg_free_result($r_factura);
if($contador==1){
$contador="(Una Factura)";
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
}else{
$contador="($contador Facturas)";
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
}
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, 'total', PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, "=SUM(H4:H$j)");   
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, "=SUM(I4:I$j)");     
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, "=SUM(J4:J$j)");     

//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("H4:H$j")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("I4:I$j")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("J4:J$j")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("L4:L$j")->getNumberFormat()
->setFormatCode('#,##0.00');
// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../public/images/head.png');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// Set style for header row using alternative method

$objPHPExcel->getActiveSheet()->getStyle('A9:N9')->applyFromArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		)
);
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

?>
