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
		<td colspan=1 class="tdtitulos">* Seleccione  Doctor(a).</td>
<td colspan=2 class="tdcampos">
		<select name="proveedor" class="campos" >
		<?php $q_p=("select sucursales.sucursal,especialidades_medicas.especialidad_medica,proveedores.id_proveedor,
		personas_proveedores.*,s_p_proveedores.* from especialidades_medicas,personas_proveedores,
		s_p_proveedores,proveedores,sucursales,ciudad where proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
		s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor and s_p_proveedores.nomina=1 and
		s_p_proveedores.id_ciudad=ciudad.id_ciudad and ciudad.id_ciudad>'0'  
		and s_p_proveedores.id_sucursal=sucursales.id_sucursal and 
		especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad 
		order by personas_proveedores.nombres_prov");
		$r_p=ejecutar($q_p);
		?>
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[sucursal] 
		$f_p[id_proveedor]"?></option>
		<?php
		}
		?>
		</select>
		</td>
		 <td class="tdtitulos"><a href="#" OnClick="con_medico2();" class="boton">Consultar</a></td>
	
        </tr>
		
</table>
<div id="con_medico2"></div>

</form>
