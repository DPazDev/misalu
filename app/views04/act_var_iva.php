<?php
include ("../../lib/jfunciones.php");
sesion();
$id_variable=$_REQUEST['id_variable'];
$variable=$_REQUEST['variable'];
$mod_variable="update variables_globales set cantidad='$variable' where variables_globales.id_variable_global=$id_variable";
$fmod_variable=ejecutar($mod_variable);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		
<td colspan=5 class="titulo_seccion">Se ha Actualizado la Variable IVA</td>	
</tr>

</table>
