<?php

/* Nombre del Archivo: guardar_sucursal1.php
   Descripción: modifica una SUCURSAL en la base de datos, para ser utilizado posteriormente
*/

include("../../lib/jfunciones.php");
sesion();

$sucursal_nuevo = $_REQUEST['sucursal_nuevo'];
$telefono_nuevo = $_REQUEST['telefono_nuevo'];
$fax_nuevo = $_REQUEST['fax_nuevo'];
$id_sucursal2 = $_REQUEST['id_sucursal2'];
$direccion_nuevo = $_REQUEST['direccion_nuevo'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$sucursal_nuevo = strtoupper($sucursal_nuevo);
$direccion_nuevo = strtoupper($direccion_nuevo);

/*echo "******************* <br>";
echo $sucursal_nuevo."<br>"; 
echo "******************* <br>";
echo $telefono_nuevo."<br>"; 
echo "******************* <br>";
echo $fax_nuevo."<br>"; 
echo "******************* <br>";
echo $direccion_nuevo."<br>";*/


$q_sucursal1 = "update sucursales set sucursal='$sucursal_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado',direccion_suc='$direccion_nuevo',telefonos_suc='$telefono_nuevo',fax_suc='$fax_nuevo' where id_sucursal='$id_sucursal2'";
$r_sucursal1 = ejecutar($q_sucursal1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito la Sucursal <?php echo $sucursal_nuevo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_sucursal();" class="boton">Modificar otra Sucursal</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
</table>
