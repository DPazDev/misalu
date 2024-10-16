<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$elid=$_SESSION['id_usuario_'.empresa];
$elus=$_SESSION['nombre_usuario_'.empresa];
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Crear dependencias / Asignar usuarios a dependencias</td>  
     </tr>
	  <br>
	<tr>  
	      <td  title="Crear una nueva dependencia"><label class="boton" style="cursor:pointer" onclick="creardepen()" >Crear dependencia </label></td> 
		  <td  title="Asignar usuario(s) a la dependencia"><label class="boton" style="cursor:pointer" onclick="usuaridepen()" >Asignar usuario(s) a dependencia </label></td>   
		<td  title="Ver a los usuarios registrados en la dependecia"><label class="boton" style="cursor:pointer" onclick="depenusuarios()" >Ver usuario(s) en dependencia(s) </label></td>     
	</tr> 
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="dependencias"></div>
