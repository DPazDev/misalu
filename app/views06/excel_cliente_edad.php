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
/* Nombre del Archivo: excel_cliente_edad.php
   Descripción: Realiza el Reporte de Impresión en excel con los datos seleccionados: Relación de Clientes entre A y B años, de un Ente en Particular.
*/ 

  
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);


if($id_ente==0)	        $condicion_ente="and entes.id_tipo_ente>0";
	else
	$condicion_ente="and entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}

list($estado,$estado_cliente)=explode("@",$_REQUEST['estado']);
	if($estado==0)	        $condicion_estado="";
	else
   $condicion_estado="and estados_t_b.id_estado_cliente=$estado";
   $inicio=$_REQUEST['inicio'];
   $fin=$_REQUEST['fin'];
   $tipcliente=$_REQUEST['tipcliente'];


$q_titular=("select clientes.apellidos,clientes.nombres,titulares.tipocliente,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,
titulares.id_titular,titulares.id_cliente,estados_clientes.estado_cliente,entes.id_tipo_ente, estados_t_b.id_estado_cliente,entes.nombre from entes,clientes,titulares,estados_t_b,estados_clientes  where clientes.id_cliente=titulares.id_cliente  $condicion_ente  $tipo_entes and titulares.id_ente=entes.id_ente and  estados_t_b.id_titular=titulares.id_titular $condicion_estado and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and estados_t_b.id_beneficiario='0' group by clientes.apellidos,clientes.nombres,titulares.tipocliente,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,
titulares.id_titular,titulares.id_cliente,estados_clientes.estado_cliente,estados_t_b.id_estado_cliente,entes.nombre,entes.id_tipo_ente order by estados_clientes.estado_cliente,entes.nombre,clientes.fecha_nacimiento");
$r_titular=ejecutar($q_titular);


$q_beneficiario=("select clientes.apellidos,clientes.nombres,clientes.cedula,clientes.fecha_nacimiento,clientes.sexo,parentesco.parentesco,beneficiarios.id_titular,beneficiarios.id_beneficiario,estados_clientes.estado_cliente,entes.id_tipo_ente, estados_t_b.id_estado_cliente,entes.nombre from entes,clientes,parentesco,beneficiarios,estados_t_b,estados_clientes,titulares where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente $condicion_ente $tipo_entes and beneficiarios.id_parentesco=parentesco.id_parentesco and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario $condicion_estado and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente order by estados_clientes.estado_cliente,entes.nombre,clientes.fecha_nacimiento,parentesco.parentesco");
$r_beneficiario=ejecutar($q_beneficiario);



// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setTitle("Clientes por Edad ")
                                                         ->setSubject("Office 2007 XLSX Test Document")
                                                         ->setDescription("Reporte Clientes en un rango de edades, de un determinado Ente ")
                                                         ->setKeywords("office 2007 openxml php")
                                                         ->setCategory("Test result file");
   



/*$i=3;*/
$objPHPExcel->getActiveSheet(0)->mergeCells('A3:I3');
$objPHPExcel->getActiveSheet()->setCellValue('A3',"Reporte Clientes por Edades, $tipcliente,en estado $estado_cliente, entre $inicio y $fin años,del Ente $ente  ");




$i=6;//variable para dar la posicion de la celda;

if($tipcliente=='TITULARES' or $tipcliente=='BENEFICIARIOS' or $tipcliente=='TODOS LOS CLIENTES'){

$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'CLIENTE');
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'NOMBRES');
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'APELLIDOS');
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'CEDULA');
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'ESTADO');
$objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'FECHA DE NACIMIENTO');
$objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'GENERO');
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'EDAD');
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'ENTE');


 if($tipcliente=='BENEFICIARIOS' or $tipcliente=='TODOS LOS CLIENTES'){
	  
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, 'PARENTESCO');
    

}}
	     $t=0;$b=0;
		 
	     while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC))
		{

if  (calcular_edad($f_titular['fecha_nacimiento'])>=$inicio &&  calcular_edad($f_titular['fecha_nacimiento'])<=$fin) { 
if($tipcliente=='TITULARES' or $tipcliente=='TODOS LOS CLIENTES'){
if($f_titular['tipocliente']!='0'){
if($f_titular['sexo']==1){
	$sexot="MASCULINO";
	}
 	else 
	{
	$sexot="FEMENINO";
	}
	$i++;
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_titular[id_titular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_titular[nombres], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titular[apellidos], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, calcular_edad($f_titular['fecha_nacimiento']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_titular[nombre], PHPExcel_Cell_DataType::TYPE_STRING);


	$t++;}
		}
		}}

     while($f_beneficiario=asignar_a($r_beneficiario,NULL,PGSQL_ASSOC))
		{

if  (calcular_edad($f_beneficiario['fecha_nacimiento'])>=$inicio &&  calcular_edad($f_beneficiario['fecha_nacimiento'])<=$fin) {
if($tipcliente=='BENEFICIARIOS' && $f_beneficiario['id_beneficiario']>0 or $tipcliente=='TODOS LOS CLIENTES' or $f_titular['id_titular']>0){


if($f_beneficiario['sexo']==1){
	$sexob="MASCULINO";
	}
 	else 
	{
	$sexob="FEMENINO";
	}
	$i++;

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_beneficiario[id_beneficiario], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_beneficiario[nombres], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_beneficiario[apellidos], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_beneficiario[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_beneficiario[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_beneficiario[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $sexob, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, calcular_edad($f_beneficiario['fecha_nacimiento']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_beneficiario[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_beneficiario[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);


		$b++;}
		}
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

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}


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
$objPHPExcel->getActiveSheet()->getStyle('A6:M6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('EXCEL_CLIENTES_POR_EDADES');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=clientes_por_edades_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>
