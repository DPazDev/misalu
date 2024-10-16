<?php
//echo "<h1> PROCESO ". $_REQUEST['proceso']." </h1>";

header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
//header('Content-Type: text/xml; charset=ISO-8859-1');
$proceso=$_REQUEST['proceso'];
$admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$tipoadmin=$f_admin['id_tipo_admin'];

// **** verifico de tiene activado el permiso de poder modificar monto reserva ****
$q_mod_res=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=4");
$r_mod_res=ejecutar($q_mod_res);
$f_mod_res=asignar_a($r_mod_res);

if ($f_mod_res[permiso]=='1'){
	$enablemr="";
}
else
{
	$enablemr="disabled";
}

// **** verifico de tiene activado el permiso de poder modificar monto aceptado ****
$q_mod_ace=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=5");
$r_mod_ace=ejecutar($q_mod_ace);
$f_mod_ace=asignar_a($r_mod_ace);

if ($f_mod_ace[permiso]=='1'){
	$enablema="";
}
else
{
	$enablema="disabled";
}


// *** buscar si este proceso se ha facturado ***
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

//* **** seleeciono los tipo de estados de proceso ****
if($tipoadmin==19){ // 19 = coordinador de facturacion
 	$otracadena="or id_estado_proceso=11";
}else{
  	$otracadena="";
}
$q_estadop=("select
				*
			from
				estados_procesos
			where
				(id_estado_proceso=2 or id_estado_proceso=7 $otracadena)
			order by
				estado_proceso");
$r_estadop=ejecutar($q_estadop) or mensaje(ERROR_BD);
// ****verifico si el proceso no se encuentra en estado de espera o en estado anulado o en consulta preventiva ****
$q_poliza="select
                        procesos.fecha_emision_factura,
						procesos.fecha_creado as fechacreado,
                        procesos.control_factura,
                        procesos.nu_planilla,
                        procesos.comentarios,
                        procesos.comentarios_gerente,
                        procesos.comentarios_medico,
                        procesos.factura_final,
                        procesos.fecha_factura_final,
                        procesos.no_clave,
                        procesos.fecha_ent_pri,
                        procesos.pro_deducible,
                        servicios.servicio,
                        estados_procesos.id_estado_proceso,
                        estados_procesos.estado_proceso,
                        gastos_t_b.descripcion as descrip,
                        gastos_t_b.nombre,
                        gastos_t_b.id_tipo_servicio,
                        gastos_t_b.id_servicio,
                        gastos_t_b.id_proveedor,
                        gastos_t_b.fecha_cita,
                        gastos_t_b.hora_cita,
                        gastos_t_b.enfermedad,
                        gastos_t_b.unidades,
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
	// ****verifico si el proceso se encuentra en estado de espera o en estado anulado ****
$q_poliza="select
                            procesos.fecha_emision_factura,
							procesos.fecha_creado as fechacreado,
                            procesos.control_factura,
                            procesos.comentarios,
                            procesos.comentarios_gerente,
                            procesos.comentarios_medico,
                            procesos.factura_final,
                            procesos.fecha_factura_final,
                            procesos.no_clave,
                            procesos.pro_deducible,
                            estados_procesos.id_estado_proceso,
                            estados_procesos.estado_proceso,
                            procesos.*
                    from
                            estados_procesos,
                            procesos
                    where
                            procesos.id_proceso='$proceso' and
                            procesos.id_estado_proceso=estados_procesos.id_estado_proceso and
                            estados_procesos.id_estado_proceso>=13 and
                            estados_procesos.id_estado_proceso<=14 ";
$r_poliza=ejecutar($q_poliza);
$num_filas2=num_filas($r_poliza);
if ($num_filas2 == 0) {

// ****verifico si el proceso se encuentra  en consulta preventiva ****
$q_poliza="select
                            procesos.fecha_emision_factura,
							procesos.fecha_creado as fechacreado,
                            procesos.control_factura,
                            procesos.nu_planilla,
                            procesos.comentarios,
                            procesos.comentarios_gerente,
                            procesos.comentarios_medico,
                            procesos.factura_final,
                            procesos.fecha_factura_final,
                            procesos.no_clave,
                            servicios.servicio,
                            estados_procesos.id_estado_proceso,
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
                            procesos.comentarios,
                            procesos.pro_deducible
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
if ($num_filas3 == 0) {


?>
<table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "El Numero de Orden no Existe "?></td></tr>
<?php
}
else
{




$f_poliza=asignar_a($r_poliza);

if ($f_poliza[id_servicio]==9 || $f_poliza[id_servicio]==6 || $f_poliza[id_servicio]==14 || $f_admin[id_tipo_admin]==14 || $f_admin[id_tipo_admin]==6 || $f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11){
    $bloqueo="";
    }
    else
    { $bloqueo="disabled"; }

$q_poliza1="select
								gastos_t_b.id_insumo,
								gastos_t_b.id_dependencia,
								gastos_t_b.fecha_creado,
								gastos_t_b.hora_creado,
								gastos_t_b.id_cobertura_t_b,
								gastos_t_b.id_gasto_t_b,
								gastos_t_b.id_organo,
								gastos_t_b.retencion,
								gastos_t_b.descripcion as descrip,
								gastos_t_b.nombre,
								gastos_t_b.id_tipo_servicio,
								gastos_t_b.id_servicio,
								gastos_t_b.id_proveedor,
								gastos_t_b.fecha_cita,
								gastos_t_b.hora_cita,
								gastos_t_b.enfermedad,
								gastos_t_b.unidades,
								gastos_t_b.fecha_continuo,
								gastos_t_b.continuo,
								gastos_t_b.monto_reserva,
								gastos_t_b.monto_aceptado,
								gastos_t_b.factura,
								procesos.fecha_recibido,
								procesos.comentarios
					from
								gastos_t_b,
								procesos
					where
								gastos_t_b.id_proceso=procesos.id_proceso and
								procesos.id_proceso='$proceso'";
$r_poliza1=ejecutar($q_poliza1);

$q_proveedor="select
									*
						from
									proveedores,
									gastos_t_b
						where
									proveedores.id_proveedor=gastos_t_b.id_proveedor and
									gastos_t_b.id_proceso='$proceso'";
$r_proveedor=ejecutar($q_proveedor);

// **** busco si es titular **** *
$q_cliente=("select
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.id_tipo_ente,
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


// **** busco si es beneficiario ****

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
                <td class="tdcampos"><?php echo "$f_clienteb[nombres]  $f_clienteb[apellidos] Cedula: $f_clienteb[cedula]"?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<?php
}
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de  $f_poliza[servicio]  en Estado"?>

   <select style="width: 300px;" name="estado_proceso" class="campos">

                <option   value="<?php echo $f_poliza[id_estado_proceso]?>"> <?php echo $f_poliza[estado_proceso]?></option>
				 <?php
                while($f_estadop=asignar_a($r_estadop,NULL,PGSQL_ASSOC)){
if  ($f_poliza[id_estado_proceso]==11){

    }
    else
    {
                ?>
                <option  value="<?php echo $f_estadop[id_estado_proceso]?>"> <?php echo "$f_estadop[estado_proceso]"?></option>
                <?php
      }
      }
                ?>
                </select>



</td>
</tr>
<tr>
               <td  class="tdtitulos">Fecha de Creado:   </td>
		<td class="tdcampos"> <?php echo  $f_poliza[fechacreado]; ?>
                </td>

		<td class="tdtitulos"></td>
		<td>
		</td>
		</tr>
<tr>
               <td  class="tdtitulos">* Fecha de Recepcion:   </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="<?php echo  $f_poliza[fecha_recibido]; ?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>

		<td class="tdtitulos">Fecha de Cita o Egreso:</td>
		<td>
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_cita]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
		</tr>

   <tr>
		 		<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" maxlength=128 size=20 value="<?php echo $f_poliza[hora_cita]?>"   ></td>

				<td class="tdtitulos">Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value="<?php echo $f_poliza[enfermedad] ?>"   ></td>
	</tr>
	<tr> <td colspan=4 class="titulo_seccion">  Datos Obligatorios Para la Recepcion de Facturas </td></tr>
	   <tr>
		 		<td class="tdtitulos">Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="facturaf" maxlength=128 size=20 value="<?php echo $f_poliza[factura_final]?>"   ></td>

				<td class="tdtitulos">Fecha Recibido Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField3" name="fechaf" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_factura_final]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField3', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	 <tr>
		 		<td class="tdtitulos">Control Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="controlf" maxlength=128 size=20 value="<?php echo $f_poliza[control_factura]?>"   ></td>

				<td class="tdtitulos">Fecha Emision Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe" name="fechaef" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_emision_factura]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
		<tr> <td colspan=4 class="titulo_seccion"> <hr></hr> </td></tr>

	 <tr>
		 		<td class="tdtitulos">Clave</td>
              	<td class="tdcampos"><input class="campos" type="text" id="clave" name="clave" OnChange="verificar_clave();" maxlength=128 size=20 value="<?php echo $f_poliza[no_clave]?>"   ></td>

		<td class="tdtitulos">Fecha Relacion Ente Privado</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField4" name="fechap" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_ent_pri]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField4', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	<tr>
		 		<td class="tdtitulos">Num Planilla o Presupuesto</td>
              	<td class="tdcampos">
              	<input class="campos" type="text" id="numpre" <?php echo $bloqueo; ?> name="nu_planilla" OnChange="verificar_planilla();" maxlength=128 size=20 value="0"   /> </td>
			      <?php
        if ($f_poliza[id_tipo_servicio]==9 || $f_poliza[id_tipo_servicio]==13)
        {
        ?>

        <td class="tdtitulos">Deducible</td>
		<td class="tdtitulos"><input class="campos" type="text" id="deducible" <?php echo $bloqueo ?> name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   ></td>
		<?php
        }
        else
        {
        ?>
        <input class="campos" type="hidden" id="deducible" <?php echo $bloqueo ?> name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   >
        <?php
        }
        ?>
	</tr>

		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"><?php echo $f_poliza['comentarios']?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Gerente Operacion</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenger" cols=72 rows=2 class="campos"><?php echo $f_poliza['comentarios_gerente']?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Medico</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenmed" cols=72 rows=2 class="campos"><?php echo $f_poliza['comentarios_medico']?></textarea></td>
		</tr>




		<?php
		$num_filas=num_filas($r_proveedor);
		if ($num_filas == 0) {

	?>
	<input class="campos" type="hidden" name="id_proveedor" maxlength=128 size=20 value="0"   >
	<?php
		}
		else
		{
		$f_proveedor=asignar_a($r_proveedor);
		$q_proveedorc="select
											clinicas_proveedores.*,
											proveedores.id_proveedor
									from
											clinicas_proveedores,proveedores,
											gastos_t_b
									where
											clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
											proveedores.id_proveedor=gastos_t_b.id_proveedor and
											gastos_t_b.id_proceso='$proceso'";

$r_proveedorc=ejecutar($q_proveedorc);
$num_filasc=num_filas($r_proveedorc);
	if ($num_filasc == 0) {
$value=0;
$proveedor="Seleccione la Clinica";
}
else
{
$f_proveedorc=asignar_a($r_proveedorc);
$value="$f_proveedorc[id_proveedor]";
$proveedor="$f_proveedorc[nombre]";
}

		?>
		<tr>
<td colspan=2>
                <select style="width: 300px;" OnChange="verificarproc(this);" id="id_proveedorc" name="id_proveedorc" class="campos">
                <?php $q_pc=("select
													clinicas_proveedores.*,
													proveedores.id_proveedor
										from
													clinicas_proveedores,
													proveedores
										where
													clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
										order by
													clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="0"> Sin Proveedor</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
</td>

		<?php
				$q_proveedorp="select
														especialidades_medicas.especialidad_medica,
														proveedores.id_proveedor,
														personas_proveedores.*,
														s_p_proveedores.*
											from
														especialidades_medicas,
														personas_proveedores,
														s_p_proveedores,
														proveedores,
														gastos_t_b
										where
														proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
														s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
														especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad  and
														proveedores.id_proveedor=gastos_t_b.id_proveedor and
														gastos_t_b.id_proceso='$proceso'";
$r_proveedorp=ejecutar($q_proveedorp);
$num_filasp=num_filas($r_proveedorp);
	if ($num_filasp == 0) {
$value=0;
$proveedor="Seleccione  el Dr(a).";
}
else
{
$f_proveedorp=asignar_a($r_proveedorp);
$value="$f_proveedorp[id_proveedor]";
$proveedor="$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] ($f_proveedorp[especialidad_medica]) $f_proveedorp[direccion_prov]";
}
			?>


<td colspan=2 >
                <select style="width: 300px;" OnChange="verificarprop(this);" id="id_proveedorp" name="id_proveedorp" class="campos">
                <?php $q_p=("select
													especialidades_medicas.especialidad_medica,
													proveedores.id_proveedor,
													personas_proveedores.*,
													s_p_proveedores.*
										from
													especialidades_medicas,
													personas_proveedores,
													s_p_proveedores,
													proveedores
										where
													proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
													s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
													especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad
													order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                 <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="0"> Sin Proveedor</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[direccion_prov]"?></option>
                <?php
                }
                ?>
                </select>
				<input class="campos" type="hidden" id="id_proveedor" name="id_proveedor" maxlength=128 size=20 value="<?php echo $f_poliza[id_proveedor] ?>"   >
 </td>
			</tr>
			<?php

			}
			?>


	<tr>
				<td colspan=1 class="tdtitulos">Nombre del Gasto</td>
              	<td colspan=1 class="tdtitulos">Descripcion</td>
				<td colspan=1 class="tdtitulos">Monto Reserva - Monto Aceptado</td>
				<td colspan=1 class="tdtitulos">Factura</td>
	</tr>
		<?php
		$i=0;
		while($f_poliza1=asignar_a($r_poliza1,NULL,PGSQL_ASSOC)){
		$totalmontor=$totalmontor+ $f_poliza1[monto_reserva];
		$totalmontoa=$totalmontoa+ $f_poliza1[monto_aceptado];
		$descri=$f_poliza1[descrip];
		$i++;
		?>
	<tr>
				<td colspan=1 class="tdcampos">

								<input class="campos" type="hidden"  id="idgasto_<?php echo $i?>"  name="idgasto" maxlength=128 size=20 value="<?php echo $f_poliza1[id_gasto_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idorgano_<?php echo $i?>"  name="idorgano" maxlength=128 size=20 value="<?php echo $f_poliza1[id_organo] ?>"   >
				<input class="campos" type="hidden"  id="idinsumo_<?php echo $i?>"  name="idinsumo" maxlength=128 size=20 value="<?php echo $f_poliza1[id_insumo] ?>"   >
				<input class="campos" type="hidden"  id="iddependencia_<?php echo $i?>"  name="iddependencia" maxlength=128 size=20 value="<?php echo $f_poliza1[id_dependencia] ?>"   >
				<input class="campos" type="hidden"  id="fcreado_<?php echo $i?>"  name="fcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_creado] ?>"   >
				<input class="campos" type="hidden"  id="hcreado_<?php echo $i?>"  name="hcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[hora_creado] ?>"   >
				<input class="campos" type="hidden"  id="idcobertura_<?php echo $i?>"  name="idcobertura" maxlength=128 size=20 value="<?php echo $f_poliza1[id_cobertura_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idtipos_<?php echo $i?>"  name="idtipos" maxlength=128 size=20 value="<?php echo $f_poliza1[id_tipo_servicio] ?>"   >
				<input class="campos" type="hidden"  id="idservicio_<?php echo $i?>"  name="idservicio" maxlength=128 size=20 value="<?php echo $f_poliza1[id_servicio] ?>"   >
				<input class="campos" type="hidden"  id="retencion_<?php echo $i?>"  name="retencion" maxlength=128 size=20 value="<?php echo $f_poliza1[retencion] ?>"   >
					<input class="campos" type="hidden"  id="unidades_<?php echo $i?>"  name="unidades" maxlength=128 size=20 value="<?php echo $f_poliza1[unidades] ?>"   >
					<input class="campos" type="hidden"  id="fechaconi_<?php echo $i?>"  name="fechaconi" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_cita] ?>"   >
	<input class="campos" type="hidden"  id="fechacon_<?php echo $i?>"  name="fechacon" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_continuo] ?>"   >
				<input class="campos" type="hidden"  id="continuo_<?php echo $i?>"  name="continuo" maxlength=128 size=20 value="<?php echo $f_poliza1[continuo] ?>"   >



				<input class="campos" type="text"  id="nom_<?php echo $i?>"  name="nom" maxlength=128 size=20 value="<?php echo $f_poliza1[nombre] ?>"   >
				</td>
				<td colspan=1 class="tdcampos">
				<input class="campos" type="text"  id="desc_<?php echo $i?>" name="decrips" maxlength=250 size=20 value="<?php echo $f_poliza1[descrip]?>"   >
				</td>
				<td colspan=1 class="tdcampos">
				<input class="campos" type="text"  id="honorariosr_<?php echo $i?>" name="honorariosr" maxlength=128 size=7 <?php echo "$enablemr"; ?> value="<?php echo $f_poliza1[monto_reserva]?>"   OnChange="return validarNumero(this);">_<input class="campos" type="text"  id="honorarios_<?php echo $i?>" name="honorarios" maxlength=128 size=7 <?php echo "$enablema"; ?> value="<?php echo $f_poliza1[monto_aceptado]?>"   OnChange="return validarNumero(this);">
				</td>
				<td colspan=1 class="tdcampos">
				<input class="campos" type="text"  id="preforma_<?php echo $i?>" name="preforma" maxlength=128 size=7 value="<?php echo $f_poliza1[factura]?>"   >
				<input class="campos" type="checkbox" checked id="check_<?php echo $i?>"  name="checkl" maxlength=128 size=20 value="">
				</td>
		<?php
		}
		?>
	</tr>
			<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total</td>
				<td colspan=1 class="tdtitulos"><?php echo formato_montos($totalmontor);?> ________ <?php echo formato_montos($totalmontoa);?></td>
				<td colspan=1 class="tdtitulos"></td>
	</tr>

		<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i?>" name="conexa">
				<input type="hidden" value="<?php echo $f_poliza[id_servicio]?>" name="servicio">
				<input type="hidden" value="<?php echo $f_poliza[id_tipo_servicio]?>" name="tiposerv"></td>
        </tr>
		<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total Monto Nuevo</td>
				<td colspan=1 class="tdcampos"><input class="campos" type="text"   name="montor" maxlength=128 size=7 value="0"   >_<input class="campos" type="text"   name="monto" maxlength=128 size=7 value="0"   >
				</td>
				<td colspan=1><a href="javascript: sumar(this); javascript: sumar2(this); " class="boton">      Calcular Montos</a>
                <?php if ($f_poliza[id_estado_proceso]==2 || $f_poliza[id_estado_proceso]==4 || ($f_admin[id_tipo_admin]==14 and $f_cliente[id_tipo_ente]==4) || ($f_admin[id_tipo_admin]==7 and $f_cliente[id_tipo_ente]==4)){
				?>
<a href="#" OnClick="gua_act_ord();" class="boton">Actualizar</a></td>
	</tr>





</table>
<?php
}
?>

	<?php
	}
}
else
{




$f_poliza=asignar_a($r_poliza);
if ($f_poliza[id_servicio]==9 || $f_poliza[id_servicio]==6 || $f_poliza[id_servicio]==14 || $f_admin[id_tipo_admin]==14 || $f_admin[id_tipo_admin]==6 || $f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11){
    $bloqueo="";
    }
    else
    {
        $bloqueo="disabled";

        }

$q_poliza1="select
								gastos_t_b.id_gasto_t_b,
								gastos_t_b.id_organo,
								gastos_t_b.retencion,
								gastos_t_b.descripcion as descrip,
								gastos_t_b.nombre,
								gastos_t_b.id_tipo_servicio,
								gastos_t_b.id_servicio,
								gastos_t_b.id_proveedor,
								gastos_t_b.fecha_cita,
								gastos_t_b.hora_cita,
								gastos_t_b.enfermedad,
								gastos_t_b.unidades,
								gastos_t_b.fecha_continuo,
								gastos_t_b.continuo,
								gastos_t_b.monto_reserva,
								gastos_t_b.monto_aceptado,
								gastos_t_b.factura,
								procesos.fecha_recibido,
								procesos.comentarios
					from
								gastos_t_b,procesos
					where
								gastos_t_b.id_proceso=procesos.id_proceso and
								procesos.id_proceso='$proceso'";
$r_poliza1=ejecutar($q_poliza1);

$q_proveedor="select
									*
							from
									proveedores
							where
									proveedores.id_proveedor=gastos_t_b.id_proveedor and
									gastos_t_b.id_proceso='$proceso'";
$r_proveedor=ejecutar($q_proveedor);

// **** busco si es titular ****
$q_cliente=("select
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								entes.id_tipo_ente,
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


// **** busco si es beneficiario **** *

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
                <td class="tdcampos"><?php echo "$f_clienteb[nombres]  $f_clienteb[apellidos] Cedula: $f_clienteb[cedula]"?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<?php
}
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden  Estado $f_poliza[estado_proceso]"?>
<input class="campos" type="hidden" name="estado_proceso" maxlength=128 size=20 value="<?php echo $f_poliza[id_estado_proceso] ?>"   >
</td></tr>

<tr>
               <td  class="tdtitulos">Fecha de Creado:   </td>
		<td class="tdcampos"> <?php echo  $f_poliza[fechacreado]; ?>
                </td>

		<td class="tdtitulos"></td>
		<td>
		</td>
		</tr>
<tr>

<tr>
               <td  class="tdtitulos">* Fecha de Recepcion:   </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="<?php echo  $f_poliza[fecha_recibido]; ?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>

		<td class="tdtitulos">Fecha de Cita o Egreso:</td>
		<td>
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_cita]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
		</tr>

   <tr>
		 		<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" maxlength=128 size=20 value="<?php echo $f_poliza[hora_cita]?>"   ></td>

				<td class="tdtitulos">Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value="<?php echo $f_poliza[enfermedad] ?>"   ></td>
	</tr>
		<tr> <td colspan=4 class="titulo_seccion">  Datos Obligatorios Para la Recepcion de Facturas </td></tr>
	   <tr>
		 		<td class="tdtitulos">Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="facturaf" maxlength=128 size=20 value="<?php echo $f_poliza[factura_final]?>"   ></td>

				<td class="tdtitulos">Fecha Recibido Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField3" name="fechaf" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_factura_final]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField3', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	<tr>
		 		<td class="tdtitulos">Control Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="controlf" maxlength=128 size=20 value="<?php echo $f_poliza[control_factura]?>"   ></td>

				<td class="tdtitulos">Fecha Emision Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe" name="fechaef" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_emision_factura]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
		<tr> <td colspan=4 class="titulo_seccion">  <hr></hr> </td></tr>
	 <tr>
		 		<td class="tdtitulos">Clave</td>
              	<td class="tdcampos"><input class="campos" type="text" id="clave" name="clave" OnChange="verificar_clave();" maxlength=128 size=20 value="<?php echo $f_poliza[no_clave]?>"   ></td>

		<td class="tdtitulos">Fecha Relacion Ente Privado</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField4" name="fechap" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_ent_pri]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField4', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	<tr>
		 		<td class="tdtitulos">Num Planilla o Presupuesto</td>
              	<td class="tdcampos"><input class="campos" type="text" id="numpre" <?php echo $bloqueo ?> name="nu_planilla" OnChange="verificar_planilla();" maxlength=128 size=20 value="0"   ></td>
			   <?php
        if ($f_poliza[id_tipo_servicio]==9 || $f_poliza[id_tipo_servicio]==13)
        {
        ?>

              <td class="tdtitulos">Deducible</td>
		<td class="tdtitulos"><input class="campos" type="text" id="deducible" <?php echo $bloqueo ?> name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   ></td>
		<?php
        }
        else
        {
        ?>
        <input class="campos" type="hidden" id="deducible" <?php echo $bloqueo ?> name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   >
        <?php
        }
        ?>

	</tr>

		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios]?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Gerente Operacion</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenger" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios_gerente]?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Medico</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenmed" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios_medico]?></textarea></td>
		</tr>



		<?php
		$num_filas=num_filas($r_proveedor);
		if ($num_filas == 0) {

	?>
	<input class="campos" type="hidden" name="id_proveedor" maxlength=128 size=20 value="0"   >
	<?php
		}
		else
		{
		$f_proveedor=asignar_a($r_proveedor);
		$q_proveedorc="select
											clinicas_proveedores.*,
											proveedores.id_proveedor
									from
											clinicas_proveedores,
											proveedores,
											gastos_t_b
									where
											clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and
											proveedores.id_proveedor=gastos_t_b.id_proveedor and
											gastos_t_b.id_proceso='$proceso'";
$r_proveedorc=ejecutar($q_proveedorc);
$num_filasc=num_filas($r_proveedorc);
	if ($num_filasc == 0) {
$value=0;
$proveedor="Seleccione la Clinica";
}
else
{
$f_proveedorc=asignar_a($r_proveedorc);
$value="$f_proveedorc[id_proveedor]";
$proveedor="$f_proveedorc[nombre]";
}

		?>
		<tr>
<td colspan=2>
                <select style="width: 300px;"OnChange="verificarproc(this);"  id="id_proveedorc" name="id_proveedorc" class="campos">
                <?php $q_pc=("select
													clinicas_proveedores.*,
													proveedores.id_proveedor
											from
													clinicas_proveedores,
													proveedores
											where
													clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
													order by
													clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="*"> Sin Proveedor</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
</td>

		<?php
				$q_proveedorp="select
														especialidades_medicas.especialidad_medica,
														proveedores.id_proveedor,
														personas_proveedores.*,
														s_p_proveedores.*
											from
														especialidades_medicas,personas_proveedores,
														s_p_proveedores,
														proveedores,
														gastos_t_b
											where
														proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
														s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
														especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
														proveedores.id_proveedor=gastos_t_b.id_proveedor and s_p_proveedores.activar='1' and
														gastos_t_b.id_proceso='$proceso'";
$r_proveedorp=ejecutar($q_proveedorp);
$num_filasp=num_filas($r_proveedorp);
	if ($num_filasp == 0) {
$value=0;
$proveedor="Seleccione  el Dr(a).";
}
else
{
$f_proveedorp=asignar_a($r_proveedorp);
$value="$f_proveedorp[id_proveedor]";
$proveedor="$f_proveedorp[nombre_prov] $f_proveedorp[apellidos_prov] $f_proveedorp[direccion_prov]";
}
			?>


<td colspan=2 >

              hola  <select style="width: 300px;" OnChange="verificarprop(this);"  id="id_proveedorp" name="id_proveedor" class="campos">
                <?php $q_p=("select
													especialidades_medicas.especialidad_medica,
													proveedores.id_proveedor,
													personas_proveedores.*,
													s_p_proveedores.*
										from
													especialidades_medicas,personas_proveedores,
													s_p_proveedores,proveedores where
													proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
													s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
													especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and s_p_proveedores.activar='1'
										order by
													personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                 <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="*"> Sin Proveedor</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[direccion_prov]"?></option>
                <?php
                }
                ?>
                </select>
 </td>

<input class="campos" type="hidden" id="id_proveedor" name="id_proveedor" maxlength=128 size=20 value="<?php echo $f_poliza[id_proveedor] ?>"   >
			</tr>
			<?php

			}
			?>


	<tr>
				<td colspan=1 class="tdtitulos">Nombre del Gasto</td>
              	<td colspan=1 class="tdtitulos">Descripcion</td>
				<td colspan=1 class="tdtitulos">Monto Reserva - Monto Aceptado</td>
				<td colspan=1 class="tdtitulos">Factura</td>
	</tr>
		<?php
		$i=0;

		$i++;
		?>
	<tr>

		<input class="campos" type="hidden"  id="idgasto_<?php echo $i?>"  name="idgasto" maxlength=128 size=20 value="<?php echo $f_poliza1[id_gasto_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idorgano_<?php echo $i?>"  name="idorgano" maxlength=128 size=20 value="<?php echo $f_poliza1[id_organo] ?>"   >
				<input class="campos" type="hidden"  id="fcreado_<?php echo $i?>"  name="fcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_creado] ?>"   >
				<input class="campos" type="hidden"  id="hcreado_<?php echo $i?>"  name="hcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[hora_creado] ?>"   >
				<input class="campos" type="hidden"  id="idcobertura_<?php echo $i?>"  name="idcobertura" maxlength=128 size=20 value="<?php echo $f_poliza1[id_cobertura_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idtipos_<?php echo $i?>"  name="idtipos" maxlength=128 size=20 value="<?php echo $f_poliza1[id_tipo_servicio] ?>"   >
				<input class="campos" type="hidden"  id="idservicio_<?php echo $i?>"  name="idservicio" maxlength=128 size=20 value="<?php echo $f_poliza1[id_servicio] ?>"   >
				<input class="campos" type="hidden"  id="retencion_<?php echo $i?>"  name="retencion" maxlength=128 size=20 value="<?php echo $f_poliza1[retencion] ?>"   >
<input class="campos" type="hidden"  id="unidades_<?php echo $i?>"  name="unidades" maxlength=128 size=20 value="<?php echo $f_poliza1[unidades] ?>"   >
<input class="campos" type="hidden"  id="fechaconi_<?php echo $i?>"  name="fechaconi" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_cita] ?>"   >
		<input class="campos" type="hidden"  id="fechacon_<?php echo $i?>"  name="fechacon" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_continuo] ?>"   >
				<input class="campos" type="hidden"  id="continuo_<?php echo $i?>"  name="continuo" maxlength=128 size=20 value="<?php echo $f_poliza1[continuo] ?>"   >
				<td colspan=1 class="tdcampos"><input class="campos" type="hidden"  id="nom_<?php echo $i?>"  name="nom" maxlength=128 size=20 value="<?php echo $f_poliza1[nombre] ?>"   >
				</td>
				<td colspan=1 class="tdcampos"><input class="campos" type="hidden"  id="desc_<?php echo $i?>" name="decrips" maxlength=250 size=20 value="<?php echo $f_poliza1[descrip]?>"   >
				</td>
				<td colspan=1 class="tdcampos">
				<input class="campos" type="hidden"  id="honorariosr_<?php echo $i?>"  name="honorariosr" maxlength=128 size=7 <?php echo "$enablemr"; ?> value="<?php echo $f_poliza1[monto_reserva]?>"  OnChange="return validarNumero(this);" >
				_<input class="campos" type="hidden"  id="honorarios_<?php echo $i?>" name="honorarios" maxlength=128 size=7 <?php echo "$enablema"; ?> value="<?php echo $f_poliza1[monto_aceptado]?>"   OnChange="return validarNumero(this);"></td>
				<td colspan=1 class="tdcampos"><input class="campos" type="hidden"  id="preforma_<?php echo $i?>" name="preforma" maxlength=128 size=7 value="<?php echo $f_poliza1[factura]?>"   ><input class="campos" type="checkbox" style="visibility:hidden"  checked id="check_<?php echo $i?>"  name="checkl" maxlength=128 size=20 value=""></td>
		<?php

		?>
	</tr>
			<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total</td>
				<td colspan=1 class="tdtitulos"><?php echo formato_montos($totalmontor);?> ________ <?php echo formato_montos($totalmontoa);?></td>
				<td colspan=1 class="tdtitulos"></td>
	</tr>

		<tr>
                <td colspan=1 class="tdtitulos"><input type="hidden" value="<?php echo $i?>" name="conexa">
				<input type="hidden" value="<?php echo $f_poliza[id_servicio]?>" name="servicio">
				<input type="hidden" value="<?php echo $f_poliza[id_tipo_servicio]?>" name="tiposerv"></td>
        </tr>
		<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total Monto Nuevo</td>
				<td colspan=1 class="tdcampos"><input class="campos" type="hidden"   name="montor" maxlength=128 size=7 value="<?php echo $f_poliza[monto_temporal]?>"   >_<input class="campos" type="text"   name="monto" maxlength=128 size=7 value="<?php echo $f_poliza[monto_temporal]?>"   >
				</td>
				<td colspan=1></td>
	</tr>
		<tr>
	<td class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<td class="tdtitulos"></td>
</tr>
<tr>
	<td class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<?php if ($f_poliza[id_estado_proceso]==13)
		{
			?>
		<td  class="tdtitulos"><a href="#" OnClick="gua_act_ord();" class="boton">Actualizar</a></td>
		<?php
		}
		else
		{
			?>
			<td class="tdtitulos"></td>
		<?php
		}
		?>
		<td class="tdtitulos"></td>
</tr>


</table>

<?
}
}
else
{
$f_poliza=asignar_a($r_poliza);
if ($f_poliza[id_servicio]==9 || $f_poliza[id_servicio]==6 || $f_poliza[id_servicio]==14 || $f_admin[id_tipo_admin]==14 || $f_admin[id_tipo_admin]==6 || $f_admin[id_tipo_admin]==7 || $f_admin[id_tipo_admin]==11){
    $bloqueo="";
    }
    else
    {
        $bloqueo="disabled";

        }
$q_cobertura_t_b="select
										coberturas_t_b.monto_actual,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_cobertura_t_b,
										count(gastos_t_b.id_cobertura_t_b)
								from
										gastos_t_b,coberturas_t_b,
										propiedades_poliza,
										procesos
								where
										propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
										gastos_t_b.id_proceso=procesos.id_proceso and
										procesos.id_proceso='$proceso' and
										gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b
								group by
										coberturas_t_b.monto_actual,
										propiedades_poliza.cualidad,
										coberturas_t_b.id_cobertura_t_b";
$r_cobertura_t_b=ejecutar($q_cobertura_t_b);

$q_proveedor="select
									*
							from
									proveedores,
									gastos_t_b
							where
									proveedores.id_proveedor=gastos_t_b.id_proveedor and
									gastos_t_b.id_proceso='$proceso'";
$r_proveedor=ejecutar($q_proveedor);

// **** busco si es titular ****
$q_cliente=("select
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								estados_clientes.estado_cliente,
								titulares.id_titular,
								titulares.id_ente,
								entes.nombre,
								tbl_tipos_entes.id_tipo_ente,
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


//**** busco si es beneficiario ****

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
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario
								and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
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
                <td class="tdcampos"><?php echo "$f_clienteb[nombres] $f_clienteb[apellidos] Cedula: $f_clienteb[cedula]"?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clienteb[estado_cliente]?></td>
        </tr>

<?php
}
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de  $f_poliza[servicio]  en Estado"?>

   <select style="width: 300px;" name="estado_proceso" class="campos">

                <option   value="<?php echo $f_poliza[id_estado_proceso]?>"> <?php echo $f_poliza[estado_proceso]?></option>
				 <?php
                while($f_estadop=asignar_a($r_estadop,NULL,PGSQL_ASSOC)){
                    if  ($f_poliza[id_estado_proceso]==11){

    }
    else
    {

                ?>
                <option  value="<?php echo $f_estadop[id_estado_proceso]?>"> <?php echo "$f_estadop[estado_proceso]"?></option>
                <?php
                }
                }
                ?>
                </select>



</td></tr>
<tr>
               <td  class="tdtitulos">Fecha de Creado:   </td>
		<td class="tdcampos"> <?php echo  $f_poliza[fechacreado]; ?>
                </td>

		<td class="tdtitulos"></td>
		<td>
		</td>
		</tr>
<tr>

<tr>
               <td  class="tdtitulos">* Fecha de Recepcion:   </td>
		<td>
 <input readonly type="text" size="10" id="dateField1" name="fechar" class="campos" maxlength="10" value="<?php echo  $f_poliza[fecha_recibido]; ?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField1', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>

		<td class="tdtitulos">Fecha de Cita o Egreso:</td>
		<td>
 <input readonly type="text" size="10" id="dateField2" name="fechac" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_cita]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField2', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
		</td>
		</tr>

   <tr>
		 		<td class="tdtitulos">Hora Cita</td>
              	<td class="tdcampos"><input class="campos" type="text" name="horac" maxlength=128 size=20 value="<?php echo $f_poliza[hora_cita]?>"   ></td>

				<td class="tdtitulos">Cuadro Medico</td>
              	<td class="tdcampos"><input class="campos" type="text" name="enfermedad" maxlength=128 size=20 value="<?php echo $f_poliza[enfermedad] ?>"   ></td>
	</tr>
		<tr> <td colspan=4 class="titulo_seccion">  Datos Obligatorios Para la Recepcion de Facturas de Proveedores</td></tr>
	   <tr>
		 		<td class="tdtitulos">Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="facturaf" maxlength=128 size=20 value="<?php echo $f_poliza[factura_final]?>"   ></td>

				<td class="tdtitulos">Fecha Recibido Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField3" name="fechaf" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_factura_final]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField3', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	<tr>
		 		<td class="tdtitulos">Control Factura Final</td>
              	<td class="tdcampos"><input class="campos" type="text" name="controlf" maxlength=128 size=20 value="<?php echo $f_poliza[control_factura]?>"   ></td>

				<td class="tdtitulos">Fecha Emision Factura Final</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateFieldfe" name="fechaef" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_emision_factura]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateFieldfe', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>

				</td>
	</tr>
		<tr> <td colspan=4 class="titulo_seccion">  <hr></hr></td></tr>
	 <tr>
		 		<td class="tdtitulos">Clave</td>
              	<td class="tdcampos"><input class="campos" type="text" id="clave" name="clave" OnChange="verificar_clave();" maxlength=128 size=20 value="<?php echo $f_poliza[no_clave]?>"   ></td>

		<td class="tdtitulos">Fecha Relacion Ente Privado</td>
              	<td class="tdcampos"><input readonly type="text" size="10" id="dateField4" name="fechap" class="campos" maxlength="10" value="<?php echo $f_poliza[fecha_ent_pri]?>">
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField4', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a></td>
	</tr>
	<tr>
		 		<td class="tdtitulos">Num Planilla Presupuesto </td>
              	<td class="tdcampos"><input class="campos" type="text"  <?php echo $bloqueo ?> id="numpre" name="nu_planilla" OnChange="verificar_planilla();" maxlength=128 size=20 value="<?php echo $f_poliza[nu_planilla]?>"   ></td>
		<?php
        if ($f_poliza[id_tipo_servicio]==9 || $f_poliza[id_tipo_servicio]==13)
        {
        ?>

              <td class="tdtitulos">Deducible</td>
		<td class="tdtitulos"><input class="campos" type="text" id="deducible" <?php echo $bloqueo ?> name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   ></td>
		<?php
        }
        else
        {
        ?>
       <td class="tdtitulos"><input class="campos" type="hidden" id="deducible"  name="deducible" onchange="return ValNumero(this);" maxlength=10 size=15 value="<?php echo $f_poliza[pro_deducible]?>"   ></td>
        <?php
        }
        ?>

	</tr>

		<tr>
			<td colspan=1 class="tdtitulos">Comentario</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenope" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios]?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Gerente Operacion</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenger" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios_gerente]?></textarea></td>
		</tr>
			<tr>
			<td colspan=1 class="tdtitulos">Comentario Medico</td>
              	<td colspan=3 class="tdcampos"><textarea name="comenmed" cols=72 rows=2 class="campos"><?php echo $f_poliza[comentarios_medico]?></textarea></td>
		</tr>



		<?php
		$num_filas=num_filas($r_proveedor);
		if ($num_filas == 0) {

	?>
	<input class="campos" type="hidden" name="id_proveedor" maxlength=128 size=20 value="0"   >
	<?php
		}
		else
		{
		$f_proveedor=asignar_a($r_proveedor);
		$q_proveedorc="select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores,gastos_t_b where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
               and proveedores.id_proveedor=gastos_t_b.id_proveedor and gastos_t_b.id_proceso='$proceso'";
$r_proveedorc=ejecutar($q_proveedorc);
$num_filasc=num_filas($r_proveedorc);
	if ($num_filasc == 0) {
$value=0;
$proveedor="Seleccione la Clinica";
}
else
{
$f_proveedorc=asignar_a($r_proveedorc);
$value="$f_proveedorc[id_proveedor]";
$proveedor="$f_proveedorc[nombre]";
}

		?>
		<tr>
<td colspan=2>
                <select style="width: 300px;" OnChange="verificarproc(this);" id="id_proveedorc" name="id_proveedorc" class="campos">
                <?php $q_pc=("select clinicas_proveedores.*,proveedores.id_proveedor from clinicas_proveedores,proveedores where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor
                order by clinicas_proveedores.nombre");
                $r_pc=ejecutar($q_pc);
                ?>
                <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="0"> Sin Proveedor</option>
                <?php
                while($f_pc=asignar_a($r_pc,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_pc[id_proveedor]?>"> <?php echo "$f_pc[nombre] $f_pc[direccion]"?></option>
                <?php
                }
                ?>
                </select>
</td>

		<?php
				$q_proveedorp="select
														especialidades_medicas.especialidad_medica,
														proveedores.id_proveedor,
														personas_proveedores.*,
														s_p_proveedores.*
											from
														especialidades_medicas,
														personas_proveedores,
														s_p_proveedores,
														proveedores,
														gastos_t_b
											where
														proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
														s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
														especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and
														proveedores.id_proveedor=gastos_t_b.id_proveedor
														and s_p_proveedores.activar='1' and gastos_t_b.id_proceso='$proceso'";
$r_proveedorp=ejecutar($q_proveedorp);
$num_filasp=num_filas($r_proveedorp);
	if ($num_filasp == 0) {
$value=0;
$proveedor="Seleccione  el Dr(a).";
}
else
{
$f_proveedorp=asignar_a($r_proveedorp);
$value="$f_proveedorp[id_proveedor]";
$proveedor="$f_proveedorp[nombres_prov] $f_proveedorp[apellidos_prov] ($f_proveedorp[especialidad_medica]) $f_proveedorp[direccion_prov]";
}
			?>


<td colspan=2 >
                <select style="width: 300px;" OnChange="verificarprop(this);" id="id_proveedorp" name="id_proveedorp" class="campos">
                <?php $q_p=("select
													especialidades_medicas.especialidad_medica,
													proveedores.id_proveedor,
													personas_proveedores.*,
													s_p_proveedores.*
											from
													especialidades_medicas,personas_proveedores,
													s_p_proveedores,proveedores
											where
													proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and
													s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor  and
													especialidades_medicas.id_especialidad_medica=s_p_proveedores.id_especialidad and s_p_proveedores.activar='1'
													order by personas_proveedores.nombres_prov");
                $r_p=ejecutar($q_p);
                ?>
                 <option   value="<?php echo $value?>"> <?php echo $proveedor?></option>
				<option   value="0"> Sin Proveedor</option>
                <?php
                while($f_p=asignar_a($r_p,NULL,PGSQL_ASSOC)){

                ?>
                <option  value="<?php echo $f_p[id_proveedor]?>"> <?php echo "$f_p[nombres_prov] $f_p[apellidos_prov] $f_p[direccion_prov]"?></option>
                <?php
                }
                ?>
                </select>
				<input class="campos" type="hidden" id="id_proveedor" name="id_proveedor" maxlength=128 size=20 value="<?php echo $f_poliza[id_proveedor] ?>"   >
 </td>
			</tr>
			<?php

			}
			?>


	<tr>
				<td colspan=1 class="tdtitulos">Nombre del Gasto</td>
              	<td colspan=1 class="tdtitulos">Descripcion</td>
				<td colspan=1 class="tdtitulos">Monto Reserva - Monto Aceptado</td>
				<td colspan=1 class="tdtitulos">Factura</td>
	</tr>
		<?php
		$i=0;
		$j=0;
		while($f_cobertura_t_b=asignar_a($r_cobertura_t_b,NULL,PGSQL_ASSOC)){
		$j++;
		?>
		<input type="hidden"  size=5 id="i_inicial_<?php echo $j?>"  name="i_inicial_<?php echo $j?>" value="<?php echo $i + 1?>">
		<?php

		$totalpoliza=0;
		$q_poliza1="select
										gastos_t_b.id_insumo,
										gastos_t_b.id_dependencia,
										gastos_t_b.fecha_creado,
										gastos_t_b.hora_creado,
										gastos_t_b.id_cobertura_t_b,
										gastos_t_b.id_gasto_t_b,
										gastos_t_b.id_organo,
										gastos_t_b.retencion,
										gastos_t_b.descripcion as descrip,
										gastos_t_b.nombre,
										gastos_t_b.id_tipo_servicio,
										gastos_t_b.id_servicio,
										gastos_t_b.id_proveedor,
										gastos_t_b.fecha_cita,
										gastos_t_b.hora_cita,
										gastos_t_b.enfermedad,
										gastos_t_b.unidades,
										gastos_t_b.fecha_continuo,
										gastos_t_b.continuo,
										gastos_t_b.monto_reserva,
										gastos_t_b.monto_aceptado,
										gastos_t_b.factura,
										procesos.fecha_recibido,
										procesos.comentarios
								from
										gastos_t_b,
										procesos
								where
										gastos_t_b.id_proceso=procesos.id_proceso and
										procesos.id_proceso='$proceso' and
										gastos_t_b.id_cobertura_t_b=$f_cobertura_t_b[id_cobertura_t_b]";
$r_poliza1=ejecutar($q_poliza1);

		while($f_poliza1=asignar_a($r_poliza1,NULL,PGSQL_ASSOC)){
		$totalmontor=$totalmontor+ $f_poliza1[monto_reserva];
		$totalmontoa=$totalmontoa+ $f_poliza1[monto_aceptado];
		$totalpoliza=$totalpoliza + $f_poliza1[monto_aceptado];
		$descri=$f_poliza1[descrip];
		$i++;
		?>
	<tr>
				<td colspan=1 class="tdcampos">

				<input class="campos" type="hidden"  id="idgasto_<?php echo $i?>"  name="idgasto" maxlength=128 size=20 value="<?php echo $f_poliza1[id_gasto_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idorgano_<?php echo $i?>"  name="idorgano" maxlength=128 size=20 value="<?php echo $f_poliza1[id_organo] ?>"   >
				<input class="campos" type="hidden"  id="idinsumo_<?php echo $i?>"  name="idinsumo" maxlength=128 size=20 value="<?php echo $f_poliza1[id_insumo] ?>"   >
				<input class="campos" type="hidden"  id="iddependencia_<?php echo $i?>"  name="iddependencia" maxlength=128 size=20 value="<?php echo $f_poliza1[id_dependencia] ?>"   >
				<input class="campos" type="hidden"  id="fcreado_<?php echo $i?>"  name="fcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_creado] ?>"   >
				<input class="campos" type="hidden"  id="hcreado_<?php echo $i?>"  name="hcreado" maxlength=128 size=20 value="<?php echo $f_poliza1[hora_creado] ?>"   >
				<input class="campos" type="hidden"  id="idcobertura_<?php echo $i?>"  name="idcobertura" maxlength=128 size=20 value="<?php echo $f_poliza1[id_cobertura_t_b] ?>"   >
				<input class="campos" type="hidden"  id="idtipos_<?php echo $i?>"  name="idtipos" maxlength=128 size=20 value="<?php echo $f_poliza1[id_tipo_servicio] ?>"   >
				<input class="campos" type="hidden"  id="idservicio_<?php echo $i?>"  name="idservicio" maxlength=128 size=20 value="<?php echo $f_poliza1[id_servicio] ?>"   >
				<input class="campos" type="hidden"  id="retencion_<?php echo $i?>"  name="retencion" maxlength=128 size=20 value="<?php echo $f_poliza1[retencion] ?>"   >
				<input class="campos" type="hidden"  id="unidades_<?php echo $i?>"  name="unidades" maxlength=128 size=20 value="<?php echo $f_poliza1[unidades] ?>"   >
				<input class="campos" type="hidden"  id="fechaconi_<?php echo $i?>"  name="fechaconi" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_cita] ?>"   >
				<input class="campos" type="hidden"  id="fechacon_<?php echo $i?>"  name="fechacon" maxlength=128 size=20 value="<?php echo $f_poliza1[fecha_continuo] ?>"   >
				<input class="campos" type="hidden"  id="continuo_<?php echo $i?>"  name="continuo" maxlength=128 size=20 value="<?php echo $f_poliza1[continuo] ?>"   >

				<input class="campos" type="text"  id="nom_<?php echo $i?>"  name="nom" maxlength=128 size=20 value="<?php echo $f_poliza1[nombre] ?>"   ></td>
				<td colspan=1 class="tdcampos"><input class="campos" type="text"  id="desc_<?php echo $i?>" name="decrips" maxlength=250 size=20 value="<?php echo $f_poliza1[descrip]?>"   ></td>
				<td colspan=1 class="tdcampos">
				<input class="campos" type="text" <?php echo "$enablemr"; ?> id="honorariosr_<?php echo $i?>"  name="honorariosr" maxlength=128 size=7 value="<?php echo $f_poliza1[monto_reserva]?>"  OnChange="return validarNumero(this);" >_
				<input class="campos" type="text" <?php echo "$enablema"; ?>  id="honorarios_<?php echo $i?>" name="honorarios" maxlength=128 size=7 value="<?php echo $f_poliza1[monto_aceptado]?>"  OnChange="return validarNumero(this);" >
				</td>
				<td colspan=1 class="tdcampos"><input class="campos" type="text"  id="preforma_<?php echo $i?>" name="preforma" maxlength=128 size=7 value="<?php echo $f_poliza1[factura]?>"   ><input class="campos" type="checkbox" checked id="check_<?php echo $i?>"  name="checkl" maxlength=128 size=20 value=""></td>
				</tr>
		<?php
		}

		?>

			<tr>
				<td colspan=2 class="tdcamposgc">Sub Total Gastos Descargado en <?php echo $f_cobertura_t_b[cualidad]?></td>
				<td colspan=1 class="tdcamposgc">
				<input class="campos" type="text"  disabled id="cob_<?php echo $j?>"  name="cob_<?php echo $j?>" maxlength=128 size=7 value="<?php echo $totalpoliza ?>"   ></td>
				<td colspan=1 class="tdcampos"></td>
				</tr>
				<tr>
				<td colspan=2 class="tdcamposr2">Cobertura Disponible <?php echo $f_cobertura_t_b[cualidad]?></td>
				<?php if ($f_admin[id_tipo_admin]==11){
				?>
				<td colspan=1 class="tdcamposr2">
				<input class="camposr" type="text"  id="cobertura_<?php echo $j?>"
				name="cobertura_<?php echo $j?>" maxlength=128 size=7
				value="<?php echo $f_cobertura_t_b[monto_actual] ?>"    >
				</td>

				<?php }
				else
				{
				?>

				<td colspan=1 class="tdcamposr2">
                                <input class="camposr" type="text" disabled id="cobertura_<?php echo $j?>"
                                name="cobertura_<?php echo $j?>" maxlength=128 size=7
                                value="<?php echo $f_cobertura_t_b[monto_actual] ?>"    >
                                </td>

				<?php
				}
				?>
				<td colspan=1 class="tdcampos">

				<input type="hidden"  size=5 id="i_final_<?php echo $j?>"  name="i_final_<?php echo $j?>" value="<?php echo $i?>">
				</td>
				</tr>

						<tr>
				<td colspan=4 class="tdcampos"><hr></hr></td>
				</tr>

		<?php
		}
		?>

			<tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total</td>
				<td colspan=1 class="tdtitulos"><?php echo formato_montos($totalmontor);?> ________ <?php echo formato_montos($totalmontoa);?></td>
				<td colspan=1 class="tdtitulos"></td>
	</tr>

		<tr>
                <td colspan=1 class="tdtitulos">
				<input type="hidden" value="<?php echo $j?>" name="conj">
				<input type="hidden" value="<?php echo $i?>" name="conexa">
				<input type="hidden" value="<?php echo $f_poliza[id_servicio]?>" name="servicio">
				<input type="hidden" value="<?php echo $f_poliza[id_tipo_servicio]?>" name="tiposerv"></td>
        </tr>


        </tr>
	<?php
	if($f_admin['id_tipo_admin']!='16')
	if (($f_admin['id_tipo_admin']!='19' ) and ($num_filasf>0) )
	{
	?>
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
	?>



        <tr>
				<td colspan=1 class="tdtitulos"></td>
              	<td colspan=1 class="tdtitulos">Total Monto Nuevo</td>
				<td colspan=1 class="tdcampos"><input class="campos" type="text"   name="montor" maxlength=128 size=7 value="0"   >_<input class="campos" type="text"   name="monto" maxlength=128 size=7 value="0"  OnChange="return validarNumero(this);" >
				</td>
				<td colspan=1><a href="javascript: sumar(this); javascript: sumar2(this); javascript: calcular_cobertura(this);" class="boton">      Calcular Montos</a>

				<?php
    //modificaion Franklin monsalve 07-12-2018
                if ($f_poliza['id_estado_proceso']==2 || $f_poliza['id_estado_proceso']==4 || ($f_admin['id_tipo_admin']==14 and $f_cliente['id_tipo_ente']==4) || $f_admin['id_tipo_admin']==19 || ($f_admin['id_tipo_admin']==7 and $f_cliente['id_tipo_ente']==4)){
				?>
				<a href="#" OnClick="gua_act_ord();" id="actualizar" style="visibility:hidden" class="boton">Actualizar</a>
				<?php }
				?>
				</td>
	</tr>
    <?php
    }
    ?>
		<tr>
	<td class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<td class="tdtitulos"></td>
</tr>
<tr>
	<td class="tdtitulos"></td>
		<td  class="tdtitulos"></td>
		<td  class="tdtitulos"> </td>
		<td class="tdtitulos"></td>
</tr>


</table>

<?php
}
?>
