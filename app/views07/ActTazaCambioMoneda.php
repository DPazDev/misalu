

<?php 
header("Content-Type: text/html;charset=utf-8");
	include ("../../lib/jfunciones.php");
	sesion(); 
///SQL CONSULATAR LAS MONEDADS//

$sqlMonedasCambios=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas;");
$ModenasCambio=ejecutar($sqlMonedasCambios);


?>

<table  class="colortable tabla_cabecera5" cellpadding=0 cellspacing=0>
<tr >
	<th class="tdcamposc1">Nombre Moneda</th>
	<th class="tdcamposc1">Moneda / Simbolo</th>
	<th class="tdcamposc1">Valor</th>
	<th class="tdcamposc1">ACCIONES</th>
</tr>
<?php while($MCambio=asignar_a($ModenasCambio,NULL,PGSQL_ASSOC)){
			//colocar 0 si es NULL
				if($MCambio[valor]=='' || $MCambio[valor]=='null' || $MCambio[valor]==NULL) {;
						$CambioValor=0;
					}	
				else 
				{$CambioValor=$MCambio[valor];}
				$IdMoneda=$MCambio[id_moneda];//idmoneda
				$NombreMoneda=strtoupper($MCambio[nombre_moneda]);
				$MonedaSimbolo=$MCambio[moneda].'/'.$MCambio[simbolo];
	 ?>
	<tr>
		<td class="tdcamposac1"><?php echo $NombreMoneda;?></td>
		<td class="tdcamposac1"><?php echo $MonedaSimbolo;?></td>
		<td class="tdcamposac1"><?php echo $CambioValor;?></td>
		<td class="tdcamposac1"><a href="#" OnClick="ActualizarTazaMoneda('<?php echo$IdMoneda;?>');" class="boton">Modificar</a>  </td>	
	</tr>
	<?php } ?>
</table>