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


$fecha = date("Y-m-d");
 $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Venta de Polizas Individuales ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion Auditar Reembolso")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->getActiveSheet(0)->mergeCells('A3:E3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación Auditar Reembolso con Fecha de Vigencia de Contrato desde el $fecre1 al $fecre2 ");


$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'ORDEN')
                    ->setCellValue("B".$i, 'TITULAR')
                    ->setCellValue("C".$i, 'CEDULA TITULAR')
                    ->setCellValue("D".$i, 'BENEFICIARIO')
                    ->setCellValue("E".$i, 'CEDULA BENEFICIARIO')
                    ->setCellValue("F".$i, 'FECHA RECIBIDO')
                    ->setCellValue("G".$i, 'FECHA VENC. ULTIMA CUOTA')
                    ->setCellValue("H".$i, 'ENTE')
                    ->setCellValue("I".$i, 'ESTADO PROCESO')
                    ->setCellValue("J".$i, 'ANALISTA')
                    ->setCellValue("K".$i, 'REVISION MEDICA')
                    ->setCellValue("L".$i, 'FECHA ADMINISTRACION')
                    ->setCellValue("M".$i, 'MONTO RECONOCIDO')
                    ->setCellValue("N".$i, 'FECHA CHEQUE')
                    ->setCellValue("O".$i, 'DIAS DE PAGO')
                    ->setCellValue("P".$i, '90 DIAS DE VENCIMIENTO');	

$q_reemb=("
select
	procesos.id_proceso,
	procesos.id_titular,
	procesos.id_beneficiario,
	procesos.fecha_recibido, 
	procesos.id_admin,
	procesos.id_estado_proceso,
	procesos.hora_creado,
	titulares.id_ente,
	admin.nombres,
	admin.apellidos,
	clientes.id_cliente,
	(clientes.nombres) AS n,
	(clientes.apellidos) AS a,
	clientes.cedula,
	entes.nombre,
	estados_procesos.estado_proceso,
	admin.nombres,
	admin.apellidos,
	procesos.comentarios_medico
	from procesos,gastos_t_b,admin,titulares,clientes,entes,estados_procesos 
where 
	procesos.fecha_recibido between '$fecre1' and '$fecre2' and 
	gastos_t_b.id_proceso=procesos.id_proceso
	and procesos.id_estado_proceso>0 
	and  (procesos.id_beneficiario>0 or procesos.id_beneficiario=0) and
	admin.id_admin=procesos.id_admin 
	and admin.id_sucursal>0 
	and (gastos_t_b.id_servicio=1 or gastos_t_b.id_servicio=10) and
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente=entes.id_ente  and
	titulares.id_cliente=clientes.id_cliente and 
	procesos.id_estado_proceso=estados_procesos.id_estado_proceso 
group by
	procesos.id_proceso,
	procesos.id_titular,
	procesos.id_beneficiario,
	procesos.fecha_recibido, 
	procesos.id_admin,
	procesos.id_estado_proceso,
	procesos.hora_creado,
	titulares.id_ente,
	admin.nombres,
	admin.apellidos,
	clientes.id_cliente,
	n,
	a,
	clientes.cedula,
	entes.nombre,
	estados_procesos.estado_proceso,
	admin.nombres,
	admin.apellidos,
	procesos.comentarios_medico

ORDER BY procesos.id_proceso DESC");

$r_reemb=ejecutar($q_reemb);

$contador=0;
	while($f_reemb=asignar_a($r_reemb)){
    $i++;
	$contador++;
$nombe="";
$cebe="";

if ($f_reemb[id_beneficiario]>0){
$q_benf=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.id_cliente,clientes.fecha_nacimiento,clientes.sexo from clientes,beneficiarios where beneficiarios.id_beneficiario='$f_reemb[id_beneficiario]' and beneficiarios.id_cliente=clientes.id_cliente;");
 $r_benf=ejecutar($q_benf);
 $f_benf=asignar_a($r_benf);


$nombe="$f_benf[nombres] $f_benf[apellidos]";
$cebe=$f_benf[cedula];
}

$q_final=("select tbl_contratos_entes.id_ente, tbl_contratos_entes.fecha_final_pago from tbl_contratos_entes where tbl_contratos_entes.id_ente=$f_reemb[id_ente]");
$r_final=ejecutar($q_final);
$f_final=asignar_a($r_final);

$q_factura=("select facturas_procesos.id_proceso, facturas_procesos.fecha_creado, facturas_procesos.fecha_imp_che, facturas_procesos.monto_sin_retencion from facturas_procesos where
 $f_reemb[id_proceso]=facturas_procesos.id_proceso ");
$r_factura=ejecutar($q_factura);
$f_factura=asignar_a($r_factura);

$dia1=$f_factura[fecha_imp_che];
$dia2=$f_reemb[fecha_recibido];

if($f_reemb[id_estado_proceso]=='11')  {
$dias = ((strtotime("$dia1"))-(strtotime("$dia2")))/86400;
	$dias = abs($dias); 
	$dias = round($dias);         
$dia3=date("Y/m/d", strtotime("$dia1 + 90 day"));
}

else {$dias=' ';
$dia3=' ';}	
	

	
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_reemb[id_proceso], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_reemb[n] $f_reemb[a]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_reemb[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $nombe, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $cebe, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_reemb[fecha_recibido], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_final[fecha_final_pago], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_reemb[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_reemb[estado_proceso], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, "$f_reemb[nombres] $f_reemb[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_reemb[comentarios_medico], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_factura[fecha_creado], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, $f_factura[monto_sin_retencion]);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, $f_factura[fecha_imp_che], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, $dias);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $dia3, PHPExcel_Cell_DataType::TYPE_STRING);

}
$j=$i;
$i++;
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("M6:M$i")->getNumberFormat()
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
$objPHPExcel->getActiveSheet()->getStyle('A6:P6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Auditar_Reembolso');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Auditar_Reembolso_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>


