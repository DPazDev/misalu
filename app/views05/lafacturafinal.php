<?php
//control de pedido: Procesar pedido

ini_set('error_reporting', E_ALL-E_NOTICE);
ini_set('display_errors', 1);

include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$fechacreado=$fecha;
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$admin=$elid;
$elus=$_SESSION['nombre_usuario_'.empresa];
$elpedidoid=$_POST['pedidoid'];
$quepro=("select tbl_ordenes_pedidos.id_proveedor,tbl_ordenes_pedidos.estatus from tbl_ordenes_pedidos where tbl_ordenes_pedidos.id_orden_pedido=$elpedidoid;");
$requepro=ejecutar($quepro);
$datpro=assoc_a($requepro);
$idprovee=$datpro['id_proveedor'];
$proveedor=$idprovee;
$EstadoPedido=$datpro['estatus'];

$q_admin="select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$elid' and admin.id_sucursal=sucursales.id_sucursal";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


if($EstadoPedido=='1'){ ///solo procesar pedidos por procesar
	///RECEPCION DEL RESTO DE VARIABLES
		$OrdCompras=$_POST['ordencompras'];
		if($OrdCompras=='false') {$EsOrdCompras=0;}else {$EsOrdCompras=1;}//si es una orden 1
		$iddepen=$_POST['depenid'];
		$elivaes=$_POST['eliva'];
		$elaumento=$_POST['elaumento'];
		$losidarti=$_POST['losidpro'];
		$lascantidesp=$_POST['lascandes'];
		$lospreciosp=$_POST['lospreprove'];
		$NOlotes=$_POST['nolote'];
		$lot=explode(',',$_POST['lot']);
		$nlot=count($lot);
		$tieneiva=$_POST['losqtiva'];
		$tieneaumento=$_POST['losqtiaumento'];
		$losartiid=explode(',',$losidarti);
		$lascantdes=explode(',',$lascantidesp);
		$losprecio=explode(',',$lospreciosp);
		$losconiva=explode(',',$tieneiva);
		$losaumento=explode(',',$tieneaumento);
		$guardalotes=explode(',',$NOlotes);
		$fechavenc='';
		$losvencimientos=explode(',',$fechavenc);

		///evitar facturas registrar facturas dobles de la misma empresa
		$lafactura=$_POST['numfact'];
		$elcontroles=$_POST['controlfactura'];
		$ConsultaOrdenCompras=("select tbl_ordenes_compras.id_orden_compra from tbl_ordenes_compras where tbl_ordenes_compras.no_factura='$lafactura' and tbl_ordenes_compras.no_control_fact='$elcontroles' and tbl_ordenes_compras.id_proveedor_insumo=$idprovee;");
		$RespOrdenCompras=ejecutar($ConsultaOrdenCompras);
		$FacturaEncontada=num_filas($RespOrdenCompras);
		if($FacturaEncontada==0){

			$contando=0;
			$j=0;//Fila Articulo
			foreach ($lot as $lotL)
			{	 	$ListaLot=explode('---',$lotL);
					$filalot=count($ListaLot);
					$lis[$j]=$filalot;
					$e=0;
					foreach ($ListaLot as $Datalot) {
							$ListaLot=explode(':',$Datalot);
							$k=0;
							foreach ($ListaLot as $datos) {
									$ArrLot[$j][$e][$k]=$datos;
									$k++;
						 		}
								$e++;
			 			}
			  $contando++;
			  $j++;
			}
			///que lotes se guardan y cuales no
			$son=1;
			foreach ($guardalotes as $guardalotes )
			{
			  $nolotguarda[$son]=$guardalotes;
			  $son++;
			}

			$pordescuento=$_POST['eldescuento'];
			$totaldescuento=$_POST['eltotaldescuen'];
			$lafacfecha=$_POST['fechemifact'];
			$son=1;
			foreach ($losartiid as $losartiid )
			{
			  $ides[$son]=$losartiid;
			  $son++;
			}

			$son=1;
			foreach ($lascantdes as $lascantdes)
			{
			  $cantidades[$son]=$lascantdes;
			  $son++;
			}
			$son=1;
			foreach ($losprecio as $losprecio)
			{
			  $losprecios[$son]=$losprecio;
			  $son++;
			}
			$son=1;
			foreach ($losconiva as $losconiva)
			{
			  $coniva[$son]=$losconiva;
			  $son++;
			}
			$son=1;
			foreach ($losaumento as $losaumento)
			{
			  $conaumen[$son]=$losaumento;
			  $son++;
			}

			$son=1;
			foreach ($losvencimientos as $losvencimientos)
			{
			  $vencidos[$son]=$losvencimientos;
			  $son++;
			}
			$cuantos=count($conaumen);
			$j=0;
			$factconiva=0;
			for($i=1;$i<=$cuantos;$i++){
			   $loteGuardar=$nolotguarda[$i];
				$iart=$ides[$i];///ARTICULO
				$canti=$cantidades[$i];//cantidad del articulo
				$predes=$losprecios[$i];//precio ARt
				$tiva=$coniva[$i];//IVa
				$taumen=$conaumen[$i];//Aumento
				$sevencen=$vencidos[$i];
			        if(empty($sevencen)){
			           $sevencen="NULL";
			          }else{
			           $sevencen="'$sevencen'";
			           }

					if($i==1){
						//guardar factura principal
						$guarisorcompra=("insert into tbl_ordenes_compras(id_proveedor_insumo,fecha_compra,no_factura,fecha_hora_creado,
			                                        id_admin,porcentaje_usado,no_control_fact,descuento,montodescuento,fecha_emi_factura,orden_compra,ivausado)
			                values ($idprovee,'$fecha','$lafactura','$fecha',$elid,$elaumento,'$elcontroles',$pordescuento,$totaldescuento,'$lafacfecha','$EsOrdCompras','$elivaes');");
					$repguarisorcompra=ejecutar($guarisorcompra);
					$buscoguardado=("select tbl_ordenes_compras.id_orden_compra from tbl_ordenes_compras where tbl_ordenes_compras.no_factura='$lafactura' and tbl_ordenes_compras.id_proveedor_insumo=$idprovee and
			tbl_ordenes_compras.fecha_compra='$fecha';");
			      $repbuscoguardado=ejecutar($buscoguardado);
					$dataordcomp=assoc_a($repbuscoguardado);
					$idordecomp=$dataordcomp['id_orden_compra'];
					}
					if($iart>0){//no incluir id 0
					$mtprodu=$canti*$predes;
					if(empty($tiva)){
						$civa=0;
					}else{$civa=1;$factconiva++;}
					if(empty($taumen)){
						$caument=0;
					}else{$caument=1;}
					//Guardar Articulos de la orden de compra (FACTURA)
					$guardarenisumorcom=("insert into tbl_insumos_ordenes_compras(id_orden_compra,id_insumo,cantidad,fecha_vencimiento,
			                           monto_producto,monto_unidad,iva,aumento) values($idordecomp,$iart,$canti,$sevencen,$mtprodu,$predes,$civa,$caument);");
				$repguardareninsumo=ejecutar($guardarenisumorcom);
							$idselect="SELECT LASTVAL() AS id";
							$idinsorden=ejecutar($idselect);
							$idinsord=assoc_a($idinsorden);
							$idiordcomp=$idinsord['id'];	//id de id_insumos_ordenes_compras

			////////////////////////////INSERTAR EN ALMACENES///////////////////////////////////////////////
			if($OrdCompras=='false')//si es orden compras
			{
			//consulta de almacen
					$buscardataprin=("select tbl_insumos_almacen.id_insumo_almacen,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.monto_unidad_publico,tbl_insumos_almacen.id_moneda from
			                      tbl_insumos_almacen where tbl_insumos_almacen.id_dependencia=$iddepen and
			                                                tbl_insumos_almacen.id_insumo='$iart';");
					$repbuscardataprin=ejecutar($buscardataprin);
					$cuantosexistente=num_filas($repbuscardataprin);
					if($cuantosexistente>0){
						$datainsprin=assoc_a($repbuscardataprin);
						$cantidadactual=$datainsprin['cantidad'];
						$montoactual=$datainsprin['monto_unidad_publico'];
						$idCAmbioMon=$datainsprin['id_moneda'];
					}else{
						$cantidadactual=0;$montoactual=0;
						$buscarInusumoCAmbio=("select id_moneda,tbl_insumos_almacen.* from tbl_insumos_almacen where id_dependencia='$iddepen'  order by id_insumo_almacen desc limit 1;");
						$MonedaCaB=ejecutar($buscarInusumoCAmbio);
						$CambioMoneda=assoc_a($MonedaCaB);
						$idCAmbioMon=$CambioMoneda['id_moneda'];

					}

					if(($civa==1) && ($caument==1)){
					    $precioproveedor=(($predes * $elivaes) + $predes);
						$nuestroprecio=$precioproveedor;
						$maselaumento=(($nuestroprecio * $elaumento) + $nuestroprecio);
						if($maselaumento>=$montoactual){
							$preciofinal=$maselaumento;
							}else{$preciofinal=$montoactual;}
					}
					if(($civa==0) && ($caument==1)){
					    $precioproveedor= $predes;
						$maselaumento=(($precioproveedor * $elaumento) + $precioproveedor);
						if($maselaumento>=$montoactual){
							$preciofinal=$maselaumento;
							}else{$preciofinal=$montoactual;}
					}
					if(($civa==0) && ($caument==0)){
					    $maselaumento=$predes;
						if($maselaumento>=$montoactual){
							$preciofinal=$maselaumento;
							}else{$preciofinal=$montoactual;}
					}
					if(($civa==1) && ($caument==0)){
					    $precioproveedor=(($predes * $elivaes) + $predes);
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
					  	$preciofinal=Formato_Numeros($preciofinal/$valorCambio);
						}else{//BS
							$preciofinal=Formato_Numeros($preciofinal);
						}

					    //FIN CONTROL DE PRECIO

					/////////////FIN DE APLICAR CAMBIO DE MONEDA///////

					$lanuevacantidad=$cantidadactual + $canti;
					$nuevoprecio=$lanuevacantidad * $preciofinal;

			    if($cuantosexistente==0){	//no existe el insumo
									$cantidadactual=0;
			            $preglobal=$canti*$preciofinal;
			            $guardarprimera=("insert into tbl_insumos_almacen (id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia)
			                               values
			                              ($iart,$canti,$preciofinal,$preglobal,'$fecha',$iddepen);");
			           	$repguadarprimera=ejecutar($guardarprimera);

          }else{  	//exite el insumo
									$actualizarfin=("update tbl_insumos_almacen set cantidad=$lanuevacantidad,monto_unidad_publico=$preciofinal,
			                                  monto_publico=$nuevoprecio,fecha_hora_creado='$fecha' where
											  tbl_insumos_almacen.id_dependencia=$iddepen and
			                                  tbl_insumos_almacen.id_insumo=$iart;");
									$repactualizarfin=ejecutar($actualizarfin);
			       }
					/////////////////////2022 franklin monsalve Control de precio/////////////////////////
					///insertar nuevo precio en el almacen 2 Control de precios(Almacen solo para nuevos precios)

					$sqlArticuloCrtPrecio=("select tbl_insumos_almacen.id_insumo_almacen,tbl_insumos_almacen.cantidad,tbl_insumos_almacen.monto_unidad_publico from
			                      tbl_insumos_almacen where tbl_insumos_almacen.id_dependencia=2 and
			                                                tbl_insumos_almacen.id_insumo='$iart';");
					$ArtCrtPrec=ejecutar($sqlArticuloCrtPrecio);
					$NunArtCrtPrec=num_filas($ArtCrtPrec);
					if($NunArtCrtPrec==0){	//no existe el insumo
							$cantidadactual=0;
							$preglobal=$preciofinal;
							$InsCrtPrec=("insert into tbl_insumos_almacen (id_insumo,cantidad,monto_unidad_publico,monto_publico,fecha_hora_creado,id_dependencia)
																 values
																($iart,'0',$preciofinal,$preglobal,'$fecha',2);");
								$RInserCrtPrec=ejecutar($InsCrtPrec);

						}else{  	//exite el insumo
							$ActCrtPrec=("update tbl_insumos_almacen set monto_unidad_publico=$preciofinal,
																					monto_publico=$nuevoprecio,fecha_hora_creado='$fecha' where
													tbl_insumos_almacen.id_dependencia=2 and
																					tbl_insumos_almacen.id_insumo=$iart;");
							$RInserCrtPrec=ejecutar($ActCrtPrec);
						}
					/////////////////////2022 franklin monsalve Control de precio/////////////////////////

				///REGISTRAR MOVIMIENTO DE INVENTARIO franklin monsalve
											$Movimiento='COMPRA';///que tipo de movimiento se esta haciendo
											$TipoMovimiento=1;//Es una entrada(1) o una salida(2)
											$Descripcion="ENTRADA SEGUN FACTURA:$idordecomp";//como se puede comprovar el movimiento
											$RegistroMovimientossql=("INSERT INTO tbl_insumos_movimientos (id_insumo,id_dependencia,id_proveedor,movimiento,tipo_movimiento,precio_unitario,cantida,cantidad_almacen,cantida_actual,id_admin,nota_movimiento)
																				VALUES ('$iart','$iddepen','$idprovee','$Movimiento','$TipoMovimiento','$preciofinal','$canti','$cantidadactual','$lanuevacantidad','$elid','$Descripcion');");
										 $RegistroMov=ejecutar($RegistroMovimientossql);
										 //fin REGISTRAR MOVIMIENTO DE INVENTARIO


			  }
			  ////Fin Guardar en almacen

			///RECUPERAR id_insumos_almacen
			    $versiexiste=("select tbl_insumos_almacen.id_insumo_almacen from tbl_insumos_almacen where
			                                  tbl_insumos_almacen.id_dependencia=$iddepen and
			                                  tbl_insumos_almacen.id_insumo='$iart';");
			                $repversiexiste=ejecutar($versiexiste);
			                $idalma=assoc_a($repversiexiste);
								 $idalmacen=$idalma['id_insumo_almacen'];

				////incorporar LOTES FECHA DE VENCIMIENTO Y CANTIDAD POR LOTE
				//consultar lote existente verificar si el lote puede guardarse
				//echo "<h1>NO GUARDAR LOTE VACIO $loteGuardar</h1>";
				if($loteGuardar=='true'){
				//guardar lote

				for($e=0;$e<$lis[$j];$e++){

					$nuevoLote=$ArrLot[$j][$e][0];
					$nuevFecCa=$ArrLot[$j][$e][1];
					$cantxlot=$ArrLot[$j][$e][2];
					//consulta LOTE
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
							$idselect="SELECT LASTVAL() AS id";//id insertado
							$idselect=ejecutar($idselect);
							$idnevLot=assoc_a($idselect);
							$idlot=$idnevLot['id'];
						}


				$sqllotinsord="INSERT INTO public.tbl_lotes_insumos_ordenes (id_insumo_orden_compra,id_lote,cantidad)	VALUES ('$idiordcomp','$idlot','$cantxlot');";
						$lotinst=ejecutar($sqllotinsord); //registrar lostes de la orden


			if($OrdCompras=='false')//si es orden compras no insertar lotes almacen
			{
				//conusltar tbl_lote_insumo_almacen idLote
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
				}
				else {
					$lotAlmac=assoc_a($conslotAlmac);//ASOCIAR DATA
					$cantiLotActual=$lotAlmac['cantida'];
					$idLotAlma=$lotAlmac['id_lotes_insumo_almacen'];
					$nuevacantidad=$cantiLotActual+$cantxlot;
					$sqlupdate="UPDATE tbl_lotes_insumo_almacen SET cantida='$nuevacantidad',fecha_mod='now()' WHERE id_lotes_insumo_almacen='$idLotAlma'";
					$ActuaLotAlmac=ejecutar($sqlupdate);
				}
			}//orden lotes insumos almacen

			 }	//fin for 01
			}//fin if no guardar lote

				$j++;
					}
			//fin if idarticulo mayor de 0

			//for principal carga
			}


		///////////////////REGISTRAR LIBRO DE COMPRAS/////////////////////////////
		///$factconiva>=0 generar Retenciones
		if($factconiva==0){
					$q_facturas=("select clinicas_proveedores.*,tbl_ordenes_compras.* from tbl_ordenes_compras,clinicas_proveedores,
						proveedores where tbl_ordenes_compras.id_orden_compra=$idordecomp and
						tbl_ordenes_compras.id_proveedor_insumo=proveedores.id_proveedor and
						proveedores.id_proveedor=$proveedor and
						clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
						order by tbl_ordenes_compras.fecha_compra;");
				$r_facturas=ejecutar($q_facturas);
				$num_filas=num_filas($r_facturas);
				$f_facturas=asignar_a($r_facturas);
				$codigot=time();
				$codigo=$admin . $codigot;
				// busco el ultimo comprobante //
				$q_facturap="select * from facturas_procesos order by facturas_procesos.id_factura_proceso desc limit 1;";
				$r_facturap=ejecutar($q_facturap);
				if(num_filas($r_facturap)==0){
					$no_comprobante="1";
				}
				else
				{	$f_facturap=asignar_a($r_facturap);
					$no_comprobante=$f_facturap[comprobante];
					$no_comprobante++;
				}
				// fin buscar el ultimo comprobante //
				// Busco las variables globales de iva y retencion//
				$q_variable_iva=("select * from variables_globales where id_variable_global=26");
				$r_variable_iva=ejecutar($q_variable_iva);
				$f_variable_iva=asignar_a($r_variable_iva);

				if (($f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11 || $f_admin[id_tipo_admin]==2) and $f_admin[id_sucursal]==1) {
						$q_variable_ret=("select * from variables_globales where id_variable_global=34");
						$r_variable_ret=ejecutar($q_variable_ret);
						$f_variable_ret=asignar_a($r_variable_ret);
					}
				else
					{ $q_variable_ret=("select * from variables_globales where id_variable_global=35");
						$r_variable_ret=ejecutar($q_variable_ret);
						$f_variable_ret=asignar_a($r_variable_ret);
					}
				// Fin de buscar las variables globales de iva y retencion //

				// busco el ultimo recibo segun la sucursal que pertenezca el admin //
				if ($banco==8)
				{
						$q_facturap1="select * from facturas_procesos where facturas_procesos.id_admin=admin.id_admin and admin.id_sucursal=$f_admin[id_sucursal] and facturas_procesos.id_banco=8 order by num_recibo desc limit 1;";
						$r_facturap1=ejecutar($q_facturap1);
						if(num_filas($r_facturap1)==0){
							$no_factura1="1";
							$descripcion="Recibo numero $no_factura1";
					}
					else
					{	$f_facturap1=asignar_a($r_facturap1);
						$no_factura1=$f_facturap1[num_recibo];
						$no_factura1++;
						$descripcion="Recibo numero $no_factura1";
					}
				}
				else
				{
					$descripcion="Cheque numero $numcheque";
					$no_factura1=0;
				}
		//fin de buscar el ultimo recibo segun la sucursal que pertenezca el admin//

				$q_compra=("select * from tbl_insumos_ordenes_compras,tbl_ordenes_compras where
				tbl_ordenes_compras.id_orden_compra=tbl_insumos_ordenes_compras.id_orden_compra and
				tbl_ordenes_compras.id_orden_compra=$idordecomp;");
				$r_compra=ejecutar($q_compra);
				$monto_factura=0;
				$mon_fac_con_iva=0;
				$mon_fac_sin_iva=0;
				$base_imponible=0;
				$numcheque=0;
				$iva_fact=0;
				$iva_ret=0;
				while($f_compra=asignar_a($r_compra,NULL,PGSQL_ASSOC)){
							if ($f_compra [iva]==1){
								if($f_compra['ivausado']==NULL or $f_compra['ivausado']=='NULL' or $f_compra['ivausado']=='')
								{//si el iva es nulo usar iva por defecto
										$ivaglobal=$f_variable_iva['comprasconfig'];
										$iva=$ivaglobal/100;
								}else{
										$iva=$f_compra['ivausado'];
								}
								$mon_fac_con_iva= $mon_fac_con_iva + (($f_compra [monto_producto] * $iva) + $f_compra [monto_producto]);
								$base_imponible= $base_imponible + $f_compra [monto_producto];
								$iva_fact=$iva_fact + ($f_compra [monto_producto] * ($iva));
							}
							else
							{
								$mon_fac_sin_iva= $mon_fac_sin_iva + $f_compra [monto_producto];
							}
							$iva_ret= $iva_fact * $f_variable_ret[cantidad];
							$monto_fac_pag=$mon_fac_sin_iva +$mon_fac_con_iva - $iva_ret;
							$montodescuento=$f_compra[montodescuento];
							$descuento=$f_compra[descuento];
							$monto_factura=($mon_fac_sin_iva + $mon_fac_con_iva) ;
				 }
				 if ($montodescuento>'0'){
							if($f_facturas['ivausado']==NULL or $f_facturas['ivausado']=='NULL' or $f_facturas['ivausado']=='')
							{//si el iva es nulo usar iva por defecto
								$ivaglobal=$f_variable_iva['comprasconfig'];
								$iva=$ivaglobal/100;
							}else {
								$iva=$f_facturas['ivausado'];
							}
							$mon_fac_sin_iva = $mon_fac_sin_iva -(($mon_fac_sin_iva * $descuento)/100);
							$base_imponible = $base_imponible -(($base_imponible * $descuento)/100);
							$iva_fact=$base_imponible * $iva;
							$iva_ret= $iva_fact * $f_variable_ret[cantidad];
							$monto_fac_pag=$base_imponible +$iva_fact - $iva_ret + $mon_fac_sin_iva;
							$monto_factura=($base_imponible + $iva_fact + $mon_fac_sin_iva) ;
					}
					$idordcom=$idordecomp;
					$factura=$f_facturas[no_factura];
					$confactura=$f_facturas[no_control_fact];
					$fecha_emision=$f_facturas[fecha_emi_factura];
					$id_act_Proveedor=$f_facturas[id_act_pro];
					$montoexento=$mon_fac_sin_iva;
					$baseimponible=$base_imponible;
					$iva_fact=$iva_fact;
					$iva_ret=$iva_ret;
					$moontosin=0;
					/////////////BUSCAR ultimo COMPROBANTE///

					if ($iva_ret>0){
								$q_comretiva="select * from facturas_procesos where facturas_procesos.corre_retiva_seniat>0 and facturas_procesos.id_banco<>9 order by facturas_procesos.corre_retiva_seniat desc limit 1;";
								$r_comretiva=ejecutar($q_comretiva);
								$anio=date("Y");
								$mes=date("m");
								if(num_filas($r_comretiva)==0){
									$no_comp_ret_iva="1";
									$ceros="00000000";
									$length = strlen($no_comp_ret_iva);
									$ceros= substr($ceros,0, - $length);
									$no_comp_ret_iva1= $anio . "-" . $mes . "-" . $ceros . $no_comp_ret_iva;
								}
								else
								{
									$f_comretiva=asignar_a($r_comretiva);
									$no_comp_ret_iva=$f_comretiva[corre_retiva_seniat];
									$no_comp_ret_iva++;
									$ceros="00000000";
									$length = strlen($no_comp_ret_iva);
									$ceros= substr($ceros,0, - $length);
									$no_comp_ret_iva1= $anio . "-" . $mes . "-" . $ceros . $no_comp_ret_iva;

									}
						 }
						else
						 {
								$no_comp_ret_iva1=0;
								$no_comp_ret_iva=0;
							}
							// fin buscar el ultimo comprobante //
					if(!empty($factura) && $factura<>''){
							$procesot .=$idordcom .",";
							$montosin=$baseimponible + $iva_fact;
							$banco=13;
							if ($banco==8)
							{
										$q.="
									insert into facturas_procesos (id_proceso,fecha_creado,hora_creado,id_admin,id_servicio,codigo,id_proveedor,numero_cheque,comprobante,monto_con_retencion,retencion,descuento,iva,monto_sin_retencion,id_banco,cedula,tipo_proveedor,factura,num_recibo,id_orden_compra,compro_retiva_seniat,corre_retiva_seniat,iva_retenido,no_control_fact,fecha_emision_fact,id_act_pro)
									values ('0','$fechacreado','$hora','$admin','0','$codigo','$proveedor','$numcheque','$no_comprobante','0','0','0','0','$montosin','$banco','$rif','0','$factura','$no_factura1','$idordcom','0','0','0','$confactura','$fecha_emision','$id_act_Proveedor');
									";
							}
							else
							{
								$q.="
									insert into facturas_procesos (id_proceso,fecha_creado,hora_creado,id_admin,id_servicio,codigo,id_proveedor,numero_cheque,comprobante,monto_con_retencion,retencion,descuento,iva,monto_sin_retencion,id_banco,cedula,tipo_proveedor,factura,num_recibo,id_orden_compra,compro_retiva_seniat,corre_retiva_seniat,iva_retenido,no_control_fact,fecha_emision_fact,id_act_pro)
									values ('0','$fechacreado','$hora','$admin','0','$codigo','$proveedor','$numcheque','$no_comprobante','$baseimponible','0','0','$iva_fact','$montoexento','$banco','$rif','3','$factura','$no_factura1','$idordcom','$no_comp_ret_iva1','$no_comp_ret_iva','$iva_ret','$confactura','$fecha_emision','$id_act_Proveedor');
									";
							}
								$q.="
								commit work;
								";
								$r=ejecutar($q);
				}
		}

		///////////////////FIN REGISTRAR LIBRO DE COMPRAS/////////////////////////
			 	 $cambiarestado=("update tbl_ordenes_pedidos set estatus=2,fecha_despachado='$fecha' where id_orden_pedido=$elpedidoid;");
			   $repcambiarestado=ejecutar($cambiarestado);
			   $mensaje="El usuario $elus ha procesado el pedido proveedor con la factura no.$lafactura al proveedor con el id $idprovee y numero de control $elcontroles ";
				 $log="CAMBIO EL ESTADO DE LA ORDEN CON NUMERO $proceso A $estado_proceso ";
				 logs($log,$ip,$elid);
				 $mesaje="El pedido se ha cargado exitosamene!!!";
		}else //fin factura repetida
		 {$mesaje="Este numero de Factura y numero de control se repite, no se han realizado cambios !!!";}
}//if de verificacion
else{$mesaje="Pedido ya se encuentra procesado!!!";}
?>

<table class="tabla_cabecera3"  cellpadding='0' cellspacing='0' >
     <tr>
       <br>
         <td colspan=4 class="titulo_seccion" align='center'><?php echo $mesaje;?></td>
	  </tr>
</table>
