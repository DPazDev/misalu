<?php

/* Nombre del Archivo: reporte_consulta_preventiva.php
   Descripción: Realiza la busqueda en la base de datos, para Reporte de Impresión: Relación Consultas Preventivas
*/ 

   include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];
   $ci=$_REQUEST['ci'];   
   $tipcliente=$_REQUEST['tipcliente'];   


echo "******************* <br>";
echo $fecre1."<br>"; 
echo "******************* <br>";
echo $fecre2."<br>"; 
echo "******************* <br>";
echo $ci."<br>"; 
echo "******************* <br>";
echo $tipcliente."<br>"; 
echo "******************* <br>";

?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="4">Reporte de Relaci&oacute;n de Consultas Preventivas del Cliente </td>     
	</tr>
</table>	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 

<?php if($tipcliente=='TITULAR'){

	$q_titular=("select clientes.id_cliente, clientes.nombres, clientes.apellidos, clientes.cedula, titulares.id_cliente, titulares.id_titular, titulares.id_ente, entes.nombre from clientes, titulares, entes where clientes.cedula=$ci and
titulares.id_cliente=clientes.id_cliente and
titulares.id_ente=entes.id_ente");
	$r_titular=ejecutar($q_titular);

		while($f_titular=asignar_a($r_titular,NULL,PGSQL_ASSOC)){

?>
	<tr> 
		<td class="tdtitulosd">Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 

	<tr> 
		<td >&nbsp;</td>
	</tr>	

	<tr> 
		<td class="tdtituloss">Nombre y Apellido del Titular:&nbsp;&nbsp;&nbsp; <?php echo "$f_titular[nombres] $f_titular[apellidos]";?></td>
	</tr>

<tr> 
		<td class="tdtituloss">C&oacute;digo:&nbsp;&nbsp;&nbsp; <?php echo "$f_titular[id_titular]"; ?></td>
	</tr>

<tr> 
		<td class="tdtituloss">C&eacute;dula del Titular:&nbsp;&nbsp;&nbsp; <?php echo "$f_titular[cedula]" ;?></td>
	</tr>

<tr> 
		<td class="tdtituloss">Ente:&nbsp;&nbsp;&nbsp; <?php echo "$f_titular[nombre]";?></td>
	</tr>

<tr> 
		<td class="tdtituloss">Las Consultas Preventivas que lleva el Cliente son:</td>
	</tr>
<tr> 
		<td >&nbsp;</td>
	</tr>
</table>	 	

<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 

<tr> 

		<td class="tdtituloss">Proceso</td>   
		<td class="tdtituloss">Fecha</td>     
		<td class="tdtituloss">Especialidad M&eacute;dica</td> 
		<td class="tdtituloss">Descripci&oacute;n</td>  
		<td class="tdtituloss" colspan=2 >An&aacute;lisis T&eacute;cnico</td> 
		<td class="tdtituloss">Operador</td> 
		      
	</tr>

<?php
	$q_proceso=("select consultas_preventivas.*, procesos.*, gastos_t_b.descripcion, admin.nombres from consultas_preventivas, procesos, gastos_t_b, admin where 
consultas_preventivas.id_titular=$f_titular[id_titular] and
consultas_preventivas.id_titular=procesos.id_titular and
consultas_preventivas.id_proceso=procesos.id_proceso and
procesos.id_proceso=gastos_t_b.id_proceso and
procesos.id_admin=admin.id_admin and
consultas_preventivas.id_beneficiario=0 and
consultas_preventivas.fecha_creado>='$fecre1' and
consultas_preventivas.fecha_creado<='$fecre2' order by consultas_preventivas.especialidad_medica");
	$r_proceso=ejecutar($q_proceso);

		while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC)){
		
echo"
	<tr> 

		<td class=\"tdtituloss\">$f_proceso[id_proceso]</td>   
		<td class=\"tdtituloss\">$f_proceso[fecha_creado]</td>     
		<td class=\"tdtituloss\">$f_proceso[especialidad_medica]</td> 
		<td class=\"tdtituloss\">$f_proceso[descripcion]</td>  
		<td class=\"tdtituloss\" colspan=2 >$f_proceso[comentarios]</td> 
		<td class=\"tdtituloss\">$f_proceso[nombres]</td> 
		      
	</tr>"?>

<?php } }
}?>
<!-- 	
<?php
	     $i=1;
		  $bsf=0; 

	     while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

			$rtitular=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where titulares.id_titular=$freporte[id_titular] and titulares.id_cliente=clientes.id_cliente"); 
			$qtitular=ejecutar($rtitular);
			$datatitular=asignar_a($qtitular);
			$ftitular="$datatitular[nombres] $datatitular[apellidos]";
			$fcedula="$datatitular[cedula]";
			   if ($freporte[id_beneficiario]>0){
				  $rbenf=("select clientes.nombres,clientes.apellidos from clientes,beneficiarios where beneficiarios.id_beneficiario=$freporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				

				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
				    
			  }else{$fbenf='';}


$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$freporte[id_proceso]");
$rgasto=ejecutar($qgasto);

		$bsf=0;

	while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC))
		{
		  $bsf= $bsf + ($fgasto[monto_aceptado]);
		  $bsf1= $bsf1 + ($fgasto[monto_aceptado]);}

		  echo"
            <tr> 

		    <td class=\"tdtituloss\">$freporte[id_proceso]</td>   
	            <td class=\"tdtituloss\">$freporte[no_clave]</td>  
	            <td class=\"tdtituloss\">$freporte[subdivision]</td>      
	            <td class=\"tdtituloss\">$ftitular</td>   
		    <td class=\"tdtituloss\">$fcedula</td>
	            <td class=\"tdtituloss\">$fbenf</td> 
	            <td class=\"tdtituloss\">$freporte[fecha_recibido]</td>       
	            <td class=\"tdtituloss\">$freporte[comentarios]</td>   
	            <td class=\"tdtituloss\">".formato_montos($bsf)."</td>      
	        </tr>";
		$i++;
		}
	?>
	<tr>
	        <td colspan=2 class="tdtitulosd" >(Hay un total de <? echo $i-1; ?> ordenes) </td>
	        <td colspan=6 class="tdtitulosd" >Total  </td>
	        <td  class="tdtitulosd"><?php echo formato_montos($bsf1); ?></td>
	</tr>

</table>

<br>

	<tr>
	        <td colspan=9 class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_entpriv.php?fecha1=$fecre1&fecha2=$fecre2&sucur=$id_sucursal&servic=$id_servicio&enpriv=$repente&lgnue=$replogo&estapro=$restpro'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
			</td>
	</tr> -->
	<br> 
</table>
