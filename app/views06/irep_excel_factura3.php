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
$num_cheque=$_REQUEST[num_cheque];
$carterv = strtoupper($_REQUEST[clavcar]);
$direccvalija = $_REQUEST[direcvalija];
if(!empty($carterv)){
   if($direccvalija == '1'){
	   $andclave = "and tbl_procesos_claves.no_clave like('$carterv%')"; 
   }else{
	      if($direccvalija == '2'){
	         $andclave = "and tbl_procesos_claves.no_clave like('%$carterv')"; 
           }else{
			     $andclave = "and tbl_procesos_claves.no_clave like('%$carterv%')"; 
			   }  
	   
	   }

}
if ($num_cheque>0){
    $andnumcheque="and tbl_facturas.numero_cheque='$num_cheque' ";
    }
    else
    {
         $andnumcheque="";
	}

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

/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);




		if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
	$serie="Todas Las Series";
	}
	else
	{
	$id_serie="and tbl_series.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	$serie=$f_serie[nomenclatura];
		}
        
// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion de  Facturas ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra la relacion de  facturas 
                                                        Serie $f_serie[nomenclatura] Sucursal $f_serie[nombre] 
                                                        Condicion de Pago $descripcion")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun( "EMPRESA: CLINISALUD MEDICINA PREPAGADA S.A.");
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('A5')->setValue($objRichText );
	// Merge cells
    $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
    $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


$objRichText = new PHPExcel_RichText();
$objPayable = $objRichText->createTextRun( "Rif:J-31180863-9");
$objPayable->getFont()->setBold(true);
$objPayable->getFont()->setItalic(true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_BLACK) );;
$objPHPExcel->getActiveSheet()->getCell('F5')->setValue($objRichText );
	// Merge cells
    $objPHPExcel->getActiveSheet()->mergeCells('F5:J5');
    $objPHPExcel->getActiveSheet()->getStyle('F5:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);


$i=9;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, '')
					->setCellValue("B".$i, 'Titular')
                    ->setCellValue("C".$i, 'Cedula')
                    ->setCellValue("D".$i, 'Beneficiario')
					 ->setCellValue("E".$i, 'Cedula')
                    ->setCellValue("F".$i, 'Fecha Egreso')
					->setCellValue("G".$i, 'Clave')
                    ->setCellValue("H".$i, 'Factura')
                    ->setCellValue("I".$i, 'Num Control')
					->setCellValue("J".$i, 'Serie')
                    ->setCellValue("K".$i, 'Estado')
					->setCellValue("L".$i, 'Fecha Emision Factura')
					->setCellValue("M".$i, 'Monto (Bs.S)')	
					->setCellValue("N".$i, 'Deducible (Bs.S.)')	
					->setCellValue("O".$i, 'Total (Bs.S.)')	
                    ->setCellValue("P".$i, 'Banco')
                    ->setCellValue("Q".$i, 'Num Cheque o tarjeta')
                    ->setCellValue("R".$i, 'ente')
                    ->setCellValue("S".$i, 'Subdivision')
                    ->setCellValue("T".$i, 'Diagnostico');	
										 
                    
                    

  if ($forma_pago==4){
      $r_factura=pg_query("select 
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
                                        $id_seriep 
					$andclave
					and
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
     
     }
     else
     {
 $r_factura=pg_query("select
                                            tbl_facturas.fecha_emision,
                                            tbl_facturas.numero_factura,
                                            tbl_facturas.numcontrol,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_factura,
                                            tbl_facturas.id_estado_factura,
                                            tbl_facturas.tipo_ente,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											gastos_t_b.fecha_cita,
                                            count(tbl_procesos_claves.id_factura) 
                                    from 
                                            tbl_facturas,
											tbl_series,
                                            tbl_procesos_claves,
                                            titulares,
                                            procesos,
                                            entes,
                                            gastos_t_b
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura  and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente 
                                            $elente and
                                            entes.id_tipo_ente=$tipo_ente and 
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin'   and
											tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie 
                                            $andclave
                                            $tipo_pago 
											$andnumcheque and
                                            procesos.id_proceso=gastos_t_b.id_proceso 
                                            $servicios
                                    group by 
                                            tbl_facturas.fecha_emision,
                                            tbl_facturas.numero_factura,
                                            tbl_facturas.numcontrol,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_factura,
                                            tbl_facturas.tipo_ente,
                                            tbl_facturas.id_estado_factura,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											gastos_t_b.fecha_cita	
                                    order by 
                                            tbl_facturas.numero_factura");

 }
 
 
$contador=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	if ($f_factura[id_estado_factura]==1)
{
	$estado_factura="Pagadas";
	}
	
if ($f_factura[id_estado_factura]==2)
{
	$estado_factura="Por Cobrar";
	}
	
	
if ($f_factura[id_estado_factura]==3)
{
	$estado_factura="Anuladas";
	}
    $i++;
	
	/* **** busco el banco **** */
$q_banco=("select * from tbl_bancos where tbl_bancos.id_ban=$f_factura[id_banco] ");
$r_banco=ejecutar($q_banco);
$f_banco=asignar_a($r_banco);

	/* **** busco la tarjeta **** */
$q_tarjeta=("select * from tbl_nombre_tarjetas where tbl_nombre_tarjetas.id_nom_tarjeta=$f_factura[id_nom_tarjeta]");
$r_tarjeta=ejecutar($q_tarjeta);
$f_tarjeta=asignar_a($r_tarjeta);
	
	$contador++;


 if ($forma_pago==4){
     $r_titulares=pg_query(" select 
                                            titulares.id_titular,
                                            titulares.id_ente,
                                            clientes.*,
                                            subdivisiones.subdivision,
                                            procesos.comentarios_medico
                                    from 
                                            clientes,
                                            titulares,
                                            subdivisiones,
                                            procesos,
                                            titulares_subdivisiones 
                                    where 
                                            clientes.id_cliente=titulares.id_cliente and 
                                            titulares.id_titular=procesos.id_titular and 
                                            procesos.id_proceso=$f_factura[id_proceso] and 
                                            titulares.id_titular=titulares_subdivisiones.id_titular and 
                                            titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision");
($f_titulares=pg_fetch_array($r_titulares, NULL, PGSQL_ASSOC));

$ente= $f_titulares[id_ente];
$titular= $f_titulares[apellidos];
$r_beneficiarios=pg_query(" select 
                                                    * 
                                            from 
                                                    clientes,
                                                    beneficiarios,
                                                    procesos
                                            where 
                                                    clientes.id_cliente=beneficiarios.id_cliente and 
                                                    beneficiarios.id_beneficiario=procesos.id_beneficiario and 
                                                    procesos.id_proceso=$f_factura[id_proceso]");
($f_beneficiarios=pg_fetch_array($r_beneficiarios, NULL, PGSQL_ASSOC));
$beneficiario= $f_beneficiarios[apellidos];


		$r_gastos=pg_query("select 
                                                    gastos_t_b.nombre,
                                                    gastos_t_b.enfermedad,
                                                    gastos_t_b.monto_reserva,
                                                    gastos_t_b.monto_aceptado,
                                                    gastos_t_b.descripcion 
                                            from 
                                                    gastos_t_b,
                                                    procesos
                                            where 
                                                    gastos_t_b.id_proceso=procesos.id_proceso and 
                                                    procesos.id_proceso=$f_factura[id_proceso]");
     
     }
     else
     {
$r_titulares=pg_query(" select 
                                            titulares.id_titular,
                                            titulares.id_ente,
                                            clientes.*,
                                            subdivisiones.subdivision,
                                            procesos.comentarios_medico 
                                    from 
                                            clientes,
                                            titulares,
                                            subdivisiones,
                                            procesos,
                                            tbl_procesos_claves,
                                            titulares_subdivisiones 
                                    where 
                                            clientes.id_cliente=titulares.id_cliente and 
                                            titulares.id_titular=procesos.id_titular and 
                                            procesos.id_proceso=tbl_procesos_claves.id_proceso and 
                                            tbl_procesos_claves.id_factura=$f_factura[id_factura] and 
                                            titulares.id_titular=titulares_subdivisiones.id_titular and 
                                            titulares_subdivisiones.id_subdivision=subdivisiones.id_subdivision");
($f_titulares=pg_fetch_array($r_titulares, NULL, PGSQL_ASSOC));
$ente= $f_titulares[id_ente];
$titular= $f_titulares[apellidos];
$r_beneficiarios=pg_query(" select 
                                                    * 
                                            from 
                                                    clientes,
                                                    beneficiarios,
                                                    procesos,
                                                    tbl_procesos_claves
                                            where 
                                                    clientes.id_cliente=beneficiarios.id_cliente and 
                                                    beneficiarios.id_beneficiario=procesos.id_beneficiario and 
                                                    procesos.id_proceso=tbl_procesos_claves.id_proceso and
                                                    tbl_procesos_claves.id_factura=$f_factura[id_factura]");
($f_beneficiarios=pg_fetch_array($r_beneficiarios, NULL, PGSQL_ASSOC));
$beneficiario= $f_beneficiarios[apellidos];


		$r_gastos=pg_query("select 
                                                    tbl_procesos_claves.no_clave,
                                                    gastos_t_b.nombre,
                                                    gastos_t_b.enfermedad,
                                                    gastos_t_b.monto_reserva,
                                                    gastos_t_b.monto_aceptado,
                                                    gastos_t_b.descripcion 
                                            from 
                                                    gastos_t_b,
                                                    tbl_procesos_claves 
                                            where 
                                                    gastos_t_b.id_proceso=tbl_procesos_claves.id_proceso and 
                                                    tbl_procesos_claves.id_factura=$f_factura[id_factura]");
        }
        
$r_ente=pg_query("select  * from entes where id_ente=$ente");
($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC));
		$totalmontres=0;
		$totalmontpag=0;
		while($f_gastos=pg_fetch_array($r_gastos, NULL, PGSQL_ASSOC)){
		

		
		 if ($forma_pago==4){
             $totalmontpag =	$totalmontpag + ($f_gastos['monto_aceptado']);
            $totalmontpag1 =	$totalmontpag1 + ($f_gastos['monto_aceptado']);
             }
             else
             {
		//Monto reserva
		if ($f_factura[id_estado_factura]==3){
			$totalmontres=0;
			}
			else{
		$totalmontres = $totalmontres + ($f_gastos['monto_reserva']);
		
		$totalmontres1 = $totalmontres1 + ($f_gastos['monto_reserva']);
		}

		//Monto Aceptado
		if ($f_factura[id_estado_factura]==3){
			$totalmontpag=0;
			}
			else
			{
		$totalmontpag =	$totalmontpag + ($f_gastos['monto_aceptado']);
		$totalmontpag1 =	$totalmontpag1 + ($f_gastos['monto_aceptado']);
		}
        }
		$no_clave= $f_gastos['no_clave'];
		
		}
		pg_free_result($r_gastos);
		
		$q_deducible=("select 
                                                    tbl_procesos_claves.fac_deducible
                                            from 
													tbl_procesos_claves 
                                            where 
                                                    tbl_procesos_claves.id_factura=$f_factura[id_factura] and tbl_procesos_claves.fac_deducible>0");
			$r_deducible=ejecutar($q_deducible);
			$f_deducible=asignar_a($r_deducible);
			$totaldeducible=	$totaldeducible + ($f_deducible['fac_deducible']);
   		
   		
/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);
                    		            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_titulares[apellidos]  $f_titulares[nombres]", PHPExcel_Cell_DataType::TYPE_STRING);                    		            		
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titulares[cedula], PHPExcel_Cell_DataType::TYPE_STRING);                    		            			
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, "$f_beneficiarios[apellidos] $f_beneficiarios[nombres]", PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, "$f_beneficiarios[cedula]", PHPExcel_Cell_DataType::TYPE_STRING);
if(empty($f_beneficiarios[nombres])){
  $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$i, $f_titulares[comentarios_medico], PHPExcel_Cell_DataType::TYPE_STRING);                    		            			
}else{
	$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$i, $f_beneficiarios[comentarios_medico], PHPExcel_Cell_DataType::TYPE_STRING);                    		            			
	}
 if ($forma_pago==4){
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_factura[fecha_cita] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $no_clave, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_factura[id_proceso], PHPExcel_Cell_DataType::TYPE_STRING);  
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "00".$f_factura[numcontrol], PHPExcel_Cell_DataType::TYPE_STRING);   
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_factura[fecha_cita] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, $totalmontpag ); 
$objPHPExcel->getActiveSheet(0)->setCellValue("N".$i, $f_deducible['fac_deducible']); 
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, $totalmontpag - $f_deducible['fac_deducible']); 
     }
     else
     {
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_factura[fecha_cita] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $no_clave, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, "00".$f_factura[numero_factura], PHPExcel_Cell_DataType::TYPE_STRING);    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "00".$f_factura[numcontrol], PHPExcel_Cell_DataType::TYPE_STRING);      

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_factura[nomenclatura] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("K".$i, $estado_factura); 
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_factura[fecha_emision] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, $totalmontpag ); 

$objPHPExcel->getActiveSheet(0)->setCellValue("N".$i, $f_deducible['fac_deducible']); 
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, $totalmontpag - $f_deducible['fac_deducible']); 
}

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $f_banco[nombanco], PHPExcel_Cell_DataType::TYPE_STRING);                    
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$i, $f_factura[numero_cheque], PHPExcel_Cell_DataType::TYPE_STRING);                    		                    		    
      if ($f_factura[tipo_ente]==3 || $f_factura[tipo_ente]==5)
        {
            $objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_e[nombre], PHPExcel_Cell_DataType::TYPE_STRING);                    		            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $f_titulares[subdivision], PHPExcel_Cell_DataType::TYPE_STRING);                    		                       		            				

            }
            else
            {
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_ente[nombre], PHPExcel_Cell_DataType::TYPE_STRING);                    		            
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $f_titulares[subdivision], PHPExcel_Cell_DataType::TYPE_STRING);                    		              				
}
       

}
$j=$i;
$i++;
pg_free_result($r_factura);
if($contador==1){
$contador="(Una Factura)";
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
}else{
$contador="($contador Facturas)";
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $contador, PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
}
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, 'total', PHPExcel_Cell_DataType::TYPE_STRING);                    		            				
$objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, "=SUM(M4:M$j)");  
$objPHPExcel->getActiveSheet(0)->setCellValue("N".$i, "=SUM(N4:N$j)");   
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, "=SUM(O4:O$j)");   

//propiedades para darle tamaño automatico a las celdas
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(3);
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

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("M4:M$j")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("N4:N$j")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("N4:N$j")->getNumberFormat()
->setFormatCode('#,##0.00');
// Add a drawing to the worksheet
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Logo');
$objDrawing->setDescription('Logo');
$objDrawing->setPath('../../public/images/head.png');
$objDrawing->setHeight(100);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
// Set style for header row using alternative method

$objPHPExcel->getActiveSheet()->getStyle('A9:S9')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Relacion_Facturas');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_facturaserie_$serie_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>
