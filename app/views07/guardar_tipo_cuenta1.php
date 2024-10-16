<?php
/* Nombre del Archivo: guardar_tipo_cuenta1.php
   Descripción: Inserta un TIPO DE CUENTA modificada en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();
$id_cuenta = $_REQUEST['id_cuenta'];
$cuenta2= $_REQUEST['cuenta2'];
$cuenta2 = strtoupper($cuenta2);

$q_cuenta = "update tbl_tiposcuentas set id_tipocuenta='$id_cuenta',tipo_cuenta='$cuenta2' where tbl_tiposcuentas.id_tipocuenta='$id_cuenta'";
$r_cuenta = ejecutar($q_cuenta);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Tipo de Cuenta <?php echo $cuenta2;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_tipo_cuenta();" class="boton">Insertar o Modificar otro Tipo de Cuenta</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>
