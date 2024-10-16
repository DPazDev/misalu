<?
   include ("../../lib/jfunciones.php");
   sesion();
   $rifdf=$_POST['verif'];
   $rifpc=$_POST['ricamb'];
   $nitpc=$_POST['nitcamb'];
   $nompc=$_POST['nomcamb'];
   /////RECIBIR UNA CADENA CON *AND*   /////////////////////////
   ////// TieneCaracterEspecial=1 TieneCaracterEspecial=0
       $TieneCaracterEspecial=$_POST['TieneCEsp'];
         if($TieneCaracterEspecial=='1'){
           $nompc=str_replace('*AND*', '&' , $nompc);
         }
   $dirpc=$_POST['dircamb'];
   $tefpc=$_POST['tefcamb'];
   $corrpc=$_POST['corrcamb'];
   $pagpc=$_POST['pagcamb'];
   $estactivo=$_POST['opciacti'];
   $actprovee=$_POST['laactivipro'];
   $nocheque=$_POST['elcheanom'];
   $rifcheque=$_POST['elcherif'];
   $direcheque=$_POST['elchedir'];
   $laactivpro=$_POST['actiprovp'];
   $activprovedie=$_POST['tipoproinex'];
   $laidcliente=$_POST['clientesid'];
   $TipoMonto=$_POST['TipoMonto'];
   $CostoServicio=$_POST['CostoServicio'];
   $monedaservicio=$_POST['monedaservicio'];

   $modcli=("update clinicas_proveedores set rif='$rifpc',nit='$nitpc',nombre=upper('$nompc'),
                    direccion=upper('$dirpc'),telefonos='$tefpc',email=upper('$corrpc'),url=upper('$pagpc'),
                     activar=$estactivo,id_act_pro=$actprovee,nomcheque=upper('$nocheque'),rifcheque=upper('$rifcheque'),direccioncheque=upper('$direcheque'),prov_compra=$laactivpro where id_clinica_proveedor='$laidcliente';");
   $modclifinal=ejecutar($modcli);

   //Modificar en la tabla s_c_proveedores MONTOS y Tipos de costo del servicio
    $ModifServiClien=("update s_c_proveedores set monto_servicio_c='$CostoServicio',tipo_monto_c='$TipoMonto',id_moneda='$monedaservicio' where id_clinica_proveedor='$laidcliente';");
    $ModifServiClienClinica=ejecutar($ModifServiClien);

   $selectprov=("select proveedores.id_proveedor
                           from
                          clinicas_proveedores,proveedores where
                          clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
                          clinicas_proveedores.id_clinica_proveedor='$laidcliente'");
  $repuselectprov=ejecutar($selectprov);
  $datselecprov=assoc_a($repuselectprov);
  $elprovid=$datselecprov['id_proveedor'];
  $actuprov=("update proveedores set tipo_proveedor=$activprovedie where id_proveedor=$elprovid;");
  $repactuprov=ejecutar($actuprov);
 $fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado al proveedor con ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
