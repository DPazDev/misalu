<?php
/* Nombre del Archivo: ireporte_cliente_x_estado.php
   Descripción: Realiza el Reporte de Impresión con los datos seleccionados: Relación de los Clientes Totales, de un determinado Ente
*/  


 include ("../../lib/jfunciones.php");
   sesion();

?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="descrip_main" colspan="4"> Leyenda para el reporte de Clientes por Ente con Número Telefónico, para saber el Tipo de Ente y a que Ente pertenecen los Clientes</td>
</tr>

	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr> 
	   	<td class="descrip_main">ID_TIPO_ENTE</td>   
	   	<td class="descrip_main">TIPO_ENTE</td>   
	   	<td class="descrip_main">ID_ENTE</td>   
	   	<td class="descrip_main">ENTE</td>   


<?
$qleyenda=("select tbl_tipos_entes.id_tipo_ente, tbl_tipos_entes.tipo_ente,entes.id_ente, entes.nombre  from entes, tbl_tipos_entes where entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente order by tbl_tipos_entes.id_tipo_ente , entes.id_ente");
$rleyenda=ejecutar($qleyenda);


	while($fleyenda=asignar_a($rleyenda)){

echo"
		<tr> 
		<td class=\"tdtitulos\">$fleyenda[id_tipo_ente]</td>
		<td class=\"tdtitulos\">$fleyenda[tipo_ente]</td>
		<td class=\"tdtitulos\">$fleyenda[id_ente]</td>
		<td class=\"tdtitulos\">$fleyenda[nombre]</td>


";?>
	        </tr>
	<tr><td>&nbsp;</td></tr>
<?}?>


	<tr><td>&nbsp;</td></tr>
