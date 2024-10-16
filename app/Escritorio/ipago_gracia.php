<?php
include ("../../lib/jfunciones.php");
sesion();

$ente=noacento($_REQUEST['nomente']);
$cliente=noacento($_REQUEST['cliente']);
$proceso=$_REQUEST['proceso'];
$fecha_recibido=$_REQUEST['fecha_recibido'];
$motivo=noacento($_REQUEST['motivo']);
$comentario=noacento($_REQUEST['comentario']);
$porcentaje=noacento($_REQUEST['porcentaje']);

$fecha=date("Y-m-d");
$hora=date("h:i:s");

$q_admin = "select admin.nombres,admin.apellidos,sucursales.sucursal from admin,sucursales,procesos where procesos.id_proceso=$proceso and procesos.id_admin=admin.id_admin and admin.id_sucursal=sucursales.id_sucursal";
$r_admin = ejecutar($q_admin);
$f_admin = asignar_a($r_admin);

$admin= $_SESSION['id_usuario_'.empresa];
$q_admin1 = "select admin.nombres,admin.apellidos,sucursales.sucursal from admin,sucursales where admin.id_admin=$admin";
$r_admin1 = ejecutar($q_admin1);
$f_admin1 = asignar_a($r_admin1);

$dia=date("d");
$mes=date("m");
$ano=date("Y");
if($mes == '01')
	$mes = "Enero";
elseif($mes == '02')
	$mes = "Febrero";
elseif($mes == '03')
	$mes = "Marzo";
elseif($mes == '04')
	$mes = "Abril";
elseif($mes == '05')
	$mes = "Mayo";
elseif($mes == '06')
	$mes = "Junio";
elseif($mes == '07')
	$mes = "Julio";
elseif($mes == '08')
	$mes = "Agosto";
elseif($mes == '09')
	$mes = "Septiembre";
elseif($mes == '10')
	$mes = "Octubre";
elseif($mes == '11')
	$mes = "Noviembre";
elseif($mes == '12')
	$mes = "Diciembre";

//$fecha=fecha_espanol(date("Y-M-d"));
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
<td  colspan=1 class="titulo1"><?php echo ($f_admin['sucursal']);  echo date(" d"); echo " de "; echo ($mes); echo " de "; echo date("Y")?></td>
</tr>

<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>	
			<td colspan=4 class="datos_cliente">
			<br><br><br> Ciudadano(a)
			</td>
			
		</tr>
<tr>	
			<td colspan=4 class="datos_cliente">
			<br> 
			<?php echo "$cliente"?>
			</td>
			
		</tr>
<tr>	
			<td colspan=4 class="datos_cliente">
			<br> 
			Ciudad.
			</td>
			
		</tr>


<tr>
	<td colspan=4 ></td></tr>
<tr>
	<td colspan=4 class="datos_cliente"><br>
	Estimado(a) Se&ntilde;or(a):
	
</td></tr>


		<tr>	
			<td colspan=4 class="datos_cliente"> <br>
Por medio de la presente le informamos que despu&eacute;de haber analizado su solicitud de reemboslo numero <?php echo $proceso ?> presentada el <?php echo camfecha($fecha_recibido)?>, les infromamos que el pago de las facturas </td>
</tr>

<tr>	
			<td colspan=4 class="datos_cliente"><br>
			<?php echo " $motivo" ?>
		
			</td>
			
		</tr>
		

<tr>	
<td colspan=4 class="datos_cliente">	<br>
ser&aacute; cancelada en un <?php echo "$porcentaje %"?> bajo la figura de PAGO UNICO DE GRACIA, ya que no nos vemos en la obligaci&oacute;n de declinar la responsabilidad en la cancelaci&oacute;n de su solicitud , seg&uacute;n  lo indicado en la exclusiones de las condiciones particulares, que se describe a continuacion: 
			</td>
			
		</tr>



<tr>	
			<td colspan=4 class="datos_cliente"><br>
			<?php echo " $comentario" ?>
		
			</td>
			
		</tr>
		

<tr>	
			<td colspan=4 class="datos_cliente"><br>
			
del contrato de afilaci&oacute;n al plan de gastos m&eacute;dicos en hospitalizac&oacute;n, cirug&iacute;a y maternidad suscrito entre CliniSalud Medicina Prepagada S.A y <?php echo $ente?>

<br> </br>
			</td>
			
		</tr>

<tr>	
			<td colspan=4 >
			

Sin m&aacute;s a que hacer referencia y atentos para cualquier informaci&oacute;n adicional, quedamos a su completa disposici&oacute;n adicional quedamos a su completa disposici&oacute;n.

			</td>
			
		</tr>
<tr>	
			<td colspan=4 >
			<br>
			</td>
			
		</tr>

<tr>	
			<td colspan=4 >
			<br><br><br><br>
Atentamente.
			</td>
			
		</tr>
<tr>
<td colspan=1 class="titulo3">
__________________
</td>
<td colspan=1  class="titulo3">

</td>
<td colspan=2 class="titulo3">
__________________
</td>
</tr>
<tr>
<td colspan=1 class="titulo3">
Analista Operativo <?php echo "$f_admin1[nombres] $f_admin1[apellidos]"?> 
</td>
<td colspan=1 class="titulo3">

</td>
<td colspan=2 class="titulo3">
Lic. Zulay Agelvis Gerente  de Operacion
</td>
</tr>
<tr>
<td colspan=4>
<br>
</br>
</td>
</tr>
<tr>
<td colspan=4 class="datos_cliente">

</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_FISCAL; ?>
</td>

</tr>
<tr>
<td colspan=4 class="datos_cliente">
<?php echo DIRECCION_VIGIA;?><br>
<?php echo  DIRECCION_QUIROFANO;?>
</td>

</tr>
</table>	


