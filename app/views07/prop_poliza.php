<?
/* Nombre del Archivo: prop_poliza.php
   Descripción: Asignar Propiedades Póliza a Cliente ya Registrado
*/

	include ("../../lib/jfunciones.php");
	sesion();
	
	$q_ente=("select entes.id_ente,entes.nombre from entes order by entes.nombre");
	$r_ente=ejecutar($q_ente);

	$q_poliza=("select propiedades_poliza.cualidad,propiedades_poliza.descripcion from propiedades_poliza group by propiedades_poliza.cualidad,propiedades_poliza.descripcion order by propiedades_poliza.cualidad");
	$r_poliza=ejecutar($q_poliza);
	    		  
?>

<form method="POST" name="r_prop_poliza" id="r_prop_poliza">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

        <tr>
		<td colspan=4 class="titulo_seccion">Asignar Propiedades Póliza a Entes con Clientes ya Registrados</td>
	</tr>
	<tr> <td>&nbsp;</td>
	</tr>
	<tr>
		<td  class="tdtitulos">&nbsp; Seleccione Ente:</td>
		<td class="tdcampos"  colspan="1"><select name="ente" id="ente" class="campos"  style="width: 210px;" >
                                     <option value=" "></option>
				     <?php  while($f_ente=asignar_a($r_ente,NULL,PGSQL_ASSOC)){



					$value="$f_ente[id_ente]@$f_ente[nombre]";



$q_pol=("select polizas_entes.id_poliza,polizas.nombre_poliza from polizas_entes,polizas where polizas_entes.id_ente=$f_ente[id_ente] and polizas.id_poliza=polizas_entes.id_poliza ");
	$r_pol=ejecutar($q_pol);
	$f_pol=asignar_a($r_pol);			
					?>
				     <option value="<?php echo $value ?>"> <?php echo "$f_ente[nombre] --- $f_pol[nombre_poliza]"?></option>
				    <?php
				    }?> 
		</td>
	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>
		<td  class="tdtitulos">&nbsp; Seleccione Póliza:</td>
		<td class="tdcampos"  colspan="1"><select name="poliza" id="poliza"class="campos"  style="width: 210px;" >
                                     <option value=" "></option>
				     <?php  while($f_poliza=asignar_a($r_poliza,NULL,PGSQL_ASSOC)){
					$value="$f_poliza[cualidad]@$f_poliza[descripcion]";			
					?>
				     <option value="<?php echo $value ?>"> <?php echo "$f_poliza[cualidad] --- $f_poliza[descripcion]"?></option>
				    <?php
				    }?> 
		</td>

	</tr>
<tr><td>&nbsp;</td></tr>
	<tr>

		<td colspan="4" class="tdcamposcc"><a href="#" OnClick="guard_prop_poliza();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>
	<tr> 
		<td colspan="4">&nbsp;</td>
	</tr>
</table>

<div id="guard_prop_poliza"></div>

</form>

