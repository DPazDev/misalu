<?php
/* Nombre del Archivo: guardar_subdivision1.php
   Descripción: Inserta una SUBDIVISION modificada en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();
$id_subdi1 = $_REQUEST['id_subdi1'];
$subdi1 = $_REQUEST['subdi1'];
$subdi1 = strtoupper($subdi1);

$q_subdi = "update subdivisiones set id_subdivision='$id_subdi1',subdivision='$subdi1' where subdivisiones.id_subdivision='$id_subdi1'";
$r_subdi = ejecutar($q_subdi);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito la Subdivisión <?php echo $subdi1;?></td>	
</tr>	

	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reg_subdivision();" class="boton">Insertar o Modificar otra Subdivisión</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>
