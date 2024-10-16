<?php
include ("../../lib/jfunciones.php");
sesion();
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js">
</script>
<?php
$tmodulos=$_REQUEST['tmodulos'];
$id_usuario=$_REQUEST['usuario'];
if(empty($id_usuario))	mensaje("No existen los parámetros para hacer esta operación.");

$q_usuario="select * from admin where id_admin='$id_usuario'";
$r_usuario=ejecutar($q_usuario);

if(num_filas($r_usuario)==0)	mensaje("No existe al menos un operador con esos parámetros.");
$f_usuario=asignar_a($r_usuario);
?>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
<tr>		<td align="right" colspan=2 class="titulo_seccion"><b>Permisologia del Usuario <?php echo $f_usuario[nombres]; ?> <?php echo $f_usuario[apellidos]; ?> <?php echo $f_usuario[cedula]; ?> Actualizada</b> </td>	
	</tr>
</table>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=3 align="left"><a href="panel.php" class="boton">panel</a></td>
		<td colspan=3 align="right"><a href="#" onClick="reg_perusuario();" class="boton">Actualizar Otro Usuario</a></td>
	</tr>	
</table>

<?php
$no_secciones=$_REQUEST['modulo'];

$id_modulo=$_REQUEST['id_modulo'];
$id_modulos=split("@",$id_modulo);

$q="
begin work;
delete from tbl_003 using tbl_001  where tbl_003.id_usuario='$id_usuario' and tbl_003.id_modulo=tbl_001.id_modulo and tbl_001.id_tipmod=$tmodulos;
";
for($i=0;$i<=$no_secciones;$i++){
	$seccion=$id_modulos[$i];

	if(!empty($seccion) && $seccion>0){
		
		$q.="insert into tbl_003 (id_usuario, id_modulo) values ('$id_usuario','$seccion');";
	}
}
$q.="
commit work;
";
$r=ejecutar($q);
?>
