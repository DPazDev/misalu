<?php
include ("../../lib/jfunciones.php");
sesion();
$busente=("select entes.id_ente,entes.nombre,entes.rif,entes.fecha_creado,entes.fecha_inicio_contrato,
                  entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob,
                  entes.codente,entes.forma_pago,tbl_tipos_entes.tipo_ente
           from
                  entes,tbl_tipos_entes
           where
                   entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and entes.id_tipo_ente=2
           order by
                  entes.nombre");
$repbusente=ejecutar($busente);                  
?>

<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">Buscar Ente</td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
   <td colspan=1 class="tdtitulos">Ente:</td>
   <td colspan=3 class="tdcampos" ><select id="lente" class="campos"  style="width: 320px;" onchange="Buscarente(this)">
			       <option value=""></option>
                    <?php  
			         while($ventes=asignar_a($repbusente,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo "$ventes[id_ente]"?>"> <?php echo "$ventes[nombre]"?></option>
			      <?}?>
   
   </td>
 </tr>
</table> 
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='datadelente'></div>
