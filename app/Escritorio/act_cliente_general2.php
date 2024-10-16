<?php
include ("../../lib/jfunciones.php");
sesion();
$clienteid=$_REQUEST['clienteid'];
$ceduclien=$_REQUEST['lacedula'];
$elnombre=strtoupper($_REQUEST['nombre']);
$elapellido=strtoupper($_REQUEST['apellido']);
$elgenero=$_REQUEST['genero'];
$elnacimiento=$_REQUEST['nacimiento'];
$telehab=$_REQUEST['habtelef'];
$telecelu=$_REQUEST['celulartelef'];
$ladireccion=$_REQUEST['direccion'];
$elcomentario=strtoupper($_REQUEST['cliencoment']);
$titulares=$_REQUEST['sihaytitu'];
$beneficiaros=$_REQUEST['sihaybenfi'];
$arreglotitus=explode(",",$titulares);
$arreglobenefi=explode(",",$beneficiaros);
$cuantitu=count($arreglotitus);
$cuanbenefi=count($arreglobenefi);
$edadcliente=calcular_edad($elnacimiento);
//guardar todo en las distintas tablas 
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

//primero actualizamos todos los datos del cliente
$updacliente=("update clientes set cedula='$ceduclien',nombres='$elnombre',apellidos='$elapellido',sexo='$elgenero',
               fecha_nacimiento='$elnacimiento',telefono_hab='$telehab',celular='$telecelu',direccion_hab='$ladireccion',
               edad=$edadcliente,comentarios='$elcomentario' 	  
              where id_cliente=$clienteid;");
$repupdcliente=ejecutar($updacliente);        
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha modificado al Cliente con el id_cliente $clienteid";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de la actualizacion principal del cliente      
if($cuantitu>=1){
for($i=0;$i<=$cuantitu;$i++){
	list($eltitularid,$elcodigotitu,$subdititu,$idpartida)=explode("|",$arreglotitus[$i]);	      
	$actcodigo=("update titulares set codigo_empleado='$elcodigotitu',tipo_partida=$idpartida where id_titular=$eltitularid;"); 
	$repactcodigo=ejecutar($actcodigo);
	$buscosubidi=("select titulares_subdivisiones.id_subdivision where id_titular=$eltitularid;");
	$repbusubdivi=ejecutar($buscosubidi);
	$datsudivi=assoc_a($repbusubdivi);
	$lasudvi=$datsudivi['id_subdivision'];
	if($lasudvi!=$subdititu){
		$actlasudivi=("update titulares_subdivisiones set id_subdivision=$subdititu where id_titular=$eltitularid;");
		$repactlasudivi=ejecutar($actlasudivi);
		}
	$cuantosubdivi=num_filas($repbusubdivi);
	if($cuantosubdivi<1){
		$actsudivi=("insert into titulares_subdivisiones(id_titular,id_subdivision) values($eltitularid,$subdititu); ");
		//$repactsudivi=ejecutar($actsudivi);
	}
	
}//fin del for del titular
}
if($cuanbenefi>=1){
  for($j=0;$j<=$cuanbenefi;$j++){
	list($elbenefi,$elparentesco)=explode("-",$arreglobenefi[$j]);	       
	$actbenefi=("update beneficiarios set id_parentesco=$elparentesco where id_beneficiario=$elbenefi and id_parentesco<>$elparentesco");
	$repactbenefi=ejecutar($actbenefi);
   }	 
}
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Cliente actualizado exitosamente!!</td>  
     </tr>
</table>
