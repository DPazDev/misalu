<?php
/* Nombre del Archivo: guardar_sucursal.php
   Descripción: Inserta una SUCURSAL en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$sucursal = $_REQUEST['sucursal'];
$direccion = $_REQUEST['direccion'];
$telefono = $_REQUEST['telefono'];
$fax = $_REQUEST['fax'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$sucursal = strtoupper($sucursal);
$direccion = strtoupper($direccion);

/*echo "******************* <br>";
echo $sucursal."<br>"; 
echo "******************* <br>";
echo $coment."<br>"; */

$q_sucursal = "insert into sucursales (sucursal,fecha_creado,hora_creado,fecha_modificado,hora_modificado,direccion_suc,telefonos_suc,fax_suc) values('$sucursal','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado','$direccion','$telefono','$fax')";
$r_sucursal = ejecutar($q_sucursal);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito la Sucursal <?php echo $sucursal;?></td>	
</tr>	

	<tr>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_sucursal();" class="boton">Insertar otra Sucursal</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

</table>
