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
list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucursal']);

if($id_sucursal==0)	$condicion_sucursal="and admin.id_sucursal>0";
else			$condicion_sucursal="and admin.id_sucursal=$id_sucursal  ";

//si la condicion sigue en este punto vacia hay que buscar por cada proceso su proveedor.
$recibo_che=$_REQUEST['recibo_che'];
$fecha_inicio=$_REQUEST['fechainicio'];
$fecha_fin=$_REQUEST['fechafin'];
$tipo_cheque=$_REQUEST['tipo_cheque'];
$proveedor=$_REQUEST['proveedor'];
$banco=$_REQUEST['banco'];
$fechacreado=date("Y-m-d");

$numcheque=" ";
/* **** verificar si se busca todos los proveedores o uno en especifico **** */
if ($proveedor==""){
	$proveedores="and facturas_procesos.id_proveedor>0";
	}
	else
    {
	$proveedores="and facturas_procesos.id_proveedor=$proveedor";
	}
	/* fin de verificar si busca todos los proveedores */
/* se realiza comparacion de datos para armar las variables para ser utilizadas en la consulta de la db tabla $q_cheques*/
if ($tipo_cheque==0 and $recibo_che==1)
{
    $numcheque="and facturas_procesos.numero_cheque>'0'";
    $proveedores="";
    }
$order="order by facturas_procesos.fecha_creado";
if ($banco=='*'){
	$tbanco="and facturas_procesos.id_banco<>9";
	}
	else
	{
		$tbanco="and facturas_procesos.id_banco=$banco";
        
		}
if ($tipo_cheque==5){
	$ttipo_cheque="and facturas_procesos.tipo_proveedor<>0";
	}
	else
	{
		$ttipo_cheque="and facturas_procesos.tipo_proveedor=$tipo_cheque";
		}
if ($recibo_che==2){
	$recibo_che1="and facturas_procesos.corre_retiva_seniat>0";
    $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";
	$order="order by facturas_procesos.corre_retiva_seniat";
   
    }        
if ($recibo_che==3){
	$recibo_che1=" and facturas_procesos.corre_ret_islr>0";
    $fecha="facturas_procesos.fecha_imp_che>='$fecha_inicio' and facturas_procesos.fecha_imp_che<='$fecha_fin' and";    
	$order="order by facturas_procesos.corre_ret_islr";
    }
    
  if ($recibo_che==1){
	
    $fecha="facturas_procesos.fecha_imp_che>='$fecha_inicio' and facturas_procesos.fecha_imp_che<='$fecha_fin' and";    
	
    }
    
    
          if ($recibo_che==0){
	
    $fecha="facturas_procesos.fecha_creado>='$fecha_inicio' and facturas_procesos.fecha_creado<='$fecha_fin' and";    
	
    }               
 /* fin de  realizar comparacion de datos para armar las variables para ser utilizadas en la consulta de la db tabla $q_cheques*/               
        
        /* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  IVA")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de IVA para ser declarada en el portal del seniat")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

// se empieza a armar la cabecera del archivo de excel
$i=1;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'RIF Agente')
                    ->setCellValue("B".$i, 'Periodo')
                    ->setCellValue("C".$i, 'Fecha Documento')
                    ->setCellValue("D".$i, 'Tipo Operacion')
                    ->setCellValue("E".$i, 'Tipo Documento')
                    ->setCellValue("F".$i, 'Rif Retenido')
                    ->setCellValue("G".$i, 'Numero Factura')	
                    ->setCellValue("H".$i, 'Numero Control')
                    ->setCellValue("I".$i, 'Monto Documento')
                    ->setCellValue("J".$i, 'Base Imponible')
                    ->setCellValue("K".$i, 'Monto Iva')
                    ->setCellValue("L".$i, 'Numero Documento Afectado')
                    ->setCellValue("M".$i, 'Numero Comprobante')
                    ->setCellValue("N".$i, 'Monto Exento Iva')	
                    ->setCellValue("O".$i, 'Alicuota');	
// Fin de armar la cabecera del archivo de excel


			    /* **** buscamos las retenciones ya sea de iva o islr de acuerdo a la seleccion de un tipo proveedor especifico***** */
    if ($recibo_che==2 || $recibo_che==3)
{

	$q_cheques=("select 
                                    admin.nombres,
                                    admin.apellidos,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.numero_cheque,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.codigo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.factura,
                                    facturas_procesos.no_control_fact,
                                    facturas_procesos.fecha_emision_fact,
                                    facturas_procesos.compro_retiva_seniat, 
                                    count(facturas_procesos.factura)
                            from 
                                    facturas_procesos,
                                    admin 
                            where 
                                   $fecha 
                                    facturas_procesos.id_admin=admin.id_admin  
                                    $condicion_sucursal  
                                    $tbanco $ttipo_cheque     
                                    $recibo_che1 
                                    $numcheque 
                                    $proveedores
                            group by 
                                    admin.nombres,
                                    admin.apellidos,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.numero_cheque,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.codigo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.factura,
                                    facturas_procesos.no_control_fact,
                                    facturas_procesos.fecha_emision_fact,
                                    facturas_procesos.compro_retiva_seniat
                            order by 
                                    facturas_procesos.comprobante");
}

      /* **** buscamos todos las retenciones (proveedores de comporas medico otros clinicas etc)ya sea de iva o islr de acuerdo a la seleccion***** */
    if (($recibo_che==2 || $recibo_che==3) and $proveedor==0)
{
    $q_cheques=("select 
                                    admin.nombres,
                                    admin.apellidos,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.numero_cheque,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.codigo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.factura,
                                    facturas_procesos.no_control_fact,
                                    facturas_procesos.fecha_emision_fact,
                                    facturas_procesos.compro_retiva_seniat, 
                                    count(facturas_procesos.factura)
                            from 
                                    facturas_procesos,
                                    admin 
                            where 
                                   $fecha 
                                    facturas_procesos.id_admin=admin.id_admin  
                                    $condicion_sucursal  
                                    $tbanco $ttipo_cheque     
                                    $recibo_che1 
                                    $numcheque 
                            group by 
                                    admin.nombres,
                                    admin.apellidos,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.numero_cheque,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.codigo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.factura,
                                    facturas_procesos.no_control_fact,
                                    facturas_procesos.fecha_emision_fact,
                                    facturas_procesos.compro_retiva_seniat
                            order by 
                                    facturas_procesos.comprobante");
    }
	$r_cheques=ejecutar($q_cheques);

	while($f_cheques=pg_fetch_array($r_cheques,NULL,PGSQL_ASSOC)){
     $i++;
  /* **** verifico que tipo de servicios es para activar la condicion que me dice si consulto por numero cheque o codigo **** */
        if ($recibo_che==0){
            $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]'";
            }
        
        if ($recibo_che==1 and $proveedor>1)
  {
      $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]'";
      }
        
        if ($recibo_che==1 and $proveedor==0)
  {
      $condicion="facturas_procesos.numero_cheque='$f_cheques[numero_cheque]'";
      }
      
          if ($recibo_che==2 || $recibo_che==3)
{
    $condicion="facturas_procesos.codigo='$f_cheques[codigo]'";
    }
    
        if (($recibo_che==2 || $recibo_che==3) and $proveedor==0)
{
    $condicion="facturas_procesos.codigo='$f_cheques[codigo]'";
    }
    
    /* **** fin de verificar la condicion**** */
/* **** compraro si busco proveedor persona o proveedor clinica **** */

/* **** BUSCO EL PROVEEDOR  persona **** */
if ($f_cheques[tipo_proveedor]==1){
$q_proveedor=("select   personas_proveedores.celular_prov,
                                    personas_proveedores.nomcheque,
                                    personas_proveedores.rifcheque,
                                    personas_proveedores.direccioncheque,
                                    actividades_pro.codigo,
                                    actividades_pro.porcentaje,
                                    actividades_pro.sustraendo,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.id_proveedor,
                                    facturas_procesos.motivo,
                                    facturas_procesos.compro_retiva_seniat,
                                    facturas_procesos.corre_compr_islr,
                                    bancos.nombre_banco,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_imp_che
                            from 
                                    personas_proveedores,
                                    actividades_pro,
                                    facturas_procesos,
                                    bancos 
                            where 
                                    personas_proveedores.id_persona_proveedor='$f_cheques[id_proveedor]' and 
                                    personas_proveedores.id_act_pro=actividades_pro.id_act_pro and 
                                    facturas_procesos.id_banco=bancos.id_banco and 
                                    $condicion");
$r_proveedor=ejecutar($q_proveedor);
$num_filas2=num_filas($r_proveedor);
if ($num_filas2>0)
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rifcheque];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[celular_pro];
$sustraendo=$f_proveedor[sustraendo];
$motivo=$f_proveedor[motivo];
$tipo_cheque1=$f_proveedor[tipo_proveedor];

}
else
{
/* **** BUSCO EL PROVEEDOR  clinica**** */

$q_proveedor=("select clinicas_proveedores.*,
                                    actividades_pro.codigo,
                                    actividades_pro.porcentaje,
                                    actividades_pro.sustraendo,
                                    bancos.nombre_banco,
                                    facturas_procesos.comprobante,
                                    facturas_procesos.motivo,
                                    facturas_procesos.fecha_creado,
                                    facturas_procesos.fecha_imp_che,
                                    facturas_procesos.compro_retiva_seniat,
                                    facturas_procesos.corre_compr_islr,
                                    facturas_procesos.ret_individual,
                                    facturas_procesos.tipo_proveedor,
                                    facturas_procesos.id_proveedor  
                            from 
                                    clinicas_proveedores,
                                    proveedores,actividades_pro,
                                    facturas_procesos,
                                    bancos  
                            where 
                                    clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
                                    proveedores.id_proveedor='$f_cheques[id_proveedor]' and 
                                    clinicas_proveedores.id_act_pro=actividades_pro.id_act_pro and 
                                    facturas_procesos.id_proveedor=proveedores.id_proveedor and 
                                    facturas_procesos.id_banco=bancos.id_banco and 
                                    $condicion");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rif];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[telefonos];
$sustraendo=$f_proveedor[sustraendo];
$motivo=$f_proveedor[motivo];
$tipo_cheque1=$f_proveedor[tipo_proveedor];
}
/* **** FIN DE BUSCAR PROVEEDOR **** */

$montot=0;


	$q_cheques1=("select * from 
                                        facturas_procesos,
                                        admin
                                where 
                                        numero_cheque='$f_cheques[numero_cheque]' and 
                                        facturas_procesos.factura='$f_cheques[factura]' and 
                                        facturas_procesos.id_admin=admin.id_admin  
                                        $condicion_sucursal");


$r_cheques1=ejecutar($q_cheques1);
$gastosclinicos=0;
$gastosmedicos=0;
$gastosmedicos1=0;
$totalgastos=0;
$retencion=0;
$monto_final=0;
$iva=0;
$ivaret=0;
$total_ret=0;
$montoexento=0;
$total_neto=0;
while($f_cheques1=pg_fetch_array($r_cheques1,NULL,PGSQL_ASSOC)){
if ($tipo_cheque1==3)
{
$total_neto=$total_neto +  (($f_cheques1[monto_sin_retencion] + $f_cheques1[monto_con_retencion] + $f_cheques1[iva]) - $f_cheques1[iva_retenido]);
}
if ($tipo_cheque==0)
{
$monto= (($f_cheques1[monto_con_retencion] - (($f_cheques1[monto_con_retencion] * $f_cheques1[retencion])/100) ) + $f_cheques1[monto_sin_retencion]);
$montot=$montot + $monto;
$montott= $montott +$monto;


}
else
{
    $ret_individual=$f_cheques1[ret_individual];
	$banco=$f_cheques1[id_banco];
	$iva=$iva + $f_cheques1[iva];
	$ivaret=number_format($ivaret + $f_cheques1[iva_retenido],2,'.','');
	$total_ret=	$total_ret + $f_cheques1[retencion];
	if ($f_cheques[tipo_proveedor]==4 and $f_cheques1[iva]==0)
{
$montoexento= $montoexento + $f_cheques1[monto_sin_retencion];
$montoexentot= $montoexentot + $f_cheques1[monto_sin_retencion];
}
	$gastosclinicos=$gastosclinicos + $f_cheques1[monto_sin_retencion];
    $gastosmedicos=$gastosmedicos + $f_cheques1[monto_con_retencion];
    $esretencion=$f_cheques1[retencion] *1;
    if ($f_cheques[tipo_proveedor]==4 and $esretencion=0){
                $gastosmedicos1=$gastosmedicos1 + $f_cheques1[monto_sin_retencion] ;
    }
       
    
	$totalgastos=$gastosclinicos  + $gastosmedicos;
	$totaldescuento=0;
	$retencion=$retencion + $f_cheques1[retencion];
    $comprobante=$f_cheques1[comprobante];
	$comprobanteislr=$f_cheques1[corre_compr_islr];
	$compro_retiva_seniat=$f_cheques1[compro_retiva_seniat];
	$comprobanteiva=$f_cheques1[corre_retiva_seniat];
	$cheque=$f_cheques1[numero_cheque];
	$recibo=$f_cheques1[num_recibo];
	$nombre=$f_cheques1[anombre];
	$cedula=$f_cheques1[ci];
	$fecha_emision=$f_cheques1[fecha_creado]; 
    
}

}
/* **** FIN DE BUSCAR PROVEEDOR **** */

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, 'J311808639', PHPExcel_Cell_DataType::TYPE_STRING);

list($ano,$mes)=explode("-",$f_cheques['fecha_creado']);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i,  "$ano$mes", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i,  "$f_cheques[fecha_emision_fact]", PHPExcel_Cell_DataType::TYPE_STRING); 
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i,  'C', PHPExcel_Cell_DataType::TYPE_STRING); 
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i,  '01', PHPExcel_Cell_DataType::TYPE_STRING); 
list($r,$f,$p)=explode("-",$rifpro);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, "$r$f$p", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, "$f_cheques[factura]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, "$f_cheques[no_control_fact]", PHPExcel_Cell_DataType::TYPE_STRING);

if ($f_cheques[tipo_proveedor]==3){
        $monto=number_format($gastosclinicos +  $gastosmedicos + $iva,2,'.','');
    }
    else
    {
        $monto=number_format($gastosclinicos +  $gastosmedicos ,2,'.','');
     }   
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "$monto", PHPExcel_Cell_DataType::TYPE_STRING);

if ($f_cheques[tipo_proveedor]==3){
        $monto2=number_format($gastosmedicos,2,'.','');  
    }
    else
    {
       $monto2=number_format((($gastosclinicos +  $gastosmedicos) -$montoexento)-$iva,2,'.','' ); 
     } 
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, "$monto2", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, "$ivaret", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, '0', PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, "$f_cheques[compro_retiva_seniat]", PHPExcel_Cell_DataType::TYPE_STRING);

if ($f_cheques[tipo_proveedor]==3){
       $monto3=number_format($gastosclinicos,2,'.',''); 
    }
    else
    {
        $monto3=number_format($montoexento,2,'.',''); 
     }
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, "$monto3", PHPExcel_Cell_DataType::TYPE_STRING);
  if ($f_cheques[tipo_proveedor]==3){
        $monto4=number_format(($ivaret/$iva),2,'.',''); 
    }
    else
    {
        $monto4= number_format(($ivaret/$iva),2,'.',''); 
     } 
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$i, "$monto4", PHPExcel_Cell_DataType::TYPE_STRING);


}

/* fin de armar toda la hoja de excel*/

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
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);


// se empieza a finalizar las propiedaes  realizacion de la hoja de excel 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relacion_ISLR');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_iva_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 
?>




