<?php
include ("../../lib/jfunciones.php");
sesion();
/* propiedades para armar el reporte de excel con PHPEXCEL */
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

/* Nombre del Archivo: excel_clientes_x_cobertura.php
   Descripción: Realiza el Reporte de Impresión en Excel con los datos seleccionados: Relación Gastos de Clientes por Coberturas, de un determinado Ente */  


/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $ente=$_REQUEST[ente];
    $q_ente=("select entes.nombre from entes where entes.id_ente='$ente'");
    $r_ente=ejecutar($q_ente);
    $f_ente=asignar_a($r_ente);

    $poliza=$_REQUEST[poliza];
    $q_poliza=("select propiedades_poliza.*, polizas.nombre_poliza from polizas, propiedades_poliza where		 
		propiedades_poliza.id_poliza=polizas.id_poliza and
		propiedades_poliza.id_propiedad_poliza='$poliza'");
    $r_poliza=ejecutar($q_poliza);
    $f_poliza=asignar_a($r_poliza);

    $monto=$_REQUEST[monto];


	$r_procesot=("select clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, coberturas_t_b.id_titular, coberturas_t_b.id_beneficiario, coberturas_t_b.monto_actual, propiedades_poliza.cualidad 
from clientes,titulares,coberturas_t_b,propiedades_poliza where 
coberturas_t_b.id_propiedad_poliza='$poliza' and
coberturas_t_b.id_titular=titulares.id_titular and
titulares.id_ente='$ente' and
clientes.id_cliente=titulares.id_cliente and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
order by coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario");
	$q_procesot=ejecutar($r_procesot);


// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setTitle("Clientes por Cobertura ")
                                                         ->setSubject("Office 2007 XLSX Test Document")
                                                         ->setDescription("Reporte Relación Gastos de Clientes por Coberturas ")
                                                         ->setKeywords("office 2007 openxml php")
                                                         ->setCategory("Test result file");
   
/*$i=3;*/
$objPHPExcel->getActiveSheet(0)->mergeCells('A3:I3');
$objPHPExcel->getActiveSheet()->setCellValue('A3',"Reporte Relación Gastos de Clientes por Coberturas, Cobertura $f_poliza[cualidad] -- $f_poliza[nombre_poliza], monto menor o igual $monto Bs.S, del Ente $f_ente[nombre] ");



$i=6;//variable para dar la posicion de la celda;

$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'CLIENTE');
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'TITULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'CEDULA TITULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'CLIENTE');
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'CEDULA BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'CELULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'TLF.');
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'PARENTESCO');
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, 'MONTO DISPONIBLE Bs.S.');

  	$t=0;
	$b=0;	
	     while($f_procesot=asignar_a($q_procesot,NULL,PGSQL_ASSOC))
		{

if($f_procesot[id_beneficiario]==0 && $f_procesot[monto_actual] <= $monto){
$t=$t+ 1;
    $i++;
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_procesot[id_titular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_procesot[nombres] $f_procesot[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_procesot[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, '', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, '', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, '', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_procesot[celular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_procesot[telefono_hab], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, '', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, $f_procesot[monto_actual]);

 }
	
	$r_procesob=("select beneficiarios.id_beneficiario,clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, parentesco.parentesco from clientes,parentesco,beneficiarios where 
beneficiarios.id_titular='$f_procesot[id_titular]' and
beneficiarios.id_beneficiario='$f_procesot[id_beneficiario]' and
beneficiarios.id_parentesco=parentesco.id_parentesco and
clientes.id_cliente=beneficiarios.id_cliente 
order by beneficiarios.id_beneficiario");

	$q_procesob=ejecutar($r_procesob);
			


	     while($f_procesob=asignar_a($q_procesob,NULL,PGSQL_ASSOC))
		{
if($f_procesot[id_beneficiario]>0 && $f_procesot[monto_actual] <= $monto){
$b=$b+1;
    $i++;

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_procesot[id_titular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_procesot[nombres] $f_procesot[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_procesot[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_procesob[id_beneficiario], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, "$f_procesob[nombres] $f_procesob[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_procesob[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_procesob[celular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_procesob[telefono_hab], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_procesob[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, $f_procesot[monto_actual]);


 }} }

$j=$i;
$i++;
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("J6:J$i")->getNumberFormat()
->setFormatCode('#,##0.00');

// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../public/images/head.png');
$objDrawing->setHeight(50);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing->setCoordinates('A2');
$objDrawing->setOffsetX(150);
$objDrawing->setOffsetY(150);
$objDrawing->setRotation(25);
$objDrawing->getShadow()->setVisible(true);
$objDrawing->getShadow()->setDirection(60);

// Set style for header row using alternative method
$objPHPExcel->getActiveSheet()->getStyle('A6:K6')->applyFromArray(
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
// finalizar las propiedades  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Clientes por Coberturas');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Clientes_por_Coberturas_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>


