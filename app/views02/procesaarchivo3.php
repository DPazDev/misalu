<?
include ("../../lib/jfunciones.php");
sesion();
$archivop=$_REQUEST['elarchivo'];
$estadoc=$_REQUEST['elestado'];
$fechac=$_REQUEST['elfecha'];
$comentario=strtoupper($_REQUEST['elcomenta']);
$ruta="../../files/$archivop";
//
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$file = fopen($ruta, "r") or exit("Unable to open file!");
$cuant=0;
$cuanb=0;
$cuerr=0;
//Output a line of the file until the end is reached

while(!feof($file))
{
list($cedula,$ente)=explode(",",fgets($file));
if(!empty($cedula) and !empty($ente)){
	$buscoidtitular=("select titulares.id_titular from titulares,clientes where 
	                  clientes.id_cliente=titulares.id_cliente and
	                  titulares.id_ente=$ente and
	                  clientes.cedula='$cedula';");
    $repbustitu=ejecutar($buscoidtitular);	                  
    $cuntitu=num_filas($repbustitu);
    if($cuntitu>=1){
    $datostitu=assoc_a($repbustitu);
    $eltitus=$datostitu[id_titular];
    $actestb=("update estados_t_b set id_estado_cliente=$estadoc where id_titular=$eltitus and id_beneficiario=0");
    $repactestb=ejecutar($actestb);
    //************Guardar el comentario**********************//
     $guarcoment=("update clientes set comentarios='$comentario' from titulares where
                    clientes.id_cliente=titulares.id_cliente and titulares.id_titular=$eltitus;");
     $repguarcoment=ejecutar($guarcoment);  
    $regisinclu=("insert into registros_exclusiones(fecha_inclusion,fecha_exclusion,id_titular,id_beneficiario,fecha_creado,id_estado_cliente,comentario,id_admin) 
                  values('$fecha','$fechac',$eltitus,0,'$fecha',$estadoc,'$comentario',$elid);");
    $repregisinclu=ejecutar($regisinclu);
    $mensaje="$elus, ha cambiado el estado del cliente desde el modulo exclusi&oacute;n por lote al titular=$eltitus";
    $relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
    $inrelo=ejecutar($relog);
    //buscamos los beneficiarios del titula si es que tiene
    $benefi=("select beneficiarios.id_beneficiario from beneficiarios where
              beneficiarios.id_titular=$eltitus;");
    $repbenefi=ejecutar($benefi);         
    $cuant++; 
    while($losben=asignar_a($repbenefi,NULL,PGSQL_ASSOC)){
	  $actestben=("update estados_t_b set id_estado_cliente=$estadoc where id_beneficiario=$losben[id_beneficiario] and
	               id_titular=$eltitus;");
	 $repacteben=ejecutar($actestben);             
	  	 //************Guardar el comentario**********************//
      $guarcomenbe=("update clientes set comentarios='$comentario' from beneficiarios where
                     clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$losben[id_beneficiario];");
      $repguarcomenbe=ejecutar($guarcomenbe);                            
	  $regisbene=("insert into registros_exclusiones(fecha_inclusion,fecha_exclusion,id_titular,id_beneficiario,fecha_creado,id_estado_cliente,comentario,id_admin) 
                  values('$fecha','$fechac',$eltitus,$losben[id_beneficiario],'$fecha',$estadoc,'$comentario',$elid);");
      $repregisben=ejecutar($regisbene);            
      $mensaje1="$elus, ha cambiado el estado del cliente desde el modulo exclusi&oacute;n por lote al beneficiario=$losben[id_beneficiario]";
      $relog1=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje1'),'$elid','$fecha','$hora','$ip');");
      $inrelo1=ejecutar($relog1);
      $cuanb++;
	}
  }else{
	    $cuerr++;
	  }	
 }
}
fclose($file);
?>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Se ha realizado exitosamente el cambio a <?echo $cuant?> titulares y <?echo $cuanb?> beneficiarios. Errores en C&eacute;dula: <?echo $cuerr?></td>  
     </tr>
</table>
