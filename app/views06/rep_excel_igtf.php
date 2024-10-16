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
$sheet = $objPHPExcel->getActiveSheet();

// propiedades para el archivo de excel


// recepcion de datos enviados

   $fechaini=$_REQUEST['fecha1'];
   $fechafin=$_REQUEST['fecha2'];
   $sucursal=$_REQUEST['sucursal'];


        if ($sucursal==0)
{
    $id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
    $serie="Todas Las Series";
    }
    else
    {
    $id_serie="and tbl_series.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
    $q_serie=("select  * from tbl_series where id_serie=$sucursal");
    $r_serie=ejecutar($q_serie);
    $f_serie=asignar_a($r_serie);
    $serie="Serie ".$f_serie[nomenclatura];
        }

 
// Merge cells
                               
$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun ( "Reporte de Facturas con IGTF 3% de $serie");
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('B2')->setValue(utf8_encode($objRichText) );


$i=4;//variable para dar la posicion de la celda;
$r=4;
$objPHPExcel->getActiveSheet(0)
                    ->setCellValue("A".$i, 'SERIE')
                    ->setCellValue("B".$i, 'N° FACTURA')
                    ->setCellValue("C".$i, 'MONTO')
                    ->setCellValue("D".$i, 'PORCENTAJE DE IGTF')
                    ->setCellValue("E".$i, 'MONTO TOTAL FACTURADO')  
                    ->setCellValue("F".$i, 'FECHA DE EMISIÓN');    


//consulta sql

$rep_fac_igtf= pg_query(" select tbl_facturas.numero_factura,
                                  tbl_facturas.id_factura,
                                  tbl_facturas.fecha_emision,
                                  tbl_facturas.id_estado_factura,
                                  tbl_series.nomenclatura,
                                  tbl_facturas.id_serie,
                                  tbl_oper_multi.id_moneda,
                                  tbl_oper_multi.monto 
                             from 
                                  tbl_facturas,                           
                                  tbl_series,
                                  tbl_oper_multi
                            where     
                                  tbl_facturas.id_factura=tbl_oper_multi.id_factura
                             and  tbl_facturas.id_serie=tbl_series.id_serie 
                                  $id_serie 
                             and  tbl_facturas.id_estado_factura=1     
                             and  tbl_facturas.fecha_emision>='$fechaini' 
                             and  tbl_facturas.fecha_emision<='$fechafin' 
                             and  id_moneda>1 

                       group by  tbl_facturas.numero_factura,                                         
                                 tbl_facturas.id_factura,                        
                                 tbl_facturas.fecha_emision,
                                 tbl_facturas.id_estado_factura,
                                 tbl_series.nomenclatura,
                                 tbl_series.nombre, 
                                 tbl_oper_multi.id_moneda,
                                 tbl_oper_multi.monto,                                           
                                 tbl_facturas.id_serie ");


$b_IGTF= ("select * from variables_globales where  nombre_var='IGTF' "); 
$t_IGTF=ejecutar($b_IGTF);
$q_IGTF=asignar_a($t_IGTF);
$IGTF= $q_IGTF['cantidad'];
$porcientoIGTF= $q_IGTF['comprasconfig'].' %';


$contador=0;
while($f_rep_fac_igtf=pg_fetch_array($rep_fac_igtf, NULL, PGSQL_ASSOC)) 
{

    $facturaigtf= ("select sum(tbl_oper_multi.monto)  from tbl_oper_multi where id_factura=$f_rep_fac_igtf[id_factura] and id_moneda>1;");
     $monto_facturaigtf = ejecutar($facturaigtf);
     $monto_total_igtf   = asignar_a($monto_facturaigtf);
  
 $montoigtf = $monto_total_igtf[sum] * $IGTF;

 $monto_t= $monto_total_igtf[sum];
 


 
 $monto_total_fac=("select sum(tbl_oper_multi.monto)
                      from tbl_oper_multi 
                     where tbl_oper_multi.id_factura=$f_rep_fac_igtf[id_factura]");
 $monto_total_f = ejecutar($monto_total_fac);
 $monto_t_f   = asignar_a($monto_total_f);
 $monto_t_factura=$monto_t_f[sum];

 $monto_t_sum_igt = (($monto_t_factura) + ($montoigtf));
 

$r++;
$contador++;


$objPHPExcel->getProperties()->setTitle("Reporte de Facturas con IGTF 3% ")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");



            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$r, $f_rep_fac_igtf[nomenclatura], PHPExcel_Cell_DataType::TYPE_STRING);    
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$r, $f_rep_fac_igtf[numero_factura], PHPExcel_Cell_DataType::TYPE_STRING);    
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$r, montos_print($monto_t), PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$r, montos_print($montoigtf), PHPExcel_Cell_DataType::TYPE_STRING);   
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$r, montos_print($monto_t_sum_igt), PHPExcel_Cell_DataType::TYPE_STRING);   
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$r, $f_rep_fac_igtf[fecha_emision], PHPExcel_Cell_DataType::TYPE_STRING);   

}



// Set style for header row using alternative method

$objPHPExcel->getActiveSheet()->getStyle('A1:S4')->applyFromArray(
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


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=facturas_igtf$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 


?>
