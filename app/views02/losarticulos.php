<?php
include ("../../lib/jfunciones.php");
sesion();
$articulo=("select * from articulocontrato order by numarticulo");
$reparticulos=ejecutar($articulo);
$cuantosarti=num_filas($reparticulos);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Art&iacute;culos resolución de contrato</td>          
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
