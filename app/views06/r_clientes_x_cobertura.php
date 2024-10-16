<?php
/* Nombre del Archivo: r_clientes_x_cobertura.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación Gastos de Clientes por Coberturas, de un determinado Ente */

    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $q_ente=("select entes.id_ente,entes.nombre from entes order by entes.nombre");
    $r_ente=ejecutar($q_ente);
?>

 <form method="POST" name="r_clientesxcobertura" id="r_clientesxcobertura">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Gastos Clientes por Cobertura, de un determinado Ente.</td>
	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione el Ente:</td>
	       <td class="tdcampos"  colspan="1"><select name="ente" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Entes">TODOS LOS ENTES</option>
				     <?php  while($f_ente=asignar_a($r_ente,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_ente['id_ente']?>"> <?php echo "$f_ente[nombre]"?></option>
				     <?php
}?> 
		</td>
	
		<td colspan=2 class="tdcampos"><a href="#" OnClick="r_clientes_x_cobertura1();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
</table>
<div id="r_clientes_x_cobertura"></div>
</from>

