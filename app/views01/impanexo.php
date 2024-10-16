<?php
header("Content-Type: text/html;charset=utf-8");
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$titular=$_REQUEST['ti_tu'];
$recibo=$_REQUEST['v_re'];
$reptitu=strtoupper($_REQUEST['pregt']);
$repue=$_REQUEST['re_pu'];
$anos=date("Y");
$mes=date("m");
$eldia=date("d");
$fecha="$eldia-$mes-$anos";
$guardanexo=("insert into tbl_anexos(id_recibo_contrato) values($recibo);");
$repguanexo=ejecutar($guardanexo);
$buscanexo=("select tbl_anexos.id_anexo from tbl_anexos where tbl_anexos.id_recibo_contrato=$recibo and
                                   tbl_anexos.fecha_creado='$fecha';");
$repbusanexo=ejecutar($buscanexo);                                   
$dataanexo=assoc_a($repbusanexo);
$idanexo=$dataanexo[id_anexo];
$verexiste=("select tbl_anexos_repuesta.id_anexo_repuesta from tbl_anexos_repuesta where tbl_anexos_repuesta.id_titular=$titular");
$repexiste=ejecutar($verexiste);
$cuaexiste=num_filas($repexiste);

if($cuaexiste<=0){
if(!empty($reptitu)){
	$guardotitularanexo=("insert into tbl_anexos_repuesta(id_anexo,id_titular,id_beneficiario,pregunta) 
                                          values($idanexo,$titular,0,'$reptitu');");
    $reptituanexo=ejecutar($guardotitularanexo);                                      
}

$arre=explode(",",$repue);
$cuanhijo=count($arre);
$apunta=1;
$aben=0;
for($i=0;$i<=$cuanhijo;$i++){
   $benefi=$arre[$aben]; 
   $repues=strtoupper($arre[$apunta]); 
    if(!empty($repues)){
   $guardotitularanexo1=("insert into tbl_anexos_repuesta(id_anexo,id_titular,id_beneficiario,pregunta) 
                                          values($idanexo,$titular,$benefi,'$repues');");
   $reptituanexo1=ejecutar($guardotitularanexo1); 
   }
  $apunta=$apunta+2;
  $aben=$aben+2;
}
$buscorep=("select tbl_anexos_repuesta.id_titular,tbl_anexos_repuesta.id_beneficiario,tbl_anexos_repuesta.pregunta
                           from tbl_anexos_repuesta where tbl_anexos_repuesta.id_anexo=$idanexo;");
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
        $esben=$laspregunta[id_beneficiario];
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
<?}?> 
