<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$fecha_pro = date("Y-m-d");
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="oa" id="oa">
<table class="tabla_cabecera4new"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo">
<table  class="tabla_cabecera5" >
	<?php
	
	if ($fecha_pro=="2014-09-17")
	{
	?>
	<tr>
 <td class="logo"><img src="../public/images/aniversario.png" alt="" title=""></td>
</tr>
<?php
}
?>
				</table>
<!--<img src="../public/images/TgC_Navidad_110.gif" alt="" title="">-->
</td>	</tr>	


</form>
