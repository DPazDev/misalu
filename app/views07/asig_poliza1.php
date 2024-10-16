<?
/*  Nombre del Archivo: asig_poliza1.php
   Descripción: Busqueda en base de datos para Asignar Póliza a Cliente ya Registrado
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

	$q_be=("select clientes.cedula, beneficiarios.id_cliente, beneficiarios.id_titular, clientes.nombres, clientes.apellidos from clientes, beneficiarios where clientes.cedula='$ci' and beneficiarios.id_cliente=clientes.id_cliente ");
	$r_be=ejecutar($q_be);
	$f_be=asignar_a($r_be);

$q_solo_tit=("select * from beneficiarios where beneficiarios.id_cliente='$f_cliente[id_cliente]'");
$r_solo_tit=ejecutar($q_solo_tit);
$f_solo_tit=asignar_a($r_solo_tit);

$solotitu=num_filas($r_solo_tit);   //para saber si solo es titular//



	if($ci!=$f_cliente[cedula] ){?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "NO EXISTE UN USUARIO EN TITULARES POLIZAS"; ?></td>
	</tr>
	<? }

else {
?>

	<table class="tabla_citas"  cellpadding=0 cellspacing=0>

<?php if($f_cliente[id_cliente]==$f_ti[id_cliente]){

	$q_polizat=("select titulares.id_titular,propiedades_poliza.id_propiedad_poliza,propiedades_poliza.id_poliza,propiedades_poliza.cualidad, 		entes.nombre,polizas.nombre_poliza from titulares_polizas,clientes,titulares,propiedades_poliza,entes,polizas where 
	clientes.cedula='$ci' and
	clientes.id_cliente=titulares.id_cliente and 
	titulares_polizas.id_titular=titulares.id_titular and
	entes.id_ente=titulares.id_ente and 
	
	titulares_polizas.id_poliza=polizas.id_poliza and
	titulares_polizas.id_poliza=propiedades_poliza.id_poliza
	order by entes.nombre,propiedades_poliza.cualidad");
	$r_polizat=ejecutar($q_polizat);


	$q_asig=("select propiedades_poliza.cualidad,polizas.nombre_poliza, entes.nombre from 							    		coberturas_t_b,polizas,propiedades_poliza,clientes,titulares,titulares_polizas,entes where
	coberturas_t_b.id_titular=titulares_polizas.id_titular and 
	coberturas_t_b.id_beneficiario='0' and 
	coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
	propiedades_poliza.id_poliza=polizas.id_poliza and 
 	clientes.id_cliente=titulares.id_cliente and 
	titulares_polizas.id_titular=titulares.id_titular and 
	clientes.cedula='$ci' and
	titulares_polizas.id_poliza=polizas.id_poliza and
 	polizas.id_poliza=propiedades_poliza.id_poliza and 
	entes.id_ente=titulares.id_ente
	order by entes.nombre,propiedades_poliza.cualidad ");

	$r_asig=ejecutar($q_asig);
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
	       <td class="tdtitulos" colspan="1">&nbsp; Tipo de Cobertura Asignadas:</td>

					<?php  while($f_asig=asignar_a($r_asig,NULL,PGSQL_ASSOC)){?>
	<tr> <td>&nbsp;</td>	</tr>
				<td class="tdcampos"  colspan=2> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo "$f_asig[cualidad]"." ---- "."$f_asig[nombre_poliza]"." ---- "."$f_asig[nombre]"?></td></tr>


<?}?>			

	<tr> <td>&nbsp;</td>	</tr>

	<tr>
	       <td class="tdtitulos" colspan="1">&nbsp; Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select id="poliza_t" name="poliza" class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura</option>
<?php $id_beneficiario='0';
				       while($f_polizat=asignar_a($r_polizat,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo "$f_polizat[id_propiedad_poliza]-$f_polizat[id_titular]-$id_beneficiario-$f_polizat[id_ente]"?>"> <?php echo "$f_polizat[cualidad]"." -- "."$f_polizat[nombre_poliza]"." -- "."$f_polizat[nombre]"?></option>
<?}?>			
		</select>

		</td>
<input type="hidden" id="id_titular" value="<?php echo $f_ti[id_titular];?>">

<? if ($solotitu<='0'){?>


<input type="hidden" id="poliza_b" value="<?php echo 0-0-0;?>">
<?}?>	
	</tr>

		<tr> <td>&nbsp;</td>		</tr>


<?}
	if($f_cliente[id_cliente]==$f_be[id_cliente] ){
$poliza_b="";
	$q_polizab=("select beneficiarios.id_titular,beneficiarios.id_beneficiario,propiedades_poliza.id_propiedad_poliza,propiedades_poliza.id_poliza,propiedades_poliza.cualidad, entes.nombre,polizas.nombre_poliza 
from titulares_polizas,clientes,titulares,propiedades_poliza,entes,polizas,beneficiarios where 
	clientes.cedula='$ci' and
	clientes.id_cliente=beneficiarios.id_cliente and 
beneficiarios.id_titular=titulares.id_titular and 
	titulares.id_titular=titulares_polizas.id_titular and
	titulares.id_ente=entes.id_ente and 
	titulares_polizas.id_poliza=polizas.id_poliza and
	polizas.id_poliza=propiedades_poliza.id_poliza
	order by entes.nombre,propiedades_poliza.cualidad;");
	$r_polizab=ejecutar($q_polizab);



	$q_asigb=("select propiedades_poliza.cualidad,polizas.nombre_poliza, entes.nombre from 				coberturas_t_b,polizas,propiedades_poliza,clientes,titulares,titulares_polizas,entes,beneficiarios where 					        coberturas_t_b.id_titular=titulares_polizas.id_titular and 
					coberturas_t_b.id_beneficiario=beneficiarios.id_beneficiario and 
					coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
					propiedades_poliza.id_poliza=polizas.id_poliza and 
					clientes.id_cliente=beneficiarios.id_cliente and 
					titulares_polizas.id_titular=titulares.id_titular and clientes.cedula='$ci' and
					titulares_polizas.id_poliza=polizas.id_poliza and
					polizas.id_poliza=propiedades_poliza.id_poliza and  
					entes.id_ente=titulares.id_ente
					order by entes.nombre,propiedades_poliza.cualidad ");

	$r_asigb=ejecutar($q_asigb);
	?>
			
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n de Pólizas del Cliente como Beneficiario</td>
	</tr>
	</td>

	<tr> <td>&nbsp;</td>	</tr>
	<tr>
		<td class="tdtitulos">&nbsp;  Nombre y Apellido del Beneficiario: </td>
		<td class="tdcampos"> <? echo "$f_be[nombres] $f_be[apellidos]";?></td>
		<td class="tdtitulos">C&eacute;dula del Beneficiario:</td>
		<td class="tdcampos"> <? echo "$f_be[cedula]" ;?></td> 
	</tr>
	<tr> <td>&nbsp;</td>	</tr>
	<tr>
	       <td class="tdtitulos" colspan="1">&nbsp; Tipo de Cobertura Asignadas:</td>

					<?php  while($f_asigb=asignar_a($r_asigb,NULL,PGSQL_ASSOC)){?>
	<tr> <td>&nbsp;</td>	</tr>
				<td class="tdcampos"  colspan=2> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo "$f_asigb[cualidad]"." -- "."$f_asigb[nombre_poliza]"." -- "."$f_asigb[nombre]"?></td></tr>


<?}?>			

	<tr> <td>&nbsp;</td>	</tr>


<? if ($solotitu>'0'){?>


<input type="hidden" id="poliza_t" value="<?php echo 0-0-0;?>">
<?}?>


<tr>
	       <td class="tdtitulos" colspan="1">&nbsp; Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select id="poliza_b" name="poliza" class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura</option>
				     <?php  while($f_polizab=asignar_a($r_polizab,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo "$f_polizab[id_propiedad_poliza]-$f_polizab[id_titular]-$f_polizab[id_beneficiario]"?>">  <?php echo "$f_polizab[cualidad]"." ---- "."$f_polizab[nombre_poliza]"." ---- "."$f_polizab[nombre]"?></option>
<?}?>			
		</select>

		</td>
	</tr>

<?}?>
<?}?>
		<tr> <td>&nbsp;</td>		</tr>
		<tr> <td>&nbsp;</td>		</tr>
<tr>

<td  class="tdcamposcc"  colspan="4"><a href="#" OnClick="guard_poliza_asig();" class="boton">Guardar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a></td>
</tr>

		<tr> <td>&nbsp;</td>		</tr>

</table>


<div id="asig_poliza1"></div>

