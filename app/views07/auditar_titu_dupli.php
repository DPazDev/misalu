<?php
include ("../../lib/jfunciones.php");
sesion();

$q_titu_dupl= "select 
								titulares.id_cliente,
								titulares.id_ente,
								count(titulares.id_cliente) 
						from 
								titulares 
						group by 
								titulares.id_cliente,
								titulares.id_ente 
						order by 
								count,titulares.id_ente";
$r_titu_dupl = ejecutar($q_titu_dupl);
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
		while($f_titu_dupl = asignar_a($r_titu_dupl)){
			if ($f_titu_dupl[count]>1){
		$i++;



		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_dupl[id_cliente]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_dupl[id_ente]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_dupl[count]?> </td> 
</tr>
<?php
		$q_titu_d= "select 
								titulares.* ,
								coberturas_t_b.*
							from 
								titulares,
								coberturas_t_b,
								entes
							where
								titulares.id_cliente='$f_titu_dupl[id_cliente]' and
								titulares.id_ente='$f_titu_dupl[id_ente]' and 
								titulares.id_titular=coberturas_t_b.id_titular and
								titulares.id_ente=entes.id_ente and
								entes.id_tipo_ente=4
							order by 
								titulares.id_titular";
$r_titu_d= ejecutar($q_titu_d);
$j=0;
while($f_titu_d = asignar_a($r_titu_d)){
		
		$q_titu_p= "select 
									* 
							from
									procesos
							where 
									procesos.id_titular=$f_titu_d[id_titular]";
		$r_titu_p= ejecutar($q_titu_p);
		$num_filasp=num_filas($r_titu_p);
			
		$q_titu_b= "select 
									* 
							from
									beneficiarios
							where 
									beneficiarios.id_titular=$f_titu_d[id_titular]";
		$r_titu_b= ejecutar($q_titu_b);
		$num_filasb=num_filas($r_titu_b);
			$z=0;
		if ($num_filasp==0 and $num_filasb>0){
			$j++;
	 if ($j==1){
		
		$newtitu=$f_titu_d[id_titular];
		$newidcobertura=$f_titu_d[id_cobertura_t_b];
?>
			<tr> 
		  <td class="tdtitulos" colspan="1"></td>

	  <td class="tdcamposr" colspan="1"><?php echo $j?></td>
		 <td class="tdcamposr"  colspan="1"><?php echo $f_titu_d[id_titular]?> </td>
		<td class="tdcamposr"  colspan="1"><?php echo $f_titu_d[id_cobertura_t_b]?> </td> 
</tr>


		<?php
		}
		else
		{
			
					$q_gasto_p= "select 
									* 
							from
									procesos,
									titulares
							where 
									procesos.id_titular='$f_titu_d[id_titular]' and
									procesos.id_titular=titulares.id_titular and procesos.id_beneficiario=0 and procesos.id_titular<>$newtitu ";
		$r_gasto_p= ejecutar($q_gasto_p);
		?>
		<tr> 
		<td class="tdtitulos" colspan="1"></td>
		
		<td class="tdcampos"  colspan="1">contador </td>
		<td class="tdcampos"  colspan="1"> Proceso</td> 
		<td class="tdcampos" colspan="1">id_titular</td>
		</tr>
		<?php
	
		while($f_gasto_p = asignar_a($r_gasto_p)){
			$z++;
			?>
			
		<tr> 
		<td class="tdtitulos" colspan="1"></td>
		
		<td class="tdcamposr"  colspan="1"><?php echo $z?> </td>
		<td class="tdcamposr"  colspan="1"><?php echo $f_gasto_p[id_proceso]?> </td> 
		<td class="tdcamposr" colspan="1"><?php echo "$f_gasto_p[id_titular] $f_gasto_p[id_beneficiario]"?> <?php echo "$newtitu ---- $newidcobertura"?></td>
		</tr>
			
			
			<?
	
	/*		
		$mod_proceso="update 
								procesos
						set  
								id_titular='$newtitu'                            
						where 
								procesos.id_proceso='$f_gasto_p[id_proceso]'				      
";
	$fmod_proceso=ejecutar($mod_proceso); 

$mod_procesob="update 
								beneficiarios
						set  
								id_titular='$newtitu'                            
						where 
								beneficiarios.id_beneficiario='$f_gasto_p[id_beneficiario]'				      
";
	$fmod_procesob=ejecutar($mod_procesob);

$mod_procesobe="update 
								estados_t_b
						set  
								id_titular='$newtitu'                            
						where 
								estados_t_b.id_beneficiario='$f_gasto_p[id_beneficiario]'				      
";
	$fmod_procesobe=ejecutar($mod_procesobe);
	
	$mod_procesobc="update 
								coberturas_t_b
						set  
								id_titular='$newtitu'                            
						where 
								coberturas_t_b.id_beneficiario='$f_gasto_p[id_beneficiario]'				      
";
	$fmod_procesobc=ejecutar($mod_procesobc);


				$mod_gasto="update 
								gastos_t_b
						set  
								id_cobertura_t_b='$newidcobertura'                            
						where 
								gastos_t_b.id_proceso='$f_gasto_p[id_proceso]'				      
";
	$fmod_gasto=ejecutar($mod_gasto);
			*/
			
			}
			}
	/*	$q_eli_titu_sub=("delete  from 
												titulares_subdivisiones
										where
												titulares_subdivisiones.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_sub=ejecutar($q_eli_titu_sub);

	$q_eli_titu_p=("delete  from 
												titulares_polizas
										where
												titulares_polizas.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_p=ejecutar($q_eli_titu_p);

	$q_eli_titu_c=("delete  from 
												titulares_cargos
										where
												titulares_cargos.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_c=ejecutar($q_eli_titu_c);

	$q_eli_titu_edo=("delete  from 
												estados_t_b
										where
												estados_t_b.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_edo=ejecutar($q_eli_titu_edo);

	$q_eli_titu_cb=("delete  from 
												coberturas_t_b
										where
												coberturas_t_b.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_cb=ejecutar($q_eli_titu_cb);
		
			$q_eli_titu_t=("delete  from 
												titulares
										where
												titulares.id_titular='$f_titu_d[id_titular]'");
$r_eli_titu_t=ejecutar($q_eli_titu_t);*/
		
		}
		}
		?>
		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
		<?php
		}
		}
		?>
				
</table>
