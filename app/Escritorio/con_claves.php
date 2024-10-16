<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');
$clave = $_REQUEST['clave'];
$proceso = $_REQUEST['proceso'];
$planilla = $_REQUEST['planilla'];
$entes = $_REQUEST['entes'];
$dateField1 = $_REQUEST['dateField1'];
$dateField2 = $_REQUEST['dateField2'];
$admin= $_SESSION['id_usuario_'.empresa];
$sucursal = "*";
$servicios = "*";

if ($sucursal=='*'){
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal>'0'";
}
else
{
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal='$sucursal'";
}

if ($servicios=='*'){
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio>'0'";
}
else
{
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio='$servicios'";

}



if (!empty($proceso))
	{
	$q = "select * From procesos where  procesos.id_proceso='$proceso'";
	}
	else
	{
		if (!empty($clave))
		{
			if ($dateField1<>""){
				$fecha="and procesos.fecha_ent_pri>='$dateField1' and procesos.fecha_ent_pri<='$dateField2'";
				}
				else
				{
					$fecha="";
					}
				
		$q = "select * From procesos where  procesos.no_clave='$clave' $fecha";
		}
		else
		{
			if (!empty($planilla))
			{
			$q = "select * From procesos where  procesos.nu_planilla='$planilla'";
			}
			else
			{
				if (!empty($entes))
				{
				$q = "select procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso,count(gastos_t_b.id_proceso) 
From procesos,titulares,admin,gastos_t_b
where procesos.fecha_ent_pri>='$dateField1' and procesos.fecha_ent_pri<='$dateField2' 
and procesos.id_titular=titulares.id_titular and titulares.id_ente='$entes' $servicios1 $sucursal1 
group by procesos.id_titular,procesos.id_beneficiario,procesos.id_proceso";
				}
			}
		}
	}

$r_procesos = ejecutar($q);

	if(num_filas($r_procesos)==0){
	?>
    <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">No Existen </td> 
</tr>
</table>
	<?php
	}
	
	else
	{
		
		if (!empty($entes))
				{
					//busco los datos del ente
	$q_ente="select * from entes where entes.id_ente=$entes";
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

	$f_procesos=asignar_a($r_procesos);
	$id_titular = $f_procesos['id_titular'];
	$id_beneficiario = $f_procesos['id_beneficiario'];
	$proceso= $f_procesos['id_proceso'];
	$clave= $f_procesos['no_clave'];
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
			beneficiarios.id_titular = '$id_titular' and
			beneficiarios.id_beneficiario = '$id_beneficiario'";
	$r_bene = ejecutar($q_bene);
	$f_bene = asignar_a($r_bene);
	}

?>
<script language="JavaScript">
<!--
function enviar(){
	
		document.factura.submit();
	
}
-->
</script>

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
		<td class="tdcamposr" colspan=1 >ORDEN NUM</td>
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
	?>
		<tr>
		<td class="tdcampos" colspan=2 ><?php echo $f[descripcion] ?> &nbsp;&nbsp; <?php $f[nombre]?></td>
		<td class="tdcampos" colspan=1  valign="bottom"><?php echo montos_print($f[monto_pagado])?></td>
		</tr>

		<?php
		}
		?>	
		<tr>
		<td class="tdtitulos" colspan=1 ></td>
		<td class="tdtitulos" colspan=1 >Sud Total</td>
		<td class="tdtitulos" colspan=1  valign="bottom"><?php echo montos_print($subtotal)?></td>
		</tr>
	<?php
		/**** buscar si este proceso se ha facturado ****/
$q_factura=("select * from tbl_facturas,tbl_procesos_claves,tbl_series where tbl_facturas.id_factura=tbl_procesos_claves.id_factura and tbl_procesos_claves.id_proceso=$f_proceso[id_proceso] and tbl_facturas.id_serie=tbl_series.id_serie and tbl_facturas.id_estado_factura<>3");
$r_factura=ejecutar($q_factura);
$num_filasf=num_filas($r_factura);
	?>
	

<?php 
	if ($num_filasf>0)
	{
	?>	
<tr> <td colspan=4 class="tdcamposr">Esta orden esta Facturada en los Siguientes Numeros de Factura</td>
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
				<td colspan=4 class="tdcampos"> <?php echo "Numero Factura  $f_factura[numero_factura] Numero Control  $f_factura[numcontrol] Estado $estado  Serie $f_factura[nomenclatura] Fecha Creado $f_factura[fecha_hora_creado]" ?></td>
	</tr>	
<tr>
<td colspan=4 class="tdtitulos"><hr></td></tr>
	<?php
	}
	}
	
		}
	}

?>
<tr>
<td  class="tdtitulos"></td>
<td  class="tdcamposr">Total BF.</td>
<td colspan=2 class="tdcamposr"><input type="hidden" id="monto" name="monto" class="campos" size=20 value="<?php echo $total?>"><?php echo montos_print($total);?></td> 
</tr>

	</table>



<?php }?>
