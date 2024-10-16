<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$totdopo=("select * from titulares_polizasp");
$reptodopo=ejecutar($totdopo);
while($lotitpoli=asignar_a($reptodopo,NULL,PGSQL_ASSOC)){
    $titu=$lotitpoli['id_titular'];
    $poli=$lotitpoli['id_poliza'];
    $fecre=$lotitpoli['fecha_creado'];
    $hocre=$lotitpoli['hora_creado'];
    $buscoex=("select titulares_polizas.id_titular_poliza from titulares_polizas where id_titular=$titu and id_poliza=$poli and 
                         fecha_creado='$fecre';");
     $repbucoex=ejecutar($buscoex);
     $cuantex=num_filas($repbucoex);
     echo "Numero de filas->$cuantex<br>";
     if($cuantex==0){
         $guardotp=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) values($titu,$poli,'$fecre','$hocre');");
         $repguardotp=ejecutar($guardotp);
         echo "Se guardo el titular $titu<br>";
     }
 }   