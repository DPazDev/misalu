<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];

/* **** Se Verifica el Usuario **** */
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin="select * from admin where id_admin='$admin'";
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);

/* ***** Comienzo la verificacion de permisologia de la cualidad de un plan que se va a activar ***** */
$q_ver_pe=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=18");
$r_ver_pe=ejecutar($q_ver_pe);
$num_filaspe=num_filas($r_ver_pe);

if ($num_filaspe==0){
						$q_ver_pe1=("select * from permisos where permisos.id_admin='$admin' and 		
												permisos.id_modulo=19");
						$r_ver_pe1=ejecutar($q_ver_pe1);
						$num_filaspe1=num_filas($r_ver_pe1);
						if ($num_filaspe1==0){
												$q_ver_pe2=("select * from permisos where permisos.id_admin='$admin' and 		
												permisos.id_modulo=20");
												$r_ver_pe2=ejecutar($q_ver_pe2);
												$num_filaspe2=num_filas($r_ver_pe2);
													if ($num_filaspe2==0){
														$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
														}
														else
														{
																$f_ver_pe2=asignar_a($r_ver_pe2);

																if ($f_ver_pe2[permiso]=='1'){
																$cualidad=" ";
											
																}
																else
																{
																$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
																}
															}
							}
							else
							{
									$f_ver_pe1=asignar_a($r_ver_pe1);

									if ($f_ver_pe1[permiso]=='1'){
									$cualidad="propiedades_poliza.cualidad!='PLAN EXCESO' and ";
				
									}
									else
									{
									$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and";
									}
								}
	
	}
	else
	{

				$f_ver_pe=asignar_a($r_ver_pe);

				if ($f_ver_pe[permiso]=='1'){
								$cualidad="(propiedades_poliza.cualidad='GASTOS AMBULATORIOS' or propiedades_poliza.cualidad='PLAN BASICO' ) and";
				
				}
				else
				{
				$cualidad="propiedades_poliza.cualidad='GASTOS AMBULATORIOS' and ";
				}
	}
/* ***** Fin Comienzo la verificacion de permisologia de la cualidad de un plan que se va a activar ***** */

/**** buscar si este proceso se ha facturado ****/
$q_factura=("select 
								* 
						from 
								tbl_facturas,
								tbl_procesos_claves,
								tbl_series 
						where 
								tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
								tbl_procesos_claves.id_proceso=$proceso and 
								tbl_facturas.id_serie=tbl_series.id_serie and 
								tbl_facturas.id_estado_factura<>3");
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);

	if ($num_filasf>0 and $f_admin[id_tipo_admin]!=16)
	{
	?>	
    <table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">Esta orden esta Facturada en los Siguientes Numeros de Factura</td>
      </tr>
	  <tr>
				<td colspan=1 class="tdtitulos">Numero Factura</td>
					<td colspan=1 class="tdtitulos">Numero Control</td>
              	<td colspan=1 class="tdtitulos">Estado Factura y serie</td>
				<td colspan=1 class="tdtitulos">Fecha Creado</td>
	</tr>	
	<?php 
	while($f_factura=asignar_a($r_factura,NULL,PGSQL_ASSOC)){

	if($f_factura['id_estado_factura']==1)
	{
	$estado="Pagada";
	}
	 if($f_factura['id_estado_factura']==2) 
	{
		$estado="Por Cobrar";
		}
		if($f_factura['id_estado_factura']==3)
		{
			$estado="Anulada";
			}
	?>
	<tr>
				<td colspan=1 class="tdcampos"><?php echo $f_factura[numero_factura] ?></td>
              	<td colspan=1 class="tdcampos"><?php echo $f_factura[numcontrol] ?></td>
				<td colspan=1 class="tdcampos"><?php echo "$estado  Serie $f_factura[nomenclatura]" ?></td>
				<td colspan=1 class="tdcampos"><?php echo $f_factura[fecha_hora_creado] ?></td>
	</tr>	
	<?php
		}
		}
        else
        {

/* **** verifico si es un proceso que no se encuentra en espera y no sea preventivo**** */
$q_poliza="select 
							procesos.nu_planilla,
							servicios.servicio,
							estados_procesos.estado_proceso,
							gastos_t_b.descripcion as descrip,
							gastos_t_b.nombre,
							gastos_t_b.id_tipo_servicio,
							gastos_t_b.id_servicio,
							gastos_t_b.id_proveedor,
							gastos_t_b.fecha_cita,
							gastos_t_b.hora_cita,
							gastos_t_b.enfermedad,
							gastos_t_b.monto_aceptado,
							procesos.fecha_recibido,
							procesos.id_estado_proceso,
							procesos.comentarios,
							coberturas_t_b.id_cobertura_t_b,
							coberturas_t_b.id_titular,
							coberturas_t_b.id_beneficiario,
							polizas.* 
					from 
							polizas,
							coberturas_t_b,
							gastos_t_b,
							procesos,
							servicios,
							estados_procesos,
							propiedades_poliza 
					where 
							polizas.id_poliza=propiedades_poliza.id_poliza and 
							propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and 
							coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and 
							gastos_t_b.id_proceso=procesos.id_proceso and 
							procesos.id_proceso='$proceso' and 
							procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
							gastos_t_b.id_servicio=servicios.id_servicio";
$r_poliza=ejecutar($q_poliza);
$num_filas=num_filas($r_poliza);

if ($num_filas == 0) { 
/* **** verifico si es un proceso que se encuentra en espera o esta anulado**** */
$q_poliza="select 
						procesos.nu_planilla,
						servicios.servicio,
						estados_procesos.estado_proceso,
						procesos.* 
					from 
						estados_procesos,
						procesos,
						servicios 
					where 
						procesos.id_proceso='$proceso' and 
						procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
						estados_procesos.id_estado_proceso>=13 and 
						estados_procesos.id_estado_proceso<=14  and 
						procesos.id_servicio_aux=servicios.id_servicio";
$r_poliza=ejecutar($q_poliza);
$num_filas2=num_filas($r_poliza);



if ($num_filas2 == 0) { 
/* **** verifico si es un proceso preventivo**** */
$q_poliza="select
							procesos.nu_planilla,
							servicios.servicio,
							estados_procesos.estado_proceso,
							gastos_t_b.descripcion as descrip,
							gastos_t_b.nombre,
							gasts_t_b.id_tipo_servicio,
							gastos_t_b.id_servicio,
							gastos_t_b.id_proveedor,
							gastos_t_b.fecha_cita,
							gastos_t_b.hora_cita,
							gastos_t_b.enfermedad,
							gastos_t_b.monto_aceptado,
							procesos.fecha_recibido,
							procesos.comentarios  
					from 
							gastos_t_b,
							procesos,
							servicios,
							estados_procesos 
					where 
							gastos_t_b.id_proceso=procesos.id_proceso and 
							procesos.id_proceso='$proceso' and 
							procesos.id_estado_proceso=estados_procesos.id_estado_proceso and 
							gastos_t_b.id_servicio=servicios.id_servicio";
$r_poliza=ejecutar($q_poliza);
$num_filas3=num_filas($r_poliza);
}
}


if ($num_filas == 0 and $num_filas2 == 0 and $num_filas3 == 0) { 


?>
<table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "El Numero de Orden no Existe $comentario "?></td></tr>
<?php
}
else
{
	
$f_poliza=asignar_a($r_poliza);

$q_poliza1="select 
								gastos_t_b.descripcion as descrip,
								gastos_t_b.nombre,
								gastos_t_b.id_tipo_servicio,
								gastos_t_b.id_servicio,
								gastos_t_b.id_proveedor,
								gastos_t_b.fecha_cita,
								gastos_t_b.hora_cita,
								gastos_t_b.enfermedad,
								gastos_t_b.monto_aceptado,
								procesos.fecha_recibido,
								procesos.comentarios,
								coberturas_t_b.id_cobertura_t_b,
								coberturas_t_b.id_titular,
								coberturas_t_b.id_beneficiario,
								polizas.* 
						from
								polizas,
								coberturas_t_b,
								gastos_t_b,
								procesos,
								propiedades_poliza 
						where 
								polizas.id_poliza=propiedades_poliza.id_poliza and 
								propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and 
								coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and 
								gastos_t_b.id_proceso=procesos.id_proceso and 
								procesos.id_proceso='$proceso'";
$r_poliza1=ejecutar($q_poliza1);


/* **** busco si es titular **** */
$q_cliente=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.tipo_ente

						from 
								clientes,
								titulares,
								estados_t_b,
								estados_clientes,
								entes,
								tbl_tipos_entes,
								procesos
						where 
								clientes.id_cliente=titulares.id_cliente and 
								titulares.id_titular=estados_t_b.id_titular and 
								estados_t_b.id_beneficiario=0 and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
								titulares.id_ente=entes.id_ente and 
								entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
								estados_clientes.id_estado_cliente>=4 and 
								titulares.id_titular=procesos.id_titular  and 
								procesos.id_proceso='$proceso'");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$f_cliente=asignar_a($r_cliente);
$iddelente=$f_cliente[id_ente];

/* **** busco si es beneficiario **** */

$q_clienteb=("select 
									clientes.id_cliente,
									clientes.nombres,
									clientes.apellidos,
									clientes.cedula,
									estados_clientes.estado_cliente,
									beneficiarios.id_beneficiario,
									beneficiarios.id_titular,
									entes.nombre 
							from 
									clientes,
									estados_clientes,
									beneficiarios,
									entes,
									estados_t_b,
									titulares,
									procesos  
							where 
									clientes.id_cliente=beneficiarios.id_cliente and 
									beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
									estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
									beneficiarios.id_titular=titulares.id_titular and 
									titulares.id_ente=entes.id_ente and 
									estados_clientes.id_estado_cliente>=4 and 
									titulares.id_titular=procesos.id_titular and 
									beneficiarios.id_beneficiario=procesos.id_beneficiario and
									 procesos.id_proceso='$proceso'");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$f_clienteb=asignar_a($r_clienteb);
if ($num_filasb == 0) { 
$titular=1;
}
else
{
	$titular=0;
	}


?>

<table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente "?></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo $f_cliente[nombres]?> <?php echo $f_cliente[apellidos]?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_cliente[cedula]?></td>
        </tr>


        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_cliente[estado_cliente]?></td>
        </tr>
		      <tr>
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_cliente[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
        </tr>
<?php if ($titular==0)
{
?>
<tr>
                <td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
                <td class="tdcampos"><?php echo $f_clienteb[nombres]?> <?php echo $f_clienteb[apellidos]?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<?php
}
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de $f_poliza[servicio] estado $f_poliza[estado_proceso]"?></td></tr>
<tr> 
               
		<td  class="tdtitulos">Fecha de Recepcion:   </td>
		<td class="tdcampos"><input readonly type="hidden" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value=<?php echo  $f_poliza[fecha_recibido]; ?>>
 <?php echo $f_poliza[fecha_recibido]; ?>

                </td>
		<td class="tdtitulos">Fecha de Cita:</td>
		<td class="tdcampos"> <input readonly type="hidden" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_cita]?>">
<?php echo $f_poliza[fecha_cita]?>
                </td>
	</tr>
	
   <tr>
		 		<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="tiposerv" maxlength=128 size=20 value="<?php echo $f_poliza[id_tipo_servicio]?>"   ><input class="campos" type="hidden" name="servicio" maxlength=128 size=20 value="<?php echo $f_poliza[id_servicio]?>" ><input class="campos" type="hidden" name="proveedor" maxlength=128 size=20 value="<?php echo $f_poliza[id_proveedor]?>"   ><input class="campos" type="hidden" name="horac" maxlength=128 size=20 value="<?php echo $f_poliza[hora_cita]?>"   ><?php echo $f_poliza[hora_cita]?></td>
			           <input class="campos" type="hidden" name="numpro" maxlength=128 size=20 value="<?php echo $f_poliza[factura]?>"   >
				<td class="tdtitulos">Cuadro Medico</td>
              	<td class="tdcampos"><?php echo $f_poliza[enfermedad] ?></td>
	</tr>		
	
		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><?php echo $f_poliza[comentarios]?></td>
		</tr>		
	
	<tr>
				<td colspan=1 class="tdtitulos">Nombre del Gasto</td>
              	<td colspan=2 class="tdtitulos">Descripcion</td>
				<td colspan=1 class="tdtitulos">Gasto</td>
	</tr>	
		<?php
		while($f_poliza1=asignar_a($r_poliza1,NULL,PGSQL_ASSOC)){
		$totalmonto=$totalmonto+ $f_poliza1[monto_aceptado];
		$descri=$f_poliza1[descrip];
		?>
	<tr>
				<td colspan=1 class="tdcampos"><?php echo $f_poliza1[nombre] ?></td>
				<td colspan=2 class="tdcampos"><?php echo $f_poliza1[descrip]?></td>
				<td colspan=1 class="tdcampos"><?php echo $f_poliza1[monto_aceptado]?></td>
		<?php
		}
		?>
	</tr>		
			<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=2 class="tdtitulos">Total</td>
				<td colspan=1 class="tdtitulos"><?php echo formato_montos($totalmonto);?></td>
	</tr>	
	
	

		<?php 
if ($f_poliza[id_tipo_servicio]==6 || $f_poliza[id_tipo_servicio]==8 || $f_poliza[id_tipo_servicio]==12) {
			if (($f_poliza[id_tipo_servicio]==8) || ($f_poliza[id_tipo_servicio]==12)){
	?>	
		<tr>
					<td class="tdtitulos">Numero Presupuesto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="numpre" 
					maxlength=128 size=20 value="<?php echo $f_poliza[nu_planilla]?>"   >
					</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>		
	<?php
	}
	else
	{
	?>
		<tr>
					<td class="tdtitulos"></td>
              	<td class="tdcampos"><input class="campos" type="hidden" name="numpre" 
					maxlength=128 size=20 value="0"   >
					</td>
				<td class="tdtitulos"></td>
              	<td class="tdcampos"></td>
	</tr>
	
	<?php
	}
	?>	
           <tr>
<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexal(<?php echo $iddelente?>);" class="boton">* Examenes de Laboratorio</a></td>

<td colspan=1 class="tdcampos">
                <select  name="examenes" class="campos">
                <option value="0">Seleccione  los Examenes Especiales</option>
                <?php
				$q_texamen=("select * from tipos_imagenologia_bi  order by tipo_imagenologia_bi");
$r_texamen=ejecutar($q_texamen);
                while($f_texamen=asignar_a($r_texamen,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_texamen[id_tipo_imagenologia_bi]?>"> <?php echo $f_texamen[tipo_imagenologia_bi]  ?>
                 </option>
                <?php
                }
                ?>
                </select>
              
  </td>
			<td colspan=1 class="tdtitulos"><a href="#" OnClick="buscarexae(<?php echo $iddelente?>);" class="boton">Buscar </a></td>
			
</tr>

<tr>
<td colspan=4>
<div id="buscarexa"></div>

</td>
</tr>

<?php 
} 
else
{

	if ($f_poliza[id_tipo_servicio]==0 and ($f_poliza[id_servicio]==2 || $f_poliza[id_servicio]==3 || $f_poliza[id_servicio]==8 || $f_poliza[id_servicio]==11  || $f_poliza[id_servicio]==13 || $f_poliza[id_servicio]==10) ){
	$ban="checked";
	?>
   <tr>
		 
				<td colspan=1 class="tdtitulos">* Honorarios Medicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_1" name="montoh" 
					maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);" ><input class="campos"  style="visibility:hidden"  type="checkbox"  <?php echo $ban ?> id="check_100"name="checkl" maxlength=128 size=20 value="">
				</td>
				<td colspan=1 class="tdtitulos">* Gastos Clinicos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="text" id="honorarios_2" name="montog" maxlength=128 size=20 value="0"   OnChange="return validarNumero(this);"><input class="campos"  style="visibility:hidden" type="checkbox"  <?php echo $ban ?>  id="check_200" name="checkl" maxlength=128 size=20 value="">
				</td>

	</tr>		
	<tr>
		 
				<td colspan=1 class="tdtitulos">* Otros Gastos</td>
              	<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="honorarios_3"  name="montoo" maxlength=128 size=20 value="0"   ><input class="campos" style="visibility:hidden" type="checkbox" <?php echo $ban ?>   id="check_300" name="checkl" maxlength=128 size=20 value=""><input class="campos" type="hidden" name="numpre" 
					maxlength=128 size=20 value="0"   >
					</td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
             

	</tr>	
<?php
$descri=$f_poliza[nombre] ;
}


if ($f_poliza[id_servicio]==14 and $f_poliza[id_tipo_servicio]==18)
{
?>

	<tr>
		<td colspan=2 class="tdtitulos">Concepto 
	 </td>
<td  class="tdtitulos">Descripcion 
		 </td>
		<td  class="tdtitulos">Monto 
		 </td>
		
		
</tr>
	<?php
	
$ban="";
	for( $i=1; $i<24; $i++){

	?>
	<tr>
		<td  colspan=2 class="tdtitulos">
		<select style="width: 300px;" id="nombre_<?php echo $i?>" name="nombre" class="campos" >
	
				<option value="Seleccione el Tipo de Concepto"> Seleccione el Tipo de Concepto</option>
				<option value="SERVICIO DE ADMINISTRACION"> SERVICIO DE ADMINISTRACION</option>
				<option value="MEDICAMENTOS"> MEDICAMENTOS</option>
				<option value="MATERIALES"> MATERIALES</option>
				<option value="MATERIALES FUNGIBLES Y DESCARTABLE"> MATERIALES FUNGIBLES Y DESCARTABLE</option>
				<option value="EQUIPOS MEDICOS"> EQUIPOS MEDICOS</option>
				<option value="USO DE SALA"> USO DE SALA</option>
				<option value="PAGOS A TERCEROS"> PAGOS A TERCEROS</option>
				
			</select> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" >
		<input class="campos" type="hidden" id="tiposerv_<?php echo $i?>" name="tiposerv"  maxlength=128 size=10 value="18">
		<input class="campos" type="hidden" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value="">
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""> 
		
		
		</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos"><input class="campos" type="hidden" id="tc_<?php echo $i?>" name="tc" maxlength=128 size=4 value="0">
<input class="campos" type="hidden" id="fechaci_<?php echo $i?>" name="fechaci" maxlength=128 size=4 value="1900-01-01">
<input class="campos" type="hidden" id="fechacf_<?php echo $i?>" name="fechacf" maxlength=128 size=4 value="1900-01-01">
<input class="campos" type="hidden" id="dd_<?php echo $i?>" name="dd" maxlength=128 size=4 value=""> 
<input class="campos" type="hidden" id="t_<?php echo $i?>" name="t" maxlength=128 size=4 value="">  
</td>
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos">* Monto Total</td>

              	<td class="tdcampos"></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>

<?php
}

if ($f_poliza[id_servicio]==1){
?>

	<tr>
		<td  class="tdtitulos">Nombre 
	 </td>
<td  class="tdtitulos">Descripcion 
		 </td>
		<td  class="tdtitulos">Factura 
		 </td>
		
		<td  colspan=1 class="tdtitulos">
		Monto      y      Tipo Servicio		
		</td>
</tr>
	<?php

$ban="";
	for( $i=1; $i<24; $i++){

	
	?>
	
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="descri_<?php echo $i?>" name="descri" maxlength=128 size=20 value=""> </td>
	<td  class="tdtitulos">
		<input class="campos" type="text" id="factura_<?php echo $i?>" name="factura" maxlength=128 size=10 value=""> </td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" >
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value=""> 
		<select style="width: 100px;" id="tiposerv_<?php echo $i?>" name="tiposerv" class="campos" >
		<?php $q_tservicio=("select * from tipos_servicios  where tipos_servicios.id_servicio=1 order by tipo_servicio");
		$r_tservicio=ejecutar($q_tservicio);
		?>
				<option value=""> Seleccione el Tipo de Servicio</option>
				<?php		
		while($f_tservicio=asignar_a($r_tservicio,NULL,PGSQL_ASSOC)){
		?>
		<option value="<?php echo $f_tservicio[id_tipo_servicio]?>"> <?php echo "$f_tservicio[tipo_servicio]"?>			</option>
		<?php
		}
		?>
		</select>
		
		</td>
</tr>
<tr>
<td colspan=1 class="tdtitulos">Es tto Continuo<select style="width: 40px;" id="tc_<?php echo $i?>" name="tc" class="campos" >
		
				<option value="0"> No</option>
				<option value="on"> Si</option>
				
		</select></td>
<td colspan=1 class="tdtitulos">F I<input readonly type="text" size="10" id="fechaci_<?php echo $i?>" name="fechaci" class="campos" maxlength="10" value="1900-01-01"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechaci_<?php echo $i?>', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos">F F<input readonly type="text" size="10" id="fechacf_<?php echo $i?>" name="fechacf" class="campos" maxlength="10" value="1900-01-01"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'fechacf_<?php echo $i?>', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
<td colspan=1 class="tdtitulos"> D D <input class="campos" type="text" id="dd_<?php echo $i?>" name="dd" maxlength=128 size=4 value=""> T <input class="campos" type="text" id="t_<?php echo $i?>" name="t" maxlength=128 size=4 value="">  <a href="javascript: cal(this);" class="boton">      Cal</a></td>
</tr>
<tr>
<td colspan=4 class="tdtitulos"><hr></td>
</tr>
<tr>
<td colspan=4 class="tdtitulos"><hr></td>
</tr>
	<?php
	
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>

              	<td class="tdcampos"></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>



<?php
}
	if (($f_poliza[id_tipo_servicio]==9) || ($f_poliza[id_tipo_servicio]==13) || ($f_poliza[id_tipo_servicio]==20) || ($f_poliza[id_tipo_servicio]==25) || ($f_poliza[id_tipo_servicio]==21)) {
	?>

<tr>
	<td  class="tdtitulos">Nombre 
	</td>
	<td  class="tdtitulos">Descripcion 
	 </td>
	 </td>
	<td  class="tdtitulos">FM
	 </td>
	<td  colspan=1 class="tdtitulos">
	Monto 	
	</td>
		
</tr>

<?php
$i=0;
$ban="";

if ($f_poliza[id_tipo_servicio]==9)
{
$id_tipo_exam='5';
}
if ($f_poliza[id_tipo_servicio]==13)
{
$id_tipo_exam='6';
}
if ($f_poliza[id_tipo_servicio]==20)
{
$id_tipo_exam='9';
}
if ($f_poliza[id_tipo_servicio]==25)
{
$id_tipo_exam='7';
}
echo $iddelente;
	$q_baremoe="select 
										examenes_bl.id_examen_bl,
										examenes_bl.examen_bl,
										tbl_baremos_precios.precio
								from 
										examenes_bl,
										tbl_baremos_entes,
										tbl_baremos_precios,
										tbl_baremos
								where 
										examenes_bl.id_examen_bl=tbl_baremos_precios.id_examen_bl and
										tbl_baremos_precios.id_baremo=tbl_baremos.id_baremo and
										tbl_baremos.id_baremo=tbl_baremos_entes.id_baremo  and
										tbl_baremos_entes.id_ente=$iddelente and 
										examenes_bl.id_tipo_examen_bl=$id_tipo_exam
								order by 
										examenes_bl.examen_bl";
$r_baremoe=ejecutar($q_baremoe);
$num_filase=num_filas($r_baremoe);

if ($num_filase==0){

					$q_baremoe="select 
															* 
												from 
															examenes_bl 
												where
															examenes_bl.id_tipo_examen_bl=$id_tipo_exam 
												order by 
															examenes_bl.examen_bl";
					$r_baremoe=ejecutar($q_baremoe);
	}
                while($f_baremoe=asignar_a($r_baremoe,NULL,PGSQL_ASSOC)){
$i++;
?>
<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value="<?php echo $f_baremoe[examen_bl]?>"> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="descri_<?php echo $i?>" name="descri" class="campos" >
		<?php if ($f_poliza[id_tipo_servicio]==20){ ?>

			<option value="SALA DE OBSERVACION">SALA DE OBSERVACION</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==9 ){ ?>

			<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==13 ){
			
			?>
			<option value="HOSPITALIZACION">HOSPITALIZACION</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==25 ){
			
			?>
			<option value="v">PROCEDIMIENTOS ESPECIALES</option>
			<?php 
			}			
			$q_especialidad=("select * from especialidades_medicas order by especialidad_medica");
			$r_especialidad=ejecutar($q_especialidad);
	                while($f_especialidad=asignar_a($r_especialidad,NULL,PGSQL_ASSOC)){	
			?>
			
			<option value="<?php echo $f_especialidad[especialidad_medica]?>"> <?php echo $f_especialidad[especialidad_medica]?></option>
			
			<?php
			}
			?>	
		</select>

<?php	if ($num_filase==0) {
 ?>
			<input class="campos" type="text" id="valor_<?php echo $i?>"  
name="valor" maxlength=128 size=10 value="<?php echo $f_baremoe[honorarios]?>"   >
<?php
}
else
{
?>
     <input class="campos" type="text" id="valor_<?php echo $i?>"  
	 name="valor" maxlength=128 size=10 value="<?php echo $f_baremoe[precio]?>" >

<?php
}
?>
		 </td>
<td  class="tdtitulos">
		<select  id="factor_<?php echo $i?>"  name="factor" class="campos" >
		
				<option value="1"> 1</option>
				<option value="2"> 2</option>
				<option value="3"> 3</option>
				<option value="4"> 4</option>
				<option value="5"> 5</option>
				<option value="6"> 6</option>
				<option value="7"> 7</option>
				<option value="8"> 8</option>
				<option value="9"> 9</option>
				<option value="10"> 10</option>
				<option value="11"> 11</option>
				<option value="12"> 12</option>
				

		</select>
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);" name="checkl" maxlength=128 size=20 value=""> 
		
		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" >
	</td>
		
		
</tr>

                <?php
                }
		$p=$i + 1;

	for( $i=$p; $i<$p*2; $i++){

	?>
	<tr>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="nombre_<?php echo $i?>" name="nombre" maxlength=128 size=20 value=""> </td>
		<td  class="tdtitulos">
<select style="width: 100px;" id="descri_<?php echo $i?>" name="descri" class="campos" >
		
				<option value="0"> Seleccione el Tipo de Servicio</option>
				<option value="MEDICAMENTOS"> MEDICAMENTOS</option>
				<option value="INSUMOS"> INSUMOS</option>
		<?php if ($f_poliza[id_tipo_servicio]==20){ ?>

			<option value="SALA DE OBSERVACION">SALA DE OBSERVACION</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==9 ){ ?>

			<option value="GASTOS EMERGENCIAS">GASTOS EMERGENCIAS</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==13 ){
			
			?>
			<option value="HOSPITALIZACION">HOSPITALIZACION</option>
			<?php 
			}
			 if ($f_poliza[id_tipo_servicio]==25 ){
			
			?>
			<option value="PROCEDIMIENTOS ESPECIALES">PROCEDIMIENTOS ESPECIALES</option>
			<?php 
			}			
			?>
		</select>
<input class="campos" type="text" id="valor_<?php echo $i?>"  name="valor" maxlength=128 size=10 value="0"   >
		 </td>
<td  class="tdtitulos">
		<select  id="factor_<?php echo $i?>" name="factor" class="campos" >
		
					<option value="1"> 1</option>
				<option value="2"> 2</option>
				<option value="3"> 3</option>
				<option value="4"> 4</option>
				<option value="5"> 5</option>
				<option value="6"> 6</option>
				<option value="7"> 7</option>
				<option value="8"> 8</option>
				<option value="9"> 9</option>
				<option value="10"> 10</option>
				<option value="11"> 11</option>
				<option value="12"> 12</option>
				<option value="13"> 13</option>
				<option value="14"> 14</option>
				<option value="15"> 15</option>
				<option value="16"> 16</option>
				<option value="17"> 17</option>
				<option value="18"> 18</option>
				<option value="19"> 19</option>
				<option value="20"> 20</option>
				<option value="21"> 21</option>
				<option value="22"> 22</option>
				<option value="23"> 23</option>
				<option value="24"> 24</option>
				<option value="25"> 25</option>
				<option value="26"> 26</option>
				<option value="27"> 27</option>
				<option value="28"> 28</option>
				<option value="29"> 29</option>
				<option value="30"> 30</option>
				<option value="31"> 31</option>
				<option value="32"> 32</option>
				<option value="33"> 33</option>
				<option value="34"> 34</option>
				<option value="35"> 35</option>
				<option value="36"> 36</option>
				

		</select>
		<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>" OnChange="multiplicar(this);"  name="checkl" maxlength=128 size=20 value=""> 
		
		</td>
		<td  class="tdtitulos">
		<input class="campos" type="text" id="honorarios_<?php echo $i?>"  name="honorarios_<?php echo $i?>" maxlength=128 size=10 value="0"  OnChange="return validarNumero(this);" >
	</td>
		
		
</tr>
	<?php
	}
	echo "<input type=\"hidden\" name=\"conexa\" value=\"$i\">";
	?>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>

              	<td class="tdcampos"></td>
				<td colspan=1><a href="javascript: sumar(this);" class="boton">      Calcular Monto</a></td>
</tr>



	<?php	

	}
	?>

	<tr>				
				<td class="tdtitulos">* Monto</td>
              	<td class="tdcampos"><input class="campos" type="text" name="monto" maxlength=128 size=20 value="0"   ></td><input class="campos" type="hidden" name="comenope" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);" >
			<input class="campos" type="hidden" name="enfermedad" maxlength=128 size=20 value="<?php echo $f_poliza[enfermedad] ?>"   >	
			<input class="campos" type="hidden" name="decrip" maxlength=128 size=20 value="<?php echo $descri?>"><input class="campos" type="hidden" name="numpre" maxlength=128 size=20 value="0"   >
	

	</tr>	
	<?php
	}
	?>
	<?php if ($f_poliza[id_servicio]==14 and $f_poliza[id_tipo_servicio]==18) {?>
	<input class="campos" type="hidden" name="tp" maxlength=128 size=15 value="18"   >
	<?php }
	else
	{
		?>
		<input class="campos" type="hidden" name="tp" maxlength=128 size=15 value="0"   >
		<?php
		}
		
		if ($f_poliza[id_estado_proceso]<7){
	?>
<tr>
<td  colspan=4 class="tdcampos">
                <select id="cobertura_1" style="width: 400px;" name="cobertura" class="campos" Onchange="asigguar();">
				  <option value=""> Seleccione  la Cobertura </option>
                <?php
				$q_coberturac=("select 
														coberturas_t_b.id_cobertura_t_b,
														coberturas_t_b.monto_actual,
														propiedades_poliza.cualidad,
														coberturas_t_b.id_propiedad_poliza,
														coberturas_t_b.id_organo
											from 
														propiedades_poliza,
														coberturas_t_b 
											where
														coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 
														$cualidad
														coberturas_t_b.id_beneficiario='$f_poliza[id_beneficiario]' and
														coberturas_t_b.id_titular='$f_poliza[id_titular]' and 
														propiedades_poliza.id_poliza='$f_poliza[id_poliza]'  and 
														coberturas_t_b.id_organo<=1  
											order by 
														propiedades_poliza.cualidad");
$r_coberturac=ejecutar($q_coberturac) or mensaje(ERROR_BD);

                while($f_coberturac=asignar_a($r_coberturac,NULL,PGSQL_ASSOC)){
                ?>
				
                <option value="<?php echo $f_coberturac[id_cobertura_t_b]?>"> <?php echo "$f_coberturac[cualidad]  Cobertura Disponible $f_coberturac[monto_actual] " ?>
                 </option>
                <?php
                }
                ?>
                </select>
          </td>
		</tr>
		<tr>
 <td colspan=4 class="tdcampos">
                <select  name="organo" class="campos">
                <option value="0"> Sin Organo</option>
                <?php
				 $q_organot=("select * from organos order by organo");
				 $r_organot=ejecutar($q_organot); 
                while($f_organot=asignar_a($r_organot,NULL,PGSQL_ASSOC)){
                ?>
                <option value="<?php echo $f_organot[id_organo]?>"> <?php echo " $f_organot[organo]   " ?>
                </option>
                <?php
                }
                ?>
                </select>
                </td>
        </tr>
		<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="1" name="contador"></td>
        </tr>  



</table>
<div id="asigguar"></div>
<?php
}
}
}
?>
