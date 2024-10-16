<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
?>
<script language="JavaScript">

function ventana2(url,ancho,alto){
        window.open(url,'window','scrollbars=1,width='+ancho+',height='+alto);
}
</script>
<?php

$no_factura = intval($_REQUEST['factura']);
$codigosap= $_REQUEST['codigosap'];
$estado_fac = $_REQUEST['estado_fac'];
$serie = $_REQUEST['serie'];

$comen_fact = $_REQUEST['comen_fact'];
$controlfactura = $_REQUEST['controlfactura'];
//busco si ya existe esa factura en la bd.
$mod_factura="update
									tbl_facturas
							set
									id_estado_factura='$estado_fac',
									numcontrol='$controlfactura',
									comen_fact='$comen_fact',
									codigosap='$codigosap'
						where
            CAST (tbl_facturas.numero_factura as integer)='$no_factura'	and
									tbl_facturas.id_serie='$serie'
";
	$fmod_factura=ejecutar($mod_factura);



		/* **** Se registra lo que hizo el usuario**** */

$log="Actualizo el estado de la Factura numero $no_factura al estado $estado_fac
";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */

?>

     <table border=0 cellpadding=0 cellspacing=2 width="100%">
<tr><td colspan=4  class="titulo_seccion"> Datos de la Factura
<?php $url="'views04/ifactura.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Cliente"> Formato 1     <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/ifactura2.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Factura con los Datos del Ente si no Escogio el Ente al Registrarla "> Formato 2    <?php echo "$no_factura Serie $serie" ?></a>



<?php $url="'views04/irelacion.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Relacion de Gastos de la Factura"> Imprimir Relacion</a>

</td>
	</tr>
    <tr>
		<td align="right" colspan=4  class="titulo_seccion">Formatos Gobernacion
<?php $url="'views04/ifacturagob.php?factura=$no_factura&serie=$serie','700','500'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura"> Formato 1 <?php echo "$no_factura Serie $serie" ?></a>
<?php $url="'views04/ifacturagob2.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura Formato Clinica">  Formato 2</a>

<?php $url="'views04/ifacturagob3.php?factura=$no_factura&serie=$serie','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Imprimir Factura Formato Clinica cuentas por tercero">  Formato   3</a>

<?php $url="'views04/irelafacturagob.php?factura=$no_factura&serie=$serie&servicios=$servicios','700','500'";
			?>
<a href="javascript: imprimir(<?php echo $url; ?>);" class="boton" title="Relacion de Factura"> Relacion  Gobernacion<?php echo "$no_factura Serie $serie" ?></a>

</td>
	</tr>


	</table>
