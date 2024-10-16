<?php
include ("../../lib/jfunciones.php");
sesion();

list($ente,$id_titular,$id_beneficiario)=explode("-",$_REQUEST['selecttitu']);

list($ente1,$id_titular1,$id_beneficiario1)=explode("-",$_REQUEST['selectbeni']);

/*echo $ente;
echo $ente1;*/
if($ente>0){

$q_poli_entet=("select polizas_entes.id_poliza, polizas.nombre_poliza from polizas_entes,polizas where polizas_entes.id_ente='$ente' and polizas_entes.id_poliza=polizas.id_poliza");
$r_poli_entet=ejecutar($q_poli_entet);

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>

<tr>
		<td colspan=4 class="titulo_seccion">POLIZA A SELECCIONAR</td>
	</tr>


	<tr> <td>&nbsp;</td>	</tr>


<tr>
<td colspan=1 class="tdtitulos">&nbsp; &nbsp; Seleccione la Poliza para el Titular </td>
	
<td class="tdcampos" colspan="1" >
<select class="campos" id="polizass" name="polizass">
		<option value="">--Seleccione la poliza--</option>

		<?php
while($f_poli_entet=asignar_a($r_poli_entet,NULL,PGSQL_ASSOC)){
		echo "<option value=\"$f_poli_entet[id_poliza]@$f_poli_entet[nombre_poliza]\" >$f_poli_entet[nombre_poliza]</option>";
			
		}
		?>
		</select>
		</td>

<?php }
else 
if($ente1>0){

$q_poli_enteb=("select polizas_entes.id_poliza, polizas.nombre_poliza from polizas_entes,polizas where polizas_entes.id_ente='$ente1' and polizas_entes.id_poliza=polizas.id_poliza");
$r_poli_enteb=ejecutar($q_poli_enteb);

?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>

<tr>
		<td colspan=4 class="titulo_seccion">POLIZA A SELECCIONAR</td>
	</tr>
	<tr> <td>&nbsp;</td>	</tr>

<tr>
<td colspan=1 class="tdtitulos">&nbsp; &nbsp; Seleccione la Poliza para el Beneficiario</td>
	
<td class="tdcampos" colspan="1" >
<select class="campos" id="polizass" name="polizass">
		<option value="">--Seleccione la poliza--</option>

		<?php
while($f_poli_enteb=asignar_a($r_poli_enteb,NULL,PGSQL_ASSOC)){
		echo "<option value=\"$f_poli_enteb[id_poliza]@$f_poli_enteb[nombre_poliza]\" >$f_poli_enteb[nombre_poliza]</option>";
			
		}
		?>
		</select>
		</td>
 <? }



?>
</tr>
</table>
<div id="bus_poliza"></div>
