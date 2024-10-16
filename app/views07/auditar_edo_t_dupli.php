<?php
include ("../../lib/jfunciones.php");
sesion();

$q_edo_dupl= "select 
									estados_t_b.id_titular,
									count(estados_t_b.id_titular) 
							from 
									estados_T_b
							where 
									estados_t_b.id_beneficiario=0 
							group by 
									estados_t_b.id_titular 
							order by 
									count";
$r_edo_dupl = ejecutar($q_edo_dupl);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar estados_t_b titulares duplicados</td>	</tr>	
 <tr> 
		  <td class="tdtitulos" colspan="1"></td>
	  <td class="tdtitulos" colspan="1"></td>
		 <td class="tdcampos"  colspan="1">id_titular </td>
		<td class="tdcampos"  colspan="1">count </td> 
		</tr>
				<?php
				$i=0;
		while($f_edo_dupl = asignar_a($r_edo_dupl)){
			if ($f_edo_dupl[count]>1){
				
				
		$i++;
		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_edo_dupl[id_titular]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_edo_dupl[count]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		<?php
		
		$q_edo_dupl2= "select 
													*
											from 
													estados_T_b
											where 
													estados_t_b.id_beneficiario=0 and
													estados_t_b.id_titular=$f_edo_dupl[id_titular]
											";
			$r_edo_dupl2 = ejecutar($q_edo_dupl2);
			$f_edo_dupl2 = asignar_a($r_edo_dupl2);
			?>
			  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_edo_dupl2[id_estado_t_b]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_edo_dupl2[id_titular]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		
		<?php
			$q_eli_titu_edo=("delete  from 
												estados_t_b
										where
												estados_t_b.id_beneficiario='0' and
												estados_t_b.id_titular=$f_edo_dupl2[id_titular] and
												estados_t_b.id_estado_t_b<>$f_edo_dupl2[id_estado_t_b]
									");
			$r_eli_titu_edo=ejecutar($q_eli_titu_edo);
		}
		}
		?>
				
</table>
