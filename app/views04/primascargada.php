<?php
include ("../../lib/jfunciones.php");
include ("arreglosprimas.php");
sesion();
$elparen=$_POST['parentes'];
$buscarparn=("select parentesco.parentesco from parentesco where parentesco.id_parentesco=$elparen");
$repbuscarpan=ejecutar($buscarparn);
$datparn=assoc_a($repbuscarpan);
$sparen=$datparn['parentesco'];
$arrayelprimdes=explode(',',$_POST['primadescr']);
$arrayeledadini=explode(',',$_POST['iniedad']);
$arrayeledadfin=explode(',',$_POST['finedad']);
$arrayelanual=explode(',',$_POST['anualpri']);
$arrayelsemespri=explode(',',$_POST['semespri']);
$arrayelstrmes=explode(',',$_POST['trimpri']);
$arrayelmenst=explode(',',$_POST['menspri']);

$elidpolizas=$_POST['idpoliza'];
$buscarpol=("select polizas.nombre_poliza from polizas where polizas.id_poliza=$elidpolizas;");
$repbuspoli=ejecutar($buscarpol);
$ladata=assoc_a($repbuspoli);
$nompoliza=$ladata['nombre_poliza'];

$contCampo=count($arrayelprimdes);
$YaRegistardo=0;
for($j=0;$j<$contCampo;$j++)
{ $registrar=true;
  $paso1=$_SESSION['pasopedido1'];
  $matriz1=&$_SESSION['matriz1'];
  $elprimdes=$arrayelprimdes[$j];
  $eledadini=$arrayeledadini[$j];
  $eledadfin=$arrayeledadfin[$j];
  $elanual=$arrayelanual[$j];
  $elsemespri=$arrayelsemespri[$j];
  $elstrmes=$arrayelstrmes[$j];
  $elmenst=$arrayelmenst[$j];
  if($elprimdes=='' || $elprimdes==NULL || $elprimdes==" ")
  {$elprimdes="$sparen entre $eledadini y $eledadfin años";}
  $contActual=count($matriz1);//contar cuantos en matriz
  if($contActual>0)
  {
    foreach ($matriz1 as list($par,$desc,$eini,$efin)){
      if($par==$sparen && $eini==$eledadini && $efin==$eledadfin)
      {$registrar=false;
        $YaRegistardo++;
       break;
      }
    }
  }

  if($registrar==true)
    {$lamatix1=cargarMatriprimero($matriz1,$paso1,$sparen,$elprimdes,$eledadini,$eledadfin,
                                                    $elanual,$elsemespri,$elstrmes,$elmenst);
    }else{$lamatix1=$matriz1; }
}
///ERROR REGISTROS REPETIDOS
if($YaRegistardo>0){?>
    <table class="tabla_cabecera3" which="100%" cellpadding=0 cellspacing=0>
      <tr>
        <td class="titulo_seccion">ALGUNOS PARENTESCOS Y RANGOS YA SE ENCUENTRAR REGISTRADOS</td>
      </tr>
    </table>
    <?php
}

$cuantomatriz=count($lamatix1);
if($cuantomatriz>=1){
 ?>

    <table class="tabla_cabecera3 colortable"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion">Primas cargada a la p&oacute;liza <?echo $nompoliza?></td>
     </tr>
    </table>
    <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Lin.</th>
                 <th class="tdtitulos">Parentesco.</th>
                 <th class="tdtitulos">Descripci&oacute;n.</th>
                 <th class="tdtitulos">Edad Ini.</th>
                 <th class="tdtitulos">Edad Fin.</th>
                 <th class="tdtitulos">Anual.</th>
                 <th class="tdtitulos">Semestral.</th>
                 <th class="tdtitulos">Trimestral.</th>
                 <th class="tdtitulos">Mensual.</th>
			  </tr>
              <?
			   $lin=1;
			      for($i=1;$i<=$cuantomatriz;$i++){
                          $paren=$lamatix1[$i][0];
                          $des=$lamatix1[$i][1];
                          $eda1=$lamatix1[$i][2];
                          $eda2=$lamatix1[$i][3];
                          $anual=$lamatix1[$i][4];
                          $semes=$lamatix1[$i][5];
                          $trmi=$lamatix1[$i][6];
                          $mensu=$lamatix1[$i][7];
                      if($paren<>""){
                   echo"<tr>
                          <td class=\"tdcampos\">$lin</td>
                          <td class=\"tdcampos\">$paren</td>
                          <td class=\"tdcampos\">$des</td>
                          <td class=\"tdcampos\">$eda1</td>
                          <td class=\"tdcampos\">$eda2</td>
                          <td class=\"tdcampos\">$anual</td>
                         <td class=\"tdcampos\">$semes</td>
                          <td class=\"tdcampos\">$trmi</td>
                          <td class=\"tdcampos\">$mensu</td>
                        </tr>";

				$lin++;
				}
               }
               echo"</table>";
}?>
<input type='hidden' id='finpoliza' value='<?echo $elidpolizas?>'>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=1 class="titulo_seccion" title="Guardar las primas cargadas"><label id="botprimafin" class="boton" style="cursor:pointer" onclick="guarda_primas_final()" >Guardar</label></td>
	</tr>
 </table>
 <img alt="spinner" id="spinnerPFin" src="../public/images/esperar.gif" style="display:none;" />
