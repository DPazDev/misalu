<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$year=date("Y");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
list($lacoti,$algo)=explode('-',$_REQUEST['lacotizac']);
$generclien=$_REQUEST['elgenero'];  
$ceduclien=$_REQUEST['lacedula'];  
     
     
         $vesitmate=("select tbl_cliente_cotizacion.no_cotizacion,
                                       tbl_cliente_cotizacion.id_cliente_cotizacion,
                                       polizas.nombre_poliza,tbl_caract_cotizacion.id_poliza 
from 
   tbl_cliente_cotizacion,polizas,tbl_caract_cotizacion,primas
 where
       tbl_cliente_cotizacion.id_cliente_cotizacion=tbl_caract_cotizacion.id_cliente_cotizacion and
       tbl_cliente_cotizacion.rif_cedula='$ceduclien' and
       tbl_caract_cotizacion.id_poliza=polizas.id_poliza and
       tbl_caract_cotizacion.id_prima=primas.id_prima and
       primas.id_parentesco=9 and tbl_caract_cotizacion.id_cliente_cotizacion=$lacoti;");
          $repmate=ejecutar($vesitmate);
          $cuantmate=num_filas($repmate);?>
          <table class="tabla_citas"  cellpadding=0 cellspacing=0>
           <?if($cuantmate==0){?>
          
              <tr>
	             <td class="tdtitulos" colspan="1">
	                <input type="hidden" id="matno"   value="0">  
                    <input type="hidden" id="matsi"     value="0">  
                 </td>
	         </tr>
        <? }else{
            $datmate=assoc_a($repmate);
            $nommate=$datmate[nombre_poliza];
            $polizamater=$datmate[id_poliza];
            ?>
            <tr>
	             <td class="tdtitulos" colspan="1">Maternidad <?echo "($nommate)"?>:</td>
                 <td class="tdcampos" colspan="1">
                         <input type="radio" name="group1" id="matno" value="0" > No
                         <input type="radio" name="group1" id="matsi" value="<?echo "1-$polizamater"?>" checked>Si
                 </td>
	        </tr>
            <?}?>
         
</table>
