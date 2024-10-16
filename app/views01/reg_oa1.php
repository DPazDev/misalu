<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$servicio=$_REQUEST['servicio'];
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>
<input class="campos" type="hidden" name="edoproceso" 
					maxlength=128 size=20 value="0" >
<?php
	if ($servicio==4 || $servicio==6 || $servicio==9  || $servicio==14)
		{
			?>
<td  colspan=1 class="tdtitulos">* Seleccione  el Tipo de Servicio.</td>
<td  colspan=3 class="tdcampos">
		<select name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=$servicio and tipos_servicios.id_tipo_servicio<>10 and tipos_servicios.id_tipo_servicio<>22 and tipos_servicios.id_tipo_servicio<>23 and tipos_servicios.id_tipo_servicio<>21 and tipos_servicios.id_tipo_servicio<>24 order by tipos_servicios.tipo_servicio");
		$r_tservicio=ejecutar($q_tservicio);
		?>
				<option value=""> Seleccione el Tipo de Servicio</option>
				<?php		
		while($f_tservicio=asignar_a($r_tservicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_tservicio[id_tipo_servicio]?>"> <?php echo "$f_tservicio[tipo_servicio]"?>			</option>
		<?php
		}
		?>
		</select>
			<a href="#" OnClick="reg_oa2();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		<?php
		}
		else
		{
		?>
		<td colspan=1 class="tdtitulos"> No Tiene Tipo Servicio </td>
		<td colspan=3 class="tdcampos"><input class="campos" type="hidden" name="tiposerv" 
					maxlength=128 size=20 value="0"   >
		<a href="#" OnClick="reg_oa2();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		<?php 
		}
		?>
			
		</tr>
</table>
	<div id="reg_oa2"></div>


