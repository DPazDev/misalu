<?
include ("../../lib/jfunciones.php");
sesion();
$cedulaben=$_REQUEST['bencedula'];
$cualdiv=$_REQUEST['ndiv'];
$buscoben=("select * from clientes where clientes.cedula='$cedulaben'");
$repbusben=ejecutar($buscoben);
$siben=num_filas($repbusben);
if($siben>=1){
	$datadbenef=assoc_a($repbusben);
	$nomb1=$datadbenef['nombres'];
	$apell1=$datadbenef['apellidos'];
	$nacib1=$datadbenef['fecha_nacimiento'];
	?>
  <label class="boton" style="cursor:pointer" onclick="verbenfcontrato('<?echo $nomb1?>','<?echo $apell1?>','<?echo $nacib1?>','<?echo $cualdiv?>')" >V</label>
<?}
?>
