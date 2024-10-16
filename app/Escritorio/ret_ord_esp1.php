<?php
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$q_proceso=("select * from procesos where procesos.id_proceso='$proceso'");
$r_proceso=ejecutar($q_proceso);
$num_filas=num_filas($r_proceso);

if ($num_filas==0) { 
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">El Numero Orden no Existe</td>
      </tr>
	</table>
	<?php
	}
	else
	{

$f_proceso=asignar_a($r_proceso);
$servicio=$f_proceso['id_servicio_aux'];

if ($f_proceso[id_estado_proceso] <>13) { 
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">El Numero Orden no se encuentra en Espera </td>
      </tr>
	</table>
	<?php
	}
	else
	{
	?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>
<input class="campos" type="hidden" name="comenope1" 
					maxlength=128 size=20 value="<?php echo $f_proceso[comentarios]?>"   >
					<input class="campos" type="hidden" name="fecharec" 
					maxlength=128 size=20 value="<?php echo $f_proceso[fecha_recibido]?>" >
						<input class="campos" type="hidden" name="edoproceso" 
					maxlength=128 size=20 value="<?php echo $f_proceso[id_estado_proceso]?>" >
<td  class="tdtitulos">* Seleccione  el Servicio.</td>
<td  class="tdcampos">
		<select name="servicio" class="campos" OnChange="reg_oats();">
		<?php $q_servicio=("select * from servicios where servicios.id_servicio=$servicio order by servicio");
		$r_servicio=ejecutar($q_servicio);
		?>
		<?php		
		while($f_servicio=asignar_a($r_servicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_servicio[id_servicio]?>"> <?php echo "$f_servicio[servicio]"?></option>
		<?php
		}
		?>
		</select>
		</td>
		</tr>



<tr>
<?php
	if ($servicio==4 || $servicio==6 || $servicio==9 || $servicio==14)
		{
			?>
<td  colspan=1 class="tdtitulos">* Seleccione  el Tipo de Servicio.</td>
<td  colspan=3 class="tdcampos">
		<select name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=$servicio order by tipo_servicio");
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
			<a href="#" OnClick="ret_espera3();" class="boton">Buscar</a>
		<?php
		}
		else
		{
		?>
		<td colspan=1 class="tdtitulos"> No Tiene Tipo Servicio </td>
		<td colspan=3 class="tdcampos"><input class="campos" type="hidden" name="tiposerv" 
					maxlength=128 size=20 value="0"   >
		<a href="#" OnClick="ret_espera3();" class="boton">Buscar</a></td>
		<?php 
		}
		?>
			
		</tr>
</table>
	<div id="retespera1"></div>
<?php
}
}
?>

