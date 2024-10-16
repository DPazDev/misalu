<?php
include ("../../lib/jfunciones.php");
sesion();
$admin= $_SESSION['id_usuario_'.empresa];
$fecha_emision=date("Y-m-d");

//busco las series.
$r_serie=("select * from tbl_series,admin where tbl_series.id_sucursal=admin.id_sucursal and admin.id_admin='$admin'");
$f_serie=ejecutar($r_serie) or mensaje(ERROR_BD);
$f_series=asignar_a($f_serie);
/* **** busco las factura para saber cual es la ultima**** */
$q_factura="select * from tbl_recibo_pago where tbl_recibo_pago.id_serie=$f_series[id_serie] order by tbl_recibo_pago.id_recibo_pago desc limit 1;";
$r_factura=ejecutar($q_factura);

if(num_filas($r_factura)==0){
	$no_factura="0001";
   
}else{
	$f_factura=asignar_a($r_factura);
	$no_factura=(int)$f_factura[numero_recibo];
	if($no_factura<=10){
		$no_factura++;
		if($no_factura==10)	$no_factura="00$no_factura";
		else			$no_factura="000$no_factura";
	}else if($no_factura>10 && $no_factura<=100){
                $no_factura++;
		if($no_factura==100)    $no_factura="0$no_factura";
		else                    $no_factura="00$no_factura";				
	}else if($no_factura>100 && $no_factura<=1000){
		$no_factura++;
		if($no_factura==1000)     $no_factura="$no_factura";                 
		else                      $no_factura="0$no_factura";
	}else{
		$no_factura++;
	}
}

	?>
    <table class="tabla_cabecera3"  cellpadding=0 cellspacing=0>

<tr><td colspan=4  class="titulo_seccion">Ingresar Datos del Recibo de Pago</td> 
</tr>
<tr>
<td  class="tdtitulos">No. de Recibo</td>
<td  class="tdcampos"><input type="hidden" id="num_rec_pago" name="num_rec_pago" value="<?php echo $no_factura; ?>"><?php echo $no_factura; ?></td>
<td class="tdtitulos">Serie</td>
<td class="tdcampos"><input type="hidden" id="serie" name="serie" value="<?php echo $f_series[id_serie]; ?>">
<input type="hidden" id="ult_controlfactura" name="ult_controlfactura" value="1">
<input class="campos" type="hidden" id="controlfactura" name="controlfactura" value="2">
<?php echo $f_series[nomenclatura]; ?></td>
<tr> 
<td class="tdtitulos">Forma de pago</td>
	<td class="tdcampos">
	 <?php
                //busco los tipos de pagos.
                $q_tipo_pago="select * from tbl_tipos_pagos order by tbl_tipos_pagos.tipo_pago";
                $r_tipo_pago=ejecutar($q_tipo_pago);
	

	?>
		 <select id="forma_pago" name="forma_pago" class="campos" OnChange="buscar_tarjetas(2);">
<option value="0">Seleccione la Forma de Pago</option>";
               
         <?php 
                        while($f_tipo_pago=asignar_a($r_tipo_pago)){
			?>
                                <option value="<?php echo $f_tipo_pago[id_tipo_pago]?>">
<?php echo $f_tipo_pago[tipo_pago]?></option>";
                        <?php
			}
			?>

         </select>
		</td> 
		<td  class="tdtitulos">* Fecha Emision   </td>
		<td  class="tdcampos">
 <input type="hidden" size="10" id="dateField3" name="dateField3" class="campos" maxlength="10" value="<?php echo $fecha_emision?>" > <?php echo $fecha_emision?>
		</td>
				
	</tr>
	<tr> 
	<td  class="tdtitulos">   </td>
	<td  class="tdtitulos">  </td>
		<td  class="tdtitulos">* Fecha Efectiva de Pago   </td>
		<td>
		<input type="text" size="10" id="fechaefectivap" name="fechaefectivap" class="campos" maxlength="10" value="<?php echo $fecha_emision?>" >
		</td>
	</tr>
	<tr>
	<td colspan=4 height=20 class="titulos"><hr></td>
	</tr>
	<tr>
	
		</tr>
	<tr>
	<td colspan=4 height=20 class="titulos"><hr></td>
	</tr>
	</table>

	<div id="buscar_tarjetas"></div>
