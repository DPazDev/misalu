<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=utf-8');

?>
<script language="JavaScript">
function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}
</script>
<?php
$dateField3 = $_REQUEST['dateField3'];
$num_notacredito = $_REQUEST['num_notacredito'];
$concepto = $_REQUEST['concepto'];
$id_factura = $_REQUEST['id_factura'];
$controlfactura = $_REQUEST['controlfactura'];
$monto = $_REQUEST['monto'];
$estado_cre = $_REQUEST['estado_cre'];
$idserie = $_REQUEST['idserie'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$sqlVerNota="select tbl_facturas.id_factura,id_estado_factura,numero_factura,numcontrol,tipo_nota,num_nota,numcontrolnota,tbl_nota_factura.fecha_emision,estado_nota from tbl_facturas,tbl_nota_factura where tbl_facturas.id_factura=tbl_nota_factura.id_factura and  numcontrolnota='$controlfactura';";
$NotasExiste=ejecutar($sqlVerNota);
$NunRegNotas=num_filas($NotasExiste);
if($NunRegNotas>0)
{?>
  <table  class="tabla colortable" style="which:100%;min-width:100%;" border=0 cellpadding=0 cellspacing=0>
      <tr><th class="tdtitulos" colspan=5>Nunmero de Control:<?php echo $controlfactura;?> esta siendo usado:</th></tr>
      <tr>
        <td class="tdtitulos">Factura Afectada</td>
        <td class="tdtitulos">Tipo de Nota </td>
        <td class="tdtitulos">Num. de Nota</td>
        <td class="tdtitulos">Num. Control de Nota</td>
        <td class="tdtitulos">fecha Emision</td>
      </tr>
  <?php
    while($regExt = asignar_a($NotasExiste)){
        $NunFactura=$regExt['numero_factura'];
        $NunNota=$regExt['num_nota'];
        $TipoNota=$regExt['tipo_nota'];
        $NunControlNota=$regExt['numcontrolnota'];
        $FechaEmiNota=$regExt['fecha_emision'];
      if($TipoNota=='1')
      {$TNota='Crédito';}
      else
      {$TNota='Débito';}
    ?>
      <tr>
        <td class="tdcampos"><?php echo"$NunFactura";?></td>
        <td class="tdcampos"><?php echo"$TNota";?></td>
        <td class="tdcampos"><?php echo"$NunNota";?></td>
        <td class="tdcampos"><?php echo"$NunControlNota";?></td>
        <td class="tdcampos"><?php echo"$FechaEmiNota";?></td>
      </tr>
    <?php } ?>
  </table>
<?php
}
else{
/* **** registrar notas de creditos **** */
$r_nota_credito="insert into tbl_nota_factura (id_factura,
	 				       tipo_nota,
					       Concepto,
							   fecha_emision,
							   monto_nota,
							   num_nota,
							   numcontrolnota,
                 estado_nota,
                 comentario_nota,
                 tipo_registro_nota,
                 id_serie
							)
					values('$id_factura',
					       '1',
					       '$concepto',
							'$dateField3',
							'$monto',
							'$num_notacredito',
							'$controlfactura',
              '1',
              '',
              $estado_cre,
              '$idserie'
            );";

		$r_nota_credito=ejecutar($r_nota_credito);


		/* **** Se registra lo que hizo el usuario**** */

$log="REGISTRO la Nota de Credito numero $num_notacredito de la id factura $id_factura ";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */

?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
	<tr>
		<td align="right" colspan=4  class="titulo_seccion">Factura Generada
<?php $url="'views04/inotafactura.php?id_factura=$id_factura&num_nota=$num_notacredito','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Notas de Creditos"> Imprimir Nota Credito <?php echo "$num_notacredito" ?></a>
<?php $url="'views04/inotacreditogob.php?id_factura=$id_factura&num_nota=$num_notacredito','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Notas de Creditos Plan salud"> Imprimir Nota Credito Plan Salud <?php echo "$num_notacredito" ?></a>

</td>
	</tr>

	</table>

<?php
} //fin verificacion
?>
