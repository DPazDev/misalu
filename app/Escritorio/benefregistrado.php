<?
include ("../../lib/jfunciones.php");
sesion();
$conteoaguarbenf=$_SESSION['pasoaguardarbenf']+1;
if($conteoaguarbenf>1){?>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente ya registrado</td>  
     </tr>
</table>
<?}else{
$cdbef=$_POST['cedulaben'];
$cdtitu=$_POST['cedulatitu'];
$iddeltitu=$_POST['titularid'];
$entdeltitu=$_POST['tituente'];
$finclubenf=$_POST['fecbeinclu']; 
$estabenf=$_POST['beestatu'];
$nombenfi=$_POST['bennombre'];
$apellbenfi=$_POST['benapell'];
$generobenfi=$_POST['begenero'];
$correbenfi=$_POST['becorre'];
$telf1benfi=$_POST['bet1'];
$telf2benfi=$_POST['bet2'];
$nacibenfi=$_POST['befenaci'];
$siete='7';
$civilbenfi=$_POST['beescivil'];
$parenbenfi=$_POST['beparen'];
$direcbenfi=$_POST['dirbenf'];
$comentbenfi=$_POST['comeben'];
$edadbenfi=calcular_edad($nacibenfi);
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$cobebenfi=$_POST['polibenf'];
$materbenfi=$_POST['materbenf'];
$ciuclient=$_POST['benciudad'];
$conrecalculo=$_POST['clirecalculo'];
//Para poder ingresar un titular como beneficiario y no repetir los datos del cliente pues ya existen
//hacemos lo siguiente
$busclexist=("select clientes.id_cliente from clientes where cedula='$cdbef';");
$respclexist=ejecutar($busclexist);
$cuaclexist=num_filas($respclexist);
if ($cuaclexist<1){
//Guardar los datos en la tabla clientes
$guardarcliente=("insert into clientes(nombres,apellidos,fecha_nacimiento,sexo,direccion_hab,
                               telefono_hab,telefono_otro,email,fecha_creado,hora_creado,
                               id_ciudad,comentarios,cedula,estado_civil,id_admin,edad)
                               values(upper('$nombenfi'),upper('$apellbenfi'),'$nacibenfi','$generobenfi',
                               upper('$direcbenfi'),'$telf1benfi','$telf2benfi','$correbenfi','$fecha',
                                '$hora','$ciuclient',upper('$comentbenfi'),'$cdbef',upper('$civilbenfi'),'$elid','$edadbenfi');");
$resguardclien=ejecutar($guardarcliente);
//fin de los registros en la tabla clientes;
/***********************************/
//Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla beneficiario
$busclient=("select id_cliente from clientes where cedula='$cdbef';");
$resbusclient=ejecutar($busclient);
$datbusclien=assoc_a($resbusclient);
$elidclienes=$datbusclien['id_cliente'];
}else{
	   //Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla beneficiario
       $busclient=("select id_cliente from clientes where cedula='$cdbef';");
       $resbusclient=ejecutar($busclient);
       $datbusclien=assoc_a($resbusclient);
       $elidclienes=$datbusclien['id_cliente'];
       $actualizardatosbeni=("update clientes set direccion_hab=upper('$direcbenfi'),comentarios=upper('$comentbenfi'),
                                              telefono_hab='$telf1benfi',telefono_otro='$telf2benfi',email='$correbenfi' where
                                               id_cliente=$elidclienes;");
        $repactualizadatos=ejecutar($actualizardatosbeni);  
	}
//fin de la busquedad;
//**********************************//
//Guardar los datos en la tabla beneficiario
  if ($generobenfi==0){
	     if ($materbenfi=="null"){  
		    $tmater=0;
		}else{	
             $tmater=$materbenfi;
			}
        }else{
                 $tmater=0;
                }
$guardabenfi=("insert into beneficiarios
       (id_cliente,id_titular,id_parentesco,fecha_creado,hora_creado,fecha_inclusion,id_tipo_beneficiario,maternidad) 
                         values 
       ('$elidclienes','$iddeltitu','$parenbenfi','$fecha','$hora','$finclubenf','7','$tmater');");
$resguabenfi=ejecutar($guardabenfi);
//fin de los registros en la tabla titular;
//**********************************//
//Buscamos el beneficiario recien guardado en la tabla beneficiarios
$busbenfi=("select beneficiarios.id_beneficiario from beneficiarios where beneficiarios.id_cliente='$elidclienes' and beneficiarios.id_titular='$iddeltitu' and beneficiarios.id_cliente=$elidclienes;");
$resbusbenfi=ejecutar($busbenfi);
$databenbi=assoc_a($resbusbenfi);
$elidbenfi=$databenbi['id_beneficiario'];
//fin de la busquedad
//**********************************//
//Registrar el beneficiario en la tabla estados_t_b
$benfiestb=("insert into estados_t_b (id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
                     values 
                   ('$estabenf','$iddeltitu','$elidbenfi','$fecha','$hora');");
$resbenfestb=ejecutar($benfiestb);
//fin del registro del beneficiario en la tabla estados_t_b
//**********************************//
//Registrar el beneficiario en la tabla tipos_b_beneficiarios 
$bentiposb=("insert into tipos_b_beneficiarios (id_tipo_beneficiario,id_beneficiario,fecha_creado,hora_creado) values ('7','$elidbenfi','$fecha','$hora') ");
$resbentiposb=ejecutar($bentiposb);
//fin del registro del beneficiario en la tabla tipos_b_beneficiarios 
//**********************************//
//Registrar el beneficiario en la tabla coberturas_t_b
      //Primero busco las propiedades de la poliza al cual pertenece dependiendo del genero
	   if (($materbenfi=="null") || ($generobenfi==1)){
		      $condgen1="and sexo=2 order by cualidad;";
		}else{
			  $condgen1="order by cualidad";
		}
   $buscpropipolizas=("select propiedades_poliza.id_propiedad_poliza,
									 propiedades_poliza.id_poliza,propiedades_poliza.cualidad,
                                     propiedades_poliza.sexo,propiedades_poliza.organo,
                                     propiedades_poliza.monto_nuevo from propiedades_poliza 
									where id_poliza='$cobebenfi' $condgen1");
$respupropolizas=ejecutar($buscpropipolizas);	
     //Segundo registramos en la tabla coberturas_t_b las propiedades de la poliza segun el tipo de la poliza
	  //también debemos estar pendiente de registrar los planes basicos
           while($laspolizason=asignar_a($respupropolizas,NULL,PGSQL_ASSOC)){
			   $lapropiedadpoliza=$laspolizason['id_propiedad_poliza'];
			   $esorgano=$laspolizason['organo'];   
			   $elmonto=$laspolizason['monto_nuevo'];   
			   $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                                          hora_creado,id_organo,monto_actual,monto_previo) 
										   values('$lapropiedadpoliza','$iddeltitu','$elidbenfi','$fecha','$hora','$esorgano',
                                                       '$elmonto','$elmonto');");   
				$respuecobetb=ejecutar($regiscobetb);
			}
//fin del registro del beneficiario en la tabla coberturas_t_b//
                $verexcontrato=("select polizas_entes.id_poliza,tbl_recibo_contrato.id_recibo_contrato,entes.fecha_inicio_contrato,
                                 entes.fecha_renovacion_contrato
                               from
                                 polizas_entes,titulares,tbl_recibo_contrato,tbl_contratos_entes,entes
				where
				titulares.id_titular=$iddeltitu and
				titulares.id_ente=polizas_entes.id_ente and
				titulares.id_ente=tbl_contratos_entes.id_ente and 
				titulares.id_ente=entes.id_ente and
				tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente order by id_recibo_contrato desc limit 1;");
         $repvercontrato=ejecutar($verexcontrato);
         $cuantoscontr=num_filas($repvercontrato);
         $datdelcontra=assoc_a($repvercontrato);
         $cualpoliza=$datdelcontra[id_poliza];
         $elrecibocontrato=$datdelcontra[id_recibo_contrato];
         $inicioel=$datdelcontra[fecha_inicio_contrato];
         $finalizoel=$datdelcontra[fecha_renovacion_contrato];
    if($cuantoscontr>=1){
       if($conrecalculo==1){
	  $busprima=("select primas.id_prima,primas.anual from primas
                                            where
                                       primas.id_poliza=$cualpoliza and
                                       primas.id_parentesco=$parenbenfi and 
                                       $edadbenfi>=primas.edad_inicio and
                                       $edadbenfi<=primas.edad_fin;");
     //echo "<br>$busprima";
     $repbusprima=ejecutar($busprima);
     $infobusprima=assoc_a($repbusprima);
     $laidprimaes=$infobusprima[id_prima];
     //echo "<br>$laidprimaes";
     $primamonto=$infobusprima[anual];
    // echo "<br>$primamonto";
     $guarrecicarac=("insert into tbl_caract_recibo_prima
        (id_recibo_contrato,id_titular,id_beneficiario,id_prima,fecha_creado,monto_prima,genera_comision)
        values($elrecibocontrato,$iddeltitu,$elidbenfi,$laidprimaes,'$fecha',$primamonto,1)");
         //echo "$guarrecicarac<br>";
         $repguarrecicarac=ejecutar($guarrecicarac);
   }else{
	 $dias	= (strtotime($finclubenf)-strtotime($finalizoel))/86400;
	 $dias 	= abs($dias);
	 $dias = floor($dias);
	 //echo "<br>Cuantos dias--$dias";
	 $busprima=("select primas.id_prima,primas.anual from primas
                                            where
                                       primas.id_poliza=$cualpoliza and
                                       primas.id_parentesco=$parenbenfi and
                                       $edadbenfi>=primas.edad_inicio and
                                       $edadbenfi<=primas.edad_fin;");
     $repbusprima=ejecutar($busprima);
     $infobusprima=assoc_a($repbusprima);
     $laidprimaes=$infobusprima[id_prima];
     //echo "<br>$laidprimaes";
     $primamonto=number_format($infobusprima[anual],2,'.','');
     //echo "<br>Monto prima--$primamonto";
     $diasmonto=$primamonto/365;
     //echo "<br>Monto en dias--$diasmonto";
     $recalcuprima=$diasmonto*$dias;
    // echo "<br>El recalculo es--$recalcuprima";
     $guarrecicarac=("insert into tbl_caract_recibo_prima
        (id_recibo_contrato,id_titular,id_beneficiario,id_prima,fecha_creado,monto_prima,genera_comision) 
        values($elrecibocontrato,$iddeltitu,$elidbenfi,$laidprimaes,'$fecha',$recalcuprima,1)");
         //echo "Es----recalculo->$guarrecicarac<br>";
         $repguarrecicarac=ejecutar($guarrecicarac);
   }
}
//**********************************//
//echo"Cedula B-->$cdbef <BR> Cedula T-->$cdtitu<BR>--Poliza-->$cobebenfi<BR>Titular id--->$iddeltitu<BR>Ente-->$entdeltitu<br>FIB-->$finclubenf<br> Estatus B-->$estabenf<BR>Nombre-->$nombenfi $apellbenfi<BR>Genero-->$generobenfi<br>Correo-->$correbenfi<br>TF1-->$telf1benfi<br>TF2-->$telf2benfi<BR>FNB-->$nacibenfi<BR>EstCi-->$civilbenfi<BR>Parentesco-->$parenbenfi<BR>-Direcc->$direcbenfi<BR>Coment-->$comentbenfi<br>Maternidad-->$materbenfi";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente registrado exitosamente</td>  
     </tr>
</table>
<?}?>
