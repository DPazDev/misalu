<?php
   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fechai1'];
   $fecre2=$_REQUEST['fechaf2'];
   $aproxnom=strtoupper($_REQUEST['aprox']);   
   $arregloarticulos=$_REQUEST['articulos'];
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
      tbl_ordenes_compras.fecha_compra between '$fecre1' and '$fecre2' and
      $otracaden1 $query order by tbl_insumos.insumo;");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >
<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp; </span>

<?if ($loqhay==0){
     echo"
          <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0> 
            <tr>
              <td class=\"datos_cliente3\" colspan=\"7\"><b>No hay informaci&oacute;n en el rango seleccionado</b></td>     
           </tr>
         </tale>
 	 ";?>
<?}else{?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
     <tr>
       <td class="datos_cliente3" colspan="7"><b>Relaci&oacute;n de compras desde el <? echo $fecre1;?> al <?echo $fecre2;?> </b></td>     
     </tr>
</tale>	 
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
	      <td class="fecha">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
	
	<tr> 
	   <td class="datos_cliente33"><strong><?php echo $lasucur;?></strong></td>
	</tr>
</tale>	 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
	<tr> 
		   <td class="datos_cliente3">Lin.</td>
	      <td class="datos_cliente3">Art&iacute;culo.</td>   
	     <td class="datos_cliente3">Laboratorio.</td>     
	     <td class="datos_cliente3">Proveedor.</td>   
	      <td class="datos_cliente3">Nu. Factura.</td>    
	     <td class="datos_cliente3">Fecha Compra.</td>   
	  <td class="datos_cliente3">Cantidad.</td>      
	<td class="datos_cliente3">Precio Unitario.</td>   
	  <td class="datos_cliente3">Precio Total.</td>     
	</tr>
	<?
	    
	    $i=1;
		$totalg=0;
	     while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
			
            echo"
                 <tr>
			<td class=\"cantidades\">[$i]-</td>
             <td class=\"cantidades\">$controlarti[insumo]</td>
			<td class=\"cantidades\">$controlarti[laboratorio]</td>
			<td class=\"cantidades\">$controlarti[nombre]</td>
			<td class=\"cantidades\">$controlarti[no_factura]</td>
            <td class=\"cantidades\">$controlarti[fecha_compra]</td>
			<td class=\"cantidades\">$controlarti[cantidad]</td>
			<td class=\"cantidades\">$controlarti[monto_unidad]</td>
			<td class=\"cantidades\">$controlarti[monto_producto]</td>           
		</tr>
              ";
		$i++;
         $totalg=$totalg+$controlarti[monto_producto];   
		}
         echo"
                 <tr>
			<td class=\"cantidades\"></td>
             <td class=\"cantidades\"></td>
			<td class=\"cantidades\"></td>
			<td class=\"cantidades\"></td>
			<td class=\"cantidades\"></td>
            <td class=\"cantidades\"></td>
			<td class=\"cantidades\"></td>
			<td class=\"cantidades\">Total General</td>
			<td class=\"cantidades\">$totalg</td>           
		</tr>
              ";   
	?>
	
</table>
<?}?>