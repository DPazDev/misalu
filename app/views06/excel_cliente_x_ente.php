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


/* Nombre del Archivo: ireporte_cliente_x_estado.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación de los Clientes Totales, de un determinado Ente
*/  
   list($estadot,$estado_clientet)=explode("@",$_REQUEST['estadot']);
	if($estadot==0)	        $condicion_estadot="";
	else if($estadot=="-01"){// activo con beneficiario //
	$condicion_estadot="and estados_t_b.id_estado_cliente=4 and estados_t_b.id_beneficiario>'0'";
	}

	else if($estadot=="-02"){// activo sin beneficiario //
	$condicion_estadot="and estados_t_b.id_estado_cliente=4";
	$condicion_count="count(estados_t_b.id_titular),";}

	else{
	$condicion_estadot="and estados_t_b.id_estado_cliente=$estadot and estados_t_b.id_beneficiario='0' ";
	}


   list($estadob,$estado_clienteb)=explode("@",$_REQUEST['estadob']);
	if($estadob==0)	        $condicion_estadob="";
	else if($estadob=="-02"){// beneficiario activo + lapso de espera //
	 $condicion_estadob="and (estados_t_b.id_estado_cliente=1 or estados_t_b.id_estado_cliente=4)";}	
	else
	$condicion_estadob="and estados_t_b.id_estado_cliente=$estadob";

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

// propiedades para documentar el archivo o reporte de excel
$objPHPExcel->getProperties()->setCreator("$f_admin[nombres] $f_admin[apellidos]")
							 ->setLastModifiedBy("$f_admin[nombres] $f_admin[apellidos]")
							 ->setTitle("Relacion Clientes por Entes ")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Reporte que muestra Relacion de Clientes por Entes")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");


$objPHPExcel->getActiveSheet(0)->mergeCells('A3:J3');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Reporte Relación Clientes Titulares en Estado $estado_clientet y Beneficiarios en Estado $estado_clienteb, del Tipo de Ente $nom_tipo_ente, del Ente $ente");


$i=6;//variable para dar la posicion de la celda;
$objPHPExcel->getActiveSheet(0)->setCellValue("A".$i, 'ID TITULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("B".$i, 'TITULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("C".$i, 'CEDULA TITULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("D".$i, 'ESTADO');
$objPHPExcel->getActiveSheet(0)->setCellValue("E".$i, 'TIPO CLIENTE');
$objPHPExcel->getActiveSheet(0)->setCellValue("F".$i, 'FECHA NAC.');
$objPHPExcel->getActiveSheet(0)->setCellValue("G".$i, 'GENERO');
$objPHPExcel->getActiveSheet(0)->setCellValue("H".$i, 'ID BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("I".$i, 'BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("J".$i, 'CEDULA BENEFICIARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("K".$i, 'ESTADO');
$objPHPExcel->getActiveSheet(0)->setCellValue("L".$i, 'FECHA NAC.');
$objPHPExcel->getActiveSheet(0)->setCellValue("M".$i, 'GENERO');
$objPHPExcel->getActiveSheet(0)->setCellValue("N".$i, 'PARENTESCO');
$objPHPExcel->getActiveSheet(0)->setCellValue("O".$i, 'EDAD');
$objPHPExcel->getActiveSheet(0)->setCellValue("P".$i, 'COMENTARIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("Q".$i, 'TELEFONO');
$objPHPExcel->getActiveSheet(0)->setCellValue("R".$i, 'CELULAR');
$objPHPExcel->getActiveSheet(0)->setCellValue("S".$i, 'ENTE');
$objPHPExcel->getActiveSheet(0)->setCellValue("T".$i, 'FECHA INCLUSION');
$objPHPExcel->getActiveSheet(0)->setCellValue("U".$i, 'FECHA EXCLUSION');
$objPHPExcel->getActiveSheet(0)->setCellValue("V".$i, 'NOMBRE POLIZA');
$objPHPExcel->getActiveSheet(0)->setCellValue("W".$i, 'DESCRIPCION');
$objPHPExcel->getActiveSheet(0)->setCellValue("X".$i, 'CUALIDAD');
$objPHPExcel->getActiveSheet(0)->setCellValue("Y".$i, 'FECHA INICIO');
$objPHPExcel->getActiveSheet(0)->setCellValue("Z".$i, 'FECHA FIN');
$objPHPExcel->getActiveSheet(0)->setCellValue("AA".$i, 'MONTO');
$objPHPExcel->getActiveSheet(0)->setCellValue("AB".$i, 'GASTOS');


	$q_titular=("select 
	clientes.id_cliente,
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	clientes.sexo,
	clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	$condicion_count
	titulares.tipocliente,
	titulares.id_ente,
	entes.id_tipo_ente,entes.nombre, titulares.fecha_inclusion,entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato

	from 
	clientes, estados_clientes, estados_t_b,entes, titulares, titulares_subdivisiones
	where 
	clientes.id_cliente=titulares.id_cliente  
	$condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente and 
	titulares.id_titular=estados_t_b.id_titular 
	$condicion_estadot
	 and
	titulares.id_titular=titulares_subdivisiones.id_titular  
	$condicion_subdivi and
	estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente 
	group by 
	clientes.id_cliente,	
	clientes.apellidos,
	clientes.nombres,
	clientes.cedula,
	clientes.sexo,
	clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
	estados_clientes.estado_cliente,
	estados_t_b.id_estado_cliente,
	titulares.id_titular,
	titulares.tipocliente,
	titulares.id_ente,
	entes.id_tipo_ente,entes.nombre,entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato, titulares.fecha_inclusion
	order by estados_clientes.estado_cliente,clientes.apellidos");
$r_titular=ejecutar($q_titular);
/*echo $q_titular;*/

	$ta=0;
	$te=0;
	$ba=0;
	$be=0;
$sexot=0;






	while($f_titular=asignar_a($r_titular)){
		if ($f_titular['id_estado_cliente']=='4'){
			$ta=$ta + 1;
		} else  if ($f_titular['id_estado_cliente']=='5') {
			$te=$te + 1;
		}
	
if($f_titular[tipocliente]=='0'){
$tip="TOMADOR"; $conto++;}
else{
$tip="TITULAR"; $conti++;}


if($f_titular[sexo]==1){
	$sexot="MASCULINO";
	}
 	else 
	{
	$sexot="FEMENINO";
	}



$q_exclusion=("select registros_exclusiones.fecha_exclusion from registros_exclusiones where registros_exclusiones.id_titular=$f_titular[id_titular]");
$r_exclusion=ejecutar($q_exclusion);
$f_exclusion=asignar_a($r_exclusion);




$q_poliza=("select
polizas.id_poliza,
polizas_entes.id_ente, 
polizas_entes.id_poliza,
propiedades_poliza.cualidad,
polizas.nombre_poliza,
propiedades_poliza.descripcion,
propiedades_poliza.monto_nuevo,
coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,
coberturas_t_b.id_cobertura_t_b
from propiedades_poliza,polizas,polizas_entes,coberturas_t_b
where 
polizas.id_poliza=polizas_entes.id_poliza and 
polizas_entes.id_ente=$f_titular[id_ente] and 
propiedades_poliza.id_poliza=polizas.id_poliza and 
propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and 
coberturas_t_b.id_titular=$f_titular[id_titular] and 
coberturas_t_b.id_beneficiario=0 
");
$r_poliza=ejecutar($q_poliza);








if($estadot=='-02' && $f_titular['count']=='1')   { 
	while($f_poliza=asignar_a($r_poliza)){
   	    $i++;	

$total_gastos=0;
$total_gastosben=0;

$q_gastos=("select 
gastos_t_b.id_cobertura_t_b,
gastos_t_b.monto_aceptado,
procesos.id_proceso,
procesos.fecha_recibido
from procesos,gastos_t_b
where
 
(procesos.fecha_recibido>='$f_titular[fecha_inicio_contrato]' and procesos.fecha_recibido<='$f_titular[fecha_renovacion_contrato]') and
gastos_t_b.id_cobertura_t_b='$f_poliza[id_cobertura_t_b]' and
procesos.id_proceso=gastos_t_b.id_proceso");
$r_gastos=ejecutar($q_gastos);


	

/* empiezo a armar la consulta para el reporte de excel en este caso como necesito validar 
que todo sea tipo texto utilizo la siguiente funcion setCellValueExplicit y al final del campo q se va a mostrar
coloco el tipo de archivo PHPExcel_Cell_DataType::TYPE_STRING */

$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $tip, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, TITULAR, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$i, calcular_edad($f_titular['fecha_nacimiento']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $f_titular[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$i, $f_titular[telefono_hab], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_titular[celular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $f_titular[nombre], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$i, $f_titular[fecha_inclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$i, $f_exclusion[fecha_exclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$i, $f_poliza[nombre_poliza], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$i, $f_poliza[descripcion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("X".$i, $f_poliza[cualidad], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Y".$i, $f_titular[fecha_inicio_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Z".$i, $f_titular[fecha_renovacion_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("AA".$i, $f_poliza[monto_nuevo] );

while($f_gastos=asignar_a($r_gastos)){
$total_gastos=$total_gastos + ($f_gastos[monto_aceptado]);
}

$objPHPExcel->getActiveSheet(0)->setCellValue("AB".$i, $total_gastos);


 }}



if($estadot!='-02')  { 

	while($f_poliza=asignar_a($r_poliza)){
   	    $i++;	

$total_gastos1=0;
$total_gastosben=0;



$q_gastos1=("select 
gastos_t_b.id_cobertura_t_b,
gastos_t_b.monto_aceptado,
procesos.id_proceso,
procesos.fecha_recibido
from procesos,gastos_t_b
where
 
(procesos.fecha_recibido>='$f_titular[fecha_inicio_contrato]' and procesos.fecha_recibido<='$f_titular[fecha_renovacion_contrato]') and
gastos_t_b.id_cobertura_t_b='$f_poliza[id_cobertura_t_b]' and
procesos.id_proceso=gastos_t_b.id_proceso");
$r_gastos1=ejecutar($q_gastos1);


$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $tip, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, TITULAR, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$i, calcular_edad($f_titular['fecha_nacimiento']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $f_titular[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$i, $f_titular[telefono_hab], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_titular[celular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $f_titular[nombre] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$i, $f_titular[fecha_inclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$i, $f_exclusion[fecha_exclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$i, $f_poliza[nombre_poliza], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$i, $f_poliza[descripcion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("X".$i, $f_poliza[cualidad], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Y".$i, $f_titular[fecha_inicio_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Z".$i, $f_titular[fecha_renovacion_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("AA".$i, $f_poliza[monto_nuevo] );

	while($f_gastos1=asignar_a($r_gastos1)){
$total_gastos1=$total_gastos1 + ($f_gastos1[monto_aceptado]);}

$objPHPExcel->getActiveSheet(0)->setCellValue("AB".$i, $total_gastos1);}
              




		$q_beneficiario=("
		select 
		beneficiarios.id_cliente,
		beneficiarios.id_parentesco,
		beneficiarios.id_beneficiario,
		beneficiarios.fecha_inclusion,
		parentesco.parentesco,
		clientes.apellidos,
		clientes.nombres,
		clientes.cedula,
		clientes.sexo,
		clientes.fecha_nacimiento,clientes.comentarios,clientes.telefono_hab, clientes.celular,
		estados_clientes.estado_cliente,
		estados_t_b.id_estado_cliente 
		from clientes, estados_clientes, beneficiarios, estados_t_b, parentesco,titulares 
		where 
		clientes.id_cliente=beneficiarios.id_cliente and 
		titulares.id_titular=beneficiarios.id_titular and 
		titulares.id_titular=$f_titular[id_titular] and 
		beneficiarios.id_beneficiario=estados_t_b.id_beneficiario  
	        $condicion_estadob and
		estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 		parentesco.id_parentesco=beneficiarios.id_parentesco order by clientes.apellidos");
		$r_beneficiario=ejecutar($q_beneficiario);

$sexob=0;

		while($f_beneficiario=asignar_a($r_beneficiario)){
			if ($f_beneficiario['id_estado_cliente']=='4'){
				$ba=$ba + 1;
			}else if ($f_beneficiario['id_estado_cliente']=='5') {
				$be=$be + 1;
			}

if($f_beneficiario[sexo]==1){
	$sexob="MASCULINO";
	}
 	else 
	{
	$sexob="FEMENINO";
	}

$q_exclusion_b=("select registros_exclusiones.fecha_exclusion from registros_exclusiones where registros_exclusiones.id_beneficiario=$f_beneficiario[id_beneficiario]");
$r_exclusion_b=ejecutar($q_exclusion_b);
$f_exclusion_b=asignar_a($r_exclusion_b);



$q_polizaben=("select
polizas.id_poliza, 
polizas_entes.id_ente, 
polizas_entes.id_poliza,
propiedades_poliza.cualidad,
polizas.nombre_poliza,
propiedades_poliza.descripcion,
propiedades_poliza.monto_nuevo,
coberturas_t_b.id_propiedad_poliza,
coberturas_t_b.monto_actual,
coberturas_t_b.id_cobertura_t_b
from propiedades_poliza,polizas,polizas_entes,coberturas_t_b
where 
polizas.id_poliza=polizas_entes.id_poliza and 
polizas_entes.id_ente=$f_titular[id_ente] and 
propiedades_poliza.id_poliza=polizas.id_poliza and 
propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and 
coberturas_t_b.id_beneficiario=$f_beneficiario[id_beneficiario]

");




$r_polizaben=ejecutar($q_polizaben);
	while($f_polizaben=asignar_a($r_polizaben)){
   	    $i++;

$total_gastosben=0;

$q_gastosben=("select 
gastos_t_b.id_cobertura_t_b,
gastos_t_b.monto_aceptado,
procesos.id_proceso,
procesos.fecha_recibido
from procesos,gastos_t_b
where
 
(procesos.fecha_recibido>='$f_titular[fecha_inicio_contrato]' and procesos.fecha_recibido<='$f_titular[fecha_renovacion_contrato]') and
gastos_t_b.id_cobertura_t_b='$f_polizaben[id_cobertura_t_b]' and
procesos.id_proceso=gastos_t_b.id_proceso");
$r_gastosben=ejecutar($q_gastosben);



	
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("A".$i, $f_titular[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("B".$i, "$f_titular[nombres] $f_titular[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("C".$i, $f_titular[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("D".$i, $f_titular[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("E".$i, $tip, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("F".$i, $f_titular[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("G".$i, $sexot, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("H".$i, $f_beneficiario[id_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("I".$i, "$f_beneficiario[nombres] $f_beneficiario[apellidos]", PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("J".$i, $f_beneficiario[cedula], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("K".$i, $f_beneficiario[estado_cliente], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("L".$i, $f_beneficiario[fecha_nacimiento], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("M".$i, $sexob, PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("N".$i, $f_beneficiario[parentesco], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("O".$i, calcular_edad($f_beneficiario['fecha_nacimiento']), PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("P".$i, $f_beneficiario[comentarios], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Q".$i, $f_beneficiario[telefono_hab], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("R".$i, $f_beneficiario[celular], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("S".$i, $f_titular[nombre] , PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("T".$i, $f_beneficiario[fecha_inclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("U".$i, $f_exclusion_b[fecha_exclusion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("V".$i, $f_polizaben[nombre_poliza], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("W".$i, $f_polizaben[descripcion], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("X".$i, $f_polizaben[cualidad], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Y".$i, $f_titular[fecha_inicio_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValueExplicit("Z".$i, $f_titular[fecha_renovacion_contrato], PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet(0)->setCellValue("AA".$i, $f_polizaben[monto_nuevo] );

	while($f_gastosben=asignar_a($r_gastosben)){
$total_gastosben=$total_gastosben + ($f_gastosben[monto_aceptado]);}

$objPHPExcel->getActiveSheet(0)->setCellValue("AB".$i, $total_gastosben );
	     
}}}}
			
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
$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

for($i=2;$i<=$j;$i++){

 $objPHPExcel->getActiveSheet()->getRowDimension("$i")->setRowHeight(30);
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle("AA6:AA$i")->getNumberFormat()
->setFormatCode('#,##0.00');
$objPHPExcel->getActiveSheet()->getStyle("AB6:AB$i")->getNumberFormat()
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
$objPHPExcel->getActiveSheet()->getStyle('A6:AB6')->applyFromArray(
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
$objPHPExcel->getActiveSheet()->setTitle('Excel_Clientes_por_Entes');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=relacion_Excel_Clientes_por_Entes_$numealeatorio.xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

$objWriter->save('php://output');
exit;
// fin de finalizar las propiedaes  realizacion de la hoja de excel 

?>

