<?php
include ("../../lib/jfunciones.php");
sesion();

$q_titu_poliza_dupl= "select 
								titulares_polizas.id_titular,
								titulares_polizas.id_poliza,
								count(titulares_polizas.id_titular) 
						from 
								titulares_polizas  
						group by 
								titulares_polizas.id_titular,
								titulares_polizas.id_poliza 
						order by 
								count;";
$r_titu_poliza_dupl = ejecutar($q_titu_poliza_dupl);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar estados_t_b titulares duplicados</td>	</tr>	
 <tr> 
		  <td class="tdtitulos" colspan="1"></td>
	  <td class="tdtitulos" colspan="1">id_titular</td>
		 <td class="tdcampos"  colspan="1">id_poliza </td>
		<td class="tdcampos"  colspan="1">count </td> 
		</tr>
				<?php
				$i=0;
		while($f_titu_poliza_dupl = asignar_a($r_titu_poliza_dupl)){
			if ($f_titu_poliza_dupl[count]>1){
				
				
		$i++;
		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_poliza_dupl[id_titular]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_poliza_dupl[id_poliza]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_poliza_dupl[count]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		<?php
		
		$q_titu_poliza_dupl2= "select 
													*
											from 
													titulares_polizas
											where 
													titulares_polizas.id_titular=$f_titu_poliza_dupl[id_titular] and
													titulares_polizas.id_poliza=$f_titu_poliza_dupl[id_poliza]
											";
			$r_titu_poliza_dupl2 = ejecutar($q_titu_poliza_dupl2);
			$f_titu_poliza_dupl2 = asignar_a($r_titu_poliza_dupl2);
			?>
			  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_poliza_dupl2[id_titular]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_poliza_dupl2[id_titular_poliza]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_poliza_dupl2[id_poliza]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		
		<?php
			$q_eli_titu_poliza=("delete  from 
												titulares_polizas
										where
												titulares_polizas.id_titular=$f_titu_poliza_dupl2[id_titular] and
												titulares_polizas.id_poliza=$f_titu_poliza_dupl2[id_poliza] and
												titulares_polizas.id_titular_poliza<>$f_titu_poliza_dupl2[id_titular_poliza]
									");
			$r_eli_titu_poliza=ejecutar($q_eli_titu_poliza);
		}
		}
		?>
				
</table>
