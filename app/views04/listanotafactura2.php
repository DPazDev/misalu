<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=utf-8');
$fechaIni = $_POST['fechaIni'];
$fechaFin = $_POST['fechaFin'];
$TipoDeNota = $_POST['tiponota'];
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select tbl_series.*,admin.* from tbl_series,admin where admin.id_admin='$admin' and tbl_series.id_sucursal=admin.id_sucursal limit 1;");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$idserie=$f_admin['id_serie'];
$nomesclatura=$f_admin['nomenclatura'];
	if($TipoDeNota==1){
		$TipoNotaSql="and tbl_nota_factura.tipo_nota=1 ";
	}
	else if($TipoDeNota==2){
		$TipoNotaSql="and tbl_nota_factura.tipo_nota=2 ";
	}
	else{
		$TipoNotaSql="";
	}


$SQLnotasFacturas="select
								id_nota_factura,
								tbl_nota_factura.id_factura,
								tipo_nota,
								tbl_nota_factura.concepto,
								tbl_nota_factura.fecha_emision,
								monto_nota,
								num_nota,
								numcontrolnota,
								estado_nota,
								comentario_nota,
								numero_factura,
								numcontrol
						from
								tbl_nota_factura,tbl_facturas
						where
								tbl_nota_factura.fecha_emision BETWEEN '$fechaIni' and '$fechaFin' and
								tbl_facturas.id_factura=tbl_nota_factura.id_factura and
								tbl_facturas.id_serie=$idserie $TipoNotaSql
								order by tbl_nota_factura.fecha_emision desc,tbl_nota_factura.id_nota_factura;";
$NotasFacturas=ejecutar($SQLnotasFacturas);
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="listaNotas">
	<table class="tabla_cabecera3 colortable"  border=0 cellpadding=0 cellspacing=0>
		<!--BACECERAS--->
		<tr>

			<th class="titulo_seccion" colspan="8"><?php echo"NOTAS SERIE: $nomesclatura";?></th>

		</tr>
		<tr>
			<th class="tdtitulos">TIPO NOTA</th>
			<th class="tdtitulos">FACTURA(N. CONTROL)</th>
			<th class="tdtitulos">NUMERO NOTA</th>
			<th class="tdtitulos">NUM. COMTROL</th>
			<th class="tdtitulos">FECHA EMISION</th>
			<th class="tdtitulos" colspan="2">CONCEPTO</th>
			<th class="tdtitulos">MONTO</th>
		</tr>
		<!--DATA --->
		<?php
		$totalNotaC=0;
		$totalNotaD=0;
		while($notaFac = asignar_a($NotasFacturas)){
			$tipoNota=	$notaFac['tipo_nota'];
			$factura=$notaFac['numero_factura'];
			$controlFactura=$notaFac['numcontrol'];
			$NumNota=$notaFac['num_nota'];
			$NontrolNota=$notaFac['numcontrolnota'];
			$FechaEmiNota=$notaFac['fecha_emision'];
			$concepto=$notaFac['concepto'];
			$monto=Formato_Numeros($notaFac['monto_nota']);
			if($tipoNota==1)
				{$totalNotaC=Formato_Numeros($totalNotaC+$monto);
					$NotaTipo='CRÉDITO';
					$colorCell='color:red;';
				}
			else if($tipoNota==2)
				{$totalNotaD=$totalNotaD+$monto;
					$NotaTipo='DÉBITO';
					$colorCell='color:green;';
				}
			else
				$NotaTipo='no identificada';

			////FORMATO NUM FACTURA/NOTA Factura
			$numNotaFat=(int) $NumNota;///Trasformar a entero
			if($numNotaFat<10)
			{$numNotaFat='000'.$numNotaFat;}
			else if($numNotaFat>=10 && $numNotaFat<100)
			{$numNotaFat='00'.$numNotaFat;}
			else if($numNotaFat>=100 && $numNotaFat<1000)
			{$numNotaFat='0'.$numNotaFat;}
			else{$numNotaFat=$numNotaFat;}

			?>
			<tr>
				<td class="tdcampos"><?php echo $NotaTipo; ?></td>
				<td class="tdcampos"><?php echo "$factura ($controlFactura)"; ?></td>
				<td class="tdcampos"><a href="#" OnClick="verNotaFacturaLista('<?php echo $tipoNota;?>','<?php echo $numNotaFat;?>');" class=""><?php echo "$numNotaFat"; ?></a></td>
				<td class="tdcampos"><?php echo "$NontrolNota"; ?></td>
				<td class="tdcampos"><?php echo "$FechaEmiNota"; ?></td>
				<td class="tdcampos" colspan="2" style="text-transform:uppercase;"><?php echo "$concepto"; ?></td>
				<td class="tdcampos"><?php echo "<span style='$colorCell'>$monto</span>";?></td>

			</tr>
	<?php
		}
		///TOTALES NOTAS CREDITO
		if($TipoDeNota==1 ||  $TipoDeNota==0){
	?>
			<tr>
				<td colspan="5"></td>
				<td class="tdtitulos" style="padding-right: 5px;height: 30px;text-align: right;">TOTAL DE NOTAS DE CÉDITO </td>
				<td class="tdtitulos" style="padding-right: 5px;height: 30px;text-align: right; color:red;"><?php echo "$totalNotaC";?></td>
				<td></td>
			</tr>
		<?php
	 	}
		if($TipoDeNota==2 ||  $TipoDeNota==0){
		?>
			<tr>
				<td colspan="5"></td>
				<td  class="tdtitulos" style="padding-right: 5px;height: 30px;text-align: right;">TOTAL DE NOTAS DE DÉBITO </td>
				<td></td>
				<td class="tdtitulos" style="padding-right: 5px;height: 30px;text-align: right; color:green;"><?php echo "$totalNotaD";?></td>
			</tr>
		<?php
			}
		?>
	</table>
</form>
