<?php
   include ("../../lib/jfunciones.php");
   sesion();

 $usuario=$_REQUEST['usuario'];
 $dpto=$_REQUEST['dpto'];

if($dpto > 0){

$qdepto=("select admin.id_admin, departamentos.id_departamento, departamentos.departamento from admin, departamentos where departamentos.id_departamento='$dpto' and admin.id_departamento='$dpto' and admin.activar='1'");
$rdepto=ejecutar($qdepto);
?>

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas" cellpadding=0 cellspacing=0 > 

<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
	<td colspan=20 class="descrip_main">Reporte Permisos Asignados a los Usuarios</td>     
</tr>


<tr><td >&nbsp;</td></tr>

<?php
	     while($fdepto=asignar_a($rdepto,NULL,PGSQL_ASSOC)){


$qnom=("select admin.nombres, admin.apellidos, admin.id_departamento, admin.id_sucursal, admin.id_tipo_admin, admin.id_cargo, admin.login, admin.cedula from admin where admin.id_admin=$fdepto[id_admin] and admin.activar='1'");
$rnom=ejecutar($qnom);
$fnom=asignar_a($rnom);

$q_re=("select tipos_admin.tipo_admin, cargos.cargo, sucursales.sucursal, departamentos.departamento from tipos_admin, cargos, sucursales, departamentos where tipos_admin.id_tipo_admin='$fnom[id_tipo_admin]' and cargos.id_cargo='$fnom[id_cargo]' and sucursales.id_sucursal='$fnom[id_sucursal]' and departamentos.id_departamento='$fnom[id_departamento]'");
$r_re=ejecutar($q_re);
$f_re=asignar_a($r_re);
?>


<tr><td >&nbsp;</td></tr>


<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[nombres]." ".$fnom[apellidos] ; ?></td>
</tr>


<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td class="descrip_main"> LOGIN: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[login] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td class="descrip_main"> CEDULA: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[cedula] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> TIPO DE USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[tipo_admin] ; ?></td>
</tr>

<tr>
<td colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> CARGO </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[cargo] ; ?></td>
</tr>

<tr>
<td colspan=10  class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> SUCURSAL: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[sucursal] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> DEPARTAMENTO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[departamento] ; ?></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=10  class="descrip_main"> &nbsp; &nbsp; </td>
<td class="tdcampos"> TIPO MODULO: </td>
	<td  class="tdcampos">&nbsp; &nbsp; MODULO: </td>
</tr>
<?php 
	
$q_infor=("select admin.id_admin,admin.nombres, departamentos.departamento,tbl_002.tip_mod, tbl_001.modulo  from admin,tbl_001, tbl_003,departamentos,tbl_002 where admin.id_admin=$fdepto[id_admin] and  admin.id_departamento=departamentos.id_departamento 
and tbl_003.id_usuario=admin.id_admin and
tbl_001.id_modulo=tbl_003.id_modulo and
tbl_002.id_tipmod=tbl_001.id_tipmod order by tbl_002.tip_mod, tbl_001.modulo");
$r_infor=ejecutar($q_infor);

 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
<tr> 
<td colspan=10 class=\"tdtitulos\"> &nbsp; &nbsp; </td>
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

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas" cellpadding=0 cellspacing=0 > 

<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
	<td colspan=20 class="descrip_main">Reporte Permisos Asignados a los Usuarios</td>     
</tr>


<tr><td >&nbsp;</td></tr>


<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[nombres]." ".$fnom[apellidos] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> LOGIN: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[login] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> CEDULA: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fnom[cedula] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> TIPO DE USUARIO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[tipo_admin] ; ?></td>
</tr>

<tr>
<td colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> CARGO </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[cargo] ; ?></td>
</tr>

<tr>
<td colspan=10  class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> SUCURSAL: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[sucursal] ; ?></td>
</tr>

<tr>
<td  colspan=10 class="descrip_main"> &nbsp; &nbsp; </td>
<td  class="descrip_main"> DEPARTAMENTO: </td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $f_re[departamento] ; ?></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr><td>&nbsp;</td></tr>
<tr>
<td colspan=10  class="descrip_main"> &nbsp; &nbsp; </td>
<td class="tdcampos"> TIPO MODULO: </td>
	<td  class="tdcampos">&nbsp; &nbsp; MODULO: </td>
</tr>
<?php 


	
$q_infor=("select admin.id_admin,admin.nombres, departamentos.departamento,tbl_002.tip_mod, tbl_001.modulo  from admin,tbl_001, tbl_003,departamentos,tbl_002 where admin.id_admin=$usuario and  admin.id_departamento=departamentos.id_departamento 
and tbl_003.id_usuario=admin.id_admin and
tbl_001.id_modulo=tbl_003.id_modulo and
tbl_002.id_tipmod=tbl_001.id_tipmod order by tbl_002.tip_mod, tbl_001.modulo");
$r_infor=ejecutar($q_infor);




 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
	<tr> 
<td colspan=10 class=\"tdtitulos\"> &nbsp; &nbsp; </td>
	        <td colspan=1 class=\"tdtitulos\">&nbsp;$f_infor[tip_mod]</td> 
		<td class=\"tdtituloss\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$f_infor[modulo] </td>

</tr>



";} }?>






<tr><td>&nbsp;</td></tr>

</table>





