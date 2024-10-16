<?php

/* Nombre del Archivo: modificar_organo.php
   Descripción: Solicita los datos para MODIFICAR un ORGANO en la base de datos
*/


include("../../lib/jfunciones.php");
sesion();

$id_organo1 = $_REQUEST['organo0'];

/*echo "******************* <br>";
echo $id_organo1."<br>"; 
echo "******************* <br>";*/

$q_organo = "select organo from organos where organos.id_organo='$id_organo1';";
$r_organo = ejecutar($q_organo);
$f_organo = asignar_a($r_organo);

?>
<script>

</script>
<form method="POST" action="guardar_organo1.php" name="organo">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Modificar Organo</td>	</tr>		

	<tr>

		<td colspan=1 class="tdtitulos">* Nuevo Nombre del Organo</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="organo_nuevo" maxlength=128 size=20 value="<?php echo $f_organo['organo']; ?>"></td>
		<input type="hidden" name="id_organo2" value="<?php echo $id_organo1;?>">

		<td colspan=2 class="tdcampos"><a href="#" OnClick="guardar_organo1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>


</table>

</form>
										
