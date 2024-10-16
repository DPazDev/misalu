<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$idrecibo=$_REQUEST['elidrecibo'];
$aumento=$_REQUEST['elporcentaje'];
$elaumento=$_REQUEST['elaumento'];
$actualizaraumento=("update tbl_recibo_contrato set poraumento=$aumento,conaumento=$elaumento where id_recibo_contrato=$idrecibo;");
$repactulaumento=ejecutar($actualizaraumento);
$mensaje="$elus, ha calculado el aumento de renovacion al id_recibo_contrato No. $idrecibo";
$relog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip');");
$inrelo=ejecutar($relog);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Poliza actualizada!!!</td>  
     </tr>
</table>
