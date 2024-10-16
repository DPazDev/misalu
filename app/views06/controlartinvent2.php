<?
include ("../../lib/jfunciones.php");
sesion();
$aproxnom=strtoupper($_POST['aproxim']);
$fecha1=$_POST['fe1'];
$fecha2=$_POST['fe2'];
$arregloarticulos=$_POST['arreglo'];
$tok = explode(',',$arregloarticulos); 
$son=1;

foreach ( $tok as $tok ) 
{ 
   if($tok>0){	
   $caja[$son]=$tok;
   $son++;
  }

}
if(empty($aproxnom)){
for ($i=1;$i<$son;$i++){
 if($i<=1){
    $otracaden="tbl_insumos.id_tipo_insumo=$caja[$i]";
	$otracaden1=$otracaden;
  }else{
        $cadena="or tbl_insumos.id_tipo_insumo=$caja[$i]";
        $otracaden="$otracaden $cadena"; 
		$otracaden1="($otracaden)";
       }
}
}else{
	   $query="tbl_insumos.insumo like('%$aproxnom%')";
	}
$buscararticulos=("select tbl_insumos.insumo,tbl_laboratorios.laboratorio,clinicas_proveedores.nombre,
       tbl_ordenes_compras.no_factura,tbl_insumos_ordenes_compras.cantidad,
       tbl_insumos_ordenes_compras.monto_unidad,
       tbl_insumos_ordenes_compras.monto_producto, tbl_ordenes_compras.id_orden_compra,
      tbl_ordenes_compras.fecha_compra
from
      tbl_insumos,tbl_laboratorios,clinicas_proveedores,tbl_ordenes_compras,
      tbl_insumos_ordenes_compras,proveedores
where
      tbl_insumos.id_insumo=tbl_insumos_ordenes_compras.id_insumo and
      tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
      tbl_ordenes_compras.id_orden_compra=tbl_insumos_ordenes_compras.id_orden_compra and
      tbl_ordenes_compras.id_proveedor_insumo=proveedores.id_proveedor and
      proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor and
      tbl_ordenes_compras.fecha_compra between '$fecha1' and '$fecha2' and
      $otracaden1 $query order by tbl_ordenes_compras.fecha_compra desc;");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
if ($loqhay==0){
	echo"
      <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
<br>
     <tr> 
	 
         <td colspan=4 class=\"titulo_seccion\">No hay art&iacute;culos cargados!! </td>  
     </tr>
</table>	 
     ";
	}else{
?>		
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
	   <td class="titulo_seccion" colspan=4 class="titulo_seccion">Reporte de art&iacute;culos en la dependencia <?echo $ladepenes?></td> 
          <td  class="titulo_seccion" title="Regresar al modulo"><label class="boton" style="cursor:pointer" onclick="invencompra()" >Regresar</label></td>    
     </tr>
   </table>	
   
   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
		        <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">Art&iacute;culo.</th>
                 <th class="tdtitulos">Laboratorio.</th> 
		         <th class="tdtitulos">Proveedor.</th>                
				 <th class="tdtitulos">Nu. Factura.</th>                 
				 <th class="tdtitulos">Fecha Compra.</th>                 
				 <th class="tdtitulos">Cantidad.</th>                 
				 <th class="tdtitulos">Precio Unitario.</th>                 
				 <th class="tdtitulos">Precio Total.</th>                 
              </tr>
			<?
			    $linea=1;   
                $codigo=""; 
				$totalg=0;			
			    while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
				   $codigo="$controlarti[id_dependencia]-$controlarti[id_insumo]-$controlarti[id_tipo_insumo]";
				   
				
	           echo "
		<tr>
			<td class=\"tdcampos\">[$linea]-</td>
             <td class=\"tdcampos\">$controlarti[insumo]</td>
			<td class=\"tdcampos\">$controlarti[laboratorio]</td>
			<td class=\"tdcampos\">$controlarti[nombre]</td>
                        <td class=\"tdcampos\"><a href=\"views06/lafacturaprov.php?numfactu=$controlarti[id_orden_compra]\" title=\"Ver factura\" class=\"boton\" onclick=\"Modalbox.show(this.href, {title: this.title, width:900,height:400, overlayClose: false}); return false;\">$controlarti[no_factura]</a></td>
            <td class=\"tdcampos\">$controlarti[fecha_compra]</td>
			<td class=\"tdcampos\">$controlarti[cantidad]</td>
			<td class=\"tdcampos\">$controlarti[monto_unidad]</td>
			<td class=\"tdcampos\">$controlarti[monto_producto]</td>
		</tr>
		";
		$linea++;
		$codigo="";
		$totalg=$totalg+$controlarti[monto_producto];   
	}
	    
	 echo "
		<tr>
			<td class=\"tdcampos\"></td>
             <td class=\"tdcampos\"></td>
			<td class=\"tdcampos\"></td>
			<td class=\"tdcampos\"></td>
			<td class=\"tdcampos\"></td>
            <td class=\"tdcampos\"></td>
			<td class=\"tdcampos\"></td>
			<td class=\"tdcampos\">Total General</td>
			<td class=\"tdcampos\">$totalg</td>
		</tr>
		";
	
	?>  
  <tr><td class="tdcampos" colspan=8><hr></td></tr>	
  <tr>
     <? $url="'views06/reportinvcompra.php?fechai1=$fecha1&fechaf2=$fecha2&articulos=$arregloarticulos&aprox=$aproxnom'"; ?>  
       <td>
	     <td colspan=4 class="tdcampos"><a href="javascript: imprimir(<?echo $url?>);" class="boton">Imprimir</a>
	   </td>   
     
  </tr>  
  </table>			  
   
<?}?>
