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
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Control de art&iacute;culos en dependencia</td>  
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
			echo"<td class=\"tdtitulos\" colspan=\"1\">Dependencia :</td>";?>
			<td class="tdcampos"  colspan="1">
			   <select id="proveedcom" class="campos"  style="width: 230px;" >
			        <option value=""></option>
                              <option value="0">Todas</option>
           <?php  while($haydepend=asignar_a($repuesmisdepend,NULL,PGSQL_ASSOC)){?>
						<option value="<?php echo $haydepend[id_dependencia]?>"> <?php echo "$haydepend[dependencia]"?></option>
			      <?php
			             }
		              ?>
		              </select></td> 
			      </td>
			<?
	echo"</tr>
           
          ";	
	    
		}
	    $lineas++;
		}?>
       </tr>
	 <br>     
	   <tr>
             <input type="hidden" id="tocajas" value="<?echo $lineas;?>">
	     <td  title="Generear reporte de control de art&iacute;culos"><label class="boton" style="cursor:pointer" onclick="ReporteCArti1(); return false;" >Generar Reporte</label></td>   
	  </tr>   
</table>
