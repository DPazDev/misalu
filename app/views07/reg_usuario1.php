<?php

/* Nombre del Archivo: reg_usuario1.php
   Descripción: Formulario para solicitar los datos para INSERTAR o MODIFICAR un USUARIO en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();


$q_usuario=("select admin.nombres, admin.apellidos, admin.id_admin from admin order by admin.nombres");
$r_usuario=ejecutar($q_usuario);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="guardar_usuario.php" method="post" id="usuario" name="usuario">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Registrar o Modificar Usuario</td>
	</tr>	
<tr><td>&nbsp;</td></tr>
	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Verificar N&uacute;mero de C&eacute;dula</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" name="ci" maxlength=150 size=25 value=""></td>
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Usuario: </td>
		<td colspan=1 class="tdcamposcc" ><select id="nombre" name="nombre" class="campos" style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Usuario. ---</option>
				      <?php  while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_usuario[id_admin]?>"> <?php echo "$f_usuario[nombres] $f_usuario[apellidos]"?></option>
				     <?php }?> 
		</td>


		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_usuario();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
</table>
<div id="guardar_usuario"></div>
</from>
