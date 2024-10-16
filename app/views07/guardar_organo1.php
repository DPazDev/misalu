<?php

/* Nombre del Archivo: guardar_organo1.php
   Descripción: modifica un ORGANO en la base de datos, para ser utilizado posteriormente
*/


include("../../lib/jfunciones.php");
sesion();

$organo_nuevo = $_REQUEST['organo_nuevo'];
$id_organo2 = $_REQUEST['id_organo2'];

$fecha_creado = date("Y-m-d");
$hora_creado = date("h:m:s");

$organo_nuevo = strtoupper($organo_nuevo);

/*echo "******************* <br>";
echo $pais_nuevo."<br>"; 
echo "******************* <br>";
echo "******************* <br>";
echo $id_pais2."<br>"; 
echo "******************* <br>";*/

$q_organo1 = "update organos set organo='$organo_nuevo',fecha_modificado='$fecha_creado',hora_modificado='$hora_creado' where organos.id_organo='$id_organo2'";
$r_organo1 = ejecutar($q_organo1);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>



<tr>		
<td colspan=4 class="titulo_seccion"> Se Modifico con Exito el Organo <?php echo $organo_nuevo;?></td>	
</tr>	

	<tr>
		<td colspan=2 class="tdcamposcc"><a href="#" OnClick="reg_organo();" class="boton">Modificar otro Organo</a>
		
		<td colspan=2 class="tdcampos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		
	</tr>
</table>
