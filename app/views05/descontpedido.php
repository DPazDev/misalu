<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$idpedidos=$_POST['elidpedido'];
$arreglocantidades=$_POST['cantidades'];
$arregloidedes=$_POST['idedes'];
$despcomentario=$_POST['comedes'];
$depenrestapedido=("select tbl_ordenes_pedidos.id_dependencia,tbl_ordenes_pedidos.id_dependencia_saliente,
tbl_ordenes_pedidos.id_orden_pedido from tbl_ordenes_pedidos where tbl_ordenes_pedidos.id_orden_pedido='$idpedidos'");
$repuespedido=ejecutar($depenrestapedido);
$datadelpedido=assoc_a($repuespedido);
$deperestar=$datadelpedido['id_dependencia'];
$depesuma=$datadelpedido['id_dependencia_saliente'];
$eliddelpedio=$datadelpedido['id_orden_pedido'];
$arrcan = explode(',',$arreglocantidades);
$arrid = explode(',',$arregloidedes);
$cant=1;
$ides=1;
$p=0;
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
	$buscarproresta=("select tbl_insumos_almacen.id_insumo,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos.insumo from tbl_insumos_almacen,tbl_insumos
   where tbl_insumos_almacen.id_insumo=$elidactual and tbl_insumos_almacen.id_dependencia=$deperestar and
tbl_insumos_almacen.id_insumo=tbl_insumos.id_insumo");
	$repbuscarproresta=ejecutar($buscarproresta);
	$cuantosing=num_filas($repbuscarproresta);
	$dataentabla=assoc_a($repbuscarproresta);
	$cantidadentalba=$dataentabla['cantidad'];
	$MotoUnidad=$dataentabla['monto_unidad_publico'];
	$nombreinsumo=$dataentabla['insumo'];
	$idartentabla=$dataentabla['id_insumo'];
      //  echo "Canti.----->[$lacaactual]----Nomb.--->$nombreinsumo---id_ins----->$elidactual<br>";
	if((($lacaactual>=0)&&($cuantosing>=1))){
		//echo "Canti.----->[$lacaactual]----Nomb.--->$nombreinsumo---id_ins----->$elidactual<br>";
	   $sedespacho=1;
		$nuevacantidad=$cantidadentalba-$lacaactual;
		$actualizartabla=("update tbl_insumos_almacen set cantidad=$nuevacantidad where tbl_insumos_almacen.id_insumo=$elidactual and tbl_insumos_almacen.id_dependencia=$deperestar");
		$repuestaactabla=ejecutar($actualizartabla);

    ///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
    $Movimiento='DESPACHO';///que tipo de movimiento se esta haciendo
    $TipoMovimiento=2;//Es una entrada(1) o una salida(2)
    $Descripcion="SALIDA SEGUN PEDIDO:$idpedidos";//como se puede comprovar el movimiento
    $idprovee=1495; ///CLINISALUD C.A.
    $RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
                      VALUES ('$idartentabla','$deperestar','$idprovee','$Movimiento','$TipoMovimiento','$MotoUnidad','$lacaactual','$cantidadentalba','$nuevacantidad','$elid','$Descripcion');");
   $RegistroMov=ejecutar($RegistroMovimientossql);
   //fin REGISTRAR MOVIMIENTO DE INVENTARIO
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


?>
