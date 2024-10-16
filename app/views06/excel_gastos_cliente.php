<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
header('Content-type: application/vnd.ms-excel');//poner cabezera de excel
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
header("Content-Disposition: attachment; filename=archivo$numealeatorio.xls");//Esta ya es la hoja excel con el numero aleatorio.xls
header("Pragma: no-cache");//Para que no utili la cahce
header("Expires: 0");

/*  Nombre del Archivo: reporte_gastos_cliente.php
   Descripción: Solicitar los datos para Reporte de Impresión: Relación de Gastos del Cliente
*/
   
	$poliza=$_REQUEST['poliza'];
	$fecha1=$_REQUEST['fecha1'];
	$fecha2=$_REQUEST['fecha2'];

	$q_poliza=("select coberturas_t_b.id_cobertura_t_b,coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario,propiedades_poliza.*, 				polizas.nombre_poliza, propiedades_poliza.id_poliza,coberturas_t_b 
		        from propiedades_poliza, polizas,coberturas_t_b 
		    where 
		 propiedades_poliza.id_poliza=polizas.id_poliza and  coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza and 			 coberturas_t_b.id_organo<=1 and coberturas_t_b.id_cobertura_t_b=$poliza order by propiedades_poliza.cualidad");
	$r_poliza=ejecutar($q_poliza);
	while($f_poliza=asignar_a($r_poliza, NULL, PGSQL_ASSOC)){?>

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0  width="100%"> 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="7">Relaci&oacute;n de Gastos de la Cobertura <?php echo "$f_poliza[cualidad] --  $f_poliza[nombre_poliza]" ;?>  </td>     
	</tr>
</table>	
 <br>

<?php $q_titular=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula,
			titulares.id_titular, entes.nombre, estados_clientes.estado_cliente			
			from clientes, titulares, entes, estados_clientes, estados_t_b
			where titulares.id_titular=$f_poliza[id_titular] and
				titulares.id_cliente= clientes.id_cliente and
				estados_t_b.id_beneficiario=0 and
				titulares.id_ente=entes.id_ente and 
				estados_t_b.id_titular=titulares.id_titular and
				estados_clientes.id_estado_cliente= estados_t_b.id_estado_cliente");

	$r_titular=ejecutar($q_titular);
	$f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC);



	$q_beneficiario=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula,titulares.id_titular,
			beneficiarios.id_titular, beneficiarios.id_beneficiario, entes.nombre, estados_clientes.estado_cliente
			
			from clientes, titulares, beneficiarios, entes, estados_clientes, estados_t_b
			where  beneficiarios.id_beneficiario=$f_poliza[id_beneficiario] and
				beneficiarios.id_cliente= clientes.id_cliente and
				titulares.id_ente=entes.id_ente and 
				estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
				estados_t_b.id_titular=titulares.id_titular and
				estados_clientes.id_estado_cliente= estados_t_b.id_estado_cliente");

	$r_beneficiario=ejecutar($q_beneficiario);
	$f_beneficiario=asignar_a($r_beneficiario,NULL,PGSQL_ASSOC);
?>



<table class="tabla_citas"  cellpadding=0 cellspacing=0  width="100%"> 
	
	<tr>
		<td class="tdtitulosd" colspan=3>&nbsp;</td>

		<td class="tdtitulosd" colspan=1>Relaci&oacute;n del <?php echo " $fecha1 al $fecha2 ";?>&nbsp;</td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>

	<tr> 
		<td class="tdtitulos"> Nombre y Apellido del Titular: </td>
		<td class="tdcampos"> <?php echo "$f_titular[nombres] $f_titular[apellidos]";?></td>
		<td class="tdtitulos">Ente:</td> 
		<td class="tdcampos"> <?php echo "$f_titular[nombre]";?></td>
	</tr>
	<tr>	
		<td class="tdtitulos">C&oacute;digo:</td> 
		<td class="tdcampos" ><?php echo "$f_titular[id_titular]"; ?></td>	
		<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr" ><?php echo "$f_titular[estado_cliente]";?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">C&eacute;dula del Titular:</td>
		<td class="tdcampos"> <?php echo "$f_titular[cedula]" ;?></td> 	
	</tr>

	<tr> 
		<td >&nbsp;</td>
	</tr>
<?php if($f_poliza[id_beneficiario]>0){?>
	<tr> 
		<td class="tdtitulos" > Nombre y Apellido del Beneficiario:</td>
		<td class="tdcampos" ><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]";?></td>
		<td class="tdtitulos">Ente:</td>
		<td class="tdcampos" > <?php echo "$f_beneficiario[nombre]";?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">C&oacute;digo:</td>
		<td class="tdcampos" ><?php echo "$f_beneficiario[id_beneficiario]"; ?></td>
		<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr" > <?php echo "$f_beneficiario[estado_cliente]";?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">C&eacute;dula del Beneficiario:</td>
		<td class="tdcampos" ><?php echo "$f_beneficiario[cedula]" ;?></td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>
<?php } ?>
</table>	


<table class="tabla_citas"  cellpadding=0 cellspacing=0  width="100%" > 

	<tr> 
		<td class="descrip_main">PROCESO</td> 
		<td class="descrip_main">ESTADO</td>     
		<td class="descrip_main">SERVICIO</td> 		
		<td class="descrip_main">FECHA RECIBIDO</td>     
		<td class="descrip_main">PROVEEDOR</td>   
		<td class="descrip_main">DESCRIPCI&Oacute;N</td> 
		<td class="descrip_main">MONTO ACEPTADO</td> 
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
		<td colspan=6 class=\"descrip_main\">Total &nbsp; </td>
		<td colspan=6 class=\"descrip_main\">";echo montos_print($totalmonto)." Bs.S.";?> </td>
	</tr>
<?php echo "<tr> 
		<td class=\"descrip_main\" colspan=7>An&aacute;lisis T&eacute;cnico: $f_gastos[comentarios] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Analista: $f_gastos[nombres]</td> 
				      
	</tr>
<tr><td colspan=7>&nbsp;</td></tr>
<tr><td colspan=7>&nbsp;</td></tr>";
}
 }

?>

	<tr> 

		<td class="descrip_main" colspan=7>TOTAL FINAL <?php echo montos_print($total_gastos)." Bs.S.";?>&nbsp;</td>
	</tr>

 </table>
 

