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



/* Nombre del Archivo: excel_proceso_usuario.php
  Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación Procesos realizados por los Usuarios */

list($id_usuario,$usuario)=explode("@",$_REQUEST['usuario']);
if($id_usuario==0){	        $condicion_usuario="and procesos.id_admin>0 and 
	admin.id_admin=procesos.id_admin";
				$var_usuario="procesos.id_admin, admin.nombres, admin.apellidos,";}



else if($id_usuario=="-01"){	        $condicion_usuario=" and admin.id_admin=procesos.id_admin";
				$var_usuario="";}
else{
$condicion_usuario="and procesos.id_admin=$id_usuario and 
	admin.id_admin=procesos.id_admin";
$var_usuario="procesos.id_admin, admin.nombres, admin.apellidos,";}


	$fecre1=$_REQUEST['fecha1'];
	$fecre2=$_REQUEST['fecha2'];

list($id_servicio,$servicio)=explode("@",$_REQUEST['servic']);
	if($id_servicio==0){	        
	$condicion_servicio="and gastos_t_b.id_servicio>0";
	$agrupar=",count(procesos.id_proceso)";
	$planilla="procesos.id_proceso";
	$pla="procesos.nu_planilla," ;}

	else if($id_servicio=="-01"){
	$condicion_servicio="and (gastos_t_b.id_servicio!=6 and gastos_t_b.id_servicio!=9 ) ";
	$agrupar=",count(procesos.id_proceso)";
	$planilla="procesos.id_proceso";}

	else if($id_servicio=="-02"){
	$condicion_servicio="and (gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9 ) ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}

	else if($id_servicio=="6"){
	$condicion_servicio="and gastos_t_b.id_servicio=6 ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}

	else if($id_servicio=="9"){
	$condicion_servicio="and gastos_t_b.id_servicio=9 ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}
	else{
	$condicion_servicio="and gastos_t_b.id_servicio='$id_servicio'";
	$agrupar=",count(procesos.id_proceso)";
	$planilla="procesos.id_proceso";}



list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="and admin.id_sucursal>0";
else
$condicion_sucursal="and admin.id_sucursal='$id_sucursal'";

/*

echo $id_usuario."**";
echo $usuario."BBBBBB";
echo $fecre1."MMMMMM";
echo $fecre2."RRRRRr";
echo $servicio."------";
echo $id_servicio."TRTRTR";*/



// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setTitle("Procesos por Usuarios ")
                                                         ->setSubject("Office 2007 XLSX Test Document")
                                                         ->setDescription("Reporte que muestra los Procesos realizados por un Usuario Especifico")
                                                         ->setKeywords("office 2007 openxml php")
                                                         ->setCategory("Test result file");
/*$i=3;*/
$objPHPExcel->getActiveSheet(0)->mergeCells('A3:I3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Procesos por Usuarios,realizados en $servicio, en la Sucursal $sucursal, del $fecre1 al $fecre2");


$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'CANT.');
                
if($id_servicio=="-02" or $id_servicio=="6" or $id_servicio=="9")
         $objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'PLANILLA');
else               $objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'ORDEN');

                    $objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'FECHA');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'TITULAR');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'CEDULA TITULAR');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'BENEFICIARIO');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'CEDULA BENEFICIARIO');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'ENTE');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'ESTADO PROCESO');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, 'MONTO');
                    $objPHPExcel->getActiveSheet(0)->setCellValue("K".$i, 'ANALISTA'); 
                    $objPHPExcel->getActiveSheet(0)->setCellValue("L".$i, 'ESPECIALIDAD'); 
                    $objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, 'DOCTOR'); 
                    $objPHPExcel->getActiveSheet(0)->setCellValue("N".$i, 'REFERENCIA DOCTOR'); 
                    $objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, 'CITA'); 
                    $objPHPExcel->getActiveSheet(0)->setCellValue("P".$i, 'ESTADO FACTURA');
		    $objPHPExcel->getActiveSheet(0)->setCellValue("Q".$i, 'NUM. FACTURA'); 
		    $objPHPExcel->getActiveSheet(0)->setCellValue("R".$i, 'SERIE');
		    $objPHPExcel->getActiveSheet(0)->setCellValue("S".$i, 'PLANILLA');
 		
 
$q_proceso=("select  $planilla, $pla
	procesos.id_titular,  
	procesos.id_beneficiario, 
	procesos.fecha_recibido, 
$var_usuario
	procesos.id_estado_proceso, 
	gastos_t_b.id_servicio, 
	titulares.id_ente, 
	gastos_t_b.id_proveedor, 
	(gastos_t_b.nombre) AS nom, 
	gastos_t_b.fecha_cita,
	gastos_t_b.id_proveedor_ref,  
	clientes.id_cliente, 
	clientes.sexo, 
	(clientes.nombres) AS n, 
	(clientes.apellidos) AS a, 
	clientes.cedula, 
	clientes.fecha_nacimiento, 
	entes.nombre, 
	estados_procesos.estado_proceso $agrupar
	from procesos,gastos_t_b,admin,titulares,clientes,entes,estados_procesos 
	where 
	procesos.fecha_recibido between '$fecre1' and '$fecre2' and 
	gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso>0 and 
	(procesos.id_beneficiario>0 or procesos.id_beneficiario=0)  $condicion_servicio $condicion_sucursal and 
	procesos.id_titular=titulares.id_titular and 
	entes.id_tipo_ente>0 $condicion_usuario and 
	titulares.id_ente=entes.id_ente and 
	gastos_t_b.id_proveedor>=0 and 
	titulares.id_cliente=clientes.id_cliente and
	procesos.id_estado_proceso=estados_procesos.id_estado_proceso
	group by 
	$planilla,$pla $var_usuario procesos.id_titular, procesos.id_beneficiario, procesos.fecha_recibido,  procesos.id_estado_proceso, gastos_t_b.id_servicio,	nom,  titulares.id_ente, gastos_t_b.id_proveedor, 
	gastos_t_b.fecha_cita, gastos_t_b.id_proveedor_ref, clientes.id_cliente, clientes.sexo, n, a, clientes.cedula, clientes.fecha_nacimiento, entes.nombre, estados_procesos.estado_proceso
 ORDER BY $planilla DESC ");
	$r_proceso=ejecutar($q_proceso);






	$contador=0;
	     while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC))
		{
	$i++;
	$contador++;
$estado_fact="";
$qpropersona=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov, proveedores.tipo_proveedor from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor='$f_proceso[id_proveedor]' and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor");
$rpropersona=ejecutar($qpropersona);
$dataproper=asignar_a($rpropersona);
$fpropersona="$dataproper[nombres_prov] $dataproper[apellidos_prov]";
$tipo_provper="$dataproper[tipo_proveedor]";

$qproclinica=("select clinicas_proveedores.nombre, proveedores.tipo_proveedor from clinicas_proveedores,proveedores where proveedores.id_proveedor='$f_proceso[id_proveedor]' and clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor");
$rproclinica=ejecutar($qproclinica);
$dataprocli=asignar_a($rproclinica);
$fproclinica="$dataprocli[nombre]";
$tipo_provcli="$dataprocli[tipo_proveedor]";


if($f_proceso[id_proveedor_ref]>0){

$qpro_ref=("select personas_proveedores.nombres_prov,personas_proveedores.apellidos_prov, proveedores.tipo_proveedor from personas_proveedores,s_p_proveedores,proveedores where proveedores.id_proveedor='$f_proceso[id_proveedor_ref]' and
s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and 
personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor");
$rpro_ref=ejecutar($qpro_ref);
$datapro_ref=asignar_a($rpro_ref);
$fpro_ref="$datapro_ref[nombres_prov] $datapro_ref[apellidos_prov]";
$tipo_provper="$dataproper[tipo_proveedor]";
}
else
$fpro_ref="DOCTORES EXTRAMURALES";
// BUSQUEDA DE PROCESOS FACTURADOS  






if($id_usuario=="-01"){ $var_usua="";}

else { $var_usua="and procesos.id_admin='$f_proceso[id_admin]'";}

if($planilla=="procesos.nu_planilla"){

$q_monto=("select gastos_t_b.monto_aceptado from gastos_t_b, procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.nu_planilla='$f_proceso[nu_planilla]' $var_usua "); 

}




if($planilla=="procesos.id_proceso"){
$q_monto=("select gastos_t_b.monto_aceptado from gastos_t_b, procesos where gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$f_proceso[id_proceso]'");}


$plani=$f_proceso[nu_planilla];

$r_monto=ejecutar($q_monto);
$suma=0;

	     while($f_monto=asignar_a($r_monto,NULL,PGSQL_ASSOC)){
$suma=$suma + $f_monto[monto_aceptado];

$q_facturas=("select
                                tbl_facturas.id_factura,
                                tbl_facturas.numero_factura,
                                tbl_facturas.fecha_emision,	
                                tbl_series.nomenclatura,
				tbl_facturas.id_estado_factura
                            from
                                tbl_facturas,
                                tbl_series,
                                tbl_procesos_claves
                            where
                                tbl_facturas.id_factura=tbl_procesos_claves.id_factura and
                                tbl_facturas.id_serie=tbl_series.id_serie and
                                tbl_procesos_claves.id_proceso='$f_proceso[id_proceso]' ");

$r_facturas=ejecutar($q_facturas);

$f_facturas=asignar_a($r_facturas);

}

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);


$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_proceso[nu_planilla] $f_proceso[id_proceso]", PHPExcel_Cell_DataType::TYPE_STRING);


$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_proceso[fecha_recibido], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, "$f_proceso[n] $f_proceso[a]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_proceso[cedula], PHPExcel_Cell_DataType::TYPE_STRING);

if ($f_proceso[id_beneficiario]>0){
		$q_benf=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.id_cliente,clientes.fecha_nacimiento,clientes.sexo from 				clientes,beneficiarios where beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]' and 					beneficiarios.id_cliente=clientes.id_cliente");
		$r_benf=ejecutar($q_benf);
		$f_benf=asignar_a($r_benf);




$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, "$f_benf[nombres] $f_benf[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_benf[cedula], PHPExcel_Cell_DataType::TYPE_STRING);

}

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_proceso[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_proceso[estado_proceso], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, $suma, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, "$f_proceso[nombres] $f_proceso[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_proceso[nom] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, "$fpropersona $fproclinica" , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, "$fpro_ref" , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$i, $f_proceso[fecha_cita] , PHPExcel_Cell_DataType::TYPE_STRING);


if($f_facturas[id_estado_factura]=='1'){
	$estado_fact="PAGADO";
	}
 	else
if($f_facturas[id_estado_factura]=='2'){
	$estado_fact="POR COBRAR";
	}

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $estado_fact , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$i, $f_facturas[numero_factura] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_facturas[nomenclatura] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $plani , PHPExcel_Cell_DataType::TYPE_STRING);
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
$objPHPExcel->getActiveSheet()->getStyle('A6:T6')->applyFromArray(
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
// finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Procesos_Usuarios');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=procesos_usuarios_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>






