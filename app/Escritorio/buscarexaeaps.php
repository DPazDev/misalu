<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$examenes=$_REQUEST['examenes'];
$q_examen=("select * from imagenologia_bi where imagenologia_bi.id_tipo_imagenologia_bi='$examenes' order by imagenologia_bi");
$r_examen=ejecutar($q_examen);

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
       <?php       
$i=0;
$ban="";
 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC)){
	$i++;
	?>
	<tr>
		<td colspan=2  class="tdtitulos"><?php echo $f_examen[imagenologia_bi]?>-------------------------></td>
		<td colspan=2  class="tdcampos">
		<input class="campos" type="hidden" id="idexamen_<?php echo $i?>" name="idexamenl" maxlength=128 size=20 value="<?php echo $f_examen[id_imagenologia_bi]?>">
		<input class="campos" type="hidden" id="examen_<?php echo $i?>" name="examenl" maxlength=128 size=20 value="<?php echo $f_examen[imagenologia_bi]?>"><input class="campos" type="text" id="honorarios_<?php echo $i?>" name="examen" maxlength=128 size=20 value="<?php echo $f_examen[hono_privados]?>"  OnChange="return validarNumero(this);" >
		<select  id="coment_<?php echo $i?>"  name="coment" class="campos" >
		
				<option value="iNFORMADA"> INFORMADA</option>
				<option value="">NO INFORMADA</option>
				

		</select>
		<input class="ca
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkdes" maxlength=128 size=20 value=""></td>

		
</tr>
<br>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
	 <tr>				
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value="0" OnChange="return validarNumero(this);"  ></td>
					
	<td><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a>
	</td>

	</tr>		
	
	<tr>
				<td class="tdtitulos">* Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value=""   ></td>
					<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" maxlength=128 size=20 value=""   ></td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="decrip" maxlength=128 size=20 value="EXAMENES ESPECIALES"   ></td>
				
	</tr>		
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"></textarea></td>
				


	</tr>		
		
</table>
