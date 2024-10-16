<?php
header("Content-Type: text/html;charset=utf-8");
/*  Nombre del Archivo: reporte_gastos_cliente.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de Gastos del Cliente
*/

	include ("../../lib/jfunciones.php");
	sesion();   
	$poliza=$_REQUEST['poliza'];
	$id_ente=$_REQUEST['id_ente'];
	$fecha1=$_REQUEST['fecha1'];
	$fecha2=$_REQUEST['fecha2'];
	$qid_ente=("select * from entes where entes.id_ente=$id_ente");
	$rid_ente=ejecutar($qid_ente);
	$fid_ente=asignar_a($rid_ente);

	$q_poliza=("select coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,propiedades_poliza.*, 
                polizas.nombre_poliza, propiedades_poliza.id_poliza 
                  from 
              propiedades_poliza, polizas,coberturas_t_b 
            where propiedades_poliza.id_poliza=polizas.id_poliza and  coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and coberturas_t_b.id_organo<=1 and coberturas_t_b.id_cobertura_t_b=$poliza order by propiedades_poliza.cualidad");
	$r_poliza=ejecutar($q_poliza);

	while($f_poliza=asignar_a($r_poliza, NULL, PGSQL_ASSOC)){
		$id_beneficiario=$f_poliza[id_beneficiario];
		?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 

	<tr>
		<td class="titulo_seccion" colspan="4">Relaci&oacute;n de Gastos de la Cobertura <?php echo "$f_poliza[cualidad] --  $f_poliza[nombre_poliza]" ;?>  </td>     
	</tr>
</table>
	
 <br>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	
	<tr> 
		<td class="tdtitulosd" colspan=4>Relaci&oacute;n del <?php echo " $fecha1 al $fecha2 ";?>&nbsp;</td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>


</table>	 	
<br>



<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows" > 

	<tr> 
		<td class="tdcampos">PROCESO</td> 
		<td class="tdcampos">ESTADO</td>     
		<td class="tdcampos">SERVICIO</td> 		
		<td class="tdcampos">FECHA RECIBIDO</td>     
		<td class="tdcampos">PROVEEDOR</td>   
		<td class="tdcampos">DESCRIPCION</td> 
		<td class="tdcampos">MONTO ACEPTADO</td> 
	</tr>

<?php $monto1=$f_poliza[monto_nuevo];
	
	$q_gastos=("select procesos.id_proceso, procesos.id_estado_proceso, procesos.fecha_recibido, procesos.comentarios, procesos.id_admin, servicios.servicio, admin.nombres, gastos_t_b.id_cobertura_t_b,gastos_t_b.id_proveedor, estados_procesos.estado_proceso,gastos_t_b.id_servicio, count(gastos_t_b.id_proceso) 
from procesos, servicios, admin, gastos_t_b, estados_procesos where gastos_t_b.id_cobertura_t_b=$f_poliza[id_cobertura_t_b] and
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.id_estado_proceso=estados_procesos.id_estado_proceso and
fecha_recibido>='$fecha1' and fecha_recibido<='$fecha2' and 
procesos.id_admin=admin.id_admin and
gastos_t_b.id_servicio=servicios.id_servicio 
group by procesos.id_proceso, procesos.id_estado_proceso, procesos.fecha_recibido, procesos.comentarios, procesos.id_admin, servicios.servicio, admin.nombres, gastos_t_b.id_cobertura_t_b,gastos_t_b.id_servicio,gastos_t_b.id_proveedor, estados_procesos.estado_proceso
order by procesos.fecha_recibido");
	$r_gastos=ejecutar($q_gastos);

$i=0;
	while($f_gastos=asignar_a($r_gastos, NULL, PGSQL_ASSOC)){
$i=$i+1;

$q_proveedor_per=("select proveedores.id_proveedor,proveedores.id_s_p_proveedor,s_p_proveedores.id_persona_proveedor, personas_proveedores.nombres_prov, personas_proveedores.apellidos_prov from proveedores, s_p_proveedores, personas_proveedores where proveedores.id_proveedor=$f_gastos[id_proveedor] and 
proveedores.id_s_p_proveedor=s_p_proveedores.id_s_p_proveedor and 
s_p_proveedores.id_persona_proveedor=personas_proveedores.id_persona_proveedor");
	$r_proveedor_per=ejecutar($q_proveedor_per);
	$f_proveedor_per=asignar_a($r_proveedor_per);


	$q_proveedor_cli=("select proveedores.id_proveedor,proveedores.id_clinica_proveedor,clinicas_proveedores.id_clinica_proveedor, clinicas_proveedores.nombre from proveedores, clinicas_proveedores where proveedores.id_proveedor=$f_gastos[id_proveedor] and 
proveedores.id_clinica_proveedor=clinicas_proveedores.id_clinica_proveedor ");
	$r_proveedor_cli=ejecutar($q_proveedor_cli);
	$f_proveedor_cli=asignar_a($r_proveedor_cli);



echo"
	<tr> 
		<td class=\"tdtituloss\">$f_gastos[id_proceso]</td>   
		<td class=\"tdtituloss\">$f_gastos[estado_proceso]</td>    
		<td class=\"tdtituloss\">$f_gastos[servicio]</td> 		
		<td class=\"tdtituloss\">$f_gastos[fecha_recibido]</td>
		<td class=\"tdtituloss\">$f_proveedor_per[nombres_prov] $f_proveedor_per[apellidos_prov] $f_proveedor_cli[nombre]</td>
	</tr>"; 



	$q_gastos1=("select gastos_t_b.id_cobertura_t_b,gastos_t_b.id_servicio, gastos_t_b.nombre, gastos_t_b.descripcion, gastos_t_b.id_proveedor,gastos_t_b.id_proceso, gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_cobertura_t_b=$f_gastos[id_cobertura_t_b] and gastos_t_b.id_proceso=$f_gastos[id_proceso]");
	$r_gastos1=ejecutar($q_gastos1);

$totalmonto=0;
	while($f_gastos1=asignar_a($r_gastos1, NULL, PGSQL_ASSOC)){

$total_gastos=$total_gastos + ($f_gastos1[monto_aceptado]);

	

echo"		
	<tr>
		<td class=\"tdtituloss\" colspan=5></td>
		<td class=\"tdtituloss\" colspan=1>$f_gastos1[nombre] ($f_gastos1[descripcion])</td> 		     
		<td class=\"tdtituloss\" colspan=1>"; echo montos_print($f_gastos1[monto_aceptado])." Bs.S.";?></td> 
	</tr>
<?php

$totalmonto=$totalmonto + ($f_gastos1[monto_aceptado]);
 }
 echo"
	<tr>
		<td colspan=6 class=\"tdtitulosd\">Total &nbsp; </td>
		<td colspan=6 class=\"tdtitulosd\">";echo montos_print($totalmonto)." Bs.S.";?> </td>
	</tr>
<?php echo "<tr> 
		<td class=\"tdcampos\" colspan=7>An&aacute;lisis T&eacute;cnico: $f_gastos[comentarios] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Analista: $f_gastos[nombres]</td> 
				      
	</tr>
<tr><td colspan=7>&nbsp;</td></tr>
<tr><td colspan=7>&nbsp;</td></tr>";
}
 }

?>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0  > 
<tr> 

		<td class="tdcampos" colspan=7>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL FINAL <?php echo montos_print($total_gastos)."  Bs.S.";?>&nbsp;</td>
	</tr>
	<tr>
	<?php 
	/*  se coloca en la variable fecha inicio y fecha fin las fechas de contratacion del ente*/
	if ($id_beneficiario==0){
		$fechaini=$fid_ente[fecha_inicio_contrato];
		$fechafin=$fid_ente[fecha_renovacion_contrato];
		}
		else
		{
		$fechaini=$fid_ente[fecha_inicio_contratob];
		$fechafin=$fid_ente[fecha_renovacion_contratob];
			}

	if ($fecha1<$fechaini ){		
	?>
	<td class="tdcamposr" colspan=5>GASTOS DE COBERTURAS ANTERIORES SI VA A CARGAR ALGUN GASTO DE COBERTURAS PASADAS CONSULTAR LOS MISMOS ENTRE LAS FECHAS DE CONTRATACION CORRESPONDIENTE Y VERIFICAR LO DISPONIBLE PARA ESA FECHA</td>
	<?php
	}
	else
	{
	?>
	<td class="tdcampos" colspan=5>&nbsp;&nbsp; COBERTURA: <?php echo montos_print($monto1)."  Bs.S.";?> &nbsp;&nbsp;SALDO DISPONIBLE: <?php echo montos_print($monto1 - $total_gastos)."  Bs.";?> </td>
	<?php
	}
	?>
	<td class="tdcampos" colspan=2>&nbsp;&nbsp; HAY UN TOTAL DE <?php echo $i;?> ORDENES </td>
	</tr> 
	<tr>	<td colspan=7>&nbsp;</td>
	</tr>
</table>

<br>	

<tr>
	        <td colspan=7 class="tdcamposc" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_gastos_cliente.php?fecha1=$fecha1&fecha2=$fecha2&poliza=$poliza&id_ente=$id_ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
			  <?php
			$url="'views06/excel_gastos_cliente.php?fecha1=$fecha1&fecha2=$fecha2&poliza=$poliza&id_ente=$id_ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>
			</td>
	</tr>
	<tr> 
		<td colspan="7">&nbsp;</td>
	</tr>
 





