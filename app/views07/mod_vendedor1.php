<?php

/* Nombre del Archivo: mod_vendedor.php
   Descripción: Formulario que muestra los datos para MODIFICAR un VENDEDOR en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$ci1=$_REQUEST['ci1'];
$nombre1=$_REQUEST['nombre1'];

$q_vendedor="select comisionados.* from comisionados where (comisionados.cedula='$ci1' or comisionados.id_comisionado='$nombre1');";
$r_vendedor=ejecutar($q_vendedor);
$f_vendedor=asignar_a($r_vendedor);

$q_actividad="select actividades_pro.* from actividades_pro where actividades_pro.id_tipo_pro=$f_vendedor[id_tipo_pro] and actividades_pro.id_act_pro=$f_vendedor[id_act_pro];";
$r_actividad=ejecutar($q_actividad);
$f_actividad=asignar_a($r_actividad);

$q_tipo="select tipo_comisionado.* from tipo_comisionado;";
$r_tipo=ejecutar($q_tipo);

$q_naturaleza="select actividades_pro.* from actividades_pro;";
$r_naturaleza=ejecutar($q_naturaleza);


?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Vendedor </td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" maxlength=150 size=25 value="<?php echo $f_vendedor['nombres'];?>"></td>
			<input type="hidden" name="id_admin1" value="<?php echo $f_vendedor['id_comisionado'];?>">
			<input type="hidden" name="id_admin" value="<?php echo $f_vendedor['id_admin'];?>">

		<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" maxlength=150 size=25 value="<?php echo $f_vendedor['apellidos'];?>"></td>
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * C&eacute;dula &oacute RIF</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" maxlength=150 size=25 value="<?php echo $f_vendedor['cedula'];?>"></td>

		<td colspan=1 class="tdtitulos">* N&uacute;mero Celular</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular" maxlength=32 size=25 value="<?php echo $f_vendedor['celular'];?>"></td>

		
	</tr>

	<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Direcci&oacute;n Fiscal</td>
		<td colspan=1><textarea  class="campos" name="direccion" cols=23 rows=5><?php echo $f_vendedor['direccion'];?></textarea></td>
	
		
		<td colspan=1 class="tdtitulos">* Correo Electr&oacute;nico</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="email" maxlength=255 size=25 value="<?php echo $f_vendedor['email'];?>"></td>
	</tr>

	<tr><td>&nbsp;</td></tr>
	<tr>
	<td colspan=1 class="tdtitulos">* Tipo de Vendedor</td>
		<td colspan=1><select name="tipo" class="campos" style="width:210px;">
		<option value="">-- Tipo de Vendedor --</opcion>
		<?php 
		while($f_tipo=asignar_a($r_tipo)){
			if($f_tipo['id_tipo_comisionado']==$f_vendedor['id_tipo_comisionado'])
			echo "<option value=\"".$f_tipo['id_tipo_comisionado']."\"selected >".$f_tipo['tipo_comisionado']."</option>";
			else
			echo "<option value=".$f_tipo['id_tipo_comisionado'].">".$f_tipo['tipo_comisionado']."</opcion>";
}?> 
		</select>
	</tr>

<tr><td>&nbsp;</td></tr>
	  <tr>
     

 		<td class="tdtitulos" colspan="1">Clasificaci&oacute;n Jur&iacute;dica del Vendedor:</td>
		<td class="tdcampos"  colspan="1">
                    <select id="tipodeprovee" class="campos" onchange="$('proact').hide(),actividprov(); return false;" style="width: 210px;" >
                             
	<? if($f_vendedor['id_tipo_pro']==1)
         	$tipopro='Natural';
           else if($tipodeprovee==0)
                $tipopro='Juridica';
    		echo "<option value=\"".$f_vendedor['id_tipo_pro']."\"selected >".$tipopro."</option>";?>
		<option value="0"></option>
        	<option value="1">Natural</option>
        	<option value="2">Juridica</option>

        	</select>

 </td>
          <td class="tdtitulos" colspan="1">Actividad del Vendedor:</td>   
          <td class="tdcampos" colspan="1"><div id="proact"><select disabled="disabled" class="campos" style="width: 210px;" >

	                               <option value="0"><?echo $f_actividad['actividad']?></option>

				       </select>
	</div> <div id="actividad"></div></td>

      </tr>


	<tr><td>&nbsp;</td></tr>



	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_vendedor1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

	<tr><td>&nbsp;</td></tr>

</table>
