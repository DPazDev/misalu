<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$idpedidos=$_POST['elidpedido'];
$arreglocantidades=$_POST['cantidades'];
$arregloidedes=$_POST['idedes'];
$arrcan = explode(',',$arreglocantidades); 
$arrid = explode(',',$arregloidedes); 
$cant=1;
$ides=1;
$p=0;
$buscararestar=("select tbl_ordenes_donativos.id_dependencia,tbl_ordenes_donativos.no_orden_donativo from tbl_ordenes_donativos where tbl_ordenes_donativos.id_orden_donativo=$idpedidos;");
$repbuscaresta=ejecutar($buscararestar);
$databusresta=assoc_a($repbuscaresta);
$depenrestar=$databusresta[id_dependencia];
$numdonativo=$databusresta[no_orden_donativo];
foreach ( $arrcan as $arrcan ) 
{ 
 if($p!=0){
   if($arrcan>=0){	
   $cajac[$cant]=$arrcan;
   $cant++;
     
  }
}
$p++; 
}
$f=0;
foreach ( $arrid as $arrid ) 
{ 
 if($f!=0){
   if($arrid>0){	
   $cajaid[$ides]=$arrid;
   $ides++;
  }
 }
$f++;
}
$cuantos=count($cajac);
/*Primero descotemos de la depencia entrate todos los insumos del pedido
  Buscamos cada insumo de la tabla tbl_insumos_almacen estando pendiente 
  del insumo y de la dependencia a la cual se le resta 
*/
$sedespacho=0;

for($i=1;$i<=$cuantos;$i++){
	$lacaactual=$cajac[$i];
	$elidactual=$cajaid[$i];
	if(($lacaactual>=0)){
		$buscarenidinorddona=("select tbl_insumos_ordenes_donativos.cantidad,tbl_insumos_ordenes_donativos.costo from 
                                       tbl_insumos_ordenes_donativos where 
                                       tbl_insumos_ordenes_donativos.id_insumo=$elidactual and 
                                       tbl_insumos_ordenes_donativos.id_orden_donativo=$idpedidos;");
                $repbuscaenidordona=ejecutar($buscarenidinorddona);
                $dataidorddona=assoc_a($repbuscaenidordona);
                $cantidapedi=$dataidorddona[cantidad];
                $montopedi=$dataidorddona[costo];
                   if($cantidapedi<>$lacaactual){
                     $montounitario=($montopedi/$cantidapedi);
                     $nuevomonto=($lacaactual*$montounitario); 
                     $montofinalpedido=number_format($nuevomonto,2,'.','');
                     $actualizodenuevo=("update tbl_insumos_ordenes_donativos set cantidad=$lacaactual,costo=$montofinalpedido where
                                         tbl_insumos_ordenes_donativos.id_insumo=$elidactual and 
                                         tbl_insumos_ordenes_donativos.id_orden_donativo=$idpedidos;");
                     $repactualizdenuevo=ejecutar($actualizodenuevo);
                   }
		}
       if($lacaactual>0){
        $actualizodepen=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where 
                          tbl_insumos_almacen.id_insumo=$elidactual and
                          tbl_insumos_almacen.id_dependencia=$depenrestar;");      
        $repactualdepen=ejecutar($actualizodepen);
        $datarepactdepen=assoc_a($repactualdepen); 
        $loqhayendepn=$datarepactdepen[cantidad]; 
        $loqhabra=$loqhayendepn-$lacaactual;
        $restafina=("update tbl_insumos_almacen set cantidad=$loqhabra where 
                     tbl_insumos_almacen.id_insumo=$elidactual and
                     tbl_insumos_almacen.id_dependencia=$depenrestar;"); 
        $represta=ejecutar($restafina);
        }
       }
       //cambiamos el estado de la orden del donativo a procesado(2)
       $actulizdona=("update tbl_ordenes_donativos set estatus=2 where id_orden_donativo=$idpedidos;"); 
       $repaactulizdona=ejecutar($actulizdona);
       //registro en el log
        $mensaje="El usuario $elus ha procesado el donativo con id_orden_donativo=$idpedidos y cambio el estatus a procesado"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog);  
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
       <br> 
         <td colspan=4 class="titulo_seccion">El donativo No. <? echo $numdonativo?> se proceso exitosamente!!!</td>    
         <td class="titulo_seccion"><label title="Imprimir acta" class="boton" style="cursor:pointer" onclick="Impactadon(<?echo $idpedidos?>)" >Imprimir</label></td>  
         <td class="titulo_seccion"><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
     </tr>
</table>
