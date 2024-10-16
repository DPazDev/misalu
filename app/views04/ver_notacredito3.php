<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$factura = $_REQUEST['facturanueva'];
$admin= $_SESSION['id_usuario_'.empresa];
$q_sucursales=("select * from sucursales  order by sucursales.sucursal");
$r_sucursales=ejecutar($q_sucursales);
$q_servicios=("select * from servicios  order by servicios.servicio");
$r_servicios=ejecutar($q_servicios);
$q_admin=("select * from admin  where admin.id_admin=$admin");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
//busco las series.

$q_factura="select 
							tbl_series.*,
							tbl_facturas.*
					from 
							tbl_facturas,
							tbl_series,
							admin 
					where 
							tbl_facturas.numero_factura='$factura' and 
							tbl_facturas.id_serie=tbl_series.id_serie and 
							tbl_series.id_sucursal=$f_admin[id_sucursal]  ;";
$r_factura=ejecutar($q_factura);

	if(num_filas($r_factura)==0){
		?>
		<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">La factura No Existe</td> 
</tr>
</table>
	<?php
	}
	else
	{
				$f_factura = asignar_a($r_factura);
		//busco las series.
$r_serie=("select * from tbl_series,admin where tbl_series.id_sucursal=admin.id_sucursal and admin.id_admin='$admin'");
$f_serie=ejecutar($r_serie) or mensaje(ERROR_BD);
$f_series=asignar_a($f_serie);
		/* **** busco las factura para saber cual es la ultima**** */
$q_notacredito="select * from tbl_notacredito,tbl_facturas,tbl_series where tbl_notacredito.id_factura=tbl_facturas.id_factura and tbl_facturas.id_factura=$f_factura[id_factura] and tbl_facturas.id_serie=$f_series[id_serie]  order by tbl_notacredito.id_notacredito desc limit 1;";
$r_notacredito=ejecutar($q_notacredito);

$q_proceso="select * from procesos where procesos.id_proceso=tbl_procesos_claves.id_proceso and   tbl_procesos_claves.id_factura=$f_factura[id_factura]";
$r_proceso=ejecutar($q_proceso);
$f_proceso=asignar_a($r_proceso);
			$q_procesos="select * from tbl_procesos_claves where   tbl_procesos_claves.id_factura=$f_factura[id_factura]";
$r_procesos=ejecutar($q_procesos);
		
		if ($f_factura[con_ente]>0)
				{
				//busco los datos del ente
	$q_ente="select * from entes where entes.id_ente=$f_factura[con_ente]";
	$r_ente = ejecutar($q_ente);
	$f_ente = asignar_a($r_ente);
					?>
					<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">Datos del Cliente</td> 
</tr>
					<tr>
<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
<td class="tdcampos"><?= $f_ente['nombre'] ?></td>
<td class="tdtitulos">Rif o C.I. No.: </td>
<td class="tdcampos"><?= $f_ente['rif'] ?></td>
</tr>

<tr>
<td class="tdtitulos">Direcci&oacute;n:</td>
<td class="tdcampos"><?= $f_ente['direccion'] ?></td>
<td class="tdtitulos">Tel&eacute;fonos:</td>
<td class="tdcampos"><?= $f_ente['telefonos'] ?></td>
</tr>
					<?php
					}
					else
					{

	
	$id_titular = $f_proceso['id_titular'];
	$id_beneficiario = $f_proceso['id_beneficiario'];
	$proceso= $f_proceso['id_proceso'];
	$clave= $f_proceso['no_clave'];
	//busco los datos del ente
	$q_ente="select entes.* from entes, titulares 
			where 
			titulares.id_titular = '$id_titular' and 
			titulares.id_ente = entes.id_ente;";
	$r_ente = ejecutar($q_ente);
	$f_ente = asignar_a($r_ente);

	//Busco los datos del titular.
	$q_titular = "select clientes.* from clientes, titulares 
			where 
			clientes.id_cliente = titulares.id_cliente and
			titulares.id_titular = '$id_titular'";
	$r_titular = ejecutar($q_titular);
	$f_titular = asignar_a($r_titular);

	//Busco los datos del beneficiario.
	if($id_beneficiario>0){
	$q_bene = "select clientes.* from clientes, titulares, beneficiarios
			where
			clientes.id_cliente = beneficiarios.id_cliente and
			titulares.id_titular = '$id_titular' and
			beneficiarios.id_titular = titulares.id_titular and
			beneficiarios.id_beneficiario = '$id_beneficiario'";
	$r_bene = ejecutar($q_bene);
	$f_bene = asignar_a($r_bene);
	}
	
	
?>

<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">Datos del Cliente</td> 
</tr>

<?php
if($f_ente['id_ente']!=0){

?>
<tr>
<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
<td class="tdcampos"><?= $f_ente['nombre'] ?></td>
<td class="tdtitulos">Rif o C.I. No.: </td>
<td class="tdcampos"><?= $f_ente['rif'] ?></td>
</tr>

<tr>
<td class="tdtitulos">Direcci&oacute;n:</td>
<td class="tdcampos"><?= $f_ente['direccion'] ?></td>
<td class="tdtitulos">Tel&eacute;fonos:</td>
<td class="tdcampos"><?= $f_ente['telefonos'] ?></td>
</tr>
<tr>
<td class="tdtitulos">Proceso</td>
<td class="tdcampos"><?php echo $proceso; ?></td>
<td class="tdtitulos">No. de Clave:</td>
<td class="tdcampos"><?= $clave ?></td>
</tr>
<tr>
<td class="tdtitulos">Titular: </td>
<td class="tdcampos"><?= $f_titular['nombres'].' '.$f_titular['apellidos'] ?></td>
<td class="tdtitulos">Cedula.</td>
<td class="tdcampos"><?= $f_titular['cedula'] ?></td>
</tr>
<?php
	if($id_beneficiario>0){
?>
	<tr>
	<td class="tdtitulos">Beneficiario: </td>
	<td class="tdcampos"><?= $f_bene['nombres'].' '.$f_bene['apellidos'] ?></td>
	<td class="tdtitulos">Cedula</td>
	<td class="tdcampos"><?= $f_bene['cedula'] ?></td>
	</tr>
<?php
	}else{
?>
	<tr><td class="tdtitulos"><b></b>  <b></b></td></tr>
<?php
	}
}
else
{
?>
<tr>
<td class="tdtitulos">Nombre o raz&oacute;n social:</td>
<td class="tdcampos"><?= $f_titular['nombres'] .' '.$f_titular['apellidos'] ?></td>
<td class="tdtitulos">Rif o C.I. No.:</td>
<td class="tdcampos"><?= $f_titular['cedula'] ?></td>
</tr>
<tr>
<td class="tdtitulos">Direcci&oacute;n: </td>
<td class="tdcampos"><?= $f_titular['direccion_hab'] ?></td>
<td class="tdtitulos">Tel&eacute;fonos:</td>
<td class="tdcampos"><?= $f_titular['telefono_hab'].' /  '.$f_titular['celular'] ?></td>
</tr>
<tr><td class="tdtitulos"><hr></td></tr>

<?php
}
}
?>
<tr>
<td colspan=4 class="tdtitulos"><hr></td></tr>

<?php
	//Busco los procesos que estan afiliados a la clave.
	pg_result_seek($r_procesos,0);
	while($f_proceso = asignar_a($r_procesos)){
		$subtotal=0;
		?>
		<tr>
		<td class="tdcamposr" colspan=1 >PROCESO</td>
		<td class="tdcamposr" colspan=1 ><?php echo $f_proceso[id_proceso] ?></td>
		</tr>
	<tr>
	<td class="tdtitulos" colspan=2 >CONCEPTO O DESCRIPCION	</td>
	<td class="tdtitulos" colspan=2 >Bs.S.</td>
</tr>
		
		
		<?php
		$q_proceso = "select gastos_t_b.*, procesos.* from gastos_t_b,procesos 
				where 
				procesos.id_proceso='$f_proceso[id_proceso]' and
				procesos.id_proceso=gastos_t_b.id_proceso";
		$r_proceso = ejecutar($q_proceso);
		if(num_filas($r_proceso)>0){
		while($f = asignar_a($r_proceso)){
		$subtotal=$subtotal + $f[monto_aceptado];
		$total=$total + $f[monto_aceptado];
		echo "
		<tr>
		<td class=\"tdcampos\" colspan=2 >$f[descripcion] &nbsp;&nbsp; $f[nombre]</td>
		<td class=\"tdcampos\" colspan=1  valign=\"bottom\">$f[monto_pagado]</td>
		</tr>

		";
		}
			echo "
		<tr>
		<td class=\"tdtitulos\" colspan=1 ></td>
		<td class=\"tdtitulos\" colspan=1 >Sud Total</td>
		<td class=\"tdtitulos\" colspan=1  valign=\"bottom\">$subtotal</td>
		</tr>

		";
		}
	}

?>
<tr>
<td  class="tdtitulos"></td>
<td  class="tdcamposr">Total BF.</td>
<td colspan=2 class="tdcamposr"><input class="campos" size="10" type="text" name="monto" id="monto" maxlength=128 size=20 value="<?php echo $total?>">
<input class="campos" type="hidden" name="id_fact_nueva" id="id_fact_nueva" maxlength=128 size=20 value="<?php echo $f_factura[id_factura]?>">
</td> 
</tr>

<tr>
<tr>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>
<td class="tdtitulos"></td>
<td class="tdcampos"><input class="campos" type="hidden" name="vacio" maxlength=128 size=20 value=""><a href="#" OnClick="actualizar_notacredito();" class="boton" title="Actualizar Nota de Credito">Actualizar</a></td>
		</tr>
</table>

<?php }?>
