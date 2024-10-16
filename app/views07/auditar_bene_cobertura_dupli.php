<?php
include ("../../lib/jfunciones.php");
sesion();

$q_titu_cober_dupl= "select 	
											coberturas_t_b.id_beneficiario,
											coberturas_t_b.id_propiedad_poliza,
											count(coberturas_t_b.id_beneficiario) 
									from 
											coberturas_t_b 
									where 
											coberturas_t_b.id_beneficiario>0 
									group by 
											coberturas_t_b.id_beneficiario,
											coberturas_t_b.id_propiedad_poliza 
									order by 
											count;";
$r_titu_cober_dupl = ejecutar($q_titu_cober_dupl);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar estados_t_b titulares duplicados</td>	</tr>	
 <tr> 
		  <td class="tdtitulos" colspan="1"></td>
	  <td class="tdtitulos" colspan="1">id_beneficiario</td>
		 <td class="tdcampos"  colspan="1">id_propiedad_poliza </td>
		<td class="tdcampos"  colspan="1">count </td> 
		</tr>
				<?php
				$i=0;
		while($f_titu_cober_dupl = asignar_a($r_titu_cober_dupl)){
			if ($f_titu_cober_dupl[count]>1){
				
				
		$i++;
		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_cober_dupl[id_beneficiario]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_cober_dupl[id_propiedad_poliza]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_cober_dupl[count]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		<?php
		$q_titu_cober_dupl2= "select 
													*
											from 
													coberturas_t_b
											where 
													coberturas_t_b.id_beneficiario=$f_titu_cober_dupl[id_beneficiario] and
													coberturas_t_b.id_propiedad_poliza=$f_titu_cober_dupl[id_propiedad_poliza]
											";
			$r_titu_cober_dupl2 = ejecutar($q_titu_cober_dupl2);
			$f_titu_cober_dupl2 = asignar_a($r_titu_cober_dupl2);
			?>
			  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_cober_dupl2[id_beneficiario]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_cober_dupl2[id_propiedad_poliza]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_cober_dupl2[id_cobertura_t_b]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		
		<?php
		
				$q_titu_cober_dupl3= "select 
													*
											from 
													coberturas_t_b
											where 
													coberturas_t_b.id_beneficiario>0 and
													coberturas_t_b.id_beneficiario='$f_titu_cober_dupl2[id_beneficiario]' and
													coberturas_t_b.id_propiedad_poliza='$f_titu_cober_dupl2[id_propiedad_poliza]' and
													coberturas_t_b.id_cobertura_t_b<>'$f_titu_cober_dupl2[id_cobertura_t_b]'
											";
			$r_titu_cober_dupl3 = ejecutar($q_titu_cober_dupl3);
			
			while($f_titu_cober_dupl3 = asignar_a($r_titu_cober_dupl3)){
				
					$mod_gasto="update 
								gastos_t_b
						set  
								id_cobertura_t_b='$f_titu_cober_dupl2[id_cobertura_t_b]'                            
						where 
								gastos_t_b.id_cobertura_t_b='$f_titu_cober_dupl3[id_cobertura_t_b]'				      
";
	$fmod_gasto=ejecutar($mod_gasto);
	?>
		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		<tr>
		  <td class="tdtitulos" colspan="4"><?php echo $f_titu_cober_dupl3[id_cobertura_t_b]?></td> 
		</tr>
	<?php
				
				}
			
			$q_eli_cober_cb=("delete  from 
												coberturas_t_b
										where
												coberturas_t_b.id_beneficiario>0 and
												coberturas_t_b.id_beneficiario='$f_titu_cober_dupl2[id_beneficiario]' and
												coberturas_t_b.id_propiedad_poliza='$f_titu_cober_dupl2[id_propiedad_poliza]' and
												coberturas_t_b.id_cobertura_t_b<>'$f_titu_cober_dupl2[id_cobertura_t_b]'");
			$r_eli_cober_cb=ejecutar($q_eli_cober_cb);
		
			
		}
		}
		?>
				
</table>
