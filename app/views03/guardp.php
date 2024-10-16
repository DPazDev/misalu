<?php
   include ("../../lib/jfunciones.php");
   sesion();
  $tciudad=$_POST["ci1"];
  $tespeci=$_POST["es1"];
  $tdire=strtoupper($_POST["dip1"]);
  $ttelf=$_POST["te1"];
  $tservim=$_POST["ser1"];
  $estactivo=$_POST["opciacti"];
  $tcedu=$_POST["ced1"];
  $nompro=strtoupper($_POST['elnom']);
  $apepro=strtoupper($_POST['elapell']);
  $tidp=$_POST["idp1"];
  $tcoment=$_POST["com1"];
  $tprn=$_POST["pv1"];
     if($tprn=='null'){
       $tprn=0;
     }else{
       $tprn=1;
     }
  $thor=$_POST["h1"];
  $fecha=date("Y-m-d");
  $modicedu=$_POST["lanuce"];
  $hora=date("H:i:s");
  $nuesucur=$_POST["tipsucur"];
  $ellun=$_POST["llunes"];
  $elmart=$_POST["lmarte"];
  $elmier=$_POST["lmier"];
  $eljuev=$_POST["ljuev"];
  $elvier=$_POST["lviern"];
  $elsab=$_POST["lsaba"];
  $eldomin=$_POST["ldomi"];
  /////montos del servicio
  $TipoMonto=$_POST['TipoMonto']; //TIPO DE VALOR PARA EL SERVIVIO % o MONTO
  $CostoServicio=$_POST['CostoServicio']; //TIPO DE VALOR del SERVICIO
  $monedaservicio=$_POST['monedaservicio']; //TIPO DE de moneda del servicio
  $tipoprovinext=$_POST["provinex"];

  $elid=$_SESSION['id_usuario_'.empresa];
  $elus=$_SESSION['nombre_usuario_'.empresa];
    if($ellun<=0){
		$ellun=0;
		}else{$ellun=1;}

	 if($elmart<=0){
		$elmart=0;
		}else{$elmart=1;}

	if($elmier<=0){
		$elmier=0;
		}else{$elmier=1;}

	if($eljuev<=0){
		$eljuev=0;
		}else{$eljuev=1;}

	if($elvier<=0){
		$elvier=0;
		}else{$elvier=1;}

	if($elsab<=0){
		$elsab=0;
		}else{$elsab=1;}

		if($eldomin<=0){
		$eldomin=0;
		}else{$eldomin=1;}
$fllunes = $_POST["pllune"];
  $flmarte = $_POST["plmarte"];
  $flmierco = $_POST["plmierc"];
  $fljueves = $_POST["pljueve"];
  $flvierne = $_POST["plvierne"];
  $flsabado = $_POST["plsabad"];
  $fldoming = $_POST["pldoming"];
  if(empty($fllune)){
	  $fllune=0;
  }
  if(empty($flmarte)){
	  $flmarte=0;
  }
  if(empty($fllune)){
	  $fllune=0;
  }
  if(empty($flmierco)){
	  $flmierco=0;
  }
  if(empty($fljueves)){
	  $fljueves=0;
  }
  if(empty($flvierne)){
	  $flvierne=0;
  }
  if(empty($flsabado)){
	  $flsabado=0;
  }
  if(empty($fldoming)){
	  $fldoming=0;
  }

  $actualizar=("update s_p_proveedores set
                         id_ciudad=$tciudad,id_especialidad=$tespeci,id_servicio_proveedor=$tservim,telefonos_prov='$ttelf',comentarios_prov=upper('$tcoment'),horario=upper('$thor'),id_sucursal='$nuesucur',direccion_prov=upper('$tdire'),nomina='$tprn',lunes=$ellun,martes=$elmart,
                        miercoles=$elmier,jueves=$eljuev,viernes=$elvier,sabado=$elsab,domingo=$eldomin,activar=$estactivo,
                        nplunes=$fllunes,npmartes=$flmarte,npmiercole=$flmierco,npjueve=$fljueves,npviernes=$flvierne,
                        npsabado=$flsabado,npdomingo=$fldoming,monto_servicio_p='$CostoServicio',tipo_monto_p='$TipoMonto',id_moneda='$monedaservicio' where id_s_p_proveedor='$tidp';");
  $haceac=ejecutar($actualizar);

  $mensaje="$elus, ha modificado el proveedor con el id_s_p_proveeder $tidp ";
  $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
  $inrelo=ejecutar($relog);
  //para modificar el tipo de proveedor directo o indirecto
  $actprovintext=("update proveedores set tipo_proveedor=$tipoprovinext where id_s_p_proveedor=$tidp");
  $repactprovintext=ejecutar($actprovintext);
  //para modificar la cedula
  $modifcedu=("update personas_proveedores set cedula_prov='$modicedu',nombres_prov='$nompro',apellidos_prov='$apepro'
                         from s_p_proveedores
                      where
         personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and
        s_p_proveedores.id_s_p_proveedor='$tidp';");
  echo $modifcedu;
  $actualicedu=ejecutar($modifcedu);
  //$hacerlamodif=ejecutar($modifcedu);
  //Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificao al proveedor persona con id_s_p_proveedor=$tidp";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
