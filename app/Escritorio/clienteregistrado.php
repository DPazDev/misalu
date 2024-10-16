<?
include ("../../lib/jfunciones.php");
sesion();
$conteoaguar=$_SESSION['pasoaguardar']+1;
if($conteoaguar>1){?>
	<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente ya registrado</td>  
     </tr>
</table>
<?}else{
	 $cedclien=$_POST["cedulacl"];
$entclien=$_POST["entclien"];
$ingresoclien=$_POST["feingreso"];
$inclufecha=$_POST['fechainclu'];
$carclient=$_POST["carclien"];
$clisubd=$_POST["subdiv"];
$clienestatus=$_POST["estclien"];
$nombrecli=$_POST["clinom"];
$apecli=$_POST["cliape"];
$genercli=$_POST["cligen"];
$conmat=$_POST["conmate"];
$correclie=$_POST["clicorr"];
$clitf1=$_POST["telf1"];
$clitf2=$_POST["telf2"];
$fnaciclien=$_POST["clifenaci"];
$cliencivil=$_POST["clicivil"];
$direcclient=$_POST["cliendir"];
$comentclien=$_POST["cliencoment"];
$ciuclient=$_POST["cliciud"];
$codititular=$_POST["elcocli"];
$polizatitular=$_POST['clipoliza'];
$laspolizas = explode(',',$polizatitular); 
$partidaclien=$_POST['cliparida'];
$son=1;
foreach ($laspolizas as $laspolizas ) 
{ 
   if($laspolizas>0){	
   $caja[$son]=$laspolizas;
   $lapolifinal=$caja[$son];
   $son++;
  }

}
$cuatspoliza=count($caja);
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$fedad=calcular_edad($fnaciclien);
//Primero vemos si el titular existe
$buscoexiste=("select id_cliente from clientes where cedula='$cedclien';");
$repbuscoexiste=ejecutar($buscoexiste);
$dataexiste=assoc_a($repbuscoexiste);
$cuantosexiste=num_filas($repbuscoexiste);
if ($cuantosexiste>=1){
	$elidclienes=$dataexiste['id_cliente'];
	$actdatoscliente=("update clientes set direccion_hab='$direcclient',telefono_hab='$clitf1',telefono_otro='$clitf2' where clientes.id_cliente=$elidclienes;");
	$repactdatoscliente=ejecutar($actdatoscliente);
}else{	
//Guardar los datos en la tabla clientes
$guardarcliente=("insert into clientes(nombres,apellidos,fecha_nacimiento,sexo,direccion_hab,
                               telefono_hab,telefono_otro,email,fecha_creado,hora_creado,
                               id_ciudad,comentarios,cedula,estado_civil,id_admin,edad) 
                               values(upper('$nombrecli'),upper('$apecli'),'$fnaciclien','$genercli',
                               upper('$direcclient'),'$clitf1','$clitf2','$correclie','$fecha',
                                '$hora','$ciuclient',upper('$comentclien'),'$cedclien',upper('$cliencivil'),'$elid','$fedad');");
$resguardclien=ejecutar($guardarcliente);								
//fin de los registros en la tabla clientes;
/***********************************/
//Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla titular
$busclient=("select id_cliente from clientes where cedula='$cedclien';");
$resbusclient=ejecutar($busclient);
$datbusclien=assoc_a($resbusclient);
$elidclienes=$datbusclien['id_cliente'];
//fin de la busquedad;
}
//**********************************//
//Guardar los datos en la tabla titular
  if ($genercli==0){
	 if ($conmat=="null"){  
		     $tmater=0;
		}else{	
             $tmater=$conmat;
		}  
	}else{
		 $tmater=0;
		}
//reviso si ya esta el titular en ese ente
$revisotitu=("select count(titulares) from titulares where titulares.id_cliente=$elidclienes and titulares.id_ente=$entclien");
$reprevisotitu=ejecutar($revisotitu);
$datarevisotitu=assoc_a($reprevisotitu);
$numversisotitu=$datarevisotitu[count];
if($numversisotitu<=0){
$guardaclientitu=("insert into titulares(id_cliente,fecha_ingreso_empresa,fecha_creado,
                                 hora_creado,id_ente,fecha_inclusion,id_admin,codigo_empleado,
                                 maternidad,tipo_partida) values('$elidclienes','$ingresoclien','$fecha',
                                '$hora','$entclien','$inclufecha','$elid','$codititular','$tmater',$partidaclien);");
$resguatitularcliente=ejecutar($guardaclientitu);								
//fin de los registros en la tabla titular;
//**********************************//
//Buscar el cliente guardado en la tabla titulares
$bustituclien=("select id_titular from titulares where id_cliente='$elidclienes';");
$resptituclien=ejecutar($bustituclien);
$dattituclien=assoc_a($resptituclien);
$elidtitulares=$dattituclien['id_titular'];
//fin de la busquedad;
//**********************************//
//Guardar los datos en la tabla titulares_subdivisiones;
$guartitsubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$elidtitulares', '$clisubd');");
$restitusbdivi=ejecutar($guartitsubdivi);							  
//fin de los registros en la tabla titulares_subdivisiones;
//**********************************//
//Guardar los datos en la tabla titulares_cargos;
$guartituscargos=("insert into titulares_cargos(id_titular,id_cargo,fecha_creado,hora_creado)   
								  values('$elidtitulares','$carclient','$fecha','$hora');");
$restitucargos=ejecutar($guartituscargos);
//fin de los registros en la tabla  titulares_cargos;
//**********************************//
//Guardar los datos en la tabla estados_t_b;
$guardtituestado=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,
                                   fecha_creado,hora_creado) values('$clienestatus','$elidtitulares','0',
                                   '$fecha','$hora');");
$respdtituestado=ejecutar($guardtituestado);
//fin de los registros en la tabla  estados_t_b;
//**********************************//
//Guardar los datos en la tabla coberturas_t_b;
//guardo N veces
//Primero busco las propiedades de la poliza al cual pertenece dependiendo del genero
for ($m=1;$m<=$cuatspoliza;$m++){
	$polizatitular1=$caja[$m];
	
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
			   $regiscobetb=("insert into coberturas_t_b(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,
                                          hora_creado,id_organo,monto_actual,monto_previo) 
										   values('$lapropiedadpoliza','$elidtitulares','0','$fecha','$hora','$esorgano',
                                                       '$elmonto','$elmonto');");   
				$respuecobetb=ejecutar($regiscobetb);
			}
}
//find de coberturas_t_b
//Guardar los datos en la tabla titulares_polizas;
//Primero tenemos que ver cuantas tipos de polizas tiene el ente
     $buspolizentes=("select polizas_entes.id_poliza from polizas_entes where polizas_entes.id_ente='$entclien';");     
     $resppizentes=ejecutar($buspolizentes);
  //Segundo guardamos todas las polizas que tiene el ente en la tabla titulares_polizas;
    // while($lastipolente=asignar_a($resppizentes,NULL,PGSQL_ASSOC)){
   //		   $polentes=$lastipolente['id_poliza'];
  //		   $regtipolizas=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
  //									  values('$elidtitulares','$polentes','$fecha','$hora');");
  //		   $restipolizas=ejecutar($regtipolizas);						  
   //		}	 
  $guardamospoliza=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
		values('$elidtitulares','$lapolifinal','$fecha','$hora');");
     $repguardaspoliza=ejecutar($guardamospoliza);

//fin de los registros en la tabla titulares_polizas;
//**********************************//
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha agregado un nuevo Cliente con el id_cliente $elidclienes ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);

//fin de los registros en la tabla logs;
//**********************************//
//echo "Cedula->$cedclien	<br> Ente->$entclien  <br>Fingresoempresa->$ingresoclien<br>        Finclusion->$inclufecha<br>Cargo->$carclient<br>Subdivi->$clisubd<br>Estatus->$clienestatus<br>Nombre->$nombrecli<br>Apellido->$apecli<br>Genero->$genercli<br>Maternidad?->$conmat<br>           Correo->$correclie<br>Telf1->$clitf1<br>Telf2->$clitf2<br>FeNacimien->$fnaciclien<br>Estado Ci->$cliencivil<br> Direccion->$direcclient <br> Comentario->$comentclien<br>Ciudad->$ciuclient         <br>CodigoT->$codititular<br>La poliza->$polizatitular<br> Edad->$fedad";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente registrado exitosamente</td>  
     </tr>
</table>
		
<?}else{?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Error: El Titular ya se encuentra registrado en el ente seleccionado!!</td>  
     </tr>
</table>

<?}}?>

