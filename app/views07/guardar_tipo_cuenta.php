<?php
/* Nombre del Archivo: guardar_tipo_cuenta.php
   Descripción: Inserta un TIPO DE CUENTA en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$cuenta1 = $_REQUEST['cuenta1'];
$cuenta1 = strtoupper($cuenta1);

$q_cuenta = "insert into tbl_tiposcuentas (tipo_cuenta) values('$cuenta1')";
$r_cuenta = ejecutar($q_cuenta);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Tipo de Cuenta <?php echo $cuenta1;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_tipo_cuenta();" class="boton">Insertar otro Tipo de Cuenta</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>
