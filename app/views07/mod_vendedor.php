<?php

/* Nombre del Archivo: mod_vendedor.php
   Descripción: Formulario que muestra los datos para MODIFICAR un VENDEDOR en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$ci=$_REQUEST['ci'];
$nombre=$_REQUEST['nombre'];

$q_usuario="select admin.* from admin where (admin.cedula='$ci' or admin.id_admin='$nombre');";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);


$q_tipo="select tipo_comisionado.* from tipo_comisionado;";
$r_tipo=ejecutar($q_tipo);

$q_adminis="select actividades_pro.* from actividades_pro;";
$r_adminis=ejecutar($q_adminis);



?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<?php if($f_usuario[cedula]==$ci or $f_usuario[id_admin]==$nombre){?>

	<tr>
		<td colspan=4 class="titulo_seccion"> Registrar Vendedor </td>
	</tr>	

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=150 size=25 value="<?php echo $f_usuario['nombres'];?>"></td>
			<input type="hidden" name="id_admin1" value="<?php echo $f_usuario['id_admin'];?>">

		<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" maxlength=150 size=25 value="<?php echo $f_usuario['apellidos'];?>"></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * C&eacute;dula &oacute RIF</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=150 size=25 value="<?php echo $f_usuario['cedula'];?>"></td>

		<td colspan=1 class="tdtitulos">* N&uacute;mero Celular</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular" maxlength=32 size=25 value="<?php echo $f_usuario['celular'];?>"></td>

		
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Direcci&oacute;n Fiscal</td>
		<td colspan=1><textarea  class="campos" name="direccion" cols=23 rows=5><?php echo $f_usuario['direccion'];?></textarea></td>
	
		
		<td colspan=1 class="tdtitulos">* Correo Electr&oacute;nico</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="email" maxlength=255 size=25 value="<?php echo $f_usuario['email'];?>"></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Tipo de Vendedor: </td>
		<td colspan=1 class="tdcamposcc" ><select id="tipo" name="tipo" class="campos" style="width: 200px;" >
	                              <option value="0">--- Tipo de Vendedor. ---</option>
				      <?php  while($f_tipo=asignar_a($r_tipo,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_tipo[id_tipo_comisionado] ?>"> <?php echo "$f_tipo[tipo_comisionado]"?></option>
				     <?php }?> 
		</td>

	</tr>

	<tr><td>&nbsp;</td></tr>

    <tr>
          <td class="tdtitulos" colspan="1">Clasificaci&oacute;n Jur&iacute;dica del Vendedor:</td>
	  <td class="tdcampos"  colspan="1">
                    <select id="tipodeprovee" class="campos" onchange="$('proact').hide(),actividprov(); return false;" style="width: 210px;" >
                              <option value="0"></option>
                              <option value="1">Natural</option>
                              <option value="2">Juridica</option>
                    </select>
          </td>
          <td class="tdtitulos" colspan="1">Actividad del Vendedor:</td>   
          <td class="tdcampos" colspan="1"><div id="proact"><select disabled="disabled" class="campos" style="width: 130px;" >
	                               <option value="0">

				       </select>
	</div> <div id="actividad"></div></td>

      </tr>

	<tr><td>&nbsp;</td></tr>










	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_vendedor();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

<? } 

 else {?>


	<tr>
		<td colspan=4 class="titulo_seccion"> Registrar Vendedor </td>
	</tr>	

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=150 size=25 value=""></td>
			<input type="hidden" name="id_admin1" value="0">
	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" maxlength=150 size=25 value=""></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * C&eacute;dula &oacute RIF</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=150 size=25 value="<?php echo $ci;?>"></td>
	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Direcci&oacute;n Fiscal</td>
		<td colspan=1><textarea  class="campos" name="direccion" cols=23 rows=5></textarea></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * N&uacute;mero Celular</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular" maxlength=32 size=25 value=""></td>
	
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Correo Electr&oacute;nico</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="email" maxlength=255 size=25 value=""></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Tipo de Vendedor: </td>
		<td colspan=1 class="tdcamposcc" ><select id="tipo" name="tipo" class="campos" style="width: 200px;" >
	                              <option value="0">--- Tipo de Vendedor. ---</option>
				      <?php  while($f_tipo=asignar_a($r_tipo,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_tipo[id_tipo_comisionado] ?>"> <?php echo "$f_tipo[tipo_comisionado]"?></option>
				     <?php }?> 
		</td>

	</tr>

	<tr><td>&nbsp;</td></tr>

    <tr>
          <td class="tdtitulos" colspan="1">Clasificaci&oacute;n Jur&iacute;dica del Vendedor:</td>
	  <td class="tdcampos"  colspan="1">
                    <select id="tipodeprovee" class="campos" onchange="$('proact').hide(),actividprov(); return false;" style="width: 210px;" >
                              <option value="0"></option>
                              <option value="1">Natural</option>
                              <option value="2">Juridica</option>
                    </select>
          </td>
          <td class="tdtitulos" colspan="1">Actividad del Vendedor:</td>   
          <td class="tdcampos" colspan="1"><div id="proact"><select disabled="disabled" class="campos" style="width: 130px;" >
	                               <option value="0">
				       </select>
	</div> <div id="actividad"></div></td>

      </tr>

	<tr><td>&nbsp;</td></tr>




	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_vendedor();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<?} ?>

	<tr><td>&nbsp;</td></tr>

</table>
