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
$enteidmodi=$_POST['elenteid'];
$actelente=("update entes set nombre='$entees',telefonos='$telfente',direccion='$dirente',email='$corrente',rif='$rifente',
                                                nombre_contacto='$elcontactono',telefonos_contacto='$telfcontacto',email_contacto='$correcontacto',
                                                fax='$faxente',id_ciudad=$ciudente,fecha_inicio_contrato='$fechaconini',fecha_renovacion_contrato='$fechaconfin',
                                                 id_sucursal=$contrasucu,direccion_cobro='$dirrcontacto',id_tipo_ente=$tipente,forma_pago='$forpagocon',
                                                 fecha_inicio_contratob='$fecinibenfic',fecha_renovacion_contratob='$fecfinibenfic' where id_ente=$enteidmodi;");
$repactelente=ejecutar($actelente);
//actualizar la tabla entes_comisionados
$actcomisiona=("update entes_comisionados set id_comisionado=$comisiona,descuento_recargo='$descuencotra',porcentaje_comision='$porccontra'
                                                                             where id_ente=$enteidmodi;");
$repactcomisiona=ejecutar($actcomisiona);
//fin de entes_comisionados
//registrar en el log
$mensaje="$elus, ha modificado el ente con el id_ente $elidentes y nombre $entees";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
//fin del log
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">El ente <?echo $entees?> se ha modificado exitosamente!!</td>  
     </tr>
</table>