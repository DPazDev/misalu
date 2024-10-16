<?php
include ("../../lib/jfunciones.php");
include_once ("../../lib/Excel/reader.php");
sesion();
$fecha=date("Y-m-d");
$hora=date("H:i:s");
$nombredarchivo =$_REQUEST['elarchivo'];
// ExcelFile($filename, $encoding);
$data = new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('CP1251');
$filename = "$nombredarchivo";
$error=0;
if (file_exists("../../files/$filename")) {
    $data->read("../../files/$filename");
} else { 
	$error=1;
	?>
	<br>
   <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">No existe el Archivo: <?php echo "($filename)"?></td>  
     </tr>
</table>
<?php }

?>
<br>
<?php if($error==0){?>
<input type="hidden" value='<?php echo $nombredarchivo?>' id='nombreachivo'>
<table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>
     <tr> 
         <td colspan=4 class="titulo_seccion">Reporte de Facturas por Lote del Archivo: <?echo "($filename)"?></td>  
     </tr>
</table>
 <table class="tabla_citas"  cellpadding=0 cellspacing=0 border=1> 
     <tr>
		 <th class="tdtitulos">Num. factura</th>  
         <th class="tdtitulos">Num. Cheque / transf</th>
         <th class="tdtitulos">Fecha de Pago</th>
         <th class="tdtitulos">Tipos de Pago</th>
         <th class="tdtitulos">Monto Pagar</th> 
		 <th class="tdtitulos">ISLR</th>  
		 <th class="tdtitulos">Monto Neto</th> 
		 <th class="tdtitulos">Serie</th> 
		 
    </tr>	
<?php 
   $elmontofa=0;
   for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
	for ($j = 2; $j <= $data->sheets[0]['numCols']; $j++) {		
		        $nfactura=$data->sheets[0]['cells'][$i][1];	
		        $chetrafer=$data->sheets[0]['cells'][$i][2];
                $fechapago=$data->sheets[0]['cells'][$i][3];
                list($eldiaf,$elmesf,$elanof) = explode("/",$fechapago);
                $eldia = substr($eldiaf,0,2);
                $elmes = substr($elmesf,0,2);
                $elano = substr($elanof,0,2);
                $diadpago = "20$elano/$elmes/$eldia";
                $tippago=$data->sheets[0]['cells'][$i][4];
                $montopagar=$data->sheets[0]['cells'][$i][5];
                $impslr=$data->sheets[0]['cells'][$i][6];
                $montoneto=$data->sheets[0]['cells'][$i][7];
                $monto= str_replace(".",'.',$montoneto);
                $seriefact= strtoupper($data->sheets[0]['cells'][$i][8]);
                
	} 
	if(!empty($nfactura)){
	  $buscaridfactura =  ("select tbl_series.id_serie from tbl_series where nomenclatura = '$seriefact'");
      $rbuscarid       =  ejecutar($buscaridfactura);
      $dataidfactura   =  assoc_a($rbuscarid);
      $laiddefactura =  $dataidfactura[id_serie];
      
      $bfactura="select 
									tbl_facturas.id_factura,tbl_facturas.id_estado_factura  
							from
							        tbl_facturas  									
							where 
									tbl_facturas.id_serie 	='$laiddefactura' and
									tbl_facturas.numero_factura 	 = '$nfactura'";
									
									
	$refactura=ejecutar($bfactura);
	$datfactura=assoc_a($refactura);
	$elestadofac=$datfactura['id_estado_factura'];
		if($elestadofac == '1'){
            $colorcolumna ="bgcolor= '#9AFE2E'";
       }else{
	        $colorcolumna ="";	
	   }	
	   
	   $revisonumfactura = substr_count($nfactura, '.');
	   if(empty($nfactura)){
		   $error=1;
		   $mensajeerror = "Error: El Archivo no cumple con las pautas (La columna numero de Factura no puede estar vacia!!)";
	   }
	   if($revisonumfactura >= 1){
		   $error=1;
		   $mensajeerror = "Error: El Archivo no cumple con las pautas (La columna numero de Factura tiene un punto!!)";
	   }
	   if((empty($montopagar)) || (empty($montoneto))){
		   $error=1;
		   $mensajeerror = "Error: El Archivo no cumple con las pautas (La columna Monto a Pagar o Monto Neto no puede estar vacia!!)";
	   }
	   if(empty($seriefact)){
		   $error=1;
		   $mensajeerror = "Error: El Archivo no cumple con las pautas (La columna Serie no puede estar vacia!!)";
	   }	   
	?>
		<tr <?php echo $colorcolumna?>>
		   
	       <td class="tdcampos"><?echo $nfactura;?></td>
		   <td class="tdcampos"><?echo $chetrafer;?></td> 
		   <td class="tdcampos"><?echo "$fechapago";?></td> 
		   <td class="tdcampos"><?echo $tippago;?></td> 
		   <td class="tdcampos"><?echo $montopagar;?></td> 
		   <td class="tdcampos"><?echo $impslr;?></td> 
		   <td class="tdcampos"><? $elmontofa=$elmontofa+$monto; 
		                           echo $monto;?></td> 
		   <td class="tdcampos"><?echo strtoupper($seriefact);?></td> 
       </tr>
		
<?php } 
}
?>
  </table>
  
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <br>
     <tr>
        <td colspan=4 class="titulo_seccion">Datos Para Actualizar Facturas a Pagar</td>  
     </tr>
 
</table>
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
    
     <br>
     <tr>
	<td colspan=3 class="tdtitulos">Forma de pago</td>
	<td colspan=2 class="tdcampos">

<?php 
$q_tipo_pago=("select * from tbl_tipos_pagos  order by tbl_tipos_pagos.tipo_pago");
$r_tipo_pago=ejecutar($q_tipo_pago);
?>
	<select id="forma_pago1" name="forma_pago1" class="campos" style="width: 200px;"  >
<?php
 while($f_tipo_pago = asignar_a($r_tipo_pago)){
?>
		<option value="<?php echo $f_tipo_pago[id_tipo_pago]?>" <?php if($f_factura['condicion_pago']==$f_tipo_pago[id_tipo_pago]) echo "selected"; ?>>
<?php echo $f_tipo_pago[tipo_pago]?></option>
<?php
}
?>
	</select>
	</td>
	<td colspan=2 class="tdtitulos">
	Tipo de Tarjeta
	</td>
	<td colspan=2 class="tdcampos">

<?php
$q_nom_tarjeta=("select * from tbl_nombre_tarjetas  order by tbl_nombre_tarjetas.nombre_tar");
$r_nom_tarjeta=ejecutar($q_nom_tarjeta);
$q_oper_mult=("select * from tbl_oper_multi where tbl_oper_multi.id_factura=$f_factura[id_factura]");
$r_oper_mult=ejecutar($q_oper_mult);
$f_oper_mult = asignar_a($r_oper_mult);


?>

        <select id="nom_tarjeta" name="nom_tarjeta" class="campos" style="width: 200px;"  >
 <option value="0"  <?php if($f_factura['id_nom_tarjeta']==0) echo "selected"; ?>>Nada</option>

<?php

 while($f_nom_tarjeta = asignar_a($r_nom_tarjeta)){
?>
                <option value="<?php echo $f_nom_tarjeta[id_nom_tarjeta]?>"
 <?php if($f_factura['id_nom_tarjeta']==$f_nom_tarjeta[id_nom_tarjeta]) echo "selected"; ?>>
<?php echo $f_nom_tarjeta[nombre_tar]?></option>
<?php
}
?>
        </select>
	</td>
	</tr>
	<tr>	
	<td  colspan=3 class="tdtitulos">*  Fecha de Final de Credito   </td>
	<td  colspan=2 class="tdtitulos">
 <input readonly type="text" size="10" id="dateField5" name="fechac" class="campos" maxlength="10" value="<?php echo $f_factura[fecha_credito]?>"> 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField5', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
	
		
		
		<?php
		//busco los bancos.
		$q_bancos="select * from tbl_bancos order by tbl_bancos.nombanco";
		$r_bancos=ejecutar($q_bancos);
		echo " 
				<td colspan=2 class=\"tdtitulos\">Banco</td>
			<td colspan=2 class=\"tdcampos\">
			<select id=\"banco\" name=\"banco\" class=\"campos\">
			";
			while($f_banco=asignar_a($r_bancos)){
				?><option value="<?php echo $f_banco[id_ban];?>" <?php if(($f_banco[id_ban]==$f_oper_mult[id_banco]) || $f_banco[id_ban]==$f_factura[id_banco] ) echo "selected"; ?>><?php echo $f_banco[nombanco]?></option>
			<?php
			}
		echo "	</select>
			</td>
			</tr>
			<tr>
			<td colspan=3 class=\"tdtitulos\">Num Cheque, tarjeta, Trasnferencia</td>
			<td colspan=2 class=\"tdcampos\"><input type=\"text\" id=\"no_cheque\" name=\"no_cheque\" class=\"campos\" size=20 value=\"$f_factura[numero_cheque]\"></td>
		";
?>
	<td class="tdtitulos"  colspan=2 align="left">Monto Total</td>
	<td class="titulos" colspan=2 align="left">
		
		<input class="campos" type="text" disabled id="monto" name="monto" maxlength=128 size=20 value="<?php echo $elmontofa;?>"  OnChange="return validarNumero(this);"  >
		
		</td>
		
	</tr>
	<tr>
	<td class="tdtitulos"  colspan=3 align="left">Estado de Factura</td>
	<td class="titulos" colspan=2 align="left">
		<select id="estado_fac" name="estado_fac" class="campos" style="width: 70px;"  <?php echo $desactivar_2; ?>>
		<option value="1" <?php if($f_factura['id_estado_factura']==1) echo "selected"; ?>>Pagada</option>
	</select>
	</td>
	
	</tr>
	<?php 
		}
  if($error == 0)	{	
	?>
     <tr>
        <td  title="Procesar Facturas por Lote"> <br><label class="boton" style="cursor:pointer" onclick="FacturarLote1(); return false;" >Facturar</label></td>
        <td> <br><label title="Salir del Proceso"class="boton" style="cursor:pointer" onclick="ira()" >Salir</label></td>
     </tr>
 
</table>
<img alt="spinner" id="spinnerP1" src="../public/images/esperar.gif" style="display:none;" /> 
<br><br>
<?php }else{?>
  <table class="tabla_citas"  cellpadding=0 cellspacing=0>
     <br>
     <tr>
        <td colspan=4 class="titulo_seccion"><?php echo $mensajeerror?></td>  
     </tr>
 
</table>
<?php }?>
