<?php
include ("../../lib/jfunciones.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$nominaid=$_REQUEST['nomina'];
$pagtitu=$_REQUEST['titpago'];
$nombrdelente=$_REQUEST['nombe'];
$forpago=$_REQUEST['tipago'];
$codigo=$_REQUEST['concodi'];
$buscanom=("select clientes.cedula,clientes.nombres,clientes.apellidos,nomina_tb.id_titular,sum(nomina_tb.montoprima),
                        titulares.codigo_empleado from nomina_tb,clientes,titulares where nomina_tb.id_nomina=$nominaid and 
                        nomina_tb.id_titular=titulares.id_titular and titulares.id_cliente=clientes.id_cliente group by nomina_tb.id_titular,
                         clientes.cedula,clientes.nombres,clientes.apellidos,titulares.codigo_empleado order by clientes.apellidos,clientes.nombres;");
$repbuscanom=ejecutar($buscanom);  
if ($codigo==1){
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
    while($losenteti=asignar_a($repbuscanom,NULL,PGSQL_ASSOC)){  ?>
     <tr>
       
          <?if($codigo<>0){?>
          <td class="datos_cliente3"><?echo $losenteti['codigo_empleado']?></td>
          <?}?> 
			 <td class="datos_cliente3"><? if(strlen($losenteti['cedula'])<=7){
                                                   $lacednue="0$losenteti[cedula]";
                                                 }else{
                                                      $lacednue=$losenteti['cedula'];
                                                     }
            echo $lacednue;?></td>
			<td class="datos_cliente3"><?echo "$losenteti[apellidos] $losenteti[nombres]"?></td>  
       <td class="datos_cliente3"><?echo formato_montos($losenteti['sum'])?></td>
       <td class="datos_cliente3"></td>
       <td class="datos_cliente3">Clinisalud</td>  
       <?}?>
  </table>     
  <?php 
  }
  else
  {
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
          
            <td class="titulos"><b>C&eacute;dula</b></td>
			<td class="titulos"><b>Nombre</b></td>
			<td class="titulos"><b>Monto</b></td>
			<td class="titulos"><b>Concepto</b></td>
            <td class="titulos"><b>Descripci&oacute;n</b></td>
        </tr>
<?
    while($losenteti=asignar_a($repbuscanom,NULL,PGSQL_ASSOC)){  ?>
     <tr>
       
           
			 <td class="datos_cliente3"><? if(strlen($losenteti['cedula'])<=7){
                                                   $lacednue="0$losenteti[cedula]";
                                                 }else{
                                                      $lacednue=$losenteti['cedula'];
                                                     }
            echo $lacednue;?></td>
			<td class="datos_cliente3"><?echo "$losenteti[apellidos] $losenteti[nombres]"?></td>  
       <td class="datos_cliente3"><?echo formato_montos($losenteti['sum'])?></td>
       <td class="datos_cliente3"></td>
       <td class="datos_cliente3">Clinisalud</td>  
       <?}?>
  </table>     
  
  <?php
  }
  ?>