<?php
include ("../../lib/jfunciones.php");
sesion();
$dirpro=$_POST['dirp'];
$tf=$_POST['telfp'];
$svpro=$_POST['servpp'];
$ciuppo=$_POST['ciupp'];
$esmedi=$_POST['esmepp'];
$hopr=$_POST['hop1'];
$compr=$_POST['comentpp'];
$espno=$_POST['pvnpp'];
$idpsp=$_POST['idppo'];
$sucurnom=$_POST['lasucu'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$dlunes=$_POST['ellunes'];
$dmartes=$_POST['elmartes'];
$dmiercoles=$_POST['elmiercoles'];
$djueves=$_POST['eljueves'];
$dviernes=$_POST['elviernes'];
$dsabado=$_POST['elsabado'];
$ddomingo=$_POST['eldoming'];
//cantidad de estudiso
  $Estudiolunes=$_POST['EstLunes'];
  $Estudiomart=$_POST['EstMartes'];
  $Estudiomierc=$_POST['EstMiercoles'];
  $Estudiojueve=$_POST['EstJueve'];
  $Estudioviern=$_POST['EstVierne'];
  $Estudiosabad=$_POST['EstSabad'];
  $Estudiodomig=$_POST['EstDomingo'];
  $provintex=$_POST['claspiex'];//es intramural
  $pactivo=$_POST['estacti'];///activo
  /////montos del servicio
  $TipoMonto=$_POST['TipoMonto']; //TIPO DE VALOR PARA EL SERVIVIO % o MONTO
  $CostoServicio=$_POST['CostoServicio']; //TIPO DE VALOR del SERVICIO
  $monedaservicio=$_POST['monedaservicio']; //id Monda del servicio

if ($espno==1){
   $espno=$espno;
}else{
   $espno='0';
}

///LUNES///
if($dlunes<=0){
		$dlunes=0;
    $Estudiolunes=0;
  }else{
      $dlunes=1;
      if (is_numeric($Estudiolunes) && $Estudiolunes<>''){
        $Estudiolunes=$Estudiolunes;
      }else{$Estudiolunes=0;}

      }


///MARTES///
	 if($dmartes<=0){
		$dmartes=0;
    $Estudiomart=0;
  }else{
      $dmartes=1;
      if (is_numeric($Estudiomart) && $Estudiomart<>''){
        $Estudiomart=$Estudiomart;
        }else{$Estudiomart=0;}
  }
///MIERCOLES///
	if($dmiercoles<=0){
		$dmiercoles=0;
    $Estudiomierc=0;
  }else{
    $dmiercoles=1;
      if (is_numeric($Estudiomierc) && $Estudiomierc<>''){
        $Estudiomierc=$Estudiomierc;
      }else{$Estudiomierc=0;}
  }

///JEVES///
	if($djueves<=0){
		$djueves=0;
    $Estudiojueve=0;
  }else{
    $djueves=1;
    if (is_numeric($Estudiojueve) && $Estudiojueve<>''){
      $Estudiojueve=$Estudiojueve;
    }else{$Estudiojueve=0;}

  }

///VIERENES///
	if($dviernes<=0){
		$dviernes=0;
    $Estudioviern=0;
  }else{$dviernes=1;
    if (is_numeric($Estudioviern) && $Estudioviern<>''){
      $Estudioviern=$Estudioviern;
    }else{$Estudioviern=0;}
    }

///SABADO///
	if($dsabado<=0){
		$dsabado=0;
    $Estudiosabad=0;
  }else{
    $dsabado=1;
    if (is_numeric($Estudiosabad) && $Estudiosabad<>''){
      $Estudiosabad=$Estudiosabad;
    }else{$Estudiosabad=0;}

  }

///DOMINGO///
		if($ddomingo<=0){
		$ddomingo=0;
    $Estudiodomig=0;
  }else{$ddomingo=1;
    if (is_numeric($Estudiodomig) && $Estudiodomig<>''){
      $Estudiodomig=$Estudiodomig;
    }else{$Estudiodomig=0;}
    }

$guap=("insert into s_p_proveedores  (id_servicio_proveedor,id_persona_proveedor,
                         id_ciudad,direccion_prov,telefonos_prov,comentarios_prov,fecha_creado,
                         hora_creado,nomina,id_sucursal,horario,id_especialidad,lunes,martes,miercoles,jueves,viernes,sabado,domingo,activar,nplunes,npmartes,npmiercole,npjueve,npviernes,npsabado,npdomingo,monto_servicio_p,tipo_monto_p,id_moneda)
values ('$svpro','$idpsp','$ciuppo',upper('$dirpro'),'$tf',upper('$compr'),'$fecha','$hora','$espno','$sucurnom',upper('$hopr'),'$esmedi',
              $dlunes,$dmartes,$dmiercoles,$djueves,$dviernes,$dsabado,$ddomingo,'$pactivo','$Estudiolunes','$Estudiomart','$Estudiomierc','$Estudiojueve','$Estudioviern','$Estudiosabad','$Estudiodomig','$CostoServicio','$TipoMonto','$monedaservicio');");
$parguar=ejecutar($guap);
$queguarde=("select id_s_p_proveedor from s_p_proveedores where hora_creado='$hora' and id_persona_proveedor='$idpsp';");
$estoguarde=ejecutar($queguarde);
$ladaguarda=assoc_a($estoguarde);
$id_sp_pro=$ladaguarda['id_s_p_proveedor'];
echo$inserprove=("insert into proveedores (id_s_p_proveedor,id_clinica_proveedor,fecha_creado,hora_creado,tipo_proveedor) values ('$id_sp_pro','0','$fecha','$hora',$provintex);");
$parguprovee=ejecutar($inserprove);
$mensaje="$elus, ha agregado un nuevo servicio a la tabla  s_p_proveeder con el id_s_p_persona $idpsp ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//echo "Dire->$dirpro--Telf->$tf--Servicio->$svpro--Ciudad->$ciuppo--EspM->$esmedi--Horario->$hopr--Comen->$compr--EpNomi->$espno--IdPePr->$idpsp--Sucur->$sucurnom--Fecha->$fecha--Hora->$hora<BR>";
?>
