<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
//header('Content-Type: text/xml; charset=ISO-8859-1');
$proceso=$_REQUEST['proceso'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
/**** buscar si este proceso se ha facturado ****/
$q_factura=("select * from tbl_facturas,tbl_procesos_claves,tbl_series where tbl_facturas.id_factura=tbl_procesos_claves.id_factura and tbl_procesos_claves.id_proceso=$proceso and tbl_facturas.id_serie=tbl_series.id_serie and tbl_facturas.id_estado_factura<>3");
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);
/* **** fin de buscar si este proceso se ha facturado **** */
$q_espera="select * from procesos where procesos.id_proceso='$proceso'";
$r_espera=ejecutar($q_espera);
$f_espera=asignar_a($r_espera);
if ($f_espera[id_estado_proceso]==13)  {

$id_tipo_servicio=0;
$espera="ESPERA";
}
else
{
	if ($f_espera[id_estado_proceso]==14)  {

$id_tipo_servicio=0;
$espera="ANULADA";
}
else
{
$q_tiposervicio="select * from gastos_t_b where gastos_t_b.id_proceso='$proceso'";
$r_tiposervicio=ejecutar($q_tiposervicio);
$f_tiposervicio=asignar_a($r_tiposervicio);

if ($f_tiposervicio[id_tipo_servicio]==5)  {

$id_tipo_servicio=5;
}
else
{
$id_tipo_servicio=$f_tiposervicio[id_tipo_servicio];
}
}
}
$q_poliza="select 	
							procesos.no_clave,
							procesos.nu_planilla,
							procesos.donativo,
							procesos.id_servicio_aux,
							procesos.id_estado_proceso,
							procesos.id_proceso,
							procesos.nu_planilla,
							procesos.fecha_creado as fechacreado,
							estados_procesos.estado_proceso,
							gastos_t_b.id_tipo_servicio,
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
							procesos.comentarios 
					from 
							gastos_t_b,
							procesos,
							estados_procesos 
					where  
							gastos_t_b.id_proceso=procesos.id_proceso and
							procesos.id_proceso='$proceso' and 
							procesos.id_estado_proceso=estados_procesos.id_estado_proceso";
$r_poliza=ejecutar($q_poliza);
$f_poliza=asignar_a($r_poliza);

/* *** Busco el numero de cheque o recibo y el banco si el proceso esta en pago emitido*** */
if ($f_poliza[id_estado_proceso]==11){
		$q_factura_proceso="select 
												* 
										from 
												facturas_procesos 
										where 
												facturas_procesos.id_proceso=$proceso and 
												facturas_procesos.id_banco<>9";
		$r_factura_proceso=ejecutar($q_factura_proceso);
		$f_factura_proceso=asignar_a($r_factura_proceso);
		$q_banco="select 
									*
							from 
									bancos 
							where
									id_banco=$f_factura_proceso[id_banco]";
		$r_banco=ejecutar($q_banco);
		$f_banco=asignar_a($r_banco);
$descripcioncheque ="con numero de cheque $f_factura_proceso[numero_cheque], recibo $f_factura_proceso[num_recibo] y del banco $f_banco[nombre_banco]";
	}
/* *** fin de buscar el numero de cheque o recibo y el banco si el proceso esta en pago emitido*** */
$q_poliza1="select 
                            gastos_t_b.descripcion as descrip,
                            gastos_t_b.id_insumo,
                            gastos_t_b.nombre,
                            gastos_t_b.id_tipo_servicio,
                            gastos_t_b.id_servicio,
                            gastos_t_b.id_proveedor,
                            gastos_t_b.fecha_cita,
                            gastos_t_b.hora_cita,
                            gastos_t_b.enfermedad,
                            gastos_t_b.monto_aceptado,
                            gastos_t_b.unidades,
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
							clientes.fecha_nacimiento,
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
							procesos.id_proceso='$proceso' and 
							procesos.id_titular=titulares.id_titular and 
							procesos.id_beneficiario=0 and 
							clientes.id_cliente=titulares.id_cliente and 
							titulares.id_titular=estados_t_b.id_titular and 
							estados_t_b.id_beneficiario=0 and 
							estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
							titulares.id_ente=entes.id_ente and
							entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
							estados_clientes.id_estado_cliente>=4");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$titular=1;

/* **** busco si es beneficiario **** */
if ($num_filas == 0) { 
$q_clienteb=("select 
								clientes.id_cliente,
								clientes.nombres,
								clientes.apellidos,
								clientes.cedula,
								clientes.fecha_nacimiento,
								estados_clientes.estado_cliente,
								beneficiarios.id_beneficiario,
								beneficiarios.id_titular,
								entes.nombre,
								parentesco.parentesco
					from 
								clientes,
								estados_clientes,
								beneficiarios,
								entes,
								procesos,
								estados_t_b,
								titulares,
								parentesco
					where 
								procesos.id_proceso='$proceso' and 
								procesos.id_beneficiario=beneficiarios.id_beneficiario and
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and 
								estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
								beneficiarios.id_titular=titulares.id_titular 
								and titulares.id_ente=entes.id_ente and 
								estados_clientes.id_estado_cliente>=4 and
								parentesco.id_parentesco=beneficiarios.id_parentesco");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$titular=0;
}

if ($num_filas==0 and $num_filasb==0){

?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<tr> <td colspan=4 class="titulo_seccion">El Numero de Orden no existe </td>
      </tr>

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><a href="#" OnClick="ir_principal();" class="boton">salir</a></td>
		<td class="tdtitulos"></td>
	</tr>
</table>
<?php
}
else
{
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>

<?php if ($titular==1) {

/* ***** repita para buscar al titular **** */
$i=0;
while($f_cliente=asignar_a($r_cliente,NULL,PGSQL_ASSOC)){
$i++;
$q_datos=("select 
							estados_procesos.estado_proceso,
							procesos.comentarios,
							procesos.fecha_recibido,
							procesos.comentarios_gerente,
							procesos.comentarios_medico,
							admin.nombres,
							admin.apellidos,
							gastos_t_b.fecha_cita,
							gastos_t_b.nombre,
							gastos_t_b.descripcion,
							gastos_t_b.enfermedad,
							gastos_t_b.id_servicio,
							coberturas_t_b.id_cobertura_t_b,
							propiedades_poliza.cualidad,
							coberturas_t_b.id_propiedad_poliza,
							coberturas_t_b.monto_actual,
							coberturas_t_b.id_organo,
							servicios.servicio,
							gastos_t_b.id_tipo_servicio
					from 
							propiedades_poliza,
							coberturas_t_b,
							gastos_t_b,
							estados_procesos,
							procesos,
							admin,
							servicios 
					where
							gastos_t_b.id_proceso=procesos.id_proceso and 
							gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and 		
							coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 	
							gastos_t_b.id_proceso=procesos.id_proceso and 
							procesos.id_proceso='$proceso' and 
							procesos.id_admin=admin.id_admin and 
							gastos_t_b.id_servicio=servicios.id_servicio and 
							procesos.id_estado_proceso=estados_procesos.id_estado_proceso ");
	$r_datos=ejecutar($q_datos) or mensaje(ERROR_BD);
	$f_datos=asignar_a($r_datos);
	$cliente="$f_cliente[nombres] $f_cliente[apellidos]";
    $ente="$f_cliente[nombre]";

	$q_tiposer=("select  * from tipos_servicios where tipos_servicios.id_tipo_servicio=$id_tipo_servicio");
	$r_tiposer=ejecutar($q_tiposer) or mensaje(ERROR_BD);
	$f_tiposer=asignar_a($r_tiposer);

	$nombret="$f_cliente[nombres] $f_cliente[apellidos]";
$cedulat=$f_cliente[cedula];
$edadt=calcular_edad($f_cliente[fecha_nacimiento]);
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de Este Cliente como Titular "?> <input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
		<td class="tdtitulos">Nombres y Apellidos del Titular</td>
		<td class="tdcampos"><?php echo$nombret; ?></td>
		<td class="tdtitulos">Cedula</td>
                <td class="tdcampos"><?php echo $cedulat;?></td>
	</tr>		
	
	<tr> 
		<td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_cliente[nombre];
				$ente=$f_cliente[nombre];?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_cliente[estado_cliente]?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_cliente[tipo_ente]?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
	</tr>


<tr>
		<td class="tdtitulos"> Servicio</td>
		<td class="tdcampos"><?php echo $f_datos[servicio]?> (<?php echo $f_tiposer[tipo_servicio]?>)</td>
		<td class="tdtitulos"> Especialidad</td>
                <td class="tdcampos"><?php echo $f_datos[nombre]?></td>
	</tr>		
	
	<tr>
		<td class="tdtitulos"> Descripcion</td>
		<td class="tdcampos"><?php echo $f_datos[descripcion]?> </td>
		<td class="tdtitulos"> Enfermedad</td>
                <td class="tdcampos"><?php echo $f_datos[enfermedad]?></td>
	</tr>
	<tr>
		<td class="tdtitulos"> Fecha Cita</td>
		<td class="tdcampos"><?php echo $f_datos[fecha_cita]?> </td>
		<td class="tdtitulos"> Analista</td>
                <td class="tdcampos"><?php echo $f_datos[nombres]?> <?php echo $f_datos[apellidos]?></td>
	</tr>
	<tr>
	 <tr>
                <td class="tdtitulos"> Estado del Proceso</td>
                <td class="tdcampos"><?php echo $f_datos[estado_proceso]?></td>           
		<td class="tdtitulos"> Comentarios Operador</td>
		<td class="tdcampos"><textarea class="campos"  name="cooperador" cols=18 rows=3><?php echo $f_datos[comentarios]?></textarea> </td>
		
	</tr>
     <tr>
	<td class="tdtitulos"> Comentarios Gerente Operacion</td>
                <td class="tdcampos"><textarea class="campos" name="cogerent" cols=18 rows=3><?php echo $f_datos[comentarios_gerente]?></textarea></td> 
                <td class="tdtitulos"> Comentarios Gerente Medico</td>
                <td class="tdcampos"><textarea class="campos" name="comedico" cols=18 rows=3><?php echo $f_datos[comentarios_medico]?></textarea></td>                     
        </tr>

<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de Atencion en Estado $f_poliza[estado_proceso] $descripcioncheque $espera"?></td></tr>
<tr> 
               
		<td  class="tdtitulos">Fecha Creado:   </td>
		<td class="tdcampos"><?php echo  $f_poliza[fechacreado]; ?>

                </td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"> 
                </td>
	</tr>
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
               
		<td  class="tdtitulos">Num de Clave   </td>
<?php 
$url3="views01/con_claves.php?clave=$f_poliza[no_clave]";
?>
		<td class="tdcampos"><a href="<?php echo $url3; ?>" title="Consultar Clave"  onclick="Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;"><?php echo $f_poliza[no_clave]?></a>

                </td>
		<td class="tdtitulos">Num Planilla/Presupuesto</td>
		<td class="tdcampos"> 
<?php echo $f_poliza[nu_planilla]?>
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
				<td colspan=2 class="tdcampos"><?php echo $f_poliza1[descrip];
                                                                                    if ($f_poliza1[id_insumo]>0){
                                                                                        echo " cantidad $f_poliza1[unidades]";
                                                                                        }?></td>
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
	if ($num_filasf>0)
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
	?>
	
	
<tr> <td colspan=4 class="titulo_seccion"> Imprimir</td></tr>


<?php
}


if ($f_poliza[id_servicio]==1 || $f_poliza[id_servicio]==10){
?>

<tr>
		<td colspan=4 class="tdtitulos"><?php
        if ($f_poliza[id_estado_proceso]==2 || $f_poliza[id_estado_proceso]==4){
			$url="'views01/ireembolso.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			<?php } if  ($f_poliza[id_estado_proceso]<>11  and $f_poliza[id_estado_proceso]<>10 and $num_filasf==0) {
				$url="'views01/irevisionc.php?proceso=$proceso'";
                
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
<?php if ($f_poliza[id_estado_proceso]==2 || $f_poliza[id_estado_proceso]==4){
         if($f_admin[id_departamento] == 17){


?>
		<td colspan=4 class="tdtitulos">

<a href="#" OnClick="anular_orden();" class="boton">Anular Orden </a>
<a href="#" OnClick="anular_orden_no_doctor();" class="boton">Anular Orden No Asistio Doctor </a>
<?php }
    }
}?>




<?php

}
else
{
	if ($f_poliza[id_estado_proceso]==2){
	
	if ($f_poliza[id_tipo_servicio]==0 and ($f_poliza[id_servicio]==2 || $f_poliza[id_servicio]==3 || $f_poliza[id_servicio]==8 || $f_poliza[id_servicio]==11  || $f_poliza[id_servicio]==13 || $f_poliza[id_servicio]==10) )
	{
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir Cartas de Compromiso</td></tr>
	<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>
			

	
	<?php
	}
	else
	{
	?>
	<tr> <td colspan=4 class="titulo_seccion"> Imprimir Ordenes</td></tr>
<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios </a>
			
<?php
			$url="'views01/iordenb.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a><?php

			$url="'views01/irevision.php?proceso=$proceso'";
			?>
			<?php
			$url="'views01/ipresupuesto_orden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
			</td>
</tr>
<?php
	if ($f_poliza[nu_planilla]>0){
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir  Emergencias</td></tr>
<tr>
<td colspan=4 class="tdtitulos">
			<?php

		$url="'views01/isolicitudmedicamento1.php?nu_planilla=$f_poliza[nu_planilla]&nombret=$nombret&cedulat=$cedulat&ente=$ente&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamentos </a>
			<?php
		$url="'views01/iplanilla_ingreso.php?nu_planilla=$f_poliza[nu_planilla]&nombret=$nombret&cedulat=$cedulat&ente=$ente&edadt=$edadt&comentarios=$f_poliza[comentarios]&fecha_ingreso=$f_poliza[fecha_recibido]&numproceso=$proceso'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Planilla de Ingreso </a>
			<?php
			$url="'views01/ipresupuesto.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
				<?php $url="'views01/ipresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			<?php $url="'views01/ifacturapreforma.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Factura preforma</a> 
            <?php $url="'views01/ifacturapreformapdvsa.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">  Nota de Servicio</a>
				<?php $url="'views01/ifpresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>
			<?php }
			?>
						</td>
</tr>

<?php
}

if ($f_espera[id_estado_proceso]<>7 and $num_filasf==0) {
  if($f_admin[id_departamento] == 17){
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir  Emergencias</td></tr>
<tr>
<td>
		<td colspan=4 class="tdtitulos">
		<a href="#" OnClick="anular_orden();" class="boton">Anular Orden</a> 
		<a href="#" OnClick="anular_orden_no_doctor();" class="boton">Anular Orden No Asistio Doctor </a>  </td>
<?php
    }
}
?>	
</tr>



<?php
 }
?>





<?php
}
if ($f_espera[id_estado_proceso]==7 || $f_espera[id_estado_proceso]==11 || $f_espera[id_estado_proceso]==16) {
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir Finiquitos</td></tr>
<tr>
		<td colspan=4 class="tdtitulos">
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$ente'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=2&ente=$ente'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado logo mi salud</a>
			<?php
	if ($f_poliza[nu_planilla]>0){
		
			$url="'views01/iplanilla.php?proceso=$f_poliza[nu_planilla]&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Formato de Emergencia </a>
			<?php
			$url="'views01/ipresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			<?php $url="'views01/ifacturapreforma.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">  Factura preforma</a> <?php $url="'views01/ifacturapreformapdvsa.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">  Nota de Servicio</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>
			<?php }
			?>
</tr>
<?php
}
?>




<?php

if ($f_espera[id_estado_proceso]==13) {
?>

<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso en Espera </a>



<?php
}
?>








<?php
}
else
{
if ($titular==0)
{

/* **** repita para beneficiario **** */

while($f_clienteb=asignar_a($r_clienteb,NULL,PGSQL_ASSOC)){
	$nombreb="$f_clienteb[nombres] $f_clienteb[apellidos]";
	$cedulab=$f_clienteb[cedula];
	$edadb=calcular_edad($f_clienteb[fecha_nacimiento]);
	$parentesco=$f_clienteb[parentesco];
$i++;
$q_clientet=("select 
							clientes.id_cliente,
							clientes.nombres,
							clientes.apellidos,
							clientes.cedula,
							clientes.fecha_nacimiento,
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
							tbl_tipos_entes
                where 
							titulares.id_titular=$f_clienteb[id_titular] and 
							clientes.id_cliente=titulares.id_cliente and 
							titulares.id_titular=estados_t_b.id_titular and 
							estados_t_b.id_beneficiario=0 and 
							estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and 
							titulares.id_ente=entes.id_ente and
							entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente");

$r_clientet=ejecutar($q_clientet) or mensaje(ERROR_BD);
$f_clientet=asignar_a($r_clientet);
   $cliente="$f_clientet[nombres] $f_clientet[apellidos]";
    $ente="$f_clientet[nombre]";
	$edadt=calcular_edad($f_clientet[fecha_nacimiento]);
$q_datos=("select 		
							estados_procesos.estado_proceso,
							procesos.fecha_recibido,
							procesos.comentarios,
							procesos.comentarios_gerente,
							procesos.comentarios_medico,
							admin.nombres,admin.apellidos,
							gastos_t_b.fecha_cita,
							gastos_t_b.nombre,
							gastos_t_b.descripcion,
							gastos_t_b.enfermedad,
							coberturas_t_b.id_cobertura_t_b,
							propiedades_poliza.cualidad,
							coberturas_t_b.id_propiedad_poliza,
							coberturas_t_b.monto_actual,
							coberturas_t_b.id_organo,
							servicios.servicio,
							gastos_t_b.id_tipo_servicio
					from 
							propiedades_poliza,
							coberturas_t_b,
							gastos_t_b,
							estados_procesos,
							procesos,
							admin,
							servicios 
					where
							gastos_t_b.id_proceso=procesos.id_proceso and 
							gastos_t_b.id_cobertura_t_b=coberturas_t_b.id_cobertura_t_b and 		
							coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 		
							gastos_t_b.id_proceso=procesos.id_proceso and 
							procesos.id_proceso='$proceso' and 
							procesos.id_admin=admin.id_admin and 
							gastos_t_b.id_servicio=servicios.id_servicio and 
							procesos.id_estado_proceso=estados_procesos.id_estado_proceso");
$r_datos=ejecutar($q_datos) or mensaje(ERROR_BD);
$f_datos=asignar_a($r_datos);



$q_tiposer=("select  
							* 
					from 
							tipos_servicios 
					where 
							tipos_servicios.id_tipo_servicio=$id_tipo_servicio");
$r_tiposer=ejecutar($q_tiposer) or mensaje(ERROR_BD);
$f_tiposer=asignar_a($r_tiposer);
?>
<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos del Cliente como Beneficiario"?><input  type="hidden" size="10"  name="id_cobertura_t_b" class="campos" maxlength="10" value=<?php echo $f_cobertura[id_cobertura_t_b]; ?>></td></tr>

<tr>
                <td class="tdtitulos">Nombres y Apellidos del titular</td>
                <td class="tdcampos"><?php echo "$f_clientet[nombres] $f_clientet[apellidos]"?></td>
                <td class="tdtitulos">Cedula Titular</td>
                <td class="tdcampos"><?php echo $f_clientet[cedula]?></td>
        </tr>


        <tr>
         <td class="tdtitulos">Ente</td>
                <td class="tdcampos"><?php echo $f_clientet[nombre]; $ente=$f_clientet[nombre];?></td>
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr"><?php echo $f_clientet[estado_cliente]?></td>
        </tr>
    <tr>
         <td class="tdtitulos">Tipo Ente</td>
                <td class="tdcamposr"><?php echo $f_clientet[tipo_ente]; ?></td>
                <td class="tdtitulos"></td>
                <td class="tdcamposr"></td>
        </tr>






<tr>
		<td class="tdtitulos">Nombres y Apellidos del Beneficiario</td>
		<td class="tdcampos"><?php echo $nombreb?></td>
		<td class="tdtitulos">Cedula</td>
                <td class="tdcampos"><?php echo $cedulab?></td>
	</tr>		
	
	<tr> 
                <td class="tdtitulos">Estado</td>
                <td class="tdcamposr">
		<?php echo $f_clienteb[estado_cliente]?></td>
	</tr>


<tr>
		<td class="tdtitulos"> Servicio</td>
		<td class="tdcampos"><?php echo $f_datos[servicio]?> (<?php echo $f_tiposer[tipo_servicio]?>)</td>
		<td class="tdtitulos"> Especialidad</td>
                <td class="tdcampos"><?php echo $f_datos[nombre]?></td>
	</tr>		
	
	<tr>
		<td class="tdtitulos"> Descripcion</td>
		<td class="tdcampos"><?php echo $f_datos[descripcion]?> </td>
		<td class="tdtitulos"> Enfermedad</td>
                <td class="tdcampos"><?php echo $f_datos[enfermedad]?></td>
	</tr>
	<tr>
		<td class="tdtitulos"> Fecha Cita</td>
		<td class="tdcampos"><?php echo $f_datos[fecha_cita]?> </td>
		<td class="tdtitulos"> Analista</td>
                <td class="tdcampos"><?php echo $f_datos[nombres]?> <?php echo $f_datos[apellidos]?></td>
	</tr>
	<tr>
	 <tr>
                <td class="tdtitulos"> Estado del Proceso</td>
                <td class="tdcampos"><?php echo $f_datos[estado_proceso]?></td>           
		<td class="tdtitulos"> Comentarios Operador</td>
		<td class="tdcampos"><textarea class="campos" name="cooperador" cols=18 rows=3>  <?php echo $f_datos[comentarios]?></textarea> </td>
		
	</tr>
     <tr>
	<td class="tdtitulos"> Comentarios Gerente Operacion</td>
                <td class="tdcampos"><textarea class="campos" name="cogerent" cols=18 rows=3>  <?php echo $f_datos[comentarios_gerente]?></textarea></td> 
                <td class="tdtitulos"> Comentarios Gerente Medico</td>
                <td class="tdcampos"><textarea class="campos" name="comedico" cols=18 rows=3> <?php echo $f_datos[comentarios_medico]?></textarea></td>                     
        </tr>









<?php
}
?>


<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de Atencion en Estado $f_poliza[estado_proceso] $descripcioncheque $espera"?></td></tr>

<tr> 
               
		<td  class="tdtitulos">Fecha Creado:   </td>
		<td class="tdcampos"><?php echo  $f_poliza[fechacreado]; ?>

                </td>
		<td class="tdtitulos"></td>
		<td class="tdcampos"> 
                </td>
	</tr>

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
		 		<td class="tdtitulos">Clave</td>
<?php 
$url3="views01/con_claves.php?clave=$f_poliza[no_clave]";
?>
              	<td class="tdcampos"><a href="<?php echo $url3; ?>" title="Consultar Clave"  onclick="Modalbox.show(this.href, {title: this.title, width:800,height:400, overlayClose: false}); return false;"><?php echo $f_poliza[no_clave]?></a></td>
			           
				<td class="tdtitulos">Num Planilla</td>
              	<td class="tdcampos"><?php echo $f_poliza[nu_planilla] ?></td>
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
				<td colspan=2 class="tdcampos"><?php echo $f_poliza1[descrip];
                                                                                    if ($f_poliza1[id_insumo]>0){
                                                                                        echo " cantidad $f_poliza1[unidades]";
                                                                                        }?></td>
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
</tr>	
	<?php 
	if ($num_filasf>0)
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
	?>
	


<tr> <td colspan=4 class="titulo_seccion"> Imprimir</td></tr>


<?php



if ($f_poliza[id_servicio]==1 || $f_poliza[id_servicio]==10){
?>

<tr>
		<td colspan=4 class="tdtitulos"><?php
        if ($f_poliza[id_estado_proceso]==2 || $f_poliza[id_estado_proceso]==4){
			$url="'views01/ireembolso.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso sin Espera </a>
			<?php } if  ($f_poliza[id_estado_proceso]<>11 and $f_poliza[id_estado_proceso]<>10 and $num_filasf==0) {
				$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
            <?php if ($f_poliza[id_estado_proceso]==2 || $f_poliza[id_estado_proceso]==4){
                      if($f_admin[id_departamento] == 17){
?>
		<td colspan=4 class="tdtitulos">

<a href="#" OnClick="anular_orden();" class="boton">Anular Orden</a>
<a href="#" OnClick="anular_orden_no_doctor();" class="boton">Anular Orden No Asistio Doctor </a>
<?php 
       }
     }
}?>


<?php

}
else
{
	
if ($f_poliza[id_estado_proceso]==2){
	
	
	if ($f_poliza[id_tipo_servicio]==0 and ($f_poliza[id_servicio]==2 || $f_poliza[id_servicio]==3 || $f_poliza[id_servicio]==8 || $f_poliza[id_servicio]==11  || $f_poliza[id_servicio]==13 || $f_poliza[id_servicio]==10) )
	{
?>

	<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Recibo de Carta Aval </a>
			<?php
			$url="'views01/icarta.php?proceso=$proceso&si=0'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Carta Aval </a>

	
	<?php
	}
	else
	{
	?>
<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/iorden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden Con Monto </a><?php
			$url="'views01/iorden.php?proceso=$proceso&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Orden Sin Monto  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
           
			<?php
			$url="'views01/iordenb.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Orden ente privado  </a><?php
			$url="'views01/irevision.php?proceso=$proceso'";
			?>
			<?php
			$url="'views01/ipresupuesto_orden.php?proceso=$proceso&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a>
</td>
</tr>

			<?php
	if ($f_poliza[nu_planilla]>0){
		?>
		<tr> <td colspan=4 class="titulo_seccion"> Imprimir  Emergencias</td></tr>
<tr>
<td colspan=4 class="tdtitulos">
		<?php
			$url="'views01/isolicitudmedicamento1.php?nu_planilla=$f_poliza[nu_planilla]&nombret=$f_clientet[nombres] $f_clientet[apellidos]&cedulat=$f_clientet[cedula]&nombreb=$nombreb&cedulab=$cedulab&ente=$ente&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Solicitud de Medicamentos </a>
			<?php
		$url="'views01/iplanilla_ingreso.php?nu_planilla=$f_poliza[nu_planilla]&nombret=$f_clientet[nombres] $f_clientet[apellidos]&cedulat=$f_clientet[cedula]&nombreb=$nombreb&cedulab=$cedulab&parentesco=$parentesco&ente=$ente&edadt=$edadt&edadb=$edadb&comentarios=$f_poliza[comentarios]&fecha_ingreso=$f_poliza[fecha_recibido]&numproceso=$proceso'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Planilla de Ingreso </a>
			<?php
			$url="'views01/ipresupuesto.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto </a><?php
			$url="'views01/ipresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			<?php $url="'views01/ifacturapreforma.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">  Factura preforma</a>
            <?php $url="'views01/ifacturapreformapdvsa.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">  Nota de Servicio</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1&ente=$ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>
			<?php }
			?>
	
	
<?php
}
if ($num_filasf==0){
   if($f_admin[id_departamento] == 17){
?>
<tr>
<td colspan=4 class="tdtitulos">
<hr> </hr>
</td>
</tr>
<tr>
<td>
		<td colspan=4 class="tdtitulos">
		<a href="#" OnClick="anular_orden();" class="boton">Anular Orden</a> 
		<a href="#" OnClick="anular_orden_no_doctor();" class="boton">Anular Orden No Asistio Doctor </a>  </td>
	</tr>



<?php }
    }
 } 
?>


<?php
}
}
if ($f_espera[id_estado_proceso]==7 || $f_espera[id_estado_proceso]==11 || $f_espera[id_estado_proceso]==16) {
?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir Finiquitos</td></tr>
<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/irevisionc.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Comentarios</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito </a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=0&ente=$ente'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado</a>
			<?php
			$url="'views01/ifiniquito.php?proceso=$proceso&si=2&ente=$ente'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton"> Finiquito Ente Privado logo mi salud</a>
			<?php
			if ($f_poliza[nu_planilla]>0){
		
			$url="'views01/iplanilla.php?proceso=$f_poliza[nu_planilla]&si=0'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Formato de Emergencia </a>
			<?php
			$url="'views01/ipresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Presupuesto Ente Privado</a>
			<?php $url="'views01/ifacturapreforma.php?proceso=$f_poliza[nu_planilla]&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Factura preforma</a>
			<?php $url="'views01/ifpresupuestop.php?proceso=$f_poliza[nu_planilla]&si=1&ente=$ente''";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Finiquito Presupuesto Ente Privado </a>
			<?php }
			?>
			
</tr>
<?php
}
}
}

?>




<?php

if ($f_espera[id_estado_proceso]==13) {
?>

<tr>
		<td colspan=4 class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$proceso&si=1'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Reembolso en Espera </a>
</tr>
<?php
}
    $q_carta_rechazo="select * from tbl_control_cartas where tbl_control_cartas.id_proceso='$proceso' and tbl_control_cartas.activar='0'";
    $r_carta_rechazo=ejecutar($q_carta_rechazo);
    $num_filascr=num_filas($r_carta_rechazo);
    if ($num_filascr>0){
    $f_carta_rechazo=asignar_a($r_carta_rechazo);
    if ($f_carta_rechazo[id_tipo_control]==1)
    {
        $rechazo='Total';
        }
        else
        {
            $rechazo='Parcial';
            }
     if ($f_carta_rechazo[id_tipo_control]==3){
         ?>
         <tr> <td colspan=4 class="titulo_seccion"> Esta Orden ya Tiene un Pago Unico de Gracia
    <?php
			$url="'views01/ipago_gracia.php?proceso=$proceso&motivo=$f_carta_rechazo[motivo]&comentario=$f_carta_rechazo[comentario]&cliente=$cliente&nomente=$ente&porcentaje=$f_carta_rechazo[porcentaje]&fecha_recibido=$f_datos[fecha_recibido]'";
			?> <a title="Imprimir Carta del Pago Unico de Gracia" href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Imprimir Pago Unico de Gracia </a>
    </td></tr>
         
         <?php
         }
         else
         {
         ?>


    <tr> <td colspan=4 class="titulo_seccion"> Esta Orden Tiene una Carta de Rechazo <?php echo $rechazo;?>
    <?php
			$url="'views01/icartar.php?proceso=$proceso&motivo=$f_carta_rechazo[motivo]&comentario=$f_carta_rechazo[comentario]&cliente=$cliente&nomente=$ente&fecha_recibido=$f_datos[fecha_recibido]'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Imprimir Carta Rechazo </a>
    </td></tr>
    <?php
     }
     }
   
     if ($f_poliza[donativo]==1 || $f_poliza[donativo]==3)  {
  ?>
<tr> <td colspan=4 class="titulo_seccion"> Imprimir Cartas de Donativos 
        <select id="donativo" name="donativo" class="campos" style="width: 200px;"  >
		<option value="1"  <?php if($f_poliza[donativo]==1) echo "selected"; ?>>Donativo por Responsabilidad Social</option>
        <option value="3"  <?php if($f_poliza[donativo]==3) echo "selected"; ?>>Donativo por CiniSalud</option>
   	</select>
   <label class="boton" title="Modificar el Tipo de Donativo" style="cursor:pointer" onclick="act_orden_donativo()" >Actualizar</label>
   </td>
</tr>
<tr>
		<td  class="tdtitulos"><?php
  if ($f_espera[id_estado_proceso]<=2) {
			$url="'views01/iacta.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Acta</a>
			<?php
            }
			$url="'views01/isolicitud.php?proceso=$proceso'";
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Solicitud</a>
			</td>
</tr>
<?php
}
?>
</table>




