<?
include ("../../lib/jfunciones.php");
sesion();
$depenentrante=$_POST['elentrante'];
$depensaliente=$_POST['elsaliente'];
$fechainicial=$_POST['fecha1'];
$fechafinal=$_POST['fecha2'];
$nomdentra=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$depenentrante;");
$repnomentr=ejecutar($nomdentra);
$dataentra=assoc_a($repnomentr);
$nomdeentra=$dataentra['dependencia'];
$nomdsalien=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$depensaliente;");
$repnomsalie=ejecutar($nomdsalien);
$datasalien=assoc_a($repnomsalie);
$nomdesalien=$datasalien['dependencia'];
if ($depenentrante==0){
	$para="and tbl_ordenes_pedidos.id_dependencia_saliente>0";
	}
	
	else
	{
		$para="and tbl_ordenes_pedidos.id_dependencia_saliente=$depenentrante";
	}
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
if ($depenentrante==5)
{
	$buscararticulos=("select tbl_insumos.id_insumo, count(tbl_insumos_ordenes_entregas.id_insumo),tbl_ordenes_entregas.id_orden_entrega
 from
tbl_insumos,tbl_insumos_ordenes_entregas,tbl_ordenes_entregas,tbl_dependencias where
($otracaden) and 
tbl_insumos.id_insumo=tbl_insumos_ordenes_entregas.id_insumo and
tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and
tbl_ordenes_entregas.id_dependencia=tbl_dependencias.id_dependencia and tbl_dependencias.id_dependencia=$depensaliente and tbl_ordenes_entregas.id_orden_pedido=0 and tbl_ordenes_entregas.fecha_emision between '$fechainicial' and '$fechafinal'
group by tbl_insumos.id_insumo,tbl_ordenes_entregas.id_orden_entrega order by tbl_ordenes_entregas.id_orden_entrega;");
	}
	else
	{
$buscararticulos=("select tbl_insumos.id_insumo, count(tbl_insumos_ordenes_entregas.id_insumo),tbl_ordenes_entregas.id_orden_entrega,
tbl_ordenes_entregas.id_orden_pedido,tbl_ordenes_pedidos.estatus from
tbl_insumos,tbl_insumos_ordenes_entregas,tbl_ordenes_entregas,tbl_ordenes_pedidos where
($otracaden) and 
tbl_insumos.id_insumo=tbl_insumos_ordenes_entregas.id_insumo and
tbl_insumos_ordenes_entregas.id_orden_entrega=tbl_ordenes_entregas.id_orden_entrega and
tbl_ordenes_entregas.id_dependencia=tbl_ordenes_pedidos.id_dependencia_saliente and 
tbl_ordenes_entregas.id_orden_pedido=tbl_ordenes_pedidos.id_orden_pedido and
(tbl_ordenes_pedidos.estatus=2 or tbl_ordenes_pedidos.estatus=3 ) $para  and tbl_ordenes_pedidos.id_dependencia=$depensaliente and tbl_ordenes_entregas.fecha_emision between '$fechainicial' and '$fechafinal'
group by tbl_insumos.id_insumo,tbl_ordenes_entregas.id_orden_entrega,tbl_ordenes_entregas.id_orden_pedido,tbl_ordenes_pedidos.estatus order by tbl_ordenes_entregas.id_orden_pedido;");
}
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
         <td colspan=4 class="titulo_seccion">Art&iacute;culos despachados por la depencia <? echo $nomdesalien?> y recibido por la dependencia <?echo $nomdeentra?> desde el<?echo " $dia-$mes-$anio al $dia1-$mes1-$anio1"?> </td>  
     </tr>
</table>	
   <table class="tabla_citas"  cellpadding=0 cellspacing=0>
              <tr>
			     <th class="tdtitulos">Lin.</th>  
                 <th class="tdtitulos">Desp. por.</th>
                 <th class="tdtitulos">Recib. por.</th>
				 <th class="tdtitulos">Num. Ord Entr.</th> 
				 <th class="tdtitulos">Fecha. Ord Entr.</th>  
                 <th class="tdtitulos">Art.</th>
				 <th class="tdtitulos">Estatus.</th> 
                 <th class="tdtitulos">Lab.</th>
                 <th class="tdtitulos">Cant.</th>
                 <th class="tdtitulos">Mot. Uni.</th>
                 <th class="tdtitulos">Mot. Total.</th>
              </tr>
			<?
			    $linea=1;   
			    while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
				   $idarticulo=$controlarti['id_insumo'];
				   $idordenentrega=$controlarti['id_orden_entrega'];
				   $idordenpedido=$controlarti['id_orden_pedido'];   
				   $elestado=$controlarti['estatus'];      
				    if($elestado==2)
					  $estadoes='Despachado';
					if($elestado==3)
					  $estadoes='Recibido';
				 $losdatosdelarti=("select tbl_insumos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio,tbl_insumos_ordenes_entregas.cantidad
												   from tbl_insumos_ordenes_entregas,tbl_insumos,tbl_laboratorios where
                                                   tbl_insumos_ordenes_entregas.id_insumo=tbl_insumos.id_insumo and
                                                   tbl_insumos_ordenes_entregas.id_insumo=$idarticulo and
                                                   tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
                                                   tbl_insumos_ordenes_entregas.id_orden_entrega=$idordenentrega ;");  
					  $repaartientre=ejecutar($losdatosdelarti);		
					  $dataartientre=assoc_a($repaartientre);  
					  $nombrearti=$dataartientre['insumo'];
					  $nombrelab=$dataartientre['laboratorio'];
					  $lascantidad=$dataartientre['cantidad'];
                      $elinsumo1=$dataartientre['id_insumo'];
                $buscprecios=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$elinsumo1 and tbl_insumos_almacen.id_dependencia=89;");
                $repbusprecios=ejecutar($buscprecios);
                if(num_filas($repbusprecios)==0){
                $buscprecios=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$elinsumo1 and tbl_insumos_almacen.id_dependencia=2;");
                $repbusprecios=ejecutar($buscprecios);    
                    }
                       if(num_filas($repbusprecios)==0){
                $buscprecios=("select tbl_insumos_almacen.monto_unidad_publico from tbl_insumos_almacen where tbl_insumos_almacen.id_insumo=$elinsumo1 and tbl_insumos_almacen.id_dependencia=64;");
                $repbusprecios=ejecutar($buscprecios);    
                    }
                    
                $datprecios=assoc_a($repbusprecios);
                $elprecioes=$datprecios['monto_unidad_publico'];
                $totalmonto=$lascantidad*$elprecioes;
						if ($paradependencia==5)
{
	 if ($idordenentrega<>$idordenentregaTP){
	$buscoqpidio=("select admin.nombres,admin.apellidos,tbl_dependencias.dependencia,tbl_ordenes_entregas.* from admin,tbl_ordenes_entregas,tbl_dependencias where
                                                tbl_ordenes_entregas.id_admin=admin.id_admin and 
                                                tbl_ordenes_entregas.id_orden_entrega=$idordenentrega and tbl_ordenes_entregas.id_dependencia=tbl_dependencias.id_dependencia and tbl_dependencias.id_dependencia=$dedependencia");
					   $repbuscqpidio=ejecutar($buscoqpidio);
					   $datqpidio=assoc_a($repbuscqpidio);
	$elqueacepto="$datqpidio[nombres] $datqpidio[apellidos]"; 
	$noordenentre= $datqpidio[no_orden_entrega]; 
	 $fechaentre= $datqpidio[fecha_emision]; 
	  $idordenentregaTP=$idordenentrega; 
	}
	else
	{
		$idordenentregaTP=$idordenentrega;
				 
					$elqueacepto="";  
					$noordenentre="";
					$fechaentre="";  
		
		}
	}
	
	else
	{  
				   if(($idordenentrega<>$idordenentregaTP) &&($idordenpedido<>$idordenpedidoTP)){
				      
		
					
					$buscoqpidio=("select admin.nombres,admin.apellidos,tbl_dependencias.dependencia from admin,tbl_ordenes_pedidos,tbl_dependencias where
                                                tbl_ordenes_pedidos.id_admin=admin.id_admin and 
                                                tbl_ordenes_pedidos.id_orden_pedido=$idordenpedido and tbl_ordenes_pedidos.id_dependencia_saliente=tbl_dependencias.id_dependencia");
					   $repbuscqpidio=ejecutar($buscoqpidio);
					   $datqpidio=assoc_a($repbuscqpidio);
					   $elquepidio="$datqpidio[nombres] $datqpidio[apellidos] ($datqpidio[dependencia])";	
					  $buscoqacepto=("select admin.nombres,admin.apellidos,tbl_ordenes_entregas.no_orden_entrega,
                                                    tbl_ordenes_entregas.fecha_emision
                                                   from admin,tbl_ordenes_entregas where
                                                tbl_ordenes_entregas.id_admin=admin.id_admin and 
                                                tbl_ordenes_entregas.id_orden_entrega=$idordenentrega;");  
					  $repqacepto=ejecutar($buscoqacepto);							
					  $datqacepto=assoc_a($repqacepto);  
					  $elqueacepto="$datqacepto[nombres] $datqacepto[apellidos]"; 
					  $noordenentre= $datqacepto[no_orden_entrega]; 
					  $fechaentre= $datqacepto[fecha_emision]; 
					 
					
					
					  $idordenentregaTP=$idordenentrega;
				     $idordenpedidoTP=$idordenpedido;  
				  }else{
					 $idordenentregaTP=$idordenentrega;
				     $idordenpedidoTP=$idordenpedido;
					 
					 $elquepidio=""; 
					 $elqueacepto="";  
					 //$noordenentre=""; 
					 $fechaentre="";  
					}
					}
	           echo "
		<tr>
			<td class=\"tdcampos\">$linea</td>
             <td class=\"tdcampos\">$elqueacepto</td>
			<td class=\"tdcampos\">$elquepidio</td>
			<td class=\"tdcampos\">$noordenentre</td>
			<td class=\"tdcampos\">$fechaentre</td>
            <td class=\"tdcampos\">$nombrearti</td>
             <td class=\"tdcampos\">$estadoes</td>  
             <td class=\"tdcampos\">-$nombrelab-</td>
			<td class=\"tdcampos\">$lascantidad</td>
          <td class=\"tdcampos\">$elprecioes</td>
          <td class=\"tdcampos\">$totalmonto</td>
		</tr>
		";
		$linea++;
		$nombrearti="";
		$nombrelab="";
		$lascantidad="";    
	}?>  
  <tr><td class="tdcampos" colspan=8><hr></td></tr>	
  <tr>
     <? $url="'views06/reportcaimpresion.php?fechaini=$fechainicial&
fechafin=$fechafinal&elentrante=$depenentrante&arreglo=$arregloarticulos&elsaliente=$depensaliente'"; ?>  
       <td>
	     <td colspan=4 class="tdcampos"><a href="javascript: imprimir(<?echo $url?>);" class="boton">Imprimir</a>
	   </td>   
        <td  title="Generear reporte en formato excel">
		  <a href="#" onClick="ReporteCArtiExcel(); return false;">
		 <img border="0" src="../public/images/excel.jpg"></a>   
	  </td>
	   <td  title="regresar al modulo control de art&iacute;culos"><label class="boton" style="cursor:pointer" onclick="control_articulos(); return false;" >Regresar</label></td> 
  </tr>  
  </table>			  
  <input type="hidden" id="fechaini" value='<? echo $fechainicial?>'>
  <input type="hidden" id="fechafin" value='<? echo $fechafinal?>'>
  <input type="hidden" id='despachopor' value='<? echo $depensaliente?>'>  <input type="hidden" id='recibidopor'    value='<? echo $depenentrante?>'> 
  <input type="hidden" id='elarreglo' value='<? echo $arregloarticulos?>'>   
<?}?>
