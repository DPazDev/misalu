<?php
include ("../../lib/jfunciones.php");
sesion();
$numerocontr=$_REQUEST['elcontrato'];
$buscamosdatcontr=("select comisionados.id_comisionado,comisionados.nombres,comisionados.apellidos,
polizas.nombre_poliza,entes.id_ente,entes.nombre,tbl_recibo_contrato.id_recibo_contrato,entes.rif,
tbl_recibo_contrato.fecha_ini_vigencia,tbl_recibo_contrato.fecha_fin_vigencia,
tbl_contratos_entes.fecha_creado
from
tbl_recibo_contrato,tbl_contratos_entes,entes,comisionados,polizas,polizas_entes
where
tbl_recibo_contrato.num_recibo_prima='$numerocontr' and
tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
tbl_contratos_entes.id_ente=entes.id_ente and
tbl_contratos_entes.id_ente=polizas_entes.id_ente and
polizas_entes.id_poliza=polizas.id_poliza and
tbl_recibo_contrato.id_comisionado=comisionados.id_comisionado");
$repbuscontrato=ejecutar($buscamosdatcontr);
$cuantoexi=num_filas($repbuscontrato);
if($cuantoexi==0){?>
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">No existe data con el No.<?echo $numerocontr?> asociado!!</td>
</tr>
</table>
<?}else{
$datacontrato=assoc_a($repbuscontrato);
$idcomisio=$datacontrato['id_comisionado'];
$comisionado="$datacontrato[nombres] $datacontrato[apellidos]";
$elentecontrato=$datacontrato[nombre];
$fecha1=$datacontrato[fecha_ini_vigencia];
$fecha2=$datacontrato[fecha_fin_vigencia];
$cedula=$datacontrato[rif];
$lapoliza=$datacontrato[nombre_poliza];
$fechacreado=$datacontrato[fecha_creado];
$idreciboes=$datacontrato[id_recibo_contrato];
$loscomi=("select comisionados.id_comisionado,comisionados.nombres,comisionados.apellidos from comisionados order by comisionados.nombres
");
$reploscomi=ejecutar($loscomi);
?>
<input type="hidden" id="idrecibo" value="<?echo $idreciboes?>">
<input type="hidden" id="comiviejo" value="<?echo $idcomisio?>">
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">Datos del contrato No.<?echo $numerocontr?></td>
</tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos" colspan="1">Titular:</td>
       <td class="tdcampos"  colspan="1"><?echo $elentecontrato?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">C&eacute;dula:</td>
       <td class="tdcampos"  colspan="1"><?echo $cedula?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha creado:</td>
       <td class="tdcampos"  colspan="1"><?echo $fechacreado?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Poliza:</td>
       <td class="tdcampos"  colspan="1"><?echo $lapoliza?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha inicio:</td>
       <td class="tdcampos"  colspan="1"><?echo $fecha1?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha fin:</td>
       <td class="tdcampos"  colspan="1"><?echo $fecha2?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Comisionado:</td>
       <td class="tdcampos"  colspan="1"><select id="comiid" class="campos"  style="width: 230px;" >
			       <option value="<?echo $idcomisio?>"><?echo $comisionado?></option>
                    <?php  
			         while($datcomi=asignar_a($reploscomi,NULL,PGSQL_ASSOC)){
				    ?>
					<option value="<?php echo $datcomi[id_comisionado]?>"> <?php echo "$datcomi[nombres] $datcomi[apellidos]"?></option>
			      <?}?>
		        </select>  </td>  
     </tr>
     <tr>	    
         <td title="Cambiar Comisionado"><label id="titularboton" class="boton" style="cursor:pointer" onclick="cambiacomi()" >Actualizar</label>        
   </tr>
</table>     
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='fincambcomi'></div>
<?}?>
