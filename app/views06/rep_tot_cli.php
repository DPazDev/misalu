<?php
include ("../../lib/jfunciones.php");
sesion();

$q_tipo_ente= "select * from tbl_tipos_entes order by tipo_ente";
$r_tipo_ente = ejecutar($q_tipo_ente);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=4 class="titulo_seccion">Reporte que Muestra el Total Clientes por Entes </td>	</tr>	

	</tr>
	<tr> 
	  
	
	  <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(3)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@Todos los Tipos">Todos los Tipos</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
		
		 
		</tr>
</table>
<div id="bus_ent"></div>
