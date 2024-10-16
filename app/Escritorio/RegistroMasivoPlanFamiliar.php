<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>


<?php
include ("../../lib/jfunciones.php");
include_once ("../../lib/Excel/reader.php");
$fecha=date("Y-m-d");
$hora=date("H:i:s");
//$nombredarchivo =$_REQUEST['elarchivo'];
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
//$data->setOutputEncoding('CP1251');
//$data->setOutputEncoding('utf-8');
//$data-->setUTFEncoder('iconv');
$data->setOutputEncoding('UTF-8');
///////////////////////////////////DATA///////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////DATA//////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////DATA//////////////////////////////////////////////////////////////////////////////////

$filename = "Carga masiva contraluria.xls";

///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////

$error=0;
if (file_exists("../../files/$filename")) {
    $data->read("../../files/$filename");
} else {
	$error=1;
	?>
	<br>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">No existe el Archivo: <?php echo "($filename)"?></td>
     </tr>
</table>
<?php }

?>
<br>


<?php if($error==0){



	?>
<input type="hidden" value='<?php echo $nombredarchivo?>' id='nombreachivo'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=4 class="titulo_seccion">Reporte de Facturas por Lote del Archivo: <?echo "($filename)"?></td>
     </tr>
</table>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1>
     <tr>

		 <th class="tdtitulos">1 Apellido</th>
		 <th class="tdtitulos">2 Nombre</th>
		 <th class="tdtitulos">3 Género</th>
		 <th class="tdtitulos">4 Fecha de nacimiento</th>
		 <th class="tdtitulos">5 Edad</th>
       	 <th class="tdtitulos">6 Cedula</th>
       		<th class="tdtitulos">7 Tipo Beneficiario/Titular</th>
        	<th class="tdtitulos">8 parentesco</th>
       		<th class="tdtitulos">9 Nacionalidad</th>
		 <th class="tdtitulos">10 estado Civil</th>        
		 <th class="tdtitulos">11 ESTADO RESIDENCIA</th>
		 <th class="tdtitulos">12 CIUDAD RESIDENCIA</th>
  		 <th class="tdtitulos">13 Direccion Residencia</th>
		 <th class="tdtitulos">14 Telf</th>
		 <th class="tdtitulos">15 Tlf Móvil2</th>
		 <th class="tdtitulos">16 EMEAL</th>
		 <th class="tdtitulos">17 COMENTARIO</th>
		 <th class="tdtitulos">18 Estado Del Cliente</th>
		 <th class="tdtitulos">19 CARGO</th>
		 <th class="tdtitulos">20 Fecha ingreso empresa</th>
		 <th class="tdtitulos">21 Fecha inclusión</th>
		 <th class="tdtitulos">22 MATERNIDAD S/N</th>
	</tr>

<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////CONFIGURAR ARCHIVO///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///EL ENTE
		$entclien='2503';//ID ENTE Contraloria
		$poliza='224';//ID PLAN FAMILIA SEGURA UNO
    ///Cambio de plan: renueva el y traslada todas las cualidades a la nueva
    // $TrasladoPlan='0';////si desea cambiar de plan id de plan anterior 0 si no desea cambiar
		$fecha=date("Y-m-d");
		$hora=date("H:i:s");
///contadores
		$clientesnuevos=0;
		$clienteExitentes=0;
		$cleintesactualizados=0;

		$cuatspoliza='1';//poliza
		$elid='425';//ID DE USUARIO SISTEMAS
		$clisubd='3';///Subdivicion Particular
		$codempleado=0;
		$partidaclien=0;
 		$registroData=0;
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////CONFIGURAR ARCHIVO///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////









/////VALIDAR Que el ente tenga esta poliza asiganda

$sqlPolizEnte=("select * from polizas_entes where  id_ente='$entclien' and id_poliza='$poliza';");
 $consultaPolizEnte=ejecutar($sqlPolizEnte);
$NunPolizaEnte=num_filas($consultaPolizEnte);
 				if($NunPolizaEnte<=0) {
										echo "<h1>LA POLIZA NO ESTA ASIGNADA A ESTE ENTE</h1>";
 							}else {//Poliza Exite en el ente
  
								$idTitular=0;
 for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { ///INTEREACION CON EL ARCHIVO EXEL Por Filas

	$registroData++;
	echo "<h1><br> CLEINTE $registroData <br>-------------------------------------<br></h1>";
		        $cedclien=$data->sheets[0]['cells'][$i][6];//Cedula Cliente
   echo "<h1><br>$cedclien---</h1>";
              $apecli=$data->sheets[0]['cells'][$i][1];//Apellidos
              $apecli=utf8_encode($apecli);
			  $nombrecli=$data->sheets[0]['cells'][$i][2];
		      $nombrecli=utf8_encode($nombrecli);

              $TipoTB=$data->sheets[0]['cells'][$i][7];/// ES Un Titular o es Beneficiario 
              $TipoCliente=strtoupper(substr($TipoTB,0,1));
			  echo "<h1>$TipoCliente</h1><br>";
              if($TipoCliente=='T')
              {
                $arrayTitulares['Ci']=$cedclien;
                $idTitular=0;
                $TipoCliente=1;///Titular 1

              }else{
				echo "<h2>Titular".$idTitular." </h2>";
                $BeneficiarioCi=$cedclien;
                $TipoCliente=2; ///Beneficiario 2
              }
              

               //PARENTESCO
               $Parentesco=trim($data->sheets[0]['cells'][$i][8]); 
               echo  $consParentesco="select * from parentesco where UPPER(parentesco)=UPPER('$Parentesco');";
                $ResParentesco=ejecutar($consParentesco);
              		$DataParentesco=assoc_a($ResParentesco);
              		$cuantosesta=num_filas($ResParentesco);
                if($cuantosesta==0)
                {
                    $idParentesco=3;//hijo
                }else{
                    $idParentesco=$DataParentesco['id_parentesco'];
                }
echo "<h1>parentes $idParentesco</h1><br>";
             //Fechas Exel              

			 $fecha_nac2=$data->sheets[0]['cells'][$i][4];
            //   echo "<h1><p>$fecha_nac2 ----> $val</p></h1>";
              //buscar que separador tiene
              $fechatex = strpos($fecha_nac2, '/');
              if($fechatex===false)
              { $fechatex = strpos($fecha_nac2, '-');
						if($fechatex !== false){
						$separador='-';
						} else {
						$separadorNoEncontado=true;
						$CambioFormato=true;
						}

              }
              else {
              $separador='/';
              }

              $f=explode($separador,$fecha_nac2);//separo la fecha
///primer valor explotado 0 DIA
					$dia=$f[0];
					$NumCant=strlen($dia);
              if($NumCant>2) {
						$DIA=substr($dia, 0, 2);
              }else {$DIA=$dia; }
   ///SEGUNDO VALOR EXLOTADO 1 MES
   				$mes=$f[1];
					$NumCant=strlen($mes);
              if($NumCant>2) {
						$MES=substr($mes, 0, 2);
              }else {$MES=$mes; }
   //TERCER VALOR explotado año 2
          			$aaa=$f[2];
					$NumCant=strlen($aaa);
              if($NumCant>4) {
						$AAA=substr($aaa, 4, 2);
						$CambioFormato=true;

              }else {$AAA=$aaa; }

              if($CambioFormato==true) {
					$AAA='19'.$AAA;
              $fechaFormato=$DIA.'-'.$MES.'-'.$AAA;
              $fecha_nac3 = ($fecha_nac2 - 25569) * 86400;
             $fnaciclien=date('d-m-Y',strtotime('+1 day',$fecha_nac3));
              }else {$fnaciclien=$DIA.'-'.$MES.'-'.$AAA;}


              $edad=intval($data->sheets[0]['cells'][$i][5]);//Edad
              echo "<h1><br>edad :$edad <br></h1>";
              $nacion=strtoupper(trim($data->sheets[0]['cells'][$i][9]));//CIUDADANIA
              $cliencivil=$data->sheets[0]['cells'][$i][10];//Estado Civil
              $sexo=$data->sheets[0]['cells'][$i][3];//Genero sexo
              if($sexo=='M' || $sexo=='M'){$genercli=1; echo "<h2><br>MASCULINO $sexo</h2>";
              }else {
              $genercli=0;}

              $estadoHab=$data->sheets[0]['cells'][$i][11];//estado
              $estadoHab=utf8_encode($estadoHab);
              $ciudad_recidencia=$data->sheets[0]['cells'][$i][12];//ciudad
              $ciudad_recidencia= utf8_encode($ciudad_recidencia);

              //IDcIUDAD
              	$BuscarCiudad=("select id_ciudad,ciudad from ciudad where ciudad=upper('$ciudad_recidencia')");
              	$ResCiudad=ejecutar($BuscarCiudad);
              	$cuantosres=num_filas($ResCiudad);
              	if($cuantosres>0) {
					$DataCiudad=assoc_a($ResCiudad);
					$id_ciudad=$DataCiudad['id_ciudad'];
					}else {
 				  $BuscarPais=("select * from pais where pais=upper('$nacion')");
              		$ResPais=ejecutar($BuscarPais);
              		$DataPais=assoc_a($ResPais);
              		$id_pais=$DataPais['id_pais'];
               	$buscarestado=("select * from estados where estado=upper('$estadoHab') and id_pais='$id_pais';");
              		$ResEstado=ejecutar($buscarestado);
              		$DataEstado=assoc_a($ResEstado);
              		$cuantosesta=num_filas($ResEstado);
              		if($cuantosesta>0) {
              			$id_estado=$DataEstado['id_estado'];

              		$InsertCiudad=("INSERT INTO ciudad(id_estado,ciudad,fecha_creado,hora_creado)
											VALUES ('$id_estado',upper('$ciudad_recidencia'),'$fecha','$hora')");
						$ResEstado=ejecutar($InsertCiudad);

              		}else {//ESTADO NO EXISTE
               		$insertEstado=("INSERT INTO estados(id_pais,estado,fecha_creado,hora_creado)
              											VALUES ('$id_pais',upper('$estadoHab'),'$fecha','$hora' )");
          $ResEstado=ejecutar($insertEstado);
              		$buscarestado=("select id_estado from estados where estado=upper('$estadoHab') and id_pais='$id_pais';");
              		$ResEstado=ejecutar($buscarestado);
              		$DataEstado=assoc_a($ResEstado);
              		$id_estado=$DataEstado['id_estado'];
               	$InsertCiudad=("INSERT INTO ciudad(id_estado,ciudad,fecha_creado,hora_creado)
											VALUES ('$id_estado',upper('$ciudad_recidencia'),'$fecha','$hora')");
						$ResEstado=ejecutar($InsertCiudad);

						}
              		$BuscarCiudad=("select id_ciudad,ciudad from ciudad where ciudad=upper('$ciudad_recidencia')");
              		$ResCiudad=ejecutar($BuscarCiudad);
              		$DataCiudad=assoc_a($ResCiudad);
						$id_ciudad=$DataCiudad['id_ciudad'];
					}
              //FIN DE IDcIUDAD recuperar el id Ciudad


              $direcclient=$data->sheets[0]['cells'][$i][13];//Direccion de Habitacion
              $direcclient=utf8_encode($direcclient);


              $clitf1=$data->sheets[0]['cells'][$i][14];//telefono1
              $clitf2=$data->sheets[0]['cells'][$i][15];//telefono2
              $correclie=$data->sheets[0]['cells'][$i][16];//Correo Electronico
              echo$comentclien=$data->sheets[0]['cells'][$i][17]; // comentarioz
   echo" <br>";
              echo $comentclien=utf8_decode($comentclien);
               echo" <br>";
              $ESTATUS=$data->sheets[0]['cells'][$i][18]; //Estado Del Cliente
              $ESTATUS=strtoupper($ESTATUS);
              if($ESTATUS=='ACTIVO') {
              $clienestatus=4;//ESTATUS Activo
              }else {$clienestatus=5;}//inactivo

              $ente=$entclien;
              $cargo=$data->sheets[0]['cells'][$i][19];
              $BCargo=trim($cargo);
	////BUSCAR EL CARGO
	$buscaridcargo=("select id_cargo from cargos where cargo='$BCargo';");
$buscaridcargo=ejecutar($buscaridcargo);
$cargoExiste=num_filas($buscaridcargo);
if($cargoExiste>0) {
$cargoencontrado=assoc_a($buscaridcargo);
$carclient=$cargoencontrado['id_cargo'];//idcargo
}else {
	//registrar cargo nuevo
$RegNuevoCargo=("INSERT INTO cargos(cargo,fecha_creado,hora_creado) VALUES ('$BCargo','$fecha','$hora');");
$cargoNuevo=ejecutar($RegNuevoCargo);
$RecuperarIDCargo=("select max(id_cargo) from cargos");//recuperar id
$RecuperarCargoNuevo=ejecutar($RecuperarIDCargo);
$idMAX=assoc_a($RecuperarCargoNuevo);//recuperar id asociar
$carclient=$idMAX['max'];//idcargo
}

              //tratar Fecha Exell
              	$ingresoclienexell=$data->sheets[0]['cells'][$i][21]; //FECHA DE INGRESO A LA EMPRESA

              //buscar que separador tiene
              $fechatex = strpos($ingresoclienexell, '/');
              if($fechatex===false)
              { $fechatex = strpos($ingresoclienexell, '-');
						if($fechatex !== false){
						$separador='-';
						}else {
  						$separadorNoEncontado=true;
  						$CambioFormato=true;
						}

              }
              else {
              $separador='/';
              }
echo  "<h1>fechareg: $ingresoclienexell </h1>";

              $f=explode($separador,$ingresoclienexell);//separo la fecha
		///primer valor explotado 0 DIA
					$dia=$f[0];
					$NumCant=strlen($dia);
              if($NumCant>2) {
						$DIA=substr($dia, 0, 2);
              }else {$DIA=$dia; }
   	///SEGUNDO VALOR EXLOTADO 1 MES
   					$mes=$f[1];
					$NumCant=strlen($mes);
              if($NumCant>2) {
						$MES=substr($mes, 0, 2);
              }else {$MES=$mes; }
   	//TERCER VALOR explotado año 2
          			$aaa=$f[2];
					$NumCant=strlen($aaa);
              if($NumCant>4) {
						$AAA=substr($aaa, 4, 2);
						$CambioFormato=true;

              }else {$AAA=$aaa; }

              if($CambioFormato==true) {
					$AAA='19'.$AAA;
              $fechaFormato=$DIA.'-'.$MES.'-'.$AAA;
              $fecha_nac3 = ($ingresoclienexell - 25569) * 86400;
             echo$ingresoclien=date('d-m-Y',strtotime('+1 day',$fecha_nac3));
              }else {$ingresoclien=$DIA.'-'.$MES.'-'.$AAA;}

              //tratar Fecha Exell
              $inclufechaexel=$data->sheets[0]['cells'][$i][22]; //FECHa de inclusion
					$inclufecha2 = ($inclufechaexel - 25569) * 86400;

              	$inclufecha=date('d-m-Y',strtotime('+1 day',$inclufecha2));//FECHA DE INCLUSION

              $maternidad=$data->sheets[0]['cells'][$i][22];

              if($maternidad==NULL || $maternidad=='') {
              	$conmat='null';
              }else {
              $maternidad=substr("$maternidad", 0, 1);
  //            echo "MATERNIDAD $maternidad";
              if($maternidad=='N' ) {$conmat='0';}else {$conmat='1';}
					}


//         echo "<h3>FIN: primer 1 bloque</h3>";




///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////INSERTAR DATOS ////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////






/////////////////////////insercion de datos//////////
///1)verificar registro de cliente//
$buscoexiste=("select id_cliente from clientes where cedula='$cedclien';");
$repbuscoexiste=ejecutar($buscoexiste);
$dataexiste=assoc_a($repbuscoexiste);
$cuantosexiste=num_filas($repbuscoexiste);
///Existe el cliente?
if($cuantosexiste>=1) {
		$elidcliente=$dataexiste['id_cliente'];
		$cleintesactualizados+=1;
		//Actualizar Cliente
		$actdatoscliente=("update clientes set direccion_hab='$direcclient',telefono_hab='$clitf1',telefono_otro='$clitf2',comentarios=upper('$comentclien') where clientes.id_cliente='$elidcliente';");
		$repactdatoscliente=ejecutar($actdatoscliente);

		$clienteExitentes+=1;
	echo "<h1>ACTUALIZAR ESTE CLEINTE CLENTE</h1>";


}else {
		//NUEVO CLIENTE/////////////////
		$clientesnuevos+=1;
		//Guardar los datos en la tabla clientes
		echo $guardarcliente=("insert into clientes(nombres,apellidos,fecha_nacimiento,sexo,direccion_hab,
                               telefono_hab,telefono_otro,email,fecha_creado,hora_creado,
                               id_ciudad,comentarios,cedula,estado_civil,id_admin,edad)
                               values(upper('$nombrecli'),upper('$apecli'),'$fnaciclien','$genercli',
                               upper('$direcclient'),'$clitf1','$clitf2','$correclie','$fecha',
                                '$hora','$id_ciudad',upper('$comentclien'),'$cedclien',upper('$cliencivil'),'$elid','$edad');");
 		$resguardclien=ejecutar($guardarcliente);
		//fin de los registros en la tabla clientes;
		//Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla titular
		$busclient=("select id_cliente from clientes where cedula='$cedclien';");
		$resbusclient=ejecutar($busclient);
		$datbusclien=assoc_a($resbusclient);
		$elidcliente=$datbusclien['id_cliente'];
		echo "<h1>REGISTRAR NUEVO CLEINTE CLENTE</h1>";
		//fin de la busquedad;
	}//fin if



					///asiganar MATERNIDAD A FEMENINOS
					if ($genercli==0){
	 						if ($conmat=="null"){ $tmater=0; }
									else{ $tmater=1;	}
					}else{ $tmater=0;  }

			//reviso si ya esta el titular en ese ente
    //Cambio de PLAN TRASLADAR PAL ACTUAL A LA nuevoAjax
    $numversisotituAct=0;
    if($TrasladoPlan>0){
      echo "USAR los titulares DE id_poliza $TrasladoPlan y ID ENTE $entclien ";
      echo $revisotituTr=("select titulares.id_titular,titulares.id_cliente,titulares.id_ente,titulares_polizas.id_poliza from titulares,titulares_polizas where  titulares_polizas.id_titular=titulares.id_titular and titulares.id_cliente='$elidcliente' and titulares.id_ente='$entclien' and titulares_polizas.id_poliza='$TrasladoPlan';");
      $reprevisotituAct=ejecutar($revisotituTr);
			$numversisotituAct=num_filas($reprevisotituAct);
    }

      echo "USAR los titulares DE NUEVA POLIZA $poliza y ID ENTE $entclien ";
      echo $revisotitu=("select titulares.id_titular,titulares.id_cliente,titulares.id_ente,titulares_polizas.id_poliza from titulares,polizas_entes,titulares_polizas where polizas_entes.id_poliza=titulares_polizas.id_poliza and titulares_polizas.id_titular=titulares.id_titular and polizas_entes.id_ente=titulares.id_ente and titulares.id_cliente=$elidcliente and titulares.id_ente=$entclien and titulares_polizas.id_poliza='$poliza'");
		 	$reprevisotitu=ejecutar($revisotitu);

			$numversisotitu=num_filas($reprevisotitu);

      //si esta en la poliza videja hacer actualizaciones
      $titularExite=0; //si se  amtienen en 0 registrar
      if($numversisotituAct>0) //exite en la poliza anerior y hay que actulizar
      { $titularExite=1;
        $datarevisotitu=assoc_a($reprevisotituAct);
      }

      if($numversisotitu>0)//exite en la poliza actual y hay que actulizar
      { $titularExite=1;
        $datarevisotitu=assoc_a($reprevisotitu);
      }



	  ///BERIFICAION DE CLIENTES BENEFICIARIO O TITULAR 

	  ///SI EXISTE COMO TITULAR DE LA POLIZA ACTUALIZAR -- SI ES BENEFICIARIO DE LA POLIZA Y EXISTE ACTUALIZAR
	  if($TipoCliente==1 && $titularExite==1)//TITULAR
	  {$idBeneficiario=0;
		$titularExite=1;
	  }else if($TipoCliente==1 && $titularExite<=1){//TITULAR
		$titularExite=0;
		$idBeneficiario=0;
	  }else if($TipoCliente==2){//TITULAR
		
			$SqlVerifiqueBenef="select 
			titulares.id_titular,titulares.id_cliente,titulares.id_ente,titulares_polizas.id_poliza, beneficiarios.id_beneficiario 
			from titulares,polizas_entes,titulares_polizas,beneficiarios where 
			beneficiarios.id_titular=titulares.id_titular and 
			polizas_entes.id_poliza=titulares_polizas.id_poliza and 
			titulares_polizas.id_titular=titulares.id_titular and polizas_entes.id_ente=titulares.id_ente and beneficiarios.id_cliente='$elidcliente' and titulares.id_ente='$entclien' and titulares_polizas.id_poliza='$poliza';";
			$ejeVerificaBeneficiario=ejecutar($SqlVerifiqueBenef);

			$numversisotitu=num_filas($ejeVerificaBeneficiario);
			if($numversisotitu>0){
				$titularExite=1;
				$BeneficirioExiste=assoc_a($numversisotitu);
				$idBeneficiario=$BeneficirioExiste['id_beneficiario'];
				$elidtitulares=assoc_a($numversisotitu);
			}else{
				$titularExite=0;
				$idBeneficiario=0;
			}

	  }





	//Nuevo Titular
 

if($titularExite<=0){


    if($TipoCliente==1) {  //ES un titular
                    echo "<h3>Primer 5 NUEVO TITULAR id CLIENTE:$elidcliente</h3>";

                echo$guardaclientitu=("insert into titulares(id_cliente,fecha_ingreso_empresa,fecha_creado,
                                        hora_creado,id_ente,fecha_inclusion,id_admin,codigo_empleado,
                                        maternidad,tipo_partida) values('$elidcliente','$ingresoclien','$fecha',
                                        '$hora','$entclien','$inclufecha','$elid','$codempleado','$tmater',$partidaclien ) RETURNING id_titular;");
                $resguatitularcliente=ejecutar($guardaclientitu);
                //fin de los registros en la tabla titular;
                //Buscar el cliente guardado en la tabla titulares

            $idnevtitu=assoc_a($resguatitularcliente);
            $elidtitulares=$idnevtitu['id_titular'];
                //fin de la busquedad;
                $idTitular = $elidtitulares; 
				
        echo "<h3>Primer 5.1 NUEVO TITULAR $elidtitulares-<br></h3>";

                //Guardar los datos en la tabla titulares_subdivisiones;
                $guartitsubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$elidtitulares', '$clisubd');");
                $restitusbdivi=ejecutar($guartitsubdivi);
                //fin de los registros en la tabla titulares_subdivisiones;
        echo "<h3>SUBDIVISION<br></h3>";

                //Guardar los datos en la tabla titulares_cargos;
                $guartituscargos=("insert into titulares_cargos(id_titular,id_cargo,fecha_creado,hora_creado)
                                        values('$elidtitulares','$carclient','$fecha','$hora');");
                $restitucargos=ejecutar($guartituscargos);
        echo "<h3>CARGOS TITULARES<br></h3>";

         }


         if($TipoCliente==2) {  //ES un Beneficiario
           $idTitular= $idTitular;///RECUPERAR EL ULTIMO TITULAR
         echo$sqlBeneficiario="INSERT INTO beneficiarios(id_cliente,id_titular,id_parentesco,fecha_creado,hora_creado,fecha_inclusion,id_tipo_beneficiario,maternidad)
         VALUES ('$elidcliente','$idTitular','$idParentesco','$fecha','$hora','$fecha','7','$conmat') RETURNING id_beneficiario;";
         	$Beneficiario=ejecutar($sqlBeneficiario);
             $Benef=assoc_a($Beneficiario);
             $idBeneficiario=$Benef['id_beneficiario'];
        }else{
            $idBeneficiario=0;
        }
   

		//Guardar los datos en la tabla estados_t_b;
		$guardtituestado=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,
                                   fecha_creado,hora_creado) values('$clienestatus','$elidtitulares','$idBeneficiario',
                                   '$fecha','$hora');");
		$respdtituestado=ejecutar($guardtituestado);
		//fin de los registros en la tabla  estados_t_b;
echo "<h3>ESTADOS TB<br></h3>";

			//Guardar los datos en la tabla coberturas_t_b; guardo N veces
			//Primero busco las propiedades de la poliza al cual pertenece dependiendo del genero

 echo "<h3>Primer 6 NUEVO TITULAR Propiedades $elidtitulares<br></h3>";
		for ($m=1;$m<=$cuatspoliza;$m++){
				$polizatitular1=$poliza;

	   		if (($conmat=="null") || ($genercli==1)){
		 			$condgen1="and sexo=2 order by cualidad;";
				}else{
					$condgen1="order by cualidad";
				}
						$buscpropipolizas=("select propiedades_poliza.id_propiedad_poliza,
									 propiedades_poliza.id_poliza,propiedades_poliza.cualidad,
                                     propiedades_poliza.sexo,propiedades_poliza.organo,
                                     propiedades_poliza.monto_nuevo from propiedades_poliza
									where id_poliza='$polizatitular1' $condgen1");

						$respupropolizas=ejecutar($buscpropipolizas);
       				//Segundo registramos en la tabla coberturas_t_b las propiedades de la poliza segun el tipo de la poliza
	  					//también debemos estar pendiente de registrar los planes basicos
           			while($laspolizason=asignar_a($respupropolizas,NULL,PGSQL_ASSOC)){
			   			$lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
			   			$esorgano=$laspolizason['organo'];
			   			$elmonto=$laspolizason['monto_nuevo'];
			   			$regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,id_organo,monto_actual,monto_previo)
						values('$lapropiedadpoliza','$elidtitulares','$idBeneficiario','$fecha','$hora','$esorgano','$elmonto','$elmonto');");
							$respuecobetb=ejecutar($regiscobetb);
						}
			}//FOR//find de coberturas_t_b

 echo "<h3>Primer 7 NUEVO TITULAR POLIZA $elidtitulares<br></h3>";
            if($TipoCliente==1) { ///titular
				//Guardar los datos en la tabla titulares_polizas;
  				$guardamospoliza=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado)
					            	values('$elidtitulares','$poliza','$fecha','$hora');");
     			$repguardaspoliza=ejecutar($guardamospoliza);

             }

          //fin if titular	Nuevo
		}else {//actalizar los titulares existentes

 echo "<h3>Primer 8 ACTUALIZAR TITULAR POLIZA $poliza<br></h3>";

	

			$elidtitulares=$datarevisotitu['id_titular'];
			$idTitular=$elidtitulares;
			//buscarga titulares Cargo
			$idBeneficiario=$idBeneficiario;
echo "<h3><br>ACTUALIZAR CARGO $poliza  <p>TITULAR $elidtitulares o Beneficia $idBeneficiario</p><br></h3>";
if($TipoCliente==1) { 
	echo $buscarCargoTitulares=("select id_titular_cargo,id_cargo from titulares_cargos where id_titular='$elidtitulares';");
				$RespCargocliente=ejecutar($buscarCargoTitulares);
				$DataCargoCli=assoc_a($RespCargocliente);
				$IDCargoClienteActual=$DataCargoCli['id_cargo'];
	echo"<h2>CRGA:$IDCargoClienteActual</h2>";
	//exise
		if($IDCargoClienteActual=='' || $IDCargoClienteActual==null) {
			////NUEVO CARGO
	echo"<h2>CRGA:BACIO </h2>";
			//Guardar los datos en la tabla titulares_cargos;
			$guartituscargos=("insert into titulares_cargos(id_titular,id_cargo,fecha_creado,hora_creado)
									values('$elidtitulares','$carclient','$fecha','$hora');");
			$restitucargos=ejecutar($guartituscargos);

			}else if($IDCargoClienteActual<>$carclient)
			{			//actualizar el cargo
				$CargoTitular=$DataCargoCli['id_titular_cargo'];
				//echo "ACTUALICE $CargoTitular";
				$ConsultaActualizarCargo=("update titulares_cargos set id_cargo='$carclient' where id_titular_cargo='$CargoTitular';");
				$ActualizarCargo=ejecutar($ConsultaActualizarCargo);
			}
		}
/////////////////////////////////////////////////////////////////////////////////
//Guardar los datos en la tabla coberturas_t_b;/////COBERTURAS T B
//ACTUALIZAR ESTADOS_T_B TITULAR BENEFICIARIO///

		//recuperar el id estados t b
		echo "<h2>ESTADOS TB de id_titular=$elidtitulares<br></h2>";
					echo$SqlEstadosTB=("select id_estado_t_b from estados_t_b where id_titular='$elidtitulares' and id_beneficiario='$idBeneficiario'; ");
					$RespStadosTB=ejecutar($SqlEstadosTB);
					$DataStadosTB=assoc_a($RespStadosTB);
					$id_estado_t_b=$DataStadosTB['id_estado_t_b'];
					if($id_estado_t_b=='' || $id_estado_t_b=NULL) {
//echo "<b><h1> INSERTAR NO SE ENCONTRO EL ESTADO</h1>";
			//no existe en estados tb
							$guardtituestado=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,
                                   fecha_creado,hora_creado) values('$clienestatus','$elidtitulares','$idBeneficiario',
                                   '$fecha','$hora');");
							$respdtituestado=ejecutar($guardtituestado);
							//fin de los registros en la tabla  estados_t_b;
						}else {
					//ESTADOS_T_B ACTUALIZAR
					echo "<h4> actualizar estatus </h4>";
					$guardtituestado=("update estados_t_b set id_estado_cliente='$clienestatus', fecha_modificado='$fecha',hora_modificado='$hora' where id_titular='$elidtitulares' and id_beneficiario='$idBeneficiario';");
					$respdtituestado=ejecutar($guardtituestado);
					}

/////////BUSCAR QUE EL TITULAR TENGA SUBDIVISION///
if($TipoCliente==1) { 
		$SqlSubDivision=("select  id_titular_subdivision,id_titular,id_subdivision from titulares_subdivisiones where id_titular='$elidtitulares';");
		$ResSubdivision=ejecutar($SqlSubDivision);
		$ExisteSubdivision=num_filas($ResSubdivision);
		if($ExisteSubdivision<=0) {
	//Guardar los datos en la tabla titulares_subdivisiones;
		$guartitsubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$elidtitulares', '$clisubd');");
		$restitusbdivi=ejecutar($guartitsubdivi);

		}
	}
//fin de los registros en la tabla titulares_subdivisiones;

//Primero busco las propiedades de la poliza al cual pertenece dependiendo del genero
echo "<h1>nun de Polizas: $cuatspoliza<br></h1>";

for ($m=1;$m<=$cuatspoliza;$m++){
	$polizatitular1=$poliza;///poliza a renovar
	$ConsulTitularPoliza=("select id_titular_poliza,id_titular,id_poliza from titulares_polizas where id_titular ='$elidtitulares' and id_poliza='$polizatitular1'");
	$RespCargocliente=ejecutar($ConsulTitularPoliza);
	$DataTitularPoliza=assoc_a($RespCargocliente);
 	$IdTitularPoliza=$DataTitularPoliza['id_titular_poliza'];
 	//recupera Id Titular
	$ExistenTitularPoliza=num_filas($RespCargocliente);

	   if (($conmat=="null") || ($genercli==1)){
		 $condgen1="and sexo=2 order by cualidad;";
		}else{
			$condgen1="order by cualidad";
			}

///ESTA INSCRITO EN LA POLIZA
echo "<br><h1>PROPIEDADES POLIZAS: ACTUA <br></h1>";

echo $buscpropipolizas=("select propiedades_poliza.id_propiedad_poliza,
									 propiedades_poliza.id_poliza,propiedades_poliza.cualidad,
                                     propiedades_poliza.sexo,propiedades_poliza.organo,
                                     propiedades_poliza.monto_nuevo from propiedades_poliza
									where id_poliza='$polizatitular1' $condgen1;");

$respupropolizas=ejecutar($buscpropipolizas);
echo"<br><h3>$ExistenTitularPoliza</h3>";
if($ExistenTitularPoliza<=0){//Si No existe el titular en esta poliza Registrarlo
	//ACTUALIZAR COBERTURAS
    ///caso 1 el titular no existe en la poliza pero se ara un traslado
  if($TrasladoPlan>0)
    { //CONSULTA DE Propiedades poliza
      ////buscamos las propiedades existentes
      echo "<br>ACTUALIZAR y TRASLAAR POLIZA ACTUAL: $TrasladoPlan a $polizatitular1 <br>";
      while($laspolizason=asignar_a($respupropolizas,NULL,PGSQL_ASSOC)){
            $cualidad=$laspolizason['cualidad'];
            $lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
   			    $esorgano=$laspolizason['organo'];
   			    $elmonto=$laspolizason['monto_nuevo'];

            ///buscar las cualidad en la poliza anterior
            $BuscarCoberturaCulid=("select id_cobertura_t_b,propiedades_poliza.id_propiedad_poliza,propiedades_poliza.cualidad,propiedades_poliza.monto_nuevo from propiedades_poliza,coberturas_t_b where
                      coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and propiedades_poliza.cualidad='$cualidad' and
                      coberturas_t_b.id_titular='$elidtitulares' and id_beneficiario=$idBeneficiario and
                       id_poliza='$TrasladoPlan' limit 1; ");
            $CoberCualidad=ejecutar($BuscarCoberturaCulid);
            $CantidaCoinc=num_filas($CoberCualidad);
            echo "<br>ACTUALIZAR $CantidaCoinc";
            if($CantidaCoinc>0)
            { //si se encuentra la Cualidad Acturlaixzamos Con el id Propiedad de la nueva(id_cobertura_t_b)
              $PlanActTitularCualidad=assoc_a($CoberCualidad);
              $idCobertura=$PlanActTitularCualidad['id_cobertura_t_b'];

              $sqlActualizaCobertura="update coberturas_t_b set id_propiedad_poliza='$lapropiedadpoliza',fecha_modificado='$fecha',hora_modificado='$hora', id_organo='$esorgano', monto_actual='$elmonto', monto_previo='$elmonto' where id_cobertura_t_b='$idCobertura'; ";
              $CoberActualizada=ejecutar($sqlActualizaCobertura);
            }else{
              echo"<br>INSRTAR :: $lapropiedadpoliza";
              ///si no se encontro la cualidad se insertar
              $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                            hora_creado,id_organo,monto_actual,monto_previo)
      										   values('$lapropiedadpoliza','$elidtitulares','$idBeneficiario','$fecha','$hora','$esorgano',
                             '$elmonto','$elmonto');");
      				$respuecobetb=ejecutar($regiscobetb);

            }
            ////QUE PASA CON LAS COVERTURAS QUE NO EXISTEN EN EL NUEVO PLAN



      }//fin while
      ////ACTUALIZAR titulares_polizas
      //Guardar los datos en la tabla titulares_polizas;
      $guardamospoliza=("update titulares_polizas set fecha_modificado='$fecha', hora_modificado='$hora', id_poliza='$poliza' where id_titular_poliza='$elidtitulares' ;");
        $repguardaspoliza=ejecutar($guardamospoliza);

    }else{

      echo"<br> insertar Nuevo";
      //INSERTAR
	  //Segundo registramos en la tabla coberturas_t_b las propiedades de la poliza segun el tipo de la poliza
	  //también debemos estar pendiente de registrar los planes basicos
    while($laspolizason=asignar_a($respupropolizas,NULL,PGSQL_ASSOC)){
           	echo"<h1>insertar Coberturas No existe el Titular</h1>";
			   $lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
			   $esorgano=$laspolizason['organo'];
			   $elmonto=$laspolizason['monto_nuevo'];
			  echo $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                                          hora_creado,id_organo,monto_actual,monto_previo)
										   values('$lapropiedadpoliza','$elidtitulares','$idBeneficiario','$fecha','$hora','$esorgano',
                                                       '$elmonto','$elmonto');");
				$respuecobetb=ejecutar($regiscobetb);
			}
			if($TipoCliente==1) { 
				//Guardar los datos en la tabla titulares_polizas;
 			 	$guardamospoliza=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado)
										values('$elidtitulares','$poliza','$fecha','$hora');");
     			$repguardaspoliza=ejecutar($guardamospoliza);
			}
    }
	}else {//ACTUALIZAR
	echo "<h2>ACTULAIZAR poliza Nueva TITULARES<h2>";

	//Guardar los datos en la tabla titulares_polizas;
  		$guardamospoliza=("update titulares_polizas set fecha_modificado='$fecha', hora_modificado='$hora' where id_titular_poliza='$IdTitularPoliza' ;");
     	$repguardaspoliza=ejecutar($guardamospoliza);
	///actualizar Coberturas

///BUSCAR LAS COBERTULAS EXISTENTES
		while($laspolizason=asignar_a($respupropolizas,NULL,PGSQL_ASSOC)){
					     	$IdPropiedadPoliza=$laspolizason['id_propiedad_poliza'];//id_propiedad_Poliza

					 //    	echo "<h1>BUSQUE COBERTURA  </h1><br>";


					     $BuscarCualidadPolizaTitular=("select * from coberturas_t_b where id_propiedad_poliza='$IdPropiedadPoliza' and id_titular='$elidtitulares'");
			   			 $respuestaPropiPoliza=ejecutar($BuscarCualidadPolizaTitular);
			   			//Si esta propidad esta asiganada al titular se actualiza
			   			 $ExistenPropiedadPoliza=num_filas($respuestaPropiPoliza);
			   		//			echo "<h1>ACTUALICE COBERTURA  ( $ExistenPropiedadPoliza )</h1><br>";
			   			if($ExistenPropiedadPoliza>0) {
			   					//Actualizar Los Montos Yfechas de Modificado
			   					$lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
			  						 $esorgano=$laspolizason['organo'];
			  							 $elmonto=$laspolizason['monto_nuevo'];
			  						 $regiscobetb=("update  coberturas_t_b set fecha_modificado='$fecha',hora_modificado='$hora',monto_actual='$elmonto',monto_previo='$elmonto'  where id_propiedad_poliza='$IdPropiedadPoliza' and id_titular='$elidtitulares'");
									 $respuecobetb=ejecutar($regiscobetb);
								}else {

								//insertar cualidad No existente
								//echo "<h2>---->No existe esta Cualidad en la POLIZA</h2><br>";

											$lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
			  								 $esorgano=$laspolizason['organo'];
			  								 $elmonto=$laspolizason['monto_nuevo'];
			 							 $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                                    hora_creado,id_organo,monto_actual,monto_previo)
										                values('$lapropiedadpoliza','$elidtitulares','$idBeneficiario','$fecha','$hora','$esorgano',
                                             '$elmonto','$elmonto');");
										  $respuecobetb=ejecutar($regiscobetb);
								}
			}	//While Propiedades

	}

}//FOR
//find de coberturas_t_b



} //////FIN ELSE//

///////////////////////////////////////

?>



		<tr>
			<td ><?php echo $apecli;?></td>
	       <td ><?php echo $nombrecli;?></td>
		   <td ><?php echo $sexo;?></td>
	       <td ><?php echo $fnaciclien;?></td>
	       <td ><?php echo $edad;?></td>
	       <td ><?php echo $cedclien;?></td>		   
		   <td ><?php echo $TipoTB;?></td>
		   <td ><?php echo $Parentesco;?></td>
	       <td ><?php echo $nacion;?></td>
	       <td ><?php echo $cliencivil;?></td>
	       <td ><?php echo $estadoHab;?></td>
	       <td ><?php echo $ciudad_recidencia;?></td>
          <td ><?php echo $direcclient;?></td>
          <td ><?php echo $clitf1;?></td>
          <td ><?php echo $clitf2;?></td>
          <td ><?php echo $correclie;?></td>
          <td ><?php echo $comentclien;?></td>
          <td ><?php echo $ESTATUS;?></td>
          <td ><?php echo $ente;?></td>
          <td ><?php echo $cargo;?></td>
          <td ><?php echo $ingresoclien;?></td>
          <td ><?php echo $inclufecha;?></td>
          <td ><?php echo $maternidad;?></td>

       </tr>

<?php	} //cierre For

}//CIERRE de ELSE VERIFICA POLIZA ENTE
}//cierre if


/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////


  ?>
 <tr>
 <td colspan="16"> clientes</td>
 <td >Total</td>
 <td ><?php echo $i;?></td>
 <td ><?php echo $clientesnuevos;?></td>
 <td >No Existentes</td>
 <td ><?php echo $clienteExitentes;?></td>
 </tr>
</table>


</html>
