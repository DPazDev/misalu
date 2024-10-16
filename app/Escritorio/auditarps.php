<?
include ("../../lib/jfunciones.php");
sesion();
?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Auditar cliente Plan Salud (OJO LECTURA DE ARCHIVO)</td>  
     </tr>
    <br>
	<tr>  
	      <td  title="Auditar clientes como titular"><label class="boton" style="cursor:pointer" onclick="auditarclienps('t')" >Auditar titular</label></td> 
		  <td  title="Auditar clientes como beneficiario"><label class="boton" style="cursor:pointer" onclick="auditarclienps('b')" >Auditar beneficiario</label></td>   
		  <td  title="Auditar todos los clientes "><label class="boton" style="cursor:pointer" onclick="auditarclienps('c')" >Auditar todos los clientes</label></td>     
	</tr> 
  </table>
