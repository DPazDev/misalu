<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php 
include ("../../lib/jfunciones.php");
sesion();
$elid=$_SESSION['id_usuario_'.empresa];
$_SESSION['opcionpe']=0;

?>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Provedores por Insumos</td>
     </tr>	
     <tr>
			<td colspan=3 class="tdtitulos">Busqueda por producto o c&oacute;digo </td>     
     		<td colspan=3 class="tdtitulos"><input type="text" name="binsumo" id="binsumo" value="" class="campos" size="35" maxlength="50" onkeypress="fenter(event,'fbuscarprov()');" /></td>
         <td >
<!--boton de busqueda  -->     
         <label class="boton" style="cursor:pointer"  onclick="fbuscarprov();return false;" >Buscar</label></td> 		
	  </tr>      
        
</table>
<img alt="spinner" id="spinnerARTI" src="../public/images/esperar.gif" style="display:none;" /> 
<div id="resinsumos"></div>
