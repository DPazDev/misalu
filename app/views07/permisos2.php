<?php
include ("../../lib/jfunciones.php");
sesion();
$tmodulos=$_REQUEST['tmodulos'];
$login=trim(strtolower($_REQUEST['login']));
$q_usuario="select * from admin where admin.id_admin='$login'";
$r_usuario=ejecutar($q_usuario);
$f_usuario=asignar_a($r_usuario);
if(num_filas($r_usuario)==0)	{
echo "El Usuario no Existe";

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="actcliente();" class="boton">Registrar</a></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
	</tr>
	</table>
<?php
}
else
{


//busco las secciones q tenga asignadas.
$q="select * from tbl_003 where id_usuario='$f_usuario[id_admin]';";
$r=ejecutar($q);
$no_asignadas=num_filas($r);
$i=0;


//busco todas las secciones
$q_todas="select * from tbl_001 where tbl_001.id_tipmod=$tmodulos order by modulo asc;";
$r_todas=ejecutar($q_todas);
?>

<input type="hidden" name="usuario" value="<?php echo $f_usuario[id_admin]; ?>">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td align="right" colspan=2 class="titulo_seccion"><b></b> <?php echo $f_usuario[nombres]; ?> <?php echo $f_usuario[apellidos]; ?> <?php echo $f_usuario[cedula]; ?></td>	
	</tr>
</table>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
	<tr><td class="tdtitulos" colspan=3>Secciones</td>
	<td align="right" class="tdtitulos" colspan=3>
	<?php
	$i=0;
	while($f_todas=asignar_a($r_todas)){
$i++;
		pg_result_seek($r,0);
		$ban="";
		while($f=asignar_a($r)){
			if($f['id_modulo']==$f_todas['id_modulo'])	$ban="checked";
		}
		echo "$f_todas[modulo]";

		?>
<input class="campos" type="hidden" id="modulo_<?php echo $i?>" name="nommodulo" maxlength=128 size=20 value="<?php echo $f_todas[id_modulo]?>">
<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkdes" maxlength=128 size=20 value=""><br>
<?php

	}
	echo "<input type=\"hidden\" name=\"modulo\" value=\"$i\">";
	?>

	</td></tr>
		<tr>
		<td colspan=3 align="left"><a href="panel.php" class="boton">panel</a></td>
		<td colspan=3 align="right"><a href="#" onClick="repermisos();" class="boton">siguiente</a></td>
	</tr>	
</table>

<?php
}

?>
