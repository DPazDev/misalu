<?php
include ("../../lib/jfunciones.php");
sesion();
$lacedclient=$_REQUEST['elcedula'];
$buscontratos=("select titulares.id_titular,polizas.nombre_poliza,titulares.id_ente,
tbl_recibo_contrato.fecha_ini_vigencia,tbl_recibo_contrato.fecha_fin_vigencia,tbl_contratos_entes.numero_contrato,
tbl_recibo_contrato.num_recibo_prima,tbl_tipos_entes.tipo_ente,entes.nombre,estados_clientes.estado_cliente,tbl_recibo_contrato.id_recibo_contrato  
from 
clientes,titulares,tbl_caract_recibo_prima,polizas,titulares_polizas,tbl_recibo_contrato,tbl_contratos_entes,tbl_tipos_entes,
entes,estados_clientes,estados_t_b 
 where
clientes.id_cliente=titulares.id_cliente and
clientes.cedula='$lacedclient' and
titulares.id_titular=tbl_caract_recibo_prima.id_titular and
titulares.id_titular=titulares_polizas.id_titular and
titulares_polizas.id_poliza=polizas.id_poliza and
titulares.id_ente=tbl_contratos_entes.id_ente and
tbl_contratos_entes.id_contrato_ente=tbl_recibo_contrato.id_contrato_ente and
titulares.id_ente=entes.id_ente and
titulares.id_titular=estados_t_b.id_titular and
estados_t_b.id_beneficiario=0 and
estados_t_b.id_estado_cliente<>8 and
estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente
group by titulares.id_titular,polizas.nombre_poliza,titulares.id_ente,tbl_recibo_contrato.fecha_ini_vigencia,
tbl_recibo_contrato.fecha_fin_vigencia,
tbl_contratos_entes.numero_contrato,tbl_recibo_contrato.num_recibo_prima,tbl_tipos_entes.tipo_ente,
entes.nombre,estados_clientes.estado_cliente,tbl_recibo_contrato.id_recibo_contrato");

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
   <tr>
	 <td class="tdtitulos">Fecha Nacimiento:</td>
	 <td class="tdcampos" ><?php echo camfecha($datobasico['fecha_nacimiento']); ?></td>
     <td class="tdtitulos">Edad:</td>
     <td class="tdcampos" ><label style="color: #ff0000"> <?php echo calcular_edad($datobasico['fecha_nacimiento']); ?> a&ntilde;os</label></td>
     <td class="tdtitulos">Sexo:</td>
     <td class="tdcampos" ><?php 
	   if($datobasico['sexo']==0){
	 	echo "FEMENINO";
	  }else{
	 	echo "MASCULINO";}?></td>
   </tr>    			
   <tr>
	 <td class="tdtitulos">Tel&eacute;fono:</td>
	 <td class="tdcampos" ><?php echo $datobasico['telefono_hab']; echo " / "; echo $datobasico['telefono_otro'];  ?></td>
	 <td class="tdtitulos">Celular:</td>
	 <td class="tdcampos" ><?php echo $datobasico['celular']; ?></td>
  </tr>
  <tr>
     <td colspan=1 class="tdtitulos">Direcci&oacute;n:</td>
     <td colspan=3 class="tdcampos" ><?php echo $datobasico['direccion_hab']; ?></td>
  </tr>
</table>	   
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=3 class="titulo_seccion">Contrato(s) asignado al titular</td>          
	</tr>
</table> 
 <table class="tabla_citas"  cellpadding=0 cellspacing=0>
 <? 
    while($loscontratos=asignar_a($repbuscontratos,NULL,PGSQL_ASSOC)){
 ?> 
   <tr>
    <td class="tdtitulos">Ente:</td>   
    <td class="tdcampos"><?echo $loscontratos['nombre']?></td>   
    <td class="tdtitulos">Tipo Ente:</td>   
    <td class="tdcampos"><?echo $loscontratos['tipo_ente']?></td>   
   </tr>
   <tr>
    <td class="tdtitulos">Contrato No:</td>   
    <td class="tdcampos"><?echo "$loscontratos[numero_contrato]-$loscontratos[num_recibo_prima]"?></td>   
    <td class="tdtitulos">Fecha Inicio - Fin:</td>   
    <td class="tdcampos"><?echo "$loscontratos[fecha_ini_vigencia] / $loscontratos[fecha_fin_vigencia]"?></td>   
   </tr>
   <tr>
    <td class="tdtitulos">Poliza:</td>   
    <td class="tdcampos"><label style="color: #ff0000"><?echo $loscontratos[nombre_poliza]?></label></td>   
    <td class="tdtitulos">Estatus:</td>   
    <td class="tdcampos"><label style="color: #ff0000"><?echo $loscontratos[estado_cliente]?></label></td>   
   </tr> 
   
   <?
      $eltitu=$loscontratos[id_titular];
      $verbenef=("select clientes.nombres,clientes.apellidos,parentesco.parentesco 
      from 
        clientes,parentesco,beneficiarios 
      where 
        beneficiarios.id_titular=$eltitu and 
		beneficiarios.id_cliente=clientes.id_cliente and
        beneficiarios.id_parentesco=parentesco.id_parentesco");
      $repverbenf=ejecutar($verbenef);  
      $cuantbenef=num_filas($repverbenf);
      if($cuantbenef>=1){
   ?>
   <tr><td>&nbsp;</td></tr>
   <tr>
     
              <td class="tdtitulos">Beneficiarios:</td>   
                            
                <td>
                    <table cellpadding=0 cellspacing=0 align="center" border="0">
                    
                    <? 
                       while($losben=asignar_a($repverbenf,NULL,PGSQL_ASSOC)){
                     ?> 
                        <tr>
                            <td class="tdtitulos">Nombre:</td> 
                             <td class="tdcampos"><?echo "$losben[nombres] $losben[apellidos]"?></td>
                             <td class="tdtitulos">&nbsp;&nbsp;&nbsp;Parentesco:</td> 
                             <td class="tdcampos"><?echo $losben[parentesco]?></td>
                        </tr>
                        <?}?>
                    </table>
                 </td>
                                
   </tr>
   <?}?>
   <tr><td>&nbsp;</td></tr>
   <tr><td colspan=3 class="tdcampos" title="Anular Contrato"><label class="boton" style="cursor:pointer" onclick="anularcontrato('<?echo $loscontratos['id_recibo_contrato']?>','<?echo "$loscontratos[numero_contrato]-$loscontratos[num_recibo_prima]"?>')" >Anular Contrato</label></td></tr>
   <tr><td colspan=5><hr></td></tr>
 <?}?>
 </table>  
  <div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
  </div>  
 <div id='anulcontrato'></div>
<?}?>
