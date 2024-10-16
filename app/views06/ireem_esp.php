<?php
include ("../../lib/jfunciones.php");
sesion();
$monto=$_REQUEST['monto'];
$fechaini=$_REQUEST['fechaini'];
$fechafin=$_REQUEST['fechafin'];
$igualdad=$_REQUEST['igualdad'];
/* **** busco el usuario **** */
$id_admin= $_SESSION['id_usuario_'.empresa];
$q_admin=("select admin.*,sucursales.* from admin where admin.id_admin='$id_admin' and admin.id_sucursal=sucursales.id_sucursal");
$r_admin=ejecutar($q_admin);
$f_admin=asignar_a($r_admin);


list($id_estado,$estado)=explode("@",$_REQUEST['id_estado']);
if($id_estado==0)	        $condicion_estado="procesos.id_estado_proceso>0";
else
$condicion_estado="procesos.id_estado_proceso='$id_estado'";





$q_reem_espe=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,estados_procesos.estado_proceso,procesos.id_proceso, procesos.id_beneficiario from clientes,procesos,entes,titulares,gastos_t_b,estados_procesos where $condicion_estado and procesos.fecha_recibido>='$fechaini' and procesos.fecha_recibido<='$fechafin' and procesos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and gastos_t_b.id_servicio=1 and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso=estados_procesos.id_estado_proceso
group by
 clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,estados_procesos.estado_proceso,procesos.id_proceso, procesos.id_beneficiario order by procesos.id_proceso DESC"); 
$r_reem_espe=ejecutar($q_reem_espe);



?>

<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form action="POST" method="post" name="reemesp">
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo">

</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
</td>
<td colspan=3 class="titulo1">
<?php echo "$f_admin[sucursal] $fechaimpreso"?>
</td>
</tr>

<tr>		<td colspan=8 class="titulo3">Relacion de Reembolsos con Monto <?php echo $igualdad?> a  <?php echo formato_montos($monto); echo     " Desde $fechaini Hasta $fechafin "?></td>	</tr>	
<tr>
		<td colspan=8 class="tdcampos"><hr> </hr></td>
		 
		</tr>
<tr>
		<td class="tdcampos">Num</td>
		<td class="tdcampos">Proceso</td>
		<td class="tdcampos">Titular</td>
		<td class="tdcampos">Cedula</td>
		<td class="tdcampos">Beneficiario</td>
		<td class="tdcampos">Cedula</td>
		<td class="tdtitulos">Estado</td>
		<td class="tdcampos">Ente</td>
		<td class="tdcampos">Monto</td> 
		</tr>
<?php
$i=0;
if ($igualdad==">"){
while($f_reem_espe = asignar_a($r_reem_espe)){
			
$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$f_reem_espe[id_proceso]");
$rgasto=ejecutar($qgasto);

		$bsf=0;

	while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC))
		{
		  $bsf= $bsf + ($fgasto[monto_aceptado]);}

	 
	if ($bsf > $monto){ 
	if ($f_reem_espe[id_beneficiario]>0)
	{
	$q_beneficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,procesos,beneficiarios where procesos.id_proceso=$f_reem_espe[id_proceso] and procesos.id_beneficiario=beneficiarios.id_beneficiario and beneficiarios.id_cliente=clientes.id_cliente");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
}
	$i++
?>
	<tr>
		<td class="datos_cliente"><?php echo $i?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[id_proceso]?></td>
		<td class="datos_cliente"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[cedula]?></td>
		<td class="datos_cliente"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_beneficiario[cedula]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[estado_proceso]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[nombre]?></td>
		<td class="datos_cliente"><?php echo $bsf?></td> 
		</tr>
<?php
}
}
}

if ($igualdad=="<"){
while($f_reem_espe = asignar_a($r_reem_espe)){
			
$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$f_reem_espe[id_proceso]");
$rgasto=ejecutar($qgasto);

		$bsf=0;

	while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC))
		{
		  $bsf= $bsf + ($fgasto[monto_aceptado]);}

	 
	if ($bsf < $monto){ 
	if ($f_reem_espe[id_beneficiario]>0)
	{
	$q_beneficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,procesos,beneficiarios where procesos.id_proceso=$f_reem_espe[id_proceso] and procesos.id_beneficiario=beneficiarios.id_beneficiario and beneficiarios.id_cliente=clientes.id_cliente");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
}
	$i++
?>
	<tr>
		<td class="datos_cliente"><?php echo $i?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[id_proceso]?></td>
		<td class="datos_cliente"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[cedula]?></td>
		<td class="datos_cliente"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_beneficiario[cedula]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[estado_proceso]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[nombre]?></td>
		<td class="datos_cliente"><?php echo $bsf?></td> 
		</tr>
<?php
}
}
}

if ($igualdad=="="){
while($f_reem_espe = asignar_a($r_reem_espe)){
			
$qgasto=("select gastos_t_b.monto_aceptado from gastos_t_b where gastos_t_b.id_proceso=$f_reem_espe[id_proceso]");
$rgasto=ejecutar($qgasto);

		$bsf=0;

	while($fgasto=asignar_a($rgasto,NULL,PGSQL_ASSOC))
		{
		  $bsf= $bsf + ($fgasto[monto_aceptado]);}
 
	if ($bsf == $monto){ 
	if ($f_reem_espe[id_beneficiario]>0)
	{
	$q_beneficiario=("select clientes.nombres,clientes.apellidos,clientes.cedula from clientes,procesos,beneficiarios where procesos.id_proceso=$f_reem_espe[id_proceso] and procesos.id_beneficiario=beneficiarios.id_beneficiario and beneficiarios.id_cliente=clientes.id_cliente");
$r_beneficiario=ejecutar($q_beneficiario);
$f_beneficiario=asignar_a($r_beneficiario);
}
	$i++
?>
	<tr>
		<td class="datos_cliente"><?php echo $i?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[id_proceso]?></td>
		<td class="datos_cliente"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[cedula]?></td>
		<td class="datos_cliente"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td>
		<td class="datos_cliente"><?php echo $f_beneficiario[cedula]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[estado_proceso]?></td>
		<td class="datos_cliente"><?php echo $f_reem_espe[nombre]?></td>
		<td class="datos_cliente"><?php echo $bsf?></td> 
		</tr>
<?php
}
}
}
?>
<tr>
		<td colspan=8 class="tdcampos"><hr> </hr></td>
		 
		</tr>
<tr>
		<td colspan=2 class="tdtitulos">Realizado Por:</td>
		<td colspan=2 class="tdtitulos"><?php echo $f_admin[nombres]?></td>
		<td  colspan=2 class="tdcampos">Recibido Por:</td>
		<td colspan=2 class="tdtitulos"></td>
		
		
		 
		</tr>
</table>


</form>
