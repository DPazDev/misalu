<?php
include ("../../lib/jfunciones.php");
sesion();

$q_recibo_pago=("select tbl_recibo_pago.id_serie,tbl_recibo_pago.numero_recibo,count(tbl_recibo_pago.numero_recibo) from tbl_recibo_pago where tbl_recibo_pago.id_serie=8 group by tbl_recibo_pago.id_serie,tbl_recibo_pago.numero_recibo order by tbl_recibo_pago.numero_recibo;");
$r_recibo_pago=ejecutar($q_recibo_pago);
?>


<form action="v_recibo_pago.php" method="POST" name="inventario_almacen" target="_blank">
<table border=1>
	<tr>		<td colspan=2 align="center" class="titulo_seccion">Auditar recibo pago</td>	</tr>
	
	<tr>
	<td align="left">Nro Recibo Prima</td>
		<td align="right">Serie en que estaba</td>
		<td align="right">Numero que tenia</td>
		<td align="left">id_recibo_pago</td>
		<td align="right">Serie en que quedo</td>
		<td align="right">numero con que quedo</td>
		
	</tr>	
	
		<?php
		$numero_recibo=34;
	while($f_recibo_pago=asignar_a($r_recibo_pago)){
		if ($f_recibo_pago[count]>1){
			
			$q_recibo_pago2=("select * from tbl_recibo_pago,tbl_recibo_contrato where tbl_recibo_pago.numero_recibo='$f_recibo_pago[numero_recibo]' and tbl_recibo_pago.id_serie='$f_recibo_pago[id_serie]' and tbl_recibo_contrato.id_recibo_contrato=tbl_recibo_pago.id_recibo_contrato ");
$r_recibo_pago2=ejecutar($q_recibo_pago2);
$i=0;
	while($f_recibo_pago2=asignar_a($r_recibo_pago2)){
		$i++;
		if ($i<$f_recibo_pago[count]){
				$numero_recibo++;
$num="00$numero_recibo";

			$q_r_pago = "update tbl_recibo_pago set id_serie=8,numero_recibo='$num' where tbl_recibo_pago.id_recibo_pago='$f_recibo_pago2[id_recibo_pago]' ";
$r_r_pago = ejecutar($q_r_pago);
			
	?>


	
	
	<tr>
	<td align="left"><?php echo $f_recibo_pago2[num_recibo_prima]?></td>
		<td align="right"><?php echo $f_recibo_pago2[id_serie]?></td>
		<td align="right"><?php echo $f_recibo_pago2[numero_recibo]?></td>
		<td align="left"><?php echo $f_recibo_pago2[id_recibo_pago]?></td>
		<td align="right">H</td>
		<td align="right"><?php echo $num?></td>
	</tr>	
	<?php 
	}
	}
	}
	}
	?>
</table>
</form>

