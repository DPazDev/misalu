<?php
include ("../../lib/jfunciones.php");
sesion();
$lapolizaid=$_POST['polizaid'];
$nomdpoliza=$_POST['lapoli'];
$veintermedi=$_POST['esinter'];
$buspoliza=("select * from propiedades_poliza where id_poliza=$lapolizaid;");
$repbuspoliza=ejecutar($buspoliza);
$_SESSION['matriz']=array();
$_SESSION['pasopedido']=0;


//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$lapolizaid';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$moneda=' '.$Moneda['simbolo'].' ('.$Moneda['moneda'].')';

?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Propiedades de la p&oacute;liza <?php echo $nomdpoliza?></td>  
         <td  class="titulo_seccion" title="Regresar al modulo"><label class="boton" style="cursor:pointer" onclick="ver_polizas()" >Regresar</label></td>   
     </tr>
</table>
<input type='hidden' id='lapoliza' value='<?echo $lapolizaid?>'>
<input type='hidden' id='nombpoliza' value='<?echo $nomdpoliza?>'>
<input type='hidden' id='intermediario' value='<?echo $veintermedi?>'>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
     <tr>
            <th class="tdtitulos">No.</th>
            <th class="tdtitulos">Nombre.</th>
            <th class="tdtitulos">Descripci&oacute;n.</th> 
	        <th class="tdtitulos">Monto.</th> 				 
	        			 
    </tr>
   <? $i=1; 
	while($propoli=asignar_a($repbuspoliza,NULL,PGSQL_ASSOC)){
    ?>
       <tr>
	    <td class="tdcampos"><?php echo $i;?></td>
	    <td class="tdcampos"><?php echo $propoli['cualidad'];?></td> 
	    <td class="tdcampos"><?php echo $propoli['descripcion'];?></td>      
	    <td class="tdcampos"><?php echo $propoli['monto']; echo $moneda; ?></td>      
      </tr>
    <? $i++;
        }?>
        <tr>
           <td  class="tdcampos"></td>    
           <td  class="tdcampos" title="Agregar nueva propiedad"><label class="boton" style="cursor:pointer" onclick="nuevpropoli('<?echo $lapolizaid?>','<?echo $nomdpoliza?>')" >Agregar Propiedad</label></td>    
           <td  class="tdcampos" title="Modificar propiedad"><label class="boton" style="cursor:pointer" onclick="modifpropoli()" >Modificar Propiedad</label></td>
           <td  class="tdcampos" title="Modificar poliza"><label class="boton" style="cursor:pointer" onclick="modifnompoli('<?echo $nomdpoliza?>')" >Modificar poliza</label></td>
        </tr>
</table>

<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
<div id='nombrepoli'></div>
<div id='gnombrepoli'></div>
<div id='poliguard'></div>

