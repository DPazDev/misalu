<?php
   include ("../../lib/jfunciones.php");
   sesion();

 $usuario=$_REQUEST['parausuario'];
 $dpto=$_REQUEST['paradpto'];


if($dpto > 0){

$qdepto=("select admin.id_admin, departamentos.id_departamento, departamentos.departamento from admin, departamentos where departamentos.id_departamento='$dpto' and admin.id_departamento='$dpto' and admin.activar='1'");
$rdepto=ejecutar($qdepto);
?>

<table class="tabla_citas" cellpadding=0 cellspacing=0 > 
<tr>
	<td class="titulo_seccion">Reporte Permisos Asignados a los Usuarios</td>     
</tr>

<?php
	     while($fdepto=asignar_a($rdepto,NULL,PGSQL_ASSOC)){


$qnom=("select admin.nombres, admin.apellidos, admin.id_departamento, admin.id_sucursal, admin.id_tipo_admin, admin.id_cargo, admin.login, admin.cedula from admin where admin.id_admin=$fdepto[id_admin] and admin.activar='1'");
$rnom=ejecutar($qnom);
$fnom=asignar_a($rnom);

$q_re=("select tipos_admin.tipo_admin, cargos.cargo, sucursales.sucursal, departamentos.departamento from tipos_admin, cargos, sucursales, departamentos where tipos_admin.id_tipo_admin='$fnom[id_tipo_admin]' and cargos.id_cargo='$fnom[id_cargo]' and sucursales.id_sucursal='$fnom[id_sucursal]' and departamentos.id_departamento='$fnom[id_departamento]'");
$r_re=ejecutar($q_re);
$f_re=asignar_a($r_re);
?>

<table class=tabla_citas"  cellpadding=0 cellspacing=0 >
<tr><td>&nbsp;</td></tr>

<tr>
<td colspan=1 class="tdcampos"> USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[nombres]." ".$fnom[apellidos] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> LOGIN: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[login] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> CEDULA: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[cedula] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> TIPO DE USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[tipo_admin] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> CARGO </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[cargo] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> SUCURSAL: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[sucursal] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> DEPARTAMENTO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[departamento] ; ?></td>
</tr>

</table>
<table class=tabla_citas"  cellpadding=0 cellspacing=0 rules="rows">
<tr><td>&nbsp;</td></tr>
<td colspan=1 class="tdcampos"> TIPO MODULO: </td>

	<td colspan=1 class="tdcampos">&nbsp; &nbsp; MODULO: </td>
<?php
	
$q_infor=("select admin.id_admin,admin.nombres, departamentos.departamento,tbl_002.tip_mod, tbl_001.modulo  from admin,tbl_001, tbl_003,departamentos,tbl_002 where admin.id_admin=$fdepto[id_admin] and  admin.id_departamento=departamentos.id_departamento 
and tbl_003.id_usuario=admin.id_admin and
tbl_001.id_modulo=tbl_003.id_modulo and
tbl_002.id_tipmod=tbl_001.id_tipmod order by tbl_002.tip_mod, tbl_001.modulo");
$r_infor=ejecutar($q_infor);

 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
	<tr> 
	        <td colspan=1 class=\"tdtitulos\">&nbsp;$f_infor[tip_mod]</td> 
		<td class=\"tdtituloss\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$f_infor[modulo] </td>

</tr>";} ?>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<?php } }

else {
$qnom=("select admin.nombres, admin.apellidos, admin.id_departamento, admin.id_sucursal, admin.id_tipo_admin, admin.id_cargo, admin.login, admin.cedula from admin where admin.id_admin=$usuario");
$rnom=ejecutar($qnom);
$fnom=asignar_a($rnom);

$q_re=("select tipos_admin.tipo_admin, cargos.cargo, sucursales.sucursal, departamentos.departamento from tipos_admin, cargos, sucursales, departamentos where tipos_admin.id_tipo_admin='$fnom[id_tipo_admin]' and cargos.id_cargo='$fnom[id_cargo]' and sucursales.id_sucursal='$fnom[id_sucursal]' and departamentos.id_departamento='$fnom[id_departamento]'");
$r_re=ejecutar($q_re);
$f_re=asignar_a($r_re);

?>

<table class="tabla_citas" cellpadding=0 cellspacing=0 > 
<tr>
	<td class="titulo_seccion">Reporte Permisos Asignados a los Usuarios</td>     
</tr>

<table class=tabla_citas"  cellpadding=0 cellspacing=0 >
<tr><td>&nbsp;</td></tr>

<tr>
<td colspan=1 class="tdcampos"> USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[nombres]." ".$fnom[apellidos] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> LOGIN: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[login] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> CEDULA: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[cedula] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> TIPO DE USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[tipo_admin] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> CARGO </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[cargo] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> SUCURSAL: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[sucursal] ; ?></td>
</tr>

<tr>
<td colspan=1 class="tdcampos"> DEPARTAMENTO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[departamento] ; ?></td>
</tr>

</table>
<table class=tabla_citas"  cellpadding=0 cellspacing=0 rules="rows">
<tr><td>&nbsp;</td></tr>
<td colspan=1 class="tdcampos"> TIPO MODULO: </td>

	<td colspan=1 class="tdcampos">&nbsp; &nbsp; MODULO: </td>
<?php 

$q_infor=("select admin.id_admin,admin.nombres, departamentos.departamento,tbl_002.tip_mod, tbl_001.modulo  from admin,tbl_001, tbl_003,departamentos,tbl_002 where admin.id_admin=$usuario and  admin.id_departamento=departamentos.id_departamento 
and tbl_003.id_usuario=admin.id_admin and
tbl_001.id_modulo=tbl_003.id_modulo and
tbl_002.id_tipmod=tbl_001.id_tipmod order by tbl_002.tip_mod, tbl_001.modulo");
$r_infor=ejecutar($q_infor);

 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
	<tr> 
	        <td colspan=1 class=\"tdtitulos\">&nbsp;$f_infor[tip_mod]</td> 
		<td class=\"tdtituloss\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$f_infor[modulo] </td>

</tr>";} ?>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>

<?php }?>



</table>

	<tr>
	        <td colspan=9 class="tdcamposs" title="Imprimir reporte">
	<?php
		$url="'views06/ireporte_permisos.php?usuario=$usuario&dpto=$dpto'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a> <?php
