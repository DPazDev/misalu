<?php
include ("../../lib/jfunciones.php");
sesion();

/* PROPIEDADES PARA ARMAR EL ARCHIVO EXCEL */

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
$mesf=$_REQUEST['formes'];
$anof=$_REQUEST['forano'];
$seri=$_REQUEST['forsucursal'];
$estafact=$_REQUEST['forestfact'];
$primerdia = "01";
$ultdia = date("d",(mktime(0,0,0,$mesf+1,1,$anof)-1));

$fechainicio = "$anof-$mesf-$primerdia";
$fechafinal =  "$anof-$mesf-$ultdia";

// ESTILOS PARA LA CABECERA


/*SERIE*/
        if ($seri==0)
{
    $id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
    $serie="TODAS LAS SERIES";
    }
    else
    {
    $id_serie="and tbl_series.id_serie=$seri";
    $id_seriep="and ts.id_serie=$seri";
    $q_serie=("select  * from tbl_series where id_serie=$seri");
    $r_serie=ejecutar($q_serie);
    $f_serie=asignar_a($r_serie);
    $serie="SERIE ".$f_serie[nomenclatura];
        }

/*FACTURA*/
if($estafact == 0){
 $elestafc ="";
}else{
  $elestafc ="and id_estado_factura=$estafact"; 
 }

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
                             ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
                             ->setTitle("Libro de venta detallado")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setCategory("Test result file");


$i=4;//variable para dar la posicion de la celda;
$r=4;
$objPHPExcel->getActiveSheet(0)
                    ->setCellValue("A".$i, 'SERIE')
                    ->setCellValue("B".$i, 'N° FACTURA')
                    ->setCellValue("C".$i, 'N° CONTROL')
                    ->setCellValue("D".$i, 'ESTADO FACTURA')
                    ->setCellValue("E".$i, 'FECHA DE EMISIÓN')
                    ->setCellValue("F".$i, 'ENTE')
                    ->setCellValue("G".$i, 'TITULAR')
                    ->setCellValue("H".$i, 'MONTO SERVICIOS')
                    ->setCellValue("I".$i, 'MONTO C. TERCEROS')
                    ->setCellValue("J".$i, 'MONTO TOTAL EXENTO')
                    ->setCellValue("K".$i, 'PORCENTAJE DE IGTF')
                    ->setCellValue("L".$i, 'MONTO TOTAL')
                    ->setCellValue("M".$i, 'N° NOTA CRÉDITO')
                    ->setCellValue("N".$i, 'MONTO NOTA CRÉDITO')
                    ->setCellValue("O".$i, 'N° NOTA DÉDITO')
                    ->setCellValue("P".$i, 'MONTO NOTA DÉDITO')
                    ->setCellValue("Q".$i, 'MONTO TOTAL')
           ;
     
                      


$buscfactu = ("select tbl_facturas.id_factura,
                      tbl_facturas.fecha_emision,
                      tbl_facturas.numero_factura,
                      tbl_facturas.numcontrol,
                      tbl_facturas.id_serie,
                      tbl_facturas.id_estado_factura,
                      sum(tbl_procesos_claves.monto),
                      sum(tbl_procesos_claves.fac_deducible) as sum_deducible,
                      tbl_series.nomenclatura,
                      clientes.nombres,
                      clientes.apellidos,
                      entes.nombre
                      
                 from tbl_facturas,
                      tbl_procesos_claves,
                      procesos,
                      titulares,
                      entes,
                      clientes,
                      tbl_series 

                where tbl_facturas.id_factura=tbl_procesos_claves.id_factura
                  and tbl_facturas.id_serie=tbl_series.id_serie
                      $id_serie 
                  and titulares.id_cliente=clientes.id_cliente  
                  and fecha_emision>='$fechainicio' 
                  and fecha_emision<='$fechafinal'
                      $elestafc
                  and tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                      procesos.id_titular=titulares.id_titular 
                  and titulares.id_ente=entes.id_ente 
                  and entes.id_ente>=0 

             group by tbl_facturas.numero_factura,
                      tbl_facturas.id_factura,
                      tbl_series.nomenclatura,
                      tbl_facturas.fecha_emision,
                      entes.nombre,
                      clientes.nombres,
                      clientes.apellidos

             order by tbl_series.nomenclatura,
                      tbl_facturas.numero_factura,
                      tbl_facturas.id_factura");
                                  
$r_dfacturas=ejecutar($buscfactu);


$k=1;
$contaNota=0;
////DEFINIR FORMATOS DE CELDA DE NOTAS Facturas
$styleArrayCredito = array('font'=>array('bold'=> true,'color' => array('rgb' => 'ce181e'),'size'=> 10,'name'=>'Arial'));
$styleArrayDebito = array('font'=>array('bold'=> true,'color' => array('rgb' => '063388'),'size'=> 10,'name'=>'Arial'));

while($lasfacturas=pg_fetch_array($r_dfacturas, NULL, PGSQL_ASSOC)) 
{
    
    switch($lasfacturas[id_estado_factura]){
        
        case 1:
         $estado_factura="Pagadas";
        break;

        case 2:
         $estado_factura="Por Cobrar";
        break;

        case 3:
         $estado_factura="Anuladas";
         break;
    }



/*MONTO TOTAL SERVICIOS-MONTO NO APROBADO-MONTO DEDUCIBLE*/

/*CUENTAS A TERCEROS MONTOS RECERVAS*/
$q_reserva=("select SUM(CAST(gastos_t_b.monto_reserva AS DOUBLE PRECISION)) as reserva
               from gastos_t_b,
                    tbl_procesos_claves,
                    procesos 
              where tbl_procesos_claves.id_proceso=procesos.id_proceso 
                and tbl_procesos_claves.id_factura=$lasfacturas[id_factura] 
                and gastos_t_b.id_proceso=procesos.id_proceso;");
$r_reserva=ejecutar($q_reserva);
$bmontoReserva=asignar_a($r_reserva);
$montoReserva=$bmontoReserva['reserva'];


/*CALCULO MONTO NO APROBADO*/
  if($bmontoReserva[id_tipo_servicio]==27){
    if ($cuantosnap=num_filas($montoReserva)>0)
            {
             $noaprobado=$montoReserva;
            
            }
    else
            {   
            $noaprobado=0;
                        
            }

  
}

/*CALCULO MONTO TOTAL EXENTO*/
  if ($lasfacturas[id_estado_factura]==3)
            {
                $montoFactura=0;
            }
    else
            {
              $montoFactura=($lasfacturas[sum] - $lasfacturas[sum_deducible] - $noaprobado ) ;
            }

/*CALCULO MONTO SERVICIOS*/
$montoserv= $montoFactura - $montoReserva;

/*CALCULO IGTF*/
$b_IGTF= ("select * from variables_globales where  nombre_var='IGTF' "); 
$t_IGTF=ejecutar($b_IGTF);
$q_IGTF=asignar_a($t_IGTF);
$IGTF= $q_IGTF['cantidad'];
$porcientoIGTF= $q_IGTF['comprasconfig'].' %';

 $facturaigtf= ("select sum(tbl_oper_multi.monto)  from tbl_oper_multi where id_factura=$lasfacturas[id_factura] and id_moneda>1;");
     $monto_facturaigtf = ejecutar($facturaigtf);

     $cont_igtf=num_filas($monto_facturaigtf);

     if($cont_igtf>0){
          $monto_total_igtf   = asignar_a($monto_facturaigtf);
          $montoIgtf = $monto_total_igtf[sum] * $IGTF;
     }

 /*MONTO TOTAL A PAGAR*/    
$Montotalf=$montoIgtf+$montoFactura;

     /* busco si posee una nota de credito*/
    $q_Notas="select * from tbl_nota_factura where tbl_nota_factura.id_factura=$lasfacturas[id_factura]";
    $r_notafact=ejecutar($q_Notas);
    $NotaC='';
    $motoNotaC=0;
    $motoNotaD=0;
    $NotaD='';
    $cuantaNotas=num_filas($r_notafact);
    if($cuantaNotas>0)
    {
      ///ACTIVAR NUEVA PAGINA NOTAS
      if($k==1){

        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objPHPExcel->getActiveSheet(1)->setTitle('Notas_Facturas');
        $objPHPExcel->getActiveSheet(1)->setCellValue("A".$k, 'NUM')
                            ->setCellValue("B".$k, 'FACTURA')
                            ->setCellValue("C".$k, 'SERIE')
                            ->setCellValue("D".$k, 'TIPO NOTA')
                            ->setCellValue("E".$k, 'N° NOTA')
                            ->setCellValue("F".$k, 'CONTROL NOTA')
                                      ->setCellValue("G".$k, 'FECHA EMISION')
                            ->setCellValue("H".$k, 'MONTO')
                            ->setCellValue("I".$k, 'CONCEPTO');
        $k++;

      }else{
        $objPHPExcel->setActiveSheetIndex(1);
      }

     while($NotaFac=pg_fetch_array($r_notafact, NULL, PGSQL_ASSOC)){
       $contaNota++;
        $tipoNota=$NotaFac['tipo_nota'];
        $serieFact=$f_factura['nomenclatura'];
        ////FORMATO NUM FACTURA/NOTA Factura
        $NumNotaFactura=(int) $NotaFac['num_nota'];///Trasformar a entero
        $NumNotaFactura=str_pad($NumNotaFactura, 7, '0', STR_PAD_LEFT);
        ////FORMATO NUM CUNTRL
        $ControlNota=(int) $NotaFac['numcontrolnota'];///Trasformar a entero
        $ControlNota=str_pad($ControlNota, 7, '0', STR_PAD_LEFT);
        ////FORMATO NUM FACTURA
        $NumFactura=(int) $lasfacturas['numero_factura'];///Trasformar a entero
        $NumFactura=str_pad($NumFactura, 7, '0', STR_PAD_LEFT);
        ///FECHA DE EMISION DE NOTA
        $FecEmisionNota=$NotaFac['fecha_emision'];
        ///MONTO FACTURA
        $montoNota=$NotaFac['monto_nota'];
        $concepto=$NotaFac['concepto'];

        if($tipoNota==1)
        {//Nota Credito
          $TipoNota='CRÉDITO';
          $motoNotaC=$motoNotaC+$montoNota;
          $NotaC=$NotaC."[$NumNotaFactura]";
          $objPHPExcel->getActiveSheet(1)->getStyle("A$k:I$k")->applyFromArray($styleArrayCredito);
        }else{
          //Nota DEBITO
          $TipoNota='DÉBITO';
          $motoNotaD=$motoNotaD+$montoNota;
          $NotaD=$NotaD."[$NumNotaFactura]";
          $objPHPExcel->getActiveSheet(1)->getStyle("A$k:I$k")->applyFromArray($styleArrayDebito);
        }
              ///INSERTAR DATA DE NOTAS HOJA 2
              $objPHPExcel->getActiveSheet(1)->setCellValue("A".$k, $contaNota);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("B".$k, $NumFactura, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("C".$k, $serieFact, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("D".$k, $TipoNota, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("E".$k, $NumNotaFactura, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("F".$k, $ControlNota, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("G".$k, $FecEmisionNota, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(1)->setCellValue("H".$k,$montoNota);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("I".$k, $concepto, PHPExcel_Cell_DataType::TYPE_STRING);

          $k++;
        }


    }
  $r++;  
        $objPHPExcel->setActiveSheetIndex(0); 
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$r, $lasfacturas[nomenclatura], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$r, $lasfacturas[numero_factura], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$r, $lasfacturas[numcontrol], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$r, $estado_factura, PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$r, $lasfacturas[fecha_emision], PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$r, $lasfacturas[nombre] , PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$r, $lasfacturas[nombres].   $lasfacturas[apellidos] , PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$r, montos_print($montoserv), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$r, montos_print($montoReserva), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$r, montos_print($montoFactura), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$r, montos_print($montoIgtf), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$r, montos_print($Montotalf), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$r, $NotaC, PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$r, montos_print($motoNotaC), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$r, $NotaD, PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$r, montos_print($motoNotaD), PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet(0)->setCellValue("Q".$r,"=(L$r-N$r)+P$r");
      
  
}

//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
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

$objPHPExcel->getActiveSheet()->getStyle('A1:S4')->applyFromArray( array( 'font'    => array( 'italic'      => true, 'bold'      => true) ) );



// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=libro_venta_detallado$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 


?>