<?
include ("../../lib/jfunciones.php");
sesion();
$cedrif=strtoupper($_REQUEST['cedurif']);
$nomcl=strtoupper($_REQUEST['cnombre']);
$apecli=strtoupper($_REQUEST['capellido']);
$telecli=$_REQUEST['ctelef'];
$corcli=strtoupper($_REQUEST['cemail']);
$policli=$_REQUEST['cpoli'];
$lamaternida=$_REQUEST['maternid'];
$laedad=$_REQUEST['edaclien'];
$lainicial=$_REQUEST['iniclien'];
$lacuota=$_REQUEST['cuotclien'];
$cliengero=$_REQUEST['elgero'];
$tipocliente=$_REQUEST['clienfin'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$tablatemp="nusuar$elid";
$tablatemp1="caranusuar$elid";
$eli1="DROP TABLE IF EXISTS $tablatemp";
$reli1=ejecutar($eli1);
$eli2="DROP TABLE IF EXISTS $tablatemp1";
$reli1=ejecutar($eli2);
//consecutivo  de cotizacion
$elano=date("Y");
$quesucu=("select admin.id_sucursal from admin where admin.id_admin=$elid");
$repsucu=ejecutar($quesucu);
$datsucu=assoc_a($repsucu);
$sucursadmin=$datsucu['id_sucursal'];
$cuantcoti=("select tbl_cliente_cotizacion.id_cliente_cotizacion from 
                     tbl_cliente_cotizacion order by id_cliente_cotizacion desc limit 1;");
$repcuncoti=ejecutar($cuantcoti);                     
$datacuncoti=assoc_a($repcuncoti);
$numcuncoti=$datacuncoti['id_cliente_cotizacion'];
if(empty($numcuncoti)){
     $numcuncoti='1';    
    }else{
           $numcuncoti=$numcuncoti+1;
        }
$elnumcoti="$elano-$sucursadmin-$policli-$elid-$numcuncoti";        
//primero guardamos en la tabla tbl_cliente_cotizacion
$guardcotindi=("insert into tbl_cliente_cotizacion(no_cotizacion,nombres,apellidos,rif_cedula,email,celular,id_admin,inicial,cuotas,edad,tipocliente) 
                          values ('$elnumcoti','$nomcl','$apecli','$cedrif','$corcli','$telecli',$elid,$lainicial,$lacuota,'$laedad','$tipocliente');");
$repgcotindi=ejecutar($guardcotindi);                          
//segundo buscamos la cotizacion resien guardada!!!
$buscotiza=("select tbl_cliente_cotizacion.id_cliente_cotizacion from tbl_cliente_cotizacion where
                                no_cotizacion='$elnumcoti' and rif_cedula='$cedrif';");
$repbucotiza=ejecutar($buscotiza);
$datbuscotiza=assoc_a($repbucotiza);
$lacotizaclien=$datbuscotiza['id_cliente_cotizacion'];
//debemos hacer un ciclo para ver cuantas personas se registran segun las edades
      
        for($i=1;$i<=10;$i++){
                    $edahombre=$_REQUEST['edh'.$i];
                    $edadmujer=$_REQUEST['edm'.$i];
                   if(!empty($edahombre)){
                   list($cuantbenh,$posrangoh)=explode('@',$edahombre);
                   list($rangh1,$rangh2)=explode('-',$posrangoh);
                   $querybusprirango=("select primas.id_prima,primas.anual from primas,parentesco where
  primas.edad_inicio=$rangh1 and primas.edad_fin=$rangh2 and
  primas.id_parentesco=parentesco.id_parentesco and
  parentesco.genero=1 and primas.id_poliza=$policli and
  primas.id_parentesco<>18 limit 1;");
                    $repqueryburango=ejecutar($querybusprirango);
                    $datosbusrango=assoc_a($repqueryburango);
                    $laidprima=$datosbusrango[id_prima];
                    $lamontoa=$datosbusrango[anual];
                    $montodfipri=$lamontoa*$cuantbenh;
                    $guardoentblcaracoti=("insert into tbl_caract_cotizacion(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza) 
                                                                      values($lacotizaclien,'1',$laidprima,$cuantbenh,$montodfipri,$policli);");
                     $repguardcarcoti=ejecutar($guardoentblcaracoti);                                                 
                   }
                   if(!empty($edadmujer)){
                   list($cuantbenm,$posrangom)=explode('@',$edadmujer);
                   list($rangm1,$rangm2)=explode('-',$posrangom);
                   $querybusprirangom=("select primas.id_prima,primas.anual from primas,parentesco where
  primas.edad_inicio=$rangm1 and primas.edad_fin=$rangm2 and
  primas.id_parentesco=parentesco.id_parentesco and
  parentesco.genero=0 and primas.id_poliza=$policli and
  primas.id_parentesco<>17 and primas.id_parentesco<>9 limit 1;");
                    $repqueryburangom=ejecutar($querybusprirangom);
                    $datosbusrangom=assoc_a($repqueryburangom);
                    $laidprimam=$datosbusrangom[id_prima];
                    $lamontoam=$datosbusrangom[anual];
                    $montodfiprim=$lamontoam*$cuantbenm;
                    $guardoentblcaracotim=("insert into tbl_caract_cotizacion(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza) 
                                                                      values($lacotizaclien,'0',$laidprimam,$cuantbenm,$montodfiprim,$policli);");
                     $repguardcarcotim=ejecutar($guardoentblcaracotim);                                                 
                   }
            }
            //echo "------------$lamaternida";
            if(!empty($lamaternida)){
                   $busrangmate=("select primas.id_prima,primas.anual from primas,parentesco where
  primas.edad_inicio>=10 and primas.edad_fin<=60 and
  primas.id_parentesco=parentesco.id_parentesco and
  parentesco.genero=0 and primas.id_poliza=$lamaternida and
  primas.id_parentesco=9 limit 1;");
          //*echo "<br>------------>$busrangmate";
                  $repbusragmate=ejecutar($busrangmate);
                 $datosmate=assoc_a($repbusragmate);
                 $laprimaes=$datosmate['id_prima'];
                 $lamontprima=$datosmate['anual'];
                 $guardomaterni=("insert into tbl_caract_cotizacion(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza) 
                                                                      values($lacotizaclien,'0',$laprimaes,1,$lamontprima,$lamaternida);");
                //  echo "<br>--------->$guardomaterni";                                                    
                 $repmaterni=ejecutar($guardomaterni);  
                }
                //en esta ultima parte busco cual seria la prima para el titular dependiendo del genero
                if($cliengero==0){
                     $busprititu=("select primas.id_prima,primas.anual,primas.edad_inicio,primas.edad_fin 
                                               from primas where id_poliza=$policli and id_parentesco=17 order by edad_inicio ;");
              //        echo "<br>---------> $busprititu";                                                                             
                     $reptitu=ejecutar($busprititu);      
                     while($vercualprima=asignar_a($reptitu,NULL,PGSQL_ASSOC)){
                       $edadfin=$vercualprima['edad_fin'];
                       if($laedad<=$edadfin){
                           $laprimadeltitu=$vercualprima['id_prima'];
                           $montoprititu=$vercualprima['anual'];
                            break;
                           }
                   }
                   //*****Esto es nuevo del CATEEM*****//
                       if($tipocliente==1){
						$montoprititu=$montoprititu;   
					   }else{
						   $montoprititu=0;
						   }
                      //****Fin de CATEEM******//
                   $guardoeltitucoti=("insert into tbl_caract_cotizacion(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza) 
                                                                      values($lacotizaclien,'0',$laprimadeltitu,1,$montoprititu,$policli);");
                   $repguardocotititu=ejecutar($guardoeltitucoti);       
                   $guardogentitu=("update tbl_cliente_cotizacion set genero='0' where id_cliente_cotizacion=$lacotizaclien");
                   $repguardogene=ejecutar($guardogentitu);
               }else{
                     $busprititu=("select primas.id_prima,primas.anual,primas.edad_inicio,primas.edad_fin 
                                               from primas where id_poliza=$policli and id_parentesco=18 order by edad_inicio ;");
                   //     echo "<br>---------> $busprititu";                        
                     $reptitu=ejecutar($busprititu);                                                                         
                      while($vercualprima=asignar_a($reptitu,NULL,PGSQL_ASSOC)){
                       $edadfin=$vercualprima['edad_fin'];
                       if($laedad<=$edadfin){
                           $laprimadeltitu=$vercualprima['id_prima'];
                           $montoprititu=$vercualprima['anual'];
                            break;
                           }
                      }
                     //*****Esto es nuevo del CATEEM*****//
                       if($tipocliente==1){
						$montoprititu=$montoprititu;   
					   }else{
						   $montoprititu=0;
						   }
                      //****Fin de CATEEM******//
                      $guardoeltitucoti=("insert into tbl_caract_cotizacion(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza) 
                                                                      values($lacotizaclien,'1',$laprimadeltitu,1,$montoprititu,$policli);");
                      // echo "<br>$guardoeltitucoti<br>";                                               
                       $repguardocotititu=ejecutar($guardoeltitucoti);           
                       $guardogentitu=("update tbl_cliente_cotizacion set genero='1' where id_cliente_cotizacion=$lacotizaclien");
                       $repguardogene=ejecutar($guardogentitu);
                   }
                       
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha registrado la cotizacion numero $elnumcoti con la cedula del cliente $cedrif y nombre $nomcl  $apecli";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha registrado exitosamente la cotizaci&oacute;n <?echo $elnumcoti;?></td>  
         <td class="titulo_seccion"><label title="Imprimi planilla solicitud" class="boton" style="cursor:pointer" onclick="planillasolicitu('<?echo $lacotizaclien?>')" >Imprimir</label></td>
     </tr>
    </table>	
