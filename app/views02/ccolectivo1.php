<?php
include ("../../lib/jfunciones.php");
sesion();
$nombrente=$_REQUEST['nombente'];
$enteid=$_REQUEST['idente'];
$busente=("select entes.nombre,entes.rif,entes.fecha_creado,entes.fecha_inicio_contrato,
                  entes.fecha_renovacion_contrato,entes.fecha_inicio_contratob,entes.fecha_renovacion_contratob,
                  entes.codente,entes.forma_pago,entes.id_tipo_ente,tbl_tipos_entes.tipo_ente,entes_comisionados.id_comisionado,entes.trabajadores
           from
                  entes,tbl_tipos_entes,entes_comisionados
           where
                   entes.id_ente=$enteid and
                   entes.id_ente=entes_comisionados.id_ente and
                   entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente
           order by
                  entes.nombre");
$repbusente=ejecutar($busente);    
$ladaente=assoc_a($repbusente);  
//Buscar si tiene contrato el ente seleccionado
$sitiene=("select count(tbl_contratos_entes.id_contrato_ente) from tbl_contratos_entes where tbl_contratos_entes.id_ente=$enteid;");
$repsitiene=ejecutar($sitiene);
$datacuaente=assoc_a($repsitiene);
$sitiene=$datacuaente['count'];
if($sitiene>=1){?>
   <table class="tabla_cabecera3" cellpadding=0 cellspacing=0>
    <tr>
	  <td colspan=4 class="titulo_seccion"><label style="color: #ff0000"><h1>El ente <?echo $nombrente?> ya tiene un contrato colectivo!!!</h1></label></td>
       </tr>
</table>
<?}else{

//Buscar polizas asignadas al ente
$poliente=("select polizas.nombre_poliza,polizas.id_poliza from polizas,polizas_entes
where
polizas.id_poliza=polizas_entes.id_poliza and
polizas_entes.id_ente=$enteid;");
$reppoliente=ejecutar($poliente);
$cuantpoliente=num_filas($reppoliente);
?>
<input type="hidden" id="tipente" value="<?echo $ladaente[id_tipo_ente]?>">
<input type="hidden" id="finente" value="<?echo $ladaente[fecha_renovacion_contrato]?>">
<input type="hidden" id="inicoente" value="<?echo $ladaente[fecha_inicio_contrato]?>">
<input type="hidden" id="comisente" value="<?echo $ladaente[id_comisionado]?>">
<input type="hidden" id="nombreente" value="<?echo $nombrente?>">
<table class="tabla_cabecera3" cellpadding=0 cellspacing=0>  
<tr>
<td colspan=4 class="titulo_seccion">Crear contrato colectivo para el ente <?echo $nombrente?></td>
</tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
     <tr>
       <td class="tdtitulos" colspan="1">Ente:</td>
       <td class="tdcampos"  colspan="1"><?echo $ladaente[nombre]?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">RIF:</td>
       <td class="tdcampos"  colspan="1"><?echo $ladaente[rif]?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Tipo de ente:</td>
       <td class="tdcampos"  colspan="1"><?echo $ladaente[tipo_ente]?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">N&uacute;mero de trabajadores:</td>
       <td class="tdcampos"  colspan="1"><?echo $ladaente[trabajadores]?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha creado:</td>
       <td class="tdcampos"  colspan="1"><?list($anente,$msente,$diente)=explode('-',$ladaente[fecha_creado]);
                                           echo "$diente-$msente-$anente";?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha inicio contrato (Titular):</td>
       <td class="tdcampos"  colspan="1"><?list($anti,$msti,$diti)=explode('-',$ladaente[fecha_inicio_contrato]);
                                           echo "$diti-$msti-$anti";?> </td>  
       <td class="tdtitulos" colspan="1">Fecha renovaci&oacute;n contrato (Titular):</td>
       <td class="tdcampos"  colspan="1"><?list($anfti,$msfti,$difti)=explode('-',$ladaente[fecha_renovacion_contrato]);
                                           echo "$difti-$msfti-$anfti";?> </td>  
     </tr>
     <tr>
       <td class="tdtitulos" colspan="1">Fecha inicio contrato (Beneficiario):</td>
       <td class="tdcampos"  colspan="1"><?list($anbi,$msbi,$dibi)=explode('-',$ladaente[fecha_inicio_contratob]);
                                           echo "$dibi-$msbi-$anbi";?> </td>  
       <td class="tdtitulos" colspan="1">Fecha renovaci&oacute;n contrato (Beneficiario):</td>
       <td class="tdcampos"  colspan="1"><?list($anfbi,$msfbi,$difbi)=explode('-',$ladaente[fecha_renovacion_contratob]);
                                           echo "$difbi-$msfbi-$anfbi";?> </td>  
       <br>                                    
     </tr>
 </table>     
 <?if($cuantpoliente>=1){?>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
     <td colspan=4 class="titulo_seccion">Polizas asignada al ente</td>
 </tr>
 
      <?while ($datopoliza=asignar_a($reppoliente,NULL,PGSQL_ASSOC)){?>
      <tr>
      <td class="tdcampos"><?echo "$datopoliza[nombre_poliza]---$datopoliza[id_poliza]";?></td>
      </tr>
      <?}?>
 
 </table>
 <?}?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr>
 <br>
  <td><label title="Crear contrato colectivo" id="enteguardado1" class="boton" style="cursor:pointer" onclick="guardacolecente()" >Crear Contrato</label></td>  
  <td><label title="Modificar registro del ente" id="enteguardado1" class="boton" style="cursor:pointer" onclick="modifelente(<?echo $enteid?>)" >Modificar Ente</label></td>  
 </tr>          
</table>     
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<div id='elentecolectivo'></div>
          
<?}?>
