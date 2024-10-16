<?php
/* Nombre del Archivo: r_cliente_edad.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación Clientes entre A y B años
*/

    include ("../../lib/jfunciones.php");
    sesion();
	$inicio="";
	$fin="";

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
	$q_ente=("select entes.id_ente,entes.nombre from entes order by entes.nombre");
	$r_ente=ejecutar($q_ente);

	$q_estado=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes  order by estados_clientes.estado_cliente");
	$r_estado=ejecutar($q_estado);

	$q_tipo_ente=("select * from tbl_tipos_entes order by tipo_ente");
	$r_tipo_ente = ejecutar($q_tipo_ente);

?>

 <form method="POST" name="r_clienteedad" id="r_clienteedad">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Clientes entre A y B a&ntilde;os, de un determinado Ente.</td>
	</tr>

       	<tr> <td>&nbsp;</td></tr>
	<tr>

		<td class="tdtitulos" colspan="1">* Seleccione Tipo de Cliente:</td>
		<td class="tdcampos"  colspan="1"><select id="tipcliente" class="campos"  style="width: 210px;" >
                        	      <option>TODOS LOS CLIENTES</option>
				      <option>TITULARES</option>
	                              <option>BENEFICIARIOS</option>
		</select>
		</td>

	       <td class="tdtitulos" colspan="1">* Seleccione el Estado:</td>
	       <td class="tdcampos"  colspan="1"><select id="estado" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">TODOS LOS ESTADOS</option>
				     <?php  while($f_estado=asignar_a($r_estado)){
				     echo "<option value=\"$f_estado[id_estado_cliente]@$f_estado[estado_cliente]\"> $f_estado[estado_cliente]</option>";

				    }?> 

</select> </td>
	</tr>
	<tr> <td>&nbsp;</td></tr>
	<tr>
		<td colspan=1 class="tdtitulos">* Seleccione las Edades</td>
		<td colspan=1 class="tdcampos"><?php

				echo fechas('0','120',$inicio,'','inicio');
				echo "&nbsp "."Y"."&nbsp "; 
				echo fechas('0','120',$fin,'','fin');

				?></td>

      <td class="tdtitulos" colspan="1">Seleccione Tipo de Ente:</td>
		 <td class="tdcampos"  colspan="1">
         <select class="campos"  style="width: 200px;"  id="tipo_ente" name="tipo_ente" onchange="bus_ent(4)" >
		<option value="">--Seleccione un Tipo de Ente--</option>
		<option value="0@TODOS LOS TIPOS">TODOS LOS TIPOS</option>
		<?php
		while($f_tipo_ente = asignar_a($r_tipo_ente)){
		echo "<option value=\"$f_tipo_ente[id_tipo_ente]@$f_tipo_ente[tipo_ente]\">$f_tipo_ente[tipo_ente]</option>";
		}
		?>
		</select> </td>
		</tr>

<tr><td>&nbsp;</td></tr>

</table>

<div id="bus_ent"></div>

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr> <td>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc">
<a href="#" OnClick="reporte_cliente_edad();" class="boton">Buscar</a> 
<a href="#" OnClick="imp_cliente_edad();" class="boton">Imprimir</a> 
<a href="#" OnClick="exc_cliente_edad();"><img border="0" src="../public/images/excel.jpg"></a> 
<a href="#" OnClick="ir_principal();" class="boton">Salir</a> </td>

	</tr>
	<tr> <td>&nbsp;</td></tr>
</table>
<div id="reporte_cliente_edad"></div>
</from>

