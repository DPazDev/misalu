<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$id_poliza=$_POST['lapolizaid'];
$matrizfin=$_SESSION['matriz1'];
$cuantomatriz=count($matrizfin);
$versihprima=("select primas.id_prima from primas where id_poliza=$id_poliza;");
$repversihprima=ejecutar($versihprima);
$cuanverprima=num_filas($repversihprima);

for($i=1;$i<=$cuantomatriz;$i++){
	$paren=$matrizfin[$i][0];
        $des=strtoupper($matrizfin[$i][1]);
        $eda1=$matrizfin[$i][2];
        $eda2=$matrizfin[$i][3];
        $anual=$matrizfin[$i][4];
        $semes=$matrizfin[$i][5];
        $trmi=$matrizfin[$i][6];
        $mensu=$matrizfin[$i][7];
	$buscoprim=("select primas.*,parentesco.parentesco from primas,parentesco where
                     primas.id_poliza=$id_poliza and primas.id_parentesco=parentesco.id_parentesco and
                     parentesco.parentesco='$paren' and edad_inicio='$eda1' and edad_fin='$eda2'");
        $repbuscprim=ejecutar($buscoprim);
        $cuantbusqprim=num_filas($repbuscprim);
        if($cuantbusqprim==0){
        $queparente=("select parentesco.id_parentesco from parentesco where parentesco.parentesco='$paren'");
		 $repdelparen=ejecutar($queparente);
		 $dataparen=assoc_a($repdelparen);
		 $elidparens=$dataparen['id_parentesco'];
        $guardarlaprimas=("insert into primas(id_poliza,id_parentesco,descripcion,anual,semestral,trimestral,mensual,
           fecha_creado,hora_creado,edad_inicio,edad_fin)
           values ($id_poliza,$elidparens,'$des','$anual','$semes','$trmi','$mensu','$fecha','$hora',$eda1,$eda2);");
		$repguardalapropiedad=ejecutar($guardarlaprimas);
		}
}
/**********************************/
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha agregado un prima con el id_poliza=$id_poliza";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=1 class="titulo_seccion">Las primas se han registrado exitosamente!!</td>
        <td  class="titulo_seccion" title="Salir"><label class="boton" style="cursor:pointer" onclick="ira(); return false;" >Salir</label><label class="boton" style="cursor:pointer" onclick="primasSelecionPolizas('<?php echo $id_poliza;?>'); return false;" >REGRESAR A PRIMAS</label></td>
	</tr>
  </table>
