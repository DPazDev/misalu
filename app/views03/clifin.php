<?
  include ("../../lib/jfunciones.php");
  sesion();
  $drif=$_POST['rifcf'];
  $dnomf=$_POST['prclinomb'];/////RECIBIR UNA CADENA CON *AND*
  /////////////////////////
  ////// TieneCaracterEspecial=1 TieneCaracterEspecial=0
  $TieneCaracterEspecial=$_POST['TieneCEsp'];
    if($TieneCaracterEspecial=='1'){
      $dnomf=str_replace('*AND*', '&' , $dnomf);
    }
  $dtef=$_POST['prclitelef'];
  $dfax=$_POST['fifax'];
  $dpawb=$_POST['fpw'];
  $dcif=$_POST['prcidf'];
  $ddire=$_POST['fdirec'];
  $dnit=$_POST['fnit'];
  $fincore=$_POST['corecl'];
  $fpaiz=$_POST['elpai'];
  $festado=$_POST['escli'];
  $laactividad=$_POST['provacti'];
  $espcomp1=$_POST['esprcomp1'];
  $espcomp2=$_POST['esprcomp2'];
  $espcomp3=$_POST['esprcomp3'];
  $estactivo=$_POST['opciacti'];
  $TipoMonto=$_POST['TipoMonto']; //TIPO DE VALOR PARA EL SERVIVIO % o MONTO
  $CostoServicio=$_POST['CostoServicio']; //TIPO DE VALOR del SERVICIO
  $monedaservicio=$_POST['monedaservicio'];//TIPO DE MONEDA UTILIZADO PARA SERVICI SI ES 0 ES PORCENTAJE
  $cheanombre=strtoupper($_POST['cheqnombre']);
  $rifanomb=strtoupper($_POST['rifanombre']);
  $direcanomb=strtoupper($_POST['diranombre']);
  $activprovedie=$_POST['actprov'];

   if ($espcomp1==1){
     $prcompra=1;
   }
   if ($espcomp2==2){
     $prcompra=0;
   }
   if ($espcomp3==3){
     $prcompra=2;
   }
  $nomcompcont=$_POST['comprnocont'];
  $dircompcont=$_POST['comprdircont'];
  $tefcompcont=$_POST['comprtelcon'];
  $fecha=date("Y-m-d");
  $hora=date("H:i:s");
  $elid=$_SESSION['id_usuario_'.empresa];
  $elus=$_SESSION['nombre_usuario_'.empresa];
  $serviclini=$_POST['elsercli'];
  //Guardar en la tabla clinica_proveedor
  $guaclinipro=("insert into clinicas_proveedores(nombre,direccion,telefonos,id_ciudad,
                        fecha_creado,hora_creado,nit,rif,fax,url,email,sin_convenio,prov_compra,
                       nombre_contacto,direccion_contacto,telefonos_contacto,id_act_pro,activar,nomcheque,rifcheque,direccioncheque)
                  values (upper('$dnomf'),upper('$ddire'),'$dtef','$dcif','$fecha','$hora',upper('$dnit'),
                 upper('$drif'),'$dfax',upper('$dpawb'),upper('$fincore'),'0','$prcompra',
                upper('$nomcompcont'),upper('$dircompcont'),'$tefcompcont',$laactividad,$estactivo,'$cheanombre','$rifanomb','$direcanomb');");
  $listguarda=ejecutar($guaclinipro);
  //Buscar lo guardado
  $qguarde=("select id_clinica_proveedor from clinicas_proveedores where rif='$drif'");
  $vergu=ejecutar($qguarde);
  $registrog=assoc_a($vergu);
  $idclpro=$registrog['id_clinica_proveedor'];

  //Guardar en la tabla proveedores
  $guarclenpro=("insert into proveedores(id_s_p_proveedor,id_clinica_proveedor,fecha_creado,hora_creado,tipo_proveedor) values ('0','$idclpro','$fecha','$hora',$activprovedie);");
  $guarclienpro=ejecutar($guarclenpro);

 //Guardar en la tabla s_c_proveedores
  $guarserviclien=("insert into s_c_proveedores(id_servicio_proveedor,id_clinica_proveedor,fecha_creado,hora_creado,monto_servicio_c,tipo_monto_c,id_moneda) values ('$serviclini','$idclpro','$fecha','$hora','$CostoServicio','$TipoMonto','$monedaservicio');");
  $guarserviclien=ejecutar($guarserviclien);


  //Guardar en lo logs
   $mensaje="$elus, ha agregado un nuevo proveedor con el id_clinica_proveedor $idclpro ";
   $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
   $inrelo=ejecutar($relog);

  //echo " El rif-> $drif,--El nombre-> $dnomf,--El telf-> $dtef,--Fax-> $dfax,--Paw->$dpawb,--,Dire->$ddire,--Nit->$dnit,--Coreeo->$fincore,--Pais->$fpaiz,--Estado->$festado,--Ciudad -> $dcif";
?>
