<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$anos=date("Y");
$mes=date("m");
$eldia=date("d");
$fecha="$eldia-$mes-$anos";
$idanexo=$_REQUEST['anexid'];
$eltipcli=$_REQUEST['eltipoclien'];
list($idclientob,$tipoclien)=explode("-",$eltipcli);
if($tipoclien=='T'){
	$elotroq="and tbl_anexos_repuesta.id_titular=$idclientob and tbl_anexos_repuesta.id_beneficiario>1;";
}else{
	$elotroq="and tbl_anexos_repuesta.id_beneficiario=$idclientob";
}	

$buscorep=("select tbl_anexos_repuesta.id_titular,tbl_anexos_repuesta.id_beneficiario,tbl_anexos_repuesta.pregunta
                           from tbl_anexos_repuesta where tbl_anexos_repuesta.id_anexo=$idanexo $elotroq;");

$repbusrep=ejecutar($buscorep);                           
?>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>

<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
<tr>
<td colspan=1 class="logo">
<img src="../../public/images/head.png">
</td>
<td colspan=2 class="titulo">

</td>
<td colspan=1 class="titulo2">
M&eacute;rida <?echo $fecha;?>
</td>
</tr>
<tr>
<td colspan=1 class="titulo2">
Rif: J-31180863-9
</td>
<td colspan=2 class="titulo">

</td>
</tr>
<tr>
	<tr>
		<td colspan=4 align="center" class="titulo3"><strong>Hoja Anexo</strong></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
</table>


<?
    while($laspregunta=asignar_a($repbusrep,NULL,PGSQL_ASSOC)){
        $estitu=$laspregunta[id_titular];
        if($tipoclien=='T'){
         $esben=0;
       }else{
   	     $esben=$laspregunta[id_beneficiario];
	   }
        
        if($esben==0){
            $busdatostit=("select clientes.nombres,clientes.apellidos,clientes.cedula 
                                 from clientes,titulares
                                 where 
                                   clientes.id_cliente=titulares.id_cliente and
                                   titulares.id_titular=$estitu;");
            $repdattiu=ejecutar($busdatostit);   
            $datatitular=assoc_a($repdattiu);
            $nombre="$datatitular[nombres] $datatitular[apellidos]";
            $cedtitu=$datatitular[cedula];
            $paren="Titular";
        }else{
             $busdabeni=("select clientes.nombres,clientes.apellidos,clientes.cedula,parentesco.parentesco
                                     from clientes,parentesco,beneficiarios
                                     where
                                        clientes.id_cliente=beneficiarios.id_cliente and
                                        beneficiarios.id_beneficiario=$esben and
                                        beneficiarios.id_titular=$estitu and
                                        beneficiarios.id_parentesco=parentesco.id_parentesco;");                                        
              $repdabeni=ejecutar($busdabeni);                           
              $dataprinbeni=assoc_a($repdabeni);
              $nombre="$dataprinbeni[nombres] $dataprinbeni[apellidos]";
              $cedtitu=$dataprinbeni[cedula];
               $paren=$dataprinbeni[parentesco];
            }
     if(!empty($laspregunta[pregunta])){
?>
<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>		
        <tr>
			<td class="datos_cliente3" ><label style="font-size: 8pt">Luego de revisión a la declaración de salud anexa a 
             la planilla de ingreso cumplimos con notificar que la inclusión de <?echo $nombre?>,  <?echo $paren?>,
             <?echo $cedtitu?>, a nuestro servicio fue aprobado con restricciones; de igual manera se informa que 
             de existir  cualquier enfermedad no declarada podrá ser anulado  el ingreso.</label></td>	
        </tr>    
        <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
		</tr>    
        <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
		</tr>    
        <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
		</tr>    
        <tr>
			<td class="tdtituloss"><label style="font-size: 8pt">Restricciones NO CUBIERTAS en cuanto a:</label></td>
		</tr>    
        <tr>
            <td class="datos_cliente3" ><label style="font-size: 8pt"><?echo $laspregunta[pregunta]?></label></td>
        </tr>    
     </table> 
     <br>    
     <br>    
 <?php 
  }
 }?>
  
 <table  class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
            <td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
            <td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
      </tr>      
       <tr>
			<td class="tdtituloss"><label style="font-size: 8pt">Firma Titular</label></td>
            <td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
            <td class="tdtituloss"><label style="font-size: 8pt">Firma Operador</label></td>
      </tr>      
      <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
            <td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
            <td class="tdtituloss"><label style="font-size: 8pt"><br></label></td>
      </tr>      
 </table>   

