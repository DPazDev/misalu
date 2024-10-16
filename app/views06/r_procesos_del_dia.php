<?php

/* Nombre del Archivo: r_entpriv.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación Entes Privados
*/

    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $q_sucursal=("select sucursales.id_sucursal,sucursales.sucursal from sucursales order by sucursal");
    $r_sucursal=ejecutar($q_sucursal);
?>

 <form method="POST" name="r_proc" id="r_proc">

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Ordenes y Facturas por Entes Ejecutados el dia de HOY </td>
	</tr>
<tr><td>&nbsp;</td></tr>


<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione la Sucursal:</td>
	       <td class="tdcampos"  colspan="1"><select name="sucur" id="sucur"class="campos"  style="width: 210px;" >
                                     <option value="0@TODAS LAS SUCURSALES">TODAS LAS SUCURSALES</option>
				     <?php  while($sucur=asignar_a($r_sucursal,NULL,PGSQL_ASSOC)){
					$value="$sucur[id_sucursal]@$sucur[sucursal]";			
					?>
				     <option value="<?php echo $value ?>"> <?php echo "$sucur[sucursal]"?></option>
				    <?php
				    }?> 
		</td>







<tr><td>&nbsp;</td></tr>

	<tr>
     
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_procesos_del_dia();" class="boton">Buscar</a>   <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>

  <tr><td>&nbsp;</td></tr>

</table>

  <div id="reporte_procesos_del_dia"></div>

</form>
