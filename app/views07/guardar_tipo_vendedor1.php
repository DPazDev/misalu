<?php
/* Nombre del Archivo: guardar_depart
   Descripción: Inserta un DEPARTAMENTO en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$tipo = $_REQUEST['tipo'];
$id_tipo2 = $_REQUEST['id_tipo2'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$tipo = strtoupper($tipo);


/*echo "******************* <br>";
echo $tipo."<br>"; 
echo "******************* <br>";
echo $fecha_creado."<br>"; 
echo "******************* <br>";
echo $hora_creado."<br>"; 

echo "******************* <br>";
echo $id_tipo2."<br>"; 
echo "******************* <br>";*/


$q_tipo = "update tipo_comisionado set tipo_comisionado='$tipo', fecha_modificado='$fecha_creado',hora_modificado='$hora_creado' where id_tipo_comisionado='$id_tipo2'";
$r_tipo = ejecutar($q_tipo);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


	<tr>		
	<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Tipo de Vendedor <?php echo $tipo;?></td>	
	</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_tipo_vendedor();" class="boton">Insertar otro Tipo de Vendedor</a>
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>

</table>
