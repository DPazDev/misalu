<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$ncontra=explode(',',$_REQUEST['elarrego']);
$cuantocon=count($ncontra);
$fechainic=$_REQUEST[fe1];
$fechafin=$_REQUEST[fe2];
$escojepoli=$_REQUEST[polizaescoge];
$titularpoliactual=$_REQUEST[lapoltiene];
$elidcontrato=$_REQUEST['laidcontrato'];
$valocoiciden=0;
$titularfinal=0;
$contratofinal=0;
$elentefinal=0;
$cuandsondistinta=0;


// Si se va a cambiar la poliza.
if($escojepoli<>$titularpoliactual){
  
  $cualidadesNuevaPoliza=
    "SELECT
      propiedades_poliza.cualidad
    FROM
      propiedades_poliza
    WHERE
      id_poliza=$escojepoli";
  $repbuscualnue=ejecutar($cualidadesNuevaPoliza);

  while($lascualnue=asignar_a($repbuscualnue,NULL,PGSQL_ASSOC)) {

	  $nombrcuali=$lascualnue['cualidad'];

	  $vercualivieja=("SELECT
      propiedades_poliza.cualidad
    FROM
      propiedades_poliza
    WHERE
      id_poliza=$titularpoliactual AND
      cualidad='$nombrcuali'");
	  $repcualivieja=ejecutar($vercualivieja);
	  $coinciden=num_filas($repcualivieja);

	  if($coinciden<=0){
		  $valocoiciden=1;
		  break;
    }
  }
}



if($valocoiciden==1){
  ?>
  <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
    <tr>  
      <td colspan=8 class="titulo_seccion">
        <label style="color: #ff0000"><h1>Error: Las propiedades de la póliza seleccionada son distintas a la anterior póliza</h1></label>
      </td>
    </tr> 
   </table>
  <?
} else {

  $paso=0;

  for($i=0;$i<=$cuantocon;$i++){

    list($estitu,$esbeni,$edad,$elparentesco,$nuevprima,$recibocontrato,$idente)=explode('-',$ncontra[$i]);
      
    $paso=1;  
    if($i==0) {

      // Actualizamos las coberturas
      $actCobertura = "UPDATE
                        coberturas_t_b
                      SET
                        monto_actual=coberturas_t_b.monto_previoWHERE id_titular='$estitu'";
      $ejecutarActCobertura = ejecutar($actCobertura);

      $cliente="SELECT
                  clientes.*
                FROM
                  clientes,titulares
                WHERE
                  clientes.id_cliente=titulares.id_cliente AND
                  titulares.id_titular=$estitu";
      $repcliente=ejecutar($cliente);
      $datcliente=assoc_a($repcliente);

      if($datcliente['sexo']==1){
        $tituparen=18;
      }else{
        $tituparen=17;  
      }

      $actelente="UPDATE
                      entes
                    SET
                      fecha_inicio_contrato='$fechainic',
                      fecha_renovacion_contrato='$fechafin',
                      fecha_renovacion_contratob='$fechafin',
                      fecha_inicio_contratob='$fechainic'  
                    FROM 
                      tbl_recibo_contrato,tbl_contratos_entes 
                    WHERE 
                      tbl_recibo_contrato.id_recibo_contrato=$elidcontrato AND
                      tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente AND
                      tbl_contratos_entes.id_ente=entes.id_ente;";
      // echo "Primero actualizo el ente $actelente<br>";             
      $repactente=ejecutar($actelente);

      $datcont=("SELECT
                    tbl_contratos_entes.id_contrato_ente,
                    tbl_contratos_entes.id_ente,
                    tbl_contratos_entes.cuotacon,
                    tbl_contratos_entes.numero_contrato,
                    tbl_recibo_contrato.id_comisionado,
                    tbl_recibo_contrato.conaumento
                  FROM
                    tbl_contratos_entes,tbl_recibo_contrato 
                  WHERE 
                    tbl_contratos_entes.id_ente=$idente AND 
                    tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente AND
                    tbl_recibo_contrato.id_recibo_contrato=$elidcontrato;");
      //echo "Busco el contrato seleccionado $datcont<br>";                          
      $repdatcon=ejecutar($datcont);
      $datcon=assoc_a($repdatcon);
      $numcontrato=$datcon[numero_contrato];
      $posibleaumentoc=$datcon[conaumento];
      
      if($titularfinal==0){
        $titularfinal=$estitu;
        $contratofinal=$numcontrato;
        $elentefinal=$idente;
      }

      $idcomisionado=$datcon[id_comisionado];
      $elcontratoid=$datcon[id_contrato_ente];
      $numerodcuotas=$datcon[cuotacon];
      $nuevaf= date("Y/m/d", strtotime("$fecha +$numerodcuotas month"));

      //actualizo la nueva fecha final de pago en la tabla tbl_contratos_entes
      $actffconente="UPDATE
          tbl_contratos_entes
        SET
          fecha_final_pago='$nuevaf'
        WHERE
          tbl_contratos_entes.id_contrato_ente=$elcontratoid";
      $repactffconente=ejecutar($actffconente);

      //busco mayor recibo contrato
      $mayorecicontrato="SELECT
          tbl_recibo_contrato.id_recibo_contrato
        FROM
          tbl_recibo_contrato
        ORDER BY 
          id_recibo_contrato
        DESC LIMIT 1;";
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
      $guardorecibocontrato=("insert into
                                tbl_recibo_contrato(id_contrato_ente,num_recibo_prima,fecha_ini_vigencia,fecha_fin_vigencia,fecha_creado,hora_emision,id_comisionado)
                                values($elcontratoid,'$numcontraprima','$fechainic','$fechafin','$fecha','$hora',$idcomisionado);");
      //echo "Guardamos todo lo referente al contrato $guardorecibocontrato<br>";                                                                   
      $reprecibocontrato=ejecutar($guardorecibocontrato);  

      //busco el recibo recien cargado
      $cualrecibo="SELECT
          tbl_recibo_contrato.id_recibo_contrato,tbl_recibo_contrato.num_recibo_prima
        FROM
          tbl_recibo_contrato
        WHERE
          tbl_recibo_contrato.id_contrato_ente=$elcontratoid and num_recibo_prima='$numcontraprima' and 
          tbl_recibo_contrato.fecha_creado='$fecha';";
      // echo "Busco el recibo recien guardado $cualrecibo<br>";                                                                                          
      $repcualrecibo=ejecutar($cualrecibo);                       
      $datcualrecibo=assoc_a($repcualrecibo);
      $idrecibocontrato=$datcualrecibo[id_recibo_contrato];//-------->ya tenemos el id_recibo_contrato
      $recibonumero=$datcualrecibo[num_recibo_prima];//-------->ya tenemos el recibo de contrato
    }

    // Si no va a cambiar de poliza
    if($escojepoli==$titularpoliactual){

      // Si es titular
      if(($estitu>1)&&($esbeni==0)){
        $busprima="SELECT
            primas.id_prima,primas.anual
          FROM
            primas
          WHERE
            primas.id_poliza=$escojepoli and
            primas.id_parentesco=$tituparen;";                 
        $repbusprima=ejecutar($busprima);        
        $infobusprima=assoc_a($repbusprima);
        $laidprimaes=$infobusprima[id_prima];
        $montoanual=$infobusprima[anual];

        if($posibleaumentoc=='0'){
          $nuevprima=$montoanual;
        }
        $guarrecicarac="INSERT INTO
            tbl_caract_recibo_prima (
              id_recibo_contrato,
              id_titular,
              id_beneficiario,
              id_prima,
              fecha_creado,
              monto_prima,
              genera_comision)
            values(
              $idrecibocontrato,
              $estitu,
              0,
              $laidprimaes,
              '$fecha',
              '$nuevprima',
              1)";
        $repguarrecicarac=ejecutar($guarrecicarac);  

        $guardregiexclu=
          "INSERT INTO
            registros_exclusiones(fecha_inclusion,fecha_exclusion,id_titular,id_beneficiario,fecha_creado,id_estado_cliente,id_admin)
          values('$fecha','$fecha',$estitu,0,'$fecha',4,$elid);";
        $repguaregiex=ejecutar($guardregiexclu);
    
      } else {

        $busprima=("SELECT
                      primas.id_prima,primas.anual from primas
                    where
                      primas.id_poliza=$escojepoli and
                      primas.id_parentesco=$elparentesco and 
                      $edad>=primas.edad_inicio and
                      $edad<=primas.edad_fin;");
        //echo "Cuando son primas con beneficiario $busprima<br>";                                                   
        $repbusprima=ejecutar($busprima);        
        $infobusprima=assoc_a($repbusprima);
        $laidprimaes=$infobusprima[id_prima];
        $montoanual=$infobusprima[anual];

        if($posibleaumentoc=='0'){
          $nuevprima=$montoanual;
        }

        $guarrecicarac="INSERT INTO
            tbl_caract_recibo_prima(
              id_recibo_contrato,
              id_titular,
              id_beneficiario,
              id_prima,
              fecha_creado,
              monto_prima,
              genera_comision)
            values($idrecibocontrato,
            $estitu,
            $esbeni,
            $laidprimaes,
            '$fecha',
            '$nuevprima',
            1)";
        $repguarrecicarac=ejecutar($guarrecicarac);

        $guardregiexclu="INSERT INTO
                          registros_exclusiones(
                            fecha_inclusion,
                            fecha_exclusion,
                            id_titular,
                            id_beneficiario,
                            fecha_creado,
                            id_estado_cliente,
                            id_admin)
                          values(
                          '$fecha',
                          '$fecha',
                          $estitu,
                          $esbeni,
                          '$fecha',
                          4,
                          $elid);";
        $repguaregiex=ejecutar($guardregiexclu);
      }

    } else{//la poliza es distinta tenemos que sustituir lo siguiente
	   
      if ($cuandsondistinta==0) {

        $acttipoli="UPDATE
            titulares_polizas
          SET
            id_poliza=$escojepoli
          where
            id_poliza=$titularpoliactual and
            id_titular=$estitu";
        $repacttipoli=ejecutar($acttipoli);
        
        $actpoliente="UPDATE
            polizas_entes
          SET
            id_poliza=$escojepoli
          where
            id_ente=$idente and
            id_poliza=$titularpoliactual";
        $repactpoliente=ejecutar($actpoliente);

        $cuandsondistinta++;
      }
  
      //Actualización de coberturas
      if(($estitu>1)&&($esbeni==0)){
        $busprima="SELECT
            primas.id_prima,primas.anual
          FROM
            primas
          WHERE
            primas.id_poliza=$escojepoli AND
            primas.id_parentesco=$tituparen;";

        $repbusprima=ejecutar($busprima);        
        $infobusprima=assoc_a($repbusprima);
        $laidprimaes=$infobusprima[id_prima];
        $nuevprima=$infobusprima[anual];

        $guarrecicarac="INSERT INTO
            tbl_caract_recibo_prima(
            id_recibo_contrato,
            id_titular,
            id_beneficiario,
            id_prima,
            fecha_creado,
            monto_prima,
            genera_comision)
          values(
            $idrecibocontrato,
            $estitu,
            0,
            $laidprimaes,
            '$fecha',
            $nuevprima,
            1)";
        $repguarrecicarac=ejecutar($guarrecicarac);

        $guardregiexclu="INSERT INTO
            registros_exclusiones(
              fecha_inclusion,
              fecha_exclusion,
              id_titular,
              id_beneficiario,
              fecha_creado,
              id_estado_cliente,
              id_admin
            )
            values(
              '$fecha',
              '$fecha',
              $estitu,
              0,
              '$fecha',
              4,
              $elid
            );";
        $repguaregiex=ejecutar($guardregiexclu);

        $caulessonnueva="SELECT 
            propiedades_poliza.id_propiedad_poliza,
            propiedades_poliza.cualidad,
            propiedades_poliza.monto_nuevo 
          FROM 
            propiedades_poliza
          WHERE
            propiedades_poliza.id_poliza=$escojepoli;";
          $repcualesnueva=ejecutar($caulessonnueva);

        while($laspropiedadesnueva=asignar_a($repcualesnueva,NULL,PGSQL_ASSOC)){
          $idpropiedadnueva=$laspropiedadesnueva[id_propiedad_poliza];
          $cualidadpoliza=$laspropiedadesnueva[cualidad];
          $elmontonuevo=$laspropiedadesnueva[monto_nuevo];

          //buco para modificar
          $loquetieneactual="SELECT
              propiedades_poliza.id_propiedad_poliza 
            FROM 
              propiedades_poliza,coberturas_t_b
            WHERE
              propiedades_poliza.cualidad='$cualidadpoliza' and 
              coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and
              coberturas_t_b.id_titular=$estitu and
              coberturas_t_b.id_beneficiario=0";
          $reploqtieneactual=ejecutar($loquetieneactual);	
          $existeelcambio=num_filas($reploqtieneactual);
              
          if($existeelcambio<=0){
            $guardoencobetb="INSERT INTO
                coberturas_t_b(
                  id_propiedad_poliza,
                  id_titular,
                  id_beneficiario,
                  fecha_creado,
                  hora_creado,
                  id_organo,
                  monto_actual,
                  monto_previo) 
                values(
                  $idpropiedadnueva,
                  $estitu,
                  0,
                  '$fecha',
                  '$hora',
                  0,
                  '$elmontonuevo',
                  '$elmontonuevo');";
            $repguardboetb=ejecutar($guardoencobetb);
          }else{
            $datosactual=assoc_a($reploqtieneactual);
            $laipproactual=$datosactual[id_propiedad_poliza];

            $entoncesactualizo="UPDATE
                coberturas_t_b
              SET
                id_propiedad_poliza=$idpropiedadnueva,
                monto_actual='$elmontonuevo',
                monto_previo='$elmontonuevo'
              WHERE
                coberturas_t_b.id_titular=$estitu and
                coberturas_t_b.id_beneficiario=0 and
                coberturas_t_b.id_propiedad_poliza=$laipproactual";
            $repentoncesactualizo=ejecutar($entoncesactualizo);								     
          }
        }
      }else{
        $busprima="SELECT
            primas.id_prima,primas.anual
          FROM
            primas
          WHERE
            primas.id_poliza=$escojepoli AND
            primas.id_parentesco=$elparentesco AND 
            $edad>=primas.edad_inicio AND
            $edad<=primas.edad_fin;";
                                        
        $repbusprima=ejecutar($busprima);
        $infobusprima=assoc_a($repbusprima);
        $laidprimaes=$infobusprima[id_prima];
          
        $guarrecicarac="INSERT INTO
            tbl_caract_recibo_prima(
            id_recibo_contrato,
            id_titular,
            id_beneficiario,
            id_prima,
            fecha_creado,
            monto_prima,
            genera_comision) 
          values(
            $idrecibocontrato,
            $estitu,
            $esbeni,
            $laidprimaes,
            '$fecha',
            $nuevprima,
            1)";
        $repguarrecicarac=ejecutar($guarrecicarac);
            
        $guardregiexclu="INSERT INTO
          registros_exclusiones(
            fecha_inclusion,
            fecha_exclusion,
            id_titular,
            id_beneficiario,
            fecha_creado,
            id_estado_cliente,
            id_admin)
          values(
            '$fecha',
            '$fecha',
            $estitu,
            $esbeni,
            '$fecha',
            4,
            $elid);";
        $repguaregiex=ejecutar($guardregiexclu);

        $caulessonnueva="SELECT 
            propiedades_poliza.id_propiedad_poliza,
            propiedades_poliza.cualidad,
            propiedades_poliza.monto_nuevo 
          FROM 
            propiedades_poliza
          WHERE
            propiedades_poliza.id_poliza=$escojepoli;";
        $repcualesnueva=ejecutar($caulessonnueva);

        while($laspropiedadesnueva=asignar_a($repcualesnueva,NULL,PGSQL_ASSOC)){

          $idpropiedadnueva=$laspropiedadesnueva[id_propiedad_poliza];
          $cualidadpoliza=$laspropiedadesnueva[cualidad];
          $elmontonuevo=$laspropiedadesnueva[monto_nuevo];
          
          $loquetieneactual="SELECT
              propiedades_poliza.id_propiedad_poliza 
            FROM 
              propiedades_poliza,coberturas_t_b
            WHERE
              propiedades_poliza.cualidad='$cualidadpoliza' AND 
              coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza AND
              coberturas_t_b.id_titular=$estitu AND
              coberturas_t_b.id_beneficiario=$esbeni";
          $reploqtieneactual=ejecutar($loquetieneactual);	
          $existeelcambio=num_filas($reploqtieneactual);	

          if($existeelcambio<=0){

            $guardoencobetb="INSERT INTO
                coberturas_t_b(
                id_propiedad_poliza,
                id_titular,
                id_beneficiario,
                fecha_creado,
                hora_creado,
                id_organo,
                monto_actual,
                monto_previo)
              values(
                $idpropiedadnueva,
                $estitu,
                $esbeni,
                '$fecha',
                '$hora',
                0,
                '$elmontonuevo',
                '$elmontonuevo');";
            $repguardboetb=ejecutar($guardoencobetb);     

          }else{
            $datosactual=assoc_a($reploqtieneactual);
            $laipproactual=$datosactual[id_propiedad_poliza];

            $entoncesactualizo="UPDATE
                coberturas_t_b
              SET
                id_propiedad_poliza=$idpropiedadnueva,
                monto_actual='$elmontonuevo',
                monto_previo='$elmontonuevo'
              WHERE
                coberturas_t_b.id_titular=$estitu and
                coberturas_t_b.id_beneficiario=$esbeni and
                coberturas_t_b.id_propiedad_poliza=$laipproactual";
            $repentoncesactualizo=ejecutar($entoncesactualizo);								     
          }
        }
      }
    }
    if($i==1){
      //Guardar los datos en la tabla logs;
      $mensaje="$elus, ha renovado el contrato No. $numcontraprima";
      $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
      $inrelo=ejecutar($relog);
      
      ?>
      <link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
      <script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

      <!-- Redireccionar a reimcuadrorecibo.php -->
      <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
        <tr> 
        
          <td colspan=4 class="titulo_seccion">Contrato renovado exitosamente!!!</td>

          <td class="titulo_seccion">
            <label title="Imprimi cuadro recibo" class="boton" style="cursor:pointer" onclick="cuadroreciboR('<?echo $datcliente['cedula']?>','<?echo $idrecibocontrato?>')" >Imprimir</label>
          </td>
          <?echo"<td class=\"titulo_seccion\"><a href=\"views01/anexos.php?titular=$estitu&recibo=$idrecibocontrato\" title=\"Crear hoja de anexos\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:800, overlayClose: false}); return false;\">Hoja Anexo</a></td>";?>         
        </tr>
      </table>

      <?
    }
  }
}
  ?>
