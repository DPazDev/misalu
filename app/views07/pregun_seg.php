<?
/* Nombre del Archivo: pregun_seg.php
   Descripción: Seleccionar las preguntas de Seguridad y agregar la respuesta a dichas Preguntas*/

	include ("../../lib/jfunciones.php");
	sesion();

$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];

$q_respuesta=("select tbl_respuestas.* from tbl_respuestas where tbl_respuestas.id_admin='$elid'");
$r_respuesta=ejecutar($q_respuesta);
$f_respuesta=asignar_a($r_respuesta);


$q_pregunta=("select tbl_preguntas.id_pregunta, tbl_preguntas.pregunta from tbl_preguntas order by tbl_preguntas.pregunta");
$r_pregunta=ejecutar($q_pregunta);	

$q_pregunta1=("select tbl_preguntas.id_pregunta, tbl_preguntas.pregunta from tbl_preguntas order by tbl_preguntas.pregunta");
$r_pregunta1=ejecutar($q_pregunta1);

$q_pregunta2=("select tbl_preguntas.id_pregunta, tbl_preguntas.pregunta from tbl_preguntas order by tbl_preguntas.pregunta");
$r_pregunta2=ejecutar($q_pregunta2);	    		  
?>

<form method="POST" name="r_pregun_seg" id="r_pregun_seg">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

        



<? if($f_respuesta[id_admin]==$elid){ ?>
	<tr>
		<td colspan=3 class="titulo_seccion">modificar</td>
	</tr>
	<tr> <td>&nbsp;</td>
	</tr>
<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="mod_pass()" class="boton">Modificar Password</a> </td>
	</tr>
	<tr> <td>&nbsp;</td>
<?}
else {

?>	
<tr>
		<td colspan=3 class="titulo_seccion">Preguntas de Seguridad</td>
	</tr>
	<tr> <td>&nbsp;</td>
	</tr>




	<tr>
		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Seleccione la Primera Pregunta</td>
                <td colspan=1 class="tdcamposcc" ><select id="pregunta1" name="pregunta1" class="campos" style="width: 300px;"   >
	                              <option value="">--- Preguntas. ---</option>
				      <?php  while($f_pregunta=asignar_a($r_pregunta,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_pregunta[id_pregunta]?>"> &nbsp;&nbsp;&nbsp;<?php echo "$f_pregunta[pregunta] "?></option>
				     <?php }?> 
		</td>
		<td colspan=1>&nbsp;</td>
	</tr>
	<tr> 
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Respuesta a la Pregunta</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="respuesta1" name="respuesta1" maxlength=128 style="width: 300px;" size=40 value="">
		</td>
	</tr>
	<tr> 
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Seleccione la Segunda Pregunta</td>
                <td colspan=1 class="tdcamposcc" ><select id="pregunta2" name="pregunta2" class="campos" style="width: 300px;"   >
	                              <option value="">--- Preguntas. ---</option>
				      <?php  while($f_pregunta1=asignar_a($r_pregunta1,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_pregunta1[id_pregunta]?>"> &nbsp;&nbsp;&nbsp;<?php echo "$f_pregunta1[pregunta] "?></option>
				     <?php }?> 
		</td>
		<td colspan=1>&nbsp;</td>
	</tr>
	<tr> 
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Respuesta a la Pregunta</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="respuesta2" name="respuesta2" maxlength=128 style="width: 300px;" size=40 value="">
		</td>


<tr> <td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Seleccione la Tercera Pregunta</td>
                <td colspan=1 class="tdcamposcc" ><select id="pregunta3" name="pregunta3" class="campos" style="width: 300px;" title="     "  >
	                              <option value="">--- Preguntas. ---</option>
				      <?php  while($f_pregunta2=asignar_a($r_pregunta2,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_pregunta2[id_pregunta]?>"> &nbsp;&nbsp;&nbsp;<?php echo "$f_pregunta2[pregunta] "?></option>
				     <?php }?> 
		</td>
		<td colspan=1>&nbsp;</td>
	</tr>
	<tr> 
		<td colspan="3">&nbsp;</td>
	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">&nbsp;&nbsp;&nbsp; Respuesta a la Pregunta</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="respuesta3" name="respuesta3" maxlength=128 style="width: 300px;" title="  " size=40 value="">
		</td>
	
	


		<td colspan=1 class="tdtitulos"><a href="#" OnClick="guardar_pregun_seg();" class="boton">Guardar</a>
		<a href="logout.php" OnClick="ir_principal();" class="boton">Salir</a>
	</tr>

<?}

?>

	<tr> 
		<td colspan="3">&nbsp;</td>
	</tr>
</table>

<div id="pregun_seg"></div>

</form>

