<?php 
include ("../../lib/jfunciones.php"); 
sesion(); 
$orden=$_POST['orden']; 
$factura=$_POST['factura']; 
$serie=$_POST['serie']; 
$serie = strtoupper($serie);

if($orden.value.length!=0){

$q_bus_orden=("select procesos.fecha_modificado,procesos.fecha_creado from procesos where procesos.id_proceso='$orden' "); 
$r_bus_orden=ejecutar($q_bus_orden); 

?> 

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td class="titulo_seccion" colspan="19"> REPORTE SEGUIR PROCESOS </td>

<tr><td>&nbsp;</td></tr>
</table>	


<table class="tabla_citas"  cellpadding=0 cellspacing=0  border=3> 

<?php

	while($f_bus_orden=asignar_a($r_bus_orden)){

$q_bus_cambios=("select logs.*, admin.nombres, admin.apellidos from logs, admin where 
admin.id_admin=logs.id_admin 
and logs.fecha_creado >='$f_bus_orden[fecha_creado]' and 
(logs.log like '%ORDEN NUMERO $orden%' OR
 logs.log like '%ORDEN $orden%' OR 
 logs.log like '%ORDEN NUM $orden%' OR 
 logs.log like '%ORDEN CON NUM $orden%' OR 
 logs.log like '%ORDEN CON NUMERO $orden%' OR 
 logs.log like '%PROCESO NO. $orden%' OR 
 logs.log like '%PROCESO NO.$orden%' OR 
 logs.log like '%ORDEN EN ESPERA $orden%')
  order by logs.id_log asc "); 
$r_bus_cambios=ejecutar($q_bus_cambios); 



/*echo $q_bus_cambios;*/
?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
		<td class="tdcampos">CONCEPTO</td>   
		<td class="tdcampos">ANALISTA</td>     
		<td class="tdcampos">FECHA CREADO</td> 
	</tr>
<?php

	while($f_bus_cambios=asignar_a($r_bus_cambios)){
  echo "
            <tr> 

		    <td class=\"tdtituloss\">$f_bus_cambios[log]</td>   
	            <td class=\"tdtituloss\">$f_bus_cambios[nombres] $f_bus_cambios[apellidos]</td>
		    <td class=\"tdtituloss\">$f_bus_cambios[fecha_creado]</td>  
	                 
	    </tr>";
}}}

?>

<tr><td>&nbsp;</td></tr>


<?php
if($factura.value.length!=0){
echo $factura;
echo $serie;
$q_bus_factura=("select tbl_facturas.fecha_emision, tbl_series.id_sucursal from tbl_facturas,tbl_series where tbl_facturas.numero_factura='$factura' and tbl_facturas.id_serie=tbl_series.id_serie and tbl_series.nomenclatura='$serie' "); 
$r_bus_factura=ejecutar($q_bus_factura); 
echo $q_bus_factura;
?> 

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> <td colspan=4>&nbsp;</td></tr>
	<tr>
		<td class="titulo_seccion" colspan="19"> REPORTE SEGUIR FACTURAS </td>

<tr><td>&nbsp;</td></tr>
</table>	


<table class="tabla_citas"  cellpadding=0 cellspacing=0  border=3> 

<?php

	while($f_bus_factura=asignar_a($r_bus_factura)){

$q_bus_cambios_fac=("select logs.*, admin.nombres, admin.apellidos 
from logs, admin where 
logs.log like ('%Factura numero $factura%') and 
admin.id_admin=logs.id_admin and 
logs.fecha_creado >='$f_bus_factura[fecha_emision]' and
admin.id_sucursal='$f_bus_factura[id_sucursal]' 
  order by logs.id_log asc "); 
$r_bus_cambios_fac=ejecutar($q_bus_cambios_fac); 



?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
		<td class="tdcampos">CONCEPTO</td>   
		<td class="tdcampos">ANALISTA</td>     
		<td class="tdcampos">FECHA CREADO</td> 
	</tr>
<?php

	while($f_bus_cambios_fac=asignar_a($r_bus_cambios_fac)){
  echo "
            <tr> 

		    <td class=\"tdtituloss\">$f_bus_cambios_fac[log]</td>   
	            <td class=\"tdtituloss\">$f_bus_cambios_fac[nombres] $f_bus_cambios_fac[apellidos]</td>
		    <td class=\"tdtituloss\">$f_bus_cambios_fac[fecha_creado]</td>  
	                 
	    </tr>";
}}}

?>

<tr><td>&nbsp;</td></tr>





