<?php
include ("../../lib/jfunciones.php");
sesion();
$nompoliza=$_REQUEST[lapoli];
$lapoliza=$_REQUEST[laidpoli];
$buspoliza=("select * from polizas where id_poliza=$lapoliza");
$repbuspoliza=ejecutar($buspoliza);
$ladapoliza=assoc_a($repbuspoliza);


//////////MONEDA EXPRESIONES////
$SqlMoneda=("select tbl_monedas.id_moneda, tbl_monedas.moneda , tbl_monedas.simbolo  from polizas,tbl_monedas where polizas.id_moneda=tbl_monedas.id_moneda and id_poliza='$lapoliza';");
$MonedaEJ=ejecutar($SqlMoneda);
$Moneda=asignar_a($MonedaEJ,NULL,PGSQL_ASSOC);
$id_moneda=$Moneda['id_moneda'];


?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Propiedades de la Poliza <?echo $nompoliza?></td>  
     </tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos">Nombre:</td>
       <td class="tdcampos" ><input type="text" id="elnopoliza" class="campos" size='40' value="<?echo $nompoliza?>"></td>
     </tr>
     <tr>
       <td class="tdtitulos">Descripci&oacute;n:</td>
       <td class="tdcampos" ><TEXTAREA COLS=60 ROWS=2 id="descripoli" class="campos"><?echo $ladapoliza['descripcion']?></TEXTAREA></td>
     </tr>
      <tr>
       <td class="tdtitulos">Estatus:</td>
       <?php if($ladapoliza[activar]==1){?>
         <td class="tdcampos" >
             <input type="radio" name="poliactiv" value="1" checked> Activa
             <input type="radio" name="poliactiv" value="0" > Desactiva
         </td>
       <?php }else{?>
           <td class="tdcampos" >
             <input type="radio" name="poliactiv" value="1"> Activa
             <input type="radio" name="poliactiv" value="0" checked> Desactiva
         </td>  
       <?}?>  
     </tr>
     
  <!-- --------MONEDA PARA CALCULAR POLIZA--- -->         
			<tr>
             <td class="tdtitulos">¿MONTOS EXPRESADOS EN?:</td>
             <td class="tdcampos">
<?php		
	//////NOMESCLATURAS DE MONEDADAS ///
$SqlMonedas=("select id_moneda,moneda,nombre_moneda,pais from tbl_monedas ORDER BY id_moneda ASC;");
$monedas=ejecutar($SqlMonedas);
?>		
			<select id="moneda" class="campos"  style="width: 230px;" >
			               <?php  
			         while($CodMoneda=asignar_a($monedas,NULL,PGSQL_ASSOC)){
			//ACTIVAR LA OPSION PRESELECIONADA
			if($id_moneda==$CodMoneda['id_moneda']) { $selectopsion='selected';}
			else {
				$selectopsion='';}
				
				?>
					<option value="<?php echo $CodMoneda[id_moneda]?>" <?php echo $selectopsion; ?> > <?php echo "$CodMoneda[moneda]-$CodMoneda[nombre_moneda]($CodMoneda[pais])"?></option>
			      <?php }?>
		     </select>  
			
			
			</td>
            </tr>            
                
     
     
     
     
     
     <tr>
       <td class="tdtitulos">Tipo poliza:</td>
       <?php if($ladapoliza[particular]==1){?>
         <td class="tdcampos" >
             <input type="radio" name="tipopoli" value="1" checked> Particular
             <input type="radio" name="tipopoli" value="0" > Colectiva
         </td>
       <?php }else{?>
           <td class="tdcampos" >
             <input type="radio" name="tipopoli" value="1"> Particular
             <input type="radio" name="tipopoli" value="0" checked> Colectiva
         </td>  
       <?php }?>  
     </tr>
     <tr>
       <td class="tdtitulos">Visible a intermediario:</td>
       <?php if($ladapoliza[intermediario]==1){?>
         <td class="tdcampos" >
             <input type="radio" name="viinter" value="0" > No
             <input type="radio" name="viinter" value="1" checked> Si
         </td>
       <?php }else{?>
           <td class="tdcampos" >
             <input type="radio" name="viinter" value="0" checked> No
             <input type="radio" name="viinter" value="1" > Si
         </td>  
       <?php }?>  
     </tr>
     <tr>
           <td  class="tdcampos" title="Guardar"><br><label class="boton" style="cursor:pointer" onclick="segudnombre()" >Guardar</label></td>    
     </tr>      
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='policambio'></div>
