<?php
include ("../../lib/jfunciones.php");
sesion();

$q_bene_dupl= "select 
								beneficiarios.id_cliente,
								count(clientes.id_cliente),
								titulares.id_titular,
								titulares.id_ente 
						from 
								beneficiarios,
								titulares,
								clientes 
						where 
								clientes.id_cliente=beneficiarios.id_cliente and 
								beneficiarios.id_titular=titulares.id_titular 
						group by 
								beneficiarios.id_cliente,
								titulares.id_titular,
								titulares.id_ente 
						order by 
								count;";
$r_bene_dupl = ejecutar($q_bene_dupl);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar la titulares duplicados</td>	</tr>	
 <tr> 
		  <td class="tdtitulos" colspan="1"></td>
	  <td class="tdtitulos" colspan="1">id_cliente</td>
		 <td class="tdcampos"  colspan="1">id_ente </td>
		<td class="tdcampos"  colspan="1">count </td> 
		</tr>
				<?php
				$i=0;
		while($f_bene_dupl = asignar_a($r_bene_dupl)){
			if ($f_bene_dupl[count]>1){
		$i++;



		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_bene_dupl[id_cliente]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_bene_dupl[id_ente]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_bene_dupl[count]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
				
		<?php
		$q_bene= "select 
								* 
						from 
								beneficiarios,
								titulares,
								entes
						where 
								beneficiarios.id_cliente=$f_bene_dupl[id_cliente] and 
								beneficiarios.id_titular=titulares.id_titular and
								titulares.id_ente=entes.id_ente and
								entes.id_ente=$f_bene_dupl[id_ente] and
								entes.id_tipo_ente>0
						order by 
								beneficiarios.id_beneficiario";
		$r_bene = ejecutar($q_bene);
		$z=0;
			while($f_bene = asignar_a($r_bene)){
				
		$q_bene_p= "select 
								* 
						from 
								procesos,
								beneficiarios,
								coberturas_t_b
						where 
								procesos.id_beneficiario=beneficiarios.id_beneficiario and
								beneficiarios.id_beneficiario=$f_bene[id_beneficiario] and
								beneficiarios.id_beneficiario=coberturas_t_b.id_beneficiario
						order by 
								beneficiarios.id_beneficiario";
		$r_bene_p = ejecutar($q_bene_p);
		$num_filasp=num_filas($r_bene_p);
		
		//if ($num_filasp==0){
			$z++;
			$f_bene_p = asignar_a($r_bene_p);
			
		//	if ($z==1){
				
				$newbene= $f_bene[id_beneficiario];
				$newcoberb=$f_bene_p[id_cobertura_t_b];
			?>
		<tr> 
		<td class="tdtitulos" colspan="1"></td>

		<td class="tdcamposr" colspan="1"><?php echo $z?></td>
		<td class="tdcamposr"  colspan="1"><?php echo "$f_bene[id_beneficiario] ---- $f_bene[id_titular]  ----  ($newcoberb)"?> </td>
		<td class="tdcamposr"  colspan="1"><?php echo $f_bene[id_cliente]?> </td> 
		</tr>

		<tr>
		 <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
			
			
			<?php
			//}
		//	else
		//	{
				?>
				
				<?php
			
			/*	$mod_proceso="update 
								procesos
						set  
								id_beneficiario='$newbene'                            
						where 
								procesos.id_beneficiario='$f_bene_p[id_beneficiario]' and
								procesos.id_titular=$f_bene_p[id_titular]				      
";
	$fmod_proceso=ejecutar($mod_proceso);
	
				$mod_gasto="update 
								gastos_t_b
						set  
								id_cobertura_t_b='$newcoberb'                            
						where 
								gastos_t_b.id_cobertura_t_b='$f_bene_p[id_cobertura_t_b]'				      
";
	$fmod_gasto=ejecutar($mod_gasto);
				*/
			
				
	//			}
			
		
			
	/*		$q_eli_bene_edo=("delete  from 
												estados_t_b
										where
												estados_t_b.id_beneficiario='$f_bene[id_beneficiario]'");
$r_eli_bene_edo=ejecutar($q_eli_bene_edo);

	$q_eli_bene_cb=("delete  from 
												coberturas_t_b
										where
												coberturas_t_b.id_beneficiario='$f_bene[id_beneficiario]'");
$r_eli_bene_cb=ejecutar($q_eli_bene_cb);
		
			$q_eli_bene_t=("delete  from 
												beneficiarios
										where
												beneficiarios.id_beneficiario='$f_bene[id_beneficiario]'");
$r_eli_bene_t=ejecutar($q_eli_bene_t);

		$q_eli_bene_tp=("delete  from 
												tipos_b_beneficiarios
										where
												tipos_b_beneficiarios.id_beneficiario='$f_bene[id_beneficiario]'");
$r_eli_bene_tp=ejecutar($q_eli_bene_tp);
			
			
			}*/
			}
		
		
		}
		}
		?>
				
</table>
