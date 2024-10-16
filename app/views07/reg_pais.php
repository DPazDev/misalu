<?php

/* Nombre del Archivo: reg_pais.php
   Descripción: Solicita los datos para INSERTAR o MODIFICAR un PAIS en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$q_pais0 = "select pais.id_pais,pais.pais from pais";
$r_pais0 = ejecutar($q_pais0);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>



<form action="guardar_pais.php" method="post" name="paiss">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



<tr>		<td colspan=4 class="titulo_seccion">Registrar o Modificar Pa&iacute;s</td>	</tr>	
	<tr> <td>&nbsp;</td>
	<tr>
                <td colspan=2 class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Verificar Nombre del País</td>
                <td colspan=1 ><select name="pais0" class="campos">
		<option value="">-- Seleccione el país --</option>
		<?php
		while($f_pais0 = asignar_a($r_pais0)){
			echo "<option value=".$f_pais0['id_pais'].">".$f_pais0['pais']."</option>";
		}
		?>
		</select>

		</td>
		<td colspan=1 class="tdcampos"><a href="#" OnClick="modificar_pais();" class="boton">Modificar</a>
		</td>

        </tr>



	<tr>
	<tr> <td>&nbsp;</td>
	</tr>

	<tr>

		<td colspan=2 class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nombre del Pa&iacute;s</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="pais" maxlength=128 size=20 value=""></td>

		<td colspan=1 class="tdtitulos"><a href="#" OnClick="guardar_pais();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

	
	<tr> <td>&nbsp;</td>
</table>

<div id="guardar_pais"></div>
<div id="modificar_pais"></div>
</form>

