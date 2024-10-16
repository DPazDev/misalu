<?php
include ("../../lib/jfunciones.php");
sesion();
$lapolizaid=$_REQUEST['polizaid'];
$nomdpoliza=$_REQUEST['nombrepoliz'];
$buspoliza=("select * from propiedades_poliza where id_poliza=$lapolizaid;");
$repbuspoliza=ejecutar($buspoliza);

//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$lapolizaid';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=' '.$Moneda['simbolo'].' ('.$Moneda['moneda'].')';


?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Propiedades de la p&oacute;liza <?echo $nomdpoliza?></td>  
     </tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
     <tr>
        <th class="tdtitulos">Nombre.</th>
        <th class="tdtitulos">Descripci&oacute;n.</th> 
	    <th class="tdtitulos">Monto. <?php echo $moneda; ?></th> 				 
	    <th class="tdtitulos">Opc.</th> 				 
    </tr>
   <? $i=1; 
      
	while($propoli=asignar_a($repbuspoliza,NULL,PGSQL_ASSOC)){
	  $valor="caja$i";	
	  $valort="cajat$i";	
	  $eldi="eldiv$i";
    ?>
      <tr>
	    <td class="tdcampos"><?echo $propoli['cualidad'];?></td> 
	    <td class="tdcampos"><TEXTAREA COLS=70 ROWS=2 id='<?echo $valort?>' class="campos"><?echo $propoli['descripcion'];?></TEXTAREA></td>      
	    <td class="tdcampos"><input type='text' class="campos" id='<?echo $valor?>' value='<?echo $propoli['monto'];?>' size=5></td>     
	    <td title="Guardar Cambio"><label class="boton" style="cursor:pointer" onclick="guardacpropoli('<?echo $propoli[id_propiedad_poliza]?>',<?echo $i?>)" >Guardar</label></td>    
	    <td class="tdcampos"><div id='<?echo $eldi?>'></div></td> 
      </tr>
<? $i++;
    }?>
   
</table>
