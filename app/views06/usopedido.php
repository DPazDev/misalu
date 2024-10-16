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
$buscararticulos=("select sum(cast(tbl_insumos_ordenes_pedidos.cantidad as integer)), tbl_insumos_ordenes_pedidos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio 
from 
tbl_insumos,tbl_ordenes_pedidos,tbl_laboratorios,tbl_insumos_ordenes_pedidos 
where 
($otracaden) and 
tbl_insumos.id_insumo=tbl_insumos_ordenes_pedidos.id_insumo and 
tbl_insumos_ordenes_pedidos.id_orden_pedido = tbl_ordenes_pedidos.id_orden_pedido and
(tbl_ordenes_pedidos.estatus=2 or tbl_ordenes_pedidos.estatus=3 ) $para and 
tbl_ordenes_pedidos.id_dependencia=$depensaliente and 
tbl_insumos.id_laboratorio = tbl_laboratorios.id_laboratorio and 
tbl_ordenes_pedidos.fecha_despachado between '$fechainicial' and '$fechafinal' 
group by  
tbl_insumos_ordenes_pedidos.id_insumo,tbl_insumos.insumo,tbl_laboratorios.laboratorio 
 order by tbl_insumos.insumo;");
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
                <th class="tdtitulos">Art.</th>
				 <th class="tdtitulos">Lab.</th>
                 <th class="tdtitulos">Cant Despachada.</th>
                 <th class="tdtitulos">Cant Usada.</th>
              </tr>
			<?
			    $linea=1;   
			    while($controlarti=asignar_a($repbuscararti,NULL,PGSQL_ASSOC)){
				   $idarticulo=$controlarti['id_insumo'];
				   $nombrearti=$controlarti['insumo'];
				   $nombrelab=$controlarti['laboratorio'];
				   $lascantidad=$controlarti['sum']; 
				 $losdatosdelarti=("select sum(cast(gastos_t_b.unidades as integer)) from 
gastos_t_b,procesos,tbl_admin_dependencias 
where 
gastos_t_b.id_insumo=$idarticulo and 
gastos_t_b.fecha_creado between '$fechainicial' and '$fechafinal' and
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.id_admin=tbl_admin_dependencias.id_admin and
tbl_admin_dependencias.id_dependencia=$depenentrante;");  
					  $repaartientre=ejecutar($losdatosdelarti);		
					  $dataartientre=assoc_a($repaartientre);
					  $seusocant=$dataartientre['sum']; 
	           echo "
		<tr>
			<td class=\"tdcampos\">$linea</td>
            <td class=\"tdcampos\">$nombrearti</td>
            <td class=\"tdcampos\">-$nombrelab-</td>
			<td class=\"tdcampos\">$lascantidad</td>
          <td class=\"tdcampos\">$seusocant</td>
		</tr>
		";
		$linea++;
		$nombrearti="";
		$nombrelab="";
		$lascantidad="";    
	}?>  
  
  </table>			  
   
<?}?>
