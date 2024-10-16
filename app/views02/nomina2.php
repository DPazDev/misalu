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
       estados_clientes.estado_cliente,titulares.id_titular$quercodi");
 $repnomina=ejecutar($nominat);       
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
        <?if($codigo<>0){?>
            <td class="titulos"><b>C&oacute;digo</b></td>
         <?}?>   
            <td class="titulos"><b>C&eacute;dula</b></td>
			<td class="titulos"><b>Nombre</b></td>
			<td class="titulos"><b>Monto</b></td>
			<td class="titulos"><b>Concepto</b></td>
            <td class="titulos"><b>Descripci&oacute;n</b></td>
        </tr>
<?
    while($losenteti=asignar_a($repnomina,NULL,PGSQL_ASSOC)){
        
        $titulares=$losenteti['id_titular'];
        
        $totalt++;
         
    ?>        
        
			
            <?
              $busprima=("select primas.$forpagon from 
                              polizas_entes,primas 
                           where polizas_entes.id_ente=$ente and 
                                   polizas_entes.id_poliza!=46 and 
                                   polizas_entes.id_poliza=primas.id_poliza and
                                   primas.id_parentesco=0;");
            $repbusprima=ejecutar($busprima);
            $datbusprima=assoc_a($repbusprima);
            if($priptitular==1){
                 $montpago=0;
            }else{
            $montpago=$datbusprima[$forpagon];
            }
            $acumutituindi=$montpago;
            ?>
            
		
        
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
      $querysubdivi clientes.nombres,clientes.apellidos,clientes.cedula,clientes.fecha_nacimiento,
       estados_clientes.estado_cliente,beneficiarios.id_beneficiario,beneficiarios.id_parentesco,parentesco.parentesco ;");
             $repbusbeni=ejecutar($buscabeni);
        while($losentebeni=asignar_a($repbusbeni,NULL,PGSQL_ASSOC)){ ?>
         <?  
              $bedad=calcular_edad($losentebeni['fecha_nacimiento']);
              $bparen=$losentebeni['id_parentesco'];
                                                     
          ?>
           <?  $busprimaben=("select primas.$forpagon from 
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
              $acumuglobal=$acumuglobal+$acumubenfindi;
          ?>
            
<?}

?>
      <tr>
         <?if($acumubenfindi>0){?>
          <?if($codigo<>0){?>
          <td class="titulos"><?echo $losenteti['codigo_empleado']?></td>
          <?}?> 
			<td class="titulos"><?echo $losenteti['cedula']?></td>
			<td class="titulos"><?echo "$losenteti[nombres] $losenteti[apellidos]"?></td>  
       <td class="titulos"><?echo $acumubenfindi +$acumutituindi?></td>
       <td class="titulos"></td>
       <td class="titulos">Clinisalud</td>  
       <?}?>
    </tr>
<?$acumubenfindi=0;
$acumutituindi=0;
}?>

</table>
