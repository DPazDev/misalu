<?php
/* Nombre del Archivo: ireporte_consultas_medicas_x_ente.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Consultas Médicas Por Entes (Totales por Entes y por Especialidad)
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
if($id_sucursal==0)	        $condicion_sucursal="";
else
$condicion_sucursal="and admin.id_sucursal=$id_sucursal";

list($estado)=explode("@",$_REQUEST['estado']);
if($estado==0)	        $condicion_estado= "and (procesos.id_estado_proceso='2' or procesos.id_estado_proceso='7')";
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
<LINK REL="StyleSheet" HREF="../../public/stylesheets/impresiones.css">
<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr>
		<td class="descrip_main"> <img src="../../public/images/head.png" alt="logo"><br>RIF J-31180863-9</td>
		<td class="titulo_seccion" colspan="19">Consultas M&eacute;dicas por Entes, Relaci&oacute;n Orden de Atenci&oacute;n y Emergencia</td>
</table>	
 <br>

<table class="tabla_citas"  cellpadding=0 cellspacing=0 > 
	<tr> 
		<td class="tdtitulosd" colspan=19>Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
		<tr> <td colspan=4>&nbsp;</td></tr>
	<tr> 
		<td class="tdcamposc" colspan=19><?php if ($id_sucursal==0) echo " TODAS LAS SUCURSALES" ;
else echo $fsucur[sucursal]?> &nbsp;**** PROCESOS <?php if($estado==0) echo "CANDIDATO A PAGO + APROBADO OPERADOR"; else if($estado==2) echo "APROBADO OPERADOR";else if($estado==7) echo "CANDIDATO A PAGO"; else if($estado==14) echo "ANULADO";?> &nbsp; **** PROVEEDORES <?php echo $nomina?></td>
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
	<td class="tdcampos">TOTAL:</td>
</tr>

<?php 
$qreporte=("select especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica, count(especialidades_medicas.id_especialidad_medica) 
            from procesos,gastos_t_b,especialidades_medicas,admin,titulares,proveedores, 
            s_p_proveedores where gastos_t_b.id_proceso=procesos.id_proceso 
            $condicion_fecha 
            and (gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6' ) and procesos.id_admin=admin.id_admin  
            $condicion_sucursal $condicion_estado $condicion_nomina and 
            procesos.id_titular=titulares.id_titular and titulares.id_ente='$fente[id_ente]' and
            
	    s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
            group by especialidades_medicas.especialidad_medica,especialidades_medicas.id_especialidad_medica 
            ORDER BY especialidades_medicas.especialidad_medica");

$contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{?>
<tr>
<?php 
echo"

	        <td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	        <td class=\"tdtitulos\">$freporte[especialidad_medica]</td> 
	        <td class=\"tdtitulos\">  $freporte[count]</td> 
</tr>

";?>
      <?php  $contador=$contador+$freporte['count'];?>

<?php
}?>
<tr>
	<td colspan=2 class=\"tdtitulos\">&nbsp;</td> 
	<td class="tdcampos">TOTAL CONSULTAS:</td>
        <td class="tdcampos"> &nbsp;&nbsp; <?php echo $contador; ?></td>
</tr>
<?php }?>
</table>	

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>


<table class="tabla_citas"  colspna=4 cellpadding=0 cellspacing=0 > 
<tr>
	<td colspan=1 class=\"tdtitulos\">&nbsp;</td> 
	<td class="tdcampos">ESPECIALIDAD:</td>
	<td class="tdcampos">TOTAL CONSULTAS:</td>
</tr>


<?php 
$qreporte=("select especialidades_medicas.especialidad_medica, count(gastos_t_b.nombre) 
               from procesos,gastos_t_b,especialidades_medicas,admin,proveedores,
               s_p_proveedores,titulares,entes
               where gastos_t_b.id_proceso=procesos.id_proceso $condicion_fecha
               and (gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6' ) and procesos.id_admin=admin.id_admin  
               $condicion_sucursal $condicion_estado $condicion_nomina and 
               procesos.id_titular=titulares.id_titular and 
               $condicion_ente $tipo_entes and titulares.id_ente=entes.id_ente and               
                	    s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica
               group by especialidades_medicas.especialidad_medica 
               ORDER BY especialidades_medicas.especialidad_medica");


 $contador=0;
$rreporte=ejecutar($qreporte);

while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{?>

<?php
echo"


	        <td colspan=1 class=\"tdtitulos\">&nbsp;</td> 
	        <td class=\"tdtitulos\">$freporte[especialidad_medica]</td> 
	        <td class=\"tdtitulos\">$freporte[count]</td> ";?>
</tr>


      <?php  $contador=$contador+$freporte['count'];?>

<?php
}?>
<tr>
	<td colspan=2 class=\"tdtitulos\">&nbsp;</td>
        <td class="tdcampos"> &nbsp;&nbsp; <?php echo $contador; ?>&nbsp; &nbsp; CONSULTAS</td>
</tr>
</table>


<table>
<br>
<tr><td colspan=4>&nbsp;</td></tr>
<br> 
	<tr>
	        <td  colspan=2 class="tdtituloss" >Elaborado Por:____________________</td>
	        <td  colspan=3 class="tdtituloss" >Aprobado Por:____________________</td>
		<td  colspan=3 class="tdtituloss" >Recibido Por:____________________</td>
			
     	</tr>
<tr><td colspan=4>&nbsp;</td></tr>

</table>

