<?php
include ("../../lib/jfunciones.php");
sesion();
//busco todas las secciones
$q_modulos="select * from tbl_002 order by  tbl_002.tip_mod asc;";
$r_modulos=ejecutar($q_modulos);
$q_admin1="select admin.id_admin,admin.cedula,admin.nombres,admin.apellidos,tipos_admin.tipo_admin,sucursales.sucursal,departamentos.departamento from admin,tipos_admin,sucursales,departamentos where admin.id_tipo_admin=tipos_admin.id_tipo_admin 
and admin.activar='1' and admin.id_sucursal=sucursales.id_sucursal and departamentos.id_departamento=admin.id_departamento order by  admin.nombres";
$r_admin1=ejecutar($q_admin1);

?>
<link HREF="../../public/stylesheets/estilos1.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js">

</script>

<form action="POST" method="POST" name="permiso" id="formp">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td align="right" colspan=4 class="titulo_seccion"><b>Permisologia del Usuario</b></td>	
	</tr>


	
	<tr>
	<td class="tdtitulos">nombre de usuario</td>
	<td align="right">
    <select id="login" name="login" style="width: 200px;" class="campos" >
	
		<option value=""> Nombre Usuario</option>
		<?php		
		while($f_admin1=asignar_a($r_admin1,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_admin1[id_admin]?>"> <?php echo "$f_admin1[nombres] $f_admin1[apellidos]  ($f_admin1[tipo_admin]  $f_admin1[departamento]  $f_admin1[sucursal])"?></option>
		<?php
		}
		?>
		</select>
    
    </td>
	
	<td  class="tdtitulos">* Seleccione  el Servicio.</td>
<td  class="tdcampos">
		<select id="tmodulos" name="tmodulos" class="campos" OnChange="buscar_permisos2();">
	
		<option value=""> Seleccione el Tipo</option>
		<?php		
		while($f_modulos=asignar_a($r_modulos,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_modulos[id_tipmod]?>"> <?php echo "$f_modulos[tip_mod]"?></option>
		<?php
		}
		?>
		</select>
		<a href="#" OnClick="buscar_permisos2();" class="boton">Buscar</a><a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
	
	
	</tr>	


</table>
<div id="permisos2"></div>
</form>

