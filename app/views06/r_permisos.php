<?php
/* NOMBRE DEL ARCHIVO: r_permisos.php
   DESCRIPCION: SOLICITAR LOS DATOS PARA REPORTE DE PERMISOS SUMINISTRADOS A CADA USUARIO
*/

	include ("../../lib/jfunciones.php");
	sesion();


$q_usuario=("select admin.nombres, admin.apellidos, admin.id_admin from admin where admin.activar='1' order by admin.nombres");
$r_usuario=ejecutar($q_usuario);

$q_dpto=("select departamentos.id_departamento, departamentos.departamento from departamentos order by departamentos.departamento");
$r_dpto=ejecutar($q_dpto);

?>


<form method="POST" name="r_permisos" id="r_permisos">
	<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Permisos Asignados a los Usuarios </td>
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;   </td>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Usuario: </td>
		<td colspan=1 class="tdcamposcc" ><select id="usuario" name="usuario" class="campos" onchange="permisos()"  style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Usuario. ---</option>
				      <?php  while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_usuario[id_admin]?>"> <?php echo "$f_usuario[nombres] $f_usuario[apellidos]"?></option>
				     <?php }?> 
		</td>	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;   </td>
	</tr>

<tr><td>&nbsp;</td></tr>



	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;   </td>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Departamento: </td>
		<td colspan=1 class="tdcamposcc" ><select id="dpto" name="dpto" class="campos" onchange="permisos()"  style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Departamento. ---</option>
				      <?php  while($f_dpto=asignar_a($r_dpto,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_dpto[id_departamento]?>"> <?php echo "$f_dpto[departamento]"?></option>
				     <?php }?> 
		</td>	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp;   </td>
	</tr>

<tr><td>&nbsp;</td></tr>



</table>
    <div id="reporte"></div>
</body>
</html>
