<?
include ("../../lib/jfunciones.php");
sesion();
$arreglofam= $_POST[arreglo];
$titulcedula=$_POST[cedulatitu];
$elgenetitu=$_POST[generotitu];
$edadtitu=calcular_edad($nacimtitu);
$teleftitu=$_POST[teleftitu];
$elcorretitu=strtoupper($_POST[corretitu]);
$fechaincltitu=$_POST[fechinclutitu];
$titulaestado=$_POST[estadotitu];
$ciudadtitu=$_POST[ciudadtitu];
$subdititu=$_POST[sudtitu];

if(empty($subdititu)){
  $subdititu=86;
}
$direcctitu=strtoupper($_POST[directitu]);
$comenttitu=strtoupper($_POST[comenttitu]);
list($numerocotizacion,$polizatitular)=explode("-",$_POST[cotipoli]);
$idcotizacion=$_POST[elidcoti];
$buscupor=("select tbl_cliente_cotizacion.inicial,tbl_cliente_cotizacion.cuotas,tbl_cliente_cotizacion.tipocliente 
            from tbl_cliente_cotizacion
            where id_cliente_cotizacion=$idcotizacion;");
$repbuscupor=ejecutar($buscupor);
$datcupor=assoc_a($repbuscupor);
$lacuota=$datcupor['cuotas'];
$porcentaje=$datcupor['inicial'];
$quecliente=$datcupor['tipocliente'];
 //**CATEEM**//  
if($quecliente==1){	
 $eltipti=1;
}else{
 $eltipti=0;	
	} 	
					  
list($opcsimater,$polizamatertitu)=explode("-",$_POST[matertitusi]);
$maternotitu=$_POST[matertituno];
$benifnomater=$_POST[maternoben];
list($opcsimaterben,$benifsimater)=explode("-",$_POST[matersiben]);
$iniciocotrato=$_POST[inicontrato];
$findecotrato=$_POST[fincontrato];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$busucursal=("select admin.id_sucursal from admin where id_admin=$elid");
$repbusucu=ejecutar($busucursal);
$datasuc=assoc_a($repbusucu);
$lasucursal=$datasuc[id_sucursal];
$elus=$_SESSION['nombre_usuario_'.empresa];
$idcomisionado=$_POST['cotizacion'];
$direccionCobro = $_POST['direccioncobro'];
$tipoContratante = $_POST['tipocontratante'];
$cedulaContratante = $_POST['cedulacontratante'];
$fedad=calcular_edad($fnaciclien);

// Verificamos si el titular ya tiene un contrato activo con la misma poliza
$versiyatiene=("
              SELECT
                1 AS existe_contrato_activo
              FROM
              titulares t
              JOIN
                tbl_contratos_entes c ON t.id_ente = c.id_ente
              JOIN
                polizas_entes p ON t.id_ente = p.id_ente
              WHERE
                t.id_cliente = (
                  SELECT id_cliente
                  FROM clientes
                  WHERE cedula = '$titulcedula'
                )
                AND c.estado_contrato = 1
                AND p.id_poliza = '$polizatitular'
              LIMIT 1;
              ");

$repvesitien=ejecutar($versiyatiene);
$cuantosTiene=num_filas($repvesitien);
if($cuantosTiene>=1){
  $datversi=assoc_a($repvesitien);
  $nopolisih=$datversi['nombre_poliza'];	
    ?>
  <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
    <tr>
      <td colspan=4 class="titulo_seccion">
        <label style="color: #ff0000"><h1>El titular ya esta registrado en la poliza <?echo $nopolisih?>!!!</h1></label>
      </td>
    </tr>
  </table>
<?} else {
  //empezamos buscando si el titular ya existe en nuesta Base de Datos
  $busclienexiste=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,clientes.direccion_hab,
                                          clientes.telefono_hab,clientes.celular,clientes.email,clientes.id_ciudad 
                                  from clientes where cedula='$titulcedula';");
  $repbusclienexis=ejecutar($busclienexiste);
  $datcliente=assoc_a($repbusclienexis);
  $elcienteides=$datcliente[id_cliente];//--------------->ya tenemos el id_cliente

  //guardamos el cliente en la tabla entes
  $guardente= "insert into
    entes(
      nombre,telefonos,direccion,rif,email_contacto,fecha_creado,hora_creado, id_ciudad,fecha_inicio_contrato,fecha_renovacion_contrato, id_sucursal,id_tipo_ente,fecha_inicio_contratob,fecha_renovacion_contratob, es_med_pre)
    values(
      '$datcliente[nombres] $datcliente[apellidos]', '$datcliente[telefono_hab]','$datcliente[direccion_hab]','$datcliente[cedula]',
      '$datcliente[email]','$fecha','$hora',$ciudadtitu,'$iniciocotrato','$findecotrato', $lasucursal,7,'$iniciocotrato','$findecotrato',1);";
  $repguaente=ejecutar($guardente);

      //buscamos el id_ente que se genera
      $selecidente="select
          entes.id_ente,entes.id_tipo_ente,entes.fecha_creado
        from
          entes
        where
          entes.rif='$titulcedula' and fecha_creado='$fecha' and entes.hora_creado='$hora';";
      $repidente=ejecutar($selecidente);
      $dataidente=assoc_a($repidente);

      $elidentees=$dataidente[id_ente];//------>ya tenenos el id_ente
      $eltipoente=$dataidente[id_tipo_ente];//------>ya tenenos el tipo de ente
      $fecente=$dataidente[fecha_creado];//------>ya tenenos la fecha creado

      list($anoente,$mesente)=explode('-',$fecente);
      $elcodigente="$elidentees-$eltipoente-$mesente$anoente";

      $atcodigo="update entes set codente='$elcodigente' where id_ente=$elidentees;";
      $repatcodigo=ejecutar($atcodigo);

      //guardamos al cliente que ya existe en la tabla titulares
      //luego de tener el id_cliente pasamos a guardar el cliente como titular en la tabla titulares
     //es importante tener en cuenta el genero y si tiene maternidad o no ya que influye en el registro!
      if(($polizamatertitu>0) and ($elgenetitu==0)){
        $guardartitu="
          insert into
            titulares(
              id_cliente, fecha_ingreso_empresa, fecha_creado, hora_creado, id_ente, fecha_inclusion, id_admin, maternidad, tipocliente
            ) 
            values(
              $elcienteides,'$fechaincltitu','$fecha','$hora',$elidentees,'$fechaincltitu',$elid,1,$eltipti
            );";
      }else{
        $guardartitu="
          insert into
            titulares(
              id_cliente, fecha_ingreso_empresa, fecha_creado, hora_creado, id_ente, fecha_inclusion, id_admin, maternidad, tipocliente
            ) 
            values(
              $elcienteides, '$fechaincltitu', '$fecha', '$hora', $elidentees, '$fechaincltitu', $elid, 0, $eltipti
            );";
      }
      $repguartitu=ejecutar($guardartitu);

      //una vez registrado el titular buscamos cual es el id_titular
      $buscidtitu="
        select
          titulares.id_titular
        from
          titulares 
        where
          titulares.id_cliente=$elcienteides and fecha_creado='$fecha' and hora_creado='$hora';";
      $repidtitu=ejecutar($buscidtitu);
      $datadidtitu=assoc_a($repidtitu);
      $eliddeltitu=$datadidtitu[id_titular];//--------------------->ya tenemos el id_titular
       
      //luego de tener el id_titular lo guardamo en la tabla titulares_subdivisiones y lo guardamos con la
      //id_subdivision=86 que es PARTICULAR
      $guardtitusubdvi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values($eliddeltitu,$subdititu);");
      $repgustdbudi=ejecutar($guardtitusubdvi);
      
      //guardamos el titular en la tabla estados_t_b
      $guardtituestatb="
        insert into
          estados_t_b(
            id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado
          ) 
          values(
            $titulaestado,$eliddeltitu,0,'$fecha','$hora'
          );";
      $reptituestab=ejecutar($guardtituestatb);

      //guardamos el titular en la tabla registros_exclusiones para tener el historial de los comentarios
      $guartituregexclu="
        insert into
          registros_exclusiones(
            fecha_inclusion,fecha_exclusion,id_titular,id_beneficiario, fecha_creado,id_estado_cliente,comentario,id_admin
          )
          values(
            '$fecha','$fecha',$eliddeltitu,0, '$fecha',$titulaestado,'$comenttitu',$elid
          );";
      $repguatituregexclu=ejecutar($guartituregexclu);

    //guardamos el titular en la tabla titulares_polizas, pero tomando en cuenta lo siguiente si es de genero
    //femenino y tiene la poliza de maternidad se guarda las 2 polizas la principal del titular mas la 
    //poliza de maternidad
    $guardtitupoliza=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
                                   values($eliddeltitu,$polizatitular,'$fecha','$hora');");
    // echo "-----------------------<br>6-$guardtitupoliza<br>"  ;                                  
    $repguartitupoliza=ejecutar($guardtitupoliza);  //poliza principal     
    //ahora guardamos el ente en la tabla polizas_entes
      $guapoliente=("insert into polizas_entes(id_ente,id_poliza) values($elidentees,$polizatitular);");
     //echo "-----------------------<br>7-$guapoliente<br>"  ;                                   
      $repgpoliente=ejecutar($guapoliente);
    //fin                          
    $guardtitupolizamate=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
                                   values($eliddeltitu,$polizamatertitu,'$fecha','$hora');");
    //echo "-----------------------<br>8-$guardtitupolizamate<br>"  ;                                                                  
    $repguartitupolizamate=ejecutar($guardtitupolizamate);  //poliza de maternidad
    //ahora guardamos el ente en la tabla polizas_entes
      $guapoliente1=("insert into polizas_entes(id_ente,id_poliza) values($elidentees,$polizamatertitu);");
      $repgpoliente1=ejecutar($guapoliente1);
    // echo "-----------------------<br>9-$guapoliente1<br>"  ; 
    //fin
    //guardamos en la tabla entes_comisionados el ente que depende para un comisionado!!
    $guarentecomi=("insert into entes_comisionados(id_ente,id_comisionado,descuento_recargo,fecha_creado,hora_creado) 
                                 values($elidentees,$elid,'.','$fecha','$hora');");
     $repguentecomi=ejecutar($guarentecomi);

    //  Registramos el cliente con el baremo asignado. 113 Es para clientes individuales medicina prepagada, osea id_tipo_ente = 7
    $guardarBaremos = "
      INSERT INTO
        tbl_baremos_entes(
          id_baremo,
          id_ente)
        values(
          '113',
          '$elidentees')";
    $repguardaBaremos = ejecutar($guardarBaremos);

      //buscamos cuantas poliza tiene el titular para cargar las coberturas_t_b
      $buslapolititu=("select titulares_polizas.id_poliza from titulares_polizas where titulares_polizas.id_titular=$eliddeltitu;");  
      $repbuspolititu=ejecutar($buslapolititu);

      while($lapoliza=asignar_a($repbuspolititu,NULL,PGSQL_ASSOC)){ //se comienza a guardar en coberturas_t_b
        $idpolies=$lapoliza[id_poliza];
        $buscpropipoliza="
          select
            propiedades_poliza.id_propiedad_poliza,
            propiedades_poliza.monto,
            propiedades_poliza.organo 
          from
            propiedades_poliza
          where
            propiedades_poliza.id_poliza=$idpolies";
        $repbuspropoliza=ejecutar($buscpropipoliza);                              
        while($propoliza=asignar_a($repbuspropoliza,NULL,PGSQL_ASSOC)){
          
          //**CATEEM**//
          if($quecliente==1){
            $guarcobtb="
              insert into
                coberturas_t_b(
                  id_propiedad_poliza,
                  id_titular,
                  id_beneficiario,
                  fecha_creado,
                  hora_creado,
                  id_organo,
                  monto_actual,
                  monto_previo
                ) 
                values(
                  $propoliza[id_propiedad_poliza],
                  $eliddeltitu,
                  0,
                  '$fecha',
                  '$hora',
                  $propoliza[organo],
                  '$propoliza[monto]',
                  '$propoliza[monto]'
                );";
            $repgucobt=ejecutar($guarcobtb);                        
          }
        }
      }
      //apartir de este punto guardamos los posibles beneficiarios que pueda tener el titular!!!
      //esto es para la carga de los hijo!!!!
      $frenofami=$_POST[frenofami]-1;
      $lafamili= explode(",",$arreglofam);
      $cuantos=count($lafamili);
      $datahijo=$cuantos/$frenofami;
      $ap1=1;
      $ap2=1;
      if($cuantos>=1){//para ver si tiene al menos un hijo!!
        for($j=0;$j<$cuantos;$j++){
          $arrfamili[$ap1][$ap2]=$lafamili[$j];
          $ap2++;
          if($ap2==7) {
              $ap2=1;
              $ap1++;
          }
        }
      }//fin de la comparacion de la cantidad de los hijos  
      //ya tenemos el arreglo con los hijos pasamos a guardar
      $cuanbenhay=count($arrfamili);
      for($z=1;$z<=$cuanbenhay;$z++){
          $cedben=$arrfamili[$z][6];
          $sexoben=$arrfamili[$z][5];
          $tippareben=$arrfamili[$z][4];
          $fechanaben=$arrfamili[$z][3];
          $apelliben=strtoupper($arrfamili[$z][2]);
          $nombrben=strtoupper($arrfamili[$z][1]);
          $edadbenef=calcular_edad($fechanaben);
      //    echo "<br>$cedben-$sexoben-$tippareben<br>";
      $bushicliente=("select clientes.id_cliente from clientes where clientes.cedula='$cedben';"); 
      $repbushiclien=ejecutar($bushicliente);
      //echo "<br>Busquedad de los hijos $bushicliente<br>";
      $haycliente=num_filas($repbushiclien);
        if($haycliente>=1){
            $datrehiclien=assoc_a($repbushiclien);
            $idclienhijo=$datrehiclien[id_cliente];//ya tenemos el id_cliente del beneficiario!!
        //    echo "<br>Si esta el beneficiarios y es $idclienhijo<br>";
        }else{
               $guarhijoclien=("insert into clientes(nombres,apellidos,fecha_nacimiento,sexo,direccion_hab,telefono_hab,
                                        celular,email,fecha_creado,hora_creado,id_ciudad,comentarios,cedula,id_admin,edad) 
                                        values('$nombrben',' $apelliben','$fechanaben','$sexoben','$direcctitu','$teleftitu','$teleftitu',
                                        '$elcorretitu','$fecha','$hora',$ciudadtitu,'$comenttitu','$cedben',$elid,$edadbenef);");
               $rephijclien=ejecutar($guarhijoclien);                 
          //     echo "<br>no esta el insert es $guarhijoclien<br>";        
               $bushijclien=("select clientes.id_cliente from clientes where clientes.cedula='$cedben';");
               $repbushiclien=ejecutar($bushijclien);                        
               $datrehiclien=assoc_a($repbushiclien);
               $idclienhijo=$datrehiclien[id_cliente];//ya tenemos el id_cliente del beneficiario!!
            //   echo "<br>No estaba la busquedad era $bushijclien<br>El Hijo es->$idclienhijo<br>";
            }
         //echo "<br>-----El hijo es $idclienhijo";   
        //guardamos al beneficiario en la tabla beneficiarios
         $guarhijoben=("insert into beneficiarios(id_cliente,id_titular,id_parentesco,fecha_creado,hora_creado,id_tipo_beneficiario) 
                                   values($idclienhijo,$eliddeltitu,$tippareben,'$fecha','$hora',7);");    
         $rephijoben=ejecutar($guarhijoben);   
         //echo "<br>-----El hijo es $guarhijoben";                          
         //buscamos el cliente recien guardado
          $busclienhijo=("select beneficiarios.id_beneficiario from beneficiarios where beneficiarios.id_cliente=$idclienhijo and 
                                beneficiarios.id_titular=$eliddeltitu;");
         $repbusclien=ejecutar($busclienhijo);
         $databusclienh=assoc_a($repbusclien);
         $hijoidben=$databusclienh[id_beneficiario];//ya tenemos el id_beneficiario
         //echo "<br>-----los hijos $busclienhijo";                          
         //guardamos al beneficiario en la tabla estados_t_b
         $hijoestb=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
                             values($titulaestado,$eliddeltitu,$hijoidben,'$fecha','$hora');");
         $rephijestb=ejecutar($hijoestb);                    
         //echo "<br>-----estados hijos $hijoestb";
         //guardamos al beneficiarios en la tabla tipos_b_beneficiarios;
         $guartipoben=("insert into tipos_b_beneficiarios(id_tipo_beneficiario,id_beneficiario,fecha_creado,hora_creado) 
                                   values(7,$hijoidben,'$fecha','$hora');");
         $guartipoben=ejecutar($guartipoben);                          
         //guardamos al beneficiario en la tabla registros_exclusiones
         $benrerexcl=("insert into registros_exclusiones(fecha_inclusion,fecha_exclusion,id_titular,
                                  id_beneficiario,fecha_creado,id_estado_cliente,comentario,id_admin) 
                                  values('$fecha','$fecha',$eliddeltitu,$hijoidben,'$fecha',$titulaestado,'$comenttitu',$elid);");
         $repbenexcl=ejecutar($benrerexcl);     
         //buscamos las propiedades de la poliza para el hijo
         $propoliben=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.monto,
                                               propiedades_poliza.organo 
                                             from propiedades_poliza where propiedades_poliza.id_poliza=$polizatitular;");
         $reppoliben=ejecutar($propoliben);                                    
         while($propolizaben=asignar_a($reppoliben,NULL,PGSQL_ASSOC)){
                   $guarcobtben=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,
                                           fecha_creado,hora_creado,id_organo,monto_actual,monto_previo) 
                                        values($propolizaben[id_propiedad_poliza],$eliddeltitu,$hijoidben,'$fecha','$hora',$propolizaben[organo],
                                           '$propolizaben[monto]','$propolizaben[monto]');");
                   $repgucobtben=ejecutar($guarcobtben); 
         }
     } //fin de los hijos
     //ahora bien tenemos que ver si hay maternidad para un beneficiario siempre y cuando
     //tenga como parentesco conyuge femenino con maternidad
     if($benifsimater>=1){
          $actualben=("update beneficiarios set maternidad=1 where id_titular=$eliddeltitu and id_parentesco=9");
          $repactben=ejecutar($actualben);
          $buscocualben=("select beneficiarios.id_beneficiario from beneficiarios where
                                       beneficiarios.id_parentesco=9 and maternidad=1 and
                                       beneficiarios.id_titular=$eliddeltitu");
          $resbucocual=ejecutar($buscocualben);    
          $datoscocual=assoc_a($resbucocual);
          $maternibeni=$datoscocual[id_beneficiario];
          $busccarcmate=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.monto,propiedades_poliza.organo from 
                                           propiedades_poliza where propiedades_poliza.id_poliza=$benifsimater;");
           $respcmate=ejecutar($busccarcmate);     
           $datcualmate=assoc_a($respcmate);
           $guardomatbenf=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,
                                           fecha_creado,hora_creado,id_organo,monto_actual,monto_previo) values
                                          ($datcualmate[id_propiedad_poliza],$eliddeltitu,$maternibeni,'$fecha','$hora',$datcualmate[organo],
                                           '$datcualmate[monto]','$datcualmate[monto]');");
         $repguardomater=ejecutar($guardomatbenf);
    }
    //guardamos todo lo necesario para el contrato
    //guardamos en la tabla tbl_contratos_entes 
    $buscomayor=("select tbl_contratos_entes.id_contrato_ente from tbl_contratos_entes order by 
                               id_contrato_ente desc limit 1;");
     $repmayoy=ejecutar($buscomayor);                          
     $cuantmayor=num_filas($repmayoy);
     if($cuantmayor>=1){
         $datmaynu=assoc_a($repmayoy);
         $elnumayes=$datmaynu[id_contrato_ente]+1;
         $numeromayo=$elnumayes;
    }else{
          $numeromayo=1;
        }
    if(($benifsimater>=1) or ($polizamatertitu>=1)){
        $ramo="HCM";
    }else{
          $ramo="HC";
        }
    $contratnumero="$ramo-$year-$lasucursal-$eltipoente-$numeromayo";   
    $nuevaf= date("Y/m/d", strtotime("$fecha +$lacuota month"));
    $guarcontente=("insert into tbl_contratos_entes(id_ente,estado_contrato,fecha_creado,numero_contrato,comentario,fecha_final_pago,cuotacon,inicialcon) 
                                 values($elidentees,'1','$fecha','$contratnumero','S/C','$nuevaf',$lacuota,$porcentaje);");
    $repguacontente=ejecutar($guarcontente);                             
    //buscamos el contrato recien guardado!!
    $buscocontrato=("select tbl_contratos_entes.id_contrato_ente,tbl_contratos_entes.numero_contrato from tbl_contratos_entes where
                                   tbl_contratos_entes.id_ente=$elidentees and tbl_contratos_entes.numero_contrato='$contratnumero';");
                                   
    $repbuscontrato=ejecutar($buscocontrato);
    $databuscontrato=assoc_a($repbuscontrato);
    $elcontratoid=$databuscontrato[id_contrato_ente];//----------> ya tenemos el id_contrato_ente;
    $contratonumero=$databuscontrato[numero_contrato];//----------> ya tenemos el numero de contrato;
    //busco mayor recibo contrato
    $mayorecicontrato=("select tbl_recibo_contrato.id_recibo_contrato from tbl_recibo_contrato order by 
                               id_recibo_contrato desc limit 1;");
     $repmayorrecontrato=ejecutar($mayorecicontrato);                          
     $cuantosmayorecontato=num_filas($repmayorrecontrato);
     if($cuantosmayorecontato>=1){
       $datmayorecibocontra=assoc_a($repmayorrecontrato);
       $elrecibomayor=$datmayorecibocontra[id_recibo_contrato]+1;
       $numcontraprima="$year-$elrecibomayor";
    }else{
          $numcontraprima="$year-1";
        }

    //guardamos en la tabla tbl_recibo_contrato
    $guardorecibocontrato="
      insert into
        tbl_recibo_contrato(
          id_contrato_ente,
          num_recibo_prima,
          fecha_ini_vigencia,
          fecha_fin_vigencia,
          fecha_creado,
          hora_emision,
          direccion_cobro,
          tipo_contratante,
          cedula_contratante,
          id_comisionado
        ) 
        values(
          $elcontratoid,
          '$numcontraprima',
          '$iniciocotrato',
          '$findecotrato',
          '$fecha',
          '$hora',
          '$direccionCobro',
          '$tipoContratante',
          '$cedulaContratante',
          $idcomisionado
        );";
    $reprecibocontrato=ejecutar($guardorecibocontrato);  

    //busco el recibo recien cargado
    $cualrecibo="
      select
        tbl_recibo_contrato.id_recibo_contrato,tbl_recibo_contrato.num_recibo_prima
      from
        tbl_recibo_contrato
      where
        tbl_recibo_contrato.id_contrato_ente=$elcontratoid and num_recibo_prima='$numcontraprima';";
     $repcualrecibo=ejecutar($cualrecibo);                       
     $datcualrecibo=assoc_a($repcualrecibo);
     $idrecibocontrato=$datcualrecibo[id_recibo_contrato];//-------->ya tenemos el id_recibo_contrato
     $recibonumero=$datcualrecibo[num_recibo_prima];//-------->ya tenemos el recibo de contrato
     //ahora tenemos que guardar las caracteristicas del contrato en la tabla tbl_caract_recibo_prima
     //buscamos todo lo que se registro en coberturas_t_b
     $todocoberturastb=("select coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,propiedades_poliza.id_poliza
  from
    coberturas_t_b,propiedades_poliza
  where
    coberturas_t_b.id_titular=$eliddeltitu and
    coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza group by 
coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,propiedades_poliza.id_poliza order by id_beneficiario;");
    $reptodocobert=ejecutar($todocoberturastb);
    while($loscobtura=asignar_a($reptodocobert,NULL,PGSQL_ASSOC)){
           $estitu=$loscobtura[id_titular];
           $esbeni=$loscobtura[id_beneficiario];
           $polizaes=$loscobtura[id_poliza];
           if($esbeni==0){//es un titular
                  $datacliente=("select clientes.edad,clientes.sexo from clientes,titulares 
                                         where
                                             clientes.id_cliente=titulares.id_cliente and
                                             titulares.id_titular=$estitu;");
                   $repdatcliente=ejecutar($datacliente);      
                   $infodatcliente=assoc_a($repdatcliente);
                   if($infodatcliente[sexo]==0){
                       $parentesco="17";
                       }else{
                             $parentesco="18";
                           }
                       $busprima=("select primas.anual,primas.id_prima,primas.edad_inicio,primas.edad_fin from primas
                                            where
                                       primas.id_poliza=$polizaes and
                                       primas.id_parentesco=$parentesco and
                                       $infodatcliente[edad]>=primas.edad_inicio  and
                                       $infodatcliente[edad]<=primas.edad_fin;");        
                     $repbusprima=ejecutar($busprima);        
                     $cuantosprima=num_filas( $repbusprima);
                     if($cuantosprima>=1){
                         $infobusprima=assoc_a($repbusprima);     
                    }else{
                           if(($parentesco==17) && ($loscobtura[id_poliza]==87) || ($loscobtura[id_poliza]==88)){
                                  $busprima1=("select primas.anual,primas.id_prima from primas
                                            where
                                       primas.id_poliza=$polizaes and
                                       primas.id_parentesco=9 and 
                                       $infodatcliente[edad]>=primas.edad_inicio  and
                                       $infodatcliente[edad]<=primas.edad_fin;");
                                   $repbusprima=ejecutar($busprima1);        
                                    $infobusprima=assoc_a($repbusprima);
                               } 
                        }      
                    $guarrecicarac=("insert into tbl_caract_recibo_prima
                          (id_recibo_contrato,id_titular,id_beneficiario,id_prima,fecha_creado,monto_prima,genera_comision) 
                          values($idrecibocontrato,$estitu,0,$infobusprima[id_prima],'$fecha',$infobusprima[anual],1)");
                    $repguarrecicarac=ejecutar($guarrecicarac);           
               }else{
                     $datacliente=("select clientes.edad,clientes.sexo,beneficiarios.id_parentesco 
                                                 from clientes,beneficiarios where
                                             clientes.id_cliente=beneficiarios.id_cliente and
                                             beneficiarios.id_beneficiario=$esbeni;");
                     $repdatcliente=ejecutar($datacliente);   
                     $infodatcliente=assoc_a($repdatcliente);
                     $busprima=("select primas.anual,primas.id_prima from primas
                                            where
                                       primas.id_poliza=$polizaes and
                                       primas.id_parentesco=$infodatcliente[id_parentesco] and 
                                       $infodatcliente[edad]>=primas.edad_inicio  and
                                       $infodatcliente[edad]<=primas.edad_fin;");
                    $repbusprima=ejecutar($busprima);  
                    $cuanhay=num_filas( $repbusprima);
                    if($cuanhay>=1){
                       $infobusprima=assoc_a($repbusprima);
                    }else{
                        if(($infodatcliente[id_parentesco]==9)||($infodatcliente[id_parentesco]==12)){ 
                        $busprima1=("select primas.anual,primas.id_prima from primas
                                            where
                                       primas.id_poliza=$polizaes and
                                       primas.id_parentesco=17 and 
                                       $infodatcliente[edad]>=primas.edad_inicio  and
                                       $infodatcliente[edad]<=primas.edad_fin;");
                        $repbusprima=ejecutar($busprima1);        
                         $infobusprima=assoc_a($repbusprima);
                      }
                     } 
                    $guarrecicarac=("insert into tbl_caract_recibo_prima
                          (id_recibo_contrato,id_titular,id_beneficiario,id_prima,fecha_creado,monto_prima,genera_comision) 
                          values($idrecibocontrato,$estitu,$esbeni,$infobusprima[id_prima],'$fecha',$infobusprima[anual],1)");
                    $repguarrecicarac=ejecutar($guarrecicarac);  
                   }
                   
     }   
//Luego de terminar con todos los registros guardamos en la tabla logs!!
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha registrado un nuevo contrato con el No. $contratonumero y recibo No. $recibonumero";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>


<!-- Obtenemos las variables necesarias para poder cargar la vista de imprimir el recibo -->
  <input type="hidden" id="cliencontratos" value="<?php echo $idrecibocontrato?>">
  <input type="hidden" id="cedulatitu" value="<?php echo $titulcedula?>">



<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente registrado exitosamente</td>  
         <td class="titulo_seccion"><label title="Imprimi cuadro recibo" class="boton" style="cursor:pointer" onclick="reimpcuadrecibo()" >Imprimir</label></td>
         <?echo"<td class=\"titulo_seccion\"><a href=\"views01/anexos.php?titular=$estitu&recibo=$idrecibocontrato\" title=\"Crear hoja de anexos\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800, overlayClose: false}); return false;\">Hoja Anexo</a></td>";?>         
     </tr>
</table>
<?}?>
