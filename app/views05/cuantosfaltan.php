<? 
      include ("../../lib/jfunciones.php");
     sesion();
     $buscarlosqfalta=("select tbl_insumos.insumo,tbl_insumos.id_insumo from tbl_insumos where tbl_insumos.id_tipo_insumo=1");
	$repus=ejecutar($buscarlosqfalta); 
	$i=1;
	while($articulos=asignar_a($repus,NULL,PGSQL_ASSOC)){
		$nom=$articulos['insumo'];
		$idin=$articulos['id_insumo']; 
		  $busendepen=("select tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen where 
                                       tbl_insumos_almacen.id_insumo=$idin and tbl_insumos_almacen.id_dependencia=2");
		  $repsbusdepen=ejecutar($busendepen);							   
		   $cuantdepen=num_filas($repsbusdepen);  
		  if($cuantdepen==0){
			   echo "el producto $nom con id $idin no esta van ->$i<BR>";
			   $eliminarpro=("delete from tbl_insumos where id_insumo=$idin;");   
			   $repeliminarpro=ejecutar($eliminarpro);                         
			   $eliminodelpedido=("delete from tbl_insumos_ordenes_pedidos where id_insumo=$idin;"); 
			   $repelinodelpedido=ejecutar($eliminodelpedido);   
			   $elimardelaentrega=("delete from tbl_insumos_ordenes_entregas where id_insumo=$idin;");   
			   $reelimalaentrega=ejecutar($elimardelaentrega);   
			  $i++;	   
			}   
		
	}
     
?>