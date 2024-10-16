<?php
include ("../../lib/jfunciones.php");
sesion();
$j=1;
/* propiedades para armar el reporte de excel */
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
/** Error reporting */
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
/** Include PHPExcel */
$ar=fopen("datoscon$numealeatorio.xml","w") or
    die("Problemas en la creacion");
   
/* fin propiedades para armar el reporte de excel */
// recepcion de datos enviados

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
$nomcuenta="";
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
	
	if ($tipo_ente==0)
{
	$eltipo_ente="tbl_tipos_entes.id_tipo_ente>=$tipo_ente"; 
	}
	else
	{

		$eltipo_ente="tbl_tipos_entes.id_tipo_ente=$tipo_ente";
     }
        
       
        
		if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
		$serie="Todas Las Series";
	}
	else
	{
	$id_serie="and tbl_series.id_serie=$sucursal";
   
	$q_serie=("select  * from tbl_series where id_serie=$sucursal");
	$r_serie=ejecutar($q_serie);
	$f_serie=asignar_a($r_serie);
	$serie=$f_serie[nomenclatura];
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

if($sucursal==4){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   $numefactu   = $f_factura[numero_factura];
   $nomrifente  = str_replace("-", "",$f_factura['rif']);
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				  $soniguales=2;
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}
if($sucursal==5){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				  $soniguales=2;
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}

if($sucursal==7){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'SERIEG';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}

if($sucursal==8){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'SERIEH';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}

if($sucursal==1){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'AMBVGA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				  $soniguales=2;
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
} 
//******Nueva programacion de la serie D****//
if($sucursal==9){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}
//******Nueva programacion de la serie F****//
if($sucursal==6){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'AMBMDA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}

//******Nueva programacion de la serie E****//
if($sucursal==3){ 
$linea='﻿<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40">
  <Styles>
    <Style ss:ID="Default" ss:Name="Normal">
      <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1" />
      <Borders />
      <Font />
      <Interior />
      <NumberFormat ss:Format="0.000" />
      <Protection />
    </Style>
  </Styles>';	
fputs($ar,$linea);
fputs($ar,"\n");
$linea1='<Worksheet ss:Name="Comprobante Diario">';
fputs($ar,$linea1);
fputs($ar,"\n");
$linea2='<Table ss:ExpandedColumnCount="23" ss:ExpandedRowCount="xxx" x:FullColumns="1">';
fputs($ar,$linea2);
fputs($ar,"\n");
fputs($ar,"<Row>");
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="String">Rg</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Cuenta</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">Descripcion</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Comentario</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Debe</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">Haber</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">C.Costo</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Moneda</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Cambio</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">Tipo Doc.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Doc Ref</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Cu. Gastos</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Auxiliar</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo1</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo2</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Atributo3</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Regla</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Fec. Doc</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta Flujo Efec.</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Afecta posición monetaria</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Monto no afecta posición</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">Modifica Patrimonio</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
$r_factura=pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//
 $totalq = ("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
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
											entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.numcontrol,
											tbl_facturas.id_factura,
											tbl_facturas.id_estado_factura,
											tbl_facturas.fecha_emision,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											tbl_facturas.id_banco,
											tbl_facturas.numero_cheque,
											entes.nombre,entes.rif
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
//											
 
$apuntaente1="";                                  
$pasofactanulada=0;
$lacnt1=0;
$pasoente="";
$monthaber=0;
$sumafactura=0;
$soniguales=0;
$pasopanu=0;
$lalinea=0;
$elrifqpaso="";
$factprincipal="";
$pasfactu="";
$laulfacqpaso="";
$fechqpaso="";
$repquery=ejecutar($totalq);
$cuantoquery=num_filas($repquery);
$peof=0;
$sipaso="";
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{ 
   $anu=0;	 
   $peof++;
   $estafactura = $f_factura[id_estado_factura];
   $deducible   = $f_factura[sum_deducible];
   $montodfactu = $f_factura[sum];
   $nomdelente  = $f_factura[nombre];
   if($nomdelente == 'PARTICULAR'){
	   $nomrifente = 'AMBVGA';
   }else{
	   $nomrifente  = str_replace("-", "",$f_factura['rif']);
	   }
   $numefactu   = $f_factura[numero_factura];
   $lafechfact  = $f_factura[fecha_emision];
   list($elano,$elmes,$eldia)=explode("-",$lafechfact);
   $lafechfact ="$eldia/$elmes/$elano";
   $formfecha   =  "$lafechfact 12:00:00 a.m.";
   $j++;
      if($j <= 2){
	    $cuentadescrip='Cuentas por Cobrar Clientes';
		$cuentacontable="1.01.03.01.001";
        $sumafactura=$sumafactura+$montodfactu; 
        $enelhaber="0";       
        $lalinea++;
        $alinea      = "$lalinea";
        fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
         
          $factprincipal = $numefactu;	      
	  }else{
		    if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible <= 0)){
			
			 
			  $cuentadescrip='Cuentas por Cobrar Clientes';
		      $cuentacontable="1.01.03.01.001";
              $sumafactura=$sumafactura+$montodfactu; 
              $enelhaber="0"; 
              $lalinea++;
              $alinea      = "$lalinea";
              
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
            
			  $soniguales++;
			  if(($pasfactu == "Anulada") and ($soniguales<=1)){
				  $factprincipal = $numefactu;
				  }
		    }else{//comienzo1
				//los entes son distintos
		         if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible <= '0')){
				 
					  if($soniguales>=1){
					      $cuentadescrip='Ingresos por Servicios a Particulares';
					      $cuentacontable="4.01.01.02.001";
					      $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
					      $montodfactut=0;
					      $enelhaber=$sumafactura;  
					      $lalinea++;
                          $alinea      = "$lalinea";
                          $cortfact = substr($laulfacqpaso,-2);
                           if(($pasfactu == "Anulada") and ($sipaso == "anu")){
				                   $factprincipal = $factprincipal-1;
				                   $sipaso="";
				           }
                           if($laulfacqpaso <> $factprincipal){
									  $numfafinal = "$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
				 		
						  $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
					  
					  $cuentadescrip='Cuentas por Cobrar Clientes';
					  $cuentacontable="1.01.03.01.001";
					  $sumafactura=$sumafactura+$montodfactu; 
					  $enelhaber="0"; 
					  $lalinea++;
                      $alinea      = "$lalinea";
                      
                      fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 
                  
				   }//fin de los entes son distintos
				   
				   //los entes son iguales pero la factura es anulada
				   if(($nomdelente == $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
     fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n"); 				
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                              $factprincipal = $numefactu;
							  $pasfactu="Anulada";
							  
							  
					      }//fin de los entes son iguales pero la factura es anulada
					      //los entes son distintos pero la factura es anulada
					      if(($nomdelente <> $pasoente) and ($estafactura == 3) and ($deducible <= 0)){
							  if($soniguales>=1){
								  
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                    if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");				 $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  $factprincipal = $f_factura[numero_factura]-1;	  
								  $anu=1;    
							  } 
							  
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactut="0"; 
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
                              $sipaso="anu";
                              fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">FACTURA ANULADA</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");
                          
							  $factprincipal = $numefactu;
							  $pasfactu="Anulada";
					      }
					      
					      //fin de los entes son distintos pero la factura es anulada
					      //los entes son iguales la factura no esta anulada pero tiene deducible
					       if(($nomdelente == $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							 
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
								   fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");		 
                                
								  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								  
								  
							  } 

							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
  fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                              
                            
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                              $j++;//+
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
 fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");          
                     		  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                 
                            
							  
					      }//fin de los entes son iguales la factura no esta anulada pero tiene deducible
					      //los entes NO son iguales la factura no esta anulada pero tiene deducible
					      if(($nomdelente <> $pasoente) and ($estafactura <> 3) and ($deducible > 0)){
							  
							  if($soniguales>=1){
								  $cuentadescrip='Ingresos por Servicios a Particulares';
								  $cuentacontable="4.01.01.02.001";
								  $sumafactura=$sumafactura+$montodfactu-$montodfactu; 
								  $montodfactut=0;
								  $enelhaber=$sumafactura;  
								  $lalinea++;
                                  $alinea      = "$lalinea";
                                  $cortfact = substr($laulfacqpaso,-2);
                                  if($laulfacqpaso <> $factprincipal){
									  $numfafinal="$factprincipal-$cortfact";
									 }else{
										 $numfafinal = $factprincipal;
										 }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  										 
                                  $montodfactu=$montodfactu;
								  $sumafactura=0;
								  $j++;
								  $soniguales=0;
								 
							  } 
							  $factprincipal = $numefactu;
							  $cuentadescrip='Cuentas por Cobrar Clientes';
							  $cuentacontable="1.01.03.01.001";
							  $sumafactura=0; 
							  $montodfactu = $montodfactu - $deducible;
							  $enelhaber="0"; 
							  $pasopanu=0;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$formfecha.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                              $j++;
							  $cuentadescrip='Ingresos por Servicios a Particulares';
							  $cuentacontable="4.01.01.02.001";
							  $sumafactura=0; 
							  $enelhaber="0"; 
							  $pasopanu=1;
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomdelente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                               
                             
							  $j++;
							  $entetompral = "$nomdelente (DEDUCIBLE)";
							  $lalinea++;
                              $alinea      = "$lalinea";
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$entetompral.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$deducible.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numefactu.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$nomrifente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");                                
                            
					      }
					      //Fin de los entes NO son iguales la factura no esta anulada pero tiene deducible
		    }//fin de comienzo1
		  
		  }
   
   
   ////
   if($peof>=$cuantoquery){
      if(($estafactura <> 3) and ($deducible <= '0')){
		  $cuentadescrip='Ingresos por Servicios a Particulares';
		  $cuentacontable="4.01.01.02.001";
		  $sumafactura=($sumafactura+$montodfactu-$montodfactu); 
		  $montodfactut=0;
		  $enelhaber=$sumafactura;  
		  $lalinea++;
          $alinea      = "$lalinea";
          $cortfact = substr($laulfacqpaso,-2);
          if($laulfacqpaso <> $factprincipal){
		     $numfafinal = "$factprincipal-$cortfact";
		  }else{
			      $numfafinal = $factprincipal;
		        }
fputs($ar,'<Row xmlns="urn:schemas-microsoft-com:office:spreadsheet">');
fputs($ar,"\n");
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$alinea.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentacontable.'</Data>
        </Cell>');
fputs($ar,"\n");   
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$cuentadescrip.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$pasoente.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$montodfactut.'</Data>
        </Cell>');
fputs($ar,"\n");     
fputs($ar,'<Cell>
          <Data ss:Type="Number">'.$enelhaber.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">01</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">BSF</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="Number">1.00000</Data>
        </Cell>');
fputs($ar,"\n");  
fputs($ar,'<Cell>
          <Data ss:Type="String">FACT</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$numfafinal.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$elrifqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">
          </Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">'.$fechqpaso.'</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="Number">0,00</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,'<Cell>
          <Data ss:Type="String">false</Data>
        </Cell>');
fputs($ar,"\n"); 
fputs($ar,"</Row>");
fputs($ar,"\n");  		        
                         $montodfactu=$montodfactu;
						  $sumafactura=0;
						  $j++;
						  $factprincipal = $numefactu;	      
						  
					  }
				   }
   
   ///
   $elrifqpaso = $nomrifente;
   $pasoente = $nomdelente;
   $laulfacqpaso = $f_factura[numero_factura];
   $fechqpaso = $formfecha;
}
fputs($ar,"</Table>
  </Worksheet>
</Workbook>"); 
fclose($ar);
    $name = "datoscon$numealeatorio.xml";
    header("Content-disposition: attachment; filename=$name");
    header("Content-type: application/octet-stream");
    readfile($name);
}



?>
