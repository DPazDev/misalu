<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$nombre=$_REQUEST['vnombre'];
$cedula=$_REQUEST['vcedula'];
$elcliente=$_REQUEST['vcliente'];
if($elcliente=='T'){
  $mensaje="Titular";    
}else{
      $mensaje="Beneficiario";  
    }
$mostpre=("select declaracion.id_declaracion,declaracion.declaracion from declaracion order by declaracion.declaracion;");
$repmostrpe=ejecutar($mostpre);                         
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
<td colspan=1 class="titulo">

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
		<td colspan=4 align="center" class="titulo3"><strong>DECLARACI&Oacute;N DE SALUD DEL SOLICITANTE</strong></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
</table>
<table   class="tabla_citas"  cellpadding=0 cellspacing=0>
         <tr>
                  <td class="tdtituloss"><label style="font-size: 8pt"><u> <?echo $mensaje?>:  <?echo $nombre?></u></label></td>
                  <td class="tdtituloss"><label style="font-size: 8pt"><u>C.I. <?echo $cedula?></u></label></td>
         </tr>
</table>   
<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>		
<?
    while($laspregunta=asignar_a($repmostrpe,NULL,PGSQL_ASSOC)){
?>
        <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><?echo $laspregunta['declaracion']?></label></td>
			<td class="datos_cliente3" ><label style="font-size: 8pt"><input type="radio" name="groupt1" id="cobprn" value="1" > Si</label></td>	
            <td class="datos_cliente3" ><label style="font-size: 8pt"><input type="radio" name="groupt1" id="cobprs" value="2"> No</label>
            </td>			
        </tr>    
 <?php 
 }?>
 </table>   
 <table  class="tabla_citas"  cellpadding=0 cellspacing=0>
     <tr>
			<td class="tdtituloss"><label style="font-size: 8pt"><strong>Autorizo a la Compa&ntilde;&iacute;a a realizar Estudios de Diagnostico.</strpng></label></td>
      </tr>      
 </table>   
 <table  class="tabla_citas"  cellpadding=0 cellspacing=0>
 <tr><br>
		<td colspan=4 align="center" class="titulo3"><label style="font-size: 8pt"><strong>CUALQUIER OCULTAMIENTO DE INFORMACION O FALSEDAD POR PARTE DEL AFILIADO ANULARA EL CONTRATO</strong></label></td>
	</tr>
</table>  
 <table  class="tabla_citas"  cellpadding=0 cellspacing=0>
   <tr><br><br>
			<td class="tdtituloss"><label style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
            <td class="tdtituloss"><label style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
            <td class="tdtituloss"><label style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
            <td class="tdtituloss"><label style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
            <td class="tdtituloss"><label style="font-size: 8pt">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma del <?echo $mensaje?>: ____________________________</label></td>
            
</tr>
</table>  