<?php
include ("../../lib/jfunciones.php");
sesion();

$admin= $_SESSION['id_usuario_'.empresa];

$fechaini=$_REQUEST[dateField1];
$fechafin=$_REQUEST[dateField2];
$forma_pago=$_REQUEST[forma_pago];
$sucursal=$_REQUEST[sucursal];
$servicio=$_REQUEST[servicios];
$num_cheque=$_REQUEST[num_cheque];
$carterv = strtoupper($_REQUEST[clavcar]);
$direccvalija = $_REQUEST[direcvalija];

if(!empty($carterv)){
   if($direccvalija == '1'){
	   $andclave = "and tbl_procesos_claves.no_clave like('$carterv%')"; 
   }else{
	      if($direccvalija == '2'){
	         $andclave = "and tbl_procesos_claves.no_clave like('%$carterv')"; 
           }else{
			     $andclave = "and tbl_procesos_claves.no_clave like('%$carterv%')"; 
			   }  
	   
	   }

}


	/* **** verifico de tiene activado el permiso de Modificar facturas de edo por cobrar a pagadas **** */
$q_mod_edo=("select * from permisos where permisos.id_admin='$admin' and permisos.id_modulo=12");
$r_mod_edo=ejecutar($q_mod_edo);
$f_mod_edo=asignar_a($r_mod_edo);

if ($num_cheque>0){
    $andnumcheque="and tbl_facturas.numero_cheque='$num_cheque' ";
    }
    else
    {
         $andnumcheque="";
        }
		
list($ente,$nom_ente)=explode("@",$_REQUEST['ente']);
list($tipo_ente,$nom_tipo_ente)=explode("@",$_REQUEST['tipo_ente']);
if  ($servicio==0){
	$servicios="and gastos_t_b.id_servicio<>12";
    $serviciosp="and g.id_servicio<>12";
	$servicios1="servicios.id_servicio<>12";
	}
	else
	{
		$servicios="and gastos_t_b.id_servicio=$servicio";
        $serviciosp="and g.id_servicio=$servicio";
		$servicios1="servicios.id_servicio=$servicio";
    }

if ($forma_pago==0){
	$tipo_pago="and tbl_facturas.id_estado_factura>=0";
	}
if ($forma_pago=='*'){
	$tipo_pago="and tbl_facturas.id_estado_factura>=1 and tbl_facturas.id_estado_factura<=2";
	
	}
	if ($forma_pago>0){
	$tipo_pago="and tbl_facturas.id_estado_factura=$forma_pago";
	}
	
	
	
if ($ente==0)
{
	$elente="and entes.id_ente>=$ente"; 
    $elentep="e.id_ente>=$ente and";
	}
	else
	{
		$elente="and entes.id_ente=$ente";
        $elentep="e.id_ente=$ente and";
		}
        
        if ($ente=='*')
{
    $elente="and entes.id_ente!=53";
    }
	
	if ($tipo_ente==0)
{
	$eltipo_ente="tbl_tipos_entes.id_tipo_ente>=$tipo_ente"; 
	}
	else
	{

		$eltipo_ente="tbl_tipos_entes.id_tipo_ente=$tipo_ente";
     }
        
       
        
		if ($sucursal==0)
{
	$id_serie="and tbl_series.id_serie>0";
    $id_seriep="and ts.id_serie>0";
	}
	else
	{
	$id_serie="and tbl_series.id_serie=$sucursal";
    $id_seriep="and ts.id_serie=$sucursal";
		}
?>





<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>


    <tr>
   
      <?php 
$r_ente=pg_query("select  * from entes where id_ente=$ente");
($f_ente=pg_fetch_array($r_ente, NULL, PGSQL_ASSOC))
?>
       
    </tr>
   
     
   <tr>
  <td height="21" colspan="12" class="titulo_seccion"><div align="center"><strong>
  Monto de Facturas  Sin Proceso de Auditoria</tr>
    
   	 <tr>
    <td  class="tdcamposc" >Serie</td>
    <td  class="tdcamposc" >Factura</td>
	 <td  class="tdcamposc" >Fecha Emision</td>
	<td  class="tdcamposc" >Ente</td> 
    <td  class="tdcamposc" >Monto</td>
	</tr>

 <?php
 if ($forma_pago==4){
    
     }
     else
     {
        
 $r_factura= pg_query("select 
                                            tbl_facturas.numero_factura,
											tbl_facturas.id_factura,
											tbl_facturas.fecha_emision,
											tbl_facturas.id_estado_factura,
											tbl_series.nomenclatura,
                                            tbl_facturas.id_serie,
											entes.nombre,
                                            sum(tbl_procesos_claves.monto),
											sum(tbl_procesos_claves.fac_deducible) as sum_deducible
                                    from 
                                            tbl_facturas,
                                            tbl_procesos_claves,
                                            procesos,
                                            titulares,
                                            entes,
											tbl_tipos_entes,
                                            tbl_series
                                    where 
                                            tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
                                            tbl_facturas.id_serie=tbl_series.id_serie
                                            $id_serie and
                                            $andclave
                                            tbl_facturas.fecha_emision>='$fechaini' and 
                                            tbl_facturas.fecha_emision<='$fechafin' and 
                                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                                            procesos.id_titular=titulares.id_titular and 
                                            titulares.id_ente=entes.id_ente 
                                            $elente and
					entes.id_tipo_ente=tbl_tipos_entes.id_tipo_ente and
                                            $eltipo_ente 
                                            $tipo_pago  
											$andnumcheque
                                    group by 
											tbl_facturas.numero_factura,
											tbl_facturas.id_factura,
											tbl_facturas.fecha_emision,
											tbl_facturas.id_estado_factura,
                                            tbl_series.nomenclatura,
                                            tbl_series.nombre,
                                            tbl_facturas.id_serie,
											entes.nombre
                                    order by 
                                            tbl_series.nomenclatura,
											tbl_facturas.numero_factura");
            //echo $r_factura; 
}
?>



   
    <?php   
    
  
	$i=0;
while($f_factura=pg_fetch_array($r_factura, NULL, PGSQL_ASSOC)) 
{
	$i++;

$lafacturaes=$f_factura['id_factura'];
	
$montonoa=("select sum(cast(gastos_t_b.monto_reserva as double precision)) as noaprobado,gastos_t_b.descripcion from 
gastos_t_b,tbl_procesos_claves 
where 
tbl_procesos_claves.id_factura='$lafacturaes' and
gastos_t_b.id_proceso=tbl_procesos_claves.id_proceso and 
gastos_t_b.monto_aceptado>'0' and 
(gastos_t_b.id_tipo_servicio=27 or gastos_t_b.id_tipo_servicio=28) group by gastos_t_b.descripcion; ");

$montonoasql=ejecutar($montonoa);

 if ($cuantosnap=num_filas($montonoasql)>0)
			{
				$r_montonoa=asignar_a($montonoasql);
            $noaprobado=$r_montonoa['noaprobado'];
			
			}
	else
			{	
			$noaprobado=0;
            		   	
			}



 if ($f_factura[id_estado_factura]==3)
			{
				$montofactura=0;
			}
			else
			{
			  $montofactura=($f_factura[sum] - $f_factura[sum_deducible] - $noaprobado ) ;
			}
	
    $totalmontpag1=$totalmontpag1 + $montofactura;
    
?>
  	 <tr>
    <td  class="tdcamposc" ><?php  echo $f_factura[nomenclatura];?></td>
     <td  class="tdcamposc" ><?php 
		if ($forma_pago<>'2'){
			
			echo "00$f_factura[numero_factura]";
			}
			else
			{
				 echo "00$f_factura[numero_factura]";
				 
					if ($f_mod_edo[permiso]=='1'){ 
		?>
<input class="campos" type="hidden" id="idfactura_<?php echo $i?>" name="idfactura_" maxlength=128 size=20 value="<?php echo $f_factura[id_factura]?>">
<input class="campos" type="hidden" id="honorarios_<?php echo $i?>" name="honorarios_" maxlength=128 size=20 value="<?php echo $montofactura?>">
<input class="campos" type="checkbox" <?php echo $ban ?> id="check_<?php echo $i?>"name="checkl" maxlength=128 size=20 value="" OnClick="sumar(this);"> 
		
		<?php
			}
			}
		?>
	</td>
	  <td  class="tdcamposc" ><?php  echo $f_factura[fecha_emision];?></td> 
	  <td  class="tdcamposc" ><?php  echo $f_factura[nombre];?></td>   
    <td  class="tdcamposc" ><?php  echo montos_print($montofactura);?></td>
	</tr>
	
	<tr>
    <td  colspan=9 class="tdcamposcc"  ><hr></hr></td>
	</tr>
    	
<?php
}
echo "<input type=\"hidden\" id=\"conexa\" name=\"conexa\" value=\"$i\">";
?>
<tr>

<td  class="tdcamposc" ></td>
<td  class="tdcamposc" ></td>
  <td  class="tdcamposc" ></td>
  <td  class="tdcamposc">Total</td>
	<td class="tdcamposc" ><?php echo  montos_print($totalmontpag1)?></td>
</tr>
   </table>
 <?php 
		if ($f_mod_edo[permiso]=='1' and $forma_pago=='2'){
		?>
		
		<table   border=0 class="tabla_citas"  cellpadding=0 cellspacing=0>
    <tr>
		<td colspan=12 align="center" class="titulo_seccion">Datos Para Actualizar Facturas a Pagadas</td>
	</tr>
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
		
		<input class="campos" type="text" disabled id="monto" name="monto" maxlength=128 size=20 value="0"  OnChange="return validarNumero(this);"  >
		
		</td>
		
	</tr>
	<tr>
	<td class="tdtitulos"  colspan=3 align="left">Estado de Factura</td>
	<td class="titulos" colspan=2 align="left">
		<select id="estado_fac" name="estado_fac" class="campos" style="width: 70px;"  <?php echo $desactivar_2; ?>>
		<option value="1" <?php if($f_factura['id_estado_factura']==1) echo "selected"; ?>>Pagada</option>
	</select>
	</td>
	<td class="titulos" colspan=2 align="left">
        <a href="#"  OnClick="mod_edo_fact_rel();" title="Modifica el Estado de La Factura" class="boton" title="Modifica el Estado de La Factura"> Actualizar
        </a>
		</td>
		
	</tr>
    
  </table>
	<?php 
		}
	?> 
