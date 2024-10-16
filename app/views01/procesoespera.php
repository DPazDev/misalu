<?php
include ("../../lib/jfunciones.php");
$admin= $_SESSION['id_usuario_'.empresa];
$id_cobertura=$_REQUEST['id_cobertura'];
$monto=$_REQUEST['monto'];
$fechare=$_REQUEST['fechare'];
$fecharci=$_REQUEST['fecharci'];
$enfermedad=$_REQUEST['enfermedad'];
$comenope=$_REQUEST['comenope'];
$servicio=$_REQUEST['servicio'];
$codigot=time();
$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$codigo=$admin . $codigot;
/*echo $id_cobertura;
echo "**1**";
echo $monto;
echo "**2**";
echo $id_proveedor;
echo "**3**";
echo $fechare;
echo "**4**";
echo $fecharci;
echo "**5**";
echo $horac;
echo "**6**";
echo $enfermedad;
echo "**7**";
echo $decrip;
echo "**8**";
echo $comenope;
echo "**9**";
echo $tiposerv;
echo "**10**";
echo $contador;*/


$q_cobertura="select * from entes,titulares,coberturas_t_b where 
 entes.id_ente=titulares.id_ente and
titulares.id_titular=coberturas_t_b.id_titular and
coberturas_t_b.id_cobertura_t_b='$id_cobertura'"; 
$r_cobertura=ejecutar($q_cobertura);
$f_cobertura=asignar_a($r_cobertura);



if ($f_cobertura[id_beneficiario]==0){
$fechainicio="$f_cobertura[fecha_inicio_contratob]";
$fechafinal="$f_cobertura[fecha_renovacion_contratob]";
}
else
{
$fechainicio="$f_cobertura[fecha_inicio_contrato]";
$fechafinal="$f_cobertura[fecha_renovacion_contrato]";
}

$admin= $_SESSION['id_usuario_'.empresa];

$r_proceso="insert into procesos (id_titular,id_beneficiario,id_estado_proceso,fecha_recibido,fecha_creado,hora_creado,comentarios,comentarios_gerente,comentarios_medico,id_admin,enfermedad_aux,id_servicio_aux,monto_temporal,codigo) 
values ('$f_cobertura[id_titular]','$f_cobertura[id_beneficiario]','13','$fechare','$fechacreado','$hora','$comenope ',' ',' ','$admin','$enfermedad','$servicio','$monto','$codigo');";
$f_proceso=ejecutar($r_proceso);

$q_cproceso="select * from procesos where procesos.codigo='$codigo'";
$r_cproceso=ejecutar($q_cproceso);
$f_cproceso=asignar_a($r_cproceso);

/* **** Se registra lo que hizo el usuario**** */

$log="REGISTRO El REEMBOLSO EN ESPERA CON ORDEN NUMERO $f_cproceso[id_proceso]";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */

?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>


<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>


<tr>		
<td colspan=4 class="titulo_seccion">La Orden  Numero <?php echo $f_cproceso[id_proceso] ?> se Coloco ne Espera<input class="campos" type="hidden" name="proceso" maxlength=128 size=20 value="<?php echo $f_cproceso[id_proceso] ?>"   > <a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a> </td>	
</tr>	

	<tr>
		<td class="tdtitulos"></td>
		<td class="tdtitulos"><?php
			$url="'views01/ireembolso.php?proceso=$f_cproceso[id_proceso]&si=1'";
			?> <a href="javascript: iop(<?php echo $url; ?>);" class="boton">Reembolso en Espera </a></td>
			
	</tr>
</table>





