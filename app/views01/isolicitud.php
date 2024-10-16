<?php
include ("../../lib/jfunciones.php");
sesion();

$proceso=$_REQUEST['proceso'];
$si=$_REQUEST['si'];
$fechaimpreso=date("d-m-Y");
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin,sucursales where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* **** busco las citas medicas *** */

$q_proceso=("select entes.nombre as ente,clientes.*,procesos.*,servicios.*,gastos_t_b.*,admin.nombres as adnom, admin.apellidos as adapell,procesos.comentarios as comen from entes,procesos,gastos_t_b,servicios,admin,clientes,titulares where procesos.id_proceso=$proceso and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.id_servicio=servicios.id_servicio  and titulares.id_titular=procesos.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and procesos.id_admin=admin.id_admin");
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
$nombre_cliente="$f_proceso[nombres]";
$apellido_cliente="$f_proceso[apellidos]";
$cedula="$f_proceso[cedula]";
$edad="$f_proceso[fecha_nacimiento]";
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

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo1">
</td>
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
<tr>
<td colspan=4 class="titulo3"> DATOS DEL SOLICITANTE
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
	$q_beneficiario=("select * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_proceso[id_beneficiario]");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
$nombre_cliente="$f_beneficiario[nombres]";
$apellido_cliente="$f_beneficiario[apellidos]";
$cedula="$f_beneficiario[cedula]";
$edad="$f_beneficiario[fecha_nacimiento]";
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
<td colspan=1 class="datos_cliente4">
Nombre Completo:</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[nombres] $f_proceso[apellidos] "?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
C.I. </td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[cedula]"?>
</td>
<td colspan=1 class="datos_cliente4">
Edad </td>
<td colspan=1 class="datos_cliente">
<?php echo calcular_edad($f_proceso[fecha_nacimiento])?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Sexo</td>
<td colspan=1 class="datos_cliente">
<?php if ($f_proceso[sexo]==1) {
	echo "Masculino";
	}
	else
	{
		echo "Femenino";
		}
	?>
</td>
<td colspan=1 class="datos_cliente4">
Tlf.</td>
<td colspan=1 class="datos_cliente">
<?php echo "$f_proceso[telefono_hab] / $f_proceso[telefono_otro] / $f_proceso[celular]"?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Direcci&oacute;n</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[direccion_hab]"?>
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> DATOS DE LA SOLICITUD
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Tipo de Solicitud</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[servicio] orden num $f_proceso[id_proceso]"?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Para:</td>
<td colspan=3 class="datos_cliente">
<?php echo "$nombre_cliente $apellido_cliente"?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
C.I.</td>
<td colspan=3 class="datos_cliente">
<?php echo "$cedula"?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Edad </td>
<td colspan=1 class="datos_cliente">
<?php echo calcular_edad($edad)?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Descripcion</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[nombre]"?>
</td>
</tr>
<tr>
<td colspan=4 class="titulo3"> Anexo:
</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente">
Fotocopia de Cedula <input type="checkbox"></input></td>

<td colspan=1 class="datos_cliente">
Informe Medico <input type="checkbox"></input></td>
<td colspan=1 class="datos_cliente">
Recipe Medico <input type="checkbox"></input>
</td>
</tr>
<tr>
<td colspan=2 class="datos_cliente">
Constancia de residencia emitida por el consejo comunal <input type="checkbox"></input></td>

<td colspan=2 class="datos_cliente">
Fotocopia Partida de Nacimiento <input type="checkbox"></input></td>
</tr>
<tr>
<td colspan=2 class="datos_cliente">
Constancia de bajo recurso emitida por el consejo comunal  <input type="checkbox"></input>
</td>
<td colspan=2 class="datos_cliente">
Presupuesto <input type="checkbox"></input>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Monto Requerido</td>
<td colspan=3 class="datos_cliente">
___________________________________________
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Fecha de Solicitud:</td>
<td colspan=3 class="datos_cliente">
<?php echo "$f_proceso[fecha_recibido]"?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Monto Otorgado</td>
<td colspan=3 class="datos_cliente">
<?php echo montos_print($monto)?>
</td>
</tr>
<tr>
<td colspan=1 class="datos_cliente4">
Fecha Entrega</td>
<td colspan=3 class="datos_cliente">
_________________________________________
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
<td colspan=2 class="titulo3">
___________________________
</td>
<td colspan=2  class="titulo3">
___________________________
</td>
</tr>

<tr>
<td colspan=2 class="titulo3">
PRESIDENTE
</td>
<td colspan=2 class="titulo3">
COORDINADOR DE RESPONSABILIDAD SOCIAL
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
<td colspan=4 class="datos_cliente1">
Domicilio Fiscal: Avenida Las Americas, Centro Comercial Mayeya, Nivel Mezzanina, locales 16,17 y 24, M&eacute;rida Edo. M&eacute;rida.
</td>

</tr>

</table>


