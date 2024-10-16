<?php

/* Nombre del Archivo: reg_vendedor1.php
   Descripción: Formulario para solicitar los datos para INSERTAR o MODIFICAR un VENDEDOR en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();


$q_usuario=("select admin.id_admin,admin.nombres,admin.apellidos from admin order by admin.nombres");
$r_usuario=ejecutar($q_usuario);

$q_vendedor=("select comisionados.nombres, comisionados.apellidos, comisionados.id_comisionado from comisionados order by comisionados.nombres");
$r_vendedor=ejecutar($q_vendedor);



?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="guardar_vendedor.php" method="post" id="usuario" name="usuario">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Registrar Vendedor</td>
	</tr>	

	<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Verificar N&uacute;mero de C&eacute;dula</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" name="ci" maxlength=150 size=25 value=""></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Vendedor: </td>
		<td colspan=1 class="tdcamposcc" ><select id="nombre" name="nombre" class="campos" style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Vendedor. ---</option>
				      <?php  while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_usuario[id_admin] ?>"> <?php echo "$f_usuario[nombres] $f_usuario[apellidos]"?></option>
				     <?php }?> 
		</td>


		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_vendedor();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=4 class="titulo_seccion">Modificar Vendedor</td>
	</tr>	

	<tr><td>&nbsp;</td></tr>

	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Verificar N&uacute;mero de C&eacute;dula</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" name="ci1" maxlength=150 size=25 value=""></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Vendedor: </td>
		<td colspan=1 class="tdcamposcc" ><select id="nombre1" name="nombre1" class="campos" style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Vendedor. ---</option>
				      <?php  while($f_vendedor=asignar_a($r_vendedor,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_vendedor[id_comisionado]?>"> <?php echo "$f_vendedor[nombres] $f_vendedor[apellidos]"?></option>
				     <?php }?> 
		</td>


		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_vendedor1();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

	<tr><td>&nbsp;</td></tr>

</table>
<div id="guardar_vendedor"></div>
</from>
