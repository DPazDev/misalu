<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$id_admin= $_SESSION['id_usuario_'.empresa];
?>
<script language="JavaScript">

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}
</script>
<?php
$id_notacredito = $_REQUEST['id_notacredito'];
$controlfactura = $_REQUEST['controlfactura'];
$dateField3 = $_REQUEST['dateField3'];
$estado_fac = $_REQUEST['estado_fac'];
$concepto = $_REQUEST['concepto'];
$id_fact_nueva = $_REQUEST['id_fact_nueva'];
$monto = $_REQUEST['monto'];
$factura = $_REQUEST['factura'];

$fecha_emision=$dateField3;
//busco si ya existe esa factura en la bd.
$mod_notacredito="update tbl_notacredito set  
							id_factura='$id_fact_nueva',
							concepto='$concepto',
					        fecha_creado='$dateField3',
					        montonc='$monto',
							edo_notacredito='$estado_fac',
							numcontrolnc='$controlfactura'
where tbl_notacredito.id_notacredito=$id_notacredito		      
";
	$fmod_notacredito=ejecutar($mod_notacredito);
	
	
		/* **** Se registra lo que hizo el usuario**** */

$log="Actualizo la Nota de Credito con id_factura $id_fact_nueva";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
		
?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
	<tr>
		<td align="right" colspan=4  class="titulo_seccion">Nota de Credito Generada 
<?php $url="'views04/inotacredito.php?id_factura=$id_fact_nueva&num_notacredito=$factura','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Notas de Creditos"> Imprimir Nota Credito <?php echo "$factura" ?></a>

</td> 
	</tr>
	
	</table>
