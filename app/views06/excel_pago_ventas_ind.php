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

	$condicion_fecha="and tbl_recibo_pago.fecha_creado between '$fecre1' and '$fecre2'";

   list($id_tipo_pago,$tipo_pago)=explode("@",$_REQUEST['pagos']);
	if($id_tipo_pago==0)	        $condicion_pagos="";
	else
	$condicion_pagos="and tbl_tipos_pagos.id_tipo_pago=$id_tipo_pago";


// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Pago Venta de Polizas Individuales ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion de Pago de Venta de Polizas Individuales")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->getActiveSheet(0)->mergeCells('A3:G3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación Pago Venta de Pólizas Individuales por Fecha de Pago desde el $fecre1 al $fecre2 ");


$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'NUMERO CONTRATO')
		    ->setCellValue("B".$i, 'NUM CUADRO RP')
        	    ->setCellValue("C".$i, 'FECHA CREADO')
        	    ->setCellValue("D".$i, 'FECHA PAGO')
        	    ->setCellValue("E".$i, 'FECHA EFECTIVO PAGO')
                    ->setCellValue("F".$i, 'FORMA PAGO')
                    ->setCellValue("G".$i, 'SERIE')
                    ->setCellValue("H".$i, 'RECIBO NUMERO')
                    ->setCellValue("I".$i, 'BANCO ')
                    ->setCellValue("J".$i, 'CHEQUE')
                    ->setCellValue("K".$i, 'MONTO RECIBO PAGO')
                    ->setCellValue("L".$i, 'ESTADO')
                    ->setCellValue("M".$i, 'ENTE')
                    ->setCellValue("N".$i, 'PLAN');

$q_venta=("select 
			tbl_contratos_entes.numero_contrato,
			tbl_contratos_entes.id_ente,
			entes.nombre,
			tbl_contratos_entes.cuotacon, 
			tbl_contratos_entes.id_contrato_ente,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_contrato.id_contrato_ente,
			tbl_recibo_pago.saldo_deudor,
			tbl_recibo_pago.id_serie,
			tbl_recibo_pago.fecha_pago,					
			tbl_recibo_pago.fecha_creado,
			tbl_recibo_pago.fecha_efec_pago,
			tbl_recibo_pago.id_tipo_pago,
			tbl_tipos_pagos.tipo_pago, 
			tbl_recibo_pago.id_recibo_pago,
			tbl_recibo_pago.monto,
			tbl_recibo_pago.numero_recibo,
			sum(tbl_caract_recibo_prima.monto_prima)
		from 
			tbl_contratos_entes,
			tbl_caract_recibo_prima,
			tbl_recibo_contrato,
			tbl_recibo_pago,
			tbl_tipos_pagos , entes
		where
			tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
			tbl_recibo_contrato.id_recibo_contrato=tbl_caract_recibo_prima.id_recibo_contrato and 
			tbl_recibo_pago.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and tbl_recibo_pago.estado_recibo=1  and
			tbl_recibo_pago.id_tipo_pago=tbl_tipos_pagos.id_tipo_pago $condicion_fecha
			$condicion_pagos and
			tbl_contratos_entes.id_ente=entes.id_ente
		group by
			tbl_contratos_entes.numero_contrato,
			tbl_contratos_entes.cuotacon,
			tbl_contratos_entes.id_ente,
			entes.nombre,
			tbl_contratos_entes.id_contrato_ente,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_contrato.id_contrato_ente,
			tbl_recibo_pago.saldo_deudor,
			tbl_recibo_pago.id_serie, 
			tbl_recibo_pago.fecha_pago,
			tbl_recibo_pago.fecha_creado,
			tbl_recibo_pago.fecha_efec_pago,
			tbl_recibo_pago.id_tipo_pago,
			tbl_tipos_pagos.tipo_pago, 
			tbl_recibo_pago.id_recibo_pago,
			tbl_recibo_pago.monto,
			tbl_recibo_pago.numero_recibo 
			order by 
			tbl_contratos_entes.id_contrato_ente,
			tbl_recibo_pago.id_recibo_pago;  ");
$r_venta=ejecutar($q_venta);

$cont=0;

	while($f_venta=asignar_a($r_venta)){
    $i++;
$cont++;

/*$q_montopagado=("select sum(tbl_recibo_pago.monto) from tbl_recibo_pago where tbl_recibo_pago.id_recibo_contrato=$f_venta[id_recibo_contrato]");
$r_montopagado=ejecutar($q_montopagado);
$f_montopagado=asignar_a($r_montopagado);

$q_cuo=("select count(tbl_recibo_pago.id_recibo_contrato) from tbl_recibo_pago where tbl_recibo_pago.id_recibo_contrato=$f_venta[id_recibo_contrato] and tbl_recibo_pago.estado_recibo=1 ");
$r_cuo=ejecutar($q_cuo);
$f_cuo=asignar_a($r_cuo);
$cuotapaga=($f_cuo[count] -1);*/



$q_banco=("select tbl_oper_multi.id_banco, tbl_oper_multi.id_nom_tarjeta,tbl_oper_multi.numero_cheque,tbl_oper_multi.id_recibo_pago,tbl_tipos_pagos.tipo_pago,tbl_oper_multi.monto from tbl_oper_multi,tbl_tipos_pagos where tbl_oper_multi.id_recibo_pago=$f_venta[id_recibo_pago] and tbl_oper_multi.condicion_pago=tbl_tipos_pagos.id_tipo_pago");
$r_banco=ejecutar($q_banco);

$q_serie=("select tbl_series.nomenclatura from tbl_series where tbl_series.id_serie=$f_venta[id_serie]");
$r_serie=ejecutar($q_serie);
$f_serie=asignar_a($r_serie);


/* buscar el nombre de los planes activos */

$q_planes=("select 
			polizas.nombre_poliza, 
			count(polizas.id_poliza) 
		from 
			polizas,
			primas,
			tbl_caract_recibo_prima 
		where 
			tbl_caract_recibo_prima.id_recibo_contrato='$f_venta[id_recibo_contrato]' and 
			tbl_caract_recibo_prima.id_prima=primas.id_prima and 
			primas.id_poliza=polizas.id_poliza 
		group by 
			polizas.nombre_poliza 
			order by
			polizas.nombre_poliza;
");
$r_planes=ejecutar($q_planes);

$e=0;
$planes=" ";
	while($f_planes=asignar_a($r_planes)){
			$e++;
		if($e==1){
						  $planes =$f_planes[nombre_poliza];
						}else{
							 $planes="$planes,$f_planes[nombre_poliza]";
						  }
		
	
		
		}

/* fin de buscar el nombre de los planes activos */



/****  busco si este contrato es nuevo o es renovacion de contrato ****/
   		
		$q_num_con=("select 
					tbl_recibo_contrato.id_contrato_ente,
					count(tbl_recibo_contrato.id_contrato_ente) 
				from
					tbl_recibo_contrato 
				where 
					tbl_recibo_contrato.id_contrato_ente=$f_venta[id_contrato_ente]
				group by 
					tbl_recibo_contrato.id_contrato_ente");
$r_num_con=ejecutar($q_num_con);
$f_num_con=asignar_a($r_num_con);
		
		if ($f_num_con[count]==1)
		{
			$varrenovo="NUEVO";
		}
			else
		{
				$varrenovo="RENOVACION";
		}
		
/****  busco si este contrato es nuevo o es renovacion de contrato ****/



/*$q_cuota=("select tbl_recibo_pago.saldo_deudor, tbl_recibo_pago.fecha_pago,tbl_recibo_pago.id_tipo_pago,tbl_tipos_pagos.tipo_pago, tbl_recibo_pago.id_recibo_pago,tbl_recibo_pago.monto,tbl_recibo_pago.numero_recibo from tbl_recibo_pago,tbl_tipos_pagos where tbl_recibo_pago.id_recibo_contrato=$f_venta[id_recibo_contrato] and tbl_recibo_pago.estado_recibo=1 $condicion_pagos and tbl_recibo_pago.id_tipo_pago=tbl_tipos_pagos.id_tipo_pago order by tbl_recibo_pago.fecha_proxima_pago DESC limit 1");
$r_cuota=ejecutar($q_cuota);
$f_cuota=asignar_a($r_cuota);


/*$q_tipo_pago=("select tbl_tipos_pagos.tipo_pago from tbl_tipos_pagos where tbl_tipos_pagos.id_tipo_pago=$f_cuota[id_tipo_pago] ");
$r_tipo_pago=ejecutar($q_tipo_pago);
$f_tipo_pago=asignar_a($r_tipo_pago);*/


/*$a=$f_venta[sum];
$b=$f_montopagado[sum];
$c=$a-$b;*/


/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_venta[numero_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_venta[num_recibo_prima], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_venta[fecha_creado], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_venta[fecha_pago], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_venta[fecha_efec_pago], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_venta[tipo_pago], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_serie[nomenclatura], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_venta[numero_recibo], PHPExcel_Cell_DataType::TYPE_STRING);



$banco2="";
$cheque2="";
while($f_banco=asignar_a($r_banco)){
$q_cuenta=("select tbl_bancos.nombanco from tbl_bancos where tbl_bancos.id_ban=$f_banco[id_banco]");
$r_cuenta=ejecutar($q_cuenta);
$f_cuenta=asignar_a($r_cuenta);

if($f_banco[id_banco]=='0'){   // primera especificacion
$banco3="EFECTIVO";}

else{
$banco3="$f_cuenta[nombanco]";} // segunada especificacion


$banco2 .="$banco3".",";// para concatenar (guardar) toda la informacion en una variable, que cumpla con las especificaciones dadas


if($f_banco[numero_cheque]=='0'){
$cheque3="EFECTIVO $f_banco[monto]$";}
else{
$cheque3="$f_cuenta[nombanco] $f_banco[monto]$";}


$cheque2 .="$cheque3"." | ";


}



$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $banco2 , PHPExcel_Cell_DataType::TYPE_STRING);

// Eliminar línea de abajo
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $cheque2, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("K".$i, $f_venta[monto], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $varrenovo, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, $f_venta[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, $planes, PHPExcel_Cell_DataType::TYPE_STRING);

}
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
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("K6:K$i")->getNumberFormat()
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
$objPHPExcel->getActiveSheet()->getStyle('A6:N6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Pago_Ventas_Individuales');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Pago_Venta_Polizas_Individuales_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>











