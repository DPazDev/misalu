<?php
//scrip para mostrar los provedores de un product, precio y tipo de producto v0
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;

//ini_set('error_reporting', E_ALL-E_NOTICE);//monstrar errores
//ini_set('display_errors', 1);//monstrar errores

$fech1=$_POST['fchi'];
$fech2=$_POST['ffin'];
$id_admin=$_POST['id'];
$hini=$_POST['hoi'];
$hfin=$_POST['hof'];
$nulos=$_POST['n'];
//explotar hora inicio para convercion a 12 horas
$ni=explode(':', $hini);
$hi=$ni[0];
//explotar hora Fin para convercion a 12 horas
$nf=explode(':', $hfin);
$hf=$nf[0];
$dochoras=array(1=>'01',2=>'02',3=>'03',4=>'04',5=>'05',6=>'06',7=>'07',8=>'08',9=>'09',10=>'10',11=>'11',12=>'12',13=>'01',14=>'02',15=>'03',16=>'04',17=>'05',18=>'06',19=>'07',20=>'08',21=>'09',22=>'10',23=>'11',24=>'12',00=>'12');
//agregar explotados inicio
$hoiconv=$dochoras[$hi];

if($hi=='00')//mostrar 01 AM cuando sea un turno de 24 horas
$hoiconv='01';

$mostrarhi=$hoiconv.':'.$ni[1].':'.$ni[2];
//agregar explotados  fin
$hofconv=$dochoras[$hf];
$mostrarhf=$hofconv.':'.$nf[1].':'.$nf[2];

///horario de Fecha de inicio Antes Meridien(AM) o Pasado Meridien(PM)
if($hini>=00 && $hini<=12) {
$Meridieni='AM';	
	}
	else {$Meridieni='PM';
	}
///horario de Fecha de Fin Antes Meridien(AM) o Pasado Meridien(PM)
if($hfin>=00 && $hfin<=12) {
$Meridienf='AM';	
	}
	else {$Meridienf='PM';	
	}	
	
//echo "<h1>feh: $fech1 fec2: $fech2 ID:$id_admin hi: $hi Hf:$hf  nul:$nulos </h1>";

//echo"<h1>$nulos hora $mostrarhi<br></h1>";
if($nulos==3) {
	$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '24::' or tbl_informedico.horaegreso  BETWEEN '00:00:00' AND '$hfin' or tbl_informedico.horaegreso IS NULL)";
}else
if($nulos==4) {
		$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '24::' or tbl_informedico.horaegreso  BETWEEN '00:00:00' AND '$hfin')";
		}
else
if($nulos==1){$horaNULL="AND (tbl_informedico.horaegreso BETWEEN '$hini' AND '$hfin' or tbl_informedico.horaegreso IS NULL)";
	
	}
	else {$horaNULL=" AND tbl_informedico.horaegreso BETWEEN '$hini' AND '$hfin'";}
	
	
$detasql = "SELECT 
tbl_informedico.id_proceso,
 admin.nombres, 
  admin.apellidos,
tbl_informedico.id_admin,
  tbl_informedico.diagnostico, 
  tbl_informedico.laboratorio, 
  tbl_informedico.ultrasonido, 
  tbl_informedico.radiologia, 
  tbl_informedico.estudiosespe, 
  tbl_informedico.fechacreado,
  tbl_informedico.horaegreso, 
  tbl_informedico.indicandole,
  tbl_informedico.fechacreado,
  tbl_informedico.horaegreso
FROM 
  public.tbl_informedico,admin where admin.id_admin = tbl_informedico.id_admin AND
  tbl_informedico.fechacreado BETWEEN '$fech1' AND '$fech2' AND admin.id_admin='$id_admin' $horaNULL ;";   
  
$infmed= ejecutar($detasql);
//echo "<h2>$detasql</h2>";
//consultar datos del doctor 
$sqlDoc="SELECT 
  admin.nombres, 
  admin.apellidos, 
  admin.id_admin
FROM 
  public.admin
WHERE 
  admin.id_admin = '$id_admin';";
$doc=asignar_a(ejecutar($sqlDoc));
?>

<table class="tabla_cabecera5" border="1" cellpadding=1 cellspacing=2>

<?php
echo"	
<tr><td colspan='9' class='titulo_seccion'>informes realizados por:<br>$doc[nombres] $doc[apellidos] entre la fecha $fech1 hasta $fech2 <br>desde $mostrarhi $Meridieni hasta $mostrarhf $Meridienf</td>
<tr>
	<td class='tdtitulos'>Nro</td>
	<!-- <td class='tdtitulos'>Proceso</td>-->
	<td class='tdtitulos'>Fecha y Hora</td>
	<td class='tdtitulos'>Diagnostico</td>
	<td class='tdtitulos'>Laboratorio</td>
	<td class='tdtitulos'>Ultrasonido</td>
	<td class='tdtitulos'>Radiologia</td>
	<td class='tdtitulos'>Est. Especiales</td>
	<td class='tdtitulos'>Indicandole</td>
</tr>	";
	
	$con=0;//contador de resultados
		while($ifmed = asignar_a($infmed)){//Bucle para Mostrar resultados
//Cantidad de Estudios realiados por el medico
  
	if($ifmed[diagnostico]==''){$diagnostico=0;}  else {$diagnostico=$ifmed[diagnostico];}
	
	if($ifmed[laboratorio]==''){	$laboratorio=0;}else {$laboratorio=$ifmed[laboratorio];}
		
	
	if($ifmed[ultrasonido]==''){$ultrasonido=0;} else {$ultrasonido=$ifmed[ultrasonido];}	

	if($ifmed[radiologia]==''){$radiologia=0;}   else {$radiologia=$ifmed[radiologia];}

	if($ifmed[estudiosespe]==''){$estudiosespe=0;}else {$estudiosespe=$ifmed[estudiosespe];}

	if($ifmed[fechacreado]==''){$fechacreado=0;}else{$fechacreado=$ifmed[fechacreado];}
	if($ifmed[indicandole]==''){$indicandole=0;}else{$indicandole=$ifmed[indicandole];}  
	if($ifmed[horaegreso]==''){$hora=0;}else{$hora=$ifmed[horaegreso];}  
  
  $con=$con+1;	//contador de repeticiones
echo"	<tr>
	<td class='tdcampos'>$con</td>
	<!-- <td class='tdcampos'>$ifmed[id_proceso]</td>-->
	<td class='tdcampos'>$fechacreado \n ($hora )	</td>
	<!-- <td class='tdcampos'>$ifmed[id_admin]</td>-->
	<td class='tdcampos'>$diagnostico</td>
	<td class='tdcampos'>$laboratorio</td>	
	<td class='tdcampos'>$ultrasonido</td>
	<td class='tdcampos'>$radiologia</td>
	<td class='tdcampos'>$estudiosespe</td>
	<td class='tdcampos'>$indicandole</td>
</tr>	";
			
		}//*/
?> 
</table>