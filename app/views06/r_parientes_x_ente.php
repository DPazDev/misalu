<?php
/* Nombre del Archivo: r_parientes_x_ente.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de los Parientes, de un determinado Ente */

    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $q_tipo_ente=("select * from tbl_tipos_entes order by tipo_ente");
    $r_tipo_ente = ejecutar($q_tipo_ente);

    $q_estado=("select estados_clientes.id_estado_cliente,estados_clientes.estado_cliente from estados_clientes order by estado_cliente");
    $r_estado=ejecutar($q_estado);

    $q_subdivision=("select subdivisiones.id_subdivision, subdivisiones.subdivision from subdivisiones order by subdivision");
    $r_subdivision=ejecutar($q_subdivision);

    $q_parentesco=("select parentesco.id_parentesco, parentesco.parentesco from parentesco order by parentesco");
    $r_parentesco=ejecutar($q_parentesco);
?>

<form method="POST" name="r_parientexente" id="r_parientexente">

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n de los Parientes, de un determinado Ente.</td>
	</tr>
	<tr><td>&nbsp;</td></tr>


       <td class="tdtitulos" colspan="1">&nbsp; &nbsp; Seleccione Estado del Parentesco:</td>
       <td class="tdcampos"  colspan="1"><select name="estado" class="campos"  style="width: 210px;" >
                                     <option value="0@Todos los Estados">Todos los Estado</option>
				     <?php  while($f_estado=asignar_a($r_estado,NULL,PGSQL_ASSOC)){
		echo "<option value=\"$f_estado[id_estado_cliente]@$f_estado[estado_cliente]\">$f_estado[estado_cliente]</option>";


				    }?> 
			</select>	
	</td>



		<td class="tdtitulos" colspan="1">&nbsp; &nbsp; Seleccione Subdivisión:</td>
	        <td class="tdcampos"  colspan="1"><select name="subdivi" class="campos"  style="width: 210px;" >
                                     <option value="0">Subdivision</option>
				     <?php  while($f_subdivision=asignar_a($r_subdivision,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_subdivision[id_subdivision]?>"> <?php echo "$f_subdivision[subdivision]"?></option>
				    <?php
				    }?> 
	</select>
	</td>	
	</tr>
	<tr><td>&nbsp;</td></tr>
<tr>

		
    			<td colspan=1 class="tdtitulos">&nbsp; &nbsp; Parentesco</td>
	<tr></tr>
       <?php       
	$j=0;

	while($f_parentesco=asignar_a($r_parentesco)){
	$j++;	?>		
		<td colspan=1 class="tdtitulos">&nbsp;</td>

		<td colspan=1 class="tdtitulos"><?php echo $f_parentesco[parentesco]?></td>
		<td>

	<input type="checkbox"  id="campo_<?php echo $j?>" name="campo" value="">

	<input class="campos" type="hidden"  id="valor_<?php echo $j?>"  name="valor" value="<?php echo $f_parentesco[id_parentesco] ?>"   >
						
	</tr>
<?php }?>

	<input class="campos" type="hidden"  id="nu_parentesco"  name="nu_parentesco" value="<?php echo $j ?>"   >
	<tr><td>&nbsp;</td></tr>
	<td class="tdtitulos" colspan="1">&nbsp; &nbsp; Con Edades: </td> 
		<td class="tdcampos" colspan="1"><select name="signo" class="campos">
		<option value=""> </option>
		<option value="<"><</option>
		<option value="=">=</option>
		<option value=">">></option>
		</select>&nbsp
		<input type="text" size="10" class="campos" name="cantidad">
		</td>
	</tr> 
	<tr><td>&nbsp;</td></tr>

	<td class="tdtitulos" colspan="1">&nbsp; &nbsp;Seleccione Tipo de Ente:</td>
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

	</tr>

    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="reporte_parientes_x_ente();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>

	</tr>
	<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
<div id="reporte_parientes_x_ente"></div>
</from>

