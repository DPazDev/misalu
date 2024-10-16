$sedespacho=1;
		$nuevacantidad=$cantidadentalba-$lacaactual;
		$actualizartabla=("update tbl_insumos_almacen set cantidad=$nuevacantidad where tbl_insumos_almacen.id_insumo=$elidactual and tbl_insumos_almacen.id_dependencia=$deperestar");
		$repuestaactabla=ejecutar($actualizartabla);
		}
}
if($sedespacho==1){
	 $actualizaorpedido=("update tbl_ordenes_pedidos set estatus=2,fecha_despachado='$fecha' where tbl_ordenes_pedidos.id_orden_pedido='$idpedidos';");
	 $repactulizarorpedido=ejecutar($actualizaorpedido); 
	 //Buscar el numero de la orden de entrega	
     $elpedidousuario=("select tbl_ordenes_entregas.no_orden_entrega from tbl_ordenes_entregas where tbl_ordenes_entregas.id_dependencia=$depesuma and tbl_ordenes_entregas.id_admin=$elid ORDER BY tbl_ordenes_entregas.no_orden_entrega DESC LIMIT 1;"); 
	$repelpedidousuario=ejecutar($elpedidousuario);
	$cuantospedidousu=num_filas($repelpedidousuario);	
	$elnumerodeentrega=assoc_a($repelpedidousuario);
	$elnumeroactuales=$elnumerodeentrega['no_orden_entrega'];	
	$cuantasentregas=num_filas($repelpedidousuario);
	if($cuantasentregas<0){
		 $noordenentrega=2;
		}else{			 
			 $numeroactualdentrega= $elnumeroactuales+1;
			 $noordenentrega=$numeroactualdentrega; 
			}
	 //crear la orden		
	  $crearorden=("insert into tbl_ordenes_entregas(id_dependencia,fecha_emision,fecha_hora_creado,id_admin,no_orden_entrega,comentario,id_orden_pedido) 
values ($depesuma,'$fecha','$fecha',$elid,$noordenentrega,'$despcomentario',$eliddelpedio);");
     $repuestacrearorden=ejecutar($crearorden);
	//ver que orden fue
	 $buscarlaordecreada=("select tbl_ordenes_entregas.id_orden_entrega from tbl_ordenes_entregas where tbl_ordenes_entregas.id_dependencia=$depesuma and tbl_ordenes_entregas.id_admin=$elid and tbl_ordenes_entregas.no_orden_entrega=$noordenentrega;");
	$repbuscarlaorden=ejecutar($buscarlaordecreada); 
	$databuscarorden=assoc_a($repbuscarlaorden);
	//Cargamos los articulos en la tabla tbl_insumos_ordenes_entregas
	         for($i=1;$i<=$cuantos;$i++){
	            $lacaactual=$cajac[$i];
	            $elidactual=$cajaid[$i];
	             if($lacaactual>0){
	               $cargatblinsordentrega=("insert into tbl_insumos_ordenes_entregas(id_orden_entrega,id_insumo,cantidad) values ($databuscarorden[id_orden_entrega],$elidactual,$lacaactual);");	
				  $repcargatblinsordentrega=ejecutar($cargatblinsordentrega);   
		         }
             }
			echo"
               <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                   <tr>
                      <td colspan=4 class=\"titulo_seccion\">El pedido ha sido despachado exitosamente</td>
                   </tr>
              </table> 
              "; 
	//fin de la carga de los articulos en la tabla   tbl_insumos_ordenes_entregas
	}else{
		   echo"
               <table class=\"tabla_cabecera3\"  cellpadding=0 cellspacing=0>
                   <tr>
                      <td colspan=4 class=\"titulo_seccion\">El pedido no ha sido despachado</td>
                      <td class=\"titulo_seccion\"><label title=\"Salir del Proceso\" class=\"boton\" style=\"cursor:pointer\" onclick=\"ira()\" >Salir</label></td>
                   </tr>
                    
              </table> 
              "; 
		}