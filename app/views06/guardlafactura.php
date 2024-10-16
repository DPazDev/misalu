<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
$proceso=$_POST['elproceso'];
$fechafact=$_POST['lafechafa'];
$facturnum=$_POST['lafactura'];
$elnumcontrol=$_POST['elcontro'];
$arrmontreser=$_POST['montrese'];
$cuantosmontr= explode(',',$arrmontreser); 
$arrmontacept=$_POST['montacep'];
$cuantosmontac=explode(',',$arrmontacept);
$arrgastostb=$_POST['elgastb'];
$cuantogastb=explode(',',$arrgastostb);
$posi=1;
$posi1=1;
$posi2=1;

foreach ( $cuantosmontr as $cuantosmontr ) 
{ 
   $cajac[$posi]=$cuantosmontr;
   $posi++;
 }
foreach ( $cuantosmontac as $cuantosmontac ) 
{ 
   $cajamac[$posi1]=$cuantosmontac;
   $posi1++;
 } 
foreach ($cuantogastb as $cuantogastb) 
{ 
   $cajagtb[$posi2]=$cuantogastb;
   $posi2++;
 }  
$cuantos=count($cajac);

for($i=0;$i<=$cuantos;$i++){
	$lacaactual=$cajac[$i];
	$lacamoace=$cajamac[$i];
	$lacabtb=$cajagtb[$i];
	  if(($lacaactual>0) && ($lacamoace>0) && ($lacabtb>0)){
	     $quergtb=("update gastos_t_b set factura='$facturnum',monto_reserva='$lacaactual',monto_aceptado='$lacamoace'  
                             where id_proceso=$proceso and id_gasto_t_b=$lacabtb;"); 
		 $repquegtb=ejecutar($quergtb);					 
		} 
}
    $actualporceso=("update procesos set id_estado_proceso=7,factura_final='$facturnum',control_factura='$elnumcontrol',fecha_factura_final='$fechafact' 
                                 where id_proceso=$proceso;");
	$repactproceso=ejecutar($actualporceso);				
	 $mensaje="El usuario $elus ha procesado el proceso con id_proceso=$proceso"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog); 
?>