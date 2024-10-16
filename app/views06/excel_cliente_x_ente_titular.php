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

/* Nombre del Archivo: reporte_cliente_x_ente_titular.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación de los Clientes Titulares Totales, de un determinado Ente
*/  

   list($estadot,$estado_clientet)=explode("@",$_REQUEST['estadot']);
	if($estadot==0)	        $condicion_estadot="";
	else
   $condicion_estadot="	and estados_t_b.id_estado_cliente='$estadot'";

   list($estadob,$estado_clienteb)=explode("@",$_REQUEST['estadob']);
	if($estadob==0)	        $condicion_estadob="";
	else
   $condicion_estadob="	and estados_t_b.id_estado_cliente='$estadob'";

   list($subdivi)=explode("@",$_REQUEST['subdivi']);
	if($subdivi==0)	        $condicion_subdivi="";
	else
   $condicion_subdivi="and titulares_subdivisiones.id_subdivision='$subdivi'";

   list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);



if($id_ente==0)	        $condicion_ente="and entes.id_ente>0";
	
	else
	$condicion_ente="and entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}


// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Clientes por Entes ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion de Clientes Titulares por Entes")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->getActiveSheet(0)->mergeCells('A3:J3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación Clientes Titulares en Estado $estado_clientet, del Tipo de Ente $nom_tipo_ente, del Ente $ente.");

$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'CLIENTE');	
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'TITULAR');	
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'CEDULA TITULAR');	
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'ESTADO');	
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'ENTE');	
 	   

$q_titular=("select 
	clientes.id_cliente,
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente, entes.nombre 
	from 
	entes,clientes, estados_clientes, estados_t_b, titulares,titulares_subdivisiones 
	where 
	clientes.id_cliente=titulares.id_cliente  
	$condicion_ente $tipo_entes 
	and titulares.id_ente=entes.id_ente and 
	titulares.id_titular=estados_t_b.id_titular and 
	estados_t_b.id_beneficiario='0' 
	$condicion_estadot
	 and
	titulares.id_titular=titulares_subdivisiones.id_titular  
	$condicion_subdivi and
	estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente
	group by 
	clientes.id_cliente,	
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.id_ente,
	titulares.tipocliente,
	entes.id_tipo_ente,entes.nombre  
	order by estados_clientes.estado_cliente,clientes.apellidos,entes.nombre");
$r_titular=ejecutar($q_titular);

	$ta=0;
	$te=0;
	$ba=0;
	$be=0;

	while($f_titular=asignar_a($r_titular)){

if($f_titular[tipocliente]!='0'){

		if ($f_titular['id_estado_cliente']=='4'){
			$ta=$ta + 1;
		}else if ($f_titular['id_estado_cliente']=='5') {
			$te=$te + 1;
		}
	   	    $i++;	

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_titular[nombre], PHPExcel_Cell_DataType::TYPE_STRING);

}}




$j=$i;
$i++;
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}
/*
// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("Y6:Y$i")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("Z6:Z$i")->getNumberFormat()
->setFormatCode('#,##0.00');*/
 
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
$objPHPExcel->getActiveSheet()->getStyle('A6:O6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Excel_Clientes_por_Entes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Excel_Clientes_por_Entes_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 




