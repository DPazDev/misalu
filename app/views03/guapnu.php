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
$sucurnom=$_POST['lasucu'];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$cenueva=$_POST['pcen'];
$novpnue=$_POST['pnbpnom'];
$apenue=$_POST['papnu'];
$corenue=$_POST['pcorenu'];
$tefnue=$_POST['ptenu'];
//horarios
$glunes=$_POST['rlunes'];
$gmart=$_POST['rmart'];
$gmierc=$_POST['rmierc'];
$gjueve=$_POST['rjueve'];
$gviern=$_POST['rvierne'];
$gsabad=$_POST['rsabad'];
$gdomi=$_POST['rdomin'];
//cantidad de estudiso
  $Estudiolunes=$_POST['EstLunes'];
  $Estudiomart=$_POST['EstMartes'];
  $Estudiomierc=$_POST['EstMiercoles'];
  $Estudiojueve=$_POST['EstJueve'];
  $Estudioviern=$_POST['EstVierne'];
  $Estudiosabad=$_POST['EstSabad'];
  $Estudiodomig=$_POST['EstDomingo'];

$pactivo=$_POST['estacti'];
/////montos del servicio
$TipoMonto=$_POST['TipoMonto']; //TIPO DE VALOR PARA EL SERVIVIO % o MONTO
$CostoServicio=$_POST['CostoServicio']; //TIPO DE VALOR del SERVICIO
$monedaservicio=$_POST['monedaservicio']; //id moneda

$provintex=$_POST['claspiex'];
if ($espno==1){
   $espno=$espno;
}else{
   $espno='0';
}

///LUNES///
if($glunes<=0){
		$glunes=0;
    $Estudiolunes=0;
  }else{
      $glunes=1;
      if (is_numeric($Estudiolunes) && $Estudiolunes<>''){
        $Estudiolunes=$Estudiolunes;
      }else{$Estudiolunes=0;}

      }


///MARTES///
	 if($gmart<=0){
		$gmart=0;
    $Estudiomart=0;
  }else{
      $gmart=1;
      if (is_numeric($Estudiomart) && $Estudiomart<>''){
        $Estudiomart=$Estudiomart;
        }else{$Estudiomart=0;}
  }
///MIERCOLES///
	if($gmierc<=0){
		$gmierc=0;
    $Estudiomierc=0;
  }else{
    $gmierc=1;
      if (is_numeric($Estudiomierc) && $Estudiomierc<>''){
        $Estudiomierc=$Estudiomierc;
      }else{$Estudiomierc=0;}
  }

///JEVES///
	if($gjueve<=0){
		$gjueve=0;
    $Estudiojueve=0;
  }else{
    $gjueve=1;
    if (is_numeric($Estudiojueve) && $Estudiojueve<>''){
      $Estudiojueve=$Estudiojueve;
    }else{$Estudiojueve=0;}

  }

///VIERENES///
	if($gviern<=0){
		$gviern=0;
    $Estudioviern=0;
  }else{$gviern=1;
    if (is_numeric($Estudioviern) && $Estudioviern<>''){
      $Estudioviern=$Estudioviern;
    }else{$Estudioviern=0;}
    }

///SABADO///
	if($gsabad<=0){
		$gsabad=0;
    $Estudiosabad=0;
  }else{
    $gsabad=1;
    if (is_numeric($Estudiosabad) && $Estudiosabad<>''){
      $Estudiosabad=$Estudiosabad;
    }else{$Estudiosabad=0;}

  }

///DOMINGO///
		if($gdomi<=0){
		$gdomi=0;
    $Estudiodomig=0;
  }else{$gdomi=1;
    if (is_numeric($Estudiodomig) && $Estudiodomig<>''){
      $Estudiodomig=$Estudiodomig;
    }else{$Estudiodomig=0;}
    }

//Guardar el nuevo proveedor en la tabla personas_proveedores
$guarprove=("insert into personas_proveedores (nombres_prov,apellidos_prov,email_prov,celular_prov,fecha_creado,hora_creado,cedula_prov,sin_convenio,id_act_pro) values (upper('$novpnue'),upper('$apenue'),upper('$corenue'),'$tefnue','$fecha','$hora','$cenueva','0',3); ");
$aguardarp=ejecutar($guarprove);
// fin de guardar en personas_proveedores

//Busco el proveedor que guarde
$tmnom=strtoupper($novpnue);
$tmapel=strtoupper($apenue);
$busprovg=("select id_persona_proveedor from personas_proveedores where cedula_prov='$cenueva';");
$loqbusq=ejecutar($busprovg);
$dabusq=assoc_a($loqbusq);
$elpguare=$dabusq['id_persona_proveedor'];
//fin de buscar

// Guardar en la tabla s_p_proveedores el proveedor recientemente guardado
$guardsp=("insert into s_p_proveedores (id_servicio_proveedor,id_persona_proveedor,id_ciudad,direccion_prov,telefonos_prov,comentarios_prov,fecha_creado,hora_creado,nomina,id_sucursal,id_especialidad,horario,lunes,
  martes,miercoles,jueves,viernes,sabado,domingo,activar,nplunes,npmartes,npmiercole,npjueve,npviernes,npsabado,npdomingo,monto_servicio_p,tipo_monto_p,id_moneda) values('$svpro','$elpguare','$ciuppo',upper('$dirpro'),'$tf',upper('$compr'),'$fecha','$hora','$espno','$sucurnom','$esmedi','$hopr','$glunes','$gmart','$gmierc','$gjueve','$gviern','$gsabad','$gdomi','$pactivo','$Estudiolunes','$Estudiomart','$Estudiomierc','$Estudiojueve','$Estudioviern','$Estudiosabad','$Estudiodomig','$CostoServicio','$TipoMonto','$monedaservicio');");
$paguardar=ejecutar($guardsp);
// Fin de guardar en s_p_proveedores

//Buscar en s_p_proveedores el servicio recientemente guardado
$busservi=("select id_s_p_proveedor from s_p_proveedores where id_persona_proveedor='$elpguare';");
$ejebusservi=ejecutar($busservi);
$databusservi=assoc_a($ejebusservi);
$elidspprov=$databusservi['id_s_p_proveedor'];
//fin de busca

//Guardar en la tabla proveedores
$guarprovedor=("insert into proveedores (id_s_p_proveedor,id_clinica_proveedor,fecha_creado,hora_creado,tipo_proveedor) values ('$elidspprov','0','$fecha','$hora',$provintex);");
$hacer=ejecutar($guarprovedor);

//fin de guardar en proveedores

//Guardar en la tabla logs
$mensaje="$elus, ha agregado un nuevo proveedor con el id_persona_proveedor $elpguar";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);

?>
