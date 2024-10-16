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
$comentario=$_REQUEST['comentario'];
$buscanom=("select nomina_tb.id_titular,nomina_tb.id_beneficiario,nomina_tb.estado_cliente,nomina_tb.montoprima,nomina_tb.monttotal   
                            from nomina_tb,clientes,titulares where 
                            nomina_tb.id_nomina=$nominaid and nomina_tb.id_titular=titulares.id_titular and
                            titulares.id_cliente=clientes.id_cliente order by clientes.apellidos,nomina_tb.id_titular,nomina_tb.id_beneficiario;;");

$repbuscanom=ejecutar($buscanom);                            
$acumt=0;
$acumb=0;
$acumg=0;
$apunta1=0;
$apunta2=0;
$acumg1=0;
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
            <td class="titulos"><label style="font-size: 8pt"><b><strong>C&oacute;digo</strong></b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Estado</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Parentesco</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Cuota  <?echo $forpago?></b></label></td>
		</tr>
<?
    while($losenteti=asignar_a($repbuscanom,NULL,PGSQL_ASSOC)){
        $titulares=$losenteti['id_titular'];
        
        $cuanthijo=("select count(nomina_tb.id_beneficiario) from nomina_tb where nomina_tb.id_nomina=$nominaid and id_titular=$titulares and id_beneficiario>0");
        $repcuanthijo=ejecutar($cuanthijo);
        $datchijo=assoc_a($repcuanthijo);
        $haytothijo=$datchijo['count']+1;
        $benficia=$losenteti['id_beneficiario'];
        if(($benficia>0) and ($benficia<99999)){
             $apunta2++;
        }
        if($benficia==0){
            $apunta1++;
             $datt=("select titulares.codigo_empleado,clientes.cedula,clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento  
                            from 
                                clientes,titulares
                           where 
                              clientes.id_cliente=titulares.id_cliente and
                              titulares.id_titular=$titulares;");
             $repdatt=ejecutar($datt);   
             $infdatt=assoc_a($repdatt);
             $codigem=$infdatt['codigo_empleado'];
             $nomcol="$infdatt[apellidos] $infdatt[nombres]";
             $cedclien=$infdatt['cedula'];
             $edatit=calcular_edad($infdatt[fecha_nacimiento])." años";
             $paren="";
             $nomtb="";
              $cedutb="";
              $edatb="";
              $elmontp=$losenteti['montoprima'];
              $acumt=$elmontp;
        }else{
             $datb=("select clientes.cedula,clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento,parentesco.parentesco 
                            from
                              clientes,beneficiarios,parentesco
                             where
                               clientes.id_cliente=beneficiarios.id_cliente and
                               beneficiarios.id_beneficiario=$benficia and
                               beneficiarios.id_parentesco=parentesco.id_parentesco;");
              $repdatb=ejecutar($datb);        
              $infdatb=assoc_a($repdatb);
              $nomtb="$infdatb[apellidos] $infdatb[nombres]";
              $cedutb=$infdatb['cedula'];
              $edatb=calcular_edad($infdatb[fecha_nacimiento])." años";
              $codigem="";
             $nomcol="";
             $paren=$infdatb['parentesco'];
             $cedclien="";
             $edatit="";
             $elmontp=$losenteti['montoprima'];
             $acumb=$elmontp;
             $acumg=$acumg+$acumb+$acumt;
            
            }
 ?>  
     <?  if($benficia<>99999){  
         ?>
        <tr>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $codigem?></label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $nomcol?></label></td>
			<td class="datos_cliente3"> <label style="font-size: 8pt"><?echo $cedclien?></label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $edatit?> </label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $nomtb?></label></td>
			<td class="datos_cliente3"> <label style="font-size: 8pt"><?echo $cedutb?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $losenteti['estado_cliente']?></label></td> 
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $edatb?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $paren?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?if($elmontp>0){
                                                  echo formato_montos($elmontp);}?></label></td>
        </tr>    
    <?}else{?>     
        <tr>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt">Sub-Total</label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo formato_montos($losenteti['monttotal']);
                                                                                           $acumg1= $acumg1+$losenteti['monttotal'];?></label></td>
        </tr>    

<?}
  }?>       
  
<tr>
           <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt">Total - General</label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo formato_montos($acumg1);?> Bs.S</label></td>
        </tr>
        <tr>
           <td class="datos_cliente3"><label style="font-size: 8pt">Total titulares:</label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $apunta1;?></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
        </tr>
        <tr>
           <td class="datos_cliente3"><label style="font-size: 8pt">Total beneficiarios:</label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $apunta2;?></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
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
         <tr>
         <td>:</td>
         <td colspan=1></td></tr>
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
            
			<td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Nombre</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>C&eacute;dula</b></label></td>
			<td class="titulos"><label style="font-size: 8pt"><b>Estado</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Edad</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Parentesco</b></label></td>
            <td class="titulos"><label style="font-size: 8pt"><b>Cuota  <?echo $forpago?></b></label></td>
		</tr>
<?
    while($losenteti=asignar_a($repbuscanom,NULL,PGSQL_ASSOC)){
        $titulares=$losenteti['id_titular'];
        
        $cuanthijo=("select count(nomina_tb.id_beneficiario) from nomina_tb where nomina_tb.id_nomina=$nominaid and id_titular=$titulares and id_beneficiario>0");
        $repcuanthijo=ejecutar($cuanthijo);
        $datchijo=assoc_a($repcuanthijo);
        $haytothijo=$datchijo['count']+1;
        $benficia=$losenteti['id_beneficiario'];
        if(($benficia>0) and ($benficia<99999)){
             $apunta2++;
        }
        if($benficia==0){
            $apunta1++;
             $datt=("select titulares.codigo_empleado,clientes.cedula,clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento  
                            from 
                                clientes,titulares
                           where 
                              clientes.id_cliente=titulares.id_cliente and
                              titulares.id_titular=$titulares;");
             $repdatt=ejecutar($datt);   
             $infdatt=assoc_a($repdatt);
             $codigem=$infdatt['codigo_empleado'];
             $nomcol="$infdatt[apellidos] $infdatt[nombres]";
             $cedclien=$infdatt['cedula'];
             $edatit=calcular_edad($infdatt[fecha_nacimiento])." años";
             $paren="";
             $nomtb="";
              $cedutb="";
              $edatb="";
              $elmontp=$losenteti['montoprima'];
              $acumt=$elmontp;
        }else{
             $datb=("select clientes.cedula,clientes.nombres,clientes.apellidos,clientes.fecha_nacimiento,parentesco.parentesco 
                            from
                              clientes,beneficiarios,parentesco
                             where
                               clientes.id_cliente=beneficiarios.id_cliente and
                               beneficiarios.id_beneficiario=$benficia and
                               beneficiarios.id_parentesco=parentesco.id_parentesco;");
              $repdatb=ejecutar($datb);        
              $infdatb=assoc_a($repdatb);
              $nomtb="$infdatb[apellidos] $infdatb[nombres]";
              $cedutb=$infdatb['cedula'];
              $edatb=calcular_edad($infdatb[fecha_nacimiento])." años";
              $codigem="";
             $nomcol="";
             $paren=$infdatb['parentesco'];
             $cedclien="";
             $edatit="";
             $elmontp=$losenteti['montoprima'];
             $acumb=$elmontp;
             $acumg=$acumg+$acumb+$acumt;
            
            }
 ?>  
     <?  if($benficia<>99999){  
         ?>
        <tr>
			
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $nomcol?></label></td>
			<td class="datos_cliente3"> <label style="font-size: 8pt"><?echo $cedclien?></label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $edatit?> </label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $nomtb?></label></td>
			<td class="datos_cliente3"> <label style="font-size: 8pt"><?echo $cedutb?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $losenteti['estado_cliente']?></label></td> 
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $edatb?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo $paren?></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?if($elmontp>0){
                                                  echo formato_montos($elmontp);}?></label></td>
        </tr>    
    <?}else{?>     
        <tr>
			
			<td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt">Sub-Total</label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo formato_montos($losenteti['monttotal']);
                                                                                           $acumg1= $acumg1+$losenteti['monttotal'];?></label></td>
        </tr>    

<?}
  }?>       
  
<tr>
           
			<td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt">Total - General</label></td>
            <td class="datos_cliente3"><label style="font-size: 8pt"><?echo formato_montos($acumg1);?> Bs.S</label></td>
        </tr>
        <tr>
           <td class="datos_cliente3"><label style="font-size: 8pt">Total titulares:</label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $apunta1;?></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td> 
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
            
        </tr>
        <tr>
           <td class="datos_cliente3"><label style="font-size: 8pt">Total beneficiarios:</label></td>
			<td class="datos_cliente3"><label style="font-size: 8pt"><?echo $apunta2;?></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
			<td class="titulos"><label style="font-size: 8pt"></label></td>
            <td class="titulos"><label style="font-size: 8pt"></label></td>
			<td class="titulos"> <label style="font-size: 8pt"></label></td>
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
         <tr>
         <td>:</td>
         <td colspan=1></td></tr>
 </table>   
 
 
 <?php
 }
 ?>
