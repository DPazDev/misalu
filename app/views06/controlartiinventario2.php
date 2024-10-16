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
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 	 
         <td colspan=4 class="titulo_seccion">Control de compras de art&iacute;culos</td>  
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

 
 if ($lineas==5){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Por aproximaci&oacute;n de nombre :</td>";?>
			<td class="tdcampos"  colspan="1">
			 <input type="text" id="nombarti" class="campos" size="30">
		   </td>
			<?
	echo"</tr>";
   }
        if ($lineas==6){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Fecha inicio :</td>";?>
			<td class="tdcampos"  colspan="1">
			 <input readonly type="text" size="10" id="Fini" class="campos" maxlength="10">
	        <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fini', 'yyyy-mm-dd')" title="Ver calendario">
	         <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a> 
		   </td>
			<?
	echo"</tr>
           
          ";	
	    
		}
		if ($lineas==7){
			echo"<td class=\"tdtitulos\" colspan=\"1\">Fecha fin :</td>";?>
			<td class="tdcampos"  colspan="1">
			 <input readonly type="text" size="10" id="Fifi" class="campos" maxlength="10">
	        <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'Fifi', 'yyyy-mm-dd')" title="Ver calendario">
	         <img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a> 
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
	     <td  title="Generear reporte de control de art&iacute;culos"><label class="boton" style="cursor:pointer" onclick="ReporteCArti2(); return false;" >Generar Reporte</label></td>   
	  </tr>   
</table>
