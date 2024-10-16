<?php
   include ("../../lib/jfunciones.php");
   sesion();

list($id_usuario,$usuario)=explode("@",$_REQUEST['parausuario']);
if($id_usuario==0){	        $condicion_usuario="and procesos.id_admin>0 and 
	admin.id_admin=procesos.id_admin";
				$var_usuario="procesos.id_admin, admin.nombres, admin.apellidos,";}



else if($id_usuario=="-01"){	        $condicion_usuario=" and admin.id_admin=procesos.id_admin";
				$var_usuario="";}
else{
$condicion_usuario="and procesos.id_admin=$id_usuario and 
	admin.id_admin=procesos.id_admin";
$var_usuario="procesos.id_admin, admin.nombres, admin.apellidos,";}


	$fecre1=$_REQUEST['fecha1'];
	$fecre2=$_REQUEST['fecha2'];

list($id_servicio,$servicio)=explode("@",$_REQUEST['servic']);
if($id_servicio==0){	        $condicion_servicio="and gastos_t_b.id_servicio>0";
				$agrupar=",count(procesos.id_proceso)";
				$planilla="procesos.id_proceso";}
	else if($id_servicio=="-01"){
	$condicion_servicio="and (gastos_t_b.id_servicio!=6 and gastos_t_b.id_servicio!=9 ) ";
	$agrupar=",count(procesos.id_proceso)";
	$planilla="procesos.id_proceso";}
	else if($id_servicio=="-02"){
	$condicion_servicio="and (gastos_t_b.id_servicio=6 or gastos_t_b.id_servicio=9 ) ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}
	else if($id_servicio=="6"){
	$condicion_servicio="and gastos_t_b.id_servicio=6 ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}

	else if($id_servicio=="9"){
	$condicion_servicio="and gastos_t_b.id_servicio=9 ";
	$agrupar=",count(procesos.nu_planilla)";
	$planilla="procesos.nu_planilla";}
	else{
	$condicion_servicio="and gastos_t_b.id_servicio='$id_servicio'";
	$agrupar=",count(procesos.id_proceso)";
	$planilla="procesos.id_proceso";}
	
list($id_sucursal,$sucursal)=explode("@",$_REQUEST['sucur']);
if($id_sucursal==0)	        $condicion_sucursal="and admin.id_sucursal>0";
else
$condicion_sucursal="and admin.id_sucursal='$id_sucursal'";

	$q_proceso=("select  $planilla, 
	procesos.id_titular, 
	procesos.id_beneficiario, 
	procesos.fecha_recibido, 
$var_usuario
	procesos.id_estado_proceso, 
	gastos_t_b.id_servicio, 
	titulares.id_ente, 
	 admin.id_sucursal,
	clientes.id_cliente, 
	clientes.sexo, 
	(clientes.nombres) AS n, 
	(clientes.apellidos) AS a, 
	clientes.cedula, 
	clientes.fecha_nacimiento, 
	entes.nombre, 
	estados_procesos.estado_proceso $agrupar
	from procesos,gastos_t_b,admin,titulares,clientes,entes,estados_procesos 
	where 
	procesos.fecha_recibido between '$fecre1' and '$fecre2' and 
	gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso>0 and 
	(procesos.id_beneficiario>0 or procesos.id_beneficiario=0)  $condicion_servicio $condicion_sucursal and 
	procesos.id_titular=titulares.id_titular and 
	entes.id_tipo_ente>0 
$condicion_usuario and
	titulares.id_ente=entes.id_ente and 
	gastos_t_b.id_proveedor>=0 and 
	titulares.id_cliente=clientes.id_cliente  and
	procesos.id_estado_proceso=estados_procesos.id_estado_proceso
	group by 
	$planilla,$var_usuario procesos.id_titular,admin.id_sucursal, procesos.id_beneficiario, procesos.fecha_recibido,  procesos.id_estado_proceso, gastos_t_b.id_servicio, titulares.id_ente,  clientes.id_cliente, clientes.sexo, n, a, clientes.cedula, clientes.fecha_nacimiento, entes.nombre, estados_procesos.estado_proceso
 ORDER BY $planilla DESC ");
	$r_proceso=ejecutar($q_proceso);

/*echo $q_proceso;*/


?>

<table class="tabla_citas" cellpadding=0 cellspacing=0 > 
<tr>
	<td class="titulo_seccion">Reporte Procesos por Usuarios</td>     
</tr>

<?php/* echo $id_usuario."**";
echo $usuario;
echo $fecre1;
echo $fecre2;
echo $servicio."------";
echo $id_servicio;

*/
?>            

</table>



<table class="tabla_citas"  cellpadding=0 cellspacing=0 border=0> 

<tr><td>&nbsp;</td></tr>
	<tr> 
		<td class="tdtitulosd" >Relaci&oacute;n de <?php echo "$fecre1 al $fecre2";?></td>
	</tr> 
<tr><td>&nbsp;</td></tr>
<table class="tabla_citas" id="colortable"  cellpadding=0 cellspacing=0 border=0> 

	<tr> 

	   	<td class="tdcampos"><?php if($id_servicio=="-02" or $id_servicio=="6" or $id_servicio=="9") echo PLANILLA ;
					else echo ORDEN; ?></td>  
<td class="tdcampos">TITULAR</td>
	        <td class="tdcampos">CEDULA TITULAR</td> 
<td class="tdcampos">BENEFICIARIO</td>
	        <td class="tdcampos">CEDULA BENEFICIARIO</td>
 <td class="tdcampos">ENTE</td>        
		<td class="tdcampos">ESTADO PROCESO</td> 
		<td class="tdcampos">ANALISTA</td> 


	
<?php
 $i=1;
	     while($f_proceso=asignar_a($r_proceso,NULL,PGSQL_ASSOC))
		{

$i++;



 echo"    <tr>
		<td class=\"tdtituloss\">$f_proceso[id_proceso] $f_proceso[nu_planilla]  </td>
		<td class=\"tdtituloss\">$f_proceso[n] $f_proceso[a]  </td>
		<td class=\"tdtituloss\">$f_proceso[cedula] </td>";

	if ($f_proceso[id_beneficiario]>0){
		$q_benf=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.id_cliente,clientes.fecha_nacimiento,clientes.sexo from 				clientes,beneficiarios where beneficiarios.id_beneficiario='$f_proceso[id_beneficiario]' and 					beneficiarios.id_cliente=clientes.id_cliente");
		$r_benf=ejecutar($q_benf);
		$f_benf=asignar_a($r_benf);
	echo "
		<td class=\"tdtituloss\">$f_benf[nombres] $f_benf[apellidos] </td>
		<td class=\"tdtituloss\">$f_benf[cedula] </td>";
}
	else {
echo"
	<td &nbsp;</td>
	<td &nbsp;</td>";
}
 echo" 
		
		<td class=\"tdtituloss\">$f_proceso[nombre] </td>
		<td class=\"tdtituloss\">$f_proceso[estado_proceso] </td>
		<td class=\"tdtituloss\">$f_proceso[nombres] $f_proceso[apellidos] </td>
";
     }

?>  

</table>

<table>
<tr><td>&nbsp;</td></tr>
	        <td class="tdtituloss" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Hay un total de <?php echo $i-1; ?> Ordenes </td>
<tr><td>&nbsp;</td></tr>

	<tr> <td >&nbsp;</td></tr>
 



	<tr>
	        <td  class="tdcamposs" title="Excel">
	<?php
		$url="'views06/excel_proceso_usuario.php?usuario=$id_usuario@$usuario&servic=$id_servicio@$servicio&fecha1=$fecre1&fecha2=$fecre2&sucur=$id_sucursal@$sucursal'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton">Excel</a> 

	</td>
	</tr>
	<tr> <td >&nbsp;</td></tr>
</table>
