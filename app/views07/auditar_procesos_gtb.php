<?php
include ("../../lib/jfunciones.php");
sesion();

$q_procesos= "select 
                                                    p.id_proceso,
													p.id_estado_proceso,
													p.fecha_recibido,
													p.comentarios,
													p.nu_planilla,
													p.id_admin,
													p.id_servicio_aux,
													p.factura_final,
													e.estado_proceso,
													a.nombres,
													a.apellidos
                                        from 
                                                    procesos p,
                                                    estados_procesos e,
													admin a
                                        where 
													p.id_estado_proceso=e.id_estado_proceso and
													e.id_estado_proceso>13 and 
													p.id_admin=a.id_admin and
													p.id_servicio_aux<>1 and
                                        not exists
                                                    (select
                                                                * 
                                                    from 
                                                                gastos_t_b g
                                                    where  
                                                                p.id_proceso=g.id_proceso)  
													order by

																p.id_admin,p.fecha_recibido";
$r_procesos = ejecutar($q_procesos);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=6 class="titulo_seccion">Auditar Procesos que se Encuentren sin Gastos</td>	</tr>	
 <tr> 
		<td class="tdtitulos" colspan="1"></td>
	    <td class="tdtitulos" colspan="1">id_proceso</td>
		<td class="tdtitulos"  colspan="1">id_estado_proceso </td>
		<td class="tdtitulos"  colspan="1">Estado Proceso</td>  
		<td class="tdtitulos"  colspan="1">Fecha Recibido</td> 
		<td class="tdtitulos"  colspan="1">Fecha Recibido</td> 
		</tr>
				<?php
				$i=0;
		while($f_procesos = asignar_a($r_procesos)){
			$i++;
		


	?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

		<td class="tdtitulos" colspan="1"><?php echo $f_procesos[id_proceso]?></td>
		<td class="tdcampos"  colspan="1"><?php echo $f_procesos[id_estado_proceso]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_procesos[no_clave]?> </td> 
		<td class="tdcampos"  colspan="1"><?php echo "$f_procesos[nombres] $f_procesos[apellidos] $f_procesos[id_admin] "?></td> 
		 <td class="tdcampos"  colspan="1"><?php echo $f_procesos[comentarios]?> </td> 
	</tr>
		<tr>
		 <td class="tdtitulos" colspan="5"><hr></hr></td> 
		</tr>
		<?php
		
		}
		?>
				
</table>
