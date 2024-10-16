<?
include ("../../lib/jfunciones.php");
sesion();
$cedulaben = $_REQUEST['bencedula'];
$cualdiv = $_REQUEST['ndiv'];
// VALIDA QUE EL CAMPO CI NO ESTÉ VACÍO
if (strlen($cedulaben) > 0) {
	$buscoben = ("select * from clientes where clientes.cedula='$cedulaben'");
	$repbusben = ejecutar($buscoben);
	$siben = num_filas($repbusben);
	// VALIDA QUE LA CONSULTA HAYA TRAÍDO AL MENOS UNA FILA
	if ($siben >= 1) {
		?>
		<script>
			
		</script>
		<?
		$datadbenef = assoc_a($repbusben);
		$nomb1 = $datadbenef['nombres'];
		$apell1 = $datadbenef['apellidos'];
		$nacib1 = $datadbenef['fecha_nacimiento'];
		$genb1 = $datadbenef['sexo'];
		// CONVIERTO LA CADENA DE FECHA DE NACIMIENTO PARA CALCULAR LA EDAD
		$fecha_nacimiento_array = explode('-', $nacib1);
		// VALIDO SI EL ARREGLO FECHA TIENE 3 ÍNDICES (AÑO, MES Y DÍA)
		if (count($fecha_nacimiento_array) === 3) {
			list($year, $month, $day) = $fecha_nacimiento_array;
			// VERIFICO SI LA FECHA ES VÁLIDA
			if (checkdate($month, $day, $year)) {
				$fecha_actual = getdate();
				$edad = $fecha_actual['year'] - $year;
				if (($fecha_actual['mon'] < $month) || ($fecha_actual['mon'] == $month && $fecha_actual['mday'] < $day)) {
					$edad--;
				}
				// ACTUALIZO LA EDAD DEL CLIENTE
				$actualizoedad = ("update clientes set edad='$edad' where cedula='$cedulaben'");
				$repacted = ejecutar($actualizoedad);
			}
		}
		?>
		<label class="boton" style="cursor:pointer"
			onclick="verbenfcontrato('<? echo $nomb1 ?>','<? echo $apell1 ?>','<? echo $nacib1 ?>','<? echo $genb1 ?>','<? echo $cualdiv ?>')">V</label>
		<?
	}
}
?>