<?php
include ("../../lib/jfunciones.php");
sesion();
$enteactual=$_REQUEST['elentactual'];
$titucam=$_REQUEST['eltitular'];
$elnuevoente=$_REQUEST['elnuevoente'];
$pasarpagos=$_REQUEST['gasttitu'];
$pasarbenfi=$_REQUEST['losbenfi'];
$comenetca=$_REQUEST['elcomen'];
$polizaid=$_REQUEST['lapoliz'];
$cedulcli=$_REQUEST['ceduclien'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//
if(($pasarpagos==2)&&($pasarbenfi==1)){
    if($enteactual==$elnuevoente){?>
         <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">Error el cliente ya se encuentra en el ente seleccionado <label class="boton" style="cursor:pointer" onclick="PasarTituaEnte()" >Retornar</label></td>  
            </tr>
          </table>
    <?}else{
        
/*
Primero vamos hacer el IF de cuando NO pasamos los gastos y SI pasamos los 
beneficiarios
*/
//primero vamos a buscar los beneficiarios del titular a cambiar
$buscarbenif=("select beneficiarios.id_beneficiario,clientes.id_cliente,beneficiarios.id_parentesco from 
                          beneficiarios,titulares,estados_t_b,clientes where
                        titulares.id_titular=beneficiarios.id_titular and
                        titulares.id_titular=$titucam and
                        beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                        estados_t_b.id_estado_cliente=4 and
                        beneficiarios.id_cliente=clientes.id_cliente");
$repbuscabenif=ejecutar($buscarbenif);                        
$repbuscabenif1=ejecutar($buscarbenif);     
$cuantoscambenif=num_filas($repbuscabenif);
if($cuantoscambenif>=1){
/*segundo vamos a colocar en estados_t_b en el campo id_estado_cliente=5 que es 
  exclusion del titular y del beneficiario*/
/*tercero vamos a incluir en la tabla registros_exclusiones el titular y los beneficiarios excluidos como 
  tambien el estado del cliente en que quedaron
*/
/*
cuarto vamos a incluir en la tabla comentarios_tb los cambios que se hizo en los titulares o beneficiarios
*/
while($losbenefi=asignar_a($repbuscabenif,NULL,PGSQL_ASSOC)){
   $actestatb=("update estados_t_b set id_estado_cliente=5 where id_titular=$titucam and id_beneficiario=$losbenefi[id_beneficiario];");
   $repactestab=ejecutar($actestatb);
   $incluregistexclusion=("insert into registros_exclusiones(fecha_inclusion,fecha_exclusion,id_titular,id_beneficiario,fecha_creado,id_estado_cliente) 
                                        values('$fecha','$fecha',$titucam,$losbenefi[id_beneficiario],'$fecha',5)");
    $repinsluregiexclusion=ejecutar($incluregistexclusion);     
    $inclucomenttb=("insert into comentarios_tb(id_titular,id_beneficiario,comentario) values($titucam,$losbenefi[id_beneficiario],'$comenetca');");
    $repinclucmenttb=ejecutar($inclucomenttb);
}
}//fin del if de cuantos beneficiarios hay
//hacemos los cambios al titular
   $actuestdeltitu=("update estados_t_b set id_estado_cliente=5 where id_titular=$titucam and id_beneficiario=0;");
   $repactuesdeltitu=ejecutar($actuestdeltitu);
   $inctiturepexclusion=("insert into registros_exclusiones(fecha_exclusion,id_titular,id_beneficiario,id_estado_cliente) 
                                        values('$fecha',$titucam,0,5);");
   $repinctiturepexclusion=ejecutar($inctiturepexclusion);   
   $inclucomentitular=("insert into comentarios_tb(id_titular,id_beneficiario,comentario) values($titucam,$0,'$comenetca');");
   $repinclucmenttitular=ejecutar($inclucomentitular);
//fin de los cambios del titular  
/*
Ahora tenemos que hacer todas las inclusiones tanto del titular como de los beneficiarios
1. buscar el id del cliente como titular;
*/   
$buscidcliente=("select clientes.id_cliente from clientes,titulares where
                           titulares.id_cliente=clientes.id_cliente and
                           titulares.id_titular=$titucam;");
$repsidcliente=ejecutar($buscidcliente);
$datidcliente=assoc_a($repsidcliente);
$elidcliente=$datidcliente['id_cliente'];
//guardar el cliente como titular
$guardaclientetitu=("insert into titulares(id_cliente,fecha_ingreso_empresa,fecha_creado,hora_creado,id_ente,id_admin) 
                                  values($elidcliente,'$fecha','$fecha','$hora',$elnuevoente,$elid);");
$repguadclientitu=ejecutar($guardaclientetitu);                                  
//buscamos el nuevo titular
$eltituguardado=("select titulares.id_titular from titulares where id_cliente=$elidcliente and fecha_creado='$fecha' and id_admin=$elid;");
$repeltiguardado=ejecutar($eltituguardado);
$datcliente=assoc_a($repeltiguardado);
$elidclientetitu=$datcliente['id_titular'];
//guardamos el nuevo titular en estados_t_b
$guartitestatb=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
                          values(4,$elidclientetitu,0,'$fecha','$hora');");
$reptitestatb=ejecutar($guartitestatb);         
//guardamos el titular en titulares subdivisiones
$titusubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values ($elidclientetitu,86);");
$reptitusubdivi=ejecutar($titusubdivi);
//guardamos el titular en titulares_polizas
$titularpolizas=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) values($elidclientetitu,$polizaid,'$fecha','$hora');");
$reptitularpoliza=ejecutar($titularpolizas); 
//tenemos que guardar las propiedades polizas en la cobertura_t_b
$buspropipolizas=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.organo,propiedades_poliza.monto_nuevo,
                                propiedades_poliza.cualidad from 
                              propiedades_poliza where id_poliza=$polizaid and propiedades_poliza.cualidad<>'MATERNIDAD';");
$repbuspolizas=ejecutar($buspropipolizas);    
    while($laspolizas=asignar_a($repbuspolizas,NULL,PGSQL_ASSOC)){
        $guarcobeturatb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,id_organo,
                                            monto_actual,monto_previo) 
                                            values($laspolizas[id_propiedad_poliza],$elidclientetitu,0,'$fecha','$hora',$laspolizas[organo],'$laspolizas[monto_nuevo]','$laspolizas[monto_nuevo]')");
        $repguarcoberturatb=ejecutar($guarcobeturatb);                                            
    }
//Ahora comenzamos a guardar todos los beneficiarios que puede tener el nuevo titular!!
    if($cuantoscambenif>=1){
        while($losnuevobenefi=asignar_a($repbuscabenif1,NULL,PGSQL_ASSOC)){
            $idclientees=$losnuevobenefi['id_cliente'];
            $parestesco=$losnuevobenefi['id_parentesco'];
            //guardamos el cliente como beneficiario
            $registrarbenefi=("insert into beneficiarios(id_cliente,id_titular,id_parentesco,fecha_creado,hora_creado,fecha_inclusion,id_tipo_beneficiario) 
                                          values($idclientees,$elidclientetitu,$parestesco,'$fecha','$hora','$fecha',7);");
             $repuregbenefi=ejecutar($registrarbenefi);                                          
             //buscamos el beneficiario que se guardo
             $verclienbenfi=("select beneficiarios.id_beneficiario from beneficiarios where beneficiarios.id_titular=$elidclientetitu and
                                        beneficiarios.id_cliente=$idclientees and beneficiarios.fecha_creado='$fecha';");
              $repverbenfi=ejecutar($verclienbenfi);                                        
              $databenfi=assoc_a($repverbenfi);
              $elidbenifes=$databenfi['id_beneficiario'];
              //guardamos el beneficiario en la tabla estados_t_b
              $benefiestatb=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
                                        values(4,$elidclientetitu,$elidbenifes,'$fecha','$hora');");
              $repbenfiestatb=ejecutar($benefiestatb);    
              //guardamos el beneficiario en la tabla tipos_b_beneficiarios
              $registipobenefi=("insert into tipos_b_beneficiarios(id_tipo_beneficiario,id_beneficiario,fecha_creado,hora_creado,porcentaje) 
                                            values(7,$elidbenifes,'$fecha','$hora','0');");
              $reptipobenfi=ejecutar($registipobenefi);                              
              //tenemos que guardar el benficiario en la cobertura_t_b
              $buspropipolizas1=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.organo,propiedades_poliza.monto_nuevo,
                                propiedades_poliza.cualidad from 
                              propiedades_poliza where id_poliza=$polizaid and propiedades_poliza.cualidad<>'MATERNIDAD';");
              $repbuspolizas1=ejecutar($buspropipolizas1);    
            while($laspolizas1=asignar_a($repbuspolizas1,NULL,PGSQL_ASSOC)){
                $guarcobeturatb1=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,
                                        id_organo,monto_actual,monto_previo) 
                                            values($laspolizas1[id_propiedad_poliza],$elidclientetitu,$elidbenifes,'$fecha','$hora',$laspolizas1[organo],'$laspolizas1[monto_nuevo]','$laspolizas[monto_nuevo]')");
            $repguarcoberturatb1=ejecutar($guarcobeturatb1);                                            
            }
            //guardamos 
      }  //fin de la cobertura_t_b de los beneficiarios
    }?>    
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
          <tr> 
             <td colspan=4 class="titulo_seccion">Listo</td>  
         </tr>
    </table>
<?
 //**********************************//
           //Guardar los datos en la tabla logs;
           $mensaje="$elus, ha excluido al  $titucam del ente $enteactual y se ha activado al nuevo ente $elnuevoente";
           $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
          $inrelo=ejecutar($relog);
 }//fin del IF Primero de cuando NO pasamos los gastos y SI pasamos los beneficiarios
} 
 else{//pasar al titular a un nuevo ente con sus gastos!!!
       $busprimeropoli=("select titulares_polizas.id_titular_poliza,polizas.nombre_poliza 
                                   from titulares_polizas,polizas where 
                                   titulares_polizas.id_titular=$titucam and titulares_polizas.id_poliza=$polizaid and
                                   titulares_polizas.id_poliza=polizas.id_poliza;");
                                   
        $repbusprimeropoli=ejecutar($busprimeropoli);
       $datbusprimero=assoc_a($repbusprimeropoli);
       $polizactual=$datbusprimero['nombre_poliza'];
       $cuantohaypoli=num_filas($repbusprimeropoli);
       if($cuantohaypoli>=1){?>
        
          <input type="hidden" id="cedulclien" value="<?echo $cedulcli?>">
           <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">Error el cliente ya se encuentra en la poliza <?echo $polizactual?> <label class="boton" style="cursor:pointer" onclick="PasarTituaEnte()" >Retornar</label></td>  
            </tr>
          </table>
     <?}else{
             //
                 $nuevapoli=("select titulares_polizas.id_titular_poliza,polizas.nombre_poliza 
                                   from titulares_polizas,polizas where 
                                   titulares_polizas.id_titular=$titucam and titulares_polizas.id_poliza=$polizaid and
                                   titulares_polizas.id_poliza=polizas.id_poliza;");
                $repnuvpoli=ejecutar($nuevapoli);
                $datnuvpoli=assoc_a($repnuvpoli);
                $nombpolizanue=$datnuvpoli['nombre_poliza'];
             //
              $buscolapoliactua=("select titulares_polizas.id_poliza from titulares_polizas where id_titular=$titucam;");
              $repbuscopoactu=ejecutar($buscolapoliactua);
              $datapoactual=assoc_a($repbuscopoactu);
              $lapoliquetiene=$datapoactual['id_poliza'];
              //buscamos la fecha cuando comienza el titular y el beneficiario para ver los gastos
              $verfechatb=("select entes.fecha_inicio_contrato,entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,
                                                entes.fecha_renovacion_contratob 
                                                 from entes where id_ente=$enteactual;");
            $repverfechatb=ejecutar($verfechatb);
            $verdafetb=assoc_a($repverfechatb);
            $fecinititu=$verdafetb['fecha_inicio_contrato'];
            $fecfintitu=$verdafetb['fecha_renovacion_contrato'];
            $feciniben=$verdafetb['fecha_inicio_contratob'];
            $fecfinben=$verdafetb['fecha_renovacion_contratob'];
            $apunta1=0;
            $apunta2=3;
            //buscamos las propiedades polizas del titular
            $busquemospropoli=("select propiedades_poliza.id_poliza,propiedades_poliza.cualidad,propiedades_poliza.monto_nuevo,
coberturas_t_b.id_cobertura_t_b from propiedades_poliza,coberturas_t_b where propiedades_poliza.id_poliza=$lapoliquetiene and
propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
coberturas_t_b.id_titular=$titucam and coberturas_t_b.id_beneficiario=0 order by propiedades_poliza.cualidad;");
           $repbusmospoli=ejecutar($busquemospropoli);
           $cuantapropiedades=num_filas($repbusmospoli);
            while($losmonto=asignar_a($repbusmospoli,NULL,PGSQL_ASSOC)){
                 $arregtb[$apunta1][1]=$losmonto['cualidad'];
                 $arregtb[$apunta1][2]=$losmonto['monto_nuevo'];
                  $buscarlosgatb=("select gastos_t_b.monto_aceptado,gastos_t_b.monto_reserva,procesos.id_proceso from 
                                               gastos_t_b,procesos where
                                               procesos.id_proceso=gastos_t_b.id_proceso and
                                               procesos.id_titular=$titucam and
                                               procesos.id_beneficiario=0 and
                                              gastos_t_b.id_cobertura_t_b=$losmonto[id_cobertura_t_b] and
                                               procesos.fecha_creado between '$fecinititu' and '$fecfintitu';");
                  $repbuscargatb=ejecutar($buscarlosgatb);                             
                  while($losprotb=asignar_a($repbuscargatb,NULL,PGSQL_ASSOC)){
                          $arregtb[$apunta1][$apunta2]=$losprotb['monto_aceptado'];
                          $apunta2++;
                      }
               $apunta1++;     
               $apunta2=3;
            }    
            //print_r($arregtb);
            $montogastos=0;
            $m=0;
            for($i=0;$i<$cuantapropiedades;$i++){
              $cuantoshay=count($arregtb[$i]);
                if($cuantoshay>2){
                    for($j=3;$j<=$cuantoshay;$j++){
                           $montogastos=$arregtb[$i][$j]+$montogastos;
                           $propiedadgast=$arregtb[$i][1];
                    }
                    $arreglogastb[$m][1]=$montogastos;
                    $arreglogastb[$m][2]=$propiedadgast;
                }else{
                       $arreglogastb[$m][1]=0;
                       $arreglogastb[$m][2]=$arregtb[$i][1];
                    }
                    $m++;
            }  
            //busquemos las nuevas propiedades que tendra el titular!!!
            $cuantsoncamb=count($arreglogastb);
            //echo "-------$cuantsoncamb------<br>";
            for($z=0;$z<$cuantsoncamb;$z++){
                $lacualidad=$arreglogastb[$z][2];
                $nuevpropiedad=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.cualidad,propiedades_poliza.organo,
                                           propiedades_poliza.monto_nuevo 
                                            from propiedades_poliza where 
                                          propiedades_poliza.id_poliza=$polizaid and propiedades_poliza.cualidad='$lacualidad'");
                $repnuevpropiedad=ejecutar($nuevpropiedad);                          
                $datanuevpropied=assoc_a($repnuevpropiedad);
                $lancualid=$datanuevpropied['cualidad'];
                $lanidprop=$datanuevpropied['id_propiedad_poliza'];
                $lanorgano=$datanuevpropied['organo'];
                $lanmontnu=$datanuevpropied['monto_nuevo'];
                $elmontoquevie=$arreglogastb[$z][1];
                $elmonuevoes=$lanmontnu-$elmontoquevie;
                //echo"El monto de la nueva-->$lanmontnu---el nuevo monto--->$elmonuevoes<br>-----se esta restando----->$elmontoquevie";
                //busquemos las propiedades que tiene para ser cambiadas!!
                $buscrproactual=("select propiedades_poliza.id_propiedad_poliza,coberturas_t_b.id_propiedad_poliza as otro, coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,
                                               coberturas_t_b.id_beneficiario from propiedades_poliza,coberturas_t_b where
                                               propiedades_poliza.id_poliza=$lapoliquetiene and propiedades_poliza.cualidad='$lancualid' and
                                               propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
                                               coberturas_t_b.id_titular=$titucam and coberturas_t_b.id_beneficiario=0;");
                 $repbuscproactual=ejecutar($buscrproactual);             
                 $cuantproactual=num_filas($repbuscproactual);
                 if($cuantproactual>=1){
                       $datdeaccober=assoc_a($repbuscproactual);
                       $elidcobertb=$datdeaccober['id_cobertura_t_b'];
                       $seactualnuevacobe=("update coberturas_t_b set id_propiedad_poliza=$lanidprop,monto_actual='$elmonuevoes',monto_previo='$elmonuevoes' where id_cobertura_t_b=$elidcobertb");
                       $repactunuecober=ejecutar($seactualnuevacobe);                       
                     }
            }
                
            //Es hora de hacer los cambio para los beneficiarios!!
            //primero vamos a buscar los beneficiarios del titular a cambiar
            $apunta1b=0;
            $apunta2be=4;
               $buscarbenif=("select beneficiarios.id_beneficiario,clientes.id_cliente,beneficiarios.id_parentesco from 
                          beneficiarios,titulares,estados_t_b,clientes where
                        titulares.id_titular=beneficiarios.id_titular and
                        titulares.id_titular=$titucam and
                        beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
                        estados_t_b.id_estado_cliente=4 and
                        beneficiarios.id_cliente=clientes.id_cliente");
              $repbuscabenif=ejecutar($buscarbenif);  
              $cuantoscambenif=num_filas($repbuscabenif);
              if($cuantoscambenif>=1){
                     while($losbenfe=asignar_a($repbuscabenif,NULL,PGSQL_ASSOC)){
                     $elbenficid=$losbenfe['id_beneficiario'];
                       $buscprobenif=("select propiedades_poliza.id_poliza,propiedades_poliza.cualidad,propiedades_poliza.monto_nuevo,
                                                   coberturas_t_b.id_cobertura_t_b ,coberturas_t_b.id_beneficiario
                                                  from propiedades_poliza,coberturas_t_b 
                                            where propiedades_poliza.id_poliza=$lapoliquetiene and
                                                    propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
                                                   coberturas_t_b.id_titular=$titucam and coberturas_t_b.id_beneficiario=$elbenficid 
                                                order by propiedades_poliza.cualidad");
                      $repbuscprobenif=ejecutar($buscprobenif);
                      $cuantapropiedabenefi=num_filas($repbuscprobenif);
                      while($laspolibenfe=asignar_a($repbuscprobenif,NULL,PGSQL_ASSOC)){
                         $arregtbenef[$apunta1b][1]=$laspolibenfe['cualidad'];
                         $arregtbenef[$apunta1b][2]=$laspolibenfe['monto_nuevo'];
                         $arregtbenef[$apunta1b][3]=$laspolibenfe['id_beneficiario'];
                  $buscarlosgatbb=("select gastos_t_b.monto_aceptado,gastos_t_b.monto_reserva,procesos.id_proceso from 
                                               gastos_t_b,procesos where
                                               procesos.id_proceso=gastos_t_b.id_proceso and
                                               procesos.id_beneficiario=$elbenficid and
                                              gastos_t_b.id_cobertura_t_b=$laspolibenfe[id_cobertura_t_b] and
                                               procesos.fecha_creado between '$feciniben' and '$fecfinben';");
                  $repbuscargatbb=ejecutar($buscarlosgatbb);     
                            while($losgatbbene=asignar_a( $repbuscargatbb,NULL,PGSQL_ASSOC)){
                                     $arregtbenef[$apunta1b][$apunta2be]=$losgatbbene['monto_aceptado'];
                                     $apunta2be++;
                                }
                    $apunta1b++;            
                    } 
                    $apunta2be=4;
                  }
                $elnumarr=count($arregtbenef);
                $montobenefi=0;
                $mbe=0;
            for($p=0;$p<$elnumarr;$p++){
              $cuantoshayb=count($arregtbenef[$p]);              
                if($cuantoshayb>3){
                    for($w=4;$w<=$cuantoshayb;$w++){
                           $montobenefi=$arregtbenef[$p][$w]+$montobenefi;
                           $propiedabenfi=$arregtbenef[$p][1];
                           $elbenefici=$arregtbenef[$p][3];
                    }
                    $arreglogastbf[$mbe][1]=$montobenefi;
                    $arreglogastbf[$mbe][2]=$propiedabenfi;
                    $arreglogastbf[$mbe][3]=$elbenefici;
                }else{
                       $arreglogastbf[$mbe][1]=0;
                       $arreglogastbf[$mbe][2]=$arregtbenef[$p][1];
                       $arreglogastbf[$mbe][3]=$arregtbenef[$p][3];
                    }
                    $mbe++;
            }
             //**
                //busquemos las nuevas propiedades que tendra el titular!!!
            $cuantsoncambenefe=count($arreglogastbf);
            //echo "-------$cuantsoncamb------<br>";
            for($q=0;$q<$cuantsoncambenefe;$q++){
                $lacualidadbene=$arreglogastbf[$q][2];
                $nuevpropiedadbene=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.cualidad,propiedades_poliza.organo,
                                           propiedades_poliza.monto_nuevo 
                                            from propiedades_poliza where 
                                          propiedades_poliza.id_poliza=$polizaid and propiedades_poliza.cualidad='$lacualidadbene'");
                $repnuevpropiedadbenef=ejecutar($nuevpropiedadbene);                          
                $datanuevpropiedbenef=assoc_a($repnuevpropiedadbenef);
                $lancualidb=$datanuevpropiedbenef['cualidad'];
                $lanidpropb=$datanuevpropiedbenef['id_propiedad_poliza'];
                $lanorganob=$datanuevpropiedbenef['organo'];
                $lanmontnub=$datanuevpropiedbenef['monto_nuevo'];
                $elmontoquevieb=$arreglogastbf[$q][1];
                $elbeneficiario=$arreglogastbf[$q][3];
                $elmonuevoesb=$lanmontnub-$elmontoquevieb;
                //busquemos las propiedades que tiene el beneficiario para ser cambiadas!!
                $buscrproactualbenef=("select propiedades_poliza.id_propiedad_poliza,coberturas_t_b.id_propiedad_poliza as otro, coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,
                                               coberturas_t_b.id_beneficiario from propiedades_poliza,coberturas_t_b where
                                               propiedades_poliza.id_poliza=$lapoliquetiene and propiedades_poliza.cualidad='$lacualidadbene' and
                                               propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
                                               coberturas_t_b.id_titular=$titucam and coberturas_t_b.id_beneficiario=$elbeneficiario;");
                 $repbuscproactualbenef=ejecutar($buscrproactualbenef);             
                 $cuantproactualbene=num_filas($repbuscproactualbenef);
                 if($cuantproactualbene>=1){
                       $datdeaccoberbef=assoc_a($repbuscproactualbenef);
                       $elidcobertbenef= $datdeaccoberbef['id_cobertura_t_b'];
                       $seactualnuevacobenf=("update coberturas_t_b set id_propiedad_poliza=$lanidpropb,monto_actual='$elmonuevoesb',monto_previo='$elmonuevoesb' where id_cobertura_t_b=$elidcobertbenef");
                       $repactunuecobenef=ejecutar($seactualnuevacobenf);                       
                     }
            }
             //**
          }//fin del cambio de los beneficiarios!!
            //por ultimo la propiedad_poliza que tenia la cambiamos para la nueva!!!
            $cambipropolizatitu=("update titulares_polizas set id_poliza=$polizaid where id_poliza=$lapoliquetiene 
                                                and id_titular=$titucam");
            $repcambpropolizati=ejecutar($cambipropolizatitu); ?>    
            <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
             <tr> 
                <td colspan=4 class="titulo_seccion">La actualizaci&oacute;n de la poliza se ha realizado exitosamente!!!</td>  
            </tr>
          </table>
     <?
       //**********************************//
           //Guardar los datos en la tabla logs;
           $mensaje="$elus, ha cambiado la propiedad poliza del titular  $titucam";
           $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
          $inrelo=ejecutar($relog);
     }
}
?>