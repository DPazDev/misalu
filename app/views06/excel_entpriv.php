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



/* Nombre del Archivo: excel_paraente.php
   Descripción: Reporte de Impresión en excel con los datos seleccionados: Relación Entes Privados
*/

   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];
   $replogo=$_REQUEST['lgnue'];   

list($repente,$entenombre)=explode("@",$_REQUEST['enpriv']);
if($repente=="-04"){
	$condicion_ente="and entes.id_tipo_ente='4' and titulares.id_ente=entes.id_ente";
    $yente="entes,titulares";
    $gente="entes.nombre";
}else{
    $condicion_ente="and titulares.id_ente=$repente and titulares.id_ente=entes.id_ente";
    $yente="titulares,entes"; 
    $gente="entes.nombre";
 }  
list($restpro,$estnombre)=explode("@",$_REQUEST['estapro']);  
 if($restpro==0)
    $condicion_restpro="";
else
    $condicion_restpro="and procesos.id_estado_proceso=$restpro";

list($id_sucursal, $sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal=='-01')
$condicion_sucursal="and admin.id_sucursal!='2'";
else if($id_sucursal==0)
  $condicion_sucursal="";
else
$condicion_sucursal="and admin.id_sucursal=$id_sucursal";

list($id_servicio,$servicio)=explode("@",$_REQUEST['servic']);
if($id_servicio==0)	       
  $condicion_servicio="and gastos_t_b.id_servicio>0";

else if($id_servicio=="-01"){
	$condicion_sevicio="and gastos_t_b.id_servicio=4 and gastos_t_b.id_servicio=6";
}
else
$condicion_servicio="and gastos_t_b.id_servicio=$id_servicio";


   $qreporte=("select
procesos.id_proceso,
procesos.id_titular,
procesos.id_beneficiario,
procesos.comentarios,
procesos.fecha_recibido,
procesos.fecha_ent_pri,
procesos.id_admin,
procesos.no_clave,
subdivisiones.subdivision,
servicios.servicio,
servicios.id_servicio,
entes.nombre,
count(gastos_t_b.id_proceso)
from 
  procesos,gastos_t_b,admin,subdivisiones,$yente,titulares_subdivisiones,servicios
where 
procesos.fecha_ent_pri between '$fecre1' and '$fecre2' and 
gastos_t_b.id_proceso=procesos.id_proceso  and
 admin.id_admin=procesos.id_admin  
$condicion_sucursal  $condicion_servicio $condicion_ente $condicion_restpro and 
procesos.id_titular=titulares.id_titular and 
titulares.id_titular=titulares_subdivisiones.id_titular and
titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and 
servicios.id_servicio=gastos_t_b.id_servicio

group by 
procesos.id_proceso,
procesos.id_titular,
procesos.id_beneficiario,
procesos.comentarios,
procesos.fecha_recibido,
procesos.fecha_ent_pri,
procesos.id_admin,titulares.id_ente,
procesos.no_clave,
subdivisiones.subdivision,
servicios.servicio,
servicios.id_servicio,
entes.nombre,
$gente
ORDER BY procesos.no_clave DESC");
$rreporte=ejecutar($qreporte);

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Clientes por Entes ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion Entes Privados")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");



$objPHPExcel->getActiveSheet(0)->mergeCells('A3:J3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación $servicio, en Estado $estnombre del Ente Privado $entenombre, Atendidos por consultas, Laboratorios, Radiologia, Estudios Especiales y Servicio de Emergencias  .");
$objPHPExcel->getActiveSheet()->setCellValue('A4', "Relación desde $fecre1 al $fecre2  .");
$objPHPExcel->getActiveSheet()->setCellValue('A5', "$sucursal  .");

$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'ORDEN');	
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'CLAVE');
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'ENTE');	
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'SUBDIVISION');	
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'TITULAR');	
$objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'CEDULA TITULAR');	
$objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'BENEFICIARIO');	
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'CEDULA BENEFICIARIO');	
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'FECHA');	
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, 'SERVICIO');	
$objPHPExcel->getActiveSheet(0)->setCellValue("K".$i, 'DIAGNOSTICO');	
$objPHPExcel->getActiveSheet(0)->setCellValue("L".$i, 'MONTO (Bs.S.)');		
 

	     $k=1;
		  $bsf=0; 

	     while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

			$rtitular=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where titulares.id_titular=$freporte[id_titular] and titulares.id_cliente=clientes.id_cliente"); 
			$qtitular=ejecutar($rtitular);
			$datatitular=asignar_a($qtitular);
			$ftitular="$datatitular[nombres] $datatitular[apellidos]";
			$fcedula="$datatitular[cedula]";
			   if ($freporte[id_beneficiario]>0){
				  $rbenf=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,beneficiarios where beneficiarios.id_beneficiario=$freporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
				    $fcedulab="$databenf[cedula]";
			  }else{$fbenf=''; $fcedulab='';}


$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$freporte[id_proceso]");
$rgasto=ejecutar($qgasto);

			$bsf=0;
		while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC)){
			  $bsf = $bsf+$fgasto[monto_aceptado];
			  $bsf1 = $bsf1+$fgasto[monto_aceptado];}

  $i++;	

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $freporte[id_proceso], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $freporte[no_clave], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $freporte[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $freporte[subdivision], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $ftitular, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $fcedula, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $fbenf, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $fcedulab, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $freporte[fecha_ent_pri], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $freporte[servicio], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $freporte[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("L".$i, $bsf);


	
		$k++;
		}

$j=$i;
$i++;
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
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

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}



// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("L6:L$i")->getNumberFormat()
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
$objPHPExcel->getActiveSheet()->setTitle('Excel_Entes_Privados');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Excel_Entes_Privados_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

