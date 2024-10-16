<?php
/* Nombre del Archivo: guardar_sucursal.php
   Descripción: Inserta una SUCURSAL en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$subdivi1 = $_REQUEST['subdivi1'];
$subdivi1 = strtoupper($subdivi1);

$q_subdi = "insert into subdivisiones (subdivision) values('$subdivi1')";
$r_subdi = ejecutar($q_subdi);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Subdivision <?php echo $subdivi1;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_subdivision();" class="boton">Insertar otra Subdivision</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>
