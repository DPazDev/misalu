<?php
/* Nombre del Archivo: guardar_depart
   Descripción: Inserta un DEPARTAMENTO en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$tipo1 = $_REQUEST['tipo1'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$tipo1 = strtoupper($tipo1);


/*echo "******************* <br>";
echo $tipo1."<br>"; 
echo "******************* <br>";
echo $fecha_creado."<br>"; 
echo "******************* <br>";
echo $hora_creado."<br>";*/ 

$q_tipo = "insert into tipo_comisionado (tipo_comisionado,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$tipo1','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_tipo = ejecutar($q_tipo);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


	<tr>		
	<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Tipo de Vendedor <?php echo $tipo1;?></td>	
	</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_tipo_vendedor();" class="boton">Insertar otro Tipo de Vendedor</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>
