<?
/* *** Nombre del Archivo: ver_usuario.php 
       Descripción: Realiza la busqueda en la Base de Datos, para el modificar un usuario *** */

	include ("../../lib/jfunciones.php");
	sesion();
	$usu=$_REQUEST['ci'];
	$usu = strtoupper($usu);

if($usu==" ")	        $condicion_usu="";
else
$condicion_usu="admin.nombres like(upper('%$usu%'))";


$q_usuario=("select admin.* from admin where $condicion_usu ");
$r_usuario=ejecutar($q_usuario);

?>

<table class="tabla_citas" cellpadding=0 cellspacing=0 > 

<tr>
	<td class="titulo_seccion">Usuarios </td> 
    
</tr>

</table>
&nbsp;

<table class=tabla_citas"  cellpadding=0 cellspacing=0 >
<tr>
	<td colspan=1 class="tdcampos">NOMBRE Y APELLIDO</td>&nbsp; &nbsp;
	<td colspan=1 class="tdcampos">CEDULA</td>&nbsp; &nbsp;
	<td colspan=1 class="tdcampos">LOGIN</td>&nbsp; &nbsp;


</tr>

<?

 while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){
	
$q_infor=("select admin.* from admin where admin.nombres='$f_usuario[nombres]'  ORDER BY nombres DESC");
$r_infor=ejecutar($q_infor);
 while($f_infor=asignar_a($r_infor,NULL,PGSQL_ASSOC)){
echo"
	<tr> 
		<td class=\"tdtituloss\">$f_infor[nombres] $f_infor[apellidos]</td> &nbsp; &nbsp;
		<td class=\"tdtituloss\">$f_infor[cedula]</td>&nbsp; &nbsp;
		<td class=\"tdtituloss\">$f_infor[login]</td>&nbsp; &nbsp;
  
	</tr>&nbsp;"; }}?>
</table>
<br>

