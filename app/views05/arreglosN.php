<?
sesion();
function primero($parametro){
	$buscarpedido=("select tbl_insumos_ordenes_pedidos.id_insumo,tbl_insumos_ordenes_pedidos.cantidad,
tbl_insumos.insumo,tbl_laboratorios.laboratorio from tbl_insumos_ordenes_pedidos,tbl_ordenes_pedidos,tbl_insumos,tbl_laboratorios where tbl_ordenes_pedidos.id_orden_pedido=tbl_insumos_ordenes_pedidos.id_orden_pedido and 
tbl_ordenes_pedidos.id_orden_pedido=$parametro and tbl_insumos_ordenes_pedidos.id_insumo=tbl_insumos.id_insumo and tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio order by tbl_insumos.insumo");
  $repbuscarpedido=ejecutar($buscarpedido);
  $posi=$_SESSION['pasopedido']; 
  $matrizprin=$_SESSION['matriz'];  
  while($losarticulos=asignar_a($repbuscarpedido,NULL,PGSQL_ASSOC)){
	    $matrizprin[$posi][0]=$losarticulos['insumo'];
        $matrizprin[$posi][1]=$losarticulos['laboratorio'];
        $matrizprin[$posi][2]=$losarticulos['cantidad'];
        $matrizprin[$posi][3]=0;
        $matrizprin[$posi][4]=0;
        $matrizprin[$posi][5]=$losarticulos['id_insumo'];
        $posi++;  
  }
 
 $_SESSION['pasopedido']=$posi;   
 $_SESSION['matriz']=$matrizprin;    
 return($_SESSION['matriz']); 
}
?>