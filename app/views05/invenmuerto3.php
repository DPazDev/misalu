<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$comenta=$_POST['coment'];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
$depensaliente=$_SESSION['elprovedor'];
$nomdepen=("select tbl_dependencias.dependencia from tbl_dependencias where tbl_dependencias.id_dependencia=$depensaliente;");
$repnomdepen=ejecutar($nomdepen);
$datanomdepen=assoc_a($repnomdepen);
$elnomdepen=$datanomdepen['dependencia'];
$matrixguar=$_SESSION['matriz'];
$cuantomatriz=count($matrixguar);
$depenmuerto=5;
$idordepedido=0;
$seguardo=0;
//buscamos en la tabla tbl_ordenes_entregas cuantos despachos se han hecho a la dependencia de inventario muerto
//en este caso decimos que id_orden_pedido=0 para decir que es el inventario muerto
   $buscarmayorentabla=("select tbl_ordenes_entregas.no_orden_entrega from tbl_ordenes_entregas where
                                          tbl_ordenes_entregas.id_admin=$elid and tbl_ordenes_entregas.id_orden_pedido=0
                                         and tbl_ordenes_entregas.id_dependencia=$depensaliente
										 order by tbl_ordenes_entregas.no_orden_entrega desc limit 1");
	$repbuscamayor=ejecutar($buscarmayorentabla);
	$cuantosmayor=num_filas($repbuscamayor);
	if($cuantosmayor==0){
		 $numdespachomuerto=0;
		}else{
			   $datacuantos=assoc_a($repbuscamayor);
			   $numordeactual=$datacuantos[no_orden_entrega];
			   $numdespachomuerto=$numordeactual+1;
			}
	//guardo en la tabla tbl_ordenes_entregas la orde de entrega para inventario muerto
    $guardoorden=("insert into tbl_ordenes_entregas(id_dependencia,fecha_emision,fecha_hora_creado,id_admin,	hora_entrega,no_orden_entrega,comentario,id_orden_pedido) values($depensaliente,'$fecha','$fecha',$elid,'$hora',$numdespachomuerto,'$comenta',$idordepedido);");		
	$repguardoorden=ejecutar($guardoorden);
	$seguardo++;
  //busco lo orden de entrega recien cargada a la tabla
  $buscaordencargada=("select tbl_ordenes_entregas.id_orden_entrega from tbl_ordenes_entregas where
                                        tbl_ordenes_entregas.id_dependencia=$depensaliente and 
										tbl_ordenes_entregas.id_admin=$elid and tbl_ordenes_entregas.id_orden_pedido=0
                                        and tbl_ordenes_entregas.no_orden_entrega=$numdespachomuerto");	
   $repbuscarodencargada=ejecutar($buscaordencargada);										
   $databuscordencargada=assoc_a($repbuscarodencargada);   
   $laidordenentrega=$databuscordencargada[id_orden_entrega];   
 for($i=0;$i<=$cuantomatriz;$i++){
       $cant=$matrixguar[$i][2];
       $idart=$matrixguar[$i][5]; 
	if(($cant>0) &&($idart>0)){   
	   $guardoeninsumosorden=("insert into tbl_insumos_ordenes_entregas(id_orden_entrega,id_insumo,cantidad) 
                                       values($laidordenentrega,$idart,'$cant');");
	   $repguardoeninsumo=ejecutar($guardoeninsumosorden);							   
	   $buscoactual=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where 
                                 tbl_insumos_almacen.id_insumo=$idart and tbl_insumos_almacen.id_dependencia=$depensaliente;");
	   $repbuscoactual=ejecutar($buscoactual);
	   $datadactual=assoc_a($repbuscoactual);   
	   $lacantidaqhay=$datadactual['cantidad'];
	   $cantidadmenos=$lacantidaqhay-$cant;
	   $actualicodepen=("update tbl_insumos_almacen set cantidad=$cantidadmenos where 
                                 tbl_insumos_almacen.id_insumo=$idart and tbl_insumos_almacen.id_dependencia=$depensaliente;");   
	    $repactualicodepen=ejecutar($actualicodepen);							 
		$buscoendepenmuerta=("select tbl_insumos_almacen.cantidad from tbl_insumos_almacen where
                                                 tbl_insumos_almacen.id_dependencia=$depenmuerto and 
                                                 tbl_insumos_almacen.id_insumo=$idart;");
		$repbuscoendepenmuerta=ejecutar($buscoendepenmuerta);										 
		$cuantoshayenmuerta=num_filas($repbuscoendepenmuerta);
		if($cuantoshayenmuerta==0){
			 $guardoartienmuerta=("insert into tbl_insumos_almacen(id_insumo,cantidad,fecha_hora_creado,id_dependencia) values($idart,$cant,'$fecha',$depenmuerto)");
			$repguardoartimuerto=ejecutar($guardoartienmuerta); 
			}else{
				       $databuscoenmuerta=assoc_a($repbuscoendepenmuerta);
					   $lascantimuerta=$databuscoenmuerta[cantidad];  
					   $nuevascantimuerta=$lascantimuerta+$cant;
					    $actualizolosartienmuerta=("update tbl_insumos_almacen set cantidad=$nuevascantimuerta where
                                                                     tbl_insumos_almacen.id_insumo=$idart 
                                                                     and tbl_insumos_almacen.id_dependencia=$depenmuerto;");   
						$repactualizoinmuerta=ejecutar($actualizolosartienmuerta);											 
				     }
	  	}  
}	 
   $seguardo++;
   if ($seguardo>=2){
	    $mensajeG="El usuario $elus ha despachado al inventario muerto la orden de entrega No.$numdespachomuerto";
$actuallog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values(upper('$mensajeG'),$elid,'$fecha','$hora','$ip')");  
$repactuallog=ejecutar($actuallog);   
        $url="'views05/reportpediprov2.php?idordepe=$laidordenentrega'"; 
	    $mensaje="La orden de despacho realizada por la dependencia $elnomdepen asia el inventario muerto se ha procesado exitosamente";   
	    echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">$mensaje <a href=\"javascript: imprimir($url);\" class=\"boton\">Imprimir</a></td>
     </tr>
</table>";
	}else{
		$mensaje="La orden de despacho realizada por la dependencia $elnomdepen asia el inventario muerto no se cargo";   
	    echo"<table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class=\"titulo_seccion\">$mensaje</td>
     </tr>
</table>";
	}
   
?>