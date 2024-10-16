<?php
//scrip que procesa lo datos pra mostrar los estudios hechos por los doctores en un intervalo de tiempo (fecha y hora)
//ini_set('error_reporting', E_ALL-E_NOTICE);
//ini_set('display_errors', 1);

//scrip para mostrar los provedores de un product, precio y tipo de producto
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;


$fech1=$_POST['finicio'];
$fech2=$_POST['ffin'];

$horaini=$_POST['hini'];
$horafin=$_POST['hfin'];
if($horaini==$horafin){
$hini=$horaini.':00:00';
$hfin=$horafin.':59:59';	
	}else {
//tranformar a
$hini=$horaini.':00:00';
$hfin=$horafin.':00:00';
if($horaini=='1' and $horafin=='24')
	{$hini='00:00:00';
	$hfin=$horafin.':00:00';}
}


if($horaini>$horafin) {
	if($horaini<='12'){
	$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '24::' or tbl_informedico.horaegreso  BETWEEN '00:00:00' AND '$hfin' or tbl_informedico.horaegreso IS NULL)";
$nulos=3;
	}
	else {
		$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '24::' or tbl_informedico.horaegreso  BETWEEN '00:00:00' AND '$hfin')";
$nulos=4;
		}
}
else 
if( $horaini<='12' && $horafin>='7' )
{$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '$hfin' or tbl_informedico.horaegreso IS NULL)";
$nulos=1;
	}
	else {$horaNULL=" AND tbl_informedico.horaegreso BETWEEN '$hini' AND '$hfin'";
	$nulos=2;
	} 


$docsql = "SELECT 
  tbl_informedico.id_admin, 
  admin.nombres as nomb, 
  admin.apellidos as apell
FROM 
  public.tbl_informedico, 
  public.admin
WHERE 
  admin.id_admin = tbl_informedico.id_admin AND
  tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' $horaNULL  
  group by tbl_informedico.id_admin,nomb,apell;";
  
  $docent2= ejecutar($docsql);
 //echo "<h2>$docsql</h2>";

?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<?php
echo"	<tr>
	<td class='tdtitulos'>Nro</td>
<!-- <td class='tdtitulos'>ID medico</td> -->
	<td class='tdtitulos'>MEDICO</td>
	<td class='tdtitulos'>cantida de informes</td>
	<td class='tdtitulos'>Laboratorios</td>
	<td class='tdtitulos'>Ultrasonidos</td>
	<td class='tdtitulos'>Radiologia</td>
	<td class='tdtitulos'>Est. Especiales</td>
	<td class='tdtitulos'>Datalles</td>
	
	</tr>	";
	
	$con=0;//contador de elementos del bucle
		while($medinf = asignar_a($docent2)){//Bucle para Mostrar los doctores
//Cantidad de Estudios realiados por el medico
//CANTIDAD TOTAL DE ESTUDIOS 
$sqlestreliz="SELECT 
 count(*) as nest
FROM 
  public.tbl_informedico
WHERE 
   tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' and tbl_informedico.id_admin='$medinf[id_admin]' $horaNULL ;";
 
  
$estudioR=ejecutar($sqlestreliz);	
	$estr=asignar_a($estudioR);


//laborotorio cantidad de pedida al dia			
$sqllab="SELECT 
  count(tbl_informedico.laboratorio) as lab 
 FROM 
  public.tbl_informedico
WHERE tbl_informedico.id_admin='$medinf[id_admin]' AND tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' and tbl_informedico.laboratorio<>'' $horaNULL ;";
$conlab=ejecutar($sqllab);	
	$lab=asignar_a($conlab);	

// ultrasonido cantidad de ultrasonidos mandados

$sqlult="SELECT 
  count(tbl_informedico.ultrasonido) as ultra 
 FROM 
  public.tbl_informedico
WHERE 
tbl_informedico.id_admin='$medinf[id_admin]' AND tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' AND tbl_informedico.ultrasonido<>''$horaNULL ;";
	$ult=asignar_a(ejecutar($sqlult));	
	
//cantidad de radiologia mandadas
$sqlrad="SELECT 
  count(tbl_informedico.radiologia) as rad
 FROM 
  public.tbl_informedico
WHERE 
tbl_informedico.id_admin='$medinf[id_admin]' AND tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' AND tbl_informedico.radiologia<>'' $horaNULL ;";
  $rad=asignar_a(ejecutar($sqlrad));
  
// estudios especiales mandados
$sqlesp="
SELECT 
  count(tbl_informedico.estudiosespe) as esp
 FROM 
  public.tbl_informedico
WHERE 
tbl_informedico.id_admin='$medinf[id_admin]' AND tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2'AND tbl_informedico.estudiosespe<>'' $horaNULL ;";
  $esp=asignar_a(ejecutar($sqlesp));
  
  $con=$con+1;	//contador de repeticiones
echo"	<tr>

	<td class='tdcampos'>$con</td>
<!--<td class='tdcampos'>$medinf[id_admin]</td> -->
	<td class='tdcampos'>$medinf[nomb] $medinf[apell]</td>
	<td class='tdcampos'>$estr[nest]</td>	
	
	<td class='tdcampos'>$lab[lab]</td>
	<td class='tdcampos'>$ult[ultra]</td>
	<td class='tdcampos'>$rad[rad]</td>
	<td class='tdcampos'>$esp[esp]</td>
	<td class='tdcampos'><!--boton de busqueda  -->   ";

//contando totales finales

$catinfT=$catinfT+$estr[nest];//total informes
$cantinflabT=$cantinflabT+$lab[lab];//total laboratorios
$cantinfultraT=$cantinfultraT+$ult[ultra];//total ultrasonidos
$cantinfradT=$cantinfradT+$rad[rad];//radiologias Totales
$cantinfesp=$cantinfesp+$esp[esp];//total Estudios Especiales
	 ?> 
	 
 <label class='boton' style='cursor:pointer'  onclick="frepormedicodetalles('<?php echo "$medinf[id_admin]"; ?>','<?php echo "$fech1"; ?>','<?php echo "$fech2"; ?>','<?php echo "$hini"; ?>','<?php echo "$hfin"; ?>','<?php echo "$nulos"; ?>');return false;" >Detalles</label></td>
		</tr>
			
	<?php
	}
?>
<tr BGCOLOR='#00FF11'>
<td colspan='2' class='tdtitulos'>TOTALES</td>
<td class='tdtitulos'><?php echo "$catinfT";?></td> 
<td class='tdtitulos'><?php echo "$cantinflabT";?></td> 
<td class='tdtitulos'><?php echo "$cantinfultraT";?></td> 
<td class='tdtitulos'><?php echo "$cantinfradT";?></td> 
<td class='tdtitulos'><?php echo "$cantinfesp";?></td> 
</tr>
</table>

