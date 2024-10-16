<?php
include ("../../lib/jfunciones.php");
sesion();
$clienanexo=strtoupper($_REQUEST['anexocli']);
list($idclientob,$tipoclien)=explode("-",$_REQUEST['clientetipo']);
if($tipoclien=='T'){
  $queryt="tbl_caract_recibo_prima.id_titular=$idclientob";
}else{
	$queryt="tbl_caract_recibo_prima.id_beneficiario=$idclientob";
	}
$busrecicon=("select tbl_caract_recibo_prima.id_recibo_contrato,tbl_caract_recibo_prima.id_titular,
                     tbl_caract_recibo_prima.id_beneficiario 
                     from 
                     tbl_caract_recibo_prima 
                     where
                     $queryt limit 1");
$repbusreci=ejecutar($busrecicon);                      
$datoreci=assoc_a($repbusreci);
$elrecibo=$datoreci['id_recibo_contrato'];
$eltitular=$datoreci['id_titular'];
if($tipoclien=='T'){
  $elbenefi=0;
}else{
	$elbenefi=$datoreci['id_beneficiario'];
	}

if($elrecibo>=1){
   $versiexistenanexos=("select tbl_anexos_repuesta.id_anexo_repuesta,tbl_anexos_repuesta.id_anexo 
                             from tbl_anexos_repuesta where 
                             id_titular=$eltitular and id_beneficiario=$elbenefi;");
   $repversiexisteane=ejecutar($versiexistenanexos);                          
   $cuantosexiste=num_filas($repversiexisteane);
   if($cuantosexiste>=1){
	   $datayaexiste=assoc_a($repversiexisteane);
	   $yaexistid=$datayaexiste['id_anexo_repuesta'];
	   $anexoid=$datayaexiste['id_anexo'];
	   $acutalianexo=("update tbl_anexos_repuesta set pregunta='$clienanexo' where id_anexo_repuesta=$yaexistid;");
	   $repactulanexo=ejecutar($acutalianexo);
   }else{   
   $guardoanexo=("insert into tbl_anexos(id_recibo_contrato) values($elrecibo);");
   $repguardoanexo=ejecutar($guardoanexo);
   $cualseguardo=("select tbl_anexos.id_anexo from tbl_anexos 
                   where tbl_anexos.id_recibo_contrato=$elrecibo");
   $repcualseguardo=ejecutar($cualseguardo);                
   $dataloqseguardo=assoc_a($repcualseguardo);
   $anexoid=$dataloqseguardo['id_anexo'];
   $guardorepuesta=("insert into tbl_anexos_repuesta(id_anexo,id_titular,id_beneficiario,pregunta) 
                   values($anexoid,$eltitular,$elbenefi,'$clienanexo')");
   $repduardorepuesta=ejecutar($guardorepuesta);
   }?>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
              <tr> 
                 <td colspan=4 class="titulo_seccion">Se ha generado exitosamente el anexo!!</td>  
              </tr>
              <tr>
	           <td title="Imprimir hoja anexo"><label id="titularboton" class="boton" style="cursor:pointer" onclick="anexos1('<?echo $anexoid?>')" >Imprimir</label>
             </tr>
     </table>
<?}?>
