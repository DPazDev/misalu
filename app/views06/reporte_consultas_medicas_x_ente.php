<?php
/* Nombre del Archivo: reporte_consultas_medicas_x_ente.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Consultas Médicas Por Entes (Totales por Entes)
*/  

 include ("../../lib/jfunciones.php");
   sesion();
   $fecre1=$_REQUEST['fecha1'];
   $fecre2=$_REQUEST['fecha2'];
   $nomina=$_REQUEST['nomina'];

if($nomina=='FECHA CITA')  {
 $condicion_nomina="and gastos_t_b.id_proveedor=proveedores.id_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
(s_p_proveedores.nomina='1' or s_p_proveedores.nomina='0')";
$condicion_fecha="and gastos_t_b.fecha_cita between '$fecre1' and '$fecre2'";
}
else 
if($nomina=='FECHA ORDEN')  {
 $condicion_nomina="and gastos_t_b.id_proveedor=proveedores.id_proveedor and s_p_proveedores.id_s_p_proveedor=proveedores.id_s_p_proveedor and
(s_p_proveedores.nomina='1' or s_p_proveedores.nomina='0')";
$condicion_fecha="and procesos.fecha_recibido between '$fecre1' and '$fecre2'";
}

list($id_sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="and admin.id_sucursal>0";
else
$condicion_sucursal="and admin.id_sucursal=$id_sucursal";

list($estado)=explode("@",$_REQUEST['estado']);
if($estado==0)	        $condicion_estado="and (procesos.id_estado_proceso=7 or procesos.id_estado_proceso=14 or procesos.id_estado_proceso=2)  ";
	else
   $condicion_estado="and procesos.id_estado_proceso=$estado";



$qsucur=("select sucursales.id_sucursal, sucursales.sucursal from sucursales where sucursales.id_sucursal=$id_sucursal");
$rsucur=ejecutar($qsucur);
$fsucur=asignar_a($rsucur);
$fsucur['sucursal'];

list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);

list($id_ente,$ente)=explode("@",$_REQUEST['ente']);



if($id_ente==0)	        $condicion_ente=" entes.id_tipo_ente>0";
	
	else
	$condicion_ente=" entes.id_ente='$id_ente'";

if  ($tipo_ente==0){
	$tipo_entes="and entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_entes="and entes.id_tipo_ente=$tipo_ente";
	}


$qente=("select entes.nombre, entes.id_ente from entes where $condicion_ente  $tipo_entes ORDER BY entes.nombre");
$rente=ejecutar($qente);


?>

<table class="tabla_citas"  cellpadding=0 cellspacing=0> 
	<tr>
		<td class="titulo_seccion" colspan="19">Consultas M&eacute;dicas por Entes, Relaci&oacute;n Orden de Atenci&oacute;n y Emergencia </td>
</table>	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="tdtitulosd" colspan=19> Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
		<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> 
<td class="tdcampos" colspan=15>&nbsp; </td>
		<td class="tdcampos" colspan=4><?php if ($id_sucursal==0) echo " TODAS LAS SUCURSALES" ;
else echo $fsucur[sucursal]?> &nbsp;**** PROCESOS <?php if($estado==0) echo "TODOS LOS ESTADOS"; else if($estado==2) echo "APROBADO OPERADOR";else if($estado==7) echo "CANDIDATO A PAGO"; else if($estado==14) echo "ANULADO";?> &nbsp; **** PROVEEDORES <?php echo $nomina?> </td>
	</tr>
<tr><td>&nbsp;</td></tr>
</table>	
 	
<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
<?php
while($fente=asignar_a($rente,NULL,PGSQL_ASSOC))
		{?>

	<tr> 
	<td class="tdcampos">ENTE:</td>
        <td class="tdtitulos">&nbsp;&nbsp; <?php echo $fente['nombre']; ?>
	<td class="tdcampos">ESPECIALIDAD:</td>
	<td class="tdcampos">ESTADO:</td>
	<td class="tdcampos">TOTAL:</td>
</tr>

<?php 
$qreporte=("select procesos.id_estado_proceso,estados_procesos.estado_proceso,especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica, count(especialidades_medicas.id_especialidad_medica) 
            from procesos,gastos_t_b,especialidades_medicas,admin,titulares,proveedores,estados_procesos, 
            s_p_proveedores where gastos_t_b.id_proceso=procesos.id_proceso 
            $condicion_fecha 
            and (gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6' ) and procesos.id_admin=admin.id_admin  
            $condicion_sucursal $condicion_estado $condicion_nomina and 
            procesos.id_titular=titulares.id_titular and titulares.id_ente='$fente[id_ente]' and
            estados_procesos.id_estado_proceso=procesos.id_estado_proceso and
	    s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
            group by procesos.id_estado_proceso,estados_procesos.estado_proceso,especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica 
            ORDER BY especialidades_medicas.especialidad_medica");

/*echo $qreporte;*/

 $contador=0;
$rreporte=ejecutar($qreporte);

$HH=0;
$JJ=0;

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

$estadopro="$freporte[id_estado_proceso]";
$especiali="$freporte[id_especialidad_medica]";

$url="views06/ireporte_consultas_medicas_x_ente_1.php?sucur=$id_sucursal&estado=$estado&fecha1=$fecre1&fecha2=$fecre2&nomina=$nomina&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente&especialidad=$freporte[id_especialidad_medica]";
?>



<tr>
<?php


echo"

	        <td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	         ";?>
<?php

if($estadopro<>$HH ){
$HH=$estadopro;
$JJ=$especiali;
echo"
<td class=\"tdtitulos\">$freporte[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte[count]</td> ";}

else if($estado=="$HH" && $especiali=="$JJ") {
$HH=$estado;
$JJ=$especiali;
echo"
 <td class=\"tdtitulos\">  $freporte[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte[count]</td> ";}

else if($estado!="$HH" && $especiali=="$JJ") {
$HH=$estado;
$JJ=$especiali;
echo"
<td class=\"tdtitulos\">$freporte[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte[count]</td> ";}

else if($estado!="$HH" && $especiali!="$JJ") {
$HH=$estado;
$JJ=$especiali;

echo"
<td class=\"tdtitulos\">$freporte[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte[count]</td> ";}
?>


<?php  if($id_ente!='0'){
echo "
<td class=\"tdcamposcc\">  <a href=\"$url\" title=\"Pacientes por Especialidad\" OnClick=\"Modalbox.show(this.href, {title: this.title, width: 800, height: 400, overlayClose: false}); return false;\" class=\"boton\">$freporte[especialidad_medica]</a> 
		</td>
</tr>

";}?>
      <?php  $contador=$contador+$freporte['count'];


}?>
<tr>
	<td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	<td class="tdcampos">TOTAL CONSULTAS:</td>
        <td class="tdcampos"> &nbsp;&nbsp; <?php echo $contador; ?></td>
</tr>
<?php } ?>
</table>	

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>


<table class="tabla_citas"  colspna=4 cellpadding=0 cellspacing=0 > 
<tr>
	<td colspan=1 class=\"tdtitulos\">&nbsp;</td> 
	<td class="tdcampos">ESPECIALIDAD:</td>
	<td class="tdcampos">ESTADO:</td>
	<td class="tdcampos">TOTAL CONSULTAS:</td>
</tr>


<?php 
$qreporte1=("select procesos.id_estado_proceso,estados_procesos.estado_proceso, especialidades_medicas.especialidad_medica, count(gastos_t_b.nombre) 
               from procesos,gastos_t_b,especialidades_medicas,admin,proveedores,
               s_p_proveedores,titulares,entes,estados_procesos
               where gastos_t_b.id_proceso=procesos.id_proceso $condicion_fecha
               and (gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6' ) and procesos.id_admin=admin.id_admin  
               $condicion_sucursal $condicion_estado $condicion_nomina and 
            estados_procesos.id_estado_proceso=procesos.id_estado_proceso and
               procesos.id_titular=titulares.id_titular and 
               $condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente and               
                	    s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
               group by procesos.id_estado_proceso,estados_procesos.estado_proceso,especialidades_medicas.especialidad_medica 
               ORDER BY especialidades_medicas.especialidad_medica");
 $contador=0;
$rreporte1=ejecutar($qreporte1);

while($freporte1=asignar_a($rreporte1,NULL,PGSQL_ASSOC))
		{


$estadopro1="$freporte1[id_estado_proceso]";
$especiali1="$freporte1[id_especialidad_medica]";

?>
<tr>
<?php

if($estadopro1<>$HH1 ){
$HH1=$estadopro1;
$JJ1=$especiali1;
echo"
<td class=\"tdtitulos\">$freporte1[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte1[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte1[count]</td> ";}

else if($estado1=="$HH1" && $especiali1=="$JJ1") {
$HH1=$estado1;
$JJ1=$especiali1;
echo"
 <td class=\"tdtitulos\">  $freporte1[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte1[count]</td> ";}

else if($estado1!="$HH1" && $especiali1=="$JJ1") {
$HH1=$estado1;
$JJ1=$especiali1;
echo"
<td class=\"tdtitulos\">$freporte1[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte1[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte1[count]</td> ";}


else if($estado1!="$HH1" && $especiali1!="$JJ1") {
$HH1=$estado1;
$JJ1=$especiali1;

echo"
<td class=\"tdtitulos\">$freporte1[especialidad_medica]</td>
 <td class=\"tdtitulos\">  $freporte1[estado_proceso]</td>
	        <td class=\"tdtitulos\">  $freporte1[count]</td> ";}

?>





</tr>


      <?php  $contador=$contador+$freporte1['count'];}?>
<tr>
	<td colspan=2 class=\"tdtitulos\">&nbsp;</td>
        <td class="tdcampos"> &nbsp;&nbsp; <?php echo $contador; ?>&nbsp; &nbsp; CONSULTAS</td>
</tr>
</table>

<table>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
	        <td colspan=4 class="tdcamposs" title="Imprimir reporte">
			  <?php
			$url="'views06/ireporte_consultas_medicas_x_ente.php?sucur=$id_sucursal&estado=$estado&fecha1=$fecre1&fecha2=$fecre2&nomina=$nomina&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Imprimir</a>
<?php $url="'views06/excel_consultas_medicas_x_ente.php?sucur=$id_sucursal&estado=$estado&fecha1=$fecre1&fecha2=$fecre2&nomina=$nomina&ente=$id_ente@$ente&tipo_ente=$tipo_ente@$nom_tipo_ente'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a>

			</td>
     </tr>
<tr><td colspan=4>&nbsp;</td></tr>
<br>
</table>
