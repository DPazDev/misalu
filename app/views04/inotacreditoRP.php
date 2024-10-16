<?php
include ("../../lib/jfunciones.php");
sesion();


$no_factura=$_REQUEST['id_factura'];
$num_notacredito=$_REQUEST['num_notacredito'];
$id_admin= $_SESSION['id_usuario_'.empresa];
//buscamos los datos de la factura
$q_factura=("select * 
                    from 
                            tbl_facturas,
                            tbl_notacredito,
                            tbl_series
                    where 
                            tbl_facturas.id_factura='$no_factura' and 
                            tbl_facturas.id_factura=tbl_notacredito.id_factura and 
                            tbl_notacredito.num_notacredito='$num_notacredito' and 
                            tbl_facturas.id_serie=tbl_series.id_serie
		      ;");

$r_factura=ejecutar($q_factura);
//if(num_filas($r_factura)==0)	mensaje("No existe una factura con esos parámetros.");
$f_factura=asignar_a($r_factura);

if($f_factura['condicion_pago']==2 && !empty($f_factura['fecha_credito'])){
	list($ano_c,$mes_c,$dia_c)=explode("-",$f_factura['fecha_credito']);
	$fecha_credito="<td align=\"right\" width=\"150\">A $dia_c DIAS</td>";
}

$q_cliente=("select  
                            clientes.*,
                            titulares.*,
                            entes.*,
                            tbl_procesos_claves.*, 
                            procesos.* 
                    from 
                            clientes,
                            titulares,
                            entes,
                            tbl_procesos_claves,
                            procesos 
                    where 
                            tbl_procesos_claves.id_proceso=procesos.id_proceso and 
                            tbl_procesos_claves.id_factura=$f_factura[id_factura] and 
                            procesos.id_titular=titulares.id_titular and 
                            titulares.id_ente=entes.id_ente and 
                            titulares.id_cliente=clientes.id_cliente
;");
		      $r_cliente=ejecutar($q_cliente);
			
			  if(num_filas($r_cliente)==0){
                
                  $q_cliente=("select  
												entes.*,
												tbl_procesos_claves.*,
												tbl_recibo_contrato.*,
												tbl_contratos_entes.*
										from 
												entes,
												tbl_procesos_claves,
												tbl_recibo_contrato,
												tbl_contratos_entes,
												tbl_facturas
										where
												tbl_facturas.id_recibo_contrato=tbl_recibo_contrato.id_recibo_contrato and 
												tbl_facturas.id_factura=tbl_procesos_claves.id_factura and 
												tbl_facturas.id_factura=$f_factura[id_factura] and 
												tbl_recibo_contrato.id_contrato_ente=tbl_contratos_entes.id_contrato_ente and
												tbl_contratos_entes.id_ente=entes.id_ente
										;");
                     $r_cliente=ejecutar($q_cliente);   
                  }
			
		      $f_cliente=asignar_a($r_cliente);


$q_ciudad=("select  ciudad.*,estados.* from ciudad,estados where ciudad.id_ciudad=$f_cliente[id_ciudad] and ciudad.id_estado=estados.id_estado
;");
		      $r_ciudad=ejecutar($q_ciudad);
		      $f_ciudad=asignar_a($r_ciudad);


$q_bene=("select  * from clientes,beneficiarios where clientes.id_cliente=beneficiarios.id_cliente and beneficiarios.id_beneficiario=$f_cliente[id_beneficiario]
;");
                      $r_bene=ejecutar($q_bene);
		      $f_bene=asignar_a($r_bene);

$q_gasto=("select  titulares.*,gastos_t_b.*,tbl_procesos_claves.* from titulares,gastos_t_b,tbl_procesos_claves,procesos where tbl_procesos_claves.id_proceso=procesos.id_proceso and tbl_procesos_claves.id_factura=$f_factura[id_factura] and procesos.id_titular=titulares.id_titular and gastos_t_b.id_proceso=procesos.id_proceso and gastos_t_b.monto_aceptado>'0'
;");
                     $r_gasto=ejecutar($q_gasto);
		      

?>
<html>
<head>
<title></title>
<link HREF="../../public/stylesheets/impresiones.css"   rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="../../public/javascripts/scripts.js"></script>
</head>
<body>
<script language="JavaScript">
<!--
function enviar(){
	if(document.jforma.factura.value.length == 0){
		alert("El número de la factura es obligatorio.");
	}else{
		document.jforma.submit();
	}
}

-->
</script>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>

<div align="center">
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="70%">&nbsp;</td>
		<td width="30%" style="padding-top: 15px;height: 30px;text-align: right;" valign="top"><b> Nota de Credito 00<?php echo $num_notacredito; ?> Afecta la factura 00<?php echo $f_factura[numero_factura]; ?> Serie <?php echo $f_factura[nomenclatura]; ?></b></td>
	</tr>
	
</table>
<br><br>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td align="right">Fecha de Emision:<?php  
		                        list($ano_e,$mes_e,$dia_e)=explode("-",$f_factura['fecha_creado']);
					                        echo "$dia_e/$mes_e/$ano_e";
								
								                ?></td>
	</tr>
</table>

	<?php if ($f_factura[con_ente]>0)
	{?>
	<table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="80%" align="left">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
		 <tr>
		 <td align="left">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="80%" align="left">Direccion: <?php echo $f_cliente[direccion];?></td>
                 
	 </tr>
<tr>
   		<td width="80%" align="left">Telefono: <?php echo $f_cliente[telefonos];?></td>
                 
	 </tr>
 	<tr>
                 <td  colspan=3 align="center">__________________________________________________________________</td>
	 </tr>
	</table> 
	
		<?php
		}
		else
		{
		?>



<?php
if ($f_cliente[no_clave]>'0'){
?>
<table width="100%" cellspacing=0 cellpadding=0 border=0>
        <tr>
	         <td width="80%" align="left">Nombre o razon social: <?php echo $f_cliente[nombre];?>
			</tr>
		 <tr>
		 <td align="left">RIF: <?php echo $f_cliente[rif]; ?></td>
	 </tr>

   	<tr>
   		<td width="80%" align="left">Direccion: <?php echo $f_cliente[direccion];?></td>
                 
	 </tr>
<tr>
   		<td width="80%" align="left">Telefono: <?php echo $f_cliente[telefonos];?></td>
                 
	 </tr>
 	<tr>
                 <td  colspan=3 align="center">__________________________________________________________________</td>
	 </tr>

<tr>
 	<td width="80%" align="left">Numero de Clave: <?php echo $f_cliente[no_clave];?></td>
</tr> 

	 <tr>
		<td width="80%" align="left">Titular: <?php echo "$f_cliente[nombres]  $f_cliente[apellidos]";?></td>
		<td align="right">Cedula <?php echo $f_cliente[cedula] ?></td>                                                               </tr>

<?php
if ($f_cliente[id_beneficiario]>'0') {
?>
	<tr>
                 <td width="80%" align="left">Beneficiario: <?php echo "$f_bene[nombres] $f_bene[apellidos]";?></td>		               
		<td align="right">Cedula <?php echo $f_bene[cedula] ?></td>                                                        
	</tr>

<?php
 }
  else
  {
  }
?>

 	
</table>

<?php
 }
 else
 {
 ?>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="80%" align="left">Nombre o razon social: <?php echo $f_cliente[nombres]; echo $f_cliente[apellidos];?></td>
		<td>&nbsp;</td>
		<td align="right">Cedula/ RIF: <?php echo $f_cliente[cedula]; ?></td>
	</tr>
        <tr>
	         <td colspan=3  width="80%" align="left">Direccion: <?php echo $f_cliente[direccion_hab]?> <?php echo $f_ciudad[ciudad]?>  edo <?php echo $f_ciudad[estado]?>   Telefonos: <?php echo $f_cliente[telefono_hab]?></td>
	</tr>
</table>

<?php
}
}
?>

<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
			<td  align="right">Condicion Pago:<?php 
				if($f_factura['condicion_pago']==1)
					echo "Contado";
				else if($f_factura['condicion_pago']==2)
					echo "Cr&eacute;dito $fecha_credito";	
				else if($f_factura['condicion_pago']==3 || $f_factura['condicion_pago']==4 || $f_factura['condicion_pago']==5 )
					echo "Contado";
		?>
		</td>
		</tr>
</table>



<br><br>
<table width="100%" cellspacing=0 cellpadding=0 border=0 style="border: thin solid black;">
	<tr>
	<td width="70%" align="left">Concepto o Descripcion</td>
	<td width="10%" align="left"></td>
	<td width="20%" align="right">Total Bs.S.</td>
	</tr>
	<?php
	 $total=0;
	
	if  ($f_factura[concepto]<>"") {
		
		while($f_gasto=asignar_a($r_gasto)){
		                 $total= ($total + $f_gasto[monto_aceptado]);
                         
						} 
                        $total= $f_factura[montonc];
				?>
			<tr>
		<td width="70%" align="left"><?php echo "$f_factura[concepto]";  ?></td>
			<td width="10%" align="left"></td>
			
			<td width="20%" align="right"><?php echo montos_print($total);?></td>
		</tr>
		<?php
		
		}
		
		else
		{
			while($f_gasto=asignar_a($r_gasto)){
		                 $total= ($total + $f_gasto[monto_aceptado]);
				?>
			<tr>
		<td width="70%" align="left"><?php echo "$f_gasto[nombre] ($f_gasto[descripcion] )";  ?></td>
			<td width="10%" align="left"></td>
			
			<td width="20%" align="right"><?php echo montos_print($f_gasto[monto_aceptado]);?></td>
		</tr>
	<?php
	}
	}
	?>



<tr>
		<td colspan=3>
		<br><br><br>
		
		</td>
	</tr>
	
</table>
		<table width="100%" cellspacing=0 cellpadding=0 border=0>
<?php 

                                $cantidad=explode(".",$total);
                                $cadenas=count($cantidad);
                                                                 if ($cantidad[1]<=9) {
                                                                        $cero=0;}
                                                                        else
                                                                        {$cero="";}
                                                                $cantidad[1]=substr($cantidad[1],0,2);
                                                                $cantida[1]=substr($cantidad[1],0,1);
                                                                if ($cantida[1]==0){
                                                                        $cero="";
                                                                        }
?>		

	<tr>
				<td  valign="top" align="right">MONTO TOTAL EXENTO O EXONERADO Bs.S. 
    		</td>
			
			
				<td width="200" valign="top" align="right"> <?php echo montos_print($total); echo  $cero;?></td>
			</tr>
				<tr>
				<td  valign="top" align="right">Base imponible segun alicuota_% Bs.S. 
    		</td>
			
			
				<td width="200" valign="top" align="right"> </td>
			</tr>
			</tr>
				<tr>
				<td  valign="top" align="right">Monto Total del impuesto segun alicuota_% Bs.S. 
    		</td>
			
			
				<td width="200" valign="top" align="right"> </td>
			</tr>
			</tr>
				<tr>
				<td  valign="top" align="right">MONTO TOTAL DE LA VENTA Bs.S. 
    		</td>
			
			
				<td width="200" valign="top" align="right"> <?php echo montos_print($total * -1);  echo  $cero;?></td>
			</tr>
			
		</table>		
		<table width="100%" cellspacing=0 cellpadding=0 border=0>
			<tr>
				<td rowspan=2 valign="top" align="left">Son: <?php 
    				
				$cantidad=explode(".",$total);
				$cadenas=count($cantidad);
                                                                 if ($cantidad[1]<=9) {
                                                                        $cero=0;}
                                                                        else
                                                                        {$cero="";}
                                                                $cantidad[1]=substr($cantidad[1],0,2);
                                                                $cantida[1]=substr($cantidad[1],0,1);
                                                                if ($cantida[1]==0){
                                                                        $cero="";
                                                                        }    




				if($cadenas==2){
				        echo ucwords(numtolet($cantidad[0],"os"))." con ".$cantidad[1]."$cero/100 Bolivares.";
			    	}else{
					echo ucwords(numtolet($cantidad[0],"os"))." Bolivares. ";
				$cero="";
			    	} 
	
				//echo numeros_a_letras($total);
	/* **** Se registra lo que hizo el usuario**** */

$log="Imprimio la Factura numero $no_factura";
logs($log,$ip,$id_admin);

/* **** Fin de lo que hizo el usuario **** */
				?></td>
			</tr>
			
			
		</table>		


</div>
</body>
</html>
