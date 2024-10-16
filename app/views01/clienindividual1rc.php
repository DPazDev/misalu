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
///array edades
$edHom=explode(',',$_REQUEST['edh']);
$edMuj=explode(',',$_REQUEST['edm']);
$edrag=explode(',',$_REQUEST['edr']);

/////pasar a un vararray

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
$numcuncoti=1;
$elnumcoti="$elano-$sucursadmin-$policli-$elid-$numcuncoti";

$creotabla=("create table $tablatemp(id_cliente_cotizacion integer,no_cotizacion varchar(140),
             nombres varchar(240), apellidos varchar(240),rif_cedula varchar(30),email varchar(240),
             celular varchar(240),id_admin integer, inicial double precision,cuotas integer, edad varchar(5),
             genero varchar(5),fecha_creado date,tipocliente varchar);");
//echo "$creotabla <br>";
$cretabala=ejecutar($creotabla);
$creotabla1=("create table $tablatemp1(id_cliente_cotizacion integer,id_prima integer,cantidad integer,
               montoprima double precision,sexo varchar(5),id_poliza integer);");
$cretabala1=ejecutar($creotabla1);
//echo "$creotabla1 <br>";
//primero guardamos en la tabla tbl_cliente_cotizacion
$guardcotindi=("insert into $tablatemp(id_cliente_cotizacion,no_cotizacion,nombres,apellidos,rif_cedula,email,celular,id_admin,inicial,cuotas,edad,tipocliente)
                          values ($numcuncoti,'$elnumcoti','$nomcl','$apecli','$cedrif','$corcli','$telecli',$elid,$lainicial,$lacuota,'$laedad','$tipocliente');");
//echo "$guardcotindi <br>";
$repgcotindi=ejecutar($guardcotindi);
//segundo buscamos la cotizacion resien guardada!!!
$buscotiza=("select $tablatemp.id_cliente_cotizacion from $tablatemp where
                                no_cotizacion='$elnumcoti' and rif_cedula='$cedrif';");
//echo "$buscotiza <br>";
$repbucotiza=ejecutar($buscotiza);
$datbuscotiza=assoc_a($repbucotiza);
$lacotizaclien=$datbuscotiza['id_cliente_cotizacion'];
//echo "-------------------$lacotizaclien-----------------";
//debemos hacer un ciclo para ver cuantas personas se registran segun las edades
$cunatosEdades=count($edHom);

        for($i=0;$i<$cunatosEdades;$i++){
                    $edahombre=$edHom[$i];
                    $edadmujer=$edMuj[$i];
                    $edadrago=$edrag[$i];
                  //echo "$edahombre-----------$edadmujer-----$edadrago<br>";
                   if(!empty($edahombre)){
                   $posrangoh=$edadrago;
                   $cuantbenh=$edahombre;
                   //echo "Cuantos hay-->$cuantbenh ---------la edad------->$posrangoh<br>";
                   list($rangh1,$rangh2)=explode('-',$posrangoh);
	                 if($rangh1>=80 or $rangh1==80) {
                   //si el rango de edad sobrepasa los 80 Modificar el rango $rangh2>89
                   //RANGO DE BARONES
                   	$rangoedadH="primas.edad_inicio=$rangh1 and primas.edad_fin>$rangh1";
                   }else {
                   	//rangos Normales EDAD INICO X y EDAD inicio FIN
                   	//RANGO DE BARONES
                   	$rangoedadH="primas.edad_inicio=$rangh1 and primas.edad_fin=$rangh2";
                   }

                  $querybusprirango=("select primas.id_prima,primas.anual from primas,parentesco where
  $rangoedadH and
  primas.id_parentesco=parentesco.id_parentesco and
  parentesco.genero=1 and primas.id_poliza=$policli and
  primas.id_parentesco<>18 limit 1;");

                    //echo "Es hombre $querybusprirango<br>";
                    $repqueryburango=ejecutar($querybusprirango);
                    $datosbusrango=assoc_a($repqueryburango);
                    $laidprima=$datosbusrango[id_prima];
                    $lamontoa=$datosbusrango[anual];
                    $montodfipri=$lamontoa*$cuantbenh;
                    $guardoentblcaracoti=("insert into $tablatemp1(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza)
                                                                      values($lacotizaclien,'1',$laidprima,$cuantbenh,$montodfipri,$policli);");
                    //echo "$guardoentblcaracoti<br>";
                     $repguardcarcoti=ejecutar($guardoentblcaracoti);
                   }
                   if(!empty($edadmujer)){
                   $posrangom=$edadrago;
                   $cuantbenm=$edadmujer;
                   //echo "Cuantos hay M-->$cuantbenm ---------la edad------->$posrangom<br>";
                   list($rangm1,$rangm2)=explode('-',$posrangom);
//SENTENCIA DE EDAD MUJERES
if($rangm1>=80 or $rangm1==80) {
                   //si el rango de edad sobrepasa los 80 Modificar el rango $rangh2>89
                   //RANGO DE BARONES
                   	$rangoedadM="primas.edad_inicio=$rangm1 and primas.edad_fin>$rangm1";
                   }else {
                   	//rangos Normales EDAD INICO X y EDAD inicio FIN
                   	//RANGO DE BARONES
                   	$rangoedadM="primas.edad_inicio=$rangm1 and primas.edad_fin=$rangm2";
                   }

  $querybusprirangom=("select primas.id_prima,primas.anual from primas,parentesco where
  $rangoedadM and primas.id_parentesco=parentesco.id_parentesco and
  parentesco.genero=0 and primas.id_poliza=$policli and
  primas.id_parentesco<>17 and primas.id_parentesco<>9 limit 1;");

                    //echo "Es mujer $querybusprirangom<br>";
                    $repqueryburangom=ejecutar($querybusprirangom);
                    $datosbusrangom=assoc_a($repqueryburangom);
                    $laidprimam=$datosbusrangom[id_prima];
                    $lamontoam=$datosbusrangom[anual];
                    $montodfiprim=$lamontoam*$cuantbenm;
                    $guardoentblcaracotim=("insert into $tablatemp1(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza)
                                                                      values($lacotizaclien,'0',$laidprimam,$cuantbenm,$montodfiprim,$policli);");
                     //echo "$guardoentblcaracotim<br>";
                     $repguardcarcotim=ejecutar($guardoentblcaracotim);
                   }
            }
            //echo "------------$lamaternida";
            if(!empty($lamaternida)){
                $busrangmate=("select primas.id_prima,primas.anual from primas,parentesco where
                primas.edad_inicio>=10 and primas.edad_fin<=59 and
                primas.id_parentesco=parentesco.id_parentesco and
                parentesco.genero=0 and primas.id_poliza=$lamaternida and
                primas.id_parentesco=9 limit 1;");
                //echo "<br>Maternidad------------>$busrangmate";
                  $repbusragmate=ejecutar($busrangmate);
                 $datosmate=assoc_a($repbusragmate);
                 $laprimaes=$datosmate['id_prima'];
                 $lamontprima=$datosmate['anual'];
                 $guardomaterni=("insert into $tablatemp1(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza)
                                                                      values($lacotizaclien,'0',$laprimaes,1,$lamontprima,$lamaternida);");
                 //echo "<br>--------->$guardomaterni";
                 $repmaterni=ejecutar($guardomaterni);
                }
                //en esta ultima parte busco cual seria la prima para el titular dependiendo del genero
                if($cliengero==0){
                     $busprititu=("select primas.id_prima,primas.anual,primas.edad_inicio,primas.edad_fin
                                               from primas where id_poliza=$policli and id_parentesco=17 order by edad_inicio ;");
                     //echo "<br>---------> $busprititu";
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
                   $guardoeltitucoti=("insert into $tablatemp1(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza)
                                                                      values($lacotizaclien,'0',$laprimadeltitu,1,$montoprititu,$policli);");
                    //echo $guardoeltitucoti;
                   $repguardocotititu=ejecutar($guardoeltitucoti);
                   $guardogentitu=("update $tablatemp set genero='0' where id_cliente_cotizacion=$lacotizaclien");
                   $repguardogene=ejecutar($guardogentitu);
               }else{
                     $busprititu=("select primas.id_prima,primas.anual,primas.edad_inicio,primas.edad_fin
                                               from primas where id_poliza=$policli and id_parentesco=18 order by edad_inicio ;");
                     //   echo "<br>----No cateem-----> $busprititu";
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
                      $guardoeltitucoti=("insert into $tablatemp1(id_cliente_cotizacion,sexo,id_prima,cantidad,montoprima,id_poliza)
                                                                      values($lacotizaclien,'1',$laprimadeltitu,1,$montoprititu,$policli);");
                      // echo "<br>$guardoeltitucoti<br>";
                       $repguardocotititu=ejecutar($guardoeltitucoti);
                       $guardogentitu=("update $tablatemp set genero='1' where id_cliente_cotizacion=$lacotizaclien");
                       $repguardogene=ejecutar($guardogentitu);
                   }

//**********************************//

?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Se ha calculado exitosamente la cotizaci&oacute;n</td>
         <td class="titulo_seccion"><label title="Imprimir planilla ejemplo" class="boton" style="cursor:pointer" onclick="planillasolicitu1('<?echo $lacotizaclien?>')" >Ver Cotizaci&oacute;n</label></td>
     </tr>
    </table>
