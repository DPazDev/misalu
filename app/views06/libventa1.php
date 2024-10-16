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
$sheet = $objPHPExcel->getActiveSheet();

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

if($seri == 0){
  $lasserie="";
  $campserie="id_serie,";
  
}else{
  $lasserie="id_serie=$seri and";   
  $campserie="";
    }

if($estafact == 0){
 $elestafc ="";
}else{
  $elestafc ="and id_estado_factura=$estafact"; 
 }
$buscfactu = ("select tbl_facturas.id_factura,tbl_facturas.fecha_emision,tbl_facturas.numero_factura,
                                   tbl_facturas.numcontrol, tbl_facturas.id_serie, 
                                   $campserie date_part('day',tbl_facturas.fecha_emision) as dia
                                   from tbl_facturas where $lasserie fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal'
                                   $elestafc 
                                   order by fecha_emision,$campserie id_factura;");
                                  
$r_dfacturas=ejecutar($buscfactu);
$apunta1='';
$distintos='';
$apunta1s='';
//****************
// propiedades para documentar el archivo o reporte de excel

$r=3;
$i=4;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'OPER. Nº')
                    ->setCellValue("C".$r, 'SERIE A')
                    ->setCellValue("D".$r, '------')
            ->setCellValue("E".$r, '------')
                    ->setCellValue("F".$r, 'SERIE A')   
                    ->setCellValue("G".$r, 'SERIE B')
                    ->setCellValue("H".$r, '------')
            ->setCellValue("I".$r, '------')
                    ->setCellValue("J".$r, 'SERIE B')
                    ->setCellValue("K".$r, 'SERIE C')
                    ->setCellValue("L".$r, '------')
            ->setCellValue("M".$r, '------')
                    ->setCellValue("N".$r, 'SERIE C')
                    ->setCellValue("O".$r, 'SERIE D')
                    ->setCellValue("P".$r, '------')
            ->setCellValue("Q".$r, '------')
                    ->setCellValue("R".$r, 'SERIE D')
                    ->setCellValue("S".$r, 'SERIE E')
                    ->setCellValue("T".$r, '------')
            ->setCellValue("U".$r, '------')
                    ->setCellValue("V".$r, 'SERIE E')
                    ->setCellValue("W".$r, 'SERIE F')
                    ->setCellValue("X".$r, '------')
            ->setCellValue("Y".$r, '------')
                    ->setCellValue("Z".$r, 'SERIE F')
                    ->setCellValue("AA".$r, 'SERIE G')
                    ->setCellValue("AB".$r, '------')
            ->setCellValue("AC".$r, '------')
                    ->setCellValue("AD".$r, 'SERIE G')
                    ->setCellValue("AE".$r, 'SERIE H')
                    ->setCellValue("AF".$r, '------')
            ->setCellValue("AG".$r, '------')
                    ->setCellValue("AH".$r, 'SERIE H')
                    ->setCellValue("AI".$r, 'SERIE I')
                    ->setCellValue("AJ".$r, '------')
            ->setCellValue("AK".$r, '------')
                    ->setCellValue("AL".$r, 'SERIE I')
                    ->setCellValue("AM".$r, 'SERIE J')
                    ->setCellValue("AN".$r, '------')
            ->setCellValue("AO".$r, '------')
                    ->setCellValue("AP".$r, 'SERIE J')        
                    ->setCellValue("AQ".$r, 'TOTAL VENTAS O PRESTACION DE SERVICIOS A   AGENTES DE RETENCION')
                    ->setCellValue("AR".$r, 'TOTAL VENTAS O PRESTACION DE SERVICIOS A   OTROS CONTRIBUYENTES')
                    ->setCellValue("B".$r, 'FECHA')
                    ->setCellValue("C".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("D".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
            ->setCellValue("E".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("F".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("G".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("H".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
            ->setCellValue("I".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("J".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("K".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("L".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("M".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("N".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("O".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("P".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("Q".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("R".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("S".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("T".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("U".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("V".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("W".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("X".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("Y".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("Z".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AA".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AB".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AC".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AD".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AE".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AF".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AG".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AH".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AI".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AJ".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AK".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AL".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AM".$i, 'N° DE INICIO DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AN".$i, 'N° DE INICIO DE CONTROL LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AO".$i, 'Nº FINAL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AP".$i, 'Nº FINAL DE CONTROL DE LA FACTURACIÓN DEL DÍA')
                    ->setCellValue("AQ".$i, 'MONTO CONSOLIDADO DE LAS VENTAS DEL DIA')
                    ->setCellValue("AS".$i, 'MONTO CONSOLIDADO DE LAS VENTAS DEL DIA');
//*******Otra hoja*********
 $objPHPExcel->createSheet();
 $objPHPExcel->setActiveSheetIndex(1);
 $objPHPExcel->getActiveSheet(1)->setTitle('Totales');
 $objPHPExcel->getActiveSheet(1)->setCellValue("A1", 'Fecha')
             ->setCellValue("B3",'SERIE A')
             ->setCellValue("C3",'SERIE A')
             ->setCellValue("D3",'SERIE B')
             ->setCellValue("E3",'SERIE B')
             ->setCellValue("F3",'SERIE C')
             ->setCellValue("G3",'SERIE C')
             ->setCellValue("H3",'SERIE D')
             ->setCellValue("I3",'SERIE D')
             ->setCellValue("J3",'SERIE E')
             ->setCellValue("K3",'SERIE E')
             ->setCellValue("L3",'SERIE F')
             ->setCellValue("M3",'SERIE F')
             ->setCellValue("N3",'SERIE G')
             ->setCellValue("O3",'SERIE G')
             ->setCellValue("P3",'SERIE H')
             ->setCellValue("Q3",'SERIE H')
             ->setCellValue("R3",'SERIE I')
             ->setCellValue("S3",'SERIE I')
             ->setCellValue("T3",'SERIE J')
             ->setCellValue("U3",'SERIE J')
             ->setCellValue("V3",'TOTAL')
             ->setCellValue("W3",'TOTAL')
             ->setCellValue("B4",'SI')
             ->setCellValue("C4",'NO')
             ->setCellValue("D4",'SI')
             ->setCellValue("E4",'NO')
             ->setCellValue("F4",'SI')
             ->setCellValue("G4",'NO')
             ->setCellValue("H4",'SI')
             ->setCellValue("I4",'NO')
             ->setCellValue("J4",'SI')
             ->setCellValue("K4",'NO')
             ->setCellValue("L4",'SI')
             ->setCellValue("M4",'NO')
             ->setCellValue("N4",'SI')
             ->setCellValue("O4",'NO')
             ->setCellValue("P4",'SI')
             ->setCellValue("Q4",'NO')
             ->setCellValue("R4",'SI')
             ->setCellValue("S4",'NO')
             ->setCellValue("T4",'SI')
             ->setCellValue("U4",'NO')
             ->setCellValue("V4",'SI')
             ->setCellValue("W4",'NO');
//****************

$t=4;
$apun=0;
$apuntmdia="";
while($lasfacturas=asignar_a($r_dfacturas,NULL,PGSQL_ASSOC)){
       $apunta2 = $lasfacturas['dia'];
       $apunta2s = $lasfacturas['id_serie'];
       $serieid = "id_serie=$lasfacturas[id_serie] and ";
       $cualseries = $lasfacturas[id_serie];
       $laidfactura =$lasfacturas['id_factura'];
       if(($apunta2 <> $apunta1) or ($apunta2s <> $apunta1s)){
           $eldia = $lasfacturas['dia'];   
           $iniciof = $lasfacturas['numero_factura'];   
           $iniciocon = $lasfacturas['numcontrol'];   
           $apunta1 = '';
           $apunta1s = '';
           $distintos= '';
          
       }else{
             $apunta1 = $lasfacturas['dia'];   
             $apunta1s = $lasfacturas['id_serie'];   
             $distintos=2;
             
           }
          
         $apunta1 = $lasfacturas['dia'];  
         $apunta1s = $lasfacturas['id_serie'];  
         if($distintos==''){
           $objPHPExcel->setActiveSheetIndex(0);
           $ultimnumfact = ejecutar("select tbl_facturas.numero_factura,
                                   tbl_facturas.numcontrol, date_part('day',tbl_facturas.fecha_emision) as dia 
                                   from tbl_facturas where $serieid fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal'
                                   $elestafc 
                                   and date_part('day',tbl_facturas.fecha_emision)='$eldia'
                                    order by id_factura desc limit 1;");     
                              
           $dataultfac = asignar_a($ultimnumfact);
           $finf    =  $dataultfac['numero_factura']; 
           $fincont =  $dataultfac['numcontrol']; 
           if(($cualseries==3) or ($cualseries==8)){
               //reviso si es un monto no aprobado o deducible
               $versirvicio = ejecutar("select procesos.id_proceso
                                from
                                       procesos,gastos_t_b,tbl_procesos_claves
                                where
                                       tbl_procesos_claves.id_factura=$laidfactura and
                                       tbl_procesos_claves.id_proceso = procesos.id_proceso and
                                       procesos.id_proceso = gastos_t_b.id_proceso and
                                      (gastos_t_b.id_tipo_servicio =27 or gastos_t_b.id_tipo_servicio =28);");
               $dataservicioes = asignar_a($versirvicio);
               $elidproceso = $dataservicioes['id_proceso'];
               if($elidproceso > 1){
                     $procesoservicio = "and tbl_procesos_claves.id_proceso <> $elidproceso";
                }else{
                     $procesoservicio ="";
                    }
               //es particular
               $montodelesparti= ejecutar(" select sum(tbl_procesos_claves.monto) as montfac,sum(tbl_procesos_claves.fac_deducible) as deducible,date_part('day',tbl_facturas.fecha_emision) as dia 
                                     from 
                                          tbl_procesos_claves,tbl_facturas,entes,titulares,procesos
                                     where 
                                          fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal'  $elestafc and
                                          tbl_facturas.id_factura=tbl_procesos_claves.id_factura and
                                          tbl_facturas.id_estado_factura <> 3 and
                                          date_part('day',tbl_facturas.fecha_emision) = '$eldia' and tbl_facturas.id_serie=$cualseries and
                                          tbl_procesos_claves.id_proceso = procesos.id_proceso and
                                          procesos.id_titular=titulares.id_titular and 
                                          titulares.id_ente = entes.id_ente and 
                                          entes.id_tipo_ente=6 
                                          $procesoservicio
                                          group by dia order by dia");
                                         
             $montdelesparti=asignar_a($montodelesparti);
             $elmontodelesparti=$montdelesparti['montfac']-$montdelesparti['deducible'];                             

            //No es particular
            $montodelno= ejecutar(" select sum(tbl_procesos_claves.monto) as montfac,sum(tbl_procesos_claves.fac_deducible) as deducible,date_part('day',tbl_facturas.fecha_emision) as dia 
                                     from 
                                          tbl_procesos_claves,tbl_facturas,entes,titulares,procesos
                                     where 
                                          fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal'  $elestafc and
                                          tbl_facturas.id_factura=tbl_procesos_claves.id_factura and
                                          tbl_facturas.id_estado_factura <> 3 and
                                          date_part('day',tbl_facturas.fecha_emision) = '$eldia' and tbl_facturas.id_serie=$cualseries and
                                          tbl_procesos_claves.id_proceso = procesos.id_proceso and
                                          procesos.id_titular=titulares.id_titular and 
                                          titulares.id_ente = entes.id_ente and 
                                          entes.id_tipo_ente<>6 
                                          $procesoservicio
                                          group by dia order by dia");
             $montdelno=asignar_a($montodelno);
             $elmontodelno=$montdelno['montfac']-$montdelno['deducible'];  

               
            }else{
                     
           $sabermonto = ejecutar("select sum(tbl_procesos_claves.monto) as montfac, sum(tbl_procesos_claves.fac_deducible) as deducible,date_part('day',tbl_facturas.fecha_emision) as dia 
from 
tbl_procesos_claves,tbl_facturas 
where 
$serieid fecha_emision>='$fechainicio' and fecha_emision<='$fechafinal' $elestafc  and
tbl_facturas.id_estado_factura <> 3 and
tbl_facturas.id_factura=tbl_procesos_claves.id_factura and
date_part('day',tbl_facturas.fecha_emision) = '$eldia'
group by dia order by dia;");
          $datamont = asignar_a($sabermonto);
          $totalf = $datamont['montfac']-$datamont['deducible'];
           }
          $diaparamostrar="$eldia/$mesf/$anof";
          
          if($apuntmdia <> $eldia){
           $t++;   
           $apun++;
          }else{
            $t=$t;   
            $apun=$apun;
          }
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$t, $apun, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$t, $diaparamostrar, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->setActiveSheetIndex(1);
          $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("A".$t, $diaparamostrar, PHPExcel_Cell_DataType::TYPE_STRING);
          $objPHPExcel->setActiveSheetIndex(0);
          
          if($cualseries == 4){//Serie A
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("B".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
              
          }

          if($cualseries == 5){//Serie B
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("E".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
              
          }
          if($cualseries == 1){//Serie C
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("F".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
              
          }

        if($cualseries == 9){//Serie D
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);

   $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("H".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);

             
              
           }
           if($cualseries == 3){//Serie E
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);

             $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("J".$t, $elmontodelno, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("K".$t, $elmontodelesparti, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
              
           }
          if($cualseries == 6){//Serie F
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("X".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Y".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Z".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
           $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("L".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
              
              
          }
          if($cualseries == 7){//Serie G
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AA".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AB".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AC".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AD".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
  $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("N".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);


              
          }
           if($cualseries == 8){//Serie H
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AE".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AF".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AG".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AH".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("P".$t, $elmontodelno, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("Q".$t, $elmontodelesparti, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);

             
              
           }

              if($cualseries == 10){//Serie I
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AI".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AJ".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Ak".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AL".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
           $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("R".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
          }


              if($cualseries == 11){//Serie J
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AM".$t, $iniciof, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AN".$t, $iniciocon, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AO".$t, $finf, PHPExcel_Cell_DataType::TYPE_STRING);
              $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("AP".$t, $fincont, PHPExcel_Cell_DataType::TYPE_STRING);
           $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValueExplicit("T".$t, $totalf, PHPExcel_Cell_DataType::TYPE_NUMERIC);
              $objPHPExcel->setActiveSheetIndex(0);
          }



    
           
           
           //Sumar los SI//
              $objPHPExcel->setActiveSheetIndex(1);
              $objPHPExcel->getActiveSheet(1)->setCellValue("V".$t, "=SUM(B".$t.",D".$t.",F".$t.",H".$t.",J".$t.",L".$t.",N".$t.",P".$t.",R".$t.",T".$t.")");
          //Sumar los NO//
              $objPHPExcel->getActiveSheet(1)->setCellValue("W".$t, "=SUM(C".$t.",E".$t.",G".$t.",I".$t.",K".$t.",M".$t.",O".$t.",Q".$t.",S".$t.",U".$t.")");
              $objPHPExcel->setActiveSheetIndex(0);
              $objPHPExcel->getActiveSheet(0)->setCellValue("AQ".$t, "=Totales!V".$t);
              $objPHPExcel->getActiveSheet(0)->setCellValue("AR".$t, "=Totales!W".$t);
              
           ////
           $apuntmdia=$apunta1; 
         }
        
             
}
//
 

// Add some data to the second sheet, resembling some different data types



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Factura');
//

 
//

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=libroventa_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 


?>
