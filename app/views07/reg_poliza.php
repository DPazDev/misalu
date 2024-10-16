<?php

/* Nombre del Archivo: reg_vendedor.php
   Descripción: Formulario para solicitar los datos para INSERTAR o MODIFICAR un VENDEDOR en la base de datos 
*/

include ("../../lib/jfunciones.php");
sesion();


$q_usuario=("select admin.nombres, admin.apellidos, admin.id_admin from admin order by admin.nombres");
$r_usuario=ejecutar($q_usuario);

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">

<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"  src="config/md5.js" charset="iso-8859-1"></script>



<form action="guardar_usuario.php" method="post" id="usuario" name="usuario">

<table  class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

	<tr>
		<td colspan=4 class="titulo_seccion">Registrar o Modificar Usuario</td>
	</tr>	
<tr><td>&nbsp;</td></tr>
	<tr>
                <td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Verificar N&uacute;mero de C&eacute;dula</td>
                <td colspan=1 class="tdcampos"><input class="campos" type="text" name="ci" maxlength=150 size=25 value=""></td>
	</tr>
<tr><td>&nbsp;</td></tr>

	<tr>
		<td colspan=1 class="tdtitulos">&nbsp; &nbsp; * Nombre del Usuario: </td>
		<td colspan=1 class="tdcamposcc" ><select id="nombre" name="nombre" class="campos" style="width: 300px;" >
	                              <option value="0">--- Seleccionar un Usuario. ---</option>
				      <?php  while($f_usuario=asignar_a($r_usuario,NULL,PGSQL_ASSOC)){?>
		                      <option value="<?php echo $f_usuario[id_admin]?>"> <?php echo "$f_usuario[nombres] $f_usuario[apellidos]"?></option>
				     <?php }?> 
		</td>


		<td colspan=2 class="tdcampos"><a href="#" OnClick="mod_usuario();" class="boton">Buscar</a> <a href="#" OnClick="ir_principal();" class="boton">Salir</a>
		</td>
        </tr>

<tr><td>&nbsp;</td></tr>
</table>
<div id="guardar_usuario"></div>
</from>



<?
/*  Nombre del Archivo: r_gastos_cliente1.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de Gastos del Cliente
*/

	include ("../../lib/jfunciones.php");
	sesion();   
	$ci=$_REQUEST['ci'];
      
	$q_cliente=("select clientes.cedula, clientes.id_cliente from clientes where clientes.cedula='$ci'");
	$r_cliente=ejecutar($q_cliente);
	$f_cliente=asignar_a($r_cliente);

	$q_ti=("select clientes.cedula, titulares.id_cliente, titulares.id_titular from clientes, titulares where clientes.cedula='$ci' and 	titulares.id_cliente=clientes.id_cliente ");
	$r_ti=ejecutar($q_ti);
	$f_ti=asignar_a($r_ti);

	$q_be=("select clientes.cedula, beneficiarios.id_cliente, beneficiarios.id_titular from clientes, beneficiarios where clientes.cedula='$ci' and beneficiarios.id_cliente=clientes.id_cliente ");
	$r_be=ejecutar($q_be);
	$f_be=asignar_a($r_be);



/*
echo $ci."//";
echo "$f_cliente[cedula]"."///";
echo $f_cliente[id_cliente]."//";
echo $f_ti[id_cliente]."***";
echo $f_be[id_cliente]."----";

$cliente=$f_cliente[id_cliente];
echo $cliente.";*/

	
	if($ci!=$f_cliente[cedula] ){?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
        <tr>
		<td colspan=4 class="titulo_seccion"> <?echo "NO EXISTE UN CLIENTE CON EL NUMERO DE CEDULA $ci"; ?></td>
	</tr>
	<? }

else {
		  
?>

	<table class="tabla_citas"  cellpadding=0 cellspacing=0>

<?php if($f_cliente[id_cliente]==$f_ti[id_cliente]){






	$q_titular=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula,
			titulares.id_titular, titulares.id_ente,entes.nombre, estados_clientes.id_estado_cliente, 		estados_clientes.estado_cliente	
			from clientes, titulares, entes, estados_clientes, estados_t_b
			where clientes.cedula='$ci' and
				titulares.id_cliente= clientes.id_cliente and
				estados_t_b.id_beneficiario=0 and
				titulares.id_ente=entes.id_ente and 
				estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
				estados_t_b.id_titular=titulares.id_titular and
				estados_clientes.id_estado_cliente= estados_t_b.id_estado_cliente ;");
	$r_titular=ejecutar($q_titular);

$c=0;
		while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC)){
$c=$c+1;?>

	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Gastos del Cliente como Titular</td>
	</tr>
	<tr> <td>&nbsp;</td>
	<tr> 
		<td class="tdtitulos"> Nombre y Apellido del Titular: </td>
		<td class="tdcampos"> <? echo "$f_titular[nombres] $f_titular[apellidos]";?></td>
		<td class="tdtitulos">Ente:</td> 
		<td class="tdcampos"> <input type="hidden" id="id_ente_<?php echo $c?>" name="id_ente_<?php echo $c?>" value="<? echo "$f_titular[id_ente]";?>"> </input><? echo "$f_titular[nombre]";?></td>
	</tr>

	<tr>	
		<td class="tdtitulos">C&oacute;digo:</td> 
		<td class="tdcampos" ><? echo "$f_titular[id_titular]"; ?></td>
			<input type="hidden" name="id_titu" id="id_titu" value="<?php echo $f_titular[id_titular];?>">
	
		<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr" > <? echo "$f_titular[estado_cliente]";?></td>
	</tr>

	<tr> 
		<td class="tdtitulos">C&eacute;dula del Titular:</td>
		<td class="tdcampos"> <? echo "$f_titular[cedula]" ;?></td> 	
	</tr>

		<tr> <td>&nbsp;</td>

	<? $q_polizat=("select coberturas_t_b.*, propiedades_poliza.*, entes.nombre from coberturas_t_b,titulares,propiedades_poliza,entes,polizas_entes where coberturas_t_b.id_titular=$f_titular[id_titular] and


coberturas_t_b.id_beneficiario='0' and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and entes.id_ente=titulares.id_ente and 
polizas_entes.id_poliza=propiedades_poliza.id_poliza and 
polizas_entes.id_ente=titulares.id_ente");
	$r_polizat=ejecutar($q_polizat);
	?>


	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select id="poliza_<?php echo $c?>" name="poliza" class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura</option>
				     <?php  while($f_polizat=asignar_a($r_polizat,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_polizat[id_cobertura_t_b]?>"> <?php echo "$f_polizat[cualidad]"." -- "."$f_polizat[nombre_poliza]"." -- "."$f_polizat[id_titular]"?></option>

  <?php
}?> 
		</td>
		
<td colspan=1 class="tdtitulos">* Nombres</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="nomb" name="nombr" maxlength=150 size=25 value=""></td>


	</tr>
		<tr> <td>&nbsp;</td>		
<?}}?>

<tr>




</tr>				     


<?php if($f_cliente[id_cliente]==$f_be[id_cliente]){

	$q_beneficiario=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula,titulares.id_titular,
			beneficiarios.id_titular, beneficiarios.id_beneficiario, titulares.id_ente,entes.nombre, estados_clientes.estado_cliente
			
			from clientes, titulares, beneficiarios, entes, estados_clientes, estados_t_b
			where clientes.cedula='$ci' and

				beneficiarios.id_cliente= clientes.id_cliente and
				titulares.id_ente=entes.id_ente and 
				estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
				estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
				estados_t_b.id_titular=titulares.id_titular and
				estados_clientes.id_estado_cliente= estados_t_b.id_estado_cliente");

	$r_beneficiario=ejecutar($q_beneficiario);

		while($f_beneficiario=asignar_a($r_beneficiario,NULL,PGSQL_ASSOC)){
$c=$c+1;

	$q_titu=("select titulares.id_titular, clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula,titulares.id_ente,entes.nombre, estados_clientes.id_estado_cliente, estados_clientes.estado_cliente from clientes, titulares, entes, estados_clientes, estados_t_b where
			titulares.id_titular=$f_beneficiario[id_titular] and
			clientes.id_cliente=titulares.id_cliente and
titulares.id_ente=entes.id_ente and 
				estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
				estados_t_b.id_beneficiario=0 and
				estados_t_b.id_titular=titulares.id_titular and
				estados_clientes.id_estado_cliente= estados_t_b.id_estado_cliente");
	$r_titu=ejecutar($q_titu);
	$f_titu=asignar_a($r_titu);
?>
	<tr> <td>&nbsp;</td>
	<tr>
		<td colspan=4 class="titulo_seccion">Relaci&oacute;n Gastos del Cliente Beneficiario</td>
	</tr>
	<tr> <td>&nbsp;</td>
	<tr> 
		<td class="tdtitulos" > Nombre y Apellido del Titular:</td>
		<td class="tdcampos"> <? echo "$f_titu[nombres] $f_titu[apellidos]";?></td>
		<td class="tdtitulos">Ente:</td>
		<td class="tdcampos" > <? echo "$f_titu[nombre]";?></td>
	</tr>

	<tr>	
		<td class="tdtitulos">C&oacute;digo:</td> 
		<td class="tdcampos" ><? echo "$f_titu[id_titular]"; ?></td>


		<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr" > <? echo "$f_titu[estado_cliente]";?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">C&eacute;dula del Titular:</td>
		<td class="tdcampos" > <? echo "$f_titu[cedula]" ;?></td>
	</tr>
	<tr> <td>&nbsp;</td>

	<tr> 
		<td class="tdtitulos" > Nombre y Apellido del Beneficiario:</td>
		<td class="tdcampos" ><? echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]";?></td>
		<td class="tdtitulos">Ente:</td>
		<td class="tdcampos"> <input type="hidden" id="id_ente_<?php echo $c?>" name="id_ente_<?php echo $c?>" value="<? echo $f_titu[id_ente];?>"> </input><? echo "$f_titu[nombre]";?></td>
	</tr>
		
		

	<tr> 
		<td class="tdtitulos">C&oacute;digo:</td>
		<td class="tdcampos" ><? echo "$f_beneficiario[id_beneficiario]"; ?></td>
			<input type="hidden" name="id_bene" id="id_bene" value="<?php echo $f_beneficiario[id_beneficiario];?>">
		<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr" > <? echo "$f_beneficiario[estado_cliente]";?></td>
	</tr>

	<tr> 
		<td class="tdtitulos">C&eacute;dula del Beneficiario:</td>
		<td class="tdcampos" ><? echo "$f_beneficiario[cedula]" ;?></td>
	</tr>
	<tr> <td>&nbsp;</td>
	
	<? $q_polizab=("select coberturas_t_b.*,propiedades_poliza.cualidad from coberturas_t_b,propiedades_poliza where coberturas_t_b.id_titular=$f_beneficiario[id_titular] and coberturas_t_b.id_beneficiario=$f_beneficiario[id_beneficiario] and coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and coberturas_t_b.id_organo<=1 order by propiedades_poliza.cualidad");
	$r_polizab=ejecutar($q_polizab);
	?>


	<tr>
	       <td class="tdtitulos" colspan="1">* Seleccione el Tipo de Cobertura:</td>
	       <td class="tdcampos"  colspan="1"><select name="poliza" id="poliza_<?php echo $c;?>" class="campos"  style="width: 210px;" >
                                     <option value= ""> Seleccione una Cobertura </option>
				     <?php  while($f_polizab=asignar_a($r_polizab,NULL,PGSQL_ASSOC)){?>
				     <option value="<?php echo $f_polizab[id_cobertura_t_b]?>"><?php echo "$f_polizab[cualidad]"." -- "."$f_polizab[nombre_poliza]"." -- "."$f_polizab[id_beneficiario]"?></option>
				     <?php
}?> 
		</td>

<td colspan=1 class="tdtitulos">* Monto</td>

		<td colspan=1 class="tdcampos"><input class="campos" type="text" id="monto" name="monto" maxlength=150 size=25 value=""></td>


	
	</tr>
		<tr> <td>&nbsp;</td>		
<?}}?>

	<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $c ?>" id="contador"></td>
        </tr>


<tr>




</tr>

	
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











