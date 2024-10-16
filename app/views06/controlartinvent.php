<?
include ("../../lib/jfunciones.php");
sesion();
$dependencia=$_POST['elprovee'];
if($dependencia==0){
    $queridepen="tbl_insumos_almacen.id_dependencia=tbl_dependencias.id_dependencia and";
}else{
     $queridepen="tbl_insumos_almacen.id_dependencia=$dependencia and";
    }
$nomdepen=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$dependencia");
$repnomdepen=ejecutar($nomdepen);
$datnomdepen=assoc_a($repnomdepen);
$ladepenes=$datnomdepen['dependencia'];
$idordenentregaTP=0;
$idordenpedidoTP=0;
list($anio,$mes,$dia)=explode("-",$fechainicial);
list($anio1,$mes1,$dia1)=explode("-",$fechafinal);
$arregloarticulos=$_POST['arreglo'];
$tok = explode(',',$arregloarticulos); 
$son=1;
$_SESSION['matriz']=array();
$_SESSION['matrizt']=array();
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
	   <td colspan=4 class="titulo_seccion">Reporte de art&iacute;culos en la dependencia <?echo $ladepenes?></td>  
     </tr>
   </table>	
   
   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
		 <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">C&oacute;digo.</th>
                 <th class="tdtitulos">Insumo.</th>
                   <?if($dependencia==0){?>
                     <th class="tdtitulos">Dependencia.</th> 
                 <?}?>
		 <th class="tdtitulos">Laboratorio.</th> 
		 <th class="tdtitulos">Cantidad.</th>                
              </tr>
			<?
			    $linea=1;   
                            $codigo=""; 
			    while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
				   $codigo="$controlarti[id_dependencia]-$controlarti[id_insumo]-$controlarti[id_tipo_insumo]";
				   
				
	           echo "
		<tr>
			<td class=\"tdcampos\">[$linea]-</td>
                        <td class=\"tdcampos\">$codigo</td>
			<td class=\"tdcampos\">$controlarti[insumo]</td>";
                        if($dependencia==0){
                          echo"<td class=\"tdcampos\">$controlarti[dependencia]</td>";
                        }
			echo" <td class=\"tdcampos\">$controlarti[laboratorio]</td>
			<td class=\"tdcampos\">$controlarti[cantidad]</td>
		</tr>
		";
		$linea++;
		$codigo="";
	}?>  
  <tr><td class="tdcampos" colspan=8><hr></td></tr>	
  <tr>
     <? $url="'views06/reportimpresioninven.php?dependencia=$dependencia&articulos=$arregloarticulos'"; ?>  
       <td>
	     <td colspan=4 class="tdcampos"><a href="javascript: imprimir(<?echo $url?>);" class="boton">Imprimir</a>
	   </td>   
     
  </tr>  
  </table>			  
   
<?}?>
