<?php
include ("../../lib/jfunciones.php");
sesion();

$q_titu_sub= "select 
									titulares.id_titular,
									count(titulares_subdivisiones.id_titular),
									titulares_subdivisiones.id_subdivision 
						from 
									titulares,
									titulares_subdivisiones 
						where 
									titulares.id_titular=titulares_subdivisiones.id_titular  
						group by
									titulares.id_titular,
									titulares_subdivisiones.id_subdivision  
						order by 
									count";
$r_titu_sub = ejecutar($q_titu_sub);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar la tabla titulares_subdivisiones</td>	</tr>	
 <tr> 
		  <td class="tdtitulos" colspan="1"></td>
	  <td class="tdtitulos" colspan="1">id_titular</td>
		 <td class="tdcampos"  colspan="1">contador </td>
		<td class="tdcampos"  colspan="1">id_subdivision </td> 
		</tr>
				<?php
				$i=0;
		while($f_titu_sub = asignar_a($r_titu_sub)){
			if ($f_titu_sub[count]>1){
/* **** eliminar resultados para ser**** */
$q_eli_titu_sub=("delete  from 
												titulares_subdivisiones
										where
												titulares_subdivisiones.id_titular='$f_titu_sub[id_titular]'");
$r_eli_titu_sub=ejecutar($q_eli_titu_sub);

$q_iner_titu_sub = "insert 
							into 
					titulares_subdivisiones 
							(id_titular,
							id_subdivision) 
					values
							('$f_titu_sub[id_titular]',
							'$f_titu_sub[id_subdivision]')";
$r_iner_titu_sub = ejecutar($q_iner_titu_sub);
				$i++;
		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_sub[id_titular]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_sub[count]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_sub[id_subdivision]?> </td> 
</tr>


		<?php
		
		$q_titu_sub1= "select 
									titulares.id_titular,
									count(titulares_subdivisiones.id_titular),
									titulares_subdivisiones.id_subdivision 
						from 
									titulares,
									titulares_subdivisiones 
						where 
									titulares.id_titular=$f_titu_sub[id_titular]  and
									titulares.id_titular=titulares_subdivisiones.id_titular
						group by
									titulares.id_titular,
									titulares_subdivisiones.id_subdivision  
						order by 
									count";
$r_titu_sub1 = ejecutar($q_titu_sub1);
$f_titu_sub1 = asignar_a($r_titu_sub1);
	?>	
		<tr> 
		  <td class="tdtitulos" colspan="1">insertado</td>

	  <td class="tdtitulos" colspan="1"><?php echo $f_titu_sub1[id_titular]?></td>
		 <td class="tdcampos"  colspan="1"><?php echo $f_titu_sub1[count]?> </td>
		<td class="tdcampos"  colspan="1"><?php echo $f_titu_sub1[id_subdivision]?> </td> 
</tr>



		<?php
		}
		}
		?>
				
</table>
