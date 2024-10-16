<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha1=$_REQUEST['lafecha1'];
$fecha2=$_REQUEST['lafecha2'];
$ente=$_REQUEST['laente'];
$subdivi=$_REQUEST['lasubdi'];
$estatu=$_REQUEST['elestatu'];
$codigo=$_REQUEST['lacodig'];
$forpago=$_REQUEST['lapago'];
$priptitular=$_REQUEST['pritcobro'];
$forpagon=strtolower($_REQUEST['lapago']);
$nomente=("select entes.nombre from entes where entes.id_ente=$ente;");
$repnomente=ejecutar($nomente);
$datnomente=assoc_a($repnomente);
$nombrdelente=$datnomente['nombre'];
$acumutituindi=0;
$acumubenfindi=0;
$acumuglobal=0;
$totalt=0;
$totalb=0;
 if($subdivi>0){
     $querysubdivi="and  titulares_subdivisiones.id_subdivision=$subdivi";
  }else{
       $querysubdivi="";
      }

   if($codigo==1){
   $quercodi=",titulares.codigo_empleado";
   }
   if($estatu==100){
       $queryestau="(estados_t_b.id_estado_cliente=4 or estados_t_b.id_estado_cliente=1)";
       }else{
             $queryestau="estados_t_b.id_estado_cliente=$estatu";
           }
   
   $nominat=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,
       estados_clientes.estado_cliente,titulares.id_titular$quercodi
from
       clientes,estados_clientes,estados_t_b,titulares,titulares_subdivisiones
where
       clientes.id_cliente=titulares.id_cliente and
       titulares.id_ente=$ente and
       titulares.id_titular=estados_t_b.id_titular and
       estados_t_b.id_beneficiario=0 and
       estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
       $queryestau and
       titulares.id_titular=titulares_subdivisiones.id_titular 
       $querysubdivi group by clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,
       estados_clientes.estado_cliente,titulares.id_titular$quercodi order by clientes.apellidos");
       
 $repnomina=ejecutar($nominat); 
 $cuantthay=num_filas($repnomina);
 
 if($cuantthay>=1){
     $busexnomina=("select nomina.id_nomina,nomina.id_subdivision from nomina where
                              nomina.id_ente=$ente and
                                       nomina.fechaini='$fecha1' and nomina.fechafin='$fecha2' and nomina.id_subdivision=$subdivi;");
    $repexnomina=ejecutar($busexnomina); 
    $cuanexnomina=num_filas($repexnomina);
    $datnomson=assoc_a($repexnomina);
    $lasubdiques=$datnomson['id_subdivision'];
    
    if(($cuanexnomina==0)&&($lasubdiques<>$subdivi)){
   
//guardar el encabezado de la nomina
$encabnomina=("insert into nomina(fechaini,fechafin,id_ente,id_subdivision,estatus,codigo,formpago) 
                           values('$fecha1','$fecha2',$ente,$subdivi,'$estatu','$codigo','$forpagon');");
$repencabnomina=ejecutar($encabnomina);      
//fin de guardar encabezado
//buscamos la nomina creada
$cualeslnomina=("select nomina.id_nomina from nomina where nomina.id_ente=$ente and
                                       nomina.fechaini='$fecha1' and nomina.fechafin='$fecha2' and 
                                       nomina.id_subdivision=$subdivi;");
$repcualnomina=ejecutar($cualeslnomina);                                       
$infonomina=assoc_a($repcualnomina);
$elidnomina=$infonomina['id_nomina'];
//fin de la busquedad
}
}

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
		<td colspan=4 align="center" class="titulo3"><strong>Cuota de descuento <?php echo $nombrdelente?></strong></td>
	</tr>
	<tr>
		<td colspan=4 class="tdcamposc">&nbsp;</td>
	</tr>
</table>
<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>
		<tr>
            
            <td class="titulos"><b>C&oacute;digo</b></td>
         
			<td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Estado</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Parentesco</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Cuota <?echo $forpago?></b></label></td>
		</tr>
<?
    while($losenteti=asignar_a($repnomina,NULL,PGSQL_ASSOC)){
        $titulares=$losenteti['id_titular'];
        $versitbenif=("select * from beneficiarios,estados_t_b where 
beneficiarios.id_titular=$titulares and 
beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
       $queryestau");
       $repversitb=ejecutar($versitbenif);
       $cuantos=num_filas($repversitb);
       if(($ente==148)||($ente==37)||($ente==38)||($ente==142)){
            $cuantos=5;
       }
       if($cuantos>=1){
        $totalt++;
    ?>        
        <tr>
			
          <td class="titulos"><label style="font-size: 8pt"><?echo $losenteti['codigo_empleado']?></label></td>
         
			<td class="titulos"><label style="font-size: 8pt"><?echo "$losenteti[nombres] $losenteti[apellidos]"?></label></td>
			<td class="titulos"><label style="font-size: 8pt"> <?echo $losenteti['cedula']?></label></td>
			<td class="titulos"><label style="font-size: 8pt"><?echo calcular_edad($losenteti['fecha_nacimiento'])?> años</label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
           
            <?
            if($priptitular==1){
                 $montpago=0;
                 $acumutituindi=$montpago;
                 
            }else{     
              $busprima=("select primas.$forpagon from 
                              polizas_entes,primas 
                           where polizas_entes.id_ente=$ente and 
                                   polizas_entes.id_poliza!=46 and 
                                   polizas_entes.id_poliza=primas.id_poliza and
                                   primas.id_parentesco=0;");
                                   
            $repbusprima=ejecutar($busprima);
            $datbusprima=assoc_a($repbusprima);
            $montpago=$datbusprima[$forpagon];
            $acumutituindi=$montpago;
            }
            $guardodatosn=("insert into nomina_tb(id_nomina,id_titular,id_beneficiario,estado_cliente,montoprima) 
                                          values($elidnomina,$titulares,0,'$losenteti[estado_cliente]',$montpago);");
            $repguardodatosn=ejecutar($guardodatosn);                              
            ?>
            <td class="titulos"><label style="font-size: 8pt"><?echo $montpago?></label></td> 
		</tr>
        
            <?
                $buscabeni=("select clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,
       estados_clientes.estado_cliente,beneficiarios.id_beneficiario,beneficiarios.id_parentesco,parentesco.parentesco
from
      clientes,estados_clientes,estados_t_b,beneficiarios,titulares,parentesco,titulares_subdivisiones
where
      clientes.id_cliente=beneficiarios.id_cliente and
      beneficiarios.id_beneficiario=estados_t_b.id_beneficiario and
      $queryestau and
      estados_t_b.id_estado_cliente=estados_clientes.id_estado_cliente and
      beneficiarios.id_parentesco=parentesco.id_parentesco and
      beneficiarios.id_titular=titulares.id_titular and
      titulares.id_ente=$ente and
      titulares.id_titular=titulares_subdivisiones.id_titular and
      titulares.id_titular=$titulares
      $querysubdivi group by clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,
       estados_clientes.estado_cliente,beneficiarios.id_beneficiario,beneficiarios.id_parentesco,parentesco.parentesco;");
             $repbusbeni=ejecutar($buscabeni);
        while($losentebeni=asignar_a($repbusbeni,NULL,PGSQL_ASSOC)){ 
        $elbenfides=$losentebeni['id_beneficiario'];
        ?>
         <tr>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
          <td class="titulos"><label style="font-size: 8pt"><?echo "$losentebeni[nombres] $losentebeni[apellidos]"?></label></td>
          <td class="titulos"><label style="font-size: 8pt"><?echo $losentebeni['cedula']?></label></td>
          <td class="titulos"><label style="font-size: 8pt"><?echo $losentebeni['estado_cliente']?></label></td>
          <td class="titulos"><label style="font-size: 8pt"><?echo calcular_edad($losentebeni['fecha_nacimiento']);
                                                      $bedad=calcular_edad($losentebeni['fecha_nacimiento']);
                                                      if($bedad<1){
                                                          $bedad=0;
                                                          }else{
                                                               $bedad=$bedad;
                                                              }
                                                      ?> años</td>
          <td class="titulos"><label style="font-size: 8pt"><?echo $losentebeni['parentesco'];
                                                       $bparen=$losentebeni['id_parentesco'];
                                                       $totalb++;?></label></td>
          <?
             $busprimaben=("select primas.$forpagon from 
                              polizas_entes,primas 
                           where polizas_entes.id_ente=$ente and 
                                   polizas_entes.id_poliza!=46 and 
                                   polizas_entes.id_poliza=primas.id_poliza and
                                   primas.id_parentesco=$bparen and
                                   ($bedad>=primas.edad_inicio and
                                   $bedad<=primas.edad_fin);");
               $repbusprimaben=ejecutar($busprimaben);  
              $datpriben=assoc_a($repbusprimaben);
              $elmotben=$datpriben[$forpagon];
              $acumubenfindi=$acumubenfindi+$elmotben;
              
              $guardodatosnbeni=("insert into nomina_tb(id_nomina,id_titular,id_beneficiario,estado_cliente,montoprima) 
                                          values($elidnomina,$titulares,$elbenfides,'$losentebeni[estado_cliente]',$elmotben);");
            $repguardodatosnbeni=ejecutar($guardodatosnbeni);   
          ?>
            <td class="titulos"><label style="font-size: 8pt"><?echo $elmotben?></label></td>
        
<?}
?>
     </tr>
        <tr>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt">Sub-Total</label></td>
             
             <td class="titulos"><label style="font-size: 8pt"><? $rr=$acumubenfindi+$acumutituindi;
                                                                                                echo $rr;
                                                 $acumuglobal=$acumuglobal+$rr;
            $guardmtotal=("insert into nomina_tb(id_nomina,id_titular,id_beneficiario,monttotal) 
                                          values($elidnomina,$titulares,99999,$rr);");
            $repguardototal=ejecutar($guardmtotal);  
            ?> Bs.S</label></td>
         </tr>
         </tr>
       
         
<?
$acumubenfindi=0;
$acumutituindi=0;
}
}?>
<tr>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt">Total - General</label></td>
             <td class="titulos"><label style="font-size: 8pt"><?echo $acumuglobal?> Bs.S</label></td>
         </tr>
 <?
  $acumuglobal=0;
 ?>   
   <tr>
             <td class="titulos"><label style="font-size: 8pt">Total titulares:</label></td>
             <td class="titulos"><label style="font-size: 8pt"><?echo $totalt?></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
         </tr>
         <tr>
             <td class="titulos"><label style="font-size: 8pt">Total beneficiarios:</label></td>
             <td class="titulos"><label style="font-size: 8pt"><?echo $totalb?></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
             <td class="titulos"><label style="font-size: 8pt"></label></td>
         </tr>
</table>
<br>
<br>
<br>

<table   border=1 class="tabla_citas"  cellpadding=0 cellspacing=0>
        <tr>

         </tr> 
		<tr>
            <td class="titulos"><b><strong>Elaborado por:</strong></b></td>
        </tr>   
         <tr><td></td><td colspan=1><TEXTAREA COLS=45 ROWS=3 ></TEXTAREA></td></tr>
 </table>           
