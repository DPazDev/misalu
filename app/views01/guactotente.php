<?php
  include ("../../lib/jfunciones.php");
  sesion();
$idclientees=$_POST['elidclies'];  
$generoclien=("select sexo from clientes where id_cliente='$idclientees';");
$repgenero=ejecutar($generoclien);
$tipgenero=assoc_a($repgenero);
$elgeneros=$tipgenero[sexo];
$conmat=0;
$elentetitur=$_POST['elentitu'];
$feingempre=$_POST['ingesoemp'];
$feregistro=$_POST['inclsis'];
$eltitucargo=$_POST['cartitu'];
$eltitusubdi=$_POST['titusubd'];
$tituestatu=$_POST['estcli'];
$codigoti=$_POST['codigotitu'];
$lapolies=$_POST['polititu'];

$laspolizas = explode(',',$lapolies); 
$son=1;
foreach ($laspolizas as $laspolizas ) 
{ 
   if($laspolizas>0){	
   $caja[$son]=$laspolizas;
   $son++;
  }

}
$cuatspoliza=count($caja);
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
//reviso si ya esta el titular en ese ente
$revisotitu=("select count(titulares) from titulares where titulares.id_cliente=$idclientees and titulares.id_ente=$elentetitur");
$reprevisotitu=ejecutar($revisotitu);
$datarevisotitu=assoc_a($reprevisotitu);
$numversisotitu=$datarevisotitu[count];
if($numversisotitu<=0){
//guardar el titular a otro ente en la tabla titulares
$guardotorente=("insert into titulares (id_cliente,fecha_ingreso_empresa,fecha_creado,hora_creado,id_ente,fecha_inclusion,id_admin,codigo_empleado) values ('$idclientees','$feingempre','$fecha','$hora','$elentetitur','$feregistro','$elid','$codigoti');");
$repuesorente=ejecutar($guardotorente);
//fin de guardar el titular en la tabla titulares

//ver el titular que guarde
  $busotroti=("select id_titular from titulares where id_cliente='$idclientees' and fecha_creado='$fecha' and hora_creado='$hora';");
 $repueotroti=ejecutar($busotroti);  
 $dataideltiu=assoc_a($repueotroti); 
 $id_otrotiente=$dataideltiu['id_titular']; 
//fin de la busquedad

//Guardar el nuevo titular en la tabal estados_t_b
  $qotrotent=("insert into estados_t_b(id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) values('$tituestatu','$id_otrotiente','0','$fecha','$hora');");
  $respqotente=ejecutar($qotrotent);  
//fin del registro en estados_t_b

//Guardar los datos en la tabla titulares_cargos
  $tientcargo=("insert into titulares_cargos(id_titular,id_cargo,fecha_creado,hora_creado) values('$id_otrotiente','$eltitucargo','$fecha','$hora');");
 $reptiotcargo=ejecutar($tientcargo);
//fin de los registros en la tabla titulares_cargos

//Guardar los datos en la tabla titulares_subdivisiones
  $titenotsub=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$id_otrotiente','$eltitusubdi');");
  $reptieotsub=ejecutar($titenotsub);  
//fin de guardar los datos en la tabla titulares_subdivisiones

//Guardar los datos en la tabla titulares_polizas;
   //Primero tenemos que ver cuantos tipos de polizas tiene el ente
     $buspolizentes=("select polizas_entes.id_poliza from polizas_entes where polizas_entes.id_ente='$elentetitur';");          
	 $resppizentes=ejecutar($buspolizentes);
  //Segundo guardamos todas las polizas que tiene el ente en la tabla titulares_polizas;
     while($lastipolente=asignar_a($resppizentes,NULL,PGSQL_ASSOC)){
		   $polentes=$lastipolente['id_poliza'];
		   $regtipolizas=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
									  values('$id_otrotiente','$polentes','$fecha','$hora');");
		   $restipolizas=ejecutar($regtipolizas);						  
		}	 
//fin de los registros en la tabla titulares_polizas;

//Guardar los datos en la tabla coberturas_t_b;
for ($m=1;$m<=$cuatspoliza;$m++){
	$polizatitular1=$caja[$m];
       //Primero busco las propiedades de la poliza al cual pertenece dependiendo del genero
	   if (($conmat==0) || ($elgeneros==1)){
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
										   values('$lapropiedadpoliza','$id_otrotiente','0','$fecha','$hora','$esorgano',
                                                       '$elmonto','$elmonto');");   
				$respuecobetb=ejecutar($regiscobetb);
			}
//fin de los registros en la tabla coberturas_t_b;

//Guardar los datos en la tabla logs;
$mensaje="$elus, ha agregado un nuevo titular como nuevo titular en otro ente con el id_titular $id_otrotiente ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
}
//fin de los registros en la tabla logs;

//echo "$codigoti------$tituestatu-----$eltitusubdi-----$eltitucargo-----$feregistro-----$feingempre----$elentetitur---$idclientees--$lapolies";
?>  
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Titular registrado a otro ente exitosamente</td>  
     </tr>
</table>
<?}else{?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Error: El Titular ya se encuentra registrado en el ente seleccionado!!</td>  
     </tr>
</table>

<?}?>
