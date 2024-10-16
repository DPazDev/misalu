<?php
/* Nombre del Archivo: ireporte_clientes_x_cobertura.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación Gastos de Clientes por Coberturas, de un determinado Ente */


    include ("../../lib/jfunciones.php");
    sesion();

/* Seleccionar la información en la base de datos, para utilizar las variables en el formulario */
    $ente=$_REQUEST[ente];
    $q_ente=("select entes.nombre from entes where entes.id_ente=$ente");
    $r_ente=ejecutar($q_ente);
    $f_ente=asignar_a($r_ente);

    $poliza=$_REQUEST[poliza];
    $q_poliza=("select propiedades_poliza.*, polizas.nombre_poliza from polizas, propiedades_poliza where		 
		propiedades_poliza.id_poliza=polizas.id_poliza and
		propiedades_poliza.id_propiedad_poliza=$poliza");
    $r_poliza=ejecutar($q_poliza);
    $f_poliza=asignar_a($r_poliza);

    $monto=$_REQUEST[monto];


	$r_procesot=("select clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, 
coberturas_t_b.id_titular, coberturas_t_b.id_beneficiario, coberturas_t_b.monto_actual, 
propiedades_poliza.cualidad from 
clientes,coberturas_t_b,propiedades_poliza,titulares where 
coberturas_t_b.id_propiedad_poliza=$poliza and
coberturas_t_b.id_titular=titulares.id_titular and
titulares.id_ente=$ente and
clientes.id_cliente=titulares.id_cliente and
coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza
order by coberturas_t_b.id_titular,coberturas_t_b.id_beneficiario ");
	$q_procesot=ejecutar($r_procesot);
?>

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="11">Reporte Relaci&oacute;n Clientes, Cobertura <?php echo "$f_poliza[cualidad] -- $f_poliza[nombre_poliza]";?>, monto menor o igual <?php echo formato_montos ($monto)."Bs.S.";?>, del Ente <?php echo "$f_ente[nombre] ";?> </td>     
        </tr>
	
 <br>

	<tr> 
	   	<td class="descrip_main">CLIENTE</td>  
	   	<td class="descrip_main">TITULAR</td> 
	   	<td class="descrip_main">CEDULA TITULAR</td> 
		<td class="descrip_main">CLIENTE</td>  
	   	<td class="descrip_main">BENEFICIARIO</td> 
	   	<td class="descrip_main">CEDULA BENEFICIARIO</td> 
	   	<td class="descrip_main">CELULAR</td> 
	   	<td class="descrip_main">TLF.</td>
	   	<td class="descrip_main">PARENTESCO</td> 
	   	<td class="descrip_main">MONTO DISPONIBLE</td>
	</tr>

<?php
  	$t=0;
	$b=0;	
	     while($f_procesot=asignar_a($q_procesot,NULL,PGSQL_ASSOC))
		{
if($f_procesot[id_beneficiario]==0 && $f_procesot[monto_actual] <= $monto){
$t=$t+ 1;
echo "
	<tr>	<td class=\"tdtituloss\">$f_procesot[id_titular]</td> 
		<td class=\"tdtituloss\">$f_procesot[nombres] $f_procesot[apellidos]</td> 
		<td class=\"tdtituloss\">$f_procesot[cedula]</td> 
		<td class=\"tdtituloss\" colspan=3>&nbsp;</td> 
		<td class=\"tdtituloss\">$f_procesot[celular]</td> 
		<td class=\"tdtituloss\">$f_procesot[telefono_hab]</td> 
		<td class=\"tdtituloss\" colspan=1>&nbsp;</td> 
		<td class=\"tdtituloss\" colspan=2>";?> <?php echo montos_print($f_procesot[monto_actual])."Bs.S.";?></td> 
	   		
</tr>
<?php
 }
	
	$r_procesob=("select beneficiarios.id_beneficiario,clientes.nombres, clientes.apellidos, clientes.cedula, clientes.telefono_hab, clientes.celular, parentesco.parentesco from clientes,parentesco,beneficiarios where 
beneficiarios.id_titular=$f_procesot[id_titular] and
beneficiarios.id_beneficiario=$f_procesot[id_beneficiario] and
beneficiarios.id_parentesco=parentesco.id_parentesco and
clientes.id_cliente=beneficiarios.id_cliente 
order by beneficiarios.id_beneficiario");

	$q_procesob=ejecutar($r_procesob);
			


	     while($f_procesob=asignar_a($q_procesob,NULL,PGSQL_ASSOC))
		{
if($f_procesot[id_beneficiario]>0 && $f_procesot[monto_actual] <= $monto){
$b=$b+1;
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
		<td class=\"tdtituloss\"colspan=2> ";?> <?php echo montos_print($f_procesot[monto_actual])."Bs.S.";?></td> 
	   		
</tr>

<?php }}} ?>
	<tr>
		<td colspan=13>&nbsp; </td>
	</tr>
	<tr>

	        <td colspan=13 class="descrip_main" > &nbsp;&nbsp;&nbsp;Hay un total de <?php echo $t+$b; ?> Clientes, <?php echo $t; ?> Titulares y <?php echo $b; ?> Beneficiarios</td>
	   	
     	</tr> 
	<br> 
	<tr>
		<td colspan=13>&nbsp; </td>
	</tr>
	<br> 
	<tr>
		<td colspan=13>&nbsp; </td>
	</tr>
	<br>
	<tr>
	        <td  colspan=4 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=4 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=4 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>    

</table>

