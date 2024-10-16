<?php
include ("../../lib/jfunciones.php");
sesion();

$codigo=$_REQUEST['codigo'];
$modificar=$_REQUEST['modificar'];
$eliminar=$_REQUEST['eliminar'];
$fechaemi=$_REQUEST['fechaemi'];
$factura=$_REQUEST['factura'];
$proceso=$_REQUEST['proceso'];


/* variable para controlar los cheques de proveedor compra */
$paso=0;
/* fin variable para controlar los cheques de proveedor compra */
$tipo_cheque=$_REQUEST['tipo_cheque'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select * from admin where admin.id_admin='$id_admin'");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);
$id_tipo_admin=$f_admin[id_tipo_admin];
$num_filas=0;
$num_filas1=0;
if ($eliminar==1)
{
   
    
/* **** Elimino de la tabla facturas_procesos la factura mal cargada y si tiene un proceso le cambio su estado en la tabla 
procesos a candidato pago **** */

$q_bus_pro="select * from facturas_procesos where  
facturas_procesos.codigo='$codigo' and facturas_procesos.factura='$factura' and facturas_procesos.id_proceso='$proceso' ";
$r_bus_pro=ejecutar($q_bus_pro);
$f_bus_pro=asignar_a($r_bus_pro);

$mod_fpro="update procesos set id_estado_proceso=7 where  procesos.id_proceso='$f_bus_pro[id_proceso]'";
$fmod_fpro=ejecutar($mod_fpro);



$eli_gasto="delete from facturas_procesos where  
facturas_procesos.codigo='$codigo' and facturas_procesos.factura='$factura' and facturas_procesos.id_proceso='$proceso'";
$feli_gasto=ejecutar($eli_gasto);

/* **** Se registra lo que hizo el usuario**** */
$log="Se elimino la factura $factura del $codigo por estar mal cargada";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
    
    
    }

if ($modificar==1)
{
  
/* **** Actualizo la tabla facturas_procesos las fecha factura emision **** */
$mod_fpro="update facturas_procesos set fecha_emision_fact='$fechaemi' where  
facturas_procesos.codigo='$codigo' and facturas_procesos.factura='$factura'";
$fmod_fpro=ejecutar($mod_fpro);

/* **** Se registra lo que hizo el usuario**** */
$log="Se modifico las fechas de emision del $codigo";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
    }


	$q_facturas=("select * from facturas_procesos where 	facturas_procesos.codigo=$codigo
");
$r_facturas=ejecutar($q_facturas);

	$q_facturas1=("select * from facturas_procesos where facturas_procesos.codigo=$codigo
");

$r_facturas1=ejecutar($q_facturas1);
$f_facturas1=asignar_a($r_facturas1);
$num_filas=num_filas($r_facturas);
if ($num_filas>0){
$tipo="Recibo";
}
	else
{
	
$q_facturas=("select * from facturas_procesos where facturas_procesos.codigo='$codigo'
");
$r_facturas=ejecutar($q_facturas);
$q_facturas1=("select * from facturas_procesos where facturas_procesos.codigo='$codigo'
");
$r_facturas1=ejecutar($q_facturas1);
$f_facturas1=asignar_a($r_facturas1);

$num_filas1=num_filas($r_facturas);
if ($num_filas>0){
$tipo="Cheque";
}		
}
if ($num_filas==0 and $num_filas1==0){?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>		<td colspan=7 class="titulo_seccion">No Hay Cheques o Recibos con estos Numeros Registrados</td>	</tr>
</table>
<?php
	
	}
	else
	{
		
/* **** compraro si busco proveedor persona o proveedor clinica **** */
if ($f_facturas1[tipo_proveedor]==1){

/* **** BUSCO EL PROVEEDOR  persona**** */

$q_proveedor=("select   personas_proveedores.*,actividades_pro.codigo,actividades_pro.porcentaje,actividades_pro.actividad,actividades_pro.sustraendo
		                from personas_proveedores,actividades_pro where personas_proveedores.id_persona_proveedor=$f_facturas1[id_proveedor] and personas_proveedores.id_act_pro=actividades_pro.id_act_pro");
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nomcheque]";
$rifpro=$f_proveedor[rifcheque];
$direccionpro=$f_proveedor[direccioncheque];
$telefonospro=$f_proveedor[celular_pro];
$objetoretencion=$f_proveedor[actividad];
$porcentaje=100 * $f_proveedor[porcentaje];
$sustraendo=$f_proveedor[sustraendo];

}
else
{

/* **** BUSCO EL PROVEEDOR  clinica**** */

$q_proveedor="select clinicas_proveedores.*,actividades_pro.codigo,actividades_pro.actividad,actividades_pro.porcentaje,actividades_pro.sustraendo from clinicas_proveedores,proveedores,actividades_pro where clinicas_proveedores.id_clinica_proveedor=proveedores.id_clinica_proveedor and proveedores.id_proveedor=$f_facturas1[id_proveedor] and clinicas_proveedores.id_act_pro=actividades_pro.id_act_pro";
$r_proveedor=ejecutar($q_proveedor);
$f_proveedor=asignar_a($r_proveedor);
$nombrepro="$f_proveedor[nombre]";
$rifpro=$f_proveedor[rif];
$direccionpro=$f_proveedor[direccion];
$telefonospro=$f_proveedor[telefonos];
$objetoretencion=$f_proveedor[actividad];

$porcentaje=100 * $f_proveedor[porcentaje];
/* **** FIN DE BUSCAR PROVEEDOR **** */
}
?>
<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
			

	
	
<td colspan=9 class="titulo_seccion">Datos Del Proveedor</td>	
</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Nombre o Razon Social </td>
		<td colspan=2 class="tdcampos"><?php echo $nombrepro?></td>
		<td colspan=2 class="tdtitulos">Rif </td>
		<td colspan=1 class="tdcampos"><?php echo $rifpro?></td>
		<td colspan=1 class="tdtitulos"></td>
	</tr>
	<tr>
		<td colspan=3 class="tdtitulos">Domicilio Fiscal  </td>
		<td colspan=6 class="tdcampos"><?php echo $direccionpro?></td>
		
	</tr>
	<tr>
		<td colspan=1 class="tdtitulos">Fecha Emisi&oacute;n</td>
		<td colspan=1 class="tdcampos"><?php echo $f_facturas1[fecha_creado]?></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos">Nro. de Comprobante IVA</td>
		<td colspan=2 class="tdcampos"><?php echo $f_facturas1[compro_retiva_seniat]?></td>
		<td colspan=1 class="tdtitulos">Periodo Fiscal</td>
		<td colspan=2 class="tdcampos"><?php 
		$compro_retiva_seniat=$f_facturas1[compro_retiva_seniat];
$periodo=split("-",$compro_retiva_seniat);
		echo "A&ntilde;o: $periodo[0] / Mes $periodo[1]"?></td>
	</tr>
    <tr>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdcampos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos">Nro. de Comprobante ISLR</td>
		<td colspan=2 class="tdcampos"><?php echo $f_facturas1[corre_compr_islr]?></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=2 class="tdcampos"></td>
	</tr>
	
	<tr>
		<td colspan=9 class="tdtitulos"><hr></hr></td>
		
	</tr>
		<tr>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos">Num Factura</td>
		<td colspan=1 class="tdtitulos">Fecha Emision de la Factura</td>
		<td colspan=1 class="tdtitulos">Monto Total de la Factura</td>
		<td colspan=1 class="tdtitulos">Monto Exento</td>
		<td colspan=1 class="tdtitulos">Base Imponible</td>
		<td colspan=1 class="tdtitulos">I.V.A. Facturado</td>
		<td colspan=1 class="tdtitulos">I.V.A. Retenido</td>
		<td colspan=1 class="tdtitulos">Total Neto A Pagar</td>
	</tr>

<?php 
while($f_facturas=asignar_a($r_facturas,NULL,PGSQL_ASSOC)){
	$total_iva_ret= 	$total_iva_ret + $f_facturas[iva_retenido];
	$total_neto=$total_neto +  (($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva]) - $f_facturas[iva_retenido]);
	$fecha_emision
    
	?>
	<tr>
    
		<td colspan=1 class="tdcampos">
        <?php 
        $i++;
    if ($f_facturas[id_banco]==13){
    ?>
        <a href="#" OnClick="quitar_gasto3(<?php echo $i?>);" class="boton" title="Quitar Gasto">Quitar</a>
        <?php
        }
        ?>
        </td>
		<td colspan=1 class="tdcampos"><?php echo $f_facturas[factura]?></td>
		<td colspan=1 class="tdcampos"><input class="campos" type="hidden" id="factura_<?php echo$i?>"  name="factura_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $f_facturas[factura]?>" > 
        <input class="campos" type="hidden" id="proceso_<?php echo$i?>"  name="proceso_<?php echo$i?>" maxlength=128 size=30 value="<?php echo $f_facturas[id_proceso]?>" >
        <input class="campos" type="text" id="fechaemi_<?php echo$i?>"  name="fechaemi_<?php echo$i?>" maxlength=10 size=10 value="<?php echo $f_facturas[fecha_emision_fact]?>" ><a href="#" OnClick="mod_fec_emi(<?php echo $i?>);" class="boton" title="Modificar Fecha Gasto">M</a></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva])?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($f_facturas[monto_sin_retencion])?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($f_facturas[monto_con_retencion])?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($f_facturas[iva])?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print($f_facturas[iva_retenido])?></td>
		<td colspan=1 class="tdcampos"><?php echo montos_print(($f_facturas[monto_sin_retencion] + $f_facturas[monto_con_retencion] + $f_facturas[iva]) - $f_facturas[iva_retenido]) ?></td>
	</tr>
	<tr>
		<td colspan=9 class="tdtitulos"><hr></hr></td>
		
	</tr>
	<?php
	}
?>
</tr>
		<tr>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"></td>
		<td colspan=1 class="tdtitulos"><?php echo montos_print($total_iva_ret)?></td>
		<td colspan=1 class="tdtitulos"><?php echo montos_print($total_neto)?></td>
	</tr>
	
<tr>
<td colspan=9 class="tdtitulos"><hr></hr></td>
</tr>

</table>
<?php
}


	
