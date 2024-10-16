<?
include ("../../lib/jfunciones.php");
sesion();
$comentdona=$_POST['elcomentariodona'];
$comentprin=$_POST['elcomentprin'];
$ladepensali=$_POST['depensalien'];
$elautoriza=$_POST['elqautoriza'];
$eltipoclien=$_POST['eltipclien'];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$areglocliente=explode("@",$eltipoclien);
$qtipoes=$areglocliente[0];
$elidtob=$areglocliente[1];

if($qtipoes=='T'){
   $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where 
                  clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$elidtob");
   $repdatacliente=ejecutar($datacliente);
   $datpriclient=assoc_a($repdatacliente);
   $nomclie=$datpriclient[nombres];
   $apellid=$datpriclient[apellidos];
   $cedclie=$datpriclient[cedula];
   $eltitulaid=$elidtob;
   $elidbenef=0;
}else{
       $datacliente=("select clientes.nombres,clientes.apellidos,clientes.cedula,titulares.id_titular from clientes,titulares,beneficiarios 
                   where 
                  clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$elidtob and
                  beneficiarios.id_titular=titulares.id_titular");
       $repdatacliente=ejecutar($datacliente);
       $datpriclient=assoc_a($repdatacliente);
       $nomclie=$datpriclient[nombres];
       $apellid=$datpriclient[apellidos];
       $cedclie=$datpriclient[cedula];
       $eltitulaid=$datpriclient[id_titular];
       $elidbenef=$elidtob;
     }

//primero lo que hacemos es buscar que numero va hacer el donativo
$buscarnum=("select tbl_ordenes_donativos.no_orden_donativo from tbl_ordenes_donativos order by tbl_ordenes_donativos.no_orden_donativo desc limit 1;");
$repbuscarnum=ejecutar($buscarnum);
$cuantosdona=num_filas($repbuscarnum);
if($cuantosdona==0){
  $numerodonativo=1;
}else{
  $datnumdona=assoc_a($repbuscarnum);
  $donaactual=$datnumdona[no_orden_donativo];
  $numerodonativo=$donaactual+1;
}
//generamos el donativo
echo "$ladepensali---$fecha---$hora---$comentprin--$elid---$comentdona---$numerodonativo--$eltitulaid--$elidbenef---$elautoriza--1";
  $eldonativo=("insert into tbl_ordenes_donativos(id_dependencia,fecha_donativo,hora_donativo,comentdonativo,id_admin,comentarios,
                                                  no_orden_donativo,id_titular,id_beneficiario,id_responsable_donativo,estatus) 
values($ladepensali,'$fecha','$hora',upper('$comentprin'),$elid,upper('$comentdona'),$numerodonativo,$eltitulaid,$elidbenef,$elautoriza,1);");
 $repeldonativo=ejecutar($eldonativo);
//buscamos la orden 
$verorden=("select tbl_ordenes_donativos.id_orden_donativo from tbl_ordenes_donativos where tbl_ordenes_donativos.no_orden_donativo=$numerodonativo;");
$repverorden=ejecutar($verorden);
$dataorden=assoc_a($repverorden);
$elidordedona=$dataorden[id_orden_donativo];
//comenzamos a guardar los insumos en la tabla tbl_insumos_ordenes_donativos
$matrixguar=$_SESSION['matriz'];
$cuantomatriz=count($matrixguar);
 for($i=0;$i<=$cuantomatriz;$i++){
                          $cant=$matrixguar[$i][2];
                          $idart=$matrixguar[$i][5];  
                          if(!empty($cant)){
                          $buscarcosto=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where
                                         tbl_insumos_almacen.id_insumo=$idart and tbl_insumos_almacen.id_dependencia=$ladepensali;");
                          $repcosto=ejecutar($buscarcosto);
                          $datacosto=assoc_a($repcosto);
                          $montoarticulo=$datacosto[monto_unidad_publico];
                          $monto1=$montoarticulo*$cant;
		          $montogeneral=number_format($monto1,2,'.','');		  
							  $guadarendona=("insert into tbl_insumos_ordenes_donativos(id_orden_donativo,id_insumo,cantidad,costo)            
                                                            values($elidordedona,$idart,$cant,$montogeneral);");
							  $repguadarendona=ejecutar($guadarendona); 
							}
}						  
//fin de los datos guardados en la tabla tbl_insumos_ordenes_donativos//
//actualizamos el log
	 $mensaje="El usuario $elus ha realizado el donativo No.$numerodonativo"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog); 
echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">El donativo No. $numerodonativo para el cliente $nomclie $apellid portador de la c&eacute;dula de identidad No. $cedclie se ha generado exitosamente!!! </td>
         <td class=\"titulo_seccion\"><label title=\"Salir del proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ira()\" >Salir</label></td>
     </tr>
</table>";
?>
