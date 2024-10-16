<?php
/* Nombre del Archivo: guardar_organo
   Descripción: Inserta un ORGANO en la base de datos, para utilizarlo posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$organo = $_REQUEST['organo'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$organo = strtoupper($organo);


/*echo "******************* <br>";
echo $depart."<br>"; 
echo "******************* <br>";
echo $coment."<br>"; */

$q_organo = "insert into organos (organo,fecha_creado,hora_creado,fecha_modificado,hora_modificado) values('$organo','$fecha_creado','$hora_creado','$fecha_creado','$hora_creado')";
$r_organo = ejecutar($q_organo);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion"> Se Registro con Exito el Organo <?php echo $organo;?></td>	
</tr>	

	<tr>
		
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_organo();" class="boton">Insertar otro Organo</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>

</table>
