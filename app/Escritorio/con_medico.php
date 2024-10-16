<?php
include ("../../lib/jfunciones.php");
sesion();
$cedula=$_REQUEST['cedula'];
$proceso=$_REQUEST['proceso'];
$id_proveedor=$_REQUEST['id_proveedor'];
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$fecha=date("Y-m-d");
$q_proveedor=("select * from s_p_proveedores,proveedores,especialidades_medicas where s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and proveedores.id_proveedor=$id_proveedor and s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica");

$r_proveedor=ejecutar($q_proveedor) or mensaje(ERROR_BD);
$num_filas=num_filas($r_proveedor);
$f_proveedor=asignar_a($r_proveedor);

$q_cita=("select gastos_t_b.fecha_cita,count(gastos_t_b.fecha_cita) from gastos_t_b,procesos where gastos_t_b.id_proveedor=$id_proveedor and gastos_t_b.fecha_cita>='$fecha' and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso<>14 group by gastos_t_b.fecha_cita order by gastos_t_b.fecha_cita");
$r_cita=ejecutar($q_cita) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_cita);

/* **** busco cita para la opcion actualizar cita **** */
$q_cita1=("select gastos_t_b.fecha_cita,gastos_t_b.descripcion,gastos_t_b.monto_aceptado,gastos_t_b.enfermedad,procesos.comentarios from gastos_t_b,procesos where gastos_t_b.id_proveedor=$id_proveedor and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso' ");
$r_cita1=ejecutar($q_cita1) or mensaje(ERROR_BD);
$f_cita1=asignar_a($r_cita1);

/* **** fin de buscar cita para actualizar **** */

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_citas"  border=1 cellpadding=0 cellspacing=0>
<tr>
                <td colspan=4   class="titulo_seccion">Horario</td>

</tr>
<tr>
                <td colspan=4   class="tdcamposgcc"><?php echo $f_proveedor[horario]?></td>

</tr>
<tr>
                <td colspan=4   class="titulo_seccion">Comentarios</td>

</tr>
<tr>
                <td colspan=4   class="tdcamposrc1"> <?php echo $f_proveedor[comentarios_prov]?></td>

</tr>
<tr>
                <td colspan=4 class="tdcamposc"><input type="hidden" name="monto" value="<?php echo $f_proveedor[monto] ?>" > </td>             
</tr>


<tr> <td colspan=4 class="titulo_seccion">Citas Medicas <?php
                        $url="views01/imorbilidadp.php?fechainicio=$f_cita[fecha_cita]&fechafin=$f_cita[fecha_cita]&proveedor=$id_proveedor&cedula=$cedula";
                        ?> <a href="<?php echo $url; ?>" title="Relacion de Citas del Paciente"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 500, overlayClose: false}); return false;" class="boton">Ver Citas del Paciente con medico Seleccionado</a></td>
      </tr>
<tr>
                <td colspan=1  class="tdcamposc"> Fechas de Las Citas</td>
                <td colspan=1  class="tdcamposac">Total de Citas General</td>
                <td colspan=1  class="tdcamposrc">Total de Citas Privadas</td>
				<td colspan=1  class="tdcamposc">Total Citas</td>

</tr>

  <?php
             while($f_cita=asignar_a($r_cita,NULL,PGSQL_ASSOC)){
				
				$q_citaf=("select entes.id_tipo_ente,gastos_t_b.fecha_cita from gastos_t_b,entes,procesos,titulares where gastos_t_b.id_proveedor=$id_proveedor and gastos_t_b.fecha_cita='$f_cita[fecha_cita]' and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso<>14  order by gastos_t_b.fecha_cita");
$r_citaf=ejecutar($q_citaf) or mensaje(ERROR_BD);
				$con1=0;
				$con2=0;
				while($f_citaf=asignar_a($r_citaf,NULL,PGSQL_ASSOC)){
					if ($f_citaf[id_tipo_ente]==4){
					$con2++;
					}
					else
					{
						$con1++;
						}
					}
				
                ?>
<tr>
		<td colspan=1 class="tdcamposc1"> <?php
		
		$dias=array("domingo","lunes","martes","miércoles" ,"jueves","viernes","sábado");
		$dia=substr($f_cita[fecha_cita],8,2);
$mes=substr($f_cita[fecha_cita],5,2);
$anio=substr($f_cita[fecha_cita],0,4);
		$pru=strtoupper($dias[intval((date("w",mktime(0,0,0,$mes,$dia,$anio))))]);
		echo "$pru $dia-$mes-$anio";?></td>
		<td colspan=1 class="tdcamposac1"><?php echo $con1;?></td>
		<td colspan=1 class="tdcamposrc1"><?php echo $con2;?></td>
		<td colspan=1 class="tdcamposc1"><?php echo $f_cita[count];?>
 <?php
                        $url="views01/imorbilidad1.php?fechainicio=$f_cita[fecha_cita]&fechafin=$f_cita[fecha_cita]&proveedor=$id_proveedor";
                        ?> <a href="<?php echo $url; ?>" title="Morbilidad"  onclick="Modalbox.show(this.href, {title: this.title, width: 800, height: 500, overlayClose: false}); return false;" class="boton">Ver Citas</a>

</td>

</tr>
<?php
}
?>

<tr>
                <td colspan=2 class="tdcamposc"> <input type="hidden" name="fecha" value="<?php echo $fecha ?>"> </td>
</tr>

</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=1 class="campos"><?php echo $f_cita1[comentarios]?></textarea><td class="tdcampos"><input class="campos" type="hidden" name="horac" 
					maxlength=128 size=20 value=""   ></td>
	</tr>
<tr>
 <td colspan=1 class="tdtitulos">* Seleccione la Fecha para la Cita:   
 <input readonly type="text" size="10" id="dateField1" onFocus="verificar_fecha();" name="fechaen" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd');"  title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>

                </td>
</tr>
<td colspan=4 class="verificar_fecha">
<div id="verificar_fecha"></div>
                </td>
</tr>
<tr>
 
<?php if ($proceso==0){
	?>
<td colspan=1 class="tdtitulos">* Seleccione el Tipo de Consulta</td>
<td colspan=1 class="tdcampos">
                <select name="tipcon" class="campos">
                <option value="0">Consulta Medica</option>                 
                <option value="24">Valoracion Pre Operatoria</option>           
				<option value="31">Lectura de Examen</option>    
				<option value="28">Consulta por Especialidad Emergencia</option>   
				<option value="30">Consulta pos Operatoria</option>
		
                </select>
                </td>


                </td>
                <td class="tdtitulos"><a href="#" OnClick="guardar_cita();" class="boton">Guardar Citas</a></td>
				</tr>
		<?php }
		else
		 {
		?>
		<td colspan=1 class="tdtitulos">* Seleccione el Tipo de Consulta</td>
<td colspan=1 class="tdcampos">
                <select name="tipcon" class="campos">
				<?php if ($f_cita1[descripcion]=='CONSULTA MEDICA'){
					?>
					<option value="0">Consulta Medica</option>    
					<?php 
					}
					else {
						 if (($f_cita1[descripcion]=='CONSULTA MEDICA + VPO') || ($f_cita1[descripcion]=='VALORACION PRE OPERATORIA')){
						?>
						<option value="24">Valoracion pre Operatoria</option>  
						<?php 
					}
					else {
						 if ($f_cita1[descripcion]=='CONSULTA MEDICA + CITOLOGIA'){
						?>
						<option value="25">Consulta Medica + Citologia</option>         
						<?php
					}
					else {
						 if ($f_cita1[descripcion]=='CONSULTA MEDICA + LECTURA DE EXAMEN'){
						?>
						<option value="32">Consulta Medica + Lectura de Examen</option>         
						<?php
						}		
						else {
						 if ($f_cita1[descripcion]=='LECTURA DE EXAMEN'){
						?>
						<option value="31">Lectura de Examen</option>         
						<?php
						}		
						else {
						 if ($f_cita1[descripcion]=='CONSULTA PREVENTIVA'){
						?>
						<option value="26">Consulta Preventiva</option>         
						<?php
						}		
						else {
						 if ($f_cita1[descripcion]=='CONSULTA POR ESPECIALIDAD EMERGENCIA'){
						?>
						<option value="28">Consulta por Especialidad Emergencia</option>         
						<?php
						}		
						else {
						 if ($f_cita1[descripcion]=='CONSULTA POS OPERATORIA'){
						?>
						<option value="30">Consulta pos Operatoria</option>         
						<?php
						}		
						else {
						 if ($f_cita1[descripcion]=='CITOLOGIA'){
						?>
						<option value="29">Citologia</option>         
						<?php
						}
						}
						}		
						}
						}
						}
						}
						}
						}
						?>
                <option value="0">Consulta Medica</option>                 
                <option value="24">Valoracion Pre Operatoria</option>           
				<option value="31">Lectura de Examen</option>    
				<option value="28">Consulta por Especialidad Emergencia</option>   
				<option value="30">Consulta pos Operatoria</option>
                </select>
                </td>


                </td>
                
</tr>
<tr>
<td colspan=2 class="tdtitulos">* Diagnostico:
                <input type="text" class="campos" name="diagnostico" value="<?php echo $f_cita1[enfermedad]?>"> </td>
				<td colspan=2 class="tdtitulos"><a href="#" OnClick="actualizar_cita();" class="boton">Actualizar Citas</a></td>
		
</tr>
<?php
			}
			?>

</table>
