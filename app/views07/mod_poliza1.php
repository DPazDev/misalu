<?
/*  Nombre del Archivo: mod_poliza1.php
   Descripción: Busqueda en base de datos para Modificar Monto en Póliza
*/

	include ("../../lib/jfunciones.php");
	sesion();   
	$ci=$_REQUEST['ci'];
      
	$q_cliente=("select clientes.cedula, clientes.id_cliente from clientes where clientes.cedula='$ci'");
	$r_cliente=ejecutar($q_cliente);
	$f_cliente=asignar_a($r_cliente);

	$q_ti=("select clientes.cedula, titulares.id_cliente, titulares.id_titular, clientes.nombres, clientes.apellidos from clientes, titulares where clientes.cedula='$ci' and titulares.id_cliente=clientes.id_cliente ");
	$r_ti=ejecutar($q_ti);
	$f_ti=asignar_a($r_ti);

	$q_be=("select clientes.cedula, beneficiarios.id_cliente, beneficiarios.id_titular,clientes.nombres, clientes.apellidos from clientes, beneficiarios where clientes.cedula='$ci' and beneficiarios.id_cliente=clientes.id_cliente ");
	$r_be=ejecutar($q_be);
	$f_be=asignar_a($r_be);



/*
echo $ci."//";
echo "$f_cliente[cedula]"."///";
echo $f_cliente[id_cliente]."//";
echo $f_ti[id_cliente]."***";
echo $f_be[id_cliente]."----";
echo $f_be[nombres]."----";
$cliente=$f_cliente[id_cliente];
echo $cliente.";*/

	
	if($ci!=$f_cliente[cedula] ){?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "NO EXISTE UN USUARIO CON EL NUMERO DE CEDULA $ci"; ?></td>
	</tr>
	<? }

else {
?>

	<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<? $c=0; ?>
<?php if($f_cliente[id_cliente]==$f_ti[id_cliente]){

$c=$c+1;

	 $q_polizat=("select coberturas_t_b.*, propiedades_poliza.*, entes.nombre from coberturas_t_b,clientes,titulares,propiedades_poliza,entes,polizas_entes where coberturas_t_b.id_titular=titulares.id_titular and
 clientes.cedula='$ci' and
 clientes.id_cliente=titulares.id_cliente and 
coberturas_t_b.id_beneficiario='0' and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and entes.id_ente=titulares.id_ente and 
polizas_entes.id_poliza=propiedades_poliza.id_poliza and 
polizas_entes.id_ente=titulares.id_ente order by propiedades_poliza.cualidad");
	$r_polizat=ejecutar($q_polizat);
	?>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n de Pólizas del Cliente como Titular</td>
	</tr>
	<tr> <td>&nbsp;</td>	</tr>

	<tr>
		<td class="tdtitulos">&nbsp;  Nombre y Apellido del Titular: </td>
		<td class="tdcampos"> <? echo "$f_ti[nombres] $f_ti[apellidos]";?></td>
		<td class="tdtitulos">C&eacute;dula del Titular:</td>
		<td class="tdcampos"> <? echo "$f_ti[cedula]" ;?></td> 
	</tr>

	<tr> <td>&nbsp;</td>	</tr>

	<tr>
	       <td class="tdtitulos" colspan="1">&nbsp; Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select id="poliza_<?php echo $c?>" name="poliza" class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura</option>
				     <?php  while($f_polizat=asignar_a($r_polizat,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_polizat[id_cobertura_t_b]?>"> <?php echo "$f_polizat[cualidad]"." -- "."$f_polizat[nombre_poliza]"." -- "."$f_polizat[nombre]"?></option>
<?}?>			
		</select>

		</td>
		


	</tr>
		<tr> <td>&nbsp;</td>		</tr>
	
<?}?>


<?php if($f_cliente[id_cliente]==$f_be[id_cliente]){

$c=$c+1;

	 $q_polizab=("select coberturas_t_b.*, propiedades_poliza.*, entes.nombre from coberturas_t_b,clientes,titulares,propiedades_poliza,entes,polizas_entes,beneficiarios where coberturas_t_b.id_beneficiario=beneficiarios.id_beneficiario and
 clientes.cedula='$ci' and
 clientes.id_cliente=beneficiarios.id_cliente and 
titulares.id_titular=beneficiarios.id_titular and
coberturas_t_b.id_titular=beneficiarios.id_titular and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and entes.id_ente=titulares.id_ente and 
polizas_entes.id_poliza=propiedades_poliza.id_poliza and 
polizas_entes.id_ente=titulares.id_ente order by propiedades_poliza.cualidad");
	$r_polizab=ejecutar($q_polizab);
	?>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n de Pólizas del Cliente como Beneficiario</td>
	</tr>
	<tr> <td>&nbsp;</td>

	<tr>
		<td class="tdtitulos">&nbsp;  Nombre y Apellido del Beneficiario: </td>
		<td class="tdcampos"> <? echo "$f_be[nombres] $f_be[apellidos]";?></td>
		<td class="tdtitulos">C&eacute;dula del Beneficiario:</td>
		<td class="tdcampos"> <? echo "$f_be[cedula]" ;?></td> 
	</tr>

	<tr> <td>&nbsp;</td>	</tr>

	<tr>
	       <td class="tdtitulos" colspan="1">&nbsp; Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select name="poliza" id="poliza_<?php echo $c?>"  class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura</option>
				     <?php  while($f_polizab=asignar_a($r_polizab,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_polizab[id_cobertura_t_b]?>"> <?php echo "$f_polizab[cualidad]"." -- "."$f_polizab[nombre_poliza]"." -- "."$f_polizab[nombre]"?></option>
<?}?>			
		</select>

		</td>
		


	</tr>
		<tr> <td>&nbsp;</td>		</tr>
	
<?}?>

	<tr> <td>&nbsp;</td>	</tr>


	<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $c ?>" id="contador" name="contador"></td>
        </tr>


	<tr>
		<td colspan=4 class="titulo_seccion">Monto a Modificar</td>
	</tr>
	<tr> <td>&nbsp;</td></tr>

		<td colspan=1  						class="tdtitulos">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Monto:</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="monto" name="monto" maxlength=150 size=25 value=""></td>
	<tr> <td>&nbsp;</td>
	
	<tr>
		<td colspan=4 class="tdcamposcc"><a href="#" OnClick="guardar_poliza();" class="boton">Modificar</a> 
 <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
	</tr>

	<tr> 
		<td colspan="4">&nbsp;</td>
	</tr>
<?}?>

</table>

<div id="mod_poliza1"></div>

