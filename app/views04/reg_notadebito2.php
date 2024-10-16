<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=utf-8');
$factura = $_REQUEST['factura'];
$admin= $_SESSION['id_usuario_'.empresa];
$VarloCambio=$_SESSION['valorcambiario'];
///eliminar 00 del numero de factura
$factura=ltrim($factura,'0');//facilita la busqueda
/*
$q_sucursales=("select * from sucursales  order by sucursales.sucursal");
$r_sucursales=ejecutar($q_sucursales);
$q_servicios=("select * from servicios  order by servicios.servicio");
$r_servicios=ejecutar($q_servicios);
*/
$q_admin=("select * from admin  where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$fecha_creado=date("Y-m-d");
//busco las series.
$q_factura="select tbl_series.*,tbl_facturas.* from tbl_facturas,tbl_series,admin where CAST (tbl_facturas.numero_factura as integer)='$factura'
and tbl_facturas.id_serie=tbl_series.id_serie and
tbl_series.id_sucursal=$f_admin[id_sucursal] and tbl_series.id_serie=tbl_facturas.id_serie and admin.id_admin=tbl_facturas.id_admin;";
$r_factura=ejecutar($q_factura);

if(num_filas($r_factura)==0){
		?>
		<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
			<tr>
				<td  colspan=4  class="titulo_seccion">La factura No Existe</td>
			</tr>
		</table>
	<?php
}
else
{
	 $f_factura = asignar_a($r_factura);
	 //busco las series.
	 $r_serie=("select * from tbl_series,admin where tbl_series.id_sucursal=admin.id_sucursal and admin.id_admin='$admin'");
	 $f_serie=ejecutar($r_serie) or mensaje(ERROR_BD);
	 $f_series=asignar_a($f_serie);
	 /* **** busco las factura para saber cual es la ultima nota de debito hecha**** */
	 $q_notadebito="select * from tbl_nota_factura,tbl_facturas where tbl_facturas.id_factura=tbl_nota_factura.id_factura and tbl_facturas.id_serie=$f_series[id_serie] and tbl_nota_factura.tipo_nota=2 order by tbl_nota_factura.id_nota_factura desc limit 1;";
	 $r_notadebito=ejecutar($q_notadebito);
	 ///buscar el ultimo numero de control de la serie
	 $q_facturaserie="select * from tbl_facturas where tbl_facturas.id_serie=$f_series[id_serie] order by tbl_facturas.id_factura desc limit 1;";
	 $rultimaFactura=ejecutar($q_facturaserie);
	 $ultNumC=asignar_a($rultimaFactura);
	 $ultimoNumControlserie=(int) $ultNumC['numcontrol'];//ultimo numero de control de la serie
	 //////////Buscar ultimo control de las notas Facturas
	 //////////Buscar ultimo control de las notas Facturas
	 $q_Control="select * from tbl_nota_factura,tbl_facturas where tbl_facturas.id_factura=tbl_nota_factura.id_factura and tbl_facturas.id_serie=$f_series[id_serie] order by tbl_nota_factura.id_nota_factura desc limit 1;";
	 $rultimaCrtNota=ejecutar($q_Control);
	 if(num_filas($rultimaCrtNota))
	 		$ultCrtN=asignar_a($rultimaCrtNota);

	 $ultimoNumControlNota=(int) $ultCrtN['numcontrolnota'];//ultimo numero de control de la serie

	 if(num_filas($r_notadebito)==0){
			$no_notadebito="0001";

		}else{
			$f_notadebito=asignar_a($r_notadebito);
			///trasformar a entero el string
			$no_notadebito=(int) $f_notadebito[num_nota];
			$ultimoNumControlNota=(int) $ultCrtN[numcontrolnota];
			if($no_notadebito<=10){
				$no_notadebito++;
				if($no_notadebito==10)	$no_notadebito="00$no_notadebito";
				else			$no_notadebito="000$no_notadebito";
			}else if($no_notadebito>10 && $no_notadebito<=100){
		                $no_notadebito++;
				if($no_notadebito==100)    $no_notadebito="0$no_notadebito";
				else                    $no_notadebito="00$no_notadebito";
			}else if($no_notadebito>100 && $no_notadebito<=1000){
				$no_notadebito++;
				if($no_notadebito==1000)     $no_notadebito="$no_notadebito";
				else                      $no_notadebito="0$no_notadebito";
			}else{
				$no_notadebito++;
			}
		}

		///comparar numeros de contrl de TBL_facturas y tbl_nota_factura
		if($ultimoNumControlserie==$ultimoNumControlNota){
			$ultimoNumControl=$ultimoNumControlserie;
		}else if($ultimoNumControlserie>$ultimoNumControlNota){
			$ultimoNumControl=$ultimoNumControlserie;
		}else{$ultimoNumControl=$ultimoNumControlNota;}

		$q_proceso="select * from procesos,tbl_procesos_claves where procesos.id_proceso=tbl_procesos_claves.id_proceso and   tbl_procesos_claves.id_factura=$f_factura[id_factura]";
		$r_proceso=ejecutar($q_proceso);
		$f_proceso=asignar_a($r_proceso);
		$q_procesos="select * from tbl_procesos_claves where   tbl_procesos_claves.id_factura=$f_factura[id_factura]";
		$r_procesos=ejecutar($q_procesos);
		if ($f_factura[con_ente]>0)
			{	//busco los datos del ente
						$q_ente="select * from entes where entes.id_ente=$f_factura[con_ente]";
						$r_ente = ejecutar($q_ente);
						$f_ente = asignar_a($r_ente);
						?>
						<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
								<tr>
									<td  colspan=4  class="titulo_seccion">Datos del Cliente</td>
								</tr>
								<tr>
									<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
									<td class="tdcampos"><?= $f_ente['nombre'] ?></td>
									<td class="tdtitulos">Rif o C.I. No.: </td>
									<td class="tdcampos"><?= $f_ente['rif'] ?></td>
								</tr>
								<tr>
									<td class="tdtitulos">Direcci&oacute;n:</td>
									<td class="tdcampos"><?= $f_ente['direccion'] ?></td>
									<td class="tdtitulos">Tel&eacute;fonos:</td>
									<td class="tdcampos"><?= $f_ente['telefonos'] ?></td>
								</tr>
							</table>
						<?php
				}
				else
				{
					$id_titular = $f_proceso['id_titular'];
					$id_beneficiario = $f_proceso['id_beneficiario'];
					$proceso= $f_proceso['id_proceso'];
					$clave= $f_proceso['no_clave'];
					//busco los datos del ente
					$q_ente="select entes.* from entes, titulares
							where
							titulares.id_titular = '$id_titular' and
							titulares.id_ente = entes.id_ente";
					$r_ente = ejecutar($q_ente);
					$f_ente = asignar_a($r_ente);

					//Busco los datos del titular.
					$q_titular = "select clientes.* from clientes, titulares
							where
							clientes.id_cliente = titulares.id_cliente and
							titulares.id_titular = '$id_titular'";
					$r_titular = ejecutar($q_titular);
					$f_titular = asignar_a($r_titular);

					//Busco los datos del beneficiario.
					if($id_beneficiario>0){
						$q_bene = "select clientes.* from clientes, titulares, beneficiarios
								where
								clientes.id_cliente = beneficiarios.id_cliente and
								titulares.id_titular = '$id_titular' and
								beneficiarios.id_titular = titulares.id_titular and
								beneficiarios.id_beneficiario = '$id_beneficiario'";
						$r_bene = ejecutar($q_bene);
						$f_bene = asignar_a($r_bene);
					}


				?>
				<table class="tabla_cabecera5" border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td  colspan=4  class="titulo_seccion">Datos del Cliente</td>
						</tr>
				<?php
					if($f_ente['id_ente']!=0){

							?>
							<tr>
								<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
								<td class="tdcampos"><?= $f_ente['nombre'] ?></td>
								<td class="tdtitulos">Rif o C.I. No.: </td>
								<td class="tdcampos"><?= $f_ente['rif'] ?></td>
							</tr>
							<tr>
								<td class="tdtitulos">Direcci&oacute;n:</td>
								<td class="tdcampos"><?= $f_ente['direccion'] ?></td>
								<td class="tdtitulos">Tel&eacute;fonos:</td>
								<td class="tdcampos"><?= $f_ente['telefonos'] ?></td>
							</tr>
							<tr>
								<td class="tdtitulos">Proceso</td>
								<td class="tdcampos"><?php echo $proceso; ?></td>
								<td class="tdtitulos">No. de Clave:</td>
								<td class="tdcampos"><?= $clave ?></td>
							</tr>
							<tr>
								<td class="tdtitulos">Titular: </td>
								<td class="tdcampos"><?= $f_titular['nombres'].' '.$f_titular['apellidos'] ?></td>
								<td class="tdtitulos">Cedula.</td>
								<td class="tdcampos"><?= $f_titular['cedula'] ?></td>
							</tr>
					<?php
							if($id_beneficiario>0){
							?>
									<tr>
										<td class="tdtitulos">Beneficiario: </td>
										<td class="tdcampos"><?= $f_bene['nombres'].' '.$f_bene['apellidos'] ?></td>
										<td class="tdtitulos">Cedula</td>
										<td class="tdcampos"><?= $f_bene['cedula'] ?></td>
									</tr>
							<?php
							}else{ ?>
									<tr>
										<td class="tdtitulos" colspan="4"><b></b>  <b></b></td>
									</tr>
									<?php
							}
					}
					else
					{ 	?>
									<tr>
										<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
										<td class="tdcampos"><?= $f_titular['nombres'] .' '.$f_titular['apellidos'] ?></td>
										<td class="tdtitulos">Rif o C.I. No.:</td>
										<td class="tdcampos"><?= $f_titular['cedula'] ?></td>
									</tr>
									<tr>
										<td class="tdtitulos">Direcci&oacute;n: </td>
										<td class="tdcampos"><?= $f_titular['direccion_hab'] ?></td>
										<td class="tdtitulos">Tel&eacute;fonos:</td>
										<td class="tdcampos"><?= $f_titular['telefono_hab'].' /  '.$f_titular['celular'] ?></td>
									</tr>

							<?php
					}
				}
		?>
		<tr>
			<td  colspan=4  class="titulo_seccion">Gastos de Factura</td>
		</tr>
		<tr>
			<td colspan=4>
				<table class="tabla colortable" style="which:100%;min-width:100%;" border=0 cellpadding=0 cellspacing=0 >

		<?php
			//Busco los procesos que estan afiliados a la clave.
			pg_result_seek($r_procesos,0);//mueve el cursor a la posicion 0 de la consulta
			while($f_proceso = asignar_a($r_procesos)){
				$subtotal=0;
				?>
				<tr>
					<th class="tdcamposr" colspan=1 >PROCESO</th>
					<th class="tdcamposr" colspan=1 ><?php echo $f_proceso[id_proceso] ?></th>
					<th class="tdcamposr" colspan=1 ></th>
					<th class="tdcamposr" colspan=1 ></th>
				</tr>
				<tr>
					<th class="tdtitulos" colspan=2 >CONCEPTO O DESCRIPCION	</th>
					<th class="tdtitulos" colspan=2 >Bs.S.</th>
				</tr>


				<?php
				$q_proceso = "select gastos_t_b.*, procesos.* from gastos_t_b,procesos
						where
						procesos.id_proceso='$f_proceso[id_proceso]' and
						procesos.id_proceso=gastos_t_b.id_proceso";
				$r_proceso = ejecutar($q_proceso);
				if(num_filas($r_proceso)>0){
				while($f = asignar_a($r_proceso)){
				$subtotal=$subtotal + $f[monto_aceptado];
				$total=$total + $f[monto_aceptado];
				?>
				<tr>
					<td class="tdcampos" colspan=2 ><?php echo $f[descripcion]?> &nbsp;&nbsp; <?php echo $f[nombre]?></td>
					<td class="tdcampos" colspan=2  valign="bottom"><?php echo montos_print($f[monto_pagado])?></td>
				</tr>

				<?php
				}
				?>
				<tr>
					<td class="tdtitulos" colspan=1 ></td>
					<td class="tdtitulos" colspan=1 >Sud Total</td>
					<td class="tdtitulos" colspan=2 valign="bottom"><?php echo montos_print($subtotal)?></td>
				</tr>

				<?php
				}
			}

			?>
			<tr>
				<td  class="tdtitulos"></td>
				<td  class="tdcamposr">Total BF.</td>
				<td colspan=2 class="tdcamposr"><?php echo $total; ?></td>
			</tr>
			</table>
		</td>
	</tr>
			<?php
//////////////////////CONSULTAR SI LA FACTURA TIENE NOTAS DE DEBITO Y CREDITO////////////////////////
				 $NotaDebitoFactua=("select * from tbl_facturas, tbl_nota_factura where tbl_facturas.id_factura='$f_factura[id_factura]' and tbl_facturas.id_factura=tbl_nota_factura.id_factura and tipo_nota=2;");
				 $NDebitoFactura=ejecutar($NotaDebitoFactua);
				 $NunDebito=num_filas($NDebitoFactura)>
				 $NotaCreditoFactua=("select * from tbl_facturas, tbl_nota_factura where tbl_facturas.id_factura='$f_factura[id_factura]' and tbl_facturas.id_factura=tbl_nota_factura.id_factura and tipo_nota=1;");
				 $NCreditoFactura=ejecutar($NotaCreditoFactua);
				 $NunCredito=num_filas($NCreditoFactura);
			if($NunDebito>0 || $NunCredito>0){
			?>
			<tr>
				<td colspan=4  class="verificar_fecha">Notas de débito exitentes para factura <?php echo $f_factura['numero_factura']." SERIE ".$f_factura['nomenclatura'];?></td>
			</tr>
			<tr>
				<td colspan=4  class="tdcamposr">
					<table  class="tabla colortable" style="which:100%;min-width:100%;" border=0 cellpadding=0 cellspacing=0>
						<?php if($NunDebito>0){?>
							<tr>
								<th colspan=4 class="tdcamposc">NOTAS DE DÉBITO</td>
							</tr>
						<?php } ?>
						<tr>
							<td class="tdtitulos">Numero Nota</td>
							<td class="tdtitulos">Control Nota</td>
							<td class="tdtitulos">Fecha Emision</td>
							<td class="tdtitulos">monto</td>
						</tr>

			<?php
					if($NunDebito>0){
						while($notasExitentes = asignar_a($NDebitoFactura)){
								$numNotaDebito=$notasExitentes['num_nota'];
								$numControlDebito=$notasExitentes['numcontrolnota'];
								$FechaEmiNota=$notasExitentes['fecha_emision'];
								$MontoNotaDebito=$notasExitentes['monto_nota'];
								$totalDebito=$totalDebito+$MontoNotaDebito;
							?>
								<tr>
									<td  class="tdcampos"><?php echo $numNotaDebito;?></td>
									<td  class="tdcampos"><?php echo $numControlDebito;?></td>
									<td  class="tdcampos"><?php echo $FechaEmiNota;?></td>
									<td  class="tdcamposr"><?php echo $MontoNotaDebito;?></td>
								</tr>
								<?php
							} ?>
								<tr>
									<td colspan=2 class="tdcamposc"></td>
									<td  class="tdcamposc" style="text-align:right;">Total débito:</td>
									<td  class="tdcamposc"><?php echo $totalDebito;?></td>
								</tr>
								<?php
							} //fin nota Debito
					/////////////////////NOTAS DE CREDITO//////////
					if($NunCredito>0){
							?>
								<tr>
									<th colspan=4 class="tdcamposc">NOTAS DE CRÉDITO</td>
								</tr>
				<?php while($NotaCredito = asignar_a($NCreditoFactura)){
								$numNotaCredito=$NotaCredito['num_nota'];
								$numControlCredito=$NotaCredito['numcontrolnota'];
								$FechaNotaCredito=$NotaCredito['fecha_emision'];
								$MontoNCredito=$NotaCredito['monto_nota'];
								$totalCredito=$totalCredito+$MontoNCredito;
							?>
								<tr>
									<td  class="tdcampos"><?php echo $numNotaCredito;?></td>
									<td  class="tdcampos"><?php echo $numControlCredito;?></td>
									<td  class="tdcampos"><?php echo $FechaNotaCredito;?></td>
									<td  class="tdcamposr"><?php echo $MontoNCredito;?></td>
								</tr>
					<?php
									}
				 ?>
									<tr>
										<td colspan=2 class="tdcamposc"></td>
										<td  class="tdcamposc" style="text-align:right;">Total Crédito:</td>
										<td  class="tdcamposc"><?php echo $totalCredito;?></td>
									</tr>

				<?php
				}
					$facturaTotal=($total+$totalDebito)-$totalCredito;
				?>
				<tr>
					<td colspan=2 class="tdcamposc">
						(Factura Total + Total Debito)- Total Credito<br>
						<?php echo "( $total + $totalDebito ) - $totalCredito";?>
					</td>
					<td  class="tdcamposc" style="text-align:right;">Total Factura:</td>
					<td  class="tdcamposc"><?php echo $facturaTotal;?></td>
				</tr>
			</table>
		</td>
		</tr>
		<?php }?>

 <!-- //////////////////////DATOS PARA CREAR LA NOTA DE DEBITO/////////////////-->
			<tr>
				<td colspan=4  class="titulo_seccion"> Datos de nota de débito</td>
			</tr>
		<tr>
			<td  class="tdtitulos">No. de Nota de Débito</td>

			<td  class="tdcampos">
				<input type="hidden" id="id_factura" name="id_factura" value="<?php echo $f_factura[id_factura]; ?>">
				<input type="hidden" id="num_notadebito" name="num_notadebito" value="<?php echo $no_notadebito; ?>"><?php echo $no_notadebito; ?></td>
			<td class="tdtitulos">Serie</td>
			<td class="tdcampos"><input type="hidden" id="serie" name="serie" value="<?php echo $f_factura[id_serie]; ?>"><?php echo $f_factura[nomenclatura]; ?></td>
		</tr>
		<tr>
			<td  class="tdtitulos">No. de Control Factura</td>
			<td  class="tdcampos">Ultimo numero de control serie:<span class="tdcamposr"><?php echo $ultimoNumControl;?></span><br>
				<input class="campos" size="8" type="hidden" id="UltimoControlFactura" name="UltimoControlFactura" value="<?php echo $ultimoNumControl?>">
				<input class="campos" size="8" type="text" id="controlfactura" name="controlfactura" value="" onkeydown="return soloMoneda(event,this)">
			</td>
			<td  class="tdtitulos">* Fecha Emision</td>
			<td class="tdcampos">
		 		<input  type="text" size="8" id="dateField3" name="fechar" class="campos" maxlength="10" value="<?php echo $fecha_creado?>" onKeyPress="return fechasformato(event,this,1);">
	   	</td>
		</tr>
		<tr>
			<td class="tdtitulos"  colspan=1 align="left">Tipo de Nota</td>
			<td class="tdcampos" colspan=3 align="left">
				<select id="TipoNotadebito" name="TipoNotadebito" class="campos" style="width: 200px;"  >
					<option value="1" >Error en la emision de la factura</option>
					<option value="2" >Reajuste de monto</option>
					<option value="3" >Comisiones</option>
					<option value="4" >Otros Gastos</option>
				</select>
			</td>
		</tr>
		<tr>
				<td colspan=2 class="tdtitulos">CONCEPTO</td>
      	<td colspan=1 class="tdtitulos">MONTO</td>
      	<td colspan=1 class="tdtitulos">MONEDA</td>
		</tr>
		<tr>
			 	<td colspan=2 class="tdcampos"><textarea id="concepto" name="concepto" maxlength='250' cols=70 rows=4  style="text-transform: uppercase;" class="campos"><?php echo $f_factura[concepto]?></textarea></td>
      	<td colspan=1 class="tdcampos"><input type="text"  id="monto" onkeydown="return soloMoneda(event,this)" onblur="CambioNotaDebito(this.value,$F(CambioValor),'MontoTotal')" class="campos" size="7" name="monto" value="0"></td>
      	<td colspan=1 class="tdcampos">
							<?php
								//////////MONEDA EXPRESIONES////
								$SqlMoneda=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas ;");
								$MonedaEJ=ejecutar($SqlMoneda);
							?>
							<select id="moneda" name="moneda" class="campos" onchange="valor=$F(moneda).split('-'); $(CambioValor).value=valor[1];$('refCambio').innerHTML=valor[1];CambioNotaDebito($F(monto),$F(CambioValor),'MontoTotal')"  >
							<?php
								$monedaLocal='VES';
								while($Moneda = asignar_a($MonedaEJ)){
									$moneda=$Moneda['moneda'].'('.$Moneda['simbolo'].')';
									if($Moneda['id_moneda']=='1'){
										$monedaLocal=$moneda;
									}
									$VarloCambio=$Moneda['valor'];
									$id_moneda=$Moneda['id_moneda'];	?>
								<option value="<?php echo $id_moneda.'-'.$VarloCambio?>" ><?php echo $moneda?></option>

							<?php } $VarloCambio=1; ?>
							</select>
							<span id='refCambio'><?php echo $VarloCambio;?></span>
							<input type="hidden"  size="4" id="CambioValor" name="CambioValor" value='<?php echo $VarloCambio;?>' onChange="$('refCambio').innerHTML=$('CambioValor').value;" >
				</td>

		</tr>

		<tr>
				<td colspan=1 class="tdtitulos"></td>
      	<td colspan=1 class="tdcampos" style="text-align:right">TOTAL NOTA DE DÉBITO</td>
      	<td colspan=1 class="tdcampos"><input type="text"  readonly id="MontoTotal" class="campos" size="8" name="MontoTotal" value=""></td>
      	<td colspan=1 class="tdcampos">
					<?php echo $monedaLocal; ?>
				</td>

		</tr>
		<tr>
			<td class="tdtitulos"></td>
			<td class="tdtitulos"></td>
			<td class="tdtitulos"></td>
			<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="guardar_notadebito();" class="boton" title="Guardar La Nota de Credito">Guardar</a></td>
		</tr>
</table>

<?php }?>
