<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include ("../../lib/jfunciones.php");
sesion();

///recepcion de variables

$TikeraTitulo=$_POST['TikeraTitulo'];
$CantTikeras=$_POST['CantidadTikeras'];
$CodTickeraInicio=$_POST['CodTickeraInicio'];
$CodTickeraFin=$_POST['CodTickeraFin'];
$InicialSerie=$_POST['InicialSerie'];
$NotaTicket=$_POST['NotaTicket'];
$cantTicket=$_POST['cantTicket'];
$contadorTikeras=$RangoInicioTicket;
$fecha=date("Y-m-d");

for ($i=$CodTickeraInicio; $i <= $CantTikeras; $i++) {
//CREAR EL SERIAL DE LA TICKERA
  $cantidadCaracter=strlen($i);
  $cuantoFaltan=8-$cantidadCaracter;
  $Ceros=str_repeat('0',$cuantoFaltan);
  echo"cantidad $cantidadCaracter - $cuantoFaltan";
  $serialtikera=$InicialSerie.'-'.$Ceros.$i;

//FIN
$verificaTikera=("select * from tbl_tickeras where identificador='$InicialSerie' and nun_tikera='$i'");
$VerfTikera=ejecutar($verificaTikera);
$regencontados=num_filas($VerfTikera);
if($regencontados>0)
  {
    echo "TIKERA RESGISTRADA $serialtikera<br>";
  }else{

    $regTikerasql=("INSERT INTO tbl_tickeras (nun_tikera,catidad_tike,identificador,fecha_registro,titulo_tickera,descripcion,serial_tikera)
    VALUES ('$i','$cantTicket','$InicialSerie','$fecha','$TikeraTitulo','$NotaTicket','$serialtikera')");
  $reppoli=ejecutar($regTikerasql);
  echo "REGISTRAR $serialtikera<br>";

  }
}
echo"<h3> Se ha creado $contadorTikeras </h3>";

?>
