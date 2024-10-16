<?php
include ("../../lib/jfunciones.php");
sesion();
$lafactid = $_REQUEST[fidvalija];
$estavalija = $_REQUEST[festaf];
$seriefactu = $_REQUEST[fserief];
$valijaserial = $_REQUEST[fserialvali];
if ($estavalija == 1){
   $estadfac ="Creada";
}else{
	 if($estavalija == 2){
		 $estadfac ="Enviada";
		 
		 }else{
			   if($estavalija == 3){
				   $estadfac ="Recibida";
				}else{
					  if($estavalija == 4){
						  $estadfac ="Entregada";
					  }else{
						  $estadfac ="Devuelta";
						  }
					}
			 }
	}
$buscamostodafactu=("select tbl_valija_factura.estado_factura,tbl_valija_factura.numero_factura,
tbl_facturas.id_factura,tbl_facturas.fecha_emision,sum(tbl_procesos_claves.monto) as total
from 
tbl_valija_factura,tbl_valija_historial,tbl_valija,tbl_facturas,tbl_procesos_claves
where
tbl_valija_historial.estado_factura = $estavalija and
tbl_valija_historial.estado_factura = tbl_valija_factura.estado_factura and
tbl_valija_historial.id_valija = $lafactid and
tbl_valija_historial.id_valija = tbl_valija_factura.id_valija and
tbl_valija_historial.id_valija = tbl_valija.id_valija and
tbl_valija.id_serie= $seriefactu and 
tbl_valija_factura.numero_factura = tbl_facturas.numero_factura and
tbl_valija.id_serie = tbl_facturas.id_serie and
tbl_procesos_claves.id_factura=tbl_facturas.id_factura 
group by 
tbl_valija_factura.estado_factura,tbl_valija_factura.numero_factura,
tbl_facturas.id_factura,tbl_facturas.fecha_emision order by tbl_facturas.id_factura;");
$repbuscamfactura = ejecutar($buscamostodafactu);
$valijaente = ("select entes.nombre from entes,tbl_valija where
tbl_valija.id_valija=$lafactid and
tbl_valija.id_ente=entes.id_ente");
$repvalijaente = ejecutar($valijaente);
$cuantasvalija = num_filas($repbuscamfactura);
$datavalijaente = assoc_a($repvalijaente);
$nombrente = $datavalijaente['nombre'];
if($cuantasvalija == 0){
?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">No existen Facturas en el estado (<?php echo $estadfac?>)<BR> del Ente (<?php echo $nombrente?>)</td>
     </tr>
</table>     
<?php }
else{ ?>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr>
         <td colspan=7 class="titulo_seccion">Facturas de la Valija No. (<?php echo $valijaserial?>) en el estado (<?php echo $estadfac?>)<BR> del Ente (<?php echo $nombrente?>)</td>
     </tr>
</table>     
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>    
             <tr>
                 <th class="tdtitulos">N. Factura.</th>
                 <th class="tdtitulos">Fecha Emisi&oacute;n.</th>  
                 <th class="tdtitulos">Monto Factura.</th>
                <?php if ($estavalija == 4){?> 
                 <th class="tdtitulos">Comentario.</th>
                <?php }?> 
                 <th class="tdtitulos">Opci&oacute;n.</th>
              </tr>
    <?php 
       $numfact=0;
       $totalfact=0;
       while($lasfac = asignar_a($repbuscamfactura,NULL,PGSQL_ASSOC)){
	   $numfact++;	   
	   $lasfacid = "factid$numfact";
	   $lasfacome = "comen$numfact";
	   $valoridf = "$lasfac[numero_factura]@$lafactid";	   
	   $totalfact = $totalfact + $lasfac[total];
	?>	             
	<tr>
        <td class="tdcampos"><?php echo $lasfac['numero_factura'];?></td>      				    
        <td class="tdcampos"><?php echo $lasfac['fecha_emision'];?></td>      				    
        <td class="tdcampos"><?php echo montos_print($lasfac[total]);?></td>      
       <?php if ($estavalija == 4){?>  				    
        <td class="tdcampos"><?php echo "<input type=text id='$lasfacome' class=\"campos\" size=25>";?></td>      				    
       <?php }?>   
        <td class="tdcampos"><?php echo "<input type=\"checkbox\" id=\"$lasfacid\" value=\"$valoridf\" checked>";?></td>      				    
        
   </tr>
   <?php }?>
    <input type=hidden id='factutotal' value='<?php echo $numfact?>'>   
    
    <tr>
        <td class="tdcampos"></td>      				    
        <td class="tdcampos">Monto Total Factura</td>      				    
        <td class="tdcampos"><?php echo montos_print($totalfact);?></td>      				    
        <td class="tdcampos"></td>      				    
        
   </tr>
</table>              
<hr>
<input type=hidden id='estadofacvalija' value='<?php echo $estavalija?>'>  
<?php if ($estavalija == 1){?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
    <td class="tdtitulos" colspan="1">Comentario:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=4 id="comentvalija" class="campos" ></TEXTAREA></td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="despachopedido" title="Cambiar Estado de Valija" class="boton" style="cursor:pointer" onclick="CEstadoValija()" >Cambiar Estado</label>
   </td>
      
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="Psalir" >Salir</label>
   </td>   
</tr>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="findevalija"></div>
<?php }?>
<?php if ($estavalija == 2){?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
    <td class="tdtitulos" colspan="1">Recibida Por:</td>
	<td class="tdcampos"  colspan="1"><input type="text" id="recibidapor" class="campos" size="35"></td>
</tr>

<tr>
    <td class="tdtitulos" colspan="1">Comentario:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=4 id="comentvalija" class="campos" ></TEXTAREA></td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="despachopedido" title="Cambiar Estado de Valija" class="boton" style="cursor:pointer" onclick="CEstadoValija()" >Cambiar Estado</label>
   </td>
      
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="Psalir" >Salir</label>
   </td>   
</tr>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="findevalija"></div>
<?php }
?>

<?php if ($estavalija == 3){?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>   
<tr>
    <td class="tdtitulos" colspan="1">Entregada Por:</td>
	<td class="tdcampos"  colspan="1"><input type="text" id="entregdpor" class="campos" size="35"></td>
</tr> 
<tr>
    <td class="tdtitulos" colspan="1">Recibida Por:</td>
	<td class="tdcampos"  colspan="1"><input type="text" id="recibidapor" class="campos" size="35"></td>
</tr>

<tr>
    <td class="tdtitulos" colspan="1">Comentario:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=4 id="comentvalija" class="campos" ></TEXTAREA></td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="despachopedido" title="Cambiar Estado de Valija" class="boton" style="cursor:pointer" onclick="CEstadoValija()" >Cambiar Estado</label>
   </td>
      
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="Psalir" >Salir</label>
   </td>   
</tr>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="findevalija"></div>
<?php }
?>

<?php if ($estavalija == 4){?>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
    <td class="tdtitulos" colspan="1">Comentario:</td>
	<td class="tdcampos"  colspan="1"><TEXTAREA COLS=65 ROWS=4 id="comentvalija" class="campos" ></TEXTAREA></td>
</tr>
</table>
<table class="tabla_citas"  cellpadding=0 cellspacing=0>    
<tr>
<br>
   <td><label id="despachopedido" title="Cambiar Estado de Valija" class="boton" style="cursor:pointer" onclick="CEstadoValija()" >Cambiar Estado</label>
   </td>
      
  <td><label title="Salir del proceso" class="boton" style="cursor:pointer" onclick="Psalir" >Salir</label>
   </td>   
</tr>
<div align=center> 
    <img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" />  
</div>
<div id="findevalija"></div>
<?php }?>

<?php }?>
