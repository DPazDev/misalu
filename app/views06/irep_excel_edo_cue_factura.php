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

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];

list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
    $serviciosp="and g.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
	}
	else
	{
		$servicios="and gastos_t_b.id_servicio=$servicio";
        $serviciosp="and g.id_servicio=$servicio";
		$servicios1="servicios.id_servicio=$servicio";
		}
if ($forma_pago==0){
	$tipo_pago="and tbl_facturas.id_estado_factura>=0";
	}
if ($forma_pago=='*'){
	$tipo_pago="and tbl_facturas.id_estado_factura>=1 and tbl_facturas.id_estado_factura<=2";
	}
	if ($forma_pago>0){
	$tipo_pago="and tbl_facturas.id_estado_factura=$forma_pago";
	}
if ($ente==0)
{
	$elente="and entes.id_ente>=$ente";
    $elentep="e.id_ente>=$ente and";
	}
	else
	{
		$elente="and entes.id_ente=$ente";
        $elentep="e.id_ente=$ente and";
		}
                  if ($ente=='*')
{
    $elente="and entes.id_ente!=53";

    }
if ($forma_pago==1)
{
	$descripcion="Pagadas";
	}
	
if ($forma_pago==2)
{
	$descripcion="Por Cobrar";
	}
	
	
if ($forma_pago==3)
{
	$descripcion="Anuladas";
	}
	if ($forma_pago=='*')
{
	$descripcion="Por Cobrar y Pagadas";
	}
    
    $r_ente=pg_query("select  * from entes where id_ente=$ente");
($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC));

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




		if ($sucursal==0)
{
	$id_serie="and tbl_facturas.id_serie>0";
    $id_seriep="and ts.id_serie>0";
	$serie="Todas Las Series";
	}
	else
	{
	$id_serie="and tbl_facturas.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	$serie=$f_serie[nomenclatura];
		}
        
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Edo. Cuenta factura por Entes y Series")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Edo. Cuenta factura por Entes y Series")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$i=9;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, '')
                    ->setCellValue("B".$i, 'Serie')
                    ->setCellValue("C".$i, 'Empresa')
                    ->setCellValue("D".$i, 'Monto');	
                    

  if ($forma_pago==4){
      /*$r_factura=pg_query("select 
                                                    p.id_proceso,
                                                    g.fecha_cita,
                                                    count(g.id_proceso)
                                        from 
                                                    procesos p,
                                                    titulares t,
                                                    entes e,
                                                    gastos_t_b g,
                                                    admin a,
                                                    tbl_series ts
                                        where 
                                        p.id_proceso=g.id_proceso 
                                        $serviciosp and
                                        g.fecha_cita>='$fechaini' and 
                                        g.fecha_cita<='$fechafin' and 
                                        p.id_titular=t.id_titular and 
                                        t.id_ente=e.id_ente and 
                                       $elentep
                                        e.id_tipo_ente=$tipo_ente  and 
                                        p.id_admin=a.id_admin and
                                        a.id_sucursal=ts.id_sucursal
                                        $id_seriep and
                                        not exists
                                                    (select
                                                                * 
                                                    from 
                                                                tbl_procesos_claves t
                                                    where  
                                                                p.id_proceso=t.id_proceso)  
                                                    group by  
                                                                p.id_proceso,
                                                               g.fecha_cita");
     */
     }
     else
     {
 $r_factura=pg_query("select 
                                            entes.nombre,
                                            tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_estado_factura,
                                            sum(tbl_procesos_claves.monto) 
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
                                            entes.id_tipo_ente=$tipo_ente and
                                            tbl_facturas.id_estado_factura=$forma_pago  
                                    group by 
                                            entes.nombre,
                                            tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
                                       		tbl_facturas.id_estado_factura
                                    order by 
                                            tbl_series.nomenclatura");

 }
 
 

while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
    	if ($f_factura[id_estado_factura]==3)
			{
				$montofactura=0;
			}
			else
			{
				$montofactura=$f_factura[sum];
			}
	
    $totalmontpag1=$totalmontpag1 + $montofactura;
    $i++;
	
	
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, "", PHPExcel_Cell_DataType::TYPE_STRING);

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, $f_factura[nomenclatura] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_factura[nombre], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, $montofactura);              

}
$j=$i;
$i++;
$k=$j+1;
pg_free_result($r_factura);

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, 'total', PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i,   "=SUM(D10:D$j)");                    		            				

//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("D10:D$k")->getNumberFormat()
->setFormatCode('#,##0.00');
// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../public/images/head.png');
$objDrawing->setHeight(70);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// Set style for header row using alternative method

$objPHPExcel->getActiveSheet()->getStyle('A9:D9')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Edo_Cuenta_factura');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Edo_Cuenta_factura_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>
