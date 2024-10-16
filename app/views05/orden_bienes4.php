<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$comenta=$_POST['coment'];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
$matrixguar=$_SESSION['matriz'];
$quienda=$_SESSION['quienda'];
$quienrecibe=$_SESSION['quienrecib'];
$elcustodio=$_SESSION['custodio'];


echo "quien loda->$quienda quien loreci->$quienrecibe el custodio->$elcustodio<br>";

$cuantomatriz=count($matrixguar);
 for($i=0;$i<=$cuantomatriz;$i++){
       $cant=$matrixguar[$i][2];
       $idart=$matrixguar[$i][5];  
         echo "$idart-------$cant<br>";
}
