<?php

/* Nombre del Archivo: ireporte_consulta_preventiva.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación Consultas Preventivas
*/ 

   include ("../../lib/jfunciones.php");
   sesion();
   $fecha1=$_REQUEST['fecha1'];
   $fecha2=$_REQUEST['fecha2'];
   $ci=$_REQUEST['ci']; 

$q_cliente=("select clientes.cedula,clientes.id_cliente from clientes where clientes.cedula='$ci'");
$r_cliente=ejecutar($q_cliente);
$f_cliente=asignar_a($r_cliente);

$q_ti=("select clientes.cedula, titulares.id_cliente from clientes, titulares where clientes.cedula='$ci' and titulares.id_cliente=clientes.id_cliente ");
$r_ti=ejecutar($q_ti);
$f_ti=asignar_a($r_ti);

$q_be=("select clientes.cedula, beneficiarios.id_cliente from clientes, beneficiarios where clientes.cedula='$ci' and beneficiarios.id_cliente=clientes.id_cliente ");
$r_be=ejecutar($q_be);
$f_be=asignar_a($r_be);
  
?>

<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
	
<?php if($f_cliente['id_cliente']==$f_ti['id_cliente']){

	$q_titular=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula, titulares.id_cliente, titulares.id_titular, titulares.id_ente, entes.nombre,estados_clientes.estado_cliente from estados_clientes, clientes, titulares, entes,estados_t_b where clientes.cedula='$ci' and
titulares.id_cliente=clientes.id_cliente and
titulares.id_ente=entes.id_ente and estados_t_b.id_titular=titulares.id_titular and estados_t_b.id_beneficiario=0 and
estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente");
	$r_titular=ejecutar($q_titular);

		while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC)){?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%"> 

	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="7">Reporte Relaci&oacute;n Consultas Preventivas del Cliente como Titular</td>     
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0  width="80%"> 
	<tr> 
		<td >&nbsp;</td>
	</tr>
	<tr> 
		<td class="tdtitulosd" colspan=5>&nbsp;</td>
		<td class="tdtitulosd" colspan=2>Relaci&oacute;n del <?php echo " $fecha1 al $fecha2 ";?>&nbsp;</td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>

	<tr> 
		<td class="tdtitulos" >&nbsp;Nombre y Apellido del Titular:</td>
		<td class="tdcampos" > <?php echo "$f_titular[nombres] $f_titular[apellidos]";?></td> 
		<td class="tdtitulos" >C&oacute;digo:</td>
		<td class="tdcampos" ><?php echo "$f_titular[id_titular]"; ?></td>	
		<td class="tdtitulos" >Estado:</td>
		<td class="tdcamposr" ><?php echo "$f_titular[estado_cliente]"; ?></td>	
	<tr> 
		<td class="tdtitulos" >&nbsp;C&eacute;dula del Titular:</td>
		<td class="tdcampos" > <?php echo "$f_titular[cedula]" ;?></td>
		<td class="tdtitulos" >Ente:</td>
		<td class="tdcampos" > <?php echo "$f_titular[nombre]";?></td>
	</tr>
	<tr><td colspan="7">&nbsp;</td></tr>
	<tr> 
		<td class="tdtituloss"colspan=7>Las Consultas Preventivas del Cliente son:</td>
	</tr>
		<tr><td colspan="7">&nbsp;</td></tr>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%"> 

	<tr> 
		<td class="descrip_main">PROCESO</td>   
		<td class="descrip_main">FECHA</td>     
		<td class="descrip_main">ESPECIALIDAD MEDICA</td> 
		<td class="descrip_main">DESCRIPCION</td>  
		<td class="descrip_main" colspan=2 >ANALISIS TECNICO</td> 
		<td class="descrip_main">ANALISTA</td> 
		      
	</tr>

<?php
	$q_proceso=("select consultas_preventivas.*, procesos.*, gastos_t_b.descripcion, admin.nombres from consultas_preventivas, procesos, gastos_t_b, admin where 
consultas_preventivas.id_titular=$f_titular[id_titular] and
consultas_preventivas.id_titular=procesos.id_titular and
consultas_preventivas.id_proceso=procesos.id_proceso and
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.id_admin=admin.id_admin and
consultas_preventivas.id_beneficiario=0 and
consultas_preventivas.fecha_creado>='$fecha1' and
consultas_preventivas.fecha_creado<='$fecha2' order by consultas_preventivas.especialidad_medica");
	$r_proceso=ejecutar($q_proceso);
		$i=0;
		while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC)){
		$i++;
echo"
	<tr> 
		<td class=\"tdtituloss\">$f_proceso[id_proceso]</td>   
		<td class=\"tdtituloss\">$f_proceso[fecha_creado]</td>     
		<td class=\"tdtituloss\">$f_proceso[especialidad_medica]</td> 
		<td class=\"tdtituloss\">$f_proceso[descripcion]</td>  
		<td class=\"tdtituloss\" colspan=2 >$f_proceso[comentarios]</td> 
		<td class=\"tdtituloss\">$f_proceso[nombres]</td> 	      
	</tr>"; }?>

	<tr> 
		<td colspan="7">&nbsp;</td>
	</tr>
	<tr>
	        <td colspan=7 class="descrip_main" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $i; ?> Consultas Preventivas  </td>

	</tr>
</table>


 <?php }}?> 



<?php if($f_cliente['id_cliente']==$f_be['id_cliente']){

$q_beneficiario=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula, beneficiarios.id_cliente, beneficiarios.id_titular, beneficiarios.id_beneficiario, titulares.id_titular, titulares.id_ente, entes.nombre,estados_clientes.estado_cliente from estados_clientes,clientes, titulares, beneficiarios, entes,estados_t_b where clientes.cedula='$ci' and
beneficiarios.id_cliente=clientes.id_cliente and
titulares.id_titular=beneficiarios.id_titular and
titulares.id_ente=entes.id_ente and estados_t_b.id_titular=titulares.id_titular and estados_t_b.id_beneficiario=beneficiarios.id_beneficiario and
estados_clientes.id_estado_cliente=estados_t_b.id_estado_cliente");
	$r_beneficiario=ejecutar($q_beneficiario);

		while($f_beneficiario=asignar_a($r_beneficiario,NULL,PGSQL_ASSOC)){?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%" > 
<?php
$q_tit_ben=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula, titulares.id_cliente, titulares.id_titular, titulares.id_ente, entes.nombre from clientes, titulares, entes where 
titulares.id_cliente=clientes.id_cliente and
titulares.id_titular=$f_beneficiario[id_titular] and
titulares.id_ente=entes.id_ente");
$r_tit_ben=ejecutar($q_tit_ben);
$f_tit_ben=asignar_a($r_tit_ben);
		

?>
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="descrip_main" colspan="7">Reporte Relaci&oacute;n Consultas Preventivas del Cliente como Beneficiario </td>     
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%" > 
	<tr> 
		<td >&nbsp;</td>
	</tr>
	<tr> 
		<td class="tdtitulosd" colspan=5>&nbsp;</td>
		<td class="tdtitulosd" colspan=2>Relaci&oacute;n del <?php echo " $fecha1 al $fecha2 " ;?>&nbsp;</td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>	
	<tr> 
		<td class="tdtitulos">&nbsp;Nombre y Apellido del Titular:</td>
		<td class="tdcampos"> <?php echo "$f_tit_ben[nombres] $f_tit_ben[apellidos]";?></td>
		<td class="tdtitulos">C&oacute;digo:</td>
		<td class="tdcampos"> <?php echo "$f_tit_ben[id_titular]"; ?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">&nbsp;C&eacute;dula del Titular:</td>
		<td class="tdcampos"> <?php echo "$f_tit_ben[cedula]" ;?></td>
		<td class="tdtitulos">Ente:</td>
		<td class="tdcampos"><?php echo "$f_tit_ben[nombre]";?></td>
	</tr>
	<tr>
		<td >&nbsp;</td>
	</tr>
	<tr> 
		<td class="tdtitulos">&nbsp;Nombre y Apellido del Beneficiario:</td>
		<td class="tdcampos"> <?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]";?></td>
		<td class="tdtitulos">C&oacute;digo:</td>
		<td class="tdcampos"><?php echo "$f_beneficiario[id_beneficiario]"; ?></td>
<td class="tdtitulos">Estado:</td>
		<td class="tdcamposr"><?php echo "$f_beneficiario[estado_cliente]"; ?></td>
	</tr>
	<tr> 
		<td class="tdtitulos">&nbsp;C&eacute;dula del Beneficiario:</td>
		<td class="tdcampos"> <?php echo "$f_beneficiario[cedula]" ;?></td>
		<td class="tdtitulos">Ente:</td>
		<td class="tdcampos"><?php echo "$f_tit_ben[nombre]";?></td>
	</tr>
	<tr> 
		<td >&nbsp;</td>
	</tr>
	<tr>
		<td class="tdtituloss" colspan=7>Las Consultas Preventivas del Cliente son:</td>
	</tr>
</table>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%"> 

	<tr> 

		<td class="descrip_main">PROCESO</td>   
		<td class="descrip_main">FECHA</td>     
		<td class="descrip_main">ESPECIALIDAD MEDICA</td> 
		<td class="descrip_main">DESCRIPCION</td>  
		<td class="descrip_main" colspan=2 >ANALISIS TECNICO</td> 
		<td class="descrip_main">ANALISTA</td> 
		      
	</tr>
<?php

	$q_proceso=("select consultas_preventivas.*, procesos.*, gastos_t_b.descripcion, admin.nombres from consultas_preventivas, procesos, gastos_t_b, admin where 

consultas_preventivas.id_titular=procesos.id_titular and
consultas_preventivas.id_proceso=procesos.id_proceso and
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.id_admin=admin.id_admin and
consultas_preventivas.id_beneficiario=$f_beneficiario[id_beneficiario] and
consultas_preventivas.fecha_creado>='$fecha1' and
consultas_preventivas.fecha_creado<='$fecha2' order by consultas_preventivas.especialidad_medica");
	$r_proceso=ejecutar($q_proceso);
		$i=0;
		while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC)){
		$i++;
echo"
	<tr> 
		<td class=\"tdtituloss\">$f_proceso[id_proceso]</td>   
		<td class=\"tdtituloss\">$f_proceso[fecha_creado]</td>     
		<td class=\"tdtituloss\">$f_proceso[especialidad_medica]</td> 
		<td class=\"tdtituloss\">$f_proceso[descripcion]</td>  
		<td class=\"tdtituloss\" colspan=2 >$f_proceso[comentarios]</td> 
		<td class=\"tdtituloss\">$f_proceso[nombres]</td>	      
	</tr>";}?> 


	<tr> 
		<td colspan="7">&nbsp;</td>
	</tr>

	<tr>
	        <td colspan=7 class="descrip_main" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $i; ?> Consultas Preventivas  </td>
	</tr>	
</table>
<?php }}?>

	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 width="80%"> 
	<tr> 
		<td colspan="7">&nbsp;</td>
	</tr>
        <br>
	<tr>

	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=2 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
	<br> 
</table>

