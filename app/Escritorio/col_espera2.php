<?php
include ("../../lib/jfunciones.php");
sesion();
$proceso=$_REQUEST['proceso'];

$q_poliza="select gastos_t_b.descripcion as descrip,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.fecha_cita,gastos_t_b.hora_cita,gastos_t_b.enfermedad,gastos_t_b.monto_aceptado,procesos.fecha_recibido,procesos.comentarios,coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,polizas.* from polizas,coberturas_t_b,gastos_t_b,procesos,propiedades_poliza where polizas.id_poliza=propiedades_poliza.id_poliza and propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso'";
$r_poliza=ejecutar($q_poliza);
$f_poliza=asignar_a($r_poliza);

$q_poliza1="select gastos_t_b.descripcion as descrip,gastos_t_b.nombre,gastos_t_b.id_tipo_servicio,gastos_t_b.id_servicio,gastos_t_b.id_proveedor,gastos_t_b.fecha_cita,gastos_t_b.hora_cita,gastos_t_b.enfermedad,gastos_t_b.monto_aceptado,procesos.fecha_recibido,procesos.comentarios,coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,polizas.* from polizas,coberturas_t_b,gastos_t_b,procesos,propiedades_poliza where polizas.id_poliza=propiedades_poliza.id_poliza and propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_proceso='$proceso'";
$r_poliza1=ejecutar($q_poliza1);

/* **** busco si es titular **** */
$q_cliente=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,titulares.id_titular,titulares.id_ente,entes.nombre from clientes,titulares,estados_t_b,estados_clientes,entes,procesos
                where clientes.id_cliente=titulares.id_cliente and titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente>=4 and titulares.id_titular=procesos.id_titular  and procesos.id_proceso='$proceso'");
$r_cliente=ejecutar($q_cliente) or mensaje(ERROR_BD);
$num_filas=num_filas($r_cliente);
$f_cliente=asignar_a($r_cliente);
if ($num_filas == 0){
	$procesot=0;
	}
	else{
	$procesot=1;
	}

/* **** busco si es beneficiario **** */

$q_clienteb=("select clientes.id_cliente,clientes.nombres,clientes.apellidos,clientes.cedula,estados_clientes.estado_cliente,
beneficiarios.id_beneficiario,beneficiarios.id_titular,entes.nombre 
from clientes,estados_clientes,beneficiarios,entes,estados_t_b,titulares,procesos  where 
clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario 
and estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and beneficiarios.id_titular=titulares.id_titular 
and titulares.id_ente=entes.id_ente and estados_clientes.id_estado_cliente>=4 and titulares.id_titular=procesos.id_titular and beneficiarios.id_beneficiario=procesos.id_beneficiario and procesos.id_proceso='$proceso'");
$r_clienteb=ejecutar($q_clienteb) or mensaje(ERROR_BD);
$num_filasb=num_filas($r_clienteb);
$f_clienteb=asignar_a($r_clienteb);
if ($num_filasb == 0) { 
$titular=1;
$procesob=0;
}
else
{
	$titular=0;
	}
?>

<table  class="tabla_citas"    cellpadding=0 cellspacing=0>
<?php if ($procesob==0 && $procesot==0)  {
?>
<tr> <td colspan=4 class="titulo_seccion">  El Numero de Orden no Existe</td></tr>
<?php }
else
{
	?>

<tr> <td colspan=4 class="titulo_seccion">  Datos del Cliente Actual </td></tr>

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


<tr> <td colspan=4 class="titulo_seccion">  <?php echo  "Datos de la Orden de Atencion"?></td></tr>
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








	<tr>
		<td colspan=2 class="tdcamposr">Esta Seguro de Colocar la Orden de Nuevo en Espera</td>
	
		<td colspan=2 class="tdcampos"><a href="#" OnClick="gua_ord_espera();" class="boton">Colocar Orden en Espera</a></td>

		
</tr>

</table>
	
<?php
}
?>


