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

/* Nombre del Archivo: reporte_parientes_x_estado.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación de los Parientes, de un determinado Ente
*/ 


   list($estado,$estado_cliente)=explode("@",$_REQUEST['estado']);
	if($estado==0)	        $condicion_estado="";
	else
   $condicion_estado="and estados_t_b.id_estado_cliente=$estado";

   list($subdivi)=explode("@",$_REQUEST['subdivi']);
	if($subdivi==0)	        $condicion_subdivi="";
	else
   $condicion_subdivi="and titulares_subdivisiones.id_subdivision=$subdivi";

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

	$signo=$_REQUEST['signo'];
	$cantidad=$_REQUEST['cantidad'];
	$nu_parentesco = $_REQUEST['nu_parentesco'];
	$valor1 = $_REQUEST['valor1'];
	$valor2=split("@",$valor1);

/*echo $estado."ee";
echo $subdivi."ss";
echo $ente."nn";
echo $signo."**";
echo $cantidad."--";
echo $nu_parentesco."////";
echo $valor1;*/


// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
                                                         ->setTitle("Parientes por Entes ")
                                                         ->setSubject("Office 2007 XLSX Test Document")
                                                         ->setDescription("Reporte que muestra los Parientes de un determinado Ente")
                                                         ->setKeywords("office 2007 openxml php")
                                                         ->setCategory("Test result file");
/*$i=3;*/
$objPHPExcel->getActiveSheet(0)->mergeCells('A3:B3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Parientes por Entes");

$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'BENEFICIARIOS');
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'CEDULA BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'PARENTESCO');
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'SEXO');
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'FECHA NAC.');
$objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'EDAD');
$objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'ESTADO');
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'COMENTARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'ENTE');

$apun=0;
for($k=0;$k<=$nu_parentesco;$k++){//este FOR lee el vector con la informacion de la cantidad de parentesco asignados, para luego realizar la busqueda  
    $valor=$valor2[$k];
    if($valor>0){
       if($apun==0){
         $cadenaparen="beneficiarios.id_parentesco=$valor"; 
       }else{
              $cadenaparen="$cadenaparen or beneficiarios.id_parentesco=$valor";//para realizar una cadena de busquedas con and y or y todas sean en el mismo tiempo
            }    
      $apun++;
    }
}
$q_parentesco=("select clientes.nombres, clientes.apellidos, clientes.cedula, clientes.sexo, clientes.fecha_nacimiento,clientes.edad,parentesco.parentesco,estados_clientes.estado_cliente,entes.nombre,clientes.comentarios 
from beneficiarios,titulares,clientes,estados_t_b,parentesco,estados_clientes,entes 
where 
($cadenaparen) and 
entes.id_ente=titulares.id_ente $condicion_ente $tipo_entes and 
titulares.id_titular=beneficiarios.id_titular and 
beneficiarios.id_cliente=clientes.id_cliente and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario $condicion_estado and 
parentesco.id_parentesco=beneficiarios.id_parentesco and
estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente
order by parentesco.parentesco, clientes.fecha_nacimiento");
/*echo $q_parentesco;*/

$r_parentesco=ejecutar($q_parentesco);
$cont=0;
	     while($f_parentesco=asignar_a($r_parentesco,NULL,PGSQL_ASSOC)){

               $edadusuario=calcular_edad($f_parentesco['fecha_nacimiento']); 
if($f_parentesco['sexo']==1){
	$sexo="MASCULINO";
	}
 	else 
	{
	$sexo="FEMENINO";
	}
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

if($signo=="<"){
	if($edadusuario<$cantidad){
$i++;

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, "$f_parentesco[nombres] $f_parentesco[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_parentesco[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_parentesco[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $sexo, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_parentesco[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $edadusuario, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_parentesco[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_parentesco[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_parentesco[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
		    $cont++; } }

if($signo=="="){
	if($edadusuario==$cantidad){
$i++;
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, "$f_parentesco[nombres] $f_parentesco[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_parentesco[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_parentesco[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $sexo, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_parentesco[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $edadusuario, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_parentesco[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_parentesco[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_parentesco[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
	    $cont++; } }

if($signo==">"){
	if($edadusuario>$cantidad){
$i++;
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, "$f_parentesco[nombres] $f_parentesco[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_parentesco[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_parentesco[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $sexo, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $f_parentesco[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $edadusuario, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $f_parentesco[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_parentesco[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, $f_parentesco[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
	    $cont++; } }

 }

$j=$i;
$i++;
//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

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
$objPHPExcel->getActiveSheet()->setTitle('Parientes por Entes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=parientes_por_entes_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 







?>







