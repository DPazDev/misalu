<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$comenta=$_POST['coment'];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elid=$_SESSION['id_usuario_'.empresa];
$proveedorid=$_SESSION['elprovedor'];
$espedidodepen=$_SESSION['pedidodepen'];
$depensaliente=$_SESSION['depesaliente'];
$versiexistpedido=$_SESSION['existepedido'];
$re=$_SESSION['existepedido'];
$m2=0;
$matrixguar=$_SESSION['matriz'];
if (($espedidodepen==0)and ($re<=0)){
        $buscarprovee=("select clinicas_proveedores.nombre from clinicas_proveedores,proveedores 
                          where proveedores.id_proveedor=$proveedorid and 
                           clinicas_proveedores.id_clinica_proveedor= proveedores.id_clinica_proveedor;");
$repuestabusprovee=ejecutar($buscarprovee);
$databusprovee=assoc_a($repuestabusprovee);
$nomprovee=$databusprovee['nombre'];
/* Lo primero que vamos hacer es lo siguiente:
     1. En la tabla tbl_ordenes_pedidos guardaremos la data principal del pedido
     2. Dependiendo del proveedor se le asignara un numero consecutivo
*/
//busquemos cual es el numero mayor de la orden de un proveedor en especifico//
if($re<=0){
$buscarmayor=("select max(no_orden_pedido) from tbl_ordenes_pedidos where id_proveedor='$proveedorid';");
$repbuscarmayor=ejecutar($buscarmayor);
$datamayor=assoc_a($repbuscarmayor);
$elmayor=$datamayor['max'];
  if($elmayor<=0){
	   $elmayor=1;
	}else{
		$elmayor=$elmayor+1;
		}
//fin de la busquedad del mayor//		
//guardar los datos principales en la tabla tbl_ordenes_pedidos//
$insertarpedido=("insert into tbl_ordenes_pedidos(id_dependencia,fecha_pedido,fecha_hora_creado,id_admin,comentarios,hora_pedido,no_orden_pedido, id_proveedor,estatus,id_dependencia_saliente) 
values(0,'$fecha','$fecha',$elid,upper('$comenta'),'$hora',$elmayor,$proveedorid,1,0);");
$repinsetarpedido=ejecutar($insertarpedido);
//fin de insertar el valor en la tabla tbl_ordenes_pedidos//
//Buscar el elemento recien guarados en la tabla tbl_ordenes_pedidos//
$registroguardado=("select id_orden_pedido from tbl_ordenes_pedidos where no_orden_pedido='$elmayor' and
                    id_proveedor=$proveedorid;");
$repuestaregistroguardado=ejecutar($registroguardado);
$datareguardado=assoc_a($repuestaregistroguardado);
$idpedido=$datareguardado['id_orden_pedido'];
//Fin de la busquedad//
}
//ver si ya existe el pedido//

if($re>0){
   $buscarpedidoexist=("select * from tbl_insumos_ordenes_pedidos where 
                        tbl_insumos_ordenes_pedidos.id_orden_pedido=$versiexistpedido limit 1;");
   $repbuscarpedidoexist=ejecutar($buscarpedidoexist);
   $datapedexistente=assoc_a($repbuscarpedidoexist);
   $idpedido=$datapedexistente['id_orden_pedido'];
   $m2=1;   
}
//fin de la busquedad/
//Comezamos a guardar todos los insumos del pedido en la tabla tbl_insumos_ordenes_pedidos//
$cuantomatriz=count($matrixguar);
 for($i=0;$i<=$cuantomatriz;$i++){
       $cant=$matrixguar[$i][2];
       $idart=$matrixguar[$i][5];  
  if(!empty($cant)){
    $buscolosarti=("select * from tbl_insumos_ordenes_pedidos where 
                   tbl_insumos_ordenes_pedidos.id_orden_pedido=$idpedido and 
                   tbl_insumos_ordenes_pedidos.id_insumo=$idart;"); 
    $repbuscolosarti=ejecutar($buscolosarti);
    $cuantossonlosarti=num_filas($repbuscolosarti);
     $guardaproducto=("insert into tbl_insumos_ordenes_pedidos(id_orden_pedido,id_insumo,cantidad) values ($idpedido,$idart,'$cant') ;");  
     $repguardaproducto=ejecutar($guardaproducto); 
    
 }
}						  
//fin de los datos guardados en la tabla tbl_insumos_ordenes_pedidos//
$mesaje='Al proveedor';
//actualizamos el log 
	 $mensaje="El usuario $elus ha creado la orden proveedor No. $idpedido"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog); 
	}else{
        if($re<=0){
		  $buscarprovee=("select tbl_dependencias.dependencia from tbl_dependencias where 
                                  id_dependencia=$proveedorid;");
          $repuestabusprovee=ejecutar($buscarprovee);
          $databusprovee=assoc_a($repuestabusprovee);
		  $nomprovee=$databusprovee['dependencia'];
		  $matrixguar=$_SESSION['matriz'];
/* Lo primero que vamos hacer es lo siguiente:
     1. En la tabla tbl_ordenes_pedidos guardaremos la data principal del pedido
     2. Dependiendo del proveedor se le asignara un numero consecutivo
*/
//busquemos cual es el numero mayor de la orden de un proveedor en especifico//
$buscarmayor=("select max(no_orden_pedido) from tbl_ordenes_pedidos where id_admin=$elid and id_dependencia=$proveedorid;");
//echo $buscarmayor;
$repbuscarmayor=ejecutar($buscarmayor);
$datamayor=assoc_a($repbuscarmayor);
$elmayor=$datamayor['max'];
  if($elmayor<=0){
	   $elmayor=1;
	}else{
		$elmayor=$elmayor+1;
		}
}
//fin de la busquedad del mayor//		
//guardar los datos principales en la tabla tbl_ordenes_pedidos//
if($re<=0){
$insertarpedido=("insert into tbl_ordenes_pedidos(id_dependencia,fecha_pedido,fecha_hora_creado,id_admin,comentarios,hora_pedido,no_orden_pedido, id_proveedor,estatus,id_dependencia_saliente) values($proveedorid,'$fecha','$fecha',$elid,upper('$comenta'),'$hora',$elmayor,0,1,$depensaliente);");
$repinsetarpedido=ejecutar($insertarpedido);
//fin de insertar el valor en la tabla tbl_ordenes_pedidos//
//Buscar el elemento recien guarados en la tabla tbl_ordenes_pedidos//
$registroguardado=("select id_orden_pedido from tbl_ordenes_pedidos where 
id_dependencia_saliente=$depensaliente and no_orden_pedido='$elmayor' and
id_admin=$elid and fecha_pedido='$fecha' and hora_pedido='$hora';");
//echo "<br> $registroguardado";
$repuestaregistroguardado=ejecutar($registroguardado);
$datareguardado=assoc_a($repuestaregistroguardado);
$idpedido=$datareguardado['id_orden_pedido'];
//Fin de la busquedad//
}else{
	   $buscaidexistente=("select tbl_ordenes_pedidos.id_orden_pedido from tbl_ordenes_pedidos where 
                               tbl_ordenes_pedidos.id_orden_pedido=$re ;");
	  $repbuscaidexistente=ejecutar($buscaidexistente);   
	  $datapedidoexistente=assoc_a($repbuscaidexistente);
	  $idpedido=$datapedidoexistente['id_orden_pedido'];
	  $m2=1;  
	}
//Comezamos a guardar todos los insumos del pedido en la tabla tbl_insumos_ordenes_pedidos//
if($m2==0){
$cuantomatriz=count($matrixguar);
 for($i=0;$i<=$cuantomatriz;$i++){
      $cant=$matrixguar[$i][2];
      $idart=$matrixguar[$i][5];  
	  if(!empty($cant)){
	  $buscolosarti=("select * from tbl_insumos_ordenes_pedidos where 
                   tbl_insumos_ordenes_pedidos.id_orden_pedido=$idpedido and 
                   tbl_insumos_ordenes_pedidos.id_insumo=$idart;"); 
                   $repbuscolosarti=ejecutar($buscolosarti);
                   $cuantossonlosarti=num_filas($repbuscolosarti);
            
                if($cuantossonlosarti==0){  
		  $guardaproducto=("insert into tbl_insumos_ordenes_pedidos(id_orden_pedido,id_insumo,cantidad) 
                                     values ($idpedido,$idart,'$cant') ;");  
		  $repguardaproducto=ejecutar($guardaproducto); 
		}
            }
}	
}else{


   $cuantomaMO=count($matrixguar);
   for($i=0;$i<=$cuantomaMO;$i++){
      $cant=$matrixguar[$i][2];
      $idart=$matrixguar[$i][5];
     if(!empty($cant)){
        $buscosiexiste=("select tbl_insumos_ordenes_pedidos.id_insumo,
                        tbl_insumos_ordenes_pedidos.cantidad
                          from tbl_insumos_ordenes_pedidos where 
                         tbl_insumos_ordenes_pedidos.id_orden_pedido=$idpedido and
                         tbl_insumos_ordenes_pedidos.id_insumo=$idart;");
        $repbuscoexiste=ejecutar($buscosiexiste);
        $cuantosexiste=num_filas($repbuscoexiste);
        $dataexiste=assoc_a($repbuscoexiste);
        $cantidadactua=$dataexiste['cantidad'];
       if($cuantosexiste==0){
           $nuevoenpedido=("insert into tbl_insumos_ordenes_pedidos(id_orden_pedido,id_insumo,cantidad) 
                            values($idpedido,$idart,'$cant');");
           $repnuevoenpedido=ejecutar($nuevoenpedido);
          }else{
              $actualizoloactual=("update tbl_insumos_ordenes_pedidos set cantidad='$cant' where 
                                    id_insumo=$idart and id_orden_pedido=$idpedido;");
              $repactulizoloactual=ejecutar($actualizoloactual);
          }
    }
  }

}					  
//fin de los datos guardados en la tabla tbl_insumos_ordenes_pedidos//
	$mesaje='A la dependencia';	
		//actualizamos el log 
	if($m2==0){	
	 $mensaje="El usuario $elus ha creado la orden dependencia No. $idpedido"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog); 
	}else{
		 $mensaje="El usuario $elus actualizo la orden dependencia No. $idpedido"; 
	    $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog); 
		}  
		}


echo"<br><br>";
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion">
		<?if ($m2!=1){?>
		El pedido No. <?echo $elmayor;?>  <?echo "$idpedido<- $mesaje  $nomprovee";?> ha sido registrado exitosamente</td>
		<?}else{?>
		El pedido ha sido modificado exitosamente</td>
		<?}?>
	  </tr>
	  <tr>
	      <br>  
	     <td colspan=7 class="titulo_seccion">
		 <?php
			$url="'views05/reportpediprov.php?idordepe=$idpedido'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
		</td>  
	  </tr>  
</table>
