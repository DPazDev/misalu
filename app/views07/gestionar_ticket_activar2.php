<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$fechaActivacion=$fecha;
$fechaVencimiento=date("Y-m-d",strtotime($fecha."+ 1 year"));
$hora=date("H:i:s");
$idtickera=$_POST['idtickera'];
$cedula=$_POST['cedula'];
$vendedorid=$_POST['vendedorid'];
$nombrecliente=$_POST['nombrecliente'];
$clienapellido=$_POST['clienapellido'];

$fenaci=$_POST['fechanaci'];
$generocliente=$_POST['generocliente'];
$correocliente=$_POST['correocliente'];
$clientf1=$_POST['clientf1'];
$clientf2=$_POST['clientf2'];
$edocivil=$_POST['edocivil'];
$paiscli=$_POST['paiscli'];
$estado=$_POST['estado'];
$ciudad=$_POST['ciudad'];
$cliendir=$_POST['cliendir'].'CORREO:'.$correocliente;
$cliencoment=$_POST['cliencoment'];

//Activar la tikera
$verificaTikera=("select * from tbl_tickeras where id_tickera='$idtickera';");
$VerfTikera=ejecutar($verificaTikera);
$regencontados=num_filas($VerfTikera);
  if($regencontados==0){
    echo "verefique datos de Tickera";
  }
  else
  {
      ////datos de poliza y ente PARTICULAR
      $buesente=("select entes.id_ente from entes where entes.nombre='PARTICULAR';");
      $repbusente=ejecutar($buesente);
      $databusente=assoc_a($repbusente);
      $bussdivi=("select subdivisiones.id_subdivision from subdivisiones where subdivisiones.subdivision='PARTICULAR';");
      $repsudivi=ejecutar($bussdivi);
      $datasubdivi=assoc_a($repsudivi);
      //
      $elid=$_SESSION['id_usuario_'.empresa];
      $elus=$_SESSION['nombre_usuario_'.empresa];

      //
      $lasubdivi=$datasubdivi['id_subdivision'];
      $elidente=$databusente['id_ente'];

    ///buscar si existe el cliente
    $consultarCliente=("select * from clientes where cedula='$cedula';");
    $cliente=ejecutar($consultarCliente);

    $clienteEncontrado=num_filas($cliente);
    if($clienteEncontrado>=1)
    {   $datbusclien=assoc_a($cliente);
        $elidclienes=$datbusclien['id_cliente'];
        echo $consultarCliente=("select * from titulares where id_cliente='$elidclienes' and id_ente='$elidente';");
        $cliente=ejecutar($consultarCliente);
        $clienteEncontrado=num_filas($cliente);
        if($clienteEncontrado==0)
            { ////activa el if registrar titular
              $registrarTitular=true;
            }else{$registrarTitular=false;}

    }else {
        //CLIENTE NUEVO
        $cedclien=$cedula;
        $nombrecli=$nombrecliente;
        $apecli=$clienapellido;
        $genrot=$generocliente;
        $teleft=$clientf1;
        $teleft2=$clientf2;
        $fenaci=$fenaci;
        $fedad=calcular_edad($fenaci);
        $edocivil=$edocivil;
        $lciudad=$ciudad;
        $directi=$cliendir;
        $comenta=$cliencoment;
        $estatusti=4;
        echo$guardarcliente=("insert into clientes(nombres,apellidos,fecha_nacimiento,sexo,direccion_hab,
                                       telefono_hab,telefono_otro,fecha_creado,hora_creado,
                                       id_ciudad,comentarios,cedula,estado_civil,id_admin,edad)
                                       values(upper('$nombrecli'),upper('$apecli'),'$fenaci','$genrot',
                                       upper('$directi'),'$teleft','$teleft2','$fecha',
                                        '$hora',$lciudad,upper('$comenta'),'$cedclien',upper('$edocivil'),'$elid','$fedad');");
        $resguardclien=ejecutar($guardarcliente);

        //fin de los registros en la tabla clientes;
        /***********************************/
        //Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla titular
        $busclient=("select clientes.id_cliente from clientes where clientes.cedula='$cedclien';");
        $resbusclient=ejecutar($busclient);
        $datbusclien=assoc_a($resbusclient);
        $elidclienes=$datbusclien['id_cliente'];

      ////activa el if registrar titular
        $registrarTitular=true;
    }
////////////////////////////////REGISTRAR TITULAR SINO EXISTE ente PARTICULAR//////
    if($registrarTitular==true){
      //registrar si no existe el titular
      $guardaclientitu=("insert into titulares(id_cliente,fecha_ingreso_empresa,fecha_creado,
                                       hora_creado,id_ente,fecha_inclusion,id_admin,
                                       maternidad) values('$elidclienes','$fecha','$fecha',
                                      '$hora','$elidente','$fecha','$elid','0');");
      $resguatitularcliente=ejecutar($guardaclientitu);
      //Guardar los datos en la tabla titulares_subdivisiones;

    }
    /////////////////fin REGISTRO PARTICULAR//////////////////////

    //**********************************//
    //Buscar el cliente guardado en la tabla titulares
    $bustituclien=("select id_titular from titulares
    where id_cliente='$elidclienes' and id_ente='$elidente';");
    $resptituclien=ejecutar($bustituclien);
    $dattituclien=assoc_a($resptituclien);
    $elidtitulares=$dattituclien['id_titular'];
    //fin de la busquedad;

    //**********************************//
    if($registrarTitular==true){
              $guartitsubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$elidtitulares','$lasubdivi');");
              $restitusbdivi=ejecutar($guartitsubdivi);
              //fin de los registros en la tabla titulares_subdivisiones;
              //**********************************//
              //**********************************//
              //Guardar los datos en la tabla estados_t_b;
              $guardtituestado=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,
                                                 fecha_creado,hora_creado) values('$estatusti','$elidtitulares','0',
                                                 '$fecha','$hora');");
              $respdtituestado=ejecutar($guardtituestado);
              //fin de los registros en la tabla  estados_t_b;
              //**********************************//
              $lapoliza=("select polizas_entes.id_poliza from polizas_entes where polizas_entes.id_ente='$elidente';");
              $replapoliza=ejecutar($lapoliza);
              $datpoliza=assoc_a($replapoliza);
              $elidpoliza=$datpoliza['id_poliza'];
              $laspropolizas=("select propiedades_poliza.id_propiedad_poliza,propiedades_poliza.monto_nuevo
                                          from propiedades_poliza where id_poliza='$elidpoliza';");
              $replaspolizas=ejecutar($laspropolizas);
              $datapropoliza=assoc_a($replaspolizas);
              $idpropoliza=$datapropoliza['id_propiedad_poliza'];
              $montopoliza=$datapropoliza['monto_nuevo'];
              //*********************************//
              //Guardar los datos en la tabla coberturas_t_b;
              $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                            hora_creado,id_organo,monto_actual,monto_previo)
                            values('$idpropoliza','$elidtitulares','0','$fecha','$hora','0','$montopoliza','$montopoliza');");
      				$respuecobetb=ejecutar($regiscobetb);
              //*********************************//
              //Guardar los datos en la tabla titulares_polizas;
              $regtipolizas=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado)
      									  values('$elidtitulares','$elidpoliza','$fecha','$hora');");
              $restipolizas=ejecutar($regtipolizas);

           }
    ////ACTUALIAZAR ESTADO DE LA TIKERA
    $sqlactivarticket=("update tbl_tickeras set id_vendedor='$vendedorid', fecha_activacion='$fechaActivacion',fecha_vencimiento='$fechaVencimiento',id_titular='$elidtitulares', status='1' where id_tickera='$idtickera';");
    $VerfTikera=ejecutar($sqlactivarticket);
  }




?>
