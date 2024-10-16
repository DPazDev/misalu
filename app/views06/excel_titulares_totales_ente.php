<?php
/* Nombre del Archivo: excel_titulares_totales_ente.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Titulares Totales por Entes
*/  
header("Content-Type: text/html;charset=utf-8");
 include ("../../lib/jfunciones.php");
 sesion();

header('Content-type: application/vnd.ms-excel');//poner cabezera de excel
$numealeatorio=rand(2,99);//crea un numero aleatorio para el nombre del archivo
header("Content-Disposition: attachment; filename=archivo$numealeatorio.xls");//Esta ya es la hoja excel con el numero aleatorio.xls
header("Pragma: no-cache");//Para que no utilice la cahce
header("Expires: 0");

	$qente=("select entes.nombre, entes.id_ente from entes ORDER BY  			 entes.nombre");
	$rente=ejecutar($qente);

?>
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="titulo_seccion" colspan="19">TOTAL TITULARES POR ENTES </td>

<tr><td>&nbsp;</td></tr>
</table>	


<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 

	<tr> 
	<td class="tdcampos">ENTE:</td>

	<td class="tdcampos">SEXO:</td>
	<td class="tdcampos">ESTADO:</td>
	<td class="tdcampos">TOTAL:</td>

</tr>
<?php
while($fente=asignar_a($rente,NULL,PGSQL_ASSOC))
		{?>



<?php $qreporte=("select estados_clientes.estado_cliente, clientes.sexo, count(clientes.sexo)  from clientes, titulares, estados_t_b, estados_clientes where titulares.id_cliente=clientes.id_cliente and titulares.id_ente='$fente[id_ente]' and  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 group by estados_clientes.estado_cliente,clientes.sexo order by clientes.sexo,estados_clientes.estado_cliente");

 $contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

?>
<tr>
<?php
echo"


                <td class=\"tdtitulos\">&nbsp;&nbsp; $fente[nombre]</td>
	        <td class=\"tdtitulos\">&nbsp; &nbsp; "; if($freporte[sexo]==1) echo "MASCULINO";
else echo "FEMENINO"; 
echo "</td> 
	        <td class=\"tdtitulos\">&nbsp; &nbsp;$freporte[estado_cliente]</td> 
	        <td class=\"tdtitulos\"> &nbsp; &nbsp; $freporte[count]</td> 

</tr>

";}?>
<tr><td>&nbsp;</td></tr>

<?php }?>

</table>
<table class="tabla_citas" cellpadding=0 cellspacing=0 border=0> 
<tr><td >&nbsp;</td></tr>
<tr><td >&nbsp;</td></tr>

<?php $qreporte=("select estados_clientes.estado_cliente, clientes.sexo, count(clientes.sexo)  from clientes, titulares, estados_t_b, estados_clientes where titulares.id_cliente=clientes.id_cliente and  estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and titulares.id_titular=estados_t_b.id_titular and estados_t_b.id_beneficiario=0 group by estados_clientes.estado_cliente,clientes.sexo order by clientes.sexo,estados_clientes.estado_cliente");

 $contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

?>
<tr>
<?php
echo"


	        <td colspan=3 class=\"tdtitulos\">&nbsp;</td> 
	        <td  class=\"tdtitulos\">&nbsp; &nbsp; "; if($freporte[sexo]==1) echo "MASCULINO";
else echo "FEMENINO"; echo "&nbsp;&nbsp;&nbsp;&nbsp;".$freporte[estado_cliente];
echo "</td> 

	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; $freporte[count]</td> 
	        <td  colspan=10 class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
 		<td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
		<td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
	        <td  class=\"tdtitulos\"> &nbsp; &nbsp; </td> 
</tr>

"; $contador=$contador+$freporte['count'];

}?>


</table>


