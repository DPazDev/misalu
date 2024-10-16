<?php
//almacenar insumos de orden de compras
ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);
include ("../../lib/jfunciones.php");
sesion();
function gestionlotes_almacen($idlot,$cantxlot,$iart,$idalmacen){
	///funcion para insertar y actualizar los lostes del almacen
	//ing franklin monsalve: gestiona el almacen para insertar o actualizar la relacion de lotes con el almacen

//CONSULTAR tbl_lote_insumo_almacen ->idLote
	$sqlconslotAlmac="SELECT
  			tbl_lotes_insumo_almacen.id_lotes_insumo_almacen,tbl_lotes_insumo_almacen.id_lote,tbl_lotes_insumo_almacen.cantida,tbl_lotes_insumo_almacen.fecha_mod
			FROM public.tbl_lotes_insumo_almacen WHERE
    		tbl_lotes_insumo_almacen.id_insumo_almacen = '$idalmacen'
			AND tbl_lotes_insumo_almacen.id_lote = '$idlot';";
	$conslotAlmac=ejecutar($sqlconslotAlmac);
	$nunDeLoteExiten=num_filas($conslotAlmac);

	if($nunDeLoteExiten==0)
		{//insertar nuevo lote insumo almacen
			$sqlotinsalma="INSERT INTO public.tbl_lotes_insumo_almacen (id_lote,cantida,id_insumo,fecha_mod,id_insumo_almacen)
					VALUES ('$idlot','$cantxlot','$iart',now(),'$idalmacen')";
			$conslotAlmac=ejecutar($sqlotinsalma);
			$cmdinse = pg_affected_rows($conslotAlmac);

		}
	else {
		$lotAlmac=assoc_a($conslotAlmac);//ASOCIAR DATA
		$cantiLotActual=$lotAlmac['cantida'];
		$idLotAlma=$lotAlmac['id_lotes_insumo_almacen'];
		$nuevacantidad=$cantiLotActual+$cantxlot;
		$sqlupdate="UPDATE tbl_lotes_insumo_almacen SET cantida='$nuevacantidad',fecha_mod='now()' WHERE id_lotes_insumo_almacen='$idLotAlma'";
		$ActuaLotAlmac=ejecutar($sqlupdate);
			$cmdinse = pg_affected_rows($conslotAlmac);

	}
///FIN DE LA FUNCION
if($cmdinse>1){$crtregistrossql=true;}else {$crtregistrossql=false;}

return $crtregistrossql;
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////RECEBIR VARIABLES//////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$idpedido=$_POST['pedido'];
$depen=$_POST['almacen'];
$listcrtlotes=explode(',',$_POST['arrcrtlote']);//control de modificasion de lotes

///////////////////lotes recibir/////
$lotes=$_POST['arrlotes'];//cadena
$reglonlotes=explode(',',$lotes);
$nlot=count($reglonlotes);//cantidad x articulos lote

$j=0;//Fila Articulo
foreach ($reglonlotes as $rlotes)
{ 	$lotest[$j]=$rlotes;
$ListaLot=explode('***',$rlotes);
	$numfila=count($ListaLot);//nun de lotes por Art
	$lis[$j]=$numfila;
	$e=0;
//	echo"FILAS: $numfila<br>";
	foreach ($ListaLot as $Datalot) {
		$listdatos=explode('::',$Datalot);
		$numdatos=count($listdatos);	//cantidad poda de informacion lote"3: "
		$ArrLot[$j][$e][0]=$listdatos[0];//id_insumo_orden_compra
		$ArrLot[$j][$e][1]=$listdatos[1];//cantidad por lotes
		$ArrLot[$j][$e][2]=$listdatos[2];//fecha de lote
		$ArrLot[$j][$e][3]=$listdatos[3];//Status
		$e++;
 	}
  $contando++;
  $j++;
}
//////fin de recepcion de lotes///

//cargar el porcentaje de iva
$busconficompra=("select variables_globales.comprasconfig,variables_globales.iva  from variables_globales where variables_globales.nombre_var='porcaumento';");
$repbusconficompra=ejecutar($busconficompra);//configuracion de compras iva
$datacofcompra=assoc_a($repbusconficompra);
$iva=$datacofcompra['iva'];//variable de iva
$elivaes=($iva/100);// calcular iva%
$aumento=$datacofcompra['comprasconfig'];// variable aumento
$elaumento=($aumento/100);// AUMENTO PORCENTAJE
$fecha=date("Y-m-d");

//articulos del pedido
 $listaarti="
 select
  tbl_insumos_ordenes_compras.id_insumo_orden_compra,
  tbl_insumos_ordenes_compras.id_insumo,
  tbl_insumos.insumo,
  tbl_laboratorios.laboratorio,
  tbl_tipos_insumos.tipo_insumo,
   tbl_insumos_ordenes_compras.monto_producto,
  tbl_insumos_ordenes_compras.monto_unidad,
  tbl_insumos_ordenes_compras.iva,
  tbl_insumos_ordenes_compras.aumento, tbl_insumos_ordenes_compras.cantidad,
  (SELECT
  tbl_insumos_almacen.cantidad
FROM
  public.tbl_insumos_almacen
WHERE
  tbl_insumos_almacen.id_insumo = tbl_insumos_ordenes_compras.id_insumo and tbl_insumos_almacen.id_dependencia='$depen') as cantalmacen
  from
  tbl_insumos_ordenes_compras,tbl_insumos,tbl_laboratorios,tbl_tipos_insumos
where
  tbl_insumos_ordenes_compras.id_orden_compra='$idpedido'  and
  tbl_insumos_ordenes_compras.id_insumo=tbl_insumos.id_insumo and
  tbl_insumos.id_laboratorio=tbl_laboratorios.id_laboratorio and
  tbl_insumos.id_tipo_insumo=tbl_tipos_insumos.id_tipo_insumo
 order by tbl_insumos.insumo;
";
$articulos=ejecutar($listaarti);
$j=0;
while($datoArt=asignar_a($articulos,NULL,PGSQL_ASSOC)){
		$idinsordcomp=$datoArt['id_insumo_orden_compra'];
		$Arti=$datoArt['id_insumo'];
		$lab=$datoArt['laboratorio'];
		$tipoinsumo=$datoArt['tipo_insumo'];
		$monto=$datoArt['monto_producto'];
		$montounidad=$datoArt['monto_unidad'];
		$tiva=$datoArt['iva'];//IVa
		$taumen=$datoArt['aumento'];//Aumento

	///exie iva para el producto 1 si 0 no
	if($tiva==1){
			$civa=1;
		}else{$civa=0;}
		///exie aumento para el producto 1 si 0 no
		if($taumen==1){
			$caument=1;
		}else{$caument=0;}



		$cantidadinsumo=$datoArt['cantidad'];
		//consultar existencia del isumo en el almacen
	 	$buscardataprin=("select tbl_insumos_almacen.id_insumo_almacen,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos_almacen.id_moneda from
	                      tbl_insumos_almacen where tbl_insumos_almacen.id_dependencia='$depen' and
	                                                tbl_insumos_almacen.id_insumo='$Arti';");
      $repbuscardataprin=ejecutar($buscardataprin);
      $nunregalmacen=num_filas($repbuscardataprin);
      if($nunregalmacen>0) {
			$datainsprin=assoc_a($repbuscardataprin);
			$cantidadactual=$datainsprin['cantidad'];
			$montoactual=$datainsprin['monto_unidad_publico'];
			$idCAmbioMon=$datainsprin['id_moneda'];
	   }
	   else {
	     	$cantidadactual=0;
			$montoactual=0;
			$buscarInusumoCAmbio=("select id_moneda,tbl_insumos_almacen.* from tbl_insumos_almacen where id_dependencia='$iddepen'  order by id_insumo_almacen desc limit 1;");
			$MonedaCaB=ejecutar($buscarInusumoCAmbio);
			$CambioMoneda=assoc_a($MonedaCaB);
			$idCAmbioMon=$CambioMoneda['id_moneda'];
	   }

		if(($civa==1) && ($caument==1)){//con aumento e iva

		   $precioproveedor=(($montounidad * $elivaes) + $montounidad);
			$nuestroprecio=$precioproveedor;
			$maselaumento=(($nuestroprecio * $elaumento) + $nuestroprecio);
			if($maselaumento>=$montoactual){
				$preciofinal=$maselaumento;
				}else{$preciofinal=$montoactual;}

			}
		if(($civa==0) && ($caument==1)){//con aumento y sin IVA
			$precioproveedor= $montounidad;
			$maselaumento=(($precioproveedor * $elaumento) + $precioproveedor);
			if($maselaumento>=$montoactual){
				$preciofinal=$maselaumento;
			}else{$preciofinal=$montoactual;}
			}
			if(($civa==0) && ($caument==0)){//sin iva y sin aumento
			 $maselaumento=$montounidad;
			if($maselaumento>=$montoactual){
				$preciofinal=$maselaumento;
				}else{$preciofinal=$montoactual;}
			}
			if(($civa==1) && ($caument==0)){//sin aumento y con iva
			   $precioproveedor=(($montoactual * $elivaes) + $montoactual);
				$nuestroprecio=$precioproveedor;
				$maselaumento=$nuestroprecio;
				if($maselaumento>=$montoactual){
					$preciofinal=$maselaumento;
				}else{$preciofinal=$montoactual;}
			}
			//////////////////////////////////////////TRANFORMACION DE MONEDA ////////////
			///CALCULO DEL CAMBIO
			$idMoneda=$idCAmbioMon;///MONEDA EN LA QUE ESTA EXPRESADA LAS CANTIDADES DE ALMACEN
			$CosnuSQLCAmbio=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas where tbl_monedas.id_moneda=$idMoneda;");
			$cambioMonedaData=ejecutar($CosnuSQLCAmbio);
			$CacmbioMoneda=assoc_a($cambioMonedaData);
			$valorCambio=$CacmbioMoneda['valor'];///Ultimo CAmbio de moneda
			$monedaCambio=$CacmbioMoneda['moneda'];///Moneda de Cambio
			$SimboloCambio=$CacmbioMoneda['simbolo'];///Simbolo de la moneda
			///cambio a usar segun la poliza
			$ejecutarCambio=1;
			///APLICAR CONTROL DE CAMBIO
				if($idMoneda>=1){ //Combio
					$preciofinal=Formato_Numeros($preciofinal*$valorCambio);
				}else{//BS
					$preciofinal=Formato_Numeros($preciofinal);
				}
						
					//FIN CONTROL DE PRECIO

			/////////////FIN DE APLICAR CAMBIO DE MONEDA///////
			echo "preciofinal de $Arti: $preciofinal";
	//determinacion de la nueva cantidad en almacen
			$lanuevacantidad=$cantidadactual + $cantidadinsumo;
			$nuevoprecio=$lanuevacantidad * $preciofinal;//cantidades por nuevo precio
		//consulta de existencia del articulo en el almacen
	  		$versiexiste=("select tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen where
	                                  tbl_insumos_almacen.id_dependencia=$depen and
	                                  tbl_insumos_almacen.id_insumo='$Arti';");
	                                  $repversiexiste=ejecutar($versiexiste);
	      $cuantosexistente=num_filas($repversiexiste);
	      if($cuantosexistente==0){
	           $preglobal=$cantidadinsumo*$preciofinal;
	           $guardarprimera=("insert into tbl_insumos_almacen (id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia)
	                                       values
	                                      ($Arti,$cantidadinsumo,$preciofinal,$preglobal,'$fecha',$depen);");
	           $repguadarprimera=ejecutar($guardarprimera);
	           $cmdinsumoalmacen = pg_affected_rows($repguadarprimera);
	       }
	       else{
				$actualizarfin=("update tbl_insumos_almacen set cantidad=$lanuevacantidad,monto_unidad_publico=$preciofinal,
	                                  monto_publico=$nuevoprecio,fecha_hora_creado='$fecha' where
									  tbl_insumos_almacen.id_dependencia=$depen and
	                                  tbl_insumos_almacen.id_insumo='$Arti';");
			$repactualizarfin=ejecutar($actualizarfin);
			$cmdinsumoalmacen = pg_affected_rows($repactualizarfin);
		   }
		   if($cmdinsumoalmacen>1) {$insumosalmacen=true;}else {$insumosalmacen=false;}

	   ////GUARDAR o ACTUALIZAR LOTES DE  INSUMOS ALMACEN
	///RECUPERAR id_insumos_almacen
	   	 $versiexiste=("select tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen where
	                                  tbl_insumos_almacen.id_dependencia=$depen and
	                                  tbl_insumos_almacen.id_insumo='$Arti';");
	                $repversiexiste=ejecutar($versiexiste);
	                $idalma=assoc_a($repversiexiste);
						 $idalmacen=$idalma['id_insumo_almacen'];

		//////////////////////carga de lotes///////////////
		$idinsumoorden=$datoArt['id_insumo_orden_compra'];

		///////////////////////////////////CONSUTA DE LOTES de este INSUMO/////////////////////////////////////////////////////////////////////
		$sqllotes="SELECT tbl_lotes_insumos_ordenes.id_insumo_orden_compra,
		tbl_lotes_insumos_ordenes.id_lote,
	 	tbl_lotes_insumos_ordenes.cantidad
		FROM tbl_lotes_insumos_ordenes WHERE tbl_lotes_insumos_ordenes.id_insumo_orden_compra='$idinsordcomp';";

	   $cosultalotes=ejecutar($sqllotes);
	   $numreglotes=num_filas($cosultalotes);
	////////////////////////STATUS DE LOTES///////////

		if($listcrtlotes[$j]==0){
		//cargar cada lote e insertar en el almacen
			while($linsumo=asignar_a($cosultalotes,NULL,PGSQL_ASSOC)){
				$idlot=$linsumo['id_lote'];
				$cantxlot=$linsumo['cantidad'];
				$respuesGestion=gestionlotes_almacen($idlot,$cantxlot,$Arti,$idalmacen);
			if($respuesGestion==true) {echo"insercion exitosa";}else {echo "no fue bien";}
				$entro++;
			}

		}else {
		if($listcrtlotes[$j]==4 && $numreglotes==0) {}
	///////////////////////ELIMINAR LOS relacion lotes ordenes EXISTENTE/////////////////
		$sqldel="delete FROM tbl_lotes_insumos_ordenes	WHERE tbl_lotes_insumos_ordenes.id_insumo_orden_compra='$idinsordcomp';";
		$elimineLIO=ejecutar($sqldel);
		$cmDel = pg_affected_rows($elimineLIO);
		 if($cmDel>1) {$DelInsumoOrden=true;}else {$DelInsumoOrden=false;}//control de insercion
		 $insertar=true;//se insertaran lotes
		for($e=0;$e<$lis[$j];$e++){//for carga de lotes
			$nuevoLote=$ArrLot[$j][$e][0];
			$cantxlot=$ArrLot[$j][$e][1];
			$nuevFecCa=$ArrLot[$j][$e][2];
			$status=$ArrLot[$j][$e][3];
			//verificar que no allan vacios
			if($nuevoLote=='') {	$insertar=false;}
			if($cantxlot=='') {	$insertar=false;}
			if($nuevFecCa=='') {	$insertar=false;}
			if($insertar==true){//if vacios insertar si no hay vacios

			$conLOTE="SELECT tbl_lotes.lote, tbl_lotes.id_lote FROM public.tbl_lotes WHERE tbl_lotes.lote = '$nuevoLote'; ";
			$cosLote=ejecutar($conLOTE);
			$nunEncontrados=num_filas($cosLote);
			if($nunEncontrados>0){//lote existe
			$coslot=assoc_a($cosLote);
			$idlot=$coslot['id_lote'];
			}
			else {//lote nuevo
			$lotsql="INSERT INTO public.tbl_lotes (lote,fecha_caduca_lote) VALUES ('$nuevoLote','$nuevFecCa')";
			$lotinst=ejecutar($lotsql);
				$inselotes = pg_affected_rows($lotinst);
		 if($inselotes>1) {$inslotes=true;}else {$inslotes=false;}//control de insercion
			$idselect="SELECT LASTVAL() AS id";//id insertado
			$idselect=ejecutar($idselect);
			$idnevLot=assoc_a($idselect);
			$idlot=$idnevLot['id'];
			}
			//insertar la relacion insumo lote - orden
			$sqllotinsord="INSERT INTO public.tbl_lotes_insumos_ordenes (id_insumo_orden_compra,id_lote,cantidad)	VALUES ('$idinsordcomp','$idlot','$cantxlot');";
			$lotinst=ejecutar($sqllotinsord); //registrar lostes de la orden
						$canloteInsOrd = pg_affected_rows($lotinst);
		 if($canloteInsOrd>1) {$loteInsOrd=true;}else {$loteInsOrd=false;}//control de insercion
			//insertar y actualizar el alamcen con lo lostes
			$respuesGestion=gestionlotes_almacen($idlot,$cantxlot,$Arti,$idalmacen);

			}//fin de if verifica vacios
		 }//fin for carga de lotes

		}
	$j++;//arrays
 }//carga de ARTICULOS FIN while

 //CAMBIAR EL ESTATUS DE LA ORDEN A FACTURA
 $sqlordCompra="update tbl_ordenes_compras set orden_compra=0 where tbl_ordenes_compras.id_orden_compra='$idpedido';";
$cambiarOrdCompra=ejecutar($sqlordCompra); //registrar lostes de la orden
$cmdtuples = pg_affected_rows($cambiarOrdCompra);


if($respuesGestion==false || $loteInsOrd==false || $inslotes==false || $DelInsumoOrden==false || $insumosalmacen==false )
 { //echo "Existen parametros que no an sido insertados";
if($respuesGestion==false) { $gt="<p>Gestion</p>";}
if($loteInsOrd==false) { $ord="<p>insertlotesorden</p>";}
if($inslotes==false) { $slot= "<p>insetlote</p>";}
if($DelInsumoOrden==false) { $sumoOr= "<p>eliminacion</p>";}
if($insumosalmacen==false) { $mosal= "<p>insumo</p>";}

 }

echo "filas que han sido afectadas: " . $cmdtuples ;

?>
<table class="tabla_cabecera3" cellpadding='0' cellspacing='0' >
<tr>
<td class="titulo_seccion" >ORDEN DE COMPRAS CONVERTIDA A FACTURA EXITOSA</td>
</tr>
</table>

<?php

?>
