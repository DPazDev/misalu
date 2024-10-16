<?
include ("../../lib/jfunciones.php");
sesion();
$tipoinsumo=("select tbl_tipos_insumos.id_tipo_insumo,tbl_tipos_insumos.tipo_insumo from tbl_tipos_insumos order by tbl_tipos_insumos.tipo_insumo;");
$reptipoinsumo=ejecutar($tipoinsumo);
$cuantosinsumo=num_filas($reptipoinsumo);
$_SESSION['pedidodepen']=0;
$espar=$cuantosinsumo%2;
  if ($espar==0){
	 $enfila=$cuantosinsumo/2;
	 $enfila1=$enfila+2; 
  }else{	 
   $enfila=ceil($cuantosinsumo/2);
  $enfila1=$enfila+2;   
  }   
$misdependecias=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia from tbl_dependencias order by tbl_dependencias.dependencia");
$repuesmisdepend=ejecutar($misdependecias);  
$repuesmisdepend1=ejecutar($misdependecias);  
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Control de art&iacute;culos despachados a dependencia</td>  
     </tr>
</table>	
<table class="tabla_cabecera5"   cellpadding=0 cellspacing=0>
    <tr>
       <td colspan=2><br><td>
    </tr>
    <tr>
         <td class="tdtitulos" colspan="1">Tipo de insumo(s):</td>
		<?
		 $lineas=1;
		 while($insumos=asignar_a($reptipoinsumo,NULL,PGSQL_ASSOC)){ 
		echo"
<tr><td class=\"tdcampos\"  colspan=\"1\"><input type=\"checkbox\"  id=\"caja$lineas\" value=\"$insumos[id_tipo_insumo]\">$insumos[tipo_insumo]</td>";?>
         
			<?   

        if ($lineas==$enfila){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Despachado por:</td>";?>
			<td class="tdcampos"  colspan="1">
			   <select id="varprovsaliente" class="campos"  style="width: 230px;" >
			        <option value=""></option>
           <?php  while($haydepend=asignar_a($repuesmisdepend,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $haydepend[id_dependencia]?>"> <?php echo "$haydepend[dependencia]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 			      
			<?
	echo"</tr>
           
          ";	
	    
		}
		 if($lineas==$enfila1){
			echo"
               <td colspan=2 class=\"tdtitulos\">* Seleccione fecha inicio:
	          <input readonly type=\"text\" size=\"10\" id=\"Fini\" class=\"campos\" maxlength=\"10\">
	          <a href=\"javascript:void(0);\" onclick=\"g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')\" title=\"Ver calendario\">
	          <img src=\"../public/images/calendar.gif\" class=\"cp_img\" alt=\"Seleccione la Fecha\"></a><br>
			  <td colspan=2 class=\"tdtitulos\">* Seleccione fecha final:
	          <input readonly type=\"text\" size=\"10\" id=\"Fifi\" class=\"campos\" maxlength=\"10\">
	          <a href=\"javascript:void(0);\" onclick=\"g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')\" title=\"Ver calendario\">
	         <img src=\"../public/images/calendar.gif\" class=\"cp_img\" alt=\"Seleccione la Fecha\"></a>
             </td>
           </tr>";
			
			}
			 if ($lineas==5){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Recibido por:</td>
			   <td class=\"tdcampos\"  colspan=\"1\">
			   <select id=\"proveentrante\" class=\"campos\"  style=\"width: 230px;\" >
			        <option value=\"0\">Todas</option>";
                   while($depend=asignar_a($repuesmisdepend1,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $depend[id_dependencia]?>"> <?php echo "$depend[dependencia]"?></option>
			      <?php
			             }
		              echo"
					  
		              </select></td> 			
			  
           </tr>";
			}
	    $lineas++;
		}?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
	     <td  title="Generear reporte de control de art&iacute;culos"><label class="boton" style="cursor:pointer" onclick="ReporteCArti(); return false;" >Generar Reporte</label></td>   
	  </tr>   
</table>
<div id="repp" ><img alt="spinner" id="spinner1" src="../public/images/esperar.gif" style="display:none;" />
</div>