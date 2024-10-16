<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$id_poliza=$_POST['lapolizaid'];
$matrizfin=$_SESSION['matriz'];
$cuantomatriz=count($matrizfin);
for($i=0;$i<=$cuantomatriz;$i++){
		$nomprop=strtoupper($matrizfin[$i][0]);
		$montopol=$matrizfin[$i][1];
		$descripol=strtoupper($matrizfin[$i][2]);
                if(($nomprop!='')&&($montopol!='')&&($descripol!='')){
		 $busconopoli=("select count(propiedades_poliza.cualidad) from propiedades_poliza where 
		 propiedades_poliza.cualidad='$nomprop' and id_poliza=$id_poliza");	
		 $repbuscopoli=ejecutar($busconopoli);
		 $datcopoli=assoc_a($repbuscopoli);
		 $cuantaspoli=$datcopoli[count];
		  if($cuantaspoli==0){
		       $guardarlapropiedad=("insert into propiedades_poliza(id_poliza,cualidad,descripcion,fecha_creado,hora_creado,monto,
		        reembolso,carta_compromiso,clave_emergencia,orden_atencion,orden_medica,servicio_general,
                        sexo,organo,monto_nuevo) 
			values ($id_poliza,'$nomprop','$descripol','$fecha','$hora','$montopol','1','1','1','1','1','1','2',
                        0,'$montopol');");
		       $repguardalapropiedad=ejecutar($guardarlapropiedad);
	         }
		}
}
    $mensaje="El usuario $elus ha cargado nuevas propiedades poliza a la poliza con id_poliza=$id_poliza"; 
	$actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	$repactualizoellog=ejecutar($actualizoellog);  
?>
<input type='hidden' id='idpolizap' value='<?echo $id_poliza?>'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=1 class="titulo_seccion">Las propiedades se han registrado exitosamente!!</td>  
        <td colspan=1 class="titulo_seccion" title="Registrar primas a p&oacute;lizas"><label class="boton" style="cursor:pointer" onclick="registrarpripol()" >Registrar Primas</label></td> 
		<td  class="titulo_seccion" title="Salir"><label class="boton" style="cursor:pointer" onclick="ira(); return false;" >Salir</label></td>
	</tr>
  </table>
<img alt="spinner" id="spinnerPFinPri" src="../public/images/esperar.gif" style="display:none;" />     
