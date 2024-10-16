<?php
include ("../../lib/jfunciones.php");
sesion();
$eladimn=$_REQUEST['elusuario'];
$elcedula=$_REQUEST['elcedula'];
if(!empty($eladimn)){
    $buscacotiza=("select tbl_cliente_cotizacion.id_cliente_cotizacion,tbl_cliente_cotizacion.nombres,
                               tbl_cliente_cotizacion.apellidos,tbl_cliente_cotizacion.no_cotizacion,
                               tbl_cliente_cotizacion.fecha_creado from tbl_cliente_cotizacion
                            where
                               tbl_cliente_cotizacion.id_admin=$eladimn;");
}else{
    $buscacotiza=("select tbl_cliente_cotizacion.id_cliente_cotizacion,tbl_cliente_cotizacion.nombres,
                               tbl_cliente_cotizacion.apellidos,tbl_cliente_cotizacion.no_cotizacion,
                               tbl_cliente_cotizacion.fecha_creado from tbl_cliente_cotizacion
                            where
                               tbl_cliente_cotizacion.rif_cedula='$elcedula';");     
    }
 $repbuscotiza=ejecutar($buscacotiza);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">No. Cotizaci&oacute;n.</th>
                 <th class="tdtitulos">Titular.</th>
        	     <th class="tdtitulos">Opci&oacute;n.</th> 
		     </tr>
			 <?php 
			    while($lacoti=asignar_a($repbuscotiza,NULL,PGSQL_ASSOC)){
				?>
			    <tr>
				   <td class="tdcampos"><?echo $lacoti['no_cotizacion'];?></td>
				   <td class="tdcampos"><?echo "$lacoti[nombres] $lacoti[apellidos]";?></td> 
                   <td class="tdcampos"><label title="Imprimi planilla solicitud" class="boton" style="cursor:pointer" onclick="planillasolicitu('<?echo $lacoti['id_cliente_cotizacion']?>')" >Imprimir</label></td>
                </tr>   

<?}?>
</table>   