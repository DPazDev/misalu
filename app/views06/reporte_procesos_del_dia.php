<?php
header("Content-Type: text/html;charset=utf-8");
/* Nombre del Archivo: reporte_entpriv.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación Entes Privados
*/ 

   include ("../../lib/jfunciones.php");
   sesion();


list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="and sucursales.id_sucursal>0";
else
$condicion_sucursal="and sucursales.id_sucursal='$id_sucursal'";

$fecha = date("Y-m-d");


/*echo "$sucursal";
echo "$fecha";*/

// BUSQUEDA DE PROCESOS POR ESTADO Y ENTE

$q_proceso=("select procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.id_servicio,servicios.servicio,entes.nombre, count(distinct procesos.id_proceso) 
from procesos,admin, estados_procesos,sucursales,servicios,gastos_t_b,entes,titulares
where 
procesos.fecha_recibido='$fecha' and 
gastos_t_b.id_servicio=servicios.id_servicio and
gastos_t_b.id_proceso=procesos.id_proceso and
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and 
estados_procesos.id_estado_proceso=procesos.id_estado_proceso and
procesos.id_titular=titulares.id_titular and
entes.id_ente=titulares.id_ente
group by
procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.id_servicio,servicios.servicio,entes.nombre order by estados_procesos.estado_proceso");

$r_proceso=ejecutar($q_proceso);
?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="4">Relaci&oacute;n Ordenes por Entes, Ejecutadas el día de Hoy  <?php 
echo "$fecha";?> </td>     
	</tr>
</table>	
  <tr><td>&nbsp;</td></tr>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		
	<tr> 
		<td class="tdcamposc"><?php  echo $sucursal;?></td>
	</tr>
  <tr><td>&nbsp;</td></tr>
</table>	 	

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 

		<td class="tdcampos">ESTADO PROCESO</td>   
		<td class="tdcampos">SERVICIO</td>     
		<td class="tdcampos">ENTE</td> 
		<td class="tdcampos">CANTIDAD</td> 
		     
	</tr>
	<?php
$HH=0;
$JJ=0;

 while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC))

		{

$estado="$f_proceso[id_estado_proceso]";
$servicio="$f_proceso[id_servicio]";


if($estado<>$HH ){
$HH=$estado;
$JJ=$servicio;		  
echo"
        <tr> 

		    <td class=\"tdtituloss\">$f_proceso[estado_proceso]</td>   
	            <td class=\"tdtituloss\">$f_proceso[servicio]</td>
		    <td class=\"tdtituloss\">$f_proceso[nombre]</td>  
	            <td class=\"tdtituloss\">$f_proceso[count]</td> 
	                       
	</tr>";

	}

else if($estado=="$HH" && $servicio=="$JJ") {
$HH=$estado;
$JJ=$servicio;	
echo"
	<tr> 
		    <td class=\"tdtituloss\">  </td>   
	            <td class=\"tdtituloss\">  </td>
		    <td class=\"tdtituloss\">$f_proceso[nombre]</td>  
	            <td class=\"tdtituloss\">$f_proceso[count]</td> 	                      
	</tr>";

	}

else if($estado=="$HH" && $servicio!="$JJ") {
$HH=$estado;
$JJ=$servicio;

echo"
	<tr> 

		    <td class=\"tdtituloss\">  </td>   
	            <td class=\"tdtituloss\">$f_proceso[servicio]</td>
		    <td class=\"tdtituloss\">$f_proceso[nombre]</td>  
	            <td class=\"tdtituloss\">$f_proceso[count]</td> 
	                       
	</tr>";

	}}
?>
</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
		<td class="tdcampos">ESTADO PROCESO</td>   
		<td class="tdcampos">SERVICIO</td>     
		<td class="tdcampos">CANTIDAD</td>     
	</tr>

<?php
$q_general=("select procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.id_servicio,servicios.servicio, count(distinct procesos.id_proceso) 
from procesos,admin, estados_procesos,sucursales,servicios,gastos_t_b,entes,titulares
where 
procesos.fecha_recibido='$fecha' and 
gastos_t_b.id_servicio=servicios.id_servicio and
gastos_t_b.id_proceso=procesos.id_proceso and
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and 
estados_procesos.id_estado_proceso=procesos.id_estado_proceso and
procesos.id_titular=titulares.id_titular and
entes.id_ente=titulares.id_ente
group by
procesos.id_estado_proceso,estados_procesos.estado_proceso,gastos_t_b.id_servicio,servicios.servicio order by estados_procesos.estado_proceso");

$r_general=ejecutar($q_general);
 while($f_general=asignar_a($r_general,NULL,PGSQL_ASSOC)){
	
 echo"
        <tr> 
		    <td class=\"tdtituloss\">$f_general[estado_proceso]</td>   
	            <td class=\"tdtituloss\">$f_general[servicio]</td>
	            <td class=\"tdtituloss\">$f_general[count]</td> 
	                       
	</tr>";
	}
?>
</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>
<?php
// FIN BUSQUEDA DE PROCESOS POR ESTADO Y ENTE


// BUSQUEDA DE EXAMENES DE LABORATORIO POR ENTE


$q_examen=("select entes.id_ente,entes.nombre, gastos_t_b.descripcion, count(gastos_t_b.descripcion) 
from procesos, servicios,gastos_t_b,entes,titulares,tipos_examenes_bl,examenes_bl,admin,sucursales
where 
procesos.fecha_recibido='$fecha' and 
gastos_t_b.id_servicio=servicios.id_servicio and
gastos_t_b.id_proceso=procesos.id_proceso and
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and
procesos.id_titular=titulares.id_titular and
entes.id_ente=titulares.id_ente and
tipos_examenes_bl.id_tipo_examen_bl='3' and
gastos_t_b.nombre=tipos_examenes_bl.tipo_examen_bl and
gastos_t_b.descripcion=examenes_bl.examen_bl

group by
entes.id_ente,entes.nombre,gastos_t_b.descripcion order by entes.nombre");
$r_examen=ejecutar($q_examen);

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="4">Relaci&oacute;n Ordenes de Examenes de Laboratorio por Entes, Ejecutadas el día de Hoy   <?php echo "$fecha";?> </td>     
	</tr>
</table>	
  <tr><td>&nbsp;</td></tr>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 

		<td class="tdcampos">ENTE</td>   
		<td class="tdcampos">TIPO DE EXAMEN</td>     
		<td class="tdcampos">CANTIDAD</td> 
		     
	</tr>
	<?php
$AA=0;

 while($f_examen=asignar_a($r_examen,NULL,PGSQL_ASSOC))

		{

$ente="$f_examen[id_ente]";

if($ente<>$AA ){
$AA=$ente;

echo"
        <tr>
		    <td class=\"tdtituloss\">$f_examen[nombre]</td>   
	            <td class=\"tdtituloss\">$f_examen[descripcion]</td>
		    <td class=\"tdtituloss\">$f_examen[count]</td>  	                       
	</tr>";
	}
else if ($ente==$AA){

$AA=$ente;
echo"
	<tr> 
		    <td class=\"tdtituloss\">  </td>   
	            <td class=\"tdtituloss\">$f_examen[descripcion]</td>
		    <td class=\"tdtituloss\">$f_examen[count]</td>  	                      
	</tr>";
	}}?>
</table>

</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
		<td class="tdcampos">EXAMEN</td>     
		<td class="tdcampos">CANTIDAD</td>     
	</tr>
<?php
$q_exam=("select gastos_t_b.descripcion, count(gastos_t_b.descripcion)
from procesos,admin,sucursales,gastos_t_b,tipos_examenes_bl,examenes_bl
where 
procesos.fecha_recibido='$fecha' and 

gastos_t_b.id_proceso=procesos.id_proceso and

procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and 



tipos_examenes_bl.id_tipo_examen_bl='3' and
gastos_t_b.nombre=tipos_examenes_bl.tipo_examen_bl and
gastos_t_b.descripcion=examenes_bl.examen_bl

group by
gastos_t_b.descripcion order by count DESC");
$r_exam=ejecutar($q_exam);
 while($f_exam=asignar_a($r_exam,NULL,PGSQL_ASSOC)){
	
 echo"
        <tr> 
		    <td class=\"tdtituloss\">$f_exam[descripcion]</td>   
	            <td class=\"tdtituloss\">$f_exam[count]</td>
	                       
	</tr>";
	}
?>
</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>
<?php


// FIN BUSQUEDA DE EXAMENES DE LABORATORIO POR ENTE


// BUSQUEDA DE PROCESOS FACTURADOS

$q_facturas=("select tbl_facturas.id_estado_factura,
tbl_facturas.numero_factura,tbl_facturas.numcontrol, entes.nombre,(tbl_series.nombre) AS serie, tbl_series.nomenclatura,count(distinct tbl_procesos_claves.monto), sum(distinct tbl_procesos_claves.monto)
from procesos,admin, sucursales,servicios,tbl_procesos_claves,entes,titulares,tbl_facturas,tbl_series
where 
tbl_facturas.fecha_emision='$fecha' and 
procesos.id_proceso=tbl_procesos_claves.id_proceso and
tbl_procesos_claves.id_factura=tbl_facturas.id_factura and
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and 
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal and 
procesos.id_titular=titulares.id_titular and
entes.id_ente=titulares.id_ente and 
tbl_series.id_sucursal=sucursales.id_sucursal and 
tbl_series.id_serie=tbl_facturas.id_serie 
group by
tbl_facturas.id_estado_factura,tbl_facturas.numero_factura,tbl_facturas.numcontrol,entes.nombre,serie,tbl_series.nomenclatura order by tbl_facturas.id_estado_factura ");

$r_facturas=ejecutar($q_facturas);

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="4">Relaci&oacute;n Ordenes Facturadas por Entes, Ejecutadas el día de Hoy   <?php echo "$fecha";?> </td>     
	</tr>
</table>	
  <tr><td>&nbsp;</td></tr>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 

		<td class="tdcampos">ESTADO FACTURA &nbsp; &nbsp;&nbsp; &nbsp;</td>   
		<td class="tdcampos">NUM. FACTURA &nbsp; &nbsp;&nbsp; &nbsp; </td> 
		<td class="tdcampos">CONTROL FACTURA &nbsp; &nbsp;&nbsp; &nbsp;</td>         
		<td class="tdcampos">ENTE &nbsp; &nbsp;&nbsp; &nbsp;</td> 
		<td class="tdcampos">SUCURSAL  &nbsp; &nbsp;&nbsp; &nbsp;</td>   
		<td class="tdcampos">SERIE &nbsp; &nbsp;&nbsp; &nbsp; </td>  

		<td class="tdcampos"> PROCESOS &nbsp; &nbsp;&nbsp; &nbsp;</td> 

		<td class="tdcampos">MONTO (Bs.S) &nbsp; &nbsp;&nbsp; &nbsp;</td> 

		     
	</tr>
	<?php
$BB=0;

  while($f_facturas=asignar_a($r_facturas,NULL,PGSQL_ASSOC)){

if($f_facturas[id_estado_factura]=='1'){
	$estado_fact="PAGADO";
	}
 	else
if($f_facturas[id_estado_factura]=='2'){
	$estado_fact="POR COBRAR";
	}
if($f_facturas[id_estado_factura]=='3'){
	$estado_fact="ANULADAS";
	}
 	else
if($f_facturas[id_estado_factura]=='4'){
	$estado_fact="POR FACTURAR";
	}
	
$est="$f_facturas[id_estado_factura]";

if($est<>$BB ){
$BB=$est;


 echo"
        <tr> 
		    <td class=\"tdtituloss\">$estado_fact  &nbsp; &nbsp; </td>   
	            <td class=\"tdtituloss\">$f_facturas[numero_factura] &nbsp; &nbsp;</td>
 		    <td class=\"tdtituloss\">$f_facturas[numcontrol] &nbsp; &nbsp;</td>   
	            <td class=\"tdtituloss\">$f_facturas[nombre] &nbsp; &nbsp;</td>
 		    <td class=\"tdtituloss\">$f_facturas[serie] &nbsp; &nbsp;&nbsp; &nbsp;</td>   
	            <td class=\"tdtituloss\">$f_facturas[nomenclatura] &nbsp; &nbsp;</td> 

		    <td class=\"tdtituloss\">$f_facturas[count] &nbsp; &nbsp;</td> 

	            <td class=\"tdtituloss\">$f_facturas[sum]</td>
	                       
	</tr>";
	}
else if ($est==$BB){

$BB=$est;
echo"
        <tr> 
		    <td class=\"tdtituloss\">   </td>   
	            <td class=\"tdtituloss\">$f_facturas[numero_factura]</td>
 		    <td class=\"tdtituloss\">$f_facturas[numcontrol]</td>   
	            <td class=\"tdtituloss\">$f_facturas[nombre]</td>
 		    <td class=\"tdtituloss\">$f_facturas[serie]</td>   
	            <td class=\"tdtituloss\">$f_facturas[nomenclatura]</td> 

		    <td class=\"tdtituloss\">$f_facturas[count]</td> 

	            <td class=\"tdtituloss\">$f_facturas[sum]</td>
	                       
	</tr>";
	}}

?>
</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>


<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 
		<td  class="tdcampos">ESTADO FACTURA</td>     
		<td  class="tdcampos">MONTO (Bs.S.)</td>     
	</tr>
<?php

$q_fact=("select tbl_facturas.id_estado_factura, sum(distinct tbl_procesos_claves.monto)
from procesos,admin, sucursales,servicios,tbl_procesos_claves,entes,titulares,tbl_facturas,tbl_series
where 
tbl_facturas.fecha_emision='$fecha' and 
procesos.id_proceso=tbl_procesos_claves.id_proceso and
tbl_procesos_claves.id_factura=tbl_facturas.id_factura and
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal $condicion_sucursal and 
procesos.id_admin=admin.id_admin and 
admin.id_sucursal=sucursales.id_sucursal and 
procesos.id_titular=titulares.id_titular and
entes.id_ente=titulares.id_ente and 
tbl_series.id_sucursal=sucursales.id_sucursal and 
tbl_series.id_serie=tbl_facturas.id_serie 
group by
tbl_facturas.id_estado_factura order by tbl_facturas.id_estado_factura ");

$r_fact=ejecutar($q_fact);



  while($f_fact=asignar_a($r_fact,NULL,PGSQL_ASSOC)){

if($f_fact[id_estado_factura]=='1'){
	$edo_fact="PAGADO";
	}
 	else
if($f_fact[id_estado_factura]=='2'){
	$edo_fact="POR COBRAR";
	}
if($f_fact[id_estado_factura]=='3'){
	$edo_fact="ANULADAS";
	}
 	else
if($f_fact[id_estado_factura]=='4'){
	$edo_fact="POR FACTURAR";
	}
	

 echo"
        <tr> 
		    <td class=\"tdtituloss\">$edo_fact</td>   
	           
	            <td class=\"tdtituloss\">$f_fact[sum]</td>
	                       
	</tr>";
	}

?>
</table>

<table>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>
</table>
<?php


// FIN BUSQUEDA DE PROCESOS FACTURADOS
