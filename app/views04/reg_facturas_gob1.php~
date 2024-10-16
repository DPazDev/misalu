<?php
include ("../../lib/jfunciones.php");
sesion();
header('Content-Type: text/xml; charset=ISO-8859-1');

list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
list($entes,$nom_ente)=explode("@",$_REQUEST['entes']);
$dateField1 = $_REQUEST['dateField1'];
$dateField2 = $_REQUEST['dateField2'];
$admin= $_SESSION['id_usuario_'.empresa];
$sucursal = $_REQUEST['sucursal'];
$servicios = $_REQUEST['servicios'];
$fecha_emision=date("Y-m-d");


if ($sucursal=='*'){
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal>'0'";
}
else
{
$sucursal1="and procesos.id_admin=admin.id_admin and admin.id_sucursal='$sucursal'";

}

if ($servicios=='*'){
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio>'0'";
}
else
{
$servicios1="and procesos.id_proceso=gastos_t_b.id_proceso and gastos_t_b.id_servicio='$servicios'";

}


//busco las series.
$r_serie=("select * from tbl_series,admin where tbl_series.id_sucursal=admin.id_sucursal and admin.id_admin='$admin'");
$f_serie=ejecutar($r_serie) or mensaje(ERROR_BD);
$f_series=asignar_a($f_serie);
/* **** busco las factura para saber cual es la ultima**** */
$q_factura="select * from tbl_facturas where tbl_facturas.id_serie=$f_series[id_serie] order by tbl_facturas.id_factura desc limit 1;";
$r_factura=ejecutar($q_factura);

if(num_filas($r_factura)==0){
	$no_factura="0001";
}else{
	$f_factura=asignar_a($r_factura);
	$no_factura=(int)$f_factura[numero_factura];
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

/* comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/

/* comparo si el servicio orden de atencion */
if ($servicios==4) {
$q= "select  
                                    procesos.id_proceso,
                                    count(gastos_t_b.id_proceso) 
                            from 
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin 
                            where 
                                    procesos.id_proceso=gastos_t_b.id_proceso and 
                                    gastos_t_b.id_servicio=$servicios and
                                    gastos_t_b.fecha_cita>='$dateField1 ' and 
                                    gastos_t_b.fecha_cita<='$dateField2' and 
                                    procesos.id_titular=titulares.id_titular and 
                                    titulares.id_ente=entes.id_ente and 
                                    entes.id_tipo_ente=$tipo_ente and 
                                    procesos.id_admin=admin.id_admin and 
                                    admin.id_sucursal=$sucursal and 
                                    (procesos.id_estado_proceso=7 or 
                                    procesos.id_estado_proceso=2 or 
                                    procesos.id_estado_proceso=10 or 
                                    procesos.id_estado_proceso=11 or 
                                    procesos.id_estado_proceso=15 or 
                                    procesos.id_estado_proceso=16 ) 
                            group by 
                                    procesos.id_proceso";
                }
                
/* comparo si el servicio es de emergencia o hospitalizacion */
if ($servicios==6 || $servicios==9) {
    
$q= "select  
                                    procesos.nu_planilla,
                                    count(procesos.nu_planilla) 
                            from 
                                    gastos_t_b,
                                    procesos,
                                    titulares,
                                    entes,
                                    admin 
                            where 
                                    procesos.id_proceso=gastos_t_b.id_proceso and 
                                    gastos_t_b.id_servicio=$servicios and
                                    procesos.fecha_recibido>='$dateField1 ' and 
                                    procesos.fecha_recibido<='$dateField2' and 
                                    procesos.id_titular=titulares.id_titular and 
                                    titulares.id_ente=entes.id_ente and 
                                    entes.id_tipo_ente=$tipo_ente and 
                                    procesos.id_admin=admin.id_admin and 
                                    admin.id_sucursal=$sucursal and 
                                    (procesos.id_estado_proceso=7 or 
                                    procesos.id_estado_proceso=2 or 
                                    procesos.id_estado_proceso=10 or 
                                    procesos.id_estado_proceso=11 or 
                                    procesos.id_estado_proceso=15 or 
                                    procesos.id_estado_proceso=16 ) 
                            group by 
                                    procesos.nu_planilla";
                }
/*fin de  comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/
/*fin de  comienzo harcer las comparaciones para ver que servicio se va a facturar y hacer su busquedad*/
$r_procesos = ejecutar($q);
	if(num_filas($r_procesos)==0){
	?>
    <table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">No Existen </td> 
</tr>
</table>
	<?php
	}
	
	else
	{
       ?>
<table class="tabla_cabecera5"  cellpadding=0 cellspacing=0>
<tr>
<td  colspan=4  class="titulo_seccion">Numeros de Procesos</td> 
</tr>

<tr>
<td colspan=4 class="tdtitulos"><hr></td></tr>

<?php
	//Busco los procesos que estan afiliados a la clave.
	pg_result_seek($r_procesos,0);
    
    if ($servicios==4) {
	while($f_proceso = asignar_a($r_procesos)){
		$subtotal=0;
		?>
		<tr>
		<td class="tdcamposr" colspan=1 >Numero Proceso</td>
		<td class="tdcamposr" colspan=1 ><?php echo $f_proceso[id_proceso] ?></td>
		</tr>
	<tr>
	<td class="tdtitulos" colspan=2 >CONCEPTO O DESCRIPCION	</td>
	<td class="tdtitulos" colspan=2 >Bs.S.</td>
</tr>
		
		
		<?php
		$q_proceso = "select
                                        gastos_t_b.*, 
                                        procesos.* 
                                from
                                        gastos_t_b,procesos 
                                where 
                                        procesos.id_proceso=gastos_t_b.id_proceso and
                                        procesos.id_proceso=$f_proceso[id_proceso]";
		$r_proceso = ejecutar($q_proceso);
		if(num_filas($r_proceso)>0){
		while($f = asignar_a($r_proceso)){
		$subtotal=$subtotal + $f[monto_aceptado];
		$total=$total + $f[monto_aceptado];
	?>
		<tr>
		<td class="tdcampos" colspan=2 ><?php echo $f[descripcion] ?> &nbsp;&nbsp; <?php $f[nombre]?></td>
		<td class="tdcampos" colspan=1  valign="bottom"><?php echo montos_print($f[monto_aceptado])?></td>
		</tr>

		<?php
		}
        }
        }
		?>	
        <tr>
		<td class="tdtitulos" colspan=1 ></td>
		<td class="tdtitulos" colspan=1 >Sud Total</td>
		<td class="tdtitulos" colspan=1  valign="bottom"><?php echo montos_print($subtotal)?></td>
		</tr>

	
		<?php
		
	}
    
    ?>
        
        <?php
	//Busco los procesos que estan afiliados a la clave.
if ($servicios==6 || $servicios==9) {
	while($f_proceso = asignar_a($r_procesos)){
		$subtotal=0;
		?>
		<tr>
		<td class="tdcamposr" colspan=1 >Numero Planilla</td>
		<td class="tdcamposr" colspan=1 ><?php echo $f_proceso[nu_planilla] ?></td>
		</tr>
	<tr>
	<td class="tdtitulos" colspan=2 >CONCEPTO O DESCRIPCION	</td>
	<td class="tdtitulos" colspan=2 >Bs.S.</td>
</tr>
		
		
		<?php
		$q_proceso = "select
                                        gastos_t_b.*, 
                                        procesos.* 
                                from
                                        gastos_t_b,procesos 
                                where 
                                        procesos.id_proceso=gastos_t_b.id_proceso and
                                        procesos.nu_planilla='$f_proceso[nu_planilla]'";
		$r_proceso = ejecutar($q_proceso);
		if(num_filas($r_proceso)>0){
		while($f = asignar_a($r_proceso)){
		$subtotal=$subtotal + $f[monto_aceptado];
		$total=$total + $f[monto_aceptado];
	?>
		<tr>
		<td class="tdcampos" colspan=2 ><?php echo $f[descripcion] ?> &nbsp;&nbsp; <?php $f[nombre]?></td>
		<td class="tdcampos" colspan=1  valign="bottom"><?php echo montos_print($f[monto_aceptado])?></td>
		</tr>

		<?php
		}
        }
        }
		?>	
		
        
        
		<tr>
		<td class="tdtitulos" colspan=1 ></td>
		<td class="tdtitulos" colspan=1 >Sud Total</td>
		<td class="tdtitulos" colspan=1  valign="bottom"><?php echo montos_print($subtotal)?></td>
		</tr>

	
		<?php
		
	}
    
    ?>
    
    
    <?
}
if(num_filas($r_procesos)>0){
?>
<tr>
<td  class="tdtitulos"></td>
<td  class="tdcamposr">Total BF.</td>
<td colspan=2 class="tdcamposr"><input type="hidden" id="monto" name="monto" class="campos" size=20 value="<?php echo $total?>"><?php echo montos_print($total);?></td> 
</tr>
<tr><td colspan=4  class="titulo_seccion">Ingresar Datos de la Factura</td> 
</tr>
<tr>
<td  class="tdtitulos">No. de Factura</td>
<td  class="tdcampos"><input type="hidden" id="factura" name="factura" value="<?php echo $no_factura; ?>"><?php echo $no_factura; ?></td>
<td class="tdtitulos">Serie</td>
<td class="tdcampos"><input type="hidden" id="serie" name="serie" value="<?php echo $f_series[id_serie]; ?>"><?php echo $f_series[nomenclatura]; ?></td>
<tr> 
<td  class="tdtitulos">* No. de Control Factura</td>
<td  class="tdcampos"><input class="campos" type="text" id="controlfactura" name="controlfactura" value=""></td>
		<td  class="tdtitulos">* Fecha Emision   </td>
		<td>
 <input readonly type="text" size="10" id="dateField3" name="fechar" class="campos" maxlength="10" value="" > 
<a href="javascript:void(0);" onclick="g_Calendar.show(event, 'dateField3', 'yyyy-mm-dd')" title="Show popup calendar">
<img src="../public/images/calendar.gif" class="cp_img" alt="Seleccione la Fecha"></a>
                </td>
				
	</tr>
	<tr>
	<td colspan=4 height=20 class="titulos"><hr></td>
	</tr>
	<tr>
	<td class="tdtitulos">Forma de pago</td>
	<td class="tdcampos">
	 <?php
                //busco los tipos de pagos.
                $q_tipo_pago="select * from tbl_tipos_pagos order by tbl_tipos_pagos.tipo_pago";
                $r_tipo_pago=ejecutar($q_tipo_pago);
	

	?>
		 <select id="forma_pago" name="forma_pago" class="campos" OnChange="buscar_tarjetas(3);">
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
		</tr>
	<tr>
	<td colspan=4 height=20 class="titulos"><hr></td>
	</tr>
	</table>

	<div id="buscar_tarjetas"></div>

<?php
}
?>



