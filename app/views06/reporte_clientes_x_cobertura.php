<?php
/* Nombre del Archivo: reporte_clientes_x_cobertura.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación Gastos de Clientes por Coberturas, de un determinado Ente */


    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $ente=$_REQUEST[ente];
    $q_ente=("select entes.nombre from entes where entes.id_ente='$ente'");
    $r_ente=ejecutar($q_ente);
    $f_ente=asignar_a($r_ente);

    $poliza=$_REQUEST[poliza]; 
    $q_poliza=("select propiedades_poliza.cualidad,propiedades_poliza.id_propiedad_poliza,propiedades_poliza.id_poliza, polizas.nombre_poliza from polizas, propiedades_poliza where		 
		propiedades_poliza.id_poliza=polizas.id_poliza and
		propiedades_poliza.id_propiedad_poliza='$poliza'");
    $r_poliza=ejecutar($q_poliza);
    $f_poliza=asignar_a($r_poliza);

    $monto=$_REQUEST[monto];
/*
echo $ente;
echo "****";
echo $poliza;
echo "****";
echo $monto;
echo "****";
echo $f_poliza[nombre_poliza];*/

	$r_procesot=("select clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, 
coberturas_t_b.id_titular, coberturas_t_b.id_beneficiario, coberturas_t_b.monto_actual, propiedades_poliza.cualidad 
from 
    clientes,coberturas_t_b,propiedades_poliza,titulares 
where 
coberturas_t_b.id_propiedad_poliza='$poliza' and
coberturas_t_b.id_titular=titulares.id_titular and
titulares.id_ente='$ente' and
clientes.id_cliente=titulares.id_cliente and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
order by coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario  ");
	$q_procesot=ejecutar($r_procesot);
?>


<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="13">Reporte Relación Clientes, Cobertura <?php echo "$f_poliza[cualidad] -- $f_poliza[nombre_poliza]";?>, monto menor o igual <?php echo formato_montos ($monto)."Bs.S.";?>, del Ente <?php echo "$f_ente[nombre] ";?> </td>     
        </tr>
</table>
	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	
	<tr> 
	   	<td class="tdcampos">CLIENTE</td>  
	   	<td class="tdcampos">TITULAR</td> 
	   	<td class="tdcampos">CEDULA TITULAR</td> 
	   	<td class="tdcampos">CLIENTE</td>  
	   	<td class="tdcampos">BENEFICIARIO</td> 
	   	<td class="tdcampos">CEDULA BENEFICIARIO</td> 
	   	<td class="tdcampos">CELULAR</td> 
	   	<td class="tdcampos">TLF.</td>
	   	<td class="tdcampos">PARENTESCO</td> 
	   	<td class="tdcampos">MONTO DISPONIBLE</td>
	</tr>

<?php
  	$t=0;
	$b=0;
	     while($f_procesot=asignar_a($q_procesot,NULL,PGSQL_ASSOC))
		{
if($f_procesot[id_beneficiario]==0 && $f_procesot[monto_actual] <= $monto){
$t=$t + 1;
echo "
	<tr>	<td class=\"tdtituloss\">$f_procesot[id_titular]</td> 
		<td class=\"tdtituloss\">$f_procesot[nombres] $f_procesot[apellidos]</td> 
		<td class=\"tdtituloss\">$f_procesot[cedula]</td>
		<td class=\"tdtituloss\" colspan=3>&nbsp;</td> 
		<td class=\"tdtituloss\">$f_procesot[celular]</td> 
		<td class=\"tdtituloss\">$f_procesot[telefono_hab]</td> 
		<td class=\"tdtituloss\" colspan=1>&nbsp;</td> 
		<td class=\"tdtituloss\" colspan=2>";?> <?php echo montos_print($f_procesot[monto_actual])." Bs.S.";?></td> 
	   		
</tr>
<?php
 }
	
	$r_procesob=("select beneficiarios.id_beneficiario,clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, parentesco.parentesco from clientes,parentesco,beneficiarios where 
beneficiarios.id_titular='$f_procesot[id_titular]' and
beneficiarios.id_beneficiario='$f_procesot[id_beneficiario]' and
beneficiarios.id_parentesco=parentesco.id_parentesco and
clientes.id_cliente=beneficiarios.id_cliente 
order by beneficiarios.id_beneficiario");
	$q_procesob=ejecutar($r_procesob);
			
	     while($f_procesob=asignar_a($q_procesob,NULL,PGSQL_ASSOC))
		{

if($f_procesot[id_beneficiario]>0 && $f_procesot[monto_actual] <= $monto){
$b=$b + 1;
echo "
	<tr>	<td class=\"tdtituloss\">$f_procesot[id_titular]</td> 
		<td class=\"tdtituloss\">$f_procesot[nombres] $f_procesot[apellidos]</td> 
		<td class=\"tdtituloss\">$f_procesot[cedula]</td> 
		<td class=\"tdtituloss\">$f_procesob[id_beneficiario]</td> 
		<td class=\"tdtituloss\">$f_procesob[nombres] $f_procesob[apellidos]</td> 
		<td class=\"tdtituloss\">$f_procesob[cedula]</td> 
		<td class=\"tdtituloss\">$f_procesob[celular]</td> 
		<td class=\"tdtituloss\">$f_procesob[telefono_hab]</td>
		<td class=\"tdtituloss\">$f_procesob[parentesco]</td>
		<td class=\"tdtituloss\" colspan=2>";?> <?php echo montos_print($f_procesot[monto_actual])." Bs.S.";?></td> 
	   		
</tr> <?php

}

}}

?>
	<tr>
		<td colspan=13>&nbsp; </td>
	</tr>
	<tr>
	        <td colspan=13 class="tdcampos" > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HAY UN TOTAL DE <?php echo $t+$b; ?> CLIENTES, <?php echo $t; ?> TITULARES Y <?php echo $b; ?> BENEFICIARIOS  </td>
	       
	</tr>
</table>
  
<br>
	<tr>
	        <td colspan=13 class="tdcamposs" title="Imprimir reporte">
	<?php
		$url="'views06/ireporte_clientes_x_cobertura.php?ente=$ente&poliza=$poliza&monto=$monto'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a><?php
		$url="'views06/excel_clientes_x_cobertura.php?ente=$ente&poliza=$poliza&monto=$monto'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>
		</td>
	</tr> 
<br>
</table>
