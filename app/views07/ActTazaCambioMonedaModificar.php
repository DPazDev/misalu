<?php
header("Content-Type: text/html;charset=utf-8");
	include ("../../lib/jfunciones.php");
	sesion();
	$datetime = new DateTime('NOW');
//  echo "<br> fecha actual";
	$datetime->format('Y-m-d H:i:s') . "\n";
	$la_time = new DateTimeZone('America/Caracas');
	$datetime->setTimezone($la_time);
	$FechaServer=$datetime->format('Y-m-d');
	$HoraServer=$datetime->format('h:i:s');
	/////////////RECEPCION DE VARIABLES
	//Formulario ActTazaCambioMoneda(ActualizarTazaMoneda)
	$IdMoneda=$_POST['idmoneda'];
	$elid=$_SESSION['id_usuario_'.empresa];
	//-------------------Funcion ACTUALIZAR--------------------------///
	if (isset($_POST['modificar'])) {
		$fecha=$FechaServer;/////Fecha del servidor esta se declara en jfunciones.php
		$hora=$HoraServer;/////Fecha del servidor esta se declara en jfunciones.php

		/*** modificar los valores de la moneda solo si fue enviado con el formulario de FORMULARIO PARA MODIFICAR ***/
		$NCambioMoneda=$_POST['NuevoCambioMoneda'];
		if($NCambioMoneda<=0)
				{$mensaje="<span style='color: red'>EL CAMBIO DEBE SER MAYO A 0 (cero)</span>";}
		else{
				if($NCambioMoneda>0)
				$NCambioMoneda=number_format($NCambioMoneda, 2, '.', '');
				$sqlModificarMoneda=("INSERT INTO tbl_monedas_cambios (id_moneda,valor,fecha_cambio,hora_cambio,id_admin)
							VALUES ('$IdMoneda','$NCambioMoneda','$fecha','$hora','$elid')");
				$CambioMoneda=ejecutar($sqlModificarMoneda);
				$mensaje="ACTUALIZACION COMPLETA";
				$log="Actualizo Taza de Cambio de la moneda: $IdMoneda a $NCambioMoneda";
				logs($log,$ip,$elid);


		}
	?>



				<div id="IrVentanaFlotante" class="modal">
  					<div class="ventana">
    					<h2>Alerta</h2>
    					<p><?php echo$mensaje;?></p>
    					<a class='boton_modal' href="#IrVentanaFlotante" style='text-decoration:none;'> ACEPTAR	</a>
  					</div>
  				</div>
		<?php

			}
	//-------------------Funcion ACTUALIZAR--------------------------///


$sqlMonedasCambios=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas where tbl_monedas.id_moneda='$IdMoneda';");
$ModenasCambio=ejecutar($sqlMonedasCambios);
	//echo "<h1>$IdMoneda</h1>";
?>
<!--------------------FORMULARIO PARA MODIFICAR---------------------------->
<table   cellpadding=0 cellspacing=0>
<tr class="titulo_seccion">
	<th class="tdcamposc1">Nombre Moneda</th>
	<th class="tdcamposc1">Valor Actual</th>
	<th class="tdcamposc1">Valor Actual</th>
	<th class="tdcamposc1">ACCIONES</th>
</tr>
<?php while($MCambio=asignar_a($ModenasCambio,NULL,PGSQL_ASSOC)){
		//colocar 0 si es NULL
				if($MCambio[valor]=='' || $MCambio[valor]=='null' || $MCambio[valor]==NULL) {;
						$CambioActualValor=0;
					}
				else
				{$CambioActualValor=$MCambio[valor];}
				$IdMoneda=$MCambio[id_moneda];//idmoneda
				$NombreMoneda=strtoupper($MCambio[nombre_moneda]);
				$MonedaSimbolo=$MCambio[moneda].'/'.$MCambio[simbolo];
	 ?>
	<tr>
		<td class="tdcamposac1"><?php echo $NombreMoneda.'('.$MonedaSimbolo.')';?></td>
		<td class="tdcamposac1"><?php echo $CambioActualValor;?> <input type='hidden' id='CambioActual' name="CambioActual" value="<?php echo $CambioActualValor;?>" /></td>
		<td class="tdcamposac1"><input class='campos' type="text" id="NuevoCambio" name="NuevoCambio" value="" placeholder="<?php echo $CambioActualValor;?>" onkeyup="MostrarPocentajeTazaMoneda(this)" onkeydown="return soloMoneda(event,this)"></td>
		<td class="tdcamposac1"><a href="#" id="ModificarMoneda" OnClick="ModificarTazaMoneda(<?php echo $IdMoneda;?>,1),MuestraCambio()" class="boton">Modificar</a> </td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4" class="tdcamposac1">

			<br>
				<br>
				<br>
				<a href="#" OnClick="ActTazaCambio()" class="boton" >Regresar</a>
		</td>
 	</tr>
 </table>

<!--------------------FIN FORMULARIO PARA MODIFICAR---------------------------->
