<?php

include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];
$cooperador=$_REQUEST['cooperador'];
$cooperador=strtoupper("$cooperador");

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];

$q_espera="select * from procesos where procesos.id_proceso='$proceso'";
$r_espera=ejecutar($q_espera);
$f_espera=asignar_a($r_espera);


$mod_proceso="update procesos set id_estado_proceso='7',fecha_modificado='$fechacreado',hora_modificado='$hora',comentarios='$cooperador' where procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso); 

/* echo $mod_proceso; ***/

$log="ACTUALIZO A CANDIDATO A PAGO LA ORDEN CON NUMERO $proceso";
logs($log,$ip,$admin);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera4"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $proceso ?> se cambio a CANDIDATO A PAGO con Exito </td>	
</tr>	

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>

<?php

?>










