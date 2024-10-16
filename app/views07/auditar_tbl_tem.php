<?php
include ("../../lib/jfunciones.php");
sesion();

$q_audi_tbl_tem= "select 
								*
						from 
								tbl_eliminar_tablas";
$q_audi_tbl_tem = ejecutar($q_audi_tbl_tem);
?>

<link HREF="../../public/stylesheets/estilos.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<form method="get" onsubmit="return false;" id="rep_ente">
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr>		<td colspan=4 class="titulo_seccion">Auditar Tablas Temporales Creadas (Eliminacion de las Tablas)</td>	</tr>	
 <tr> 
		<td class="tdcampos"  colspan="1"> </td>
		<td class="tdcampos"  colspan="1">Nombre de la Tablas </td> 
		</tr>
				<?php
				$i=0;
		while($f_audi_tbl_tem = asignar_a($q_audi_tbl_tem)){
			
				 /* **** Eliminar tabla temporal**** */

  $e_tabla_tem = "drop table $f_audi_tbl_tem[nombre_tbl_eli]";
$re_tabla_tem = ejecutar($e_tabla_tem);

		$i++;
		?>
	<tr> 
		  <td class="tdtitulos" colspan="1"><?php echo $i?></td>

	
		<td class="tdcampos"  colspan="1"><?php echo $f_audi_tbl_tem[nombre_tbl_eli]?> </td> 
</tr>

		<tr>
		  <td class="tdtitulos" colspan="4"><hr></hr></td> 
		</tr>
	<?php
	$f_audi_tbl_eli=("delete  from 
												tbl_eliminar_tablas
										where
												tbl_eliminar_tablas.nombre_tbl_eli='$f_audi_tbl_tem[nombre_tbl_eli]'");
$r_audi_tbl_eli=ejecutar($f_audi_tbl_eli);
	}
	?>
				
</table>
