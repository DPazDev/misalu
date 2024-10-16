<?
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
$elnuevoramo=strtoupper($_POST['nuevoramopoli']);
$guardarramo=("insert into ramos(ramo,fecha_creado,hora_creado) values ('$elnuevoramo','$fecha','$hora');");
$repguardarramo=ejecutar($guardarramo);
$mensaje="El usuario $elus ha creado una nuevo ramo con el nombre $elnuevoramo"; 
	 $actualizoellog=("insert into logs(log,id_admin,fecha_creado,hora_creado,ip) values (upper('$mensaje'),'$elid','$fecha','$hora','$ip')");
	  $repactualizoellog=ejecutar($actualizoellog);  
?>
<hr>
 <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">El nuevo ramo <?echo $elnuevoramo ?> ha sido registrado exitosamente</td>
		<td colspan=1 class="titulo_seccion" title="Recargar la p&acute;gina"><label class="boton" style="cursor:pointer" onclick="reg_polizas()" >Recargar</label></td>  
	</tr>	 
 </table>	
 