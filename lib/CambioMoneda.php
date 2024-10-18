<?php
header("Content-Type: text/html;charset=utf-8");
include ("jfunciones.php");
$sqlMonedasCambios=("select tbl_monedas.id_moneda,moneda,nombre_moneda,simbolo, (select valor from tbl_monedas_cambios where  tbl_monedas.id_moneda=tbl_monedas_cambios.id_moneda order by id_moneda_cambio desc,fecha_cambio desc limit 1 ) as valor from tbl_monedas where tbl_monedas.id_moneda='2';");
$ModenasCambio=ejecutar($sqlMonedasCambios);
$MCambio=asignar_a($ModenasCambio,NULL,PGSQL_ASSOC);
			//colocar 0 si es NULL
				if($MCambio[valor]=='' || $MCambio[valor]=='null' || $MCambio[valor]==NULL) {;
						$CambioValor=0;
					}
				else
				{$CambioValor=$MCambio[valor];}
      	$NombreMoneda=strtoupper($MCambio[nombre_moneda]);
				$MonedaSimbolo=$MCambio[moneda].'/'.$MCambio[simbolo];
$hora=date('h:i:s');
//TASA del día1
$_SESSION['valorcambiario']=$CambioValor;
$CambioValor=$_SESSION['valorcambiario'];
echo " Tasa de día: $MonedaSimbolo $CambioValor";
echo"<input type='hidden' name='TasaCambio' id='TasaCambio' value='$CambioValor'>";
?>
<?php pg_close($con);?>
