<?php
/* Nombre del Archivo: guardar_tarjeta1.php
   Descripción: GUARDA en la base de datos la TARJETA MODIFICADA, para utilizarlo posteriormente
*/

include("../../lib/jfunciones.php");
sesion();
$id_tar = $_REQUEST['id_tar'];
$tarje = $_REQUEST['tarje'];
$tarje = strtoupper($tarje);
$pago = $_REQUEST['pago'];

$q_tar = "update tbl_nombre_tarjetas set id_nom_tarjeta='$id_tar',nombre_tar='$tarje',id_tipo_pago='$pago' where tbl_nombre_tarjetas.id_nom_tarjeta='$id_tar'";
$r_tar = ejecutar($q_tar);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito la Tarjeta <?php echo $tarje;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_tarjeta();" class="boton">Insertar o Modificar otra Tarjeta</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>