<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion"><?php echo "$f_admin[nombres] $f_admin[apellidos]"?>La navidad es ese niño que nace en nuestro interior, que motiva en nuestros corazones los sentimientos más nobles, y esa esperanza por un mañana mejor </td>	</tr>
<tr>		<td colspan=4 class="titulo_seccion"><img src="../public/images/8-gifs-navidad.gif" alt="" title=""></td>	</tr>	
	
</table>


</form>
