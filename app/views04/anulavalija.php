<?php
include ("../../lib/jfunciones.php");
sesion();
$lafactid = $_REQUEST[fidvalija];
$valijaserial = $_REQUEST[fserialvali];
//Elimininamos la Valija de 3 tablas
//Eliminamos de la tabla tbl_valija
//Eliminamos de la tabla tbl_valija_factura
//Eliminamos de la tabla tbl_valija_historial

$anulotblvalija = ("delete from tbl_valija where id_valija = $lafactid");
$repanulotblvalija = ejecutar($anulotblvalija);

$anulotblvalijafactura = ("delete from tbl_valija_factura where id_valija = $lafactid");
$repanulotblvalijafactura = ejecutar($anulotblvalijafactura);

$anulotblvalijahistorial = ("delete from tbl_valija_historial where id_valija = $lafactid"); 
$repanulotblvalijahistorial = ejecutar($anulotblvalijahistorial);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Se ha anulado Exitosamente la valija con Serial No.(<?php echo $valijaserial?>)</td>
     </tr>
</table>  
