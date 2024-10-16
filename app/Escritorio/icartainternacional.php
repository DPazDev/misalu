
<?php
include ("../../lib/jfunciones.php");
sesion();
header( 'Content-Type: text/html;charset=utf-8' ); 
$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */

$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as comen from entes,procesos,gastos_t_b,servicios,admin where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
$cedula="$f_proceso[cedula]";

$q_gastos=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos=ejecutar($q_gastos);
$q_gastos1=("select * from gastos_t_b where gastos_t_b.id_proceso=$proceso");
$r_gastos1=ejecutar($q_gastos1);


?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table   border=0 class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">
<img src="../../public/images/lainternacional.gif">
</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
Rif: J-00338202-7</td>
</tr>

<tr>
<td colspan=1 class="titulo2">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
<?php echo "$f_admin[sucursal] $f_proceso[fecha_recibido]"?>
</td>
</tr>


<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> SOLICTUD DE DONATIVO 

</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<?php 
if ($f_proceso[id_beneficiario]>0 )
{
	$q_beneficiario=("select * from clientes where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_proceso[id_beneficiario]");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
$nombre_cliente="$f_beneficiario[nombres]";
$apellido_cliente="$f_beneficiario[apellidos]";
$cedula="$f_beneficiario[cedula]";
}	
?>
<tr>
<td colspan=4 class="datos_cliente">
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>
</tr>


<?php
 while($f_gastos=asignar_a($r_gastos,NULL,PGSQL_ASSOC)){
$monto=$monto + $f_gastos[monto_reserva];
}

$monto_letra=numeros_a_letras($monto);

?>
<tr>
<td colspan=4 class="datos_cliente1" style="text-align: justify">
Dr. Antonio Guerrero Presidente de CliniSalud Medicina Prepagada S.A. Me es grato dirigirme a usted con la finalidad de saludarle y desarle &eacute;xitos en sus funciones generales y de manifestarle un sincero agradecimiento en virtud a la labor social que usted ha venido realizando y a su vez solicitarle muy respetuosamente su valiosa colaboraci&oacute;n con el servicio  <?php echo "$f_proceso[servicio] Num. $proceso" ?> con <?php echo "$f_proceso[nombre]";  ?>,  tal como lo describe el informe M&eacute;dico. 


</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4  class="datos_cliente">
Sin m&aacute;s a que hacer referencia se despide atentamente de usted  <?php echo "$nombre_cliente $apellido_cliente" ?>, Venezolano portador de la C&eacute;dula de identidad n&uacute;mero <?php echo cedula($cedula);?>.
<br>
</br>
</br>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
__________________
</td>
<td colspan=1  class="titulo3">
__________________
</td>
<td colspan=2 class="titulo3">
__________________
</td>
</tr>

<tr>
<td colspan=1 class="titulo3">
ELABORADO POR:
<?php echo "$f_proceso[adnom] $f_proceso[adapell]" ?>
</td>
<td colspan=1 class="titulo3">
REVISADO POR:
</td>
<td colspan=2 class="titulo3">
<? echo "$nombre_cliente $apellido_cliente C.I. $cedula";?>
</td>
</tr>	
<tr>
<td>
<br>
</br>
</td>
</tr>
<tr>
<td align="right" colspan=4>
"Gesti&oacute;n con Sentido Social..."
<br>
</br>
</td>
</tr>
<tr>
<td>
<br>
</br>
<br>
</br><br>
</br>
<br>
</br>
</td>
</tr>

		



<tr>
<td colspan=4 class="datos_cliente1">
Domicilio Fiscal: Avenida Las Americas, Centro Comercial Mayeya, Nivel Mezzanina, locales 16,17 y 24, M&eacute;rida Edo. M&eacute;rida.
</td>

</tr>

</table>


