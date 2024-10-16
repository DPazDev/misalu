<?php

/* Nombre del Archivo: modificar_pais.php
   Descripción: Solicita los datos para MODIFICAR un PAIS en la base de datos
*/

include("../../lib/jfunciones.php");
sesion();

$id_pais1 = $_REQUEST['pais0'];

/*echo "******************* <br>";
echo $id_pais1."<br>"; 
echo "******************* <br>";*/

$q_pais = "select pais.pais from pais where pais.id_pais='$id_pais1';";
$r_pais = ejecutar($q_pais);
$f_pais = asignar_a($r_pais);

?>
<script>

</script>
<form method="POST" action="guardar_pais1.php" name="paiss">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Pa&iacute;s</td>	</tr>		
	<tr> <td>&nbsp;</td>
	<tr>

		<td class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nuevo Nombre del Pa&iacute;s</td>

		<td class="tdcampos"><input class="campos" type="text" name="pais_nuevo" maxlength=128 size=20 value="<?php echo $f_pais['pais']; ?>"></td>
		<input type="hidden" name="id_pais2" value="<?php echo $id_pais1;?>">


		<td class="tdtitulos"></td>

		<td class="tdcampos"><a href="#" OnClick="guardar_pais1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr> <td>&nbsp;</td>
</table>

</form>
										
