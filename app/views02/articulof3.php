<?php
include ("../../lib/jfunciones.php");
sesion();
$elarti=$_REQUEST['elnumarti'];
$articulos=("select * from articulocontrato where id_articulocon=$elarti");
$reparticulos=ejecutar($articulos);
$dataarti=assoc_a($reparticulos);
?>
<input type="hidden" id="elidarti" value="<?echo $elarti?>">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Art&iacute;culos resolución de contrato</td>          
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
             <td class="tdtitulos">N&uacute;mero del art&iacute;culo :</td>
             <td class="tdcampos"><input type="text" id="numarti" class="campos" size="10" onkeypress="return elnumero(event);" value="<?echo $dataarti['numarticulo']?>"></td>
      </tr>
      <tr>
             <td class="tdtitulos">Nombre de Ley:</td>
             <td class="tdcampos"><input type="text" id="nomley" class="campos" size="60" value="<?echo $dataarti['nombreley']?>"></td>
      </tr>
      <tr>
             <td class="tdtitulos">Nombre del art&iacute;culo:</td>
             <td class="tdcampos"><input type="text" id="nomarti" class="campos" size="60" value="<?echo $dataarti['nombre_articulo']?>"></td>
      </tr>
      <tr>
		     <td class="tdtitulos">Descripci&oacute;n del art&iacute;culo:</td>  
	         <td class="tdtitulos"><TEXTAREA COLS=65 ROWS=6 id="descriarti" class="campos"><?echo $dataarti['concepto']?></TEXTAREA></td>        
	    </tr>
	    <tr>
            <td  title="Guardar art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="guardaresarti(); return false;" >Guardar</label></td>   
        </tr>
</table>
