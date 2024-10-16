<?php 
include ("../../lib/jfunciones.php"); 
sesion(); 
$monto=$_POST['monto']; 
$fechaini=$_POST['dateField1']; 
$fechafin=$_POST['dateField2']; 
$igualdad=$_POST['igualdad']; 


list($id_estado,$estado)=explode("@",$_REQUEST['estapro']); 
if($id_estado==0)	        $condicion_estado="procesos.id_estado_proceso>0"; 
else 
$condicion_estado="procesos.id_estado_proceso='$id_estado'"; 


$q_reem_espe=("select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,estados_procesos.estado_proceso,procesos.id_proceso, procesos.id_beneficiario from clientes,procesos,entes,titulares,gastos_t_b,estados_procesos where $condicion_estado and procesos.fecha_recibido>='$fechaini' and procesos.fecha_recibido<='$fechafin' and procesos.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente and titulares.id_ente=entes.id_ente and gastos_t_b.id_servicio=1 and gastos_t_b.id_proceso=procesos.id_proceso and procesos.id_estado_proceso=estados_procesos.id_estado_proceso
group by
 clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,estados_procesos.estado_proceso,procesos.id_proceso, procesos.id_beneficiario order by procesos.id_proceso DESC"); 
$r_reem_espe=ejecutar($q_reem_espe); 


?> 

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css"> 
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script> 

<form action="POST" method="post" name="reemesp"> 
<table class="tabla_citas"  cellpadding=0 cellspacing=0> 


<tr>		<td colspan=10 class="titulo_seccion">Relación de Reembolsos con Monto <?php echo $igualdad?>  a  <?php echo formato_montos($monto) ?> Bolivares</td>	</tr> 

<tr><td>&nbsp;</td></tr> 
	 
<tr> 
		<td class="tdtitulos">Num</td> 
		<td class="tdtitulos">Proceso</td> 
		<td class="tdtitulos">Titular</td> 
		<td class="tdtitulos">Cedula</td> 
		<td class="tdtitulos">Beneficiario</td> 
		<td class="tdtitulos">Cedula</td> 
		<td class="tdtitulos">Estado</td> 
		<td class="tdtitulos">Ente</td> 
		<td class="tdtitulos">Monto</td> 
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
		<td class="tdcampos"><?php echo $i?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[id_proceso]?></td> 
		<td class="tdcampos"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[cedula]?></td> 
		<td class="tdcampos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_beneficiario[cedula]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[estado_proceso]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[nombre]?></td> 
		<td class="tdcampos"><?php echo $bsf?></td> 
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
		<td class="tdcampos"><?php echo $i?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[id_proceso]?></td> 
		<td class="tdcampos"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[cedula]?></td> 
		<td class="tdcampos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_beneficiario[cedula]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[estado_proceso]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[nombre]?></td> 
		<td class="tdcampos"><?php echo $bsf?></td> 
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
		<td class="tdcampos"><?php echo $i?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[id_proceso]?></td> 
		<td class="tdcampos"><?php echo "$f_reem_espe[nombres] $f_reem_espe[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[cedula]?></td> 
		<td class="tdcampos"><?php echo "$f_beneficiario[nombres] $f_beneficiario[apellidos]"?></td> 
		<td class="tdcampos"><?php echo $f_beneficiario[cedula]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[estado_proceso]?></td> 
		<td class="tdcampos"><?php echo $f_reem_espe[nombre]?></td> 
		<td class="tdcampos"><?php echo $bsf?></td> 
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
		<td class="tdtitulos"></td> 
		<td class="tdcampos"></td> 
		<td class="tdtitulos"></td> 
		<td class="tdtitulos"></td> 
		<td class="tdcampos"></td> 
		<td class="tdtitulos"></td> 
		<td class="tdtitulos"></td> 
		<td class="tdcampos"><?php 
			$url="'views06/ireem_esp.php?monto=$monto&fechaini=$fechaini&fechafin=$fechafin&igualdad=$igualdad&id_estado=$id_estado'"; 
			?><a href="javascript: imprimir(<?php echo $url; ?>);"class="boton"> Imprimir</a></td> 
		 
		</tr> 
</table> 
<div id="bus_rep_reem_esp"></div> 

</form>
