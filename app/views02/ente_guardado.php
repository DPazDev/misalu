<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$entees=strtoupper($_POST['elentenomb']);
$tipente=$_POST['eltipente'];
$rifente=strtoupper($_POST['elrifente']);
$corrente=strtoupper($_POST['elcorrente']);
$telfente=$_POST['eltelfente'];
$faxente=$_POST['elfaxente'];
$dirente=strtoupper($_POST['eldirecc']);
$ciudente=$_POST['elciudad'];
$contrasucu=$_POST['elsucurcont'];
$comisiona=$_POST['elcomisio'];
$fechaconini=$_POST['elinicio'];
$fechaconfin=$_POST['elfin'];
$montcontra=$_POST['elmontocon'];
$porccontra=$_POST['elporcentaj'];
$descuencotra=$_POST['eltpdescuento'];
$forpagocon=$_POST['elformpago'];
$elcontactono=strtoupper($_POST['elnomcotacto']);
$telfcontacto=$_POST['eltlfcontacto'];
$correcontacto=strtoupper($_POST['elcorrcontac']);
$dirrcontacto=strtoupper($_POST['eldirrcontacto']);
$fecinibenfic=$_POST['elfechiniben'];
$fecfinibenfic=$_POST['elfechfinben'];
$laspolizas=$_POST['arrpoliza'];
$arregpoliza=explode(",",$laspolizas);
$resulpoliza = count($arregpoliza);
//primero buscamos a ver si existe un ente con el nombre o rif indicado
$busentexis=("select entes.id_ente from entes where entes.nombre='$entees' or entes.rif='$rifente';");
$repbusentexis=ejecutar($busentexis);
$cuaentexis=num_filas($repbusentexis);
if($cuaentexis>=1){?>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">El ente <?echo $entees?> ya existe!!</td>  
     </tr>
</table>
    <?}else{
//fin de la busquedad de rif on nombre
//guardamos en la tabla entes
$guardarente=("insert into 
                             entes(nombre,telefonos,direccion,email,rif,nombre_contacto,telefonos_contacto,email_contacto,fecha_creado,
                                       hora_creado,fax,id_ciudad,fecha_inicio_contrato,fecha_renovacion_contrato,id_sucursal,
                                       id_tipo_ente,forma_pago,fecha_inicio_contratob,fecha_renovacion_contratob) 
                               values('$entees','$telfente','$dirente','$corrente','$rifente','$elcontactono','$telfcontacto','$correcontacto','$fecha',
                                          '$hora','$faxente',$ciudente,'$fechaconini','$fechaconfin',$contrasucu,
                                          $tipente,'$forpagocon','$fecinibenfic','$fecfinibenfic');");
$repguarente=ejecutar($guardarente);   
//fin de guardar entes
//buscamos el ente guardado
$busente=("select entes.id_ente,entes.id_tipo_ente,entes.fecha_creado from entes where entes.nombre='$entees' and fecha_creado='$fecha' and hora_creado='$hora' ;");
$rpbusente=ejecutar($busente);
$infobusente=assoc_a($rpbusente);
$elidentes=$infobusente['id_ente'];
$eltipoente=$infobusente[id_tipo_ente];//------>ya tenenos el tipo de ente
$fecente=$infobusente[fecha_creado];//------>ya tenenos la fecha creado
list($anoente,$mesente)=explode('-',$fecente);
$elcodigente="$elidentees-$eltipoente-$mesente$anoente";
$atcodigo=("update entes set codente='$elcodigente' where id_ente=$elidentes;");
$repatcodigo=ejecutar($atcodigo);
//fin de busqueda de entes
//guardamos en la tabla entes_comisionados
$guardarentecomisio=("insert into 
                           entes_comisionados(id_ente,id_comisionado,descuento_recargo,porcentaje_comision,fecha_creado,hora_creado) 
                           values($elidentes,$comisiona,'$descuencotra','$porccontra','$fecha','$hora');");
$repgardaentcomsio=ejecutar($guardarentecomisio);         
//fin de guardar en entes_comisionados
//guardamos en polizas_entes
for($i=0;$i<=$resulpoliza;$i++){
      if($arregpoliza[$i]>0){
            $guardpolientes=("insert into polizas_entes(id_ente,id_poliza) values($elidentes,$arregpoliza[$i]);");
            $repguardpolientes=ejecutar($guardpolientes);
            }
    }
//fin de guardar en polizas entes
//Guardar los datos en la tabla logs;
$mensaje="$elus, ha agregado un nuevo ente con el id_ente $elidentes y nombre $entees";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin de los registros en la tabla logs;
}
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">El ente <?echo $entees?> se registro exitosamente!!</td>  
     </tr>
</table>
