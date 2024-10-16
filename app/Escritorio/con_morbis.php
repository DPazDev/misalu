<?php
include ("../../lib/jfunciones.php");
sesion();
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="con_morbis">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Consultar  Morbilidad Medica</td>	</tr>	
		<tr>
		<td colspan=2 class="tdtitulos">* Seleccione la Fecha Inicio Cita:   
 <input readonly type="text" size="10" id="dateField1" name="fechainicio" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
<td colspan=2 class="tdtitulos">* Seleccione la Fecha final Cita:   
 <input readonly type="text" size="10" id="dateField2" name="fechafin" class="campos" maxlength="10" value=<?php echo $f_cita1[fecha_cita]; ?>> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
		</tr>
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione  la Clinica</td>
<td colspan=2 class="tdcampos">
		  <select style="width: 200px;" OnChange="verificarproc(this);" id="id_proveedorc" name="id_proveedorc" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                if ($f_admin[id_ente]>0){
                ?>
                <option   value="0"> Sin Proveedor</option> 
                <option value="*"> Todos </option>
                
                 <?php
                 }
                 else
                 {
                     ?>
                <option   value="0"> Sin Proveedor</option> 
                <option value="*"> Todos </option>
                     <?php 
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>  
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                }
                ?>
                </select>
		</td>
		 <td class="tdtitulos"> o </td>
	
        </tr>
		<tr>
		<td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=2 class="tdcampos">
		<select style="width: 400px;" OnChange="verificarprop(this);" id="id_proveedorp" name="id_proveedorp" class="campos" >
		<?php $q_p=("select sucursales.sucursal,especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
		personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
		s_p_proveedores,proveedores,sucursales where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
		s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1 		
		and s_p_proveedores.id_sucursal=sucursales.id_sucursal and 
		especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad 
		order by personas_proveedores.nombres_prov");
		$r_p=ejecutar($q_p);
		?>
		<option   value="0"> Sin Proveedor</option> 
		<option value="*"> Todos Los Medicos</option>
		<?php		
		while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){
			if ($f_p[activar]==0){
				$activo="SU ESTADO ES INACTIVO";
				}
				else
				{
								$activo="SU ESTADO ES ACTIVO";
	
				}
		?>
		<option  <?php if ($f_p[id_sucursal]==3 || $f_p[id_sucursal]>=8) {?> class="option" <?php } ?>
<?php if ($f_p[id_sucursal]==2) {?> class="option2" <?php } ?>
<?php if ($f_p[id_sucursal]==7) {?> class="option1" <?php } ?>

value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov]  $f_p[especialidad_medica] $f_p[sucursal] 
		$f_p[id_proveedor] $activo     "?></option>
		<?php
		}
		?>
		</select>
		</td>
		 <td class="tdtitulos"><a href="#" OnClick="buscarmorbis();" class="boton">Consultar</a>
		<a href="#" title="Exportar a Excel la Morbilidad Para Auditar el Proceso" OnClick="bus_rep_excel_morbi();"> <img border="0" src="../public/images/excel.jpg"></a> 
		<a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	
        </tr>
		
</table>
<div id="buscarmorbis"></div>

</form>
