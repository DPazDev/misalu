<?php
/* Nombre del Archivo: ireporte_consultas_medicas_x_ente_1.php
   Descripción: Realiza la busqueda en la base de datos, para el Reporte de Impresión: Consultas Médicas Por Entes (Totales por Entes) indicando los datos de los pacientes que asisten con cada especialista
*/  

 include ("../../lib/jfunciones.php");
   sesion();

$especialidad=$_REQUEST['especialidad'];


$q_especial=("select especialidades_medicas.id_especialidad_medica, especialidades_medicas.especialidad_medica from especialidades_medicas where especialidades_medicas.id_especialidad_medica='$especialidad'");
   	$r_especial = ejecutar($q_especial);
   	$f_especial=asignar_a($r_especial);


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



/*echo $estado;

echo $condicion_estado;
echo $fecha1;
echo $fecha2."-------";
echo $especialidad."//";
echo $f_ente[id_ente];
echo $f_especial[especialidad_medica];*/

$qreporte=("select procesos.id_proceso,procesos.fecha_recibido,gastos_t_b.fecha_cita, especialidades_medicas.especialidad_medica,gastos_t_b.descripcion,gastos_t_b.enfermedad,procesos.id_titular,procesos.id_beneficiario from procesos,gastos_t_b,especialidades_medicas,admin,titulares,proveedores, s_p_proveedores
where   gastos_t_b.id_proceso=procesos.id_proceso $condicion_fecha and 
	(gastos_t_b.id_servicio='4' or gastos_t_b.id_servicio='6') $condicion_estado $condicion_nomina and 
	procesos.id_titular=titulares.id_titular and 
	titulares.id_ente='$id_ente'  and procesos.id_admin=admin.id_admin
        $condicion_sucursal and
	s_p_proveedores.id_especialidad=especialidades_medicas.id_especialidad_medica and 
especialidades_medicas.id_especialidad_medica='$especialidad' 

	group by procesos.id_proceso,procesos.fecha_recibido,gastos_t_b.fecha_cita,especialidades_medicas.especialidad_medica,gastos_t_b.descripcion,gastos_t_b.enfermedad,procesos.id_titular,procesos.id_beneficiario ORDER BY procesos.id_proceso DESC");
/*echo $qreporte;*/
$rreporte=ejecutar($qreporte);

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
else echo $fsucur[sucursal]?> &nbsp;**** PROCESOS <?php if($estado==0) echo "CANDIDATO A PAGO + APROBADO OPERADOR"; else if($estado==2) echo "APROBADO OPERADOR";else if($estado==7) echo "CANDIDATO A PAGO"; else if($estado==14) echo "ANULADO";?> &nbsp; **** PROVEEDORES <?php echo $nomina?> **** ESPECIALIDAD <?php echo $f_especial[especialidad_medica];?></td>
	</tr>
<tr><td>&nbsp;</td></tr>
</table>
 
<table class="tabla_citas"  cellpadding=0 cellspacing=0 rules="rows"> 
	<tr> 

		<td class="tdcampos">ORDEN</td>   
		<td class="tdcampos">FECHA EMISION</td> 
		<td class="tdcampos">FECHA CITA</td>         
		<td class="tdcampos">TITULAR</td> 
		<td class="tdcampos">CEDULA TITULAR</td> 
		<td class="tdcampos">BENEFICIARIO</td>  
		<td class="tdcampos">CEDULA BENEFICIARIO</td> 
		<td class="tdcampos">CONSULTA</td> 
		<td class="tdcampos">ENTE</td>  
		<td class="tdcampos">ENFERMEDAD</td>            
	      
	</tr>


<?php
while($freporte=asignar_a($rreporte,NULL,PGSQL_ASSOC))
		{

		$rtitular=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,titulares where titulares.id_titular=$freporte[id_titular] and titulares.id_cliente=clientes.id_cliente"); 
			$qtitular=ejecutar($rtitular);
			$datatitular=asignar_a($qtitular);
			$ftitular="$datatitular[nombres] $datatitular[apellidos]";
			$fcedula="$datatitular[cedula]";
			   if ($freporte[id_beneficiario]>0){
				  $rbenf=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,beneficiarios where beneficiarios.id_beneficiario=$freporte[id_beneficiario] and beneficiarios.id_cliente=clientes.id_cliente;");
				  $qbenf=ejecutar($rbenf);
				  $databenf=asignar_a($qbenf);
				  $fbenf="$databenf[nombres] $databenf[apellidos]";  
				  $fceduben="$databenf[cedula]";
			  }else{$fbenf='';
				$fceduben='';}	



	  echo"
            <tr> 

		    <td colspan=1  class=\"tdtituloss\">$freporte[id_proceso] &nbsp;&nbsp;&nbsp;</td>
		    <td colspan=1  class=\"tdtituloss\">$freporte[fecha_recibido] &nbsp;&nbsp;&nbsp;</td>
		    <td colspan=1  class=\"tdtituloss\">$freporte[fecha_cita] &nbsp;&nbsp;&nbsp;</td>
		    <td colspan=1  class=\"tdtituloss\">$ftitular </td>
		    <td colspan=1  class=\"tdtituloss\">$fcedula </td>
		    <td colspan=1  class=\"tdtituloss\">$fbenf </td>
		    <td colspan=1  class=\"tdtituloss\"> $fceduben </td>
		    <td colspan=1  class=\"tdtituloss\">$freporte[descripcion] </td>
		    <td colspan=1  class=\"tdtituloss\">$f_ente[nombre] &nbsp;&nbsp;&nbsp;</td>
		    <td colspan=1  class=\"tdtituloss\">$freporte[enfermedad] </td>


</tr>";
}
	?>
