<?php

/* Nombre del Archivo: modificar_estado.php
   Descripción: Solicita los datos para MODIFICAR un ESTADO en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_estado1 = $_REQUEST['estado0'];

/*echo "******************* <br>";
echo $id_estado1."<br>"; 
echo "******************* <br>";*/

$q_estado = "select estados.estado,estados.id_pais from estados where estados.id_estado='$id_estado1';";
$r_estado = ejecutar($q_estado);
$f_estado = asignar_a($r_estado);

?>
<script>

</script>
<form method="POST" action="guardar_estado1.php" name="estadoo">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Estado</td>	</tr>		
	<tr> <td>&nbsp;</td>
	<tr>

		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nuevo Nombre del Estado</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="estado_nuevo" maxlength=128 size=20 value="<?php echo $f_estado['estado']; ?>"></td>
		<input type="hidden" name="id_estado2" value="<?php echo $id_estado1;?>">
		<input type="hidden" name="id_pais2" value="<?php echo $f_estado['id_pais'];?>">
		<td colspan=1 class="tdcampos"><a href="#" OnClick="guardar_estado1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr> <td>&nbsp;</td>
</table>

</form>
										
