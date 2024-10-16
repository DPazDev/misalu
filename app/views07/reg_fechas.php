<?php
include ("../../lib/jfunciones.php");
sesion();
$paso = $_REQUEST['paso'];
$id_admin= $_SESSION['id_usuario_'.empresa];
/* ****  se verifica si la variable paso es 1 si es asi se registra la fecha**** */

if ($paso==1){
$mesini = $_REQUEST['mesini'];
$mesfinal = $_REQUEST['mesfinal'];
$diaini = $_REQUEST['diaini'];
$diafinal = $_REQUEST['diafinal'];
$tipo_fecha = $_REQUEST['tipo_fecha'];
$medico = $_REQUEST['medico'];
$comentario = strtoupper($_REQUEST['comentario']);

if ($medico==0){
	$r_fechas="insert into tbl_fechas (id_tipo_fecha,
	 				       mes_inicio,
					       mes_final,
							dia_inicio,
							dia_final,
							comentarios,
							id_persona) 
					values('$tipo_fecha',
					       '$mesini',
					       '$mesfinal',
							'$diaini',
					       '$diafinal',
					       '$comentario',
					       '0');";
		
		$r_fechas=ejecutar($r_fechas);
	}
	else
	{
$q_gmedicos=("select * from proveedores,s_p_proveedores,personas_proveedores where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and personas_proveedores.id_persona_proveedor=$medico");
$r_gmedicos=ejecutar($q_gmedicos);
while($f_gmedicos=asignar_a($r_gmedicos,NULL,PGSQL_ASSOC)){
$r_fechas="insert into tbl_fechas (id_tipo_fecha,
	 				       mes_inicio,
					       mes_final,
							dia_inicio,
							dia_final,
							comentarios,
							id_persona) 
					values('$tipo_fecha',
					       '$mesini',
					       '$mesfinal',
							'$diaini',
					       '$diafinal',
					       '$comentario',
					       '$f_gmedicos[id_proveedor]');";
		
		$r_fechas=ejecutar($r_fechas);
		}
		}
		if ($tipo_fecha==1){
			$tipo_fecha="Dias Feriados";
			}
			if ($tipo_fecha==2){
			$tipo_fecha="Permisos";
			}
			if ($tipo_fecha==3){
			$tipo_fecha="Vacaciones";
			}

/* **** Se registra lo que hizo el usuario**** */

$log="REGISTRO la Fecha inicial $diaini - $mesini y final $diafinal - $mesfinal de tipo $tipo_fecha y para el id_persona_proveedor $medico ($comentario)";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
}
/* ****  para eliminar una fecha existente **** */ 
if ($paso==2){
	$eliminar = $_REQUEST['eliminar'];
	$q_fechas=("select * from tbl_fechas where tbl_fechas.id_fecha='$eliminar' ");
	$r_fechas=ejecutar($q_fechas);
	$f_fechas=asignar_a($r_fechas);
	
	$log="Elimino la Fecha inicial $f_fechas[dia_inicio] - $f_fechas[mes_inicio] y final $f_fechas[dia_final] - $f_fechas[mes_final] de tipo $tipo_fecha y para el id_persona_proveedor $f_fechas[id_persona]";
logs($log,$ip,$id_admin);

$e_fechas=("delete from tbl_fechas where tbl_fechas.id_fecha='$eliminar'");
	$er_fechas=ejecutar($e_fechas);
	}

$q_medicos=("select * from personas_proveedores order by personas_proveedores.nombres_prov");
$r_medicos=ejecutar($q_medicos);

$q_fechas=("select * from tbl_fechas order by tbl_fechas.id_tipo_fecha ");
$r_fechas=ejecutar($q_fechas);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="cita">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>	
<td colspan=7 class="titulo_seccion">Fechas del Control de Citas </td>	</tr>	
	<tr>
	<td colspan=1 class="tdtitulos">Id_Fecha</td>
	<td colspan=1 class="tdtitulos">Tipo de Fecha</td>
	<td class="tdtitulos">Desde </td>
	<td class="tdtitulos">Hasta</td>
	<td colspan=1 class="tdtitulos">Medicos</td>
	<td colspan=1 class="tdtitulos">Comentario</td>
	<td colspan=1 class="tdtitulos">fecha_creado</td>
	</tr>
		<?php 
		
		while($f_fechas=asignar_a($r_fechas,NULL,PGSQL_ASSOC)){
       if ($f_fechas[id_tipo_fecha]==1)
	{
		$tipo_fecha="Dias Feriados";
		}
		if ($f_fechas[id_tipo_fecha]==2)
	{
		$tipo_fecha="Permisos";
		}
		if ($f_fechas[id_tipo_fecha]==3)
	{
		$tipo_fecha="Vacaciones";
		}
		
		?>
		<tr>

<td colspan=1 class="tdcampos">
<?php echo $f_fechas[id_fecha]?>
          
                </td>
		
		<td class="tdcampos"><?php echo $tipo_fecha?></td>

		<td class="tdcampos"><?php echo "$f_fechas[dia_inicio] - $f_fechas[mes_inicio]"?></td>

		<td class="tdcampos"><?php echo "$f_fechas[dia_final] $f_fechas[mes_final]"?></td>
		
		<td class="tdcampos"><?php 
		if ($f_fechas[id_persona]==0){
			echo "Todos ";
			}
			else
			{
		$q_medicos1=("select * from personas_proveedores,s_p_proveedores,proveedores where personas_proveedores.id_persona_proveedor=s_p_proveedores.id_persona_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor=$f_fechas[id_persona] ");
$r_medicos1=ejecutar($q_medicos1);
		$f_medicos1=asignar_a($r_medicos1);
		
		echo "$f_medicos1[nombres_prov] $f_medicos1[apellidos_prov]";
		}
		
		?></td>

<td colspan=1 class="tdcampos">

		<?php echo $f_fechas[comentarios]?></td>
		<td class="tdcampos"><?php echo "$f_fechas[fecha_creado]"?></td>
</tr>
	<?php 
	}
	?>
	<tr>

<td colspan=1 class="tdcampos"></td>
		
		<td class="tdcampos"></td>

		<td class="tdcampos"></td>

		<td class="tdcampos"></td>
		
		<td class="tdcampos"></td>

		<td class="tdcampos"></td>
</tr>
</table>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>	
<td colspan=7 class="titulo_seccion">Registrar o Eliminar Fechas Para el Control de Citas </td>	</tr>	
	<tr>
	<td colspan=1 class="tdtitulos">Tipo de Fecha</td>

		<td class="tdtitulos">* Mes Inicio</td>
		
		<td class="tdtitulos">* Mes Final</td>
		
		<td class="tdtitulos">* Dia Inicio</td>
		
		<td class="tdtitulos">* Dia Final</td>
		
		<td colspan=1 class="tdtitulos">Medicos</td>
		<td colspan=1 class="tdtitulos">Comentario</td>

		</tr>
		
		<tr>

<td colspan=1 class="tdcampos">

                <select id="tipo_fecha" name="tipo_fecha" class="campos">
                              <option value="1" > Dias Feriados</option>
							<option value="2" > Permisos</option>  
							<option value="3" > Vacaciones</option>  
                
                </select>
                </td>
		
		<td class="tdcampos"><input class="campos" type="text"id="mesini" name="mesini" maxlength=128 size=5 value=""   onkeypress="return event.keyCode!=13"></td>

		<td class="tdcampos"><input class="campos" type="text"id="mesfinal" name="mesfinal" maxlength=128 size=5 value=""   onkeypress="return event.keyCode!=13"></td>

		<td class="tdcampos"><input class="campos" type="text" id="diaini" name="diaini" maxlength=128 size=5 value=""   onkeypress="return event.keyCode!=13"></td>
		
		<td class="tdcampos"><input class="campos" type="text"id="diafinal" name="diafinal" maxlength=128 size=5 value=""   onkeypress="return event.keyCode!=13"></td>

<td colspan=1 class="tdcampos">

                <select style="width: 100px;" id="medico" name="medico" class="campos">
                              <option value="0" >Todos </option>
                <?php
                while($f_medicos=asignar_a($r_medicos,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_medicos[id_persona_proveedor]?>"> <?php echo "$f_medicos[nombres_prov] $f_medicos[apellidos_prov]" ?>
                </option>
                <?php
                }
                ?>
                </select>
		</td>
		<td class="tdcampos"><input class="campos" type="text"id="comentario" name="comentario" maxlength=128 size=20 value=""   onkeypress="return event.keyCode!=13"></td>
</tr>
	<tr>
	<td colspan=7 class="tdcampos">
	<hr></hr>
	</td>
	</tr>
<tr>

<td colspan=1 class="tdcampos">

               
                </td>
		
		<td class="tdcampos"></td>

		<td colspan=3 class="tdcamposr">
		Para Eliminar una Fecha Coloque el id_fecha
		</td>

<td colspan=1 class="tdcampos">
<input class="campos" type="text"id="eliminar" name="eliminar" maxlength=128 size=5 value=""  onkeyUp="return ValNumero(this);" onkeypress="return event.keyCode!=13">	
		</td>
		<td class="tdcampos"><a href="#" OnClick="eli_fechas();" class="boton">Eliminar</a><a href="#" OnClick="reg_fechas1();" class="boton">Guardar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
</tr>
	
		
</table>
</form>
