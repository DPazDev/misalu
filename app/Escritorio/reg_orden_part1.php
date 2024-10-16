<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$cedula=$_REQUEST['cedula'];
$condicion=$_REQUEST['condicion'];
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
/* **** busco el admin **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/* ****fin de buscar  el admin **** */
if ($condicion==0){

}
/* **** comienza el procedimiento de registro de cliente titular como particular **** */
if ($condicion==1){
$nombret=strtoupper($_POST['nombret']);
$apellidot=strtoupper($_POST['apellidot']);
$telefonot=$_POST['telefonot'];
$id_cliente=$_POST['id_cliente'];
$celulart=$_POST['celulart'];
$direcciont=strtoupper($_POST['direcciont']);
$comentario="PARTICULAR REGISTRADO POR $f_admin[nombres] $f_admin[apellidos]";
if ($id_cliente==0){
$q_reg_cliente = "insert into clientes (nombres,apellidos,fecha_nacimiento,telefono_hab,celular,direccion_hab,fecha_creado,hora_creado,cedula,id_ciudad,comentarios,id_admin) values('$nombret','$apellidot','$fechacreado','$telefonot','$celulart','$direcciont','$fechacreado','$hora','$cedula','$f_admin[id_ciudad]','$comentario','$f_admin[id_admin]')";
$r_reg_cliente = ejecutar($q_reg_cliente);
}

/* **** busco el id_cliente registrado **** */
$q_clientet=("select clientes.id_cliente
		from 
		clientes
		where 
		clientes.cedula='$cedula'");
$r_clientet=ejecutar($q_clientet);
$f_clientet=asignar_a($r_clientet);

/* **** fin de buscar el id_cliente registrado **** */

$q_reg_titular = "insert into titulares (id_cliente,fecha_ingreso_empresa,fecha_creado,hora_creado,id_ente,fecha_inclusion,id_admin) values('$f_clientet[id_cliente]','$fechacreado','$fechacreado','$hora','53','$fechacreado','$f_admin[id_admin]')";
$r_reg_titular = ejecutar($q_reg_titular);
/* **** busco el id_titular registrado **** */
$q_titular=("select titulares.id_titular
		from 
		titulares
		where 
		titulares.id_cliente=$f_clientet[id_cliente] and titulares.id_ente=53");
$r_titular=ejecutar($q_titular);
$f_titular=asignar_a($r_titular);
/* **** fin de buscar el id_titular registrado **** */
/* **** registro el  id_titular en las tablas estados_t_b,coberturas_t_b,titulares_cargos,titulares_subdivisiones,titulares_polizas **** */
$q_reg_titu_edo = "insert into estados_t_b (id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
		values('4','$f_titular[id_titular]','0','$fechacreado','$hora')";
$r_reg_titu_edo = ejecutar($q_reg_titu_edo);
$q_reg_titu_car = "insert into titulares_cargos (id_titular,id_cargo,fecha_creado,hora_creado) 
		values('$f_titular[id_titular]','82','$fechacreado','$hora')";
$r_reg_titu_car = ejecutar($q_reg_titu_car);
$q_reg_titu_sub = "insert into titulares_subdivisiones (id_titular,id_subdivision) 
		values('$f_titular[id_titular]','86')";
$r_reg_titu_sub = ejecutar($q_reg_titu_sub);
$q_reg_titu_pol = "insert into titulares_polizas (id_titular,id_poliza,fecha_creado,hora_creado) 
		values('$f_titular[id_titular]','37','$fechacreado','$hora')";
$r_reg_titu_pol = ejecutar($q_reg_titu_pol);
$q_reg_titu_cober = "insert into coberturas_t_b 				(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,id_organo,monto_actual,monto_previo) 
		values('182','$f_titular[id_titular]','0','$fechacreado','$hora','0','100000000','100000000')";
$r_reg_titu_cober = ejecutar($q_reg_titu_cober);
/* **** fin  de registro el  id_titular en las tablas estados_t_b,coberturas_t_b,titulares_cargos,titulares_subdivisiones,titulares_polizas **** */
$log="REGISTRO EL titular $nombret $apellidot C.I. $cedula id $f_titular[id_titular] POR LA OPCION ORDEN PARTICULAR";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

/* **** fin de el procedimiento de registro de cliente titular como particular **** */




/* **** comienza el procedimiento de registro de cliente beneficiario como particular **** */
if ($condicion==2){
$nombreb=strtoupper($_POST['nombreb']);
$apellidob=strtoupper($_POST['apellidob']);
$direcciont=strtoupper($_POST['direcciont']);
$id_cliente=$_POST['id_cliente'];
$celulart=$_POST['celulart'];
$id_titular=$_POST['id_titular'];
$comentario="PARTICULAR REGISTRADO POR $f_admin[nombres] $f_admin[apellidos]";
if ($id_cliente==0){
$q_reg_cliente = "insert into clientes (nombres,apellidos,fecha_nacimiento,telefono_hab,celular,direccion_hab,fecha_creado,hora_creado,cedula,id_ciudad,comentarios,id_admin) values('$nombreb','$apellidob','$fechacreado','$celulart','$celulart','$direcciont','$fechacreado','$hora','$cedula','$f_admin[id_ciudad]','$comentario','$f_admin[id_admin]')";
$r_reg_cliente = ejecutar($q_reg_cliente);
}

/* **** busco el id_cliente registrado **** */
$q_clientet=("select clientes.id_cliente
		from 
		clientes
		where 
		clientes.cedula='$cedula'");
$r_clientet=ejecutar($q_clientet);
$f_clientet=asignar_a($r_clientet);

/* **** fin de buscar el id_cliente registrado **** */

$q_reg_beneficiario = "insert into beneficiarios (id_cliente,id_titular,id_parentesco,fecha_creado,hora_creado,fecha_inclusion,id_tipo_beneficiario,maternidad) values('$f_clientet[id_cliente]','$id_titular','7','$fechacreado','$hora','$fechacreado','7','0')";
$r_reg_beneficiario = ejecutar($q_reg_beneficiario);
/* **** busco el id_beneficiario registrado **** */
$q_beneficiario=("select beneficiarios.id_beneficiario
		from 
		beneficiarios
		where 
		beneficiarios.id_cliente=$f_clientet[id_cliente] and beneficiarios.id_titular=$id_titular");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
/* **** fin de buscar el id_beneficiario registrado **** */
/* **** registro el  id_titular en las tablas estados_t_b,coberturas_t_b,titulares_cargos,titulares_subdivisiones,titulares_polizas **** */
$q_reg_titu_edo = "insert into estados_t_b (id_estado_cliente,id_titular,id_beneficiario,fecha_creado,hora_creado) 
		values('4','$id_titular','$f_beneficiario[id_beneficiario]','$fechacreado','$hora')";
$r_reg_titu_edo = ejecutar($q_reg_titu_edo);
$q_reg_tipo_b_ben = "insert into titulares_polizas (id_titular,id_poliza,fecha_creado,hora_creado) 
		values('$f_titular[id_titular]','37','$fechacreado','$hora')";
$r_reg_tipo_b_ben = ejecutar($q_reg_tipo_b_ben);
$q_reg_ben_cober = "insert into coberturas_t_b 				(id_propiedad_poliza,id_titular,id_beneficiario,fecha_creado,hora_creado,id_organo,monto_actual,monto_previo) 
		values('182','$id_titular','$f_beneficiario[id_beneficiario]','$fechacreado','$hora','0','100000000','100000000')";
$r_reg_ben_cober = ejecutar($q_reg_ben_cober);

/* **** fin  de registro el  id_beneficiario en las tablas estados_t_b,coberturas_t_b,titulares_cargos,titulares_subdivisiones,titulares_polizas **** */
$log="REGISTRO EL BENEFICIARIO $nombreb $apellidob C.I. $cedula id $f_beneficiario[id_beneficiario] POR LA OPCION ORDEN PARTICULAR";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
}

/* **** fin de el procedimiento de registro de cliente beneficiario como particular **** */





/* **** busco si el cliente existe como titular particular**** */
$q_clientet=("select clientes.id_cliente,
		    clientes.nombres,
		    clientes.apellidos,
		    clientes.direccion_hab,
		    clientes.telefono_hab,
		    clientes.celular,
		    titulares.id_titular,
		    coberturas_t_b.id_cobertura_t_b
		from 
		clientes,
		titulares,
		coberturas_t_b 
		where 
		clientes.cedula='$cedula' and
		clientes.id_cliente=titulares.id_cliente and
		titulares.id_ente=53 and 
		titulares.id_titular=coberturas_t_b.id_titular and 
		coberturas_t_b.id_beneficiario=0");
$r_clientet=ejecutar($q_clientet);
$num_filas=num_filas($r_clientet);
if ($num_filas == 0) 
{

/* **** si no es titular  busco si el cliente es beneficiario particular**** */
	$q_clienteb=("select clientes.id_cliente,
		    		  clientes.nombres,
		    		  clientes.apellidos,
		    		  clientes.direccion_hab,
		    		  clientes.telefono_hab,
		    		  clientes.celular,
		    		  titulares.id_titular,
				  beneficiarios.id_beneficiario, 
				  coberturas_t_b.id_cobertura_t_b
				from
				  clientes,
				  beneficiarios,
				  titulares,
				  coberturas_t_b 
				where 
				  beneficiarios.id_cliente=clientes.id_cliente and
				  clientes.cedula='$cedula' and
				  beneficiarios.id_titular=titulares.id_titular and 
				  titulares.id_ente=53 and 
				  beneficiarios.id_beneficiario=coberturas_t_b.id_beneficiario" );
	$r_clienteb=ejecutar($q_clienteb);
	$num_filas1=num_filas($r_clienteb);

	if ($num_filas1 == 0) 
	{
/* **** si no es titular ni beneficiario particular busco si existe en otro ente **** */
$q_cliente=("select clientes.id_cliente,

		    clientes.nombres,
		    clientes.apellidos,
		    clientes.direccion_hab,
		    clientes.telefono_hab,

		    clientes.celular
		    
		from 

		clientes
		where 

		clientes.cedula='$cedula' ");
$r_cliente=ejecutar($q_cliente);
$num_filas2=num_filas($r_cliente);
if ($num_filas2 == 0) 
	{
/* **** si no es titular ni beneficiario particular ni en otro ente paso a registrar **** */
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Registrar Titular</td>	</tr>	
	<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="text" id="nombret" name="nombret" maxlength=128 size=20 value="">
		<input class="campos" type="hidden" id="id_cliente" name="id_cliente" maxlength=128 size=20 value="0">
</td>
		<td class="tdtitulos">Apellidos</td>
		<td class="tdcampos"><input class="campos" type="text" id="apellidot" name="apellidot" maxlength=128 size=20 value=""   ></td>
</tr>
<tr>
		<td class="tdtitulos">Telefono</td>
		<td class="tdcampos"><input class="campos" type="text" id="telefonot" name="telefonot" maxlength=128 size=20 value="0" onkeyUp="return ValNumero(this);"  ></td>
		<td class="tdtitulos">Celular</td>
		<td class="tdcampos"><input class="campos" type="text" id="celulart" name="celulart" maxlength=128 size=20 value="0" onkeyUp="return ValNumero(this);"  ></td>
</tr>
<tr>
		<td colspan=1 class="tdtitulos">Direccion</td>
		<td colspan=3 class="tdcampos"><input class="campos" type="text" id="direcciont" name="direcciont" maxlength=128 size=75 value=""   >
		

<a OnClick="reg_orden_part2(1);" title="Registrar Titular" class="boton">Registrar</a>
</td>


</table>

<?php
	}
else
{
$f_cliente=asignar_a($r_cliente);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Este Cliente Existe en Otros Entes
<?php $url3="views01/bsc_cliente.php?cedulaclien=$cedula";
						?>
						
						<a href="<?php echo $url3; ?>" title="Ver Información del Cliente" class="boton" onclick="Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;">Ver Data</a>
</td>	</tr>	
	<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="nombret" name="nombret" maxlength=128 size=20 value="<?php echo $f_cliente[nombres] ?>"> <?php echo $f_cliente[nombres] ?>
<input class="campos" type="hidden" id="id_cliente" name="id_cliente" maxlength=128 size=20 value="<?php echo $f_cliente[id_cliente] ?>">
</td>
		<td class="tdtitulos">Apellidos</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="apellidot" name="apellidot" maxlength=128 size=20 value="<?php echo $f_cliente[apellidos] ?>"   ><?php echo $f_cliente[apellidos] ?></td>
</tr>
<tr>
		<td class="tdtitulos">Telefono</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="telefonot" name="telefonot" maxlength=128 size=20 value="<?php echo $f_cliente[telefono_hab] ?>" onkeyUp="return ValNumero(this);"  ><?php echo $f_cliente[telefono_hab] ?></td>
		<td class="tdtitulos">Celular</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="celulart" name="celulart" maxlength=128 size=20 value="<?php echo $f_cliente[celular] ?>" onkeyUp="return ValNumero(this);"  ><?php echo $f_cliente[celular] ?></td>
</tr>
<tr>
		<td colspan=1 class="tdtitulos">Direccion</td>
		<td colspan=3 class="tdcampos"><input class="campos" type="hidden" id="direcciont" name="direcciont" maxlength=128 size=75 value="<?php echo $f_cliente[direccion_hab] ?>"  <?php echo $f_cliente[direccion_hab] ?> >
		

<a OnClick="reg_orden_part2(1);" title="Registrar Cliente Como Particular" class="boton">Registrar </a>
</td>


</table>

<?php
}
	}
	else
	{

	$f_clienteb=asignar_a($r_clienteb);
	/* **** si es beneficiario busco al titular de el **** */
	$q_clientet=("select clientes.id_cliente,
		    clientes.nombres,
		    clientes.apellidos,
		    clientes.direccion_hab,
		    clientes.telefono_hab,
		    clientes.celular,
		    titulares.id_titular
		from 
		clientes,
		titulares,
		beneficiarios 
		where 
		clientes.id_cliente=titulares.id_cliente and
		titulares.id_titular=beneficiarios.id_titular and
		beneficiarios.id_titular=$f_clienteb[id_titular]");
	$r_clientet=ejecutar($q_clientet);
	$f_clientet=asignar_a($r_clientet);
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Titular</td>	</tr>		
	<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="nombret" name="nombret" maxlength=128 size=20 value="<?php echo $f_clientet[nombres] ?>"> <?php echo $f_clientet[nombres] ?>  
<input class="campos" type="hidden" id="id_titular" name="id_titular" maxlength=128 size=20 value="<?php echo $f_clientet[id_titular] ?>"   >
</td>
		<td class="tdtitulos">Apellidos</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="apellidot" name="apellidot" maxlength=128 size=20 value="<?php echo $f_clientet[apellidos] ?>"   ><?php echo $f_clientet[apellidos] ?></td>
</tr>
<tr>
		<td class="tdtitulos">Telefono</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="telefonot" name="telefonot" maxlength=128 size=20 value="<?php echo $f_clientet[telefono_hab] ?>"   ><?php echo $f_clientet[telefono_hab] ?></td>
		<td class="tdtitulos">Celular</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="celulart" name="celulart" maxlength=128 size=20 value="<?php echo $f_clientet[celular] ?>"   ><?php echo $f_clientet[telefono_hab] ?></td>
</tr>
<tr>
		<td colspan=1 class="tdtitulos">Direccion</td>
		<td colspan=3 class="tdcampos"><input class="campos" type="hidden" id="direcciont" name="direcciont" maxlength=128 size=60 value="<?php echo $f_clientet[direccion_hab] ?>"   ><?php echo $f_clientet[direccion_hab] ?></td>
		
</tr>
<tr>		<td colspan=4 ><hr></hr></td>	</tr>
<tr>		<td colspan=4 class="titulo_seccion">Beneficiario</td>	</tr>
<tr>		<td colspan=4 ><hr></hr></td>	</tr>		
	<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="nombreb" name="nombreb" maxlength=128 size=20 value="<?php echo $f_clienteb[nombres] ?>"   ><?php echo $f_clienteb[nombres] ?>
<input class="campos" type="hidden" id="beneficiario" name="beneficiario" maxlength=128 size=20 value="<?php echo $f_clienteb[id_beneficiario] ?>"   >
<input class="campos" type="hidden" id="id_cobertura" name="id_cobertura" maxlength=128 size=20 value="<?php echo $f_clienteb[id_cobertura_t_b] ?>"   >
</td>
		<td class="tdtitulos">Apellidos</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="apellidob" name="apellidob" maxlength=128 size=20 value="<?php echo $f_clienteb[apellidos] ?>"   ><?php echo $f_clienteb[apellidos] ?></td>
</tr>
<tr>		<td colspan=4 ><hr></hr></td>	</tr>	
	<tr>
		
		<td colspan=2 class="tdcampos">
                <select  style="width: 200px;" OnChange="bus_exa_orden_par();" id="examenes" name="examenes" class="campos">
                <option value="0">Seleccione  los Examenes Especiales</option>
		<option value="*@EXAMENES DE LABORATORIO">Examenes de Laboratorio</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipos_imagenologia_bi.tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo "$f_texamen[id_tipo_imagenologia_bi]@$f_texamen[tipo_imagenologia_bi]"?>"> 
		<?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>

</td>
<td class="tdtitulos">
<input class="campos" type="hidden" id="especialidad" name="especialidad" maxlength=10 size=10 value="0">
<input class="campos" type="hidden" id="tipo_cliente" name="tipo_cliente" maxlength=10 size=10 value="1">
<a href="#" OnClick="reg_cita();" id="eti_cita" class="boton" title="Ir a la Opcion de Registrar Citas Medicas"> Consulta Medica</a>
<a href="#" OnClick="reg_oa();" id="eti_cita" class="boton" title="Ir a la Opcion de Registrar Ordenes de Atencion"> Registrar Orden</a>
</td>

</tr>
<tr>
<td colspan=4>
<div id="buscar_procesosf"></div>


</td>
</tr>
</table>

<?php

	}

}
else
{
	$f_clientet=asignar_a($r_clientet);
?>	
	

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		<td colspan=4 class="titulo_seccion">Titular</td>	</tr>	
	<tr>
		<td class="tdtitulos">Nombres</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="nombret" name="nombret" maxlength=128 size=20 value="<?php echo $f_clientet[nombres] ?>"   ><?php echo $f_clientet[nombres] ?>
<input class="campos" type="hidden" id="id_titular" name="id_titular" maxlength=128 size=20 value="<?php echo $f_clientet[id_titular] ?>"   >
<input class="campos" type="hidden" id="id_cobertura" name="id_cobertura" maxlength=128 size=20 value="<?php echo $f_clientet[id_cobertura_t_b] ?>"   >
</td>
		<td class="tdtitulos">Apellidos</td>
		<td class="tdcampos"><input class="campos" type="hidden" id="apellidot" name="apellidot" maxlength=128 size=20 value="<?php echo $f_clientet[apellidos] ?>"   ><?php echo $f_clientet[apellidos] ?></td>
</tr>
<tr>
		<td class="tdtitulos">Telefono </td>
		<td class="tdcampos"><input class="campos" type="hidden" id="telefonot" name="telefonot" maxlength=128 size=20 value="<?php echo $f_clientet[telefono_hab] ?>"   ><?php echo $f_clientet[telefono_hab] ?></td>
		<td class="tdtitulos">Celular </td>
		<td class="tdcampos"><input class="campos" type="hidden" id="celulart" name="celulart" maxlength=128 size=20 value="<?php echo $f_clientet[celular] ?>"   ><?php echo $f_clientet[celular] ?></td>
</tr>
<tr>
		<td colspan=1 class="tdtitulos">Direccion</td>
		<td colspan=3 class="tdcampos"><input class="campos" type="hidden" id="direcciont" name="direcciont" maxlength=128 size=75 value="<?php echo $f_clientet[direccion_hab] ?>"   ><?php echo $f_clientet[direccion_hab] ?></td>
		
</tr>
<tr>
		<td colspan=1 class="tdtitulos">Beneficiarios</td>
		<td colspan=3 class="tdcampos">
		<select  id="beneficiario" name="beneficiario" style="width: 200px;" class="campos" OnChange="ver_reg_bene(this);">
                <option value="0">Seleccione  Algun Beneficiario</option>
		<option value="*">Registrar   Beneficiario</option>
                <?php
				$q_bene=("select clientes.id_cliente,
		    		  clientes.nombres,
		    		  clientes.apellidos,
		    		  clientes.direccion_hab,
		    		  clientes.telefono_hab,
		    		  clientes.celular,
				  clientes.cedula,
		    		  beneficiarios.id_beneficiario 
				from
				  clientes,
				  beneficiarios
				where 
				  beneficiarios.id_cliente=clientes.id_cliente  and
				  beneficiarios.id_titular=$f_clientet[id_titular]");
		$r_bene=ejecutar($q_bene);
                while($f_bene=asignar_a($r_bene,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_bene[id_beneficiario]?>"> 
		<?php echo "$f_bene[nombres] $f_bene[apellidos] C.I. $f_bene[cedula]"  ?>
                 </option>
                <?php
                }
                ?>
                </select></td>
		
</tr>
</tr>
<tr>		<td colspan=4 ><hr></hr></td>	</tr>	
<tr>		<td colspan=4 style="visibility:hidden" id="eti_reg_ben" class="titulo_seccion">Registrar Beneficiario</td>	</tr>
<tr>		<td colspan=4 ><hr></hr></td>	</tr>	
<tr>
		<td style="visibility:hidden" id="eti_cedula" class="tdtitulos" >Cedula </td>
		<td style="visibility:hidden" class="tdcampos"><input class="campos" type="text" id="cedulab" name="cedulab" maxlength=10 size=10 value="" OnChange="verificar_bene(this);"></td>
		<td colspan=2 class="tdtitulos"><div id="verificar_bene"></div> </td>
		
</tr>
	
		
		<td colspan=2 class="tdcampos">
                <select  style="width: 200px;" OnChange="bus_exa_orden_par();" id="examenes" name="examenes" class="campos">
                <option value="0">Seleccione  los Examenes Especiales</option>
		<option value="*@EXAMENES DE LABORATORIO">Examenes de Laboratorio</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipos_imagenologia_bi.tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo "$f_texamen[id_tipo_imagenologia_bi]@$f_texamen[tipo_imagenologia_bi]"?>"> 
		<?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>

</td>
<td class="tdtitulos">
<input class="campos" type="hidden" id="especialidad" name="especialidad" maxlength=10 size=10 value="0">
<input class="campos" type="hidden" id="tipo_cliente" name="tipo_cliente" maxlength=10 size=10 value="1">

<input class="campos" type="hidden" id="cua_rec_prim" name="cua_rec_prim" maxlength=10 size=10 value="0">
<input class="campos" type="hidden" id="tipo_ente" name="tipo_ente" maxlength=10 size=10 value="0">
<a href="#" OnClick="reg_cita();" id="eti_cita" class="boton" title="Ir a la Opcion de Registrar Citas Medicas"> Consulta Medica</a>
<a href="#" OnClick="reg_oa();" id="eti_cita" class="boton" title="Ir a la Opcion de Registrar Ordenes de Atencion"> Registrar Orden</a>
</td>

</tr>
<tr>
<td colspan=4>
<div id="buscar_procesosf"></div>


</td>
</tr>

</table>

<?php
}
?>
