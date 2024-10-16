<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
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
$id_factura = $_REQUEST['id_factura'];
$id_serie = $_REQUEST['idserie'];
$FechaNota = $_REQUEST['dateField3'];
$concepto = $_REQUEST['concepto'];
$controlfactura = $_REQUEST['controlfactura'];
$nun_notaD = $_REQUEST['num_notadebito'];
$monto = $_REQUEST['monto'];
$TipoNotadebito = $_REQUEST['tipo_debito'];
$id_admin= $_SESSION['id_usuario_'.empresa];

// **** registrar notas de creditos ****
$r_nota_debito="insert into tbl_nota_factura (id_factura,
	 				       tipo_nota,
					       Concepto,
							   fecha_emision,
							   monto_nota,
							   num_nota,
							   numcontrolnota,
                 estado_nota,
                 comentario_nota,
                 tipo_registro_nota,id_serie
							)
					values('$id_factura',
					       '2',
					       '$concepto',
							'$FechaNota',
							'$monto',
							'$nun_notaD',
							'$controlfactura',
							'1',
              'COMENTARIO',
              $TipoNotadebito,
              $id_serie) RETURNING id_nota_factura;";
$r_nota_debito=ejecutar($r_nota_debito);
$ultNumC=asignar_a($r_nota_debito);


		// **** Se registra lo que hizo el usuario**** //
if($ultNumC['id_nota_factura']>0){
      $log="REGISTRO la Nota de Debito numero $nun_notaD de la id factura $id_factura ";
      logs($log,$ip,$id_admin);

//**** Fin de lo que hizo el usuario **** //

?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
	<tr>
		<td align="right" colspan=4  class="titulo_seccion">Factura Generada
<?php $url="'views04/inotafactura.php?id_factura=$id_factura&num_nota=$nun_notaD','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Notas de Débito"> Imprimir Nota Débito <?php echo "$nun_notaD" ?></a>
<?php $url="'views04/inotacreditogob.php?id_factura=$id_factura&num_notacredito=$nun_notaD','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Notas de Creditos Plan salud"> Imprimir Nota Credito Plan Salud <?php echo "$nun_notaD" ?></a>

</td>
	</tr>

	</table>
<?php }else{?>

  <table border=0 cellpadding=0 cellspacing=2 width="100%">
    <tr>
      <td class="titulo_seccion">HA OCURRIDO UN ERRROR </td>
    </tr>
  </table>




<?php }?>
