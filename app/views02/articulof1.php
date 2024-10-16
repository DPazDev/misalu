<?php
include ("../../lib/jfunciones.php");
sesion();
$articulo=("select * from articulocontrato order by numarticulo");
$reparticulos=ejecutar($articulo);
$cuantosarti=num_filas($reparticulos);
?>
<input type="hidden" id="elidarti" value="0">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Art&iacute;culos resolución de contrato</td>          
	</tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
             <td class="tdtitulos">N&uacute;mero del art&iacute;culo :</td>
             <td class="tdcampos"><input type="text" id="numarti" class="campos" size="10" onkeypress="return elnumero(event);"></td>
        </tr>
        <tr>
             <td class="tdtitulos">Nombre de Ley:</td>
             <td class="tdcampos"><input type="text" id="nomley" class="campos" size="60" value="<?echo $dataarti['nombre_articulo']?>"></td>
      </tr>
        <tr>
             <td class="tdtitulos">Nombre del art&iacute;culo:</td>
             <td class="tdcampos"><input type="text" id="nomarti" class="campos" size="60"></td>
        </tr>
        <tr>
		     <td class="tdtitulos">Descripci&oacute;n del art&iacute;culo:</td>  
	         <td class="tdtitulos"><TEXTAREA COLS=65 ROWS=6 id="descriarti" class="campos"></TEXTAREA></td>        
	    </tr>
	    <tr>
            <td  title="Guardar art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="guardaresarti(); return false;" >Guardar</label></td>   
        </tr>
</table>  
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
   
<?
  if($cuantosarti>=1){
?>
<div id='losarticulos'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Art&iacute;culos creados</td>          
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">#Art&iacute;culo.</th>  
			     <th class="tdtitulos">Ley.</th>  
                 <th class="tdtitulos" align="center">Nombre.</th>
                 <th class="tdtitulos" align="center">Descripci&oacute;n.</th>
                 <th class="tdtitulos">Opci&oacute;n.</th>  
              </tr>  
           <? 
              while($datolosarti=asignar_a($reparticulos,NULL,PGSQL_ASSOC)){
           ?> 
             <tr>
              <td class="tdcampos" align="justify"><?echo $datolosarti['numarticulo']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['nombreley']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['nombre_articulo']?></td>   
              <td class="tdcampos" align="justify"><?echo $datolosarti['concepto']?></td>   
              <td  title="Modificar art&iacute;culo"><label class="boton" style="cursor:pointer" onclick="Modifarti('<?echo $datolosarti[id_articulocon]?>'); return false;" >Modificar</label></td>   
             </tr> 
              <tr><td colspan=5><hr></td></tr>
           <?}?>   
           
</table>                 
              
</div>
<?}?>
