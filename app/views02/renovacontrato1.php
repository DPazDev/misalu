<?php
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$permrenova=("select count(permisos.id_modulo) from admin,permisos
where
admin.id_admin=permisos.id_admin and
permisos.id_modulo=13 and
admin.id_admin=$elid");
$repperenova=ejecutar($permrenova);
$datrerenova=assoc_a($repperenova);
$puedcambiar=$datrerenova['count'];
$lacedut=$_REQUEST['titucedu'];


$bustitu=("SELECT
			clientes.nombres,
			clientes.apellidos,
			clientes.cedula,
			titulares.id_titular,
			tbl_contratos_entes.id_ente,
			tbl_contratos_entes.numero_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.fecha_creado,
			tbl_recibo_contrato.id_comisionado,
			tbl_recibo_contrato.direccion_cobro,
			tbl_recibo_contrato.conaumento
		FROM
			clientes,
			titulares,
			tbl_caract_recibo_prima,
			tbl_recibo_contrato,
			tbl_contratos_entes
		WHERE
			tbl_contratos_entes.estado_contrato = '1' AND
			clientes.id_cliente = titulares.id_cliente AND
			titulares.id_titular = tbl_caract_recibo_prima.id_titular AND
			tbl_caract_recibo_prima.id_recibo_contrato = tbl_recibo_contrato.id_recibo_contrato AND
			tbl_recibo_contrato.id_contrato_ente = tbl_contratos_entes.id_contrato_ente AND
			clientes.cedula = '$lacedut'
		GROUP BY
			clientes.nombres,
			clientes.apellidos,
			clientes.cedula,
			titulares.id_titular,
			tbl_contratos_entes.id_ente,
			tbl_contratos_entes.numero_contrato,
			tbl_recibo_contrato.num_recibo_prima,
			tbl_recibo_contrato.id_recibo_contrato,
			tbl_recibo_contrato.fecha_creado,
			tbl_recibo_contrato.id_comisionado,
			tbl_recibo_contrato.direccion_cobro,
			tbl_recibo_contrato.conaumento
		ORDER BY
			tbl_contratos_entes.id_ente,
			/*tbl_recibo_contrato.fecha_creado DESC*/
			id_recibo_contrato DESC;");

$sibeni=0;
$repbustitu=ejecutar($bustitu);
$repbustitu1=ejecutar($bustitu);
$cuanbustitu=num_filas($repbustitu);

$laspolizas=("select polizas.id_poliza,polizas.nombre_poliza from polizas where polizas.activar=1 order by polizas.nombre_poliza");

if($cuanbustitu==0){?>
	<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
		<tr>
			<td colspan=4 class="titulo_seccion">No existe ning&uacute;n titular con la c&eacute;dula No.<?echo $lacedut?> !!</td>
		</tr>
	</table>
	<?php
}else{
	$datitular=assoc_a($repbustitu);
	$nombreclien="$datitular[nombres] $datitular[apellidos]";?>

	<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
		<tr>
			<td colspan=4 class="titulo_seccion">Titular (<?echo $nombreclien?>)</td>
		</tr>
	</table>
	<?php
	$beni=0;
	$etitu=0;
	$ilumina=1;
	$aputente1=1;
	$elinicio=0;
	$control=0;
	$elselecpoli=0;
	$aument=1;
	$pd=1;
	
	while($contrato=asignar_a($repbustitu1,NULL,PGSQL_ASSOC)){

		$elselecpoli++ ;
		$posibleaumento=$contrato[conaumento];
		$elaumento="cajaaumento$aument";
		$aument++;
		$enteesid=$contrato[id_ente];
		$aputente2=$enteesid;

		if ($aputente2<>$aputente1) {
			
			$replapoliza="polizaes$ilumina";
			$replapoliza=ejecutar($laspolizas);

			$aputente1=$aputente2;

			$buscolapolizaq=("SELECT
								polizas.id_poliza,polizas.nombre_poliza
							FROM
								polizas,polizas_entes
							WHERE
								polizas_entes.id_ente=$enteesid AND
								polizas_entes.id_poliza=polizas.id_poliza");
			$reppolaes=ejecutar($buscolapolizaq);
			$ladapoliza=assoc_a($reppolaes);
			$idpolizaqtiene=$ladapoliza[id_poliza];
			$nompolizaqtiene=$ladapoliza[nombre_poliza];

			$idtitu=$contrato[id_titular];
			//Busco si esta anulado el contrato.
			$estadotitu="SELECT
					estados_t_b.id_estado_cliente
				FROM
					estados_t_b
				WHERE
					estados_t_b.id_titular=$idtitu AND
					estados_t_b.id_beneficiario=0;";
			$repestadotitu=ejecutar($estadotitu);
			$datresputitu=assoc_a($repestadotitu);
			$comoestatitu=$datresputitu[id_estado_cliente];

			$contratoidnum=$contrato[id_recibo_contrato];

			$buscdatitu=("SELECT
								polizas.id_poliza,
								polizas.nombre_poliza,
								tbl_caract_recibo_prima.id_titular,
								entes.id_ente,
								tbl_caract_recibo_prima.id_beneficiario,
								tbl_caract_recibo_prima.monto_prima,
								tbl_caract_recibo_prima.id_prima,
								tbl_recibo_contrato.id_recibo_contrato,
								entes.nombre,
								entes.fecha_inicio_contrato,
								entes.fecha_renovacion_contrato,
								tbl_recibo_contrato.poraumento,
								tbl_recibo_contrato.id_comisionado
							FROM
								tbl_caract_recibo_prima,
								polizas,
								primas,
								tbl_recibo_contrato,
								entes,
								titulares
							WHERE
								tbl_caract_recibo_prima.id_titular = $idtitu AND
								tbl_caract_recibo_prima.id_prima = primas.id_prima AND
								primas.id_poliza = polizas.id_poliza AND
								tbl_caract_recibo_prima.id_recibo_contrato = tbl_recibo_contrato.id_recibo_contrato AND
								tbl_caract_recibo_prima.id_titular = titulares.id_titular AND
								titulares.id_ente = entes.id_ente
							ORDER BY
								tbl_recibo_contrato.id_recibo_contrato DESC,
								polizas.nombre_poliza;");
		$repbusdatitu=ejecutar($buscdatitu);
		$cuantoshay=num_filas($repbusdatitu);
		
		$estomador="SELECT
				count(coberturas_t_b.id_titular)
			FROM
				coberturas_t_b
			WHERE
				coberturas_t_b.id_titular=$idtitu AND
				coberturas_t_b.id_beneficiario=0";
		$repestomador=ejecutar($estomador);
		$datodeltomador=assoc_a($repestomador);
		$cuantomodador=$datodeltomador[count];

		if($cuantomodador==0){
			$menstomador="(-Tomador-)";
		}else{
			$menstomador="";
		}

		?>

		<table class="tabla_citas"  cellpadding=0 cellspacing=0>
			<tr>
				<th class="tdtitulos">Ente.</th>
				<th class="tdtitulos">Poliza.</th>
				<th class="tdtitulos">Titular <?echo $menstomador?>.</th>
				<th class="tdtitulos">Beneficiario.</th>
				<th class="tdtitulos">Deuda.</th>
				<th class="tdtitulos">Prima Actual.</th>

				<?
				if($posibleaumento=='0'){
					?>
					<th class="tdtitulos">Prima.</th>
				<?}	else {?>
					<th class="tdtitulos">Nueva Prima.</th>
				<?}	if($puedcambiar>0){?>
					<th class="tdtitulos">Renovar.</th>
				<?}?>
			</tr>
		<?
			$acumcont=0;
			$acumnuevprima=0;
			$apuncon1=1;
			$cuantaspol=0;
			$ncrt=1;
			$elfin=1;
			$clientes = [];

		while($infocontrato=asignar_a($repbusdatitu,NULL,PGSQL_ASSOC)){

			if (in_array($infocontrato[id_beneficiario], $clientes)) {
				continue;
			}
			array_push($clientes, $infocontrato[id_beneficiario]);
			$ndata=$elselecpoli;
			//$ndata:numero lista ente $ncrt: numero de control de repetido
			$contodiv="ladata".$ndata."".$ncrt;
			$divprin="principal$elselecpoli";
			$conaumento="conaumento$pd";
			$feinicon="inicioc$pd";
			$ferecon="culmico$pd";
			$comisiocon="comisionado$pd";
			$direccioncobro="direccioncobro$pd";
			$quies="datacont$pd";
			$selecpoli="lapoliza$elselecpoli";
			$estitu=$infocontrato[id_titular];
			$esbeni=$infocontrato[id_beneficiario];
			$laiddelaprima=$infocontrato[id_prima];
			$busmontoprima=("select primas.anual from primas where id_prima=$laiddelaprima");
			$repbusmontoprima=ejecutar($busmontoprima);
			$ladamontoprima=assoc_a($repbusmontoprima);
			$elmontodelaprima=$ladamontoprima['anual'];
			//echo "Titular-----($estitu)- Beneficiario------------($esbeni)<br>";
			$fe1=$infocontrato[fecha_inicio_contrato];
			$fe2=$infocontrato[fecha_renovacion_contrato];
			$poraumento=$infocontrato[poraumento];
			$laprimees=$infocontrato[id_prima];
			$recibocontrato=$infocontrato[id_recibo_contrato];
			$elentees=$infocontrato[id_ente];
			$laiddepoliza=$infocontrato[id_poliza];
			$apuncon2=$recibocontrato;
			//echo "$apuncon2>=$apuncon1";

			if($apuncon2>=$apuncon1){
				$apuncon1=$apuncon2;
				$cuantaspol++;//cuantas ha encontrado
			}else{break;}
			
			/* **** sumo el monto total de la prima**** */
			$q_buscon_caract= "select
									sum(monto_prima)
								from
									tbl_caract_recibo_prima
								where
									tbl_caract_recibo_prima.id_recibo_contrato=$recibocontrato";
			$r_buscon_caract = ejecutar($q_buscon_caract);
			$f_buscon_caract = asignar_a($r_buscon_caract);

			/* **** sumo el monto total de los pagos de los recibo de prima**** */
			$q_buscon_pagos= "select 
									sum(monto)
								from
									tbl_recibo_pago
								where
									tbl_recibo_pago.id_recibo_contrato=$recibocontrato";
			$r_buscon_pagos = ejecutar($q_buscon_pagos);
			$f_buscon_pagos = asignar_a($r_buscon_pagos);

			$ladeuda=number_format($f_buscon_caract[sum]-$f_buscon_pagos[sum],2,',','');

			if(($estitu>1) && ($esbeni==0)){
				$elnotitu=("select
								clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento
							from
								clientes,titulares
							where
								clientes.id_cliente=titulares.id_cliente and
								titulares.id_titular=$estitu");
						//echo "--Titular-- $elnotitu";
				$repelnotitu=ejecutar($elnotitu);
				$datacompltitu=assoc_a($repelnotitu);
				$nomcompletodeltitu="$datacompltitu[nombres] $datacompltitu[apellidos]";
				$nomcompletodelbeni="";
				$edadtitu=calcular_edad($datacompltitu[fecha_nacimiento]);
				$etitu++;
				$quiestitular="datacontitu$etitu";
				$soloti=1;

				
			} else { //beneficiarios

				$elnotitu=("SELECT
								clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento
							FROM
								clientes,titulares
							WHERE
								clientes.id_cliente=titulares.id_cliente and
								titulares.id_titular=$estitu");
				$repelnotitu=ejecutar($elnotitu);
				$datacompltitu=assoc_a($repelnotitu);
				$nomcompletodeltitu="$datacompltitu[nombres] $datacompltitu[apellidos]";
				$elnobeni=("SELECT
								clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento,beneficiarios.id_parentesco
							FROM
								clientes,beneficiarios,estados_t_b
							WHERE
								clientes.id_cliente=beneficiarios.id_cliente and
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
								estados_t_b.id_estado_cliente=4 and
								beneficiarios.id_beneficiario=$esbeni");

				$repelnobeni=ejecutar($elnobeni);
				$datacomplbeni=assoc_a($repelnobeni);
				$nomcompletodelbeni="$datacomplbeni[nombres] $datacomplbeni[apellidos]";
				$edadbeni=calcular_edad($datacomplbeni[fecha_nacimiento]);
				//echo "$edadbeni";
				$elparentesco=$datacomplbeni[id_parentesco];
				$beni++;
				$quiesbenefi="dataconbenif$beni";
				$sibeni=1;

			}

			?>

			<tr>
				<td class="tdcampos"><?echo $infocontrato['nombre'];?></td>
				<td class="tdcampos"><?echo $infocontrato['nombre_poliza'];?></td>
				<td class="tdcampos"><?echo $nomcompletodeltitu;?></td>
				<td class="tdcampos"><?echo $nomcompletodelbeni;?></td>
				<td class="tdcampos"><?echo "($ladeuda)";?></td>
				<td class="tdcampos"><?echo "-$infocontrato[monto_prima]-"; $acumcont=$acumcont+$infocontrato['monto_prima'];?>
				</td>

				<td class="tdcampos">
					<?php
					if($posibleaumento=='0'){
						$nuevprima=$infocontrato['monto_prima'];
						echo "-$elmontodelaprima-";

					}else{
						$nuevprima=(($poraumento/100)*$infocontrato['monto_prima'])+$infocontrato['monto_prima'];
						$acumnuevprima=$acumnuevprima+$nuevprima;
						echo $nuevprima;

					}?>
				</td>

				<?php
				if($puedcambiar>0){
					if($etitu>=1){
						$elinicio++;

						if($control<>$estitu){
							$control=$estitu;
							$perla=$elinicio;
						}
						?>
						<td class="tdcampos">
							<input type="checkbox"  id="<?echo $contodiv?>"  value="<?php echo "$estitu-$esbeni-$edadtitu-$elparentesco-$nuevprima-$recibocontrato-$elentees"?>" checked>
						</td>
						<?php
					}else{
						?>
						<td class="tdcampos">
							<input type="checkbox"  id="<?echo $contodiv?>"  value="<?php echo "$estitu-$esbeni-$edadbeni-$elparentesco-$nuevprima-$recibocontrato-$elentees"?>" checked>
						</td>
						<?php
					}
				}
				?>
			</tr>
			<tr>
				<td class="tdcampos" colspan=7><HR></td>
			</tr>
			<?
			$pd = $pd + 1;
			
			$ncrt++;
			$ilumina++;
			$elfin++;
			$etitu=0;
       }

       	?>
     	<tr>
			<td colspan=1 class="tdtitulos"></td>
			<td colspan=1 class="tdtitulos"></td>
			<td colspan=1 class="tdtitulos"></td>
			<td colspan=1 class="tdtitulos"></td>
			<td colspan=1 class="tdtitulos">Monto Prima:</td>
			<td colspan=1 class="tdtitulos"><?echo $acumcont?></td>
			<td colspan=1 class="tdtitulos"><?echo $acumnuevprima ?></td>
	 	</tr>

		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td colspan=1 class="tdtitulos negrita">No. Contrato:</td>
			<td colspan=2 class="tdtitulos"><?echo $contrato[numero_contrato]?></td>
		</tr>
		<tr>
			<td><br></td>
	 	</tr>
		<tr>
			<td colspan=1 class="tdtitulos negrita">No. Recibo Prima:</td>
			<td colspan=2 class="tdtitulos"><?echo $contrato[num_recibo_prima]?></td>
		</tr>
		<tr>
			<td><br></td>
	 	</tr>

	 	<?php
		if($puedcambiar>0){

			$polizas = "SELECT
					polizas.id_poliza,polizas.nombre_poliza
				FROM
					polizas
				WHERE
					polizas.particular=1 AND 
					polizas.maternidad=0 AND 
					polizas.activar=1 $quepoli
				ORDER BY
					nombre_poliza;";

			$repPolizas = ejecutar($polizas);

			$comisionados = "SELECT
								comisionados.id_comisionado,
								comisionados.nombres,
								comisionados.apellidos
							FROM
								comisionados";
			$repBusComisionados = ejecutar($comisionados);

			?>

			<tr>
				<td colspan=1 class="tdtitulos negrita">Cambio de Póliza</td>

				<td colspan=2 class="tdtitulos">
					<select name="<?php echo $selecpoli ?>" id="<?php echo $selecpoli ?>">
						<?php
						while ($repPoliza = asignar_a($repPolizas,NULL,PGSQL_ASSOC)) {
							?>
							<option value="<?php
								echo $repPoliza[id_poliza]
							?>"
							<?php
								echo $repPoliza[id_poliza] == $idpolizaqtiene ? "selected" : ""
							?> > <?php echo $repPoliza[nombre_poliza] ?></option>
							<?php
						}
						?>
					</select>
				</td>


			</tr>

			<tr>
				<td><br></td>
			</tr>


     		<tr>
				<td colspan=1 class="tdtitulos negrita">Fecha Inicio Contrato:</td>

				<td colspan=2 class="tdtitulos">
					<input type='text' class="campos" id="<?echo $feinicon?>" size='12' value="<?echo $fe1?>">
					<a href="javascript:void(0);" onclick="g_Calendar.show(event, '<?echo $feinicon?>', 'yyyy-mm-dd')" title="Ver calendario">
						<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha">
					</a>
				</td>



				<td colspan=1 class="tdtitulos negrita">Fecha Fin de Contrato:</td>

				<td colspan=3 class="tdtitulos">
					<input type='text' class="campos" id="<?echo $ferecon?>" size='12' value="<?echo $fe2?>">
					<a href="javascript:void(0);" onclick="g_Calendar.show(event, '<?echo $ferecon?>', 'yyyy-mm-dd')" title="Ver calendario">
						<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha">
					</a>
				</td>


			</tr>

			<tr>
				<td><br></td>
			</tr>


			<tr>
				<td colspan=1 class="tdtitulos negrita">Cambio de Asesor</td>

				<td colspan=2 class="tdtitulos">
					<select name="<?php echo $comisiocon; ?>" id="<?php echo $comisiocon; ?>">
						
						<?php while ($comisionado = asignar_a($repBusComisionados, NULL, PGSQL_ASSOC)) : 
							$selected = ($comisionado['id_comisionado'] == $contrato['id_comisionado']) ? 'selected' : '';
							$nombreCompleto = $comisionado['nombres'] . ' ' . $comisionado['apellidos'];
						?>
							<option value="<?php echo $comisionado['id_comisionado']; ?>" <?php echo $selected; ?>>
								<?php echo $nombreCompleto; ?>
							</option>
						<?php endwhile; ?>
					</select>
				</td>

			</tr>

			<tr>
				<td><br></td>
			</tr>


			<tr>

				<td colspan=1 class="tdtitulos negrita">Dirección de Cobro</td>
				<td class="tdcampos" colspan="2">
					<textarea name="<?php echo $direccioncobro; ?>" id="<?php echo $direccioncobro; ?>" cols="54" class="campos"><?php
						if ($contrato[direccion_cobro] != '') {
							echo $contrato[direccion_cobro];
						} else {
							echo "Mérida";
						}
						?></textarea>

				</td>
				

			</tr>


	 		<?php
		} else {?>
			<tr>
				<td colspan=1 class="tdtitulos">Porcentaje de aumento:</td>
				<td colspan=1 class="tdtitulos"><input type='text' class="campos" id="<?echo $elaumento?>" size='15'></td>
			</tr>
			<tr>
				<br>
				<td colspan=1 class="tdtitulos">Con porcentaje de aumento:</td>
				<td class="tdcampos" colspan="1">
								<input type="radio" name="<?echo $conaumento?>" id="<?echo $conaumento?>" value="0" > No
								<input type="radio" name="<?echo $conaumento?>" id="<?echo $conaumento?>" value="1" checked>Si
				</td>
			</tr>

			<?php
	 	}?>
	 		<tr>
	  		 	<td><br></td>
	 		</tr>
		<?php
		if(($ladeuda==0) or ($comoestatitu==8)){
			if($puedcambiar>0){
		 		?>
     			<tr>
					<td>
						<?php
						$estoes=$ilumina-$cuantoshay;
						echo "  $estoes=$ilumina-$cuantoshay <br>";
						$yporfin=$estoes;
						$np=$cuantaspol;
						?>
						<label title="Procesar cambio" id="titularente" class="boton" style="cursor:pointer" onclick="RenovaContrato('<?echo $feinicon?>',
						'<?echo $ferecon?>',
						'<?echo $np?>',
						'<?echo $yporfin?>',
						'<?echo $ilumina?>',
						'<?echo $selecpoli?>',
						'<?echo $idpolizaqtiene?>',
						'<?echo $comisiocon?>',
						'<?echo $direccioncobro?>',
						'<?echo $contratoidnum?>',
						'<?echo $ndata?>')" >Procesar Cambio</label>
					</td>
    			</tr>
    
			<?php
			}
			?>
    		<tr>
	    		<td class="tdcampos" colspan=7><HR></td>
	 		</tr>
			<?php
		} else {?>
    		<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
				<tr>
					<td colspan=4 class="titulo_seccion">
						<label style="color: #ff0000"><h1>Contrato con deuda!!!</h1></label>
					</td>
				</tr>
   			</table>
	    	<?php
		}?>
		</table>
			
		<BR>
		<div id="<?echo $divprin?>"></div>

		<?$acumcont=0;
		$acumnuevprima=0;
		
		}
	}
}
 ?>
 <div align=center>
   <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />
 </div>
