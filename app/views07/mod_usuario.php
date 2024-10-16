<?php

/* Nombre del Archivo: mod_usuario.php
   Descripción: Formulario que muestra los datos para REGISTRAR Y MODIFICAR un USUARIO en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();

$ci=$_REQUEST['ci'];
$nombre=$_REQUEST['nombre'];


$q_usuario="select admin.*,ciudad.id_estado,estados.id_pais,permisos.id_modulo,permisos.permiso from admin,ciudad,estados,pais,permisos  where admin.id_ciudad=ciudad.id_ciudad and ciudad.id_estado=estados.id_estado and estados.id_pais=pais.id_pais and (admin.cedula='$ci' or admin.id_admin='$nombre');";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);
/*echo $f_usuario['id_ente'];
echo $f_usuario['id_modulo'];
echo $f_usuario['id_admin'];*/



$q_cargo = "select cargos.id_cargo,cargos.cargo from cargos order by cargos.cargo";
$r_cargo = ejecutar($q_cargo);

$q_departamento = "select departamentos.id_departamento,departamentos.departamento from departamentos order by departamentos.departamento";
$r_departamento = ejecutar($q_departamento);

$q_sucursal = "select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursales.sucursal";
$r_sucursal = ejecutar($q_sucursal);

$q_pais = "select pais.id_pais,pais.pais from pais order by pais.pais";
$r_pais = ejecutar($q_pais);

$q_estado = "select estados.id_estado,estados.estado from estados order by estados.estado";
$r_estado = ejecutar($q_estado);

$q_ciudad = "select ciudad.id_ciudad,ciudad.ciudad from ciudad order by ciudad.ciudad";
$r_ciudad = ejecutar($q_ciudad);

$q_tipo_usuario = "select tipos_admin.id_tipo_admin,tipos_admin.codigo_admin,tipos_admin.tipo_admin from tipos_admin order by tipos_admin.tipo_admin ";
$r_tipo_usuario = ejecutar($q_tipo_usuario);

$q_modulo = "select modulos.id_modulo,modulos.nombre_grupo from modulos order by modulos.nombre_grupo";
$r_modulo = ejecutar($q_modulo);


$q_ente = "select entes.id_ente, entes.nombre from entes order by entes.nombre";
$r_ente = ejecutar($q_ente);


?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<?php if($ci==$f_usuario[cedula] or $nombre==$f_usuario[id_admin]){?>

	<tr>
		<td colspan=4 class="titulo_seccion"> Modificar Usuario</td>
	</tr>	
<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" id="nombres" maxlength=150 size=25 value="<?php echo $f_usuario['nombres'];?>"></td>
			<input type="hidden" name="id_admin1" id="id_admin1" value="<?php echo $f_usuario['id_admin'];?>">
		<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" id="apellidos"  maxlength=150 size=25 value="<?php echo $f_usuario['apellidos'];?>"></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* C&eacute;dula</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" id="cedula"  maxlength=150 size=25 value="<?php echo $f_usuario['cedula'];?>"></td>

		<td colspan=1 class="tdtitulos">* Cargo</td>
 		<td colspan=1 ><select name="cargo" id="cargo" class="campos" style="width: 210px;">

		<?php
		while($f_cargo = asignar_a($r_cargo)){
			if($f_cargo['id_cargo']==$f_usuario['id_cargo'])
			echo "<option value=\"".$f_cargo['id_cargo']."\"selected >".$f_cargo['cargo']."</option>";
			else
			echo "<option value=\"".$f_cargo['id_cargo']."\">".$f_cargo['cargo']."</option>";
		}
		?>
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Departamento</td>
 		<td colspan=1 ><select name="depart" id="depart" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el Departamento --</option>
		<?php
		while($f_departamento = asignar_a($r_departamento)){

			if($f_departamento['id_departamento']==$f_usuario['id_departamento'])
			echo "<option value=\"".$f_departamento['id_departamento']."\"selected >".$f_departamento['departamento']."</option>";
			else
			echo "<option value=".$f_departamento['id_departamento'].">".$f_departamento['departamento']."</option>";
		}
		?>
		</select>

		<td colspan=1 class="tdtitulos">* Sucursal a la que pertenece</td>
		<td colspan=1><select name="sucursal" id="sucursal" class="campos" style="width:210px;">
		<option value="">-- Seleccione la Sucursal --</opcion>
		<?php
		while($f_sucursal = asignar_a($r_sucursal)){
			if($f_sucursal['id_sucursal']==$f_usuario['id_sucursal'])
			echo "<option value=\"".$f_sucursal['id_sucursal']."\"selected >".$f_sucursal['sucursal']."</option>";
			else
			echo "<option value=".$f_sucursal['id_sucursal'].">".$f_sucursal['sucursal']."</opcion>";
}?> 
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Login</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="login" id="login" maxlength=65 size=25 value="<?php echo $f_usuario['login'];?>"></td>
		
		<td colspan=1 class="tdtitulos">* Correo Electr&oacute;nico</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="email" id="email"  maxlength=255 size=25 value="<?php echo $f_usuario['email'];?>"></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="telef" id="telef" maxlength=32 size=25 value="<?php echo $f_usuario['telefonos'];?>"></td>
	
		<td colspan=1 class="tdtitulos">* Celular</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular" id="celular" maxlength=32 size=25 value="<?php echo $f_usuario['celular'];?>"></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Ente</td>
 		<td colspan=1 ><select name="ente" id="ente" class="campos" style="width: 210px;">
		<option value="0@TODOS LOS ENTES">TODOS LOS ENTES</option>
		<?php
		while($f_ente = asignar_a($r_ente)){

		 if($f_ente['id_ente']==$f_usuario['id_ente'])
			echo "<option value=\"".$f_ente['id_ente']."\"selected >".$f_ente['nombre']."</option>";		
		else 
			echo "<option value=".$f_ente['id_ente'].">".$f_ente['nombre']."</option>";
		}
		?>
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Direcci&oacute;n</td>
		<td colspan=1><textarea  class="campos" name="direccion" id="direccion" cols=23 rows=5><?php echo $f_usuario['direccion'];?></textarea></td>
	
		<td colspan=1 class="tdtitulos">* Fecha de Nacimiento</td>
		<td colspan=1 class="tdcampos"><?php
				list($ano,$mes,$dia)=split("-",$f_usuario['fecha_nac'],3);
				echo fechas('1','32',$dia,'','dia'); 
				echo fechas('1','13',$mes,'','mes');
				echo fechas('1940','2020',$ano,'','ano'); 
				?></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Pa&iacute;s</td>
 		<td colspan=1 ><select name="pais" id="pais" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el País --</option>
		<?php
		while($f_pais = asignar_a($r_pais)){
			if($f_pais['id_pais']==$f_usuario['id_pais'])
			echo "<option value=\"".$f_pais['id_pais']."\"selected>".$f_pais['pais']."</option>";
			else
			echo "<option value=".$f_pais['id_pais'].">".$f_pais['pais']."</option>";
		}
		?>
		</select>
	
		<td colspan=1 class="tdtitulos">* Estado</td>
		<td colspan=1><select name="estado" id="estado" class="campos" style="width:210px;">
		<option value="">-- Seleccione el Estado --</opcion>
		<?php
		while($f_estado = asignar_a($r_estado)){
			if($f_estado['id_estado']==$f_usuario['id_estado'])			
			echo "<option value=\"".$f_estado['id_estado']."\"selected>".$f_estado['estado']."</opcion>";
			else
			echo "<option value=".$f_estado['id_estado'].">".$f_estado['estado']."</opcion>";
}?> 
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Ciudad</td>
		<td colspan=1><select name="ciudad" id="ciudad" class="campos" style="width:210px;">
		<option value="">-- Seleccione la Ciudad --</opcion>
		<?php
		while($f_ciudad = asignar_a($r_ciudad)){
			if($f_ciudad['id_ciudad']==$f_usuario['id_ciudad'])
			echo "<option value=\"".$f_ciudad['id_ciudad']."\"selected>".$f_ciudad['ciudad']."</opcion>";
			else
			echo "<option value=".$f_ciudad['id_ciudad'].">".$f_ciudad['ciudad']."</opcion>";
}?> 
		</select>
	
		<td colspan=1 class="tdtitulos">* Tipo de Usuario</td>
		<td colspan=1><select name="tipo" id="tipo" class="campos" style="width:210px;">
		<option value="">-- Seleccione el Tipo de Usuario --</opcion>
		<?php
		while($f_tipo_usuario = asignar_a($r_tipo_usuario)){
			if($f_tipo_usuario['id_tipo_admin']==$f_usuario['id_tipo_admin'])
			echo "<option value=\"".$f_tipo_usuario['id_tipo_admin']."\"selected>".$f_tipo_usuario['tipo_admin']."</opcion>";
			else
			echo "<option value=".$f_tipo_usuario['id_tipo_admin'].">".$f_tipo_usuario['tipo_admin']."</opcion>";

}?> 
		</select>

	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Activar Usuario</td>
<?
		if($f_usuario['activar']=='1'){?>
		<td colspan=1 class="tdtitulos"><input class="campos" type="radio" id="activar" name="activar" checked value="1">Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			      <input class="campos" type="radio" id="activar" name="activar" value="0">No</td>
<?}
		else
		if($f_usuario['activar']=='0'){?>
		<td colspan=1 class="tdtitulos"><input class="campos" type="radio" id="activar" name="activar"  value="1">Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			      <input class="campos" type="radio" id="activar" name="activar" checked value="0">No</td>
<?}
?>



		<td colspan=1 class="tdtitulos">* Comentarios</td>
		<td colspan=1><textarea  class="campos" name="comentarios" id="comentarios" cols=23 rows=5><?php echo $f_usuario['comentarios'] ;?></textarea></td>
	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Activar Permiso</td>

	</tr>
       <?php       
	$j=0;
	
	while($f_modulo=asignar_a($r_modulo)){
	$j++;

$q_permiso = "select permisos.permiso from permisos where permisos.id_modulo=$f_modulo[id_modulo] and permisos.id_admin=$f_usuario[id_admin]";
$r_permiso = ejecutar($q_permiso);
$f_permiso=asignar_a($r_permiso);
	?>		
		<td colspan=1 class="tdtitulos">&nbsp;</td>

		<td colspan=1 class="tdtitulos"><?php echo $f_modulo[nombre_grupo]?></td>
		<td>

<?	if($f_permiso['permiso']=='1'){?>

	<input type="checkbox"  id="campo_<?php echo $j?>" name="campo" checked value="1">

	<input class="campos" type="hidden"  id="valor_<?php echo $j?>"  name="valor" value="<?php echo $f_modulo[id_modulo] ?>"   >
<?}
	else							
{?>
	<input type="checkbox"  id="campo_<?php echo $j?>" name="campo" value="0">

	<input class="campos" type="hidden"  id="valor_<?php echo $j?>"  name="valor" value="<?php echo $f_modulo[id_modulo] ?>"   >
<?}?>

	</tr>
<?}?>
	<input class="campos" type="hidden"  id="nu_modulo"  name="nu_modulo" value="<?php echo $j ?>"   >
<br>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_usuario1();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

<? } 

	 else {?>


	<tr>
		<td colspan=4 class="titulo_seccion"> Registrar Usuario</td>
	</tr>	
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="nombres" id="nombres" maxlength=150 size=25 value=""></td>
	
		<td colspan=1 class="tdtitulos">* Apellidos</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="apellidos" id="apellidos" maxlength=150 size=25 value=""></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* C&eacute;dula</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="cedula" id="cedula" maxlength=150 size=25 value="<?php echo $ci;?>"></td>
	
		<td colspan=1 class="tdtitulos">* Cargo</td>
 		<td colspan=1 ><select name="cargo" id="cargo" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el Cargo --</option>
		<?php
		while($f_cargo = asignar_a($r_cargo)){
			echo "<option value=".$f_cargo['id_cargo'].">".$f_cargo['cargo']."</option>";
		}
		?>
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Departamento</td>
 		<td colspan=1 ><select name="depart" id="depart" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el Departamento --</option>
		<?php
		while($f_departamento = asignar_a($r_departamento)){
			echo "<option value=".$f_departamento['id_departamento'].">".$f_departamento['departamento']."</option>";
		}
		?>
		</select>

		<td colspan=1 class="tdtitulos">* Sucursal a la que pertenece</td>
		<td colspan=1><select name="sucursal" id="sucursal" class="campos" style="width:210px;">
		<option value="">-- Seleccione la Sucursal --</opcion>
		<?php
		while($f_sucursal = asignar_a($r_sucursal)){
			echo "<option value=".$f_sucursal['id_sucursal'].">".$f_sucursal['sucursal']."</opcion>";
}?> 
		</select>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Login</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="login" id="login" maxlength=65 size=25 value=""></td>
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Password</td>
		<td colspan=3 class="tdcampos"><input class="campos" type="password" name="passw" id="passw" maxlength=65 size=25 value="">    EL PASSWORD DEBE CONTENER 8 CARACTERES, FORMADOS POR LETRAS Y NUMEROS   </td>
</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Re-Password</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="password" name="passw1" id="passw1" maxlength=65 size=25 value=""></td>
	
		<td colspan=1 class="tdtitulos">* Correo Electr&oacute;nico</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="email" id="email" maxlength=255 size=25 value=""></td>
	</tr>

	<tr>
		<td colspan=1 class="tdtitulos">* Tel&eacute;fono</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="telef" id="telef"  maxlength=32 size=25 value=""></td>
	
		<td colspan=1 class="tdtitulos">* Celular</td>
		<td colspan=1 class="tdcampos"><input class="campos" type="text" name="celular" id="celular" maxlength=32 size=25 value=""></td>
	</tr>

<tr>
<td colspan=1 class="tdtitulos">* Ente</td>
 		<td colspan=1 ><select name="ente" id="ente" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el Ente --</option>
		<option value="0@TODOS LOS ENTES">TODOS LOS ENTES</option>
		<?php
		while($f_ente = asignar_a($r_ente)){
			echo "<option value=".$f_ente['id_ente'].">".$f_ente['nombre']."</option>";
		}
		?>
		</select>
	</tr>



	<tr>
		<td colspan=1 class="tdtitulos">* Direcci&oacute;n</td>
		<td colspan=1><textarea  class="campos" name="direccion" id="direccion" cols=23 rows=5></textarea></td>

	

		<td colspan=1 class="tdtitulos">* Fecha de Nacimiento</td>
		<td colspan=1 class="tdcampos"><?php
				list($ano,$mes,$dia)=split("-",$f_admin['fecha_nac'],3);
				echo fechas('1','32',$dia,'','dia'); 
				echo fechas('1','13',$mes,'','mes');
				echo fechas('1940','2020',$ano,'','ano'); 
				?></td>

	</tr>

	<tr>

		<td colspan=1 class="tdtitulos">* Pa&iacute;s</td>
 		<td colspan=1 ><select name="pais" id="pais" class="campos" style="width: 210px;">
		<option value="">-- Seleccione el País --</option>
		<?php
		while($f_pais = asignar_a($r_pais)){
			echo "<option value=".$f_pais['id_pais'].">".$f_pais['pais']."</option>";
		}
		?>
		</select>
	
		<td colspan=1 class="tdtitulos">* Estado</td>
		<td colspan=1><select name="estado" id="estado"  class="campos" style="width:210px;">
		<option value="">-- Seleccione el Estado --</opcion>
		<?php
		while($f_estado = asignar_a($r_estado)){
			echo "<option value=".$f_estado['id_estado'].">".$f_estado['estado']."</opcion>";
}?> 
		</select>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Ciudad</td>
		<td colspan=1><select name="ciudad" id="ciudad" class="campos" style="width:210px;">
		<option value="">-- Seleccione la Ciudad --</opcion>
		<?php
		while($f_ciudad = asignar_a($r_ciudad)){
			echo "<option value=".$f_ciudad['id_ciudad'].">".$f_ciudad['ciudad']."</opcion>";
}?> 
		</select>
	
		<td colspan=1 class="tdtitulos">* Tipo de Usuario</td>
		<td colspan=1><select name="tipo" id="tipo" class="campos" style="width:210px;">
		<option value="">-- Seleccione el Tipo de Usuario --</opcion>
		<?php
		while($f_tipo_usuario = asignar_a($r_tipo_usuario)){
			echo "<option value=".$f_tipo_usuario['id_tipo_admin'].">".$f_tipo_usuario['tipo_admin']."</opcion>";
}?> 
		</select>
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Activar Usuario</td>
		<td colspan=1 class="tdtitulos"><input class="campos" type="radio" id="activar" name="activar" checked value="1">Si&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			      <input class="campos" type="radio" id="activar" name="activar" value="0">No</td>
	
		<td colspan=1 class="tdtitulos">* Comentarios</td>
		<td colspan=1><textarea  class="campos" name="comentarios" cols=23 rows=5></textarea></td>


	</tr>
	<tr>

    			<td colspan=1 class="tdtitulos">* Activar Permiso</td>
	<tr></tr>
       <?php       
	$j=0;

	while($f_modulo=asignar_a($r_modulo)){
	$j++;
	
	?>		
		<td colspan=1 class="tdtitulos">&nbsp;</td>

		<td colspan=1 class="tdtitulos"><?php echo $f_modulo[nombre_grupo]?></td>
		<td>

	<input type="checkbox"  id="campo_<?php echo $j?>" name="campo" value="">

	<input class="campos" type="hidden"  id="valor_<?php echo $j?>"  name="valor" value="<?php echo $f_modulo[id_modulo] ?>"   >
						
	</tr>
<?}?>

	<input class="campos" type="hidden"  id="nu_modulo"  name="nu_modulo" value="<?php echo $j ?>"   >
	
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_usuario();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
<? }?>


	<tr><td>&nbsp;</td></tr>
	</table>
