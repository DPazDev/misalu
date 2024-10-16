<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$ladepen=$_POST['nomdepen'];
$depeid=$_POST['eliddp'];
$almasi=$_POST['siesalma'];
$almano=$_POST['noesalma'];
if(($almasi=='null') && ($almano==0)){
	$sino=0;
}else{
	$sino=1;
	}
$buscardepen=("select * from tbl_dependencias where id_dependencia='$depeid';");
$repbuscardepen=ejecutar($buscardepen);
$haydepen=num_filas($repbuscardepen);
if ($haydepen>=1){
	$actualdepen=("update tbl_dependencias set dependencia=upper('$ladepen'),esalmacen=$sino where id_dependencia='$depeid';");
	$repactualdepen=ejecutar($actualdepen);
}else{	
       $regidepen=("insert into tbl_dependencias(dependencia,fecha_hora_creado,esalmacen) values(upper('$ladepen'),'$fecha',$sino);");
       $repuedepen=ejecutar($regidepen);
	}   
?>