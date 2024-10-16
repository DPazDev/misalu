<?php
/* Nombre del Archivo: r_gastos_cliente.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de Gastos del Cliente
*/

	include ("../../lib/jfunciones.php");
	sesion();
	$q_estado=("select estados_clientes.* from estados_clientes order by estado_cliente");
	$r_estado=ejecutar("$q_estado");
	    		  
?>

<form method="POST" name="r_gastoscliente" id="r_gastoscliente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

        <tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Gastos del Cliente</td>
	</tr>
	<tr> <td>&nbsp;</td>
	</tr>
	<tr>
		<td  class="tdtitulos">* N&uacute;mero de C&eacute;dula</td>
                <td  class="tdcampos"><input class="campos" type="text" name="ci" maxlength=170 size=22 value=""></td>		
		<td  class="tdcampos"><a href="#" OnClick="r_gastos_cliente1();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
	<tr> 
		<td colspan="4">&nbsp;</td>
	</tr>
</table>

<div id="r_gastos_cliente"></div>

</form>

