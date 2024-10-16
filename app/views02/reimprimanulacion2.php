<?php
include ("../../lib/jfunciones.php");
sesion();
$lacedclient=$_REQUEST['elcedula'];
$buscontratos=("select 
clientes.nombres,clientes.apellidos,clientes.cedula,polizas.nombre_poliza,tbl_contrato_anulado.id_recibo_contrato,tbl_recibo_contrato.num_recibo_prima,tbl_contratos_entes.numero_contrato,tbl_contrato_anulado.fecha_creado,articulocontrato.numarticulo,admin.nombres,admin.apellidos
from  
tbl_recibo_contrato,clientes,titulares,polizas,titulares_polizas,tbl_contrato_anulado,tbl_contratos_entes,entes,articulocontrato,admin
where 
tbl_recibo_contrato.id_recibo_contrato=tbl_contrato_anulado.id_recibo_contrato and
tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente and
tbl_contratos_entes.id_ente=entes.id_ente and
titulares.id_ente=entes.id_ente and
titulares.id_titular=titulares_polizas.id_titular and
titulares_polizas.id_poliza=polizas.id_poliza and
titulares.id_cliente=clientes.id_cliente and
tbl_contrato_anulado.id_articulocon=articulocontrato.id_articulocon and
tbl_contrato_anulado.id_admin=admin.id_admin and
clientes.cedula='$lacedclient';");
$repbuscontratos=ejecutar($buscontratos);
$cuantcont=num_filas($repbuscontratos);
if($cuantcont<=0){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">No se encuentra ning&uacute;n titular con la C&eacute;dula No. <?echo $lacedclient?></td>          
	</tr>
</table>
<?}
  else{
  $datobasico=("select * from clientes where clientes.cedula='$lacedclient'");	
  $repdatobasico=ejecutar($datobasico);
  $datobasico=assoc_a($repdatobasico);  
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Datos basicos del titular</td>          
	</tr>
</table>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
    <tr>
	  <td class="tdtitulos">C&eacute;dula:</td>
      <td class="tdcampos"><?php echo $datobasico['cedula'];?></td>
	  <td class="tdtitulos">Nombres y Apellidos:</td> 
      <td class="tdcampos" ><?php echo "$datobasico[nombres] $datobasico[apellidos]"; ?></td>
   </tr>
</table> 
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Contratos Anulado(s)</td>          
	</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
     <tr>
            <th class="tdtitulos">Contrato No.</th>
            <th class="tdtitulos">Fecha Anulaci&oacute;n.</th>
            <th class="tdtitulos">Opci&oacute;n</th>
     </tr>
   <?php 
	$i=1; 
	while($polizascr=asignar_a($repbuscontratos,NULL,PGSQL_ASSOC)){
    ?>
         <tr>
			<td class="tdcampos"><?echo "$polizascr[numero_contrato]-$polizascr[num_recibo_prima]";?></td> 
			<td class="tdcampos"><?echo $polizascr['fecha_creado'];?></td>      
			<td class="tdcampos"><label class="boton" style="cursor:pointer" onclick="reimprcontrato('<?echo $polizascr[id_recibo_contrato]?>','<?echo "$polizascr[numero_contrato]-$polizascr[num_recibo_prima]"?>','<?echo $polizascr[numarticulo]?>','<?echo "$polizascr[nombres] $polizascr[apellidos]"?>')" >Reimprimir</label></td>
        </tr>
      
    <?}?>
</table>
  <div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
  </div>  
 <div id='anulcontrato'></div>
<?}?>
