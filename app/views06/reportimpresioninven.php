<?
include ("../../lib/jfunciones.php");
sesion();
$dependencia=$_REQUEST['dependencia'];
if($dependencia==0){
    $queridepen="tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and";
}else{
     $queridepen="tbl_insumos_almacen.id_dependencia=$dependencia and";
    }
$nomdepen=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$dependencia");
$repnomdepen=ejecutar($nomdepen);
$datnomdepen=assoc_a($repnomdepen);
$ladepenes=$datnomdepen[dependencia];
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
for ($i=1;$i<$son;$i++){
 if($i<=1){
    $otracaden="tbl_insumos.id_tipo_insumo=$caja[$i]";
  }else{
        $cadena="or tbl_insumos.id_tipo_insumo=$caja[$i]";
        $otracaden="$otracaden $cadena"; 
       }
}
$buscararticulos=("select tbl_insumos.id_insumo,tbl_insumos.id_tipo_insumo,tbl_dependencias.id_dependencia,
       tbl_insumos.insumo, 
       tbl_dependencias.dependencia,tbl_laboratorios.laboratorio,
       tbl_insumos_almacen.cantidad
from
       tbl_insumos,tbl_dependencias,tbl_laboratorios,tbl_insumos_almacen
where
       ($otracaden) and
       tbl_insumos.id_insumo=tbl_insumos_almacen.id_insumo and
        $queridepen
       tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
       tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia order by tbl_insumos.insumo;");
$repbuscararti=ejecutar($buscararticulos);
$loqhay=num_filas($repbuscararti);
?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css" >
<body><img src="../../public/images/head.png">&nbsp;<span class="datos_cliente33"> RIF: J-31180863-9<br>
&nbsp;&nbsp;&nbsp; </span>
<?if ($loqhay==0){
	echo"
      <table class=\"tabla_citas\"  cellpadding=0 cellspacing=0 border=1> 
<br>
     <tr> 
	 
         <td colspan=4 class=\"fecha\">No hay art&iacute;culos cargados!! </td>  
     </tr>
</table>	 
     ";
	}else{
?>		
   <table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
     <tr> 
	   <td colspan=4 class="fecha">Reporte de art&iacute;culos en la dependencia <?echo $ladepenes?></td>  
     </tr>
   </table>	
   
            <table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
              <tr>
		 <td class="datos_cliente3">Lin.</th>  
                 <td class="datos_cliente3">C&oacute;digo.</th>
                 <td class="datos_cliente3">Insumo.</th>
                 <?if($dependencia==0){?>
                     <th class="datos_cliente3">Dependencia.</th> 
                 <?}?>
		 <td class="datos_cliente3">Laboratorio.</th> 
		 <td class="datos_cliente3">Cantidad.</th>                
              </tr>
			<?
			    $linea=1;   
                            $codigo=""; 
			    while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
				   $codigo="$controlarti[id_dependencia]-$controlarti[id_insumo]-$controlarti[id_tipo_insumo]";
				   
				
	           echo "
		<tr>
			<td class=\"cantidades\">[$linea]</td>
                        <td class=\"cantidades\">$codigo</td>
			<td class=\"cantidades\">$controlarti[insumo]</td>";
                        if($dependencia==0){
                          echo"<td class=\"cantidades\">$controlarti[dependencia]</td>";
                        }
			echo"<td class=\"cantidades\">$controlarti[laboratorio]</td>
			<td class=\"cantidades\">$controlarti[cantidad]</td>
		</tr>
		";
		$linea++;
		$codigo="";
	}?>  
  </table>			  
   
<?}?>
