<?php
include ("../../lib/jfunciones.php");

$tipo_paragrafo1=noacento($_REQUEST['tipo_paragrafo1']);
$paragrafo1=noacento($_REQUEST['paragrafo1']);
$proceso=noacento($_REQUEST['proceso']);
$tipo_rechazo=noacento($_REQUEST['tipo_rechazo']);
$conexa=noacento($_REQUEST['conexa']);
$tipo_paragrafo2=split("@",$tipo_paragrafo1);
$paragrafo2=split("@",$paragrafo1);

$fecha=date("Y");
$fechacreado=date("Y-m-d");
$hora=date("H:i:s");
$admin= $_SESSION['id_usuario_'.empresa];

	for($i=0;$i<=$conexa;$i++){
	$tipo_paragrafo=$tipo_paragrafo2[$i];
	$paragrafo=$paragrafo2[$i];
		if ($tipo_paragrafo<>""){
			$paragrafos .=$tipo_paragrafo ." ". $paragrafo . ", ";
              $motivo .=$tipo_paragrafo. $paragrafo. ", ";
		
			}
}

if ($tipo_rechazo==1){
	$mod_gasto="update gastos_t_b set monto_aceptado='0',monto_pagado='0' where
gastos_t_b.id_proceso=$proceso";
$fmod_gasto=ejecutar($mod_gasto);
	$mod_proceso="update procesos set id_estado_proceso='6',comentarios='$paragrafos' where
procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso);
$rechazo="Total";
	}
	else
	{
        $mod_proceso="update procesos set id_estado_proceso='7',comentarios='$paragrafos' where
procesos.id_proceso=$proceso";
$fmod_proceso=ejecutar($mod_proceso);
		 $rechazo="Parcial";
		}
        $q_proceso = "select clientes.nombres,clientes.apellidos,clientes.cedula,entes.nombre,
procesos.fecha_recibido,procesos.comentarios,procesos.comentarios_gerente,
procesos.comentarios_medico,procesos.id_beneficiario,procesos.id_titular from 
clientes,entes,procesos,titulares,estados_t_b where procesos.id_proceso=$proceso and 
procesos.id_titular=titulares.id_titular and titulares.id_ente=entes.id_ente and 
titulares.id_cliente=clientes.id_cliente and titulares.id_titular=estados_t_b.id_titular and 
estados_t_b.id_estado_cliente=4 and estados_t_b.id_beneficiario=0";
$r_proceso = ejecutar($q_proceso);
$f_proceso = asignar_a($r_proceso);
$cliente="$f_proceso[nombres] $f_proceso[apellidos]";
$nomente="$f_proceso[nombre]";
$fecha_recibido="$f_proceso[fecha_recibido]";

$q_gasto = "select * from gastos_t_b where gastos_t_b.id_proceso=$proceso and 
gastos_t_b.monto_aceptado='0'";
$r_gasto = ejecutar($q_gasto);
while($f_gasto=asignar_a($r_gasto,NULL,PGSQL_ASSOC)){
$medicamento .=$f_gasto[nombre]. ", ";
}

        /* **** se registra la carta de rechazo **** */
 $r_control_cartas="insert into tbl_control_cartas 
(id_proceso,id_titular,id_beneficiario,id_tipo_control,motivo,comentario,fecha_creado) 
values ('$proceso','$f_proceso[id_titular]','$f_proceso[id_beneficiario]','$tipo_rechazo','$medicamento',' $motivo','$fechacreado');"; 
$f_control_cartas=ejecutar($r_control_cartas);
	
	

$q_cobertura="select entes.*,procesos.* from entes,titulares,procesos where 
 entes.id_ente=titulares.id_ente and titulares.id_titular=procesos.id_titular and procesos.id_proceso=$proceso";
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

/* **** Actualizo la cobertura**** */
$q_actualizar="select gastos_t_b.id_cobertura_t_b,count(coberturas_t_b.id_cobertura_t_b) from coberturas_t_b,gastos_t_b where coberturas_t_b.id_cobertura_t_b=gastos_t_b.id_cobertura_t_b and gastos_t_b.id_proceso=$proceso  group by gastos_t_b.id_cobertura_t_b";
$r_actualizar=ejecutar($q_actualizar);
while($f_actualizar=asignar_a($r_actualizar,NULL,PGSQL_ASSOC)){

$monto_actua=0;
$monto_gastos=0;
$q_propiedad="select * from propiedades_poliza,coberturas_t_b
where propiedades_poliza.id_propiedad_poliza=coberturas_t_b.id_propiedad_poliza and
coberturas_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]";
$r_propiedad=ejecutar($q_propiedad);
$f_propiedad=asignar_a($r_propiedad);

$q_cgastos="select * from gastos_t_b,procesos where
gastos_t_b.id_proceso=procesos.id_proceso and procesos.gasto_viejo='0' and
procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafinal' and
gastos_t_b.id_cobertura_t_b=$f_actualizar[id_cobertura_t_b]"; 
$r_cgastos=ejecutar($q_cgastos);
while($f_cgastos=asignar_a($r_cgastos,NULL,PGSQL_ASSOC)){
$monto_gastos= $monto_gastos +
$f_cgastos[monto_aceptado];
}
$monto_actual= $f_propiedad[monto_nuevo] - $monto_gastos;
$mod_cobertura="update coberturas_t_b set monto_actual='$monto_actual' where
coberturas_t_b.id_cobertura_t_b='$f_actualizar[id_cobertura_t_b]' and
coberturas_t_b.id_titular='$f_cobertura[id_titular]' and
coberturas_t_b.id_beneficiario='$f_cobertura[id_beneficiario]'";
$fmod_cobertura=ejecutar($mod_cobertura);

}
/* **** Fin de Actualizar la cobertura**** */
/* **** Se registra lo que hizo el usuario**** */
$admin= $_SESSION['id_usuario_'.empresa];

$log="LA ORDEN NUMERO $proceso FUE RECHAZADA $rechazo  por $motivo";
logs($log,$ip,$admin);

/* **** Fin de lo que hizo el usuario **** */
?>





<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>



	
<tr>
		<td class="tdtitulos"><?php
			$url="'views01/icartar.php?proceso=$proceso&motivo=$medicamento&comentario=$motivo&cliente=$cliente&nomente=$nomente&fecha_recibido=$fecha_recibido'";
			?> <a href="javascript: imprimir(<?php echo $url; ?>);" class="boton"> Imprimir Carta Rechazo </a><a href="#" OnClick="reg_oa();" class="boton">Registrar Orden</a><a href="#" 	OnClick="act_orden();" class="boton">Actualizar Orden</a><a href="#" OnClick="ir_principal();" class="boton">salir</a>
			</td>
	</tr>

</table>


