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


//SI EN ESTE REPORTE no parecen clientes que si aparecen en el reporte anterior, es porque el cliente esta registrado sin subdivision, verificar


//codigo para auditar titulares subdivision repetidos
$q_titu_sub= "select
                                    titulares.id_titular,
                                    count(titulares_subdivisiones.id_titular),
                                    titulares_subdivisiones.id_subdivision
                        from
                                    titulares,
                                    titulares_subdivisiones
                        where
                                    titulares.id_titular=titulares_subdivisiones.id_titular 
                        group by
                                    titulares.id_titular,
                                    titulares_subdivisiones.id_subdivision 
                        order by
                                    count";
$r_titu_sub = ejecutar($q_titu_sub);


while($f_titu_sub = asignar_a($r_titu_sub)){
            if ($f_titu_sub[count]>1){
/* **** eliminar resultados para ser**** */
$q_eli_titu_sub=("delete  from
                                                titulares_subdivisiones
                                        where
                                                titulares_subdivisiones.id_titular='$f_titu_sub[id_titular]'");
$r_eli_titu_sub=ejecutar($q_eli_titu_sub);

$q_iner_titu_sub = "insert
                            into
                    titulares_subdivisiones
                            (id_titular,
                            id_subdivision)
                    values
                            ('$f_titu_sub[id_titular]',
                            '$f_titu_sub[id_subdivision]')";
$r_iner_titu_sub = ejecutar($q_iner_titu_sub);

}
}

//fin codigo para auditar titulares subdivision repetidos

$fecha = date("Y-m-d");
 $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Contratos Anulados de Venta de Polizas Individuales ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion de Contratos Anulados de la Venta de Polizas Individuales")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->getActiveSheet(0)->mergeCells('A3:J3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación Contratos Anulados de la Venta de Pólizas Individuales por Fecha de Vigencia de Contrato desde el $fecre1 al $fecre2 ");


$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, '')
                    ->setCellValue("B".$i, 'TITULAR')
                    ->setCellValue("C".$i, 'CEDULA')
                    ->setCellValue("D".$i, 'TELE_CASA')
                    ->setCellValue("E".$i, 'CELULAR')
                    ->setCellValue("F".$i, 'TIPO CLIENTE')
                    ->setCellValue("G".$i, 'NUMERO CONTRATO')
                    ->setCellValue("H".$i, 'SERIE')
                    ->setCellValue("I".$i, 'NUMERO FACTURA')
                    ->setCellValue("J".$i, 'SUBDIVISION')
                    ->setCellValue("K".$i, 'FECHA INICIO')
                    ->setCellValue("L".$i, 'FECHA FIN')
                    ->setCellValue("M".$i, 'COMISIONADO')
                    ->setCellValue("N".$i, 'RECIBO PRIMA')
                    ->setCellValue("O".$i, 'MONTO CONTRATO')	
                    ->setCellValue("P".$i, 'MONTO CANCELADO')
                    ->setCellValue("Q".$i, 'MONTO DEUDOR')
		    ->setCellValue("R".$i, 'PLAN')
                    ->setCellValue("S".$i, 'ES')
                    ->setCellValue("T".$i, 'ESTADO')
                    ->setCellValue("U".$i, 'ANALISTA')
                    ->setCellValue("V".$i, 'ANULADO')
                    ->setCellValue("W".$i, 'FECHA ANULADO');





       
/* $q_venta=("select 
	entes.nombre, 
	clientes.cedula, 
	titulares.tipocliente,
	tbl_contratos_entes.numero_contrato, 
	tbl_contratos_entes.id_contrato_ente,
	subdivisiones.subdivision,
	tbl_recibo_contrato.id_recibo_contrato,
	tbl_recibo_contrato.fecha_ini_vigencia, 
	tbl_recibo_contrato.fecha_fin_vigencia,
	tbl_recibo_contrato.num_recibo_prima,
polizas.nombre_poliza,
	comisionados.nombres, comisionados.apellidos, 
	sum(tbl_caract_recibo_prima.monto_prima)
	from entes, clientes,titulares, tbl_contratos_entes, tbl_recibo_contrato, comisionados, tbl_caract_recibo_prima, primas, polizas, subdivisiones, titulares_subdivisiones 
	where 
	tbl_recibo_contrato.fecha_ini_vigencia between '$fecre1' and '$fecre2' and 
	entes.id_tipo_ente=7 and 
	tbl_caract_recibo_prima.id_titular=titulares.id_titular and
	titulares.id_cliente=clientes.id_cliente and
	entes.id_ente=tbl_contratos_entes.id_ente and tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
	tbl_recibo_contrato.id_comisionado=comisionados.id_comisionado and
	tbl_recibo_contrato.id_recibo_contrato=tbl_caract_recibo_prima.id_recibo_contrato and 
	tbl_caract_recibo_prima.id_prima=primas.id_prima and 
	primas.id_poliza=polizas.id_poliza and
	titulares.id_titular=titulares_subdivisiones.id_titular and
	titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision
	group by
	entes.nombre, clientes.cedula,	titulares.tipocliente, tbl_contratos_entes.numero_contrato, 						tbl_contratos_entes.id_contrato_ente, subdivisiones.subdivision,
	tbl_recibo_contrato.fecha_ini_vigencia, tbl_recibo_contrato.fecha_fin_vigencia,	tbl_recibo_contrato.num_recibo_prima,polizas.nombre_poliza,
	comisionados.nombres, comisionados.apellidos, tbl_recibo_contrato.id_recibo_contrato
	order by tbl_contratos_entes.id_contrato_ente");
$r_venta=ejecutar($q_venta);
*/

$q_venta=("select
	articulocontrato.nombre_articulo, 
	entes.nombre, 
	clientes.cedula,
	(admin.nombres) AS a,
	(admin.apellidos) AS b, 
	clientes.telefono_hab,
	clientes.celular,  
	titulares.tipocliente,
	tbl_contratos_entes.numero_contrato, 
	tbl_contratos_entes.id_contrato_ente,
	tbl_contratos_entes.estado_contrato,
	subdivisiones.subdivision,
	tbl_recibo_contrato.id_recibo_contrato,
	tbl_recibo_contrato.fecha_ini_vigencia, 
	tbl_recibo_contrato.fecha_fin_vigencia,
	tbl_recibo_contrato.num_recibo_prima,
	comisionados.nombres, comisionados.apellidos,
	tbl_contrato_anulado.fecha_creado,
	tbl_contrato_anulado.id_admin, 
	sum(tbl_caract_recibo_prima.monto_prima)
	from entes,articulocontrato,tbl_contrato_anulado, clientes,titulares, tbl_contratos_entes, tbl_recibo_contrato, comisionados, tbl_caract_recibo_prima, primas,  subdivisiones, titulares_subdivisiones,admin 
	where 
	tbl_recibo_contrato.fecha_ini_vigencia between '$fecre1' and '$fecre2'  and 
	entes.id_tipo_ente=7 and 
	tbl_caract_recibo_prima.id_titular=titulares.id_titular and
	titulares.id_cliente=clientes.id_cliente and
	entes.id_ente=tbl_contratos_entes.id_ente and tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
	tbl_recibo_contrato.id_comisionado=comisionados.id_comisionado and
	tbl_recibo_contrato.id_recibo_contrato=tbl_caract_recibo_prima.id_recibo_contrato and 
	tbl_caract_recibo_prima.id_prima=primas.id_prima and 
	titulares.id_titular=titulares_subdivisiones.id_titular and
	titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision and
	tbl_contratos_entes.estado_contrato=0 and 
	articulocontrato.id_articulocon=tbl_contrato_anulado.id_articulocon and
	tbl_contrato_anulado.id_admin=admin.id_admin and
	tbl_contrato_anulado.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato 
	group by
	articulocontrato.nombre_articulo, 
	entes.nombre,a,b,
	clientes.cedula,
	clientes.telefono_hab,
	clientes.celular,
	titulares.tipocliente,
	tbl_contratos_entes.numero_contrato,
	tbl_contratos_entes.id_contrato_ente,
	tbl_contratos_entes.estado_contrato,
	subdivisiones.subdivision,
	tbl_recibo_contrato.fecha_ini_vigencia,
	tbl_recibo_contrato.fecha_fin_vigencia,
	tbl_recibo_contrato.num_recibo_prima,
	comisionados.nombres,
	comisionados.apellidos,
	tbl_recibo_contrato.id_recibo_contrato,
	tbl_contrato_anulado.fecha_creado,
	tbl_contrato_anulado.id_admin 
	order by tbl_contratos_entes.id_contrato_ente");
$r_venta=ejecutar($q_venta);

$contador=0;
	while($f_venta=asignar_a($r_venta)){
    $i++;
	$contador++;
$q_montopagado=("select sum(tbl_recibo_pago.monto) from tbl_recibo_pago where tbl_recibo_pago.id_recibo_contrato=$f_venta[id_recibo_contrato]");
$r_montopagado=ejecutar($q_montopagado);
$f_montopagado=asignar_a($r_montopagado);

/*$q_cuota=("select tbl_recibo_pago.saldo_deudor, tbl_recibo_pago.fecha_proxima_pago, tbl_recibo_pago.fecha_pago from tbl_recibo_pago where tbl_recibo_pago.id_recibo_contrato=$f_venta[id_recibo_contrato] and tbl_recibo_pago.estado_recibo=1 order by tbl_recibo_pago.id_recibo_pago DESC limit 1");
$r_cuota=ejecutar($q_cuota);
$f_cuota=asignar_a($r_cuota);*/

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


$a=$f_venta[sum];
$b=$f_montopagado[sum];
$c=$a-$b;

if($f_venta[tipocliente]=='0'){
$tip="TOMADOR"; $conto++;}
else{
$tip="TITULAR"; $conti++;}

/*if($f_cuota[saldo_deudor]!="0"){
if($fecha > $f_cuota[fecha_proxima_pago]){
$cuota="CUOTA VENCIDA";
}else {$cuota=$f_cuota[fecha_proxima_pago]; }

}else $cuota="  "; */


if($f_venta[estado_contrato]=='0'){
$edo="ANULADO"; }
else{
$edo="ACTIVO"; }




$q_factura=("select tbl_facturas.numero_factura,tbl_series.nomenclatura from tbl_facturas,tbl_series where $f_venta[id_recibo_contrato]=tbl_facturas.id_recibo_contrato and tbl_facturas.id_serie=tbl_series.id_serie and tbl_facturas.id_estado_factura<3 ");
$r_factura=ejecutar($q_factura);
$f_factura=asignar_a($r_factura);

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
		
		
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_venta[id_contrato_ente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_venta[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_venta[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, "58$f_venta[telefono_hab]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, "58$f_venta[celular]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $tip, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_venta[numero_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_factura[nomenclatura], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_factura[numero_factura], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_venta[subdivision], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_venta[fecha_ini_vigencia], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_venta[fecha_fin_vigencia], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, "$f_venta[nombres] $f_venta[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, $f_venta[num_recibo_prima], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, $f_venta[sum]);
$objPHPExcel->getActiveSheet(0)->setCellValue("P".$i, $f_montopagado[sum]);
$objPHPExcel->getActiveSheet(0)->setCellValue("Q".$i, $c);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $planes, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("S".$i, $varrenovo);
$objPHPExcel->getActiveSheet(0)->setCellValue("T".$i, $edo, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("U".$i, "$f_venta[a] $f_venta[b]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("V".$i, $f_venta[nombre_articulo], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("W".$i, $f_venta[fecha_creado], PHPExcel_Cell_DataType::TYPE_STRING);

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
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);


for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("O6:O$i")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("P6:P$i")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("Q6:Q$i")->getNumberFormat()
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
$objPHPExcel->getActiveSheet()->getStyle('A6:W6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Contratos_Anulados');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Contratos_Anulados_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>


