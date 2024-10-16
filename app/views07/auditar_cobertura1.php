<?php
include ("../../lib/jfunciones.php");
sesion();
list($id_ente,$nom_ente)=explode("@",$_POST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_POST['tipo_ente']);

if ($id_ente==0){
	$ente="and entes.id_ente>0";
	}
	else
	{
		$ente="and entes.id_ente=$id_ente";
		}
		
if ($tipo_ente==0){
	$tipo_ente="and tbl_tipos_entes.id_tipo_ente>0";
	}
	else
	{
		$tipo_ente="and tbl_tipos_entes.id_tipo_ente=$tipo_ente";
		}

$q_coberturas= "select 		
									coberturas_t_b.*,
									entes.fecha_inicio_contrato,
									entes.fecha_renovacion_contrato,
									entes.fecha_inicio_contratob,
									entes.fecha_renovacion_contratob,
									propiedades_poliza.monto_nuevo,
									propiedades_poliza.monto 
							from 
									coberturas_t_b,
									titulares,
									entes,
									propiedades_poliza,
                                    tbl_tipos_entes 
							where 
									coberturas_t_b.id_titular=titulares.id_titular and 
									titulares.id_ente=entes.id_ente 
									$ente and
									entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente  
									$tipo_ente and								
									coberturas_t_b.id_propiedad_poliza=propiedades_poliza.id_propiedad_poliza  
							order by 
									coberturas_t_b.id_titular";
$r_coberturas = ejecutar($q_coberturas);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
	<tr> 
	     <td class="tdtitulos" colspan="4"><hr></hr></td> 
 
	</tr>
	<tr> 
	     <td class="tdtitulos" colspan="1"></td>
		 <td class="tdtitulos"  colspan="1">Monto Propiedad</td>
		 <td class="tdtitulos"  colspan="1">Monto Gastado</td>
		<td class="tdtitulos"  colspan="1">Monto Auditado</td> 
 
	</tr>

		<?php
		while($f_coberturas = asignar_a($r_coberturas)){
			$i++;
	/* **** Verifico si es titular o beneficiario para comparar la fecha de inicio de contrato y empiezo a buscar sus gastos para restarselos al monto original de la cobertura**** */		
			if  ($f_coberturas[id_beneficiario]==0){
						$fechainicio="$f_coberturas[fecha_inicio_contrato]";
						$fechafin="$f_coberturas[fecha_renovacion_contrato]";
				}
				else
				{
						$fechainicio="$f_coberturas[fecha_inicio_contratob]";
						$fechafin="$f_coberturas[fecha_renovacion_contratob]";
					}
			
			$q_gastos= "select 		
									gastos_t_b.monto_aceptado 
							from 
									gastos_t_b,
									procesos
							where 
									gastos_t_b.id_cobertura_t_b=$f_coberturas[id_cobertura_t_b] and
									gastos_t_b.id_proceso=procesos.id_proceso and 
									procesos.fecha_recibido>='$fechainicio' and procesos.fecha_recibido<='$fechafin'
							";
$r_gastos = ejecutar($q_gastos);
		$monto_gastos=0;
		$monto_auditado=0;
while($f_gastos = asignar_a($r_gastos)){
	$monto_gastos=	$monto_gastos + $f_gastos[monto_aceptado];
	}
	$monto_auditado=$f_coberturas[monto] - $monto_gastos;
	/* **** FIN de Verificar si es titular o beneficiario para comparar la fecha de inicio de contrato y empiezo a buscar sus gastos para restarselos al monto original de la cobertura**** */			
		?>	
	<tr> 
	     <td class="tdtitulos" colspan="1"><?php echo  $i;?></td>
		 <td class="tdcampos"  colspan="1"><?php echo  $f_coberturas[monto];?></td>
		 <td class="tdcampos"  colspan="1"><?php echo  $monto_gastos;?></td>
		<td class="tdcampos"  colspan="1"><?php echo  $monto_auditado;?></td> 
 
	</tr>
<?php
$mod_monto="update 
								coberturas_t_b
						set  
								monto_actual='$monto_auditado'                            
						where 
								coberturas_t_b.id_cobertura_t_b=$f_coberturas[id_cobertura_t_b]				      
";
	$fmod_monto=ejecutar($mod_monto);

	}
	?>
		<tr> 
	     <td class="tdtitulos" colspan="4"><?php echo  "Final"?></td>
		 </tr>
</table>
<div id="bus_ent"></div>
