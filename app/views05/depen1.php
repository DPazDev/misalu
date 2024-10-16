<?php
include ("../../lib/jfunciones.php");
sesion();
$dependenom=("select tbl_dependencias.dependencia,tbl_dependencias.id_dependencia,tbl_dependencias.esalmacen from tbl_dependencias order by dependencia;");
$repuestadepen=ejecutar($dependenom);
$numfidepen=num_filas($repuestadepen);
?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<br>
     <tr>
        <td class="tdtitulos">Nombre de la dependencia:</td>
        <td class="tdcampos"><input type="text" id="depenom" class="campos" size="35"></td>  
		<td class="tdcampos"><input type="hidden" id="iddpen" class="campos" size="5"></td>  
	</tr>
	<tr>
	    <td class="tdtitulos">Es la dependencia almacen central?</td>
        <td class="tdcampos">
		   <INPUT TYPE=RADIO NAME="depencia" id='siesdep' VALUE="1">Si<BR>
           <INPUT TYPE=RADIO NAME="depencia" id='noesdep' VALUE="0">No<BR>
		</td>  
	</tr>
	<tr>
	   <td  title="Guardar dependencia"><label class="boton" style="cursor:pointer" onclick="guardadepen()" >Guardar </label></td> 
	</tr>
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<br>
<? if ($numfidepen>=1){?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Dependencias registradas</td>  
     </tr>
</table>	 
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
        <th class="tdtitulos">No.</th>
        <th class="tdtitulos">Nombre.</th>
		<th class="tdtitulos">Es almacen central.</th>
	</tr>
	<? $i=1;
	      while($datosdepen=asignar_a($repuestadepen,NULL,PGSQL_ASSOC)){
			$tipalmacen=$datosdepen['esalmacen']; 
			if($tipalmacen==0){
				$qtipoes='No';
				}else{$qtipoes='Si';}
	?>
	<tr>
	    <td class="tdcampos"><?echo $i;?></td>
		<td class="tdcampos"><?echo $datosdepen[dependencia];?></td> 
		<td class="tdcampos"><?echo $qtipoes;?></td> 
		<td  title="Modificar dependencia"><label class="boton" style="cursor:pointer" onclick="modifdepen(<?echo $datosdepen[id_dependencia];?>,'<?echo $datosdepen[dependencia];?>','<?echo $qtipoes?>')" >Modificar </label></td>
	</tr>
	<?
	$i++;
	}
	?>
</table>	
<?}?>
