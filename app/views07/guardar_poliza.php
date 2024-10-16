<?
/*  Nombre del Archivo: guardar_poliza.php
   Descripción: Guarda en la Base de Datos el Monto a modificar en la Póliza
*/


header("Content-Type: text/html;charset=utf-8");


	include ("../../lib/jfunciones.php");
	sesion();   

	$poliza=$_REQUEST['poliza'];
	$contador=$_REQUEST['contador'];
	$monto=$_REQUEST['monto'];

	$q_monto="update coberturas_t_b set monto_actual='$monto' where coberturas_t_b.id_cobertura_t_b='$poliza' ;";
	$r_monto=ejecutar($q_monto);

?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "MODIFICADO"; ?></td>
	</tr>
<?



