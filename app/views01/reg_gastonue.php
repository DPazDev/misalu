<?php
include ("../../lib/jfunciones.php");
sesion();
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="gastnuev">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Gastos Nuevos del Cliente</td>	</tr>	
	<tr>
		<td class="tdtitulos">* C&eacute;dula</td>
		<td class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=128 size=20 value=""   ></td>
	
		<td  class="tdtitulos">* Seleccione  el Servicio.</td>
<td  class="tdcampos">
		<select name="servicio" class="campos" >
		<?php $q_servicio=("select * from servicios order by servicio");
		$r_servicio=ejecutar($q_servicio);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_servicio[id_servicio]?>"> <?php echo "$f_servicio[servicio]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="reg_gastonue2();" class="boton">Siguiente</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		</tr>
</table>
	<div id="reg_gastonue2"></div>

</form>
