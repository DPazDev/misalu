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

list($baremo,$nom_baremo)=explode("@",$_REQUEST['baremo1']);
list($tbaremo,$nom_tbaremo)=explode("@",$_REQUEST['tbaremo']);

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
	

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


	if  ($tbaremo==0)
     /* buscamos las imagenologias radiologias etc*/
{
	
$q_baremo="select * from imagenologia_bi where imagenologia_bi.id_tipo_imagenologia_bi='$baremo' order by imagenologia_bi";
$r_baremo=ejecutar($q_baremo);

}
	if  ($tbaremo==2)
    /* buscamos las especialidades medicas*/
{
	
$q_baremo="select * from especialidades_medicas order by especialidades_medicas.especialidad_medica";
$r_baremo=ejecutar($q_baremo);

}

	if  ($tbaremo==1)
{
     /* buscamos los examenes de laboratorio, emergencia, hsopitalizacion etc*/
	
$q_baremo="select * from examenes_bl where examenes_bl.id_tipo_examen_bl='$baremo' order by examenes_bl.examen_bl";
$r_baremo=ejecutar($q_baremo);

}

	if  ($tbaremo==3)
{
     /* buscamos los baremos de medicamentos o suministros etc*/
	
 if ($baremo==1)
    {
        $id_dependencia='64';
        }
        else
        {
            $id_dependencia='89';
            }


$q_baremo="select  
                            tbl_insumos.id_insumo,
                            tbl_insumos.insumo,
                            tbl_insumos_almacen.monto_unidad_publico,
                            tbl_laboratorios.laboratorio  
                    from 
                            tbl_insumos,
                            tbl_insumos_almacen,
                            tbl_laboratorios
                    where 
                            tbl_insumos.id_tipo_insumo='$baremo' and
                            tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and 
                            tbl_insumos_almacen.id_dependencia=$id_dependencia  and 
                            tbl_laboratorios.id_laboratorio=tbl_insumos.id_laboratorio
                    order by 
                            tbl_insumos.insumo";
$r_baremo=ejecutar($q_baremo);


}

	if  ($tbaremo==4)
    /* buscamos las especialidades medicas*/
{
	
$q_baremo="select * from servicios where id_servicio<>12 order by servicios.servicio";
$r_baremo=ejecutar($q_baremo);

}
		
        
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Reporte de  Baremos ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra los Baremos de CliniSalud")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$i=9;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, '')
                    ->setCellValue("B".$i, 'Codigo')
                    ->setCellValue("C".$i, 'Descripcion')
                    ->setCellValue("D".$i, 'Monto CliniSalud')
                    ->setCellValue("E".$i, 'Monto Privado');	
                    
                    // Seleccionando la fuente a utilizar
$objPHPExcel->getDefaultStyle()->getFont()->setName(‘Arial’);
$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);

$objPHPExcel->setActiveSheetIndex(0);


$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun( "Rif:J-31180863-9");
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('C2')->setValue($objRichText );
	// Merge cells
    $objPHPExcel->getActiveSheet()->mergeCells('C2:E2');
    $objPHPExcel->getActiveSheet()->getStyle('C2:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);


$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun( "Merida $fechacreado");
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('A5')->setValue($objRichText );
	// Merge cells
    $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
    $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun( "BAREMOS DEL TIPO $nom_tbaremo ($nom_baremo)");
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('A8')->setValue($objRichText );
	// Merge cells
    $objPHPExcel->getActiveSheet()->mergeCells('A8:E8');
    $objPHPExcel->getActiveSheet()->getStyle('A8:E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

 
	$i=10;
		while($f_baremo=asignar_a($r_baremo,NULL,PGSQL_ASSOC)){
			$i++;
	

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, '', PHPExcel_Cell_DataType::TYPE_STRING);

	if  ($tbaremo==0)
{

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$tbaremo-$baremo-$f_baremo[id_imagenologia_bi]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_baremo[imagenologia_bi], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_baremo[honorarios], PHPExcel_Cell_DataType::TYPE_STRING);            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_baremo[hono_privados], PHPExcel_Cell_DataType::TYPE_STRING);  		    
     }
     
     	if  ($tbaremo==2)
{
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$tbaremo-$baremo-$f_baremo[id_especialidad_medica]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_baremo[especialidad_medica], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_baremo[monto], PHPExcel_Cell_DataType::TYPE_STRING);            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_baremo[hono_privados], PHPExcel_Cell_DataType::TYPE_STRING);  	
    
    }
    
    	if  ($tbaremo==1)
{
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$tbaremo-$baremo-$f_baremo[id_examen_bl]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_baremo[examen_bl], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_baremo[honorarios], PHPExcel_Cell_DataType::TYPE_STRING);            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_baremo[hono_privados], PHPExcel_Cell_DataType::TYPE_STRING);  	
    }
    
    
        	if  ($tbaremo==3)
{
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$tbaremo-$baremo-$f_baremo[id_insumo]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, "$f_baremo[insumo] $f_baremo[laboratorio]", PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, number_format($f_baremo[monto_unidad_publico],2,'.',''), PHPExcel_Cell_DataType::TYPE_STRING);            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, number_format($f_baremo[monto_unidad_publico],2,'.',''), PHPExcel_Cell_DataType::TYPE_STRING);  	
}

        	if  ($tbaremo==4)
{
    $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_baremo[codigo]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, "$f_baremo[servicio]", PHPExcel_Cell_DataType::TYPE_STRING);  
}
    
    
}
                		            				

//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../public/images/head.png');
$objDrawing->setHeight(90);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// Set style for header row using alternative method

$objPHPExcel->getActiveSheet()->getStyle('A9:E9')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Baremo Clinisalud');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_baremo_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>
