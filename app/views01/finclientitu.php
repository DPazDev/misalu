<?
include ("../../lib/jfunciones.php");
sesion();
$buesente=("select entes.id_ente from entes where entes.nombre='PARTICULAR';");
$repbusente=ejecutar($buesente);
$databusente=assoc_a($repbusente);
$bussdivi=("select subdivisiones.id_subdivision from subdivisiones where subdivisiones.subdivision='PARTICULAR';");
$repsudivi=ejecutar($bussdivi);
$datasubdivi=assoc_a($repsudivi);
//
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

//
$lasubdivi=$datasubdivi['id_subdivision'];
$elidente=$databusente['id_ente'];

$cedclien=$_POST["lacedula"];
$nombrecli=$_POST["lanombre"];
$correocli=$_POST["lacorreo"];
$apecli=$_POST["laapelli"];
$genrot=$_POST["lagenero"];
$teleft=$_POST["latelefono"];
$fenaci=$_POST["lafenaci"];
$fedad=calcular_edad($fenaci);
$lciudad=$_POST["laciudad"];
$directi=$_POST["ladirecc"];
$comenta=$_POST["lacoment"];
$estatusti=4;
$bussiexistec=("select clientes.id_cliente from clientes where clientes.cedula='$cedclien';");
$repbusiexistec=ejecutar($bussiexistec);
$cuantoexistec=num_filas($repbusiexistec);
if($cuantoexistec<=0){
//Guardar los datos en la tabla clientes
$guardarcliente=("insert into clientes(nombres,apellidos,email,fecha_nacimiento,sexo,direccion_hab,
                               telefono_hab,telefono_otro,fecha_creado,hora_creado,
                               id_ciudad,comentarios,cedula,estado_civil,id_admin,edad) 
                               values(upper('$nombrecli'),upper('$apecli'),'$correocli','$fenaci','$genrot',
                               upper('$directi'),'$teleft','$teleft','$fecha',
                                '$hora',$lciudad,upper('$comenta'),'$cedclien','SOLTERO','$elid','$fedad');");
$resguardclien=ejecutar($guardarcliente);								
//fin de los registros en la tabla clientes;
/***********************************/
//Buscar el cliente guardado en la tabla clientes para guardarlo en la tabla titular
$busclient=("select clientes.id_cliente from clientes where clientes.cedula='$cedclien';");
$resbusclient=ejecutar($busclient);
$datbusclien=assoc_a($resbusclient);
$elidclienes=$datbusclien['id_cliente'];
//fin de la busquedad;
}else{
    $datcliexis=assoc_a($repbusiexistec);     
    $elidclienes=$datcliexis['id_cliente'];
    }
//**********************************//
//Guardar los datos en la tabla titular
  if ($genrot==0){
	$tmater=0;
		
	}else{
		 $tmater=0;
		}
//reviso si ya esta el titular en ese ente
$revisotitu=("select count(titulares) from titulares where titulares.id_cliente=$elidclienes and titulares.id_ente=$elidente");
$reprevisotitu=ejecutar($revisotitu);
$datarevisotitu=assoc_a($reprevisotitu);
$numversisotitu=$datarevisotitu[count];
if($numversisotitu<=0){
$guardaclientitu=("insert into titulares(id_cliente,fecha_ingreso_empresa,fecha_creado,
                                 hora_creado,id_ente,fecha_inclusion,id_admin,
                                 maternidad) values('$elidclienes','$fecha','$fecha',
                                '$hora','$elidente','$fecha','$elid','$tmater');");
$resguatitularcliente=ejecutar($guardaclientitu);								
//fin de los registros en la tabla titular;
//**********************************//
//Buscar el cliente guardado en la tabla titulares
$bustituclien=("select id_titular from titulares 
where id_cliente='$elidclienes' and fecha_creado='$fecha' and hora_creado='$hora';");
$resptituclien=ejecutar($bustituclien);
$dattituclien=assoc_a($resptituclien);
$elidtitulares=$dattituclien['id_titular'];
//fin de la busquedad;
//**********************************//
//Guardar los datos en la tabla titulares_subdivisiones;
$guartitsubdivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values('$elidtitulares','$lasubdivi');");
$restitusbdivi=ejecutar($guartitsubdivi);							  
//fin de los registros en la tabla titulares_subdivisiones;
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
										   values('$idpropoliza','$elidtitulares','0','$fecha','$hora','0',
                                                       '$montopoliza','$montopoliza');");   
				$respuecobetb=ejecutar($regiscobetb);
//*********************************//                
//Guardar los datos en la tabla titulares_polizas;    
$regtipolizas=("insert into titulares_polizas(id_titular,id_poliza,fecha_creado,hora_creado) 
									  values('$elidtitulares','$elidpoliza','$fecha','$hora');");
		   $restipolizas=ejecutar($regtipolizas);	  
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha agregado un nuevo Cliente con el id_cliente $elidclienes ";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de los registros en la tabla logs;
//**********************************//           
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

<?}?>
