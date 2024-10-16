<?php
/* Nombre del Archivo: reporte_parientes_totales_entes.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Parientes Totales por entes
*/  

 include ("../../lib/jfunciones.php");
 sesion();

	$qente=("select entes.nombre, entes.id_ente from entes ORDER BY  			 entes.nombre");
	$rente=ejecutar($qente);

?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="19">TOTAL PARIENTES POR ENTES </td>

<tr><td>&nbsp;</td></tr>
</table>	


<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
<?php
while($fente=asignar_a($rente,NULL,PGSQL_ASSOC))
		{?>

	<tr> 
	<td class="tdcampos">ENTE:</td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fente[nombre]; ?></td>
	<td class="tdcampos">PARENTESCO:</td>
	<td class="tdcampos">ESTADO:</td>
	<td class="tdcampos">TOTAL:</td>

</tr>

<?php $qreporte=("select parentesco.parentesco,estados_clientes.estado_cliente, count(beneficiarios.id_parentesco)  from parentesco, beneficiarios, titulares,estados_t_b,estados_clientes where titulares.id_titular=beneficiarios.id_titular  and titulares.id_ente='$fente[id_ente]' and beneficiarios.id_parentesco=parentesco.id_parentesco and  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and beneficiarios.id_beneficiario=estados_t_b.id_beneficiario group by parentesco.parentesco,estados_clientes.estado_cliente order by parentesco.parentesco");

 $contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{?>
<tr>
<?php
echo"

	        <td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	        <td class=\"tdtitulos\">&nbsp; &nbsp;$freporte[parentesco]</td> 
	        <td class=\"tdtitulos\">&nbsp; &nbsp;$freporte[estado_cliente]</td> 
	        <td class=\"tdtitulos\"> &nbsp; &nbsp; $freporte[count]</td> 

</tr>

";}?>
<tr><td>&nbsp;</td></tr>

<?php }?>

</table>

<br>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
	        <td colspan=4 class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_parientes_totales_entes.php'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
<?php $url="'views06/excel_parientes_totales_entes.php'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>

			</td>
     </tr>
<tr><td colspan=4>&nbsp;</td></tr>

